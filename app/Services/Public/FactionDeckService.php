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
      'factions',
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
    
    // Group cards by dice count (cost length)
    $cardsByDiceCount = collect();
    $totalDiceCount = 0;
    
    // Count total symbols
    $symbolCounts = [
      'R' => 0,
      'G' => 0,
      'B' => 0
    ];
    
    foreach ($cards as $card) {
      $diceCount = strlen($card->cost);
      $copies = $card->pivot->copies;
      
      // Count cards by dice count
      if ($cardsByDiceCount->has($diceCount)) {
        $cardsByDiceCount[$diceCount] += $copies;
      } else {
        $cardsByDiceCount[$diceCount] = $copies;
      }
      
      // Count total dice for average
      $totalDiceCount += $diceCount * $copies;
      
      // Count individual symbols
      if (!empty($card->cost)) {
        $symbols = str_split(strtoupper($card->cost));
        foreach ($symbols as $symbol) {
          if (isset($symbolCounts[$symbol])) {
            $symbolCounts[$symbol] += $copies;
          }
        }
      }
    }
    
    return [
      'totalCards' => $totalCards,
      'uniqueCards' => $cards->count(),
      'cardsByDiceCount' => $cardsByDiceCount->sortKeys(),
      'averageDiceCount' => $totalCards > 0 ? round($totalDiceCount / $totalCards, 2) : 0,
      'symbolCounts' => $symbolCounts,
      'cardsByType' => $factionDeck->getCardCopiesBreakdown(),
      'totalHeroes' => $factionDeck->heroes->count(),
      'uniqueHeroes' => $factionDeck->heroes->count(),
      'heroesByClass' => $factionDeck->getHeroCopiesByClassBreakdown(),
      'heroesBySuperclass' => $factionDeck->getHeroCopiesBreakdown()
    ];
  }
}