<?php

namespace App\Services\Public;

use App\Models\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class CardService
{
  /**
   * Items per page for pagination
   */
  private const ITEMS_PER_PAGE = 12;

  /**
   * Get the relations that should be loaded for card display
   */
  public function getCardRelations(): array
  {
    return [
      'faction',
      'cardType.heroSuperclass',
      'equipmentType',
      'attackRange',
      'attackSubtype',
      'heroAbility.attackRange',
      'heroAbility.attackSubtype'
    ];
  }

  /**
   * Get query for published cards with all relations
   */
  public function getPublishedCardsQuery(): Builder
  {
    return Card::published()->with($this->getCardRelations());
  }

  /**
   * Get paginated cards with filters
   */
  public function getPaginatedCards(Request $request): array
  {
    // Base query for published cards
    $query = $this->getPublishedCardsQuery();
    
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
    $cards = $query->paginate(self::ITEMS_PER_PAGE)->withQueryString();
    
    return [
      'cards' => $cards,
      'totalCount' => $totalCount,
      'filteredCount' => $filteredCount
    ];
  }

  /**
   * Get a single published card with relations
   */
  public function getPublishedCard(Card $card): Card
  {
    if (!$card->isPublished() || !$card->faction->isPublished()) {
      abort(404);
    }

    $card->load($this->getCardRelations());
    
    return $card;
  }

  /**
   * Get cards for collection
   */
  public function getCardsForCollection(array $cardIds): Builder
  {
    return Card::published()
      ->whereIn('id', $cardIds)
      ->with($this->getCardRelations());
  }
}