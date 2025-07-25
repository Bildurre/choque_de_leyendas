<?php

namespace App\Services\Content;

use App\Models\Card;
use App\Models\Hero;
use App\Models\Block;
use App\Models\Counter;
use App\Models\Faction;
use App\Models\FactionDeck;
use App\Models\GameMode;

class BlockDataService
{
  /**
   * Get data for blocks that need database queries
   */
  public function getBlockData(Block $block): array
  {
    return match($block->type) {
      'counters-list' => $this->getCounterListData($block),
      'relateds' => $this->getRelatedsData($block),
      'automatic-index' => $this->getAutomaticIndexData($block),
      'game-modes' => $this->getGameModesData($block),
      default => []
    };
  }
  
  /**
   * Get counter list block data
   */
  protected function getCounterListData(Block $block): array
  {
    $counterType = $block->settings['counter_type'] ?? 'boon';
    
    return [
      'counters' => Counter::published()
        ->where('type',$counterType)
        ->orderBy('name')
        ->get(),
      'counterType' => $counterType,
    ];
  }
  
  /**
   * Get relateds block data
   */
  protected function getRelatedsData(Block $block, $currentModel = null): array
  {
    $relatedsContent = $block->getTranslation('content', app()->getLocale());
    $modelType = $block->settings['model_type'] ?? 'hero';
    $displayType = $block->settings['display_type'] ?? 'latest';
    $buttonText = $relatedsContent['button_text'] ?? '';
    
    $modelClass = match($modelType) {
      'faction' => Faction::class,
      'hero' => Hero::class,
      'card' => Card::class,
      'deck' => FactionDeck::class,
      default => Hero::class,
    };
    
    $indexRoute = match($modelType) {
      'faction' => route('public.factions.index'),
      'hero' => route('public.heroes.index'),
      'card' => route('public.cards.index'),
      'deck' => route('public.faction-decks.index'),
      default => route('public.heroes.index'),
    };
    
    $query = $modelClass::published();

    // Auto-detect current model from route parameters
    $currentModel = $this->detectCurrentModel($modelClass);

    // Exclude current model if found
    if ($currentModel) {
      $query->where('id', '!=', $currentModel->id);
    }
    
    // Exclude current model if provided and types match
    if ($currentModel && get_class($currentModel) === $modelClass) {
      $query->where('id', '!=', $currentModel->id);
    }
    
    // Apply display type
    if ($displayType === 'random') {
      $items = $query->inRandomOrder()->limit(6)->get();
    } else {
      $items = $query->latest()->limit(6)->get();
    }
    
    return [
      'items' => $items,
      'modelType' => $modelType,
      'indexRoute' => $indexRoute,
      'buttonText' => $buttonText,
    ];
  }
  
  /**
   * Get automatic index block data
   */
  protected function getAutomaticIndexData(Block $block): array
  {
    $currentPage = \App\Models\Page::find($block->page_id);
    
    $indexableBlocks = $currentPage ? $currentPage->blocks()
      ->where('is_indexable', true)
      ->where('id', '!=', $block->id)
      ->where('order', '>', $block->order)
      ->orderBy('order')
      ->get() : collect();
    
    return [
      'indexableBlocks' => $indexableBlocks,
      'isCompact' => $block->settings['compact'] ?? false,
      'isNumbered' => $block->settings['numbered'] ?? false,
    ];
  }

  /**
   * Get automatic index block data
   */
  protected function getGameModesData(Block $block): array
  {
    return [
      'gameModes' => GameMode::with(['deckConfiguration', 'factionDecks'])->get()
    ];
  }

  /**
   * Detect current model from route parameters
   *
   * @param string $modelClass
   * @return mixed|null
   */
  protected function detectCurrentModel(string $modelClass)
  {
    $route = request()->route();
    
    if (!$route) {
      return null;
    }
    
    // Get all route parameters
    $parameters = $route->parameters();
    
    // Check each parameter to find matching model instance
    foreach ($parameters as $parameter) {
      if (is_object($parameter) && get_class($parameter) === $modelClass) {
        return $parameter;
      }
    }
    
    return null;
  }
}