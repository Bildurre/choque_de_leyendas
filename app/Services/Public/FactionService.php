<?php

namespace App\Services\Public;

use App\Models\Faction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FactionService
{
  /**
   * Items per page for pagination
   */
  private const ITEMS_PER_PAGE = 12;

  /**
   * Get the relations that should be loaded for faction display
   */
  public function getFactionRelations(): array
  {
    return [
      'heroes' => function($query) {
        $query->published();
      },
      'cards' => function($query) {
        $query->published();
      },
      'factionDecks' => function($query) {
        $query->published();
      }
    ];
  }

  /**
   * Get query for published factions
   */
  public function getPublishedFactionsQuery(): Builder
  {
    return Faction::published();
  }

  /**
   * Get paginated factions with filters
   */
  public function getPaginatedFactions(Request $request): array
  {
    // Base query for published factions
    $query = $this->getPublishedFactionsQuery();
    
    // Count total before filters
    $totalCount = $query->count();
    
    // Apply public filters
    $query->applyPublicFilters($request);
    
    // Count after filters
    $filteredQuery = clone $query;
    $filteredCount = $filteredQuery->count();
    
    // Apply default ordering if no sort is specified
    if (!$request->has('sort')) {
      $query->orderBy('name');
    }
    
    // Paginate results
    $factions = $query->paginate(self::ITEMS_PER_PAGE)->withQueryString();
    
    return [
      'factions' => $factions,
      'totalCount' => $totalCount,
      'filteredCount' => $filteredCount
    ];
  }

  /**
   * Get a single published faction with relations
   */
  public function getPublishedFaction(Faction $faction): Faction
  {
    if (!$faction->isPublished()) {
      abort(404);
    }

    $faction->load($this->getFactionRelations());
    
    return $faction;
  }

  /**
   * Get faction statistics
   */
  public function getFactionStatistics(Faction $faction): array
  {
    $cards = $faction->cards()->published()->get();
    
    return [
      'totalCards' => $cards->count(),
      'cardsByCost' => $cards->groupBy('cost')->map->count(),
      'cardsByType' => $cards->groupBy('card_type_id')->map->count(),
      'averageCost' => $cards->avg('cost'),
      'heroCount' => $faction->heroes()->published()->count(),
      'deckCount' => $faction->factionDecks()->published()->count()
    ];
  }
}