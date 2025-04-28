<?php

namespace App\Services;

use App\Models\HeroClass;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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
    // Validar unicidad del nombre en todos los idiomas disponibles
    $this->validateNameUniqueness($data['name']);
    
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
   * @throws \Exception
   */
  public function update(HeroClass $heroClass, array $data): HeroClass
  {
    // Validar unicidad del nombre excluyendo el registro actual
    $this->validateNameUniqueness($data['name'], $heroClass->id);
    
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
   * Validate that the name is unique in all translations
   *
   * @param array $names
   * @param int|null $excludeId
   * @return void
   * @throws \Exception
   */
  protected function validateNameUniqueness(array $names, ?int $excludeId = null): void
  {
    foreach ($names as $locale => $name) {
      if (empty($name)) continue;
      
      // Consulta para verificar unicidad
      $query = HeroClass::whereRaw("JSON_EXTRACT(name, '$.\"{$locale}\"') = ?", [$name]);
      
      if ($excludeId) {
        $query->where('id', '!=', $excludeId);
      }
      
      if ($query->exists()) {
        throw new \Exception("Ya existe una clase con el nombre '{$name}' en el idioma " . locale_name($locale));
      }
    }
  }
}