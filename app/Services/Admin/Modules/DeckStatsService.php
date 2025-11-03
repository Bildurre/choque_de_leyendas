<?php

namespace App\Services\Admin\Modules;

use App\Models\FactionDeck;
use App\Models\GameMode;
use Illuminate\Support\Facades\DB;

class DeckStatsService
{
  /**
   * Get deck statistics
   *
   * @return array
   */
  public function getStats(): array
  {
    $decks = FactionDeck::with(['factions', 'gameMode', 'cards', 'heroes'])->get();
    
    return [
      'summary' => $this->getSummaryStats($decks),
      'by_faction' => $this->getStatsByFaction($decks),
      'by_game_mode' => $this->getStatsByGameMode($decks),
      'size_distribution' => $this->getSizeDistribution($decks),
      'composition' => $this->getCompositionStats($decks),
    ];
  }

  /**
   * Get summary statistics
   *
   * @param \Illuminate\Support\Collection $decks
   * @return array
   */
  protected function getSummaryStats($decks): array
  {
    $publishedDecks = $decks->where('is_published', true);
    
    // Calculate average sizes only for decks that have cards/heroes
    $decksWithCards = $decks->filter(function ($deck) {
      return $deck->total_cards > 0;
    });
    
    $decksWithHeroes = $decks->filter(function ($deck) {
      return $deck->total_heroes > 0;
    });

    return [
      'total_decks' => $decks->count(),
      'published_decks' => $publishedDecks->count(),
      'avg_cards_per_deck' => $decksWithCards->count() > 0 ? round($decksWithCards->avg('total_cards'), 1) : 0,
      'avg_heroes_per_deck' => $decksWithHeroes->count() > 0 ? round($decksWithHeroes->avg('total_heroes'), 1) : 0,
      'game_modes_count' => $decks->pluck('game_mode_id')->unique()->count(),
    ];
  }

  /**
   * Get statistics by faction
   *
   * @param \Illuminate\Support\Collection $decks
   * @return array
   */
  protected function getStatsByFaction($decks): array
  {
    $decksByFaction = $decks->groupBy('faction_id');
    
    return $decksByFaction->map(function ($factionDecks, $factionId) {
      $faction = $factionDecks->first()->faction;
      return [
        'name' => $faction ? $faction->name : 'Sin facción',
        'color' => $faction ? ($faction->color ?? '#808080') : '#808080',
        'count' => $factionDecks->count(),
        'published' => $factionDecks->where('is_published', true)->count(),
        'avg_size' => round($factionDecks->avg('total_cards'), 1),
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get statistics by game mode
   *
   * @param \Illuminate\Support\Collection $decks
   * @return array
   */
  protected function getStatsByGameMode($decks): array
  {
    $decksByGameMode = $decks->groupBy('game_mode_id');
    $totalDecks = $decks->count();
    
    return $decksByGameMode->map(function ($modeDecks, $gameModeId) use ($totalDecks) {
      $gameMode = $modeDecks->first()->gameMode;
      return [
        'name' => $gameMode ? $gameMode->name : 'Sin modo',
        'count' => $modeDecks->count(),
        'published' => $modeDecks->where('is_published', true)->count(),
        'percentage' => $totalDecks > 0 ? round(($modeDecks->count() / $totalDecks) * 100, 1) : 0,
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get deck size distribution
   *
   * @param \Illuminate\Support\Collection $decks
   * @return array
   */
  protected function getSizeDistribution($decks): array
  {
    // Group decks by card count ranges
    $sizeRanges = [
      '0-20' => 0,
      '21-30' => 0,
      '31-40' => 0,
      '41-50' => 0,
      '51-60' => 0,
      '60+' => 0,
    ];

    foreach ($decks as $deck) {
      $totalCards = $deck->total_cards;
      
      if ($totalCards <= 20) {
        $sizeRanges['0-20']++;
      } elseif ($totalCards <= 30) {
        $sizeRanges['21-30']++;
      } elseif ($totalCards <= 40) {
        $sizeRanges['31-40']++;
      } elseif ($totalCards <= 50) {
        $sizeRanges['41-50']++;
      } elseif ($totalCards <= 60) {
        $sizeRanges['51-60']++;
      } else {
        $sizeRanges['60+']++;
      }
    }

    // Convert to array format for charts
    $distribution = [];
    foreach ($sizeRanges as $range => $count) {
      $distribution[] = [
        'range' => $range,
        'count' => $count,
        'percentage' => $decks->count() > 0 ? round(($count / $decks->count()) * 100, 1) : 0,
      ];
    }

    return $distribution;
  }

  /**
   * Get deck composition statistics
   *
   * @param \Illuminate\Support\Collection $decks
   * @return array
   */
  protected function getCompositionStats($decks): array
  {
    // Collect all card type distributions from decks
    $allCardTypes = [];
    $allHeroClasses = [];
    
    foreach ($decks as $deck) {
      // Get card type breakdown
      $cardBreakdown = $deck->getCardCopiesBreakdown();
      foreach ($cardBreakdown as $type => $count) {
        if (!isset($allCardTypes[$type])) {
          $allCardTypes[$type] = 0;
        }
        $allCardTypes[$type] += $count;
      }
      
      // Get hero class breakdown
      $heroBreakdown = $deck->getHeroCopiesByClassBreakdown();
      foreach ($heroBreakdown as $class => $count) {
        if (!isset($allHeroClasses[$class])) {
          $allHeroClasses[$class] = 0;
        }
        $allHeroClasses[$class] += $count;
      }
    }
    
    // Sort and take top entries
    arsort($allCardTypes);
    arsort($allHeroClasses);
    
    return [
      'top_card_types' => array_slice($allCardTypes, 0, 6, true),
      'top_hero_classes' => array_slice($allHeroClasses, 0, 6, true),
    ];
  }
}