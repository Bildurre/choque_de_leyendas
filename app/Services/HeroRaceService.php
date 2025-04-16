<?php

namespace App\Services;

use App\Models\HeroRace;
use Illuminate\Database\Eloquent\Collection;

class HeroRaceService
{
  /**
   * Get all hero races
   *
   * @return Collection
   */
  public function getAllHeroRaces(): Collection
  {
    return HeroRace::withCount('heroes')->get();
  }

  /**
   * Create a new hero race
   *
   * @param array $data
   * @return HeroRace
   * @throws \Exception
   */
  public function create(array $data): HeroRace
  {
    if (!$this->validateModifiers($data)) {
      throw new \Exception('La suma total de los valores absolutos de los modificadores no puede superar 3.');
    }
    
    $heroRace = new HeroRace();
    $heroRace->fill($data);
    $heroRace->save();
    
    return $heroRace;
  }

  /**
   * Update an existing hero race
   *
   * @param HeroRace $heroRace
   * @param array $data
   * @return HeroRace
   * @throws \Exception
   */
  public function update(HeroRace $heroRace, array $data): HeroRace
  {
    if (!$this->validateModifiers($data)) {
      throw new \Exception('La suma total de los valores absolutos de los modificadores no puede superar 3.');
    }
    
    $heroRace->fill($data);
    $heroRace->save();
    
    return $heroRace;
  }

  /**
   * Delete a hero race
   *
   * @param HeroRace $heroRace
   * @return bool
   * @throws \Exception
   */
  public function delete(HeroRace $heroRace): bool
  {
    // Check if race has associated heroes
    if ($heroRace->heroes()->count() > 0) {
      throw new \Exception("No se puede eliminar la raza porque tiene héroes asociados.");
    }
    
    return $heroRace->delete();
  }
  
  /**
   * Validate modifiers total
   *
   * @param array $data
   * @return bool
   */
  public function validateModifiers(array $data): bool
  {
    $totalModifiers = abs($data['agility_modifier'] ?? 0) +
                     abs($data['mental_modifier'] ?? 0) +
                     abs($data['will_modifier'] ?? 0) +
                     abs($data['strength_modifier'] ?? 0) +
                     abs($data['armor_modifier'] ?? 0);
    
    return $totalModifiers <= 3;
  }
}