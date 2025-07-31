<?php

namespace App\Services\Admin\Modules;

use App\Models\Hero;
use App\Models\HeroClass;
use App\Models\HeroRace;
use Illuminate\Support\Facades\DB;

class HeroStatsService
{
  /**
   * Get hero statistics
   *
   * @return array
   */
  public function getStats(): array
  {
    $heroes = Hero::with(['faction', 'heroClass.heroSuperclass', 'heroRace'])->get();
    
    return [
      'summary' => $this->getSummaryStats($heroes),
      'by_faction' => $this->getStatsByFaction($heroes),
      'by_class' => $this->getStatsByClass($heroes),
      'by_race' => $this->getStatsByRace($heroes),
      'attributes' => $this->getAttributeStats($heroes),
      'gender_distribution' => $this->getGenderDistribution($heroes),
    ];
  }

  /**
   * Get summary statistics
   *
   * @param \Illuminate\Support\Collection $heroes
   * @return array
   */
  protected function getSummaryStats($heroes): array
  {
    $publishedHeroes = $heroes->where('is_published', true);
    $heroesWithPassives = $heroes->filter(function ($hero) {
      return !empty($hero->passive_name) || !empty($hero->passive_description);
    });

    return [
      'total_heroes' => $heroes->count(),
      'published_heroes' => $publishedHeroes->count(),
      'heroes_with_passives' => $heroesWithPassives->count(),
      'unique_classes' => $heroes->pluck('hero_class_id')->unique()->count(),
      'unique_races' => $heroes->pluck('hero_race_id')->unique()->count(),
    ];
  }

  /**
   * Get statistics by faction
   *
   * @param \Illuminate\Support\Collection $heroes
   * @return array
   */
  protected function getStatsByFaction($heroes): array
  {
    $heroesByFaction = $heroes->groupBy('faction_id');
    
    return $heroesByFaction->map(function ($factionHeroes, $factionId) {
      $faction = $factionHeroes->first()->faction;
      return [
        'name' => $faction ? $faction->name : 'Sin facción',
        'color' => $faction ? ($faction->color ?? '#808080') : '#808080',
        'count' => $factionHeroes->count(),
        'published' => $factionHeroes->where('is_published', true)->count(),
        'avg_health' => round($factionHeroes->avg('health'), 1),
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get statistics by class
   *
   * @param \Illuminate\Support\Collection $heroes
   * @return array
   */
  protected function getStatsByClass($heroes): array
  {
    // By superclass
    $bySuperclass = $heroes->groupBy(function ($hero) {
      return $hero->heroClass && $hero->heroClass->heroSuperclass 
        ? $hero->heroClass->heroSuperclass->id 
        : null;
    })->filter(function ($group, $key) {
      return $key !== null;
    });

    $superclassStats = $bySuperclass->map(function ($heroes, $superclassId) {
      $superclass = $heroes->first()->heroClass->heroSuperclass;
      return [
        'name' => $superclass->name,
        'count' => $heroes->count(),
        'percentage' => $heroes->count() > 0 ? round(($heroes->count() / $this->getTotalHeroesCount()) * 100, 1) : 0,
      ];
    })->sortByDesc('count')->values()->toArray();

    // By specific class
    $byClass = $heroes->groupBy('hero_class_id')->filter(function ($group, $key) {
      return $key !== null;
    });

    $classStats = $byClass->map(function ($classHeroes, $classId) {
      $class = $classHeroes->first()->heroClass;
      return [
        'name' => $class ? $class->name : 'Sin clase',
        'count' => $classHeroes->count(),
        'superclass' => $class && $class->heroSuperclass ? $class->heroSuperclass->name : 'Sin superclase',
      ];
    })->sortByDesc('count')->values()->take(10)->toArray();

    return [
      'by_superclass' => $superclassStats,
      'top_classes' => $classStats,
    ];
  }

  /**
   * Get statistics by race
   *
   * @param \Illuminate\Support\Collection $heroes
   * @return array
   */
  protected function getStatsByRace($heroes): array
  {
    $byRace = $heroes->groupBy('hero_race_id')->filter(function ($group, $key) {
      return $key !== null;
    });

    return $byRace->map(function ($raceHeroes, $raceId) {
      $race = $raceHeroes->first()->heroRace;
      return [
        'name' => $race ? $race->name : 'Sin raza',
        'count' => $raceHeroes->count(),
        'percentage' => $this->getTotalHeroesCount() > 0 ? round(($raceHeroes->count() / $this->getTotalHeroesCount()) * 100, 1) : 0,
      ];
    })->sortByDesc('count')->values()->toArray();
  }

  /**
   * Get attribute statistics
   *
   * @param \Illuminate\Support\Collection $heroes
   * @return array
   */
  protected function getAttributeStats($heroes): array
  {
    $attributes = ['agility', 'mental', 'will', 'strength', 'armor'];
    $stats = [];

    foreach ($attributes as $attribute) {
      $stats[$attribute] = [
        'avg' => round($heroes->avg($attribute), 1),
        'min' => $heroes->min($attribute),
        'max' => $heroes->max($attribute),
        'distribution' => $this->getAttributeDistribution($heroes, $attribute),
      ];
    }

    // Health stats
    $stats['health'] = [
      'avg' => round($heroes->avg('health'), 1),
      'min' => $heroes->min('health'),
      'max' => $heroes->max('health'),
    ];

    // Total attributes
    $totalAttributes = $heroes->map(function ($hero) {
      return $hero->agility + $hero->mental + $hero->will + $hero->strength + $hero->armor;
    });

    $stats['total_attributes'] = [
      'avg' => round($totalAttributes->avg(), 1),
      'min' => $totalAttributes->min(),
      'max' => $totalAttributes->max(),
    ];

    return $stats;
  }

  /**
   * Get gender distribution
   *
   * @param \Illuminate\Support\Collection $heroes
   * @return array
   */
  protected function getGenderDistribution($heroes): array
  {
    $byGender = $heroes->groupBy('gender');
    
    return [
      'male' => isset($byGender['male']) ? $byGender['male']->count() : 0,
      'female' => isset($byGender['female']) ? $byGender['female']->count() : 0,
    ];
  }

  /**
   * Get attribute distribution for a specific attribute
   *
   * @param \Illuminate\Support\Collection $heroes
   * @param string $attribute
   * @return array
   */
  protected function getAttributeDistribution($heroes, $attribute): array
  {
    $distribution = [];
    for ($i = 1; $i <= 8; $i++) {
      $count = $heroes->where($attribute, $i)->count();
      $distribution[$i] = $count;
    }
    return $distribution;
  }

  /**
   * Get total heroes count for percentage calculations
   *
   * @return int
   */
  protected function getTotalHeroesCount(): int
  {
    return Hero::count();
  }
}