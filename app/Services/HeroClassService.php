<?php

namespace App\Services;

use App\Models\HeroClass;
use Illuminate\Database\Eloquent\Collection;

class HeroClassService
{
  /**
   * Get all hero classes with related superclass
   *
   * @return Collection
   */
  public function getAllHeroClasses(): Collection
  {
    return HeroClass::with('heroSuperclass')->get();
  }

  /**
   * Create a new hero class
   *
   * @param array $data
   * @return HeroClass
   */
  public function create(array $data): HeroClass
  {
    $heroClass = new HeroClass();
    $heroClass->fill($data);
    $heroClass->save();
    
    return $heroClass;
  }

  /**
   * Update an existing hero class
   *
   * @param HeroClass $heroClass
   * @param array $data
   * @return HeroClass
   */
  public function update(HeroClass $heroClass, array $data): HeroClass
  {
    $heroClass->fill($data);
    $heroClass->save();
    
    return $heroClass;
  }

  /**
   * Delete a hero class
   *
   * @param HeroClass $heroClass
   * @return bool
   */
  public function delete(HeroClass $heroClass): bool
  {
    return $heroClass->delete();
  }
  
  /**
   * Validate modifiers total
   *
   * @param array $data
   * @return bool
   */
  public function validateModifiers(array $data): bool
  {
    $totalModifiers = abs($data['agility_modifier']) +
                     abs($data['mental_modifier']) +
                     abs($data['will_modifier']) +
                     abs($data['strength_modifier']) +
                     abs($data['armor_modifier']);
    
    return $totalModifiers <= 3;
  }
}