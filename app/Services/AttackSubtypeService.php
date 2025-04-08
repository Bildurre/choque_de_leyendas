<?php

namespace App\Services;

use App\Models\AttackSubtype;
use Illuminate\Database\Eloquent\Collection;

class AttackSubtypeService
{
  /**
   * Get all attack subtypes with related type
   *
   * @return Collection
   */
  public function getAllSubtypes(): Collection
  {
    return AttackSubtype::with('type')->get();
  }

  /**
   * Get subtypes by type ID
   * 
   * @param int $typeId
   * @return Collection
   */
  public function getSubtypesByType(int $typeId): Collection
  {
    return AttackSubtype::where('attack_type_id', $typeId)->get();
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
    $attackSubtype->fill($data);
    
    // Use parent color if not specified
    if (empty($data['color']) && !empty($data['attack_type_id'])) {
      $parentType = \App\Models\AttackType::find($data['attack_type_id']);
      if ($parentType) {
        $attackSubtype->color = $parentType->color;
      }
    }
    
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
    $attackSubtype->fill($data);
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