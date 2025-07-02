<?php

namespace App\Services\Public;

use App\Models\FactionDeck;
use Illuminate\Database\Eloquent\Builder;

class FactionDeckService
{
  /**
   * Get the relations that should be loaded for faction deck display
   */
  public function getFactionDeckRelations(): array
  {
    return [
      'faction',
      'gameMode',
      'heroes.heroClass.heroSuperclass',
      'heroes.heroRace',
      'heroes.faction',
      'heroes.heroAbilities.attackRange',
      'heroes.heroAbilities.attackSubtype',
      'cards.cardType.heroSuperclass',
      'cards.equipmentType',
      'cards.attackRange',
      'cards.attackSubtype',
      'cards.heroAbility.attackRange',
      'cards.heroAbility.attackSubtype',
      'cards.faction'
    ];
  }

  /**
   * Get a single published faction deck with relations
   */
  public function getPublishedFactionDeck(FactionDeck $factionDeck): FactionDeck
  {
    if (!$factionDeck->isPublished()) {
      abort(404);
    }

    $factionDeck->load($this->getFactionDeckRelations());
    
    return $factionDeck;
  }

  /**
   * Get deck statistics
   */
  public function getDeckStatistics(FactionDeck $factionDeck): array
  {
    $cards = $factionDeck->cards;
    $totalCards = $cards->sum('pivot.copies');
    
    return [
      'totalCards' => $totalCards,
      'uniqueCards' => $cards->count(),
      'cardsByCost' => $cards->groupBy('cost')->map(function ($group) {
        return $group->sum('pivot.copies');
      }),
      'cardsByType' => $cards->groupBy('card_type_id')->map(function ($group) {
        return $group->sum('pivot.copies');
      }),
      'averageCost' => $cards->avg('cost'),
      'totalHeroes' => $factionDeck->heroes->sum('pivot.copies'),
      'uniqueHeroes' => $factionDeck->heroes->count()
    ];
  }
}