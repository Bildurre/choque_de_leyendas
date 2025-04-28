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
   * @throws \Exception
   */
  public function create(array $data): HeroClass
  {
    $heroClass = new HeroClass();
    
    // Aplicar datos directamente ya que la validación se hace en el Request
    if (isset($data['name']) && is_array($data['name'])) {
      $heroClass->setTranslations('name', $data['name']);
    }
    
    if (isset($data['passive']) && is_array($data['passive'])) {
      $heroClass->setTranslations('passive', $data['passive']);
    }
    
    if (isset($data['hero_superclass_id'])) {
      $heroClass->hero_superclass_id = $data['hero_superclass_id'];
    }
    
    $heroClass->save();
    
    return $heroClass;
  }

  /**
   * Update an existing hero class
   *
   * @param HeroClass $heroClass
   * @param array $data
   * @return HeroClass
   * @throws \Exception
   */
  public function update(HeroClass $heroClass, array $data): HeroClass
  {
    // Aplicar datos directamente ya que la validación se hace en el Request
    if (isset($data['name']) && is_array($data['name'])) {
      $heroClass->setTranslations('name', $data['name']);
    }
    
    if (isset($data['passive']) && is_array($data['passive'])) {
      $heroClass->setTranslations('passive', $data['passive']);
    }
    
    if (isset($data['hero_superclass_id'])) {
      $heroClass->hero_superclass_id = $data['hero_superclass_id'];
    }
    
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
}