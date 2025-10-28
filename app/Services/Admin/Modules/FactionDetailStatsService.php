<?php

namespace App\Services\Admin\Modules;

use App\Models\Faction;
use App\Models\Hero;
use App\Models\Card;
use App\Models\HeroAbility;
use Illuminate\Support\Facades\DB;

class FactionDetailStatsService
{
  /**
   * Get detailed statistics for a specific faction
   *
   * @param int $factionId
   * @return array
   */
  public function getStatsForFaction(int $factionId): array
  {
    $faction = Faction::with([
      'heroes.heroClass.heroSuperclass',
      'heroes.heroRace',
      'heroes.heroAbilities.attackRange',
      'heroes.heroAbilities.attackSubtype',
      'cards.cardType',
      'cards.cardSubtype',
      'cards.equipmentType',
      'cards.attackRange',
      'cards.attackSubtype'
    ])->findOrFail($factionId);

    return [
      'faction_info' => $this->getFactionInfo($faction),
      'heroes' => $this->getHeroStats($faction),
      'cards' => $this->getCardStats($faction),
      'hero_abilities' => $this->getHeroAbilityStats($faction),
    ];
  }

  /**
   * Get all factions with basic info for selector
   *
   * @return array
   */
  public function getAllFactionsBasicInfo(): array
  {
    return Faction::select('id', 'name', 'color', 'is_published')
      ->orderBy('name')
      ->get()
      ->map(function ($faction) {
        return [
          'id' => $faction->id,
          'name' => $faction->name,
          'color' => $faction->color ?? '#808080',
          'is_published' => $faction->is_published,
        ];
      })
      ->toArray();
  }

  /**
   * Get faction basic information
   *
   * @param Faction $faction
   * @return array
   */
  protected function getFactionInfo(Faction $faction): array
  {
    return [
      'id' => $faction->id,
      'name' => $faction->name,
      'color' => $faction->color ?? '#808080',
      'is_published' => $faction->is_published,
      'total_heroes' => $faction->heroes->count(),
      'total_cards' => $faction->cards->count(),
    ];
  }

  /**
   * Get hero statistics for the faction
   *
   * @param Faction $faction
   * @return array
   */
  protected function getHeroStats(Faction $faction): array
  {
    $heroes = $faction->heroes;

    if ($heroes->isEmpty()) {
      return [
        'total' => 0,
        'by_superclass' => [],
        'by_class' => [],
        'attributes' => [],
      ];
    }

    return [
      'total' => $heroes->count(),
      'by_superclass' => $this->getHeroesBySuperclass($heroes),
      'by_class' => $this->getHeroesByClass($heroes),
      'attributes' => $this->getHeroAttributeStats($heroes),
    ];
  }

  /**
   * Get heroes grouped by superclass
   *
   * @param \Illuminate\Support\Collection $heroes
   * @return array
   */
  protected function getHeroesBySuperclass($heroes): array
  {
    $bySuperclass = $heroes->groupBy(function ($hero) {
      return $hero->heroClass && $hero->heroClass->heroSuperclass 
        ? $hero->heroClass->heroSuperclass->id 
        : null;
    })->filter(function ($group, $key) {
      return $key !== null;
    });

    return $bySuperclass->map(function ($superclassHeroes, $superclassId) use ($heroes) {
      $superclass = $superclassHeroes->first()->heroClass->heroSuperclass;
      return [
        'name' => $superclass->name,
        'count' => $superclassHeroes->count(),
        'percentage' => $heroes->count() > 0 ? round(($superclassHeroes->count() / $heroes->count()) * 100, 1) : 0,
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get heroes grouped by class
   *
   * @param \Illuminate\Support\Collection $heroes
   * @return array
   */
  protected function getHeroesByClass($heroes): array
  {
    $byClass = $heroes->groupBy('hero_class_id')->filter(function ($group, $key) {
      return $key !== null;
    });

    return $byClass->map(function ($classHeroes, $classId) use ($heroes) {
      $class = $classHeroes->first()->heroClass;
      return [
        'name' => $class ? $class->name : 'Sin clase',
        'count' => $classHeroes->count(),
        'percentage' => $heroes->count() > 0 ? round(($classHeroes->count() / $heroes->count()) * 100, 1) : 0,
        'superclass' => $class && $class->heroSuperclass ? $class->heroSuperclass->name : 'Sin superclase',
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get hero attribute statistics
   *
   * @param \Illuminate\Support\Collection $heroes
   * @return array
   */
  protected function getHeroAttributeStats($heroes): array
  {
    $attributes = ['agility', 'mental', 'will', 'strength', 'armor'];
    $stats = [];

    foreach ($attributes as $attribute) {
      $stats[$attribute] = [
        'avg' => round($heroes->avg($attribute), 1),
        'min' => $heroes->min($attribute),
        'max' => $heroes->max($attribute),
      ];
    }

    return $stats;
  }

  /**
   * Get card statistics for the faction
   *
   * @param Faction $faction
   * @return array
   */
  protected function getCardStats(Faction $faction): array
  {
    $cards = $faction->cards;

    if ($cards->isEmpty()) {
      return [
        'total' => 0,
        'by_dice_cost' => [],
        'by_specific_cost' => [],
        'by_type' => [],
        'by_subtype' => [],
        'by_attack_type' => [],
        'by_attack_subtype' => [],
        'equipment_by_type' => [],
      ];
    }

    return [
      'total' => $cards->count(),
      'by_dice_cost' => $this->getCardsByDiceCost($cards),
      'by_specific_cost' => $this->getCardsBySpecificCost($cards),
      'by_type' => $this->getCardsByType($cards),
      'by_subtype' => $this->getCardsBySubtype($cards),
      'by_attack_type' => $this->getCardsByAttackType($cards),
      'by_attack_subtype' => $this->getCardsByAttackSubtype($cards),
      'equipment_by_type' => $this->getEquipmentByType($cards),
    ];
  }

  /**
   * Get cards grouped by dice cost (total cost in dice)
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getCardsByDiceCost($cards): array
  {
    $costRanges = [
      '0' => 0,
      '1' => 0,
      '2' => 0,
      '3' => 0,
      '4' => 0,
      '5+' => 0,
    ];

    foreach ($cards as $card) {
      $totalCost = $this->calculateTotalCost($card->cost);
      
      if ($totalCost >= 5) {
        $costRanges['5+']++;
      } else {
        $costRanges[(string)$totalCost]++;
      }
    }

    $distribution = [];
    foreach ($costRanges as $cost => $count) {
      $distribution[] = [
        'cost' => $cost,
        'count' => $count,
        'percentage' => $cards->count() > 0 ? round(($count / $cards->count()) * 100, 1) : 0,
      ];
    }

    return $distribution;
  }

  /**
   * Get cards grouped by specific cost (exact cost string)
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getCardsBySpecificCost($cards): array
  {
    $byCost = $cards->groupBy(function ($card) {
      return $card->cost ?? '0';
    });

    return $byCost->map(function ($costCards, $cost) use ($cards) {
      return [
        'cost' => $cost,
        'count' => $costCards->count(),
        'percentage' => $cards->count() > 0 ? round(($costCards->count() / $cards->count()) * 100, 1) : 0,
      ];
    })
    ->sortByDesc('count')
    ->take(10) // Show top 10 most common costs
    ->values()
    ->toArray();
  }

  /**
   * Get cards grouped by card type
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getCardsByType($cards): array
  {
    $byType = $cards->groupBy('card_type_id')->filter(function ($group, $key) {
      return $key !== null;
    });

    return $byType->map(function ($typeCards, $typeId) use ($cards) {
      $type = $typeCards->first()->cardType;
      return [
        'name' => $type ? $type->name : 'Sin tipo',
        'count' => $typeCards->count(),
        'percentage' => $cards->count() > 0 ? round(($typeCards->count() / $cards->count()) * 100, 1) : 0,
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get cards grouped by card subtype (only cards with subtype)
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getCardsBySubtype($cards): array
  {
    $cardsWithSubtype = $cards->filter(function ($card) {
      return $card->card_subtype_id !== null;
    });

    if ($cardsWithSubtype->isEmpty()) {
      return [];
    }

    $bySubtype = $cardsWithSubtype->groupBy('card_subtype_id');

    return $bySubtype->map(function ($subtypeCards, $subtypeId) use ($cardsWithSubtype) {
      $subtype = $subtypeCards->first()->cardSubtype;
      return [
        'name' => $subtype ? $subtype->name : 'Sin subtipo',
        'count' => $subtypeCards->count(),
        'percentage' => $cardsWithSubtype->count() > 0 ? round(($subtypeCards->count() / $cardsWithSubtype->count()) * 100, 1) : 0,
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get cards grouped by attack type
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getCardsByAttackType($cards): array
  {
    $cardsWithAttackType = $cards->filter(function ($card) {
      return $card->attack_type !== null;
    });

    if ($cardsWithAttackType->isEmpty()) {
      return [];
    }

    $byAttackType = $cardsWithAttackType->groupBy('attack_type');

    return [
      'physical' => $byAttackType->get('physical', collect())->count(),
      'magical' => $byAttackType->get('magical', collect())->count(),
    ];
  }

  /**
   * Get cards grouped by attack subtype
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getCardsByAttackSubtype($cards): array
  {
    $cardsWithAttackSubtype = $cards->filter(function ($card) {
      return $card->attack_subtype_id !== null;
    });

    if ($cardsWithAttackSubtype->isEmpty()) {
      return [];
    }

    $byAttackSubtype = $cardsWithAttackSubtype->groupBy('attack_subtype_id');

    return $byAttackSubtype->map(function ($subtypeCards, $subtypeId) use ($cardsWithAttackSubtype) {
      $subtype = $subtypeCards->first()->attackSubtype;
      return [
        'name' => $subtype ? $subtype->name : 'Sin subtipo',
        'count' => $subtypeCards->count(),
        'percentage' => $cardsWithAttackSubtype->count() > 0 ? round(($subtypeCards->count() / $cardsWithAttackSubtype->count()) * 100, 1) : 0,
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get equipment cards grouped by equipment type
   *
   * @param \Illuminate\Support\Collection $cards
   * @return array
   */
  protected function getEquipmentByType($cards): array
  {
    $equipmentCards = $cards->filter(function ($card) {
      return $card->equipment_type_id !== null;
    });

    if ($equipmentCards->isEmpty()) {
      return [
        'weapons' => [],
        'armors' => [],
      ];
    }

    $weapons = $equipmentCards->filter(function ($card) {
      return $card->equipmentType && $card->equipmentType->category === 'weapon';
    });

    $armors = $equipmentCards->filter(function ($card) {
      return $card->equipmentType && $card->equipmentType->category === 'armor';
    });

    return [
      'weapons' => $this->groupEquipmentByType($weapons),
      'armors' => $this->groupEquipmentByType($armors),
    ];
  }

  /**
   * Group equipment by specific type
   *
   * @param \Illuminate\Support\Collection $equipment
   * @return array
   */
  protected function groupEquipmentByType($equipment): array
  {
    if ($equipment->isEmpty()) {
      return [];
    }

    $byType = $equipment->groupBy('equipment_type_id');

    return $byType->map(function ($typeCards, $typeId) use ($equipment) {
      $type = $typeCards->first()->equipmentType;
      return [
        'name' => $type ? $type->name : 'Sin tipo',
        'count' => $typeCards->count(),
        'percentage' => $equipment->count() > 0 ? round(($typeCards->count() / $equipment->count()) * 100, 1) : 0,
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get hero ability statistics for the faction
   *
   * @param Faction $faction
   * @return array
   */
  protected function getHeroAbilityStats(Faction $faction): array
  {
    // Get all abilities from heroes of this faction
    $abilities = $faction->heroes->flatMap(function ($hero) {
      return $hero->heroAbilities;
    })->unique('id');

    if ($abilities->isEmpty()) {
      return [
        'total' => 0,
        'by_attack_type' => [],
        'by_attack_subtype' => [],
        'by_attack_range' => [],
      ];
    }

    return [
      'total' => $abilities->count(),
      'by_attack_type' => $this->getAbilitiesByAttackType($abilities),
      'by_attack_subtype' => $this->getAbilitiesByAttackSubtype($abilities),
      'by_attack_range' => $this->getAbilitiesByAttackRange($abilities),
    ];
  }

  /**
   * Get abilities grouped by attack type
   *
   * @param \Illuminate\Support\Collection $abilities
   * @return array
   */
  protected function getAbilitiesByAttackType($abilities): array
  {
    $abilitiesWithAttackType = $abilities->filter(function ($ability) {
      return $ability->attack_type !== null;
    });

    if ($abilitiesWithAttackType->isEmpty()) {
      return [];
    }

    $byAttackType = $abilitiesWithAttackType->groupBy('attack_type');

    return [
      'physical' => $byAttackType->get('physical', collect())->count(),
      'magical' => $byAttackType->get('magical', collect())->count(),
    ];
  }

  /**
   * Get abilities grouped by attack subtype
   *
   * @param \Illuminate\Support\Collection $abilities
   * @return array
   */
  protected function getAbilitiesByAttackSubtype($abilities): array
  {
    $abilitiesWithAttackSubtype = $abilities->filter(function ($ability) {
      return $ability->attack_subtype_id !== null;
    });

    if ($abilitiesWithAttackSubtype->isEmpty()) {
      return [];
    }

    $byAttackSubtype = $abilitiesWithAttackSubtype->groupBy('attack_subtype_id');

    return $byAttackSubtype->map(function ($subtypeAbilities, $subtypeId) use ($abilitiesWithAttackSubtype) {
      $subtype = $subtypeAbilities->first()->attackSubtype;
      return [
        'name' => $subtype ? $subtype->name : 'Sin subtipo',
        'count' => $subtypeAbilities->count(),
        'percentage' => $abilitiesWithAttackSubtype->count() > 0 ? round(($subtypeAbilities->count() / $abilitiesWithAttackSubtype->count()) * 100, 1) : 0,
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get abilities grouped by attack range
   *
   * @param \Illuminate\Support\Collection $abilities
   * @return array
   */
  protected function getAbilitiesByAttackRange($abilities): array
  {
    $abilitiesWithAttackRange = $abilities->filter(function ($ability) {
      return $ability->attack_range_id !== null;
    });

    if ($abilitiesWithAttackRange->isEmpty()) {
      return [];
    }

    $byAttackRange = $abilitiesWithAttackRange->groupBy('attack_range_id');

    return $byAttackRange->map(function ($rangeAbilities, $rangeId) use ($abilitiesWithAttackRange) {
      $range = $rangeAbilities->first()->attackRange;
      return [
        'name' => $range ? $range->name : 'Sin rango',
        'count' => $rangeAbilities->count(),
        'percentage' => $abilitiesWithAttackRange->count() > 0 ? round(($rangeAbilities->count() / $abilitiesWithAttackRange->count()) * 100, 1) : 0,
      ];
    })->sortByDesc('count')->values()->toArray();
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
}