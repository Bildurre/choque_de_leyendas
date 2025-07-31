<?php

namespace App\Services\Admin\Modules;

use App\Models\Faction;
use App\Models\Hero;
use App\Models\Card;
use App\Models\FactionDeck;
use Illuminate\Support\Facades\DB;

class FactionStatsService
{
  /**
   * Get faction statistics
   *
   * @return array
   */
  public function getStats(): array
  {
    $factions = Faction::with(['heroes', 'cards', 'factionDecks'])->get();
    
    return [
      'summary' => $this->getSummaryStats($factions),
      'faction_comparison' => $this->getFactionComparison($factions),
      'distribution' => $this->getDistributionStats($factions),
      'top_factions' => $this->getTopFactions($factions),
    ];
  }

  /**
   * Get summary statistics
   *
   * @param \Illuminate\Support\Collection $factions
   * @return array
   */
  protected function getSummaryStats($factions): array
  {
    $heroesPerFaction = $factions->map(function ($faction) {
      return $faction->heroes->count();
    });

    $cardsPerFaction = $factions->map(function ($faction) {
      return $faction->cards->count();
    });

    $decksPerFaction = $factions->map(function ($faction) {
      return $faction->factionDecks->count();
    });

    return [
      'total_factions' => $factions->count(),
      'published_factions' => $factions->where('is_published', true)->count(),
      'avg_heroes_per_faction' => round($heroesPerFaction->avg(), 1),
      'avg_cards_per_faction' => round($cardsPerFaction->avg(), 1),
      'avg_decks_per_faction' => round($decksPerFaction->avg(), 1),
    ];
  }

  /**
   * Get faction comparison data
   *
   * @param \Illuminate\Support\Collection $factions
   * @return array
   */
  protected function getFactionComparison($factions): array
  {
    return $factions->map(function ($faction) {
      return [
        'name' => $faction->name,
        'color' => $faction->color ?? '#808080', // Color por defecto si no tiene
        'heroes_count' => $faction->heroes->count(),
        'cards_count' => $faction->cards->count(),
        'decks_count' => $faction->factionDecks->count(),
        'is_published' => $faction->is_published,
      ];
    })->sortByDesc('cards_count')->values()->toArray();
  }

  /**
   * Get distribution statistics
   *
   * @param \Illuminate\Support\Collection $factions
   * @return array
   */
  protected function getDistributionStats($factions): array
  {
    $publishedFactions = $factions->where('is_published', true);
    $draftFactions = $factions->where('is_published', false);

    return [
      'by_status' => [
        'published' => $publishedFactions->count(),
        'draft' => $draftFactions->count(),
      ],
      'by_content' => [
        'with_heroes' => $factions->filter(function ($faction) {
          return $faction->heroes->count() > 0;
        })->count(),
        'without_heroes' => $factions->filter(function ($faction) {
          return $faction->heroes->count() === 0;
        })->count(),
        'with_cards' => $factions->filter(function ($faction) {
          return $faction->cards->count() > 0;
        })->count(),
        'without_cards' => $factions->filter(function ($faction) {
          return $faction->cards->count() === 0;
        })->count(),
      ],
    ];
  }

  /**
   * Get top factions by different metrics
   *
   * @param \Illuminate\Support\Collection $factions
   * @return array
   */
  protected function getTopFactions($factions): array
  {
    $byHeroes = $factions->sortByDesc(function ($faction) {
      return $faction->heroes->count();
    })->take(3);

    $byCards = $factions->sortByDesc(function ($faction) {
      return $faction->cards->count();
    })->take(3);

    $byDecks = $factions->sortByDesc(function ($faction) {
      return $faction->factionDecks->count();
    })->take(3);

    return [
      'most_heroes' => $byHeroes->map(function ($faction) {
        return [
          'name' => $faction->name,
          'count' => $faction->heroes->count(),
          'color' => $faction->color ?? '#808080',
        ];
      })->values()->toArray(),
      'most_cards' => $byCards->map(function ($faction) {
        return [
          'name' => $faction->name,
          'count' => $faction->cards->count(),
          'color' => $faction->color ?? '#808080',
        ];
      })->values()->toArray(),
      'most_decks' => $byDecks->map(function ($faction) {
        return [
          'name' => $faction->name,
          'count' => $faction->factionDecks->count(),
          'color' => $faction->color ?? '#808080',
        ];
      })->values()->toArray(),
    ];
  }
}