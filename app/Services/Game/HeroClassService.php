<?php

namespace App\Services\Game;

use App\Models\HeroClass;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Traits\HandlesTranslations;

class HeroClassService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'passive'];

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
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($heroClass, $data, $this->translatableFields);
    
    // Set non-translatable fields
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
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($heroClass, $data, $this->translatableFields);
    
    // Update non-translatable fields
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
    // Check for related heroes
    if ($heroClass->heroes()->count() > 0) {
      throw new \Exception("No se puede eliminar la clase porque tiene héroes asociados.");
    }
    
    return $heroClass->delete();
  }
}