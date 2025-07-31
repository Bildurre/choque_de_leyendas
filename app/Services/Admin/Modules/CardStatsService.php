<?php

namespace App\Services\Admin\Modules;

use App\Models\Card;
use App\Models\CardType;
use App\Models\EquipmentType;
use Illuminate\Support\Facades\DB;

class CardStatsService
{
  /**
   * Get card statistics
   *
   * @return array
   */
  public function getStats(): array
  {
    $cards = Card::with(['faction', 'cardType', 'equipmentType'])->get();
    $cardTypes = CardType::withCount('cards')->get();
    
    return [
      'summary' => $this->getSummaryStats($cards),
      'by_type' => $this->getStatsByType($cards, $cardTypes),
      'by_cost' => $this->getStatsByCost($cards),
      'by_faction' => $this->getStatsByFaction($cards),
      'equipment_distribution' => $this->getEquipmentDistribution($cards),
    ];
  }

  /**
   * Get summary statistics
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getSummaryStats($cards): array
  {
    $publishedCards = $cards->where('is_published', true);
    $cardsWithEffects = $cards->filter(function ($card) {
      return !empty($card->effect);
    });
    $cardsWithRestrictions = $cards->filter(function ($card) {
      return !empty($card->restriction);
    });

    return [
      'total_cards' => $cards->count(),
      'published_cards' => $publishedCards->count(),
      'cards_with_effects' => $cardsWithEffects->count(),
      'cards_with_restrictions' => $cardsWithRestrictions->count(),
      'area_attacks' => $cards->where('area', true)->count(),
    ];
  }

  /**
   * Get statistics by card type
   *
   * @param \Illuminate\Support\Collection $cards
   * @param \Illuminate\Support\Collection $cardTypes
   * @return array
   */
  protected function getStatsByType($cards, $cardTypes): array
  {
    return $cardTypes->map(function ($type) use ($cards) {
      $typeCards = $cards->where('card_type_id', $type->id);
      return [
        'id' => $type->id,
        'name' => $type->name,
        'count' => $typeCards->count(),
        'percentage' => $cards->count() > 0 ? round(($typeCards->count() / $cards->count()) * 100, 1) : 0,
        'published' => $typeCards->where('is_published', true)->count(),
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get statistics by cost
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getStatsByCost($cards): array
  {
    $costDistribution = [];
    
    // Group by total cost
    $cardsByCost = $cards->groupBy(function ($card) {
      $totalCost = $this->calculateTotalCost($card->cost);
      return $totalCost >= 5 ? '5+' : (string)$totalCost;
    });

    // Create distribution array
    foreach (['0', '1', '2', '3', '4', '5+'] as $cost) {
      $count = isset($cardsByCost[$cost]) ? $cardsByCost[$cost]->count() : 0;
      $costDistribution[] = [
        'cost' => $cost,
        'count' => $count,
        'percentage' => $cards->count() > 0 ? round(($count / $cards->count()) * 100, 1) : 0,
      ];
    }

    // Color distribution (only R, G, B)
    $colorCounts = [
      'R' => 0,
      'G' => 0,
      'B' => 0,
    ];

    foreach ($cards as $card) {
      $colors = $this->getCardColors($card->cost);
      foreach ($colors as $color) {
        if (isset($colorCounts[$color])) {
          $colorCounts[$color]++;
        }
      }
    }

    return [
      'distribution' => $costDistribution,
      'colors' => $colorCounts,
      'avg_cost' => round($cards->avg(function ($card) {
        return $this->calculateTotalCost($card->cost);
      }), 1),
    ];
  }

  /**
   * Get statistics by faction
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getStatsByFaction($cards): array
  {
    $cardsByFaction = $cards->groupBy('faction_id');
    
    return $cardsByFaction->map(function ($factionCards, $factionId) {
      $faction = $factionCards->first()->faction;
      return [
        'name' => $faction ? $faction->name : 'Sin facción',
        'color' => $faction ? ($faction->color ?? '#808080') : '#808080',
        'count' => $factionCards->count(),
        'avg_cost' => round($factionCards->avg(function ($card) {
          return $this->calculateTotalCost($card->cost);
        }), 1),
        'types' => $factionCards->groupBy('card_type_id')->count(),
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get equipment distribution
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getEquipmentDistribution($cards): array
  {
    $equipmentCards = $cards->where('card_type_id', 1); // Assuming 1 is equipment type
    
    // General equipment distribution (weapon vs armor)
    $byCategory = $equipmentCards->groupBy(function ($card) {
      return $card->equipmentType ? $card->equipmentType->category : 'other';
    });

    $generalDistribution = [
      'weapon' => isset($byCategory['weapon']) ? $byCategory['weapon']->count() : 0,
      'armor' => isset($byCategory['armor']) ? $byCategory['armor']->count() : 0,
    ];

    // Specific weapon types distribution
    $weaponCards = isset($byCategory['weapon']) ? $byCategory['weapon'] : collect();
    $weaponTypes = $weaponCards->groupBy(function ($card) {
      return $card->equipmentType ? $card->equipmentType->name : 'other';
    })->map(function ($cards) {
      return $cards->count();
    })->sortDesc();

    // Specific armor types distribution
    $armorCards = isset($byCategory['armor']) ? $byCategory['armor'] : collect();
    $armorTypes = $armorCards->groupBy(function ($card) {
      return $card->equipmentType ? $card->equipmentType->name : 'other';
    })->map(function ($cards) {
      return $cards->count();
    })->sortDesc();

    return [
      'total' => $equipmentCards->count(),
      'general_distribution' => $generalDistribution,
      'weapon_types' => $weaponTypes->toArray(),
      'armor_types' => $armorTypes->toArray(),
    ];
  }

  /**
   * Calculate total cost from cost string
   *
   * @param string|null $cost
   * @return int
   */
  protected function calculateTotalCost($cost): int
  {
    if (empty($cost)) return 0;
    
    preg_match_all('/\d+/', $cost, $numbers);
    $numeric = array_sum($numbers[0]);
    
    preg_match_all('/[RGB]/', $cost, $colors);
    $colored = count($colors[0]);
    
    return $numeric + $colored;
  }

  /**
   * Get card colors from cost string
   *
   * @param string|null $cost
   * @return array
   */
  protected function getCardColors($cost): array
  {
    if (empty($cost)) return [];
    
    preg_match_all('/[RGB]/', $cost, $colors);
    return array_unique($colors[0]);
  }
}