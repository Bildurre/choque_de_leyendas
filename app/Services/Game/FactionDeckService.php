<?php

namespace App\Services\Game;

use App\Models\FactionDeck;
use App\Models\DeckAttributesConfiguration;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class FactionDeckService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];
  protected $deckAttributesConfigurationService;

  /**
   * Constructor
   * 
   * @param DeckAttributesConfigurationService $deckAttributesConfigurationService
   */
  public function __construct(DeckAttributesConfigurationService $deckAttributesConfigurationService)
  {
    $this->deckAttributesConfigurationService = $deckAttributesConfigurationService;
  }

  /**
   * Get all faction decks with optional pagination
   *
   * @param int|null $perPage Number of items per page, or null for all items
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only trashed items
   * @param int|null $factionId Filter by faction ID
   * @param int|null $gameModeId Filter by game mode ID
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getAllFactionDecks(
    int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false,
    ?int $factionId = null,
    ?int $gameModeId = null
  ): mixed
  {
    $query = FactionDeck::with(['faction', 'gameMode'])
      ->withCount(['cards', 'heroes']);
    
    // Apply trash filters
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    // Apply faction filter
    if ($factionId) {
      $query->where('faction_id', $factionId);
    }
    
    // Apply game mode filter
    if ($gameModeId) {
      $query->where('game_mode_id', $gameModeId);
    }
    
    // Default ordering
    $query->orderBy('faction_id')->orderBy('id');
    
    if ($perPage) {
      return $query->paginate($perPage)->withQueryString();
    }
    
    return $query->get();
  }

  /**
   * Get a faction deck with its related cards and heroes
   * 
   * @param FactionDeck $factionDeck
   * @return FactionDeck
   */
  public function getFactionDeckWithRelations(FactionDeck $factionDeck): FactionDeck
  {
    return $factionDeck->load([
      'faction', 
      'gameMode', 
      'cards', 
      'heroes'
    ]);
  }

  /**
   * Create a new faction deck
   *
   * @param array $data
   * @return FactionDeck
   * @throws \Exception
   */
  public function create(array $data): FactionDeck
  {
    try {
      // Start a transaction
      DB::beginTransaction();
      
      $factionDeck = new FactionDeck();
      
      // Process translatable fields
      $data = $this->processTranslatableFields($data, $this->translatableFields);
      
      // Apply translations
      $this->applyTranslations($factionDeck, $data, $this->translatableFields);
      
      // Set faction and game mode
      $factionDeck->faction_id = $data['faction_id'];
      $factionDeck->game_mode_id = $data['game_mode_id'];
      
      // Handle icon upload
      if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
        $factionDeck->storeImage($data['icon'], 'icon');
      }
      
      $factionDeck->save();
      
      // Process cards and heroes
      $this->syncCardsAndHeroes($factionDeck, $data);
      
      // Validate deck against configuration
      $this->validateDeck($factionDeck);
      
      // Commit the transaction
      DB::commit();
      
      return $factionDeck;
    } catch (\Exception $e) {
      // Rollback the transaction in case of error
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Update an existing faction deck
   *
   * @param FactionDeck $factionDeck
   * @param array $data
   * @return FactionDeck
   * @throws \Exception
   */
  public function update(FactionDeck $factionDeck, array $data): FactionDeck
  {
    try {
      // Start a transaction
      DB::beginTransaction();
      
      // Process translatable fields
      $data = $this->processTranslatableFields($data, $this->translatableFields);
      
      // Apply translations
      $this->applyTranslations($factionDeck, $data, $this->translatableFields);
      
      // Update faction and game mode if present
      if (isset($data['faction_id'])) {
        $factionDeck->faction_id = $data['faction_id'];
      }
      
      if (isset($data['game_mode_id'])) {
        $factionDeck->game_mode_id = $data['game_mode_id'];
      }
      
      // Handle icon updates
      if (isset($data['remove_icon']) && $data['remove_icon']) {
        $factionDeck->deleteImage('icon');
      } elseif (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
        $factionDeck->storeImage($data['icon'], 'icon');
      }
      
      $factionDeck->save();
      
      // Process cards and heroes
      $this->syncCardsAndHeroes($factionDeck, $data);
      
      // Validate deck against configuration
      $this->validateDeck($factionDeck);
      
      // Commit the transaction
      DB::commit();
      
      return $factionDeck;
    } catch (\Exception $e) {
      // Rollback the transaction in case of error
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Sync cards and heroes for a faction deck
   * 
   * @param FactionDeck $factionDeck
   * @param array $data
   * @return void
   */
  protected function syncCardsAndHeroes(FactionDeck $factionDeck, array $data): void
  {
    // Process cards
    if (isset($data['cards']) && is_array($data['cards'])) {
      $cardPivot = [];
      
      foreach ($data['cards'] as $cardData) {
        if (isset($cardData['id']) && isset($cardData['copies'])) {
          $cardPivot[$cardData['id']] = ['copies' => $cardData['copies']];
        }
      }
      
      $factionDeck->cards()->sync($cardPivot);
    }
    
    // Process heroes
    if (isset($data['heroes']) && is_array($data['heroes'])) {
      $heroPivot = [];
      
      foreach ($data['heroes'] as $heroData) {
        if (isset($heroData['id']) && isset($heroData['copies'])) {
          $heroPivot[$heroData['id']] = ['copies' => $heroData['copies']];
        }
      }
      
      $factionDeck->heroes()->sync($heroPivot);
    }
  }

  /**
   * Validate a deck against the current configuration
   * 
   * @param FactionDeck $factionDeck
   * @throws \Exception
   */
  protected function validateDeck(FactionDeck $factionDeck): void
  {
    // Get the current configuration
    $config = $this->deckAttributesConfigurationService->getConfiguration();
    
    // Count total cards
    $totalCards = $factionDeck->totalCards;
    
    // Check if any card exceeds the max copies
    $hasExceededCardCopies = $factionDeck->cards()
      ->wherePivot('copies', '>', $config->max_copies_per_card)
      ->exists();
    
    // Check if any hero exceeds the max copies
    $hasExceededHeroCopies = $factionDeck->heroes()
      ->wherePivot('copies', '>', $config->max_copies_per_hero)
      ->exists();
    
    // Validate the deck
    $validationResult = $config->validateDeck(
      $totalCards,
      $hasExceededCardCopies,
      $hasExceededHeroCopies
    );
    
    // If not valid, throw an exception with the errors
    if (!$validationResult['isValid']) {
      throw new \Exception(implode("\n", $validationResult['errors']));
    }
  }

  /**
   * Delete a faction deck (soft delete)
   *
   * @param FactionDeck $factionDeck
   * @return bool
   * @throws \Exception
   */
  public function delete(FactionDeck $factionDeck): bool
  {
    return $factionDeck->delete();
  }

  /**
   * Restore a deleted faction deck
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $factionDeck = FactionDeck::onlyTrashed()->findOrFail($id);
    return $factionDeck->restore();
  }

  /**
   * Force delete a faction deck permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $factionDeck = FactionDeck::onlyTrashed()->findOrFail($id);
    
    // Delete icon if exists
    if ($factionDeck->hasImage('icon')) {
      $factionDeck->deleteImage('icon');
    }
    
    // Detach cards and heroes
    $factionDeck->cards()->detach();
    $factionDeck->heroes()->detach();
    
    return $factionDeck->forceDelete();
  }

  /**
   * Get cards available for a faction deck
   * 
   * @param int $factionId
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getAvailableCards(int $factionId)
  {
    return \App\Models\Card::where('faction_id', $factionId)
      ->orderBy('card_type_id')
      ->orderBy('id')
      ->get();
  }

  /**
   * Get heroes available for a faction deck
   * 
   * @param int $factionId
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getAvailableHeroes(int $factionId)
  {
    return \App\Models\Hero::where('faction_id', $factionId)
      ->orderBy('id')
      ->get();
  }
}