<?php

namespace App\Services\Game;

use App\Models\AttackSubtype;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Traits\HandlesTranslations;

class AttackSubtypeService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];

  /**
   * Get all attack subtypes
   *
   * @return Collection
   */
  public function getAllSubtypes(): Collection
  {
    return AttackSubtype::withCount('abilities')->get();
  }

  /**
   * Get subtypes by type (physical or magical)
   *
   * @param string $type
   * @return Collection
   */
  public function getSubtypesByType(string $type): Collection
  {
    return AttackSubtype::where('type', $type)->get();
  }

  /**
   * Create a new attack subtype
   *
   * @param array $data
   * @return AttackSubtype
   */
  public function create(array $data): AttackSubtype
  {
    $attackSubtype = new AttackSubtype();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($attackSubtype, $data, $this->translatableFields);
    
    // Set non-translatable fields
    $attackSubtype->type = $data['type'];
    
    $attackSubtype->save();
    
    return $attackSubtype;
  }

  /**
   * Update an existing attack subtype
   *
   * @param AttackSubtype $attackSubtype
   * @param array $data
   * @return AttackSubtype
   */
  public function update(AttackSubtype $attackSubtype, array $data): AttackSubtype
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($attackSubtype, $data, $this->translatableFields);
    
    // Update non-translatable fields
    if (isset($data['type'])) {
      $attackSubtype->type = $data['type'];
    }
    
    $attackSubtype->save();
    
    return $attackSubtype;
  }

  /**
   * Delete an attack subtype
   *
   * @param AttackSubtype $attackSubtype
   * @return bool
   * @throws \Exception
   */
  public function delete(AttackSubtype $attackSubtype): bool
  {
    // Check for related abilities
    if ($attackSubtype->abilities()->count() > 0) {
      throw new \Exception("No se puede eliminar el subtipo porque tiene habilidades asociadas.");
    }
    
    return $attackSubtype->delete();
  }
}