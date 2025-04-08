<?php

namespace App\Services;

use App\Models\AttackType;
use Illuminate\Database\Eloquent\Collection;

class AttackTypeService
{
  /**
   * Get all attack types with count of related subtypes
   *
   * @return Collection
   */
  public function getAllTypes(): Collection
  {
    return AttackType::withCount('subtypes')->get();
  }

  /**
   * Create a new Attack type
   *
   * @param array $data
   * @return AttackType
   */
  public function create(array $data): AttackType
  {
    $attackType = new AttackType();
    $attackType->fill($data);
    $attackType->save();
    
    return $attackType;
  }

  /**
   * Update an existing Attack type
   *
   * @param AttackType $attackType
   * @param array $data
   * @return AttackType
   */
  public function update(AttackType $attackType, array $data): AttackType
  {
    $attackType->fill($data);
    $attackType->save();
    
    return $attackType;
  }

  /**
   * Delete an attack type
   *
   * @param AttackType $attackType
   * @return bool
   * @throws \Exception
   */
  public function delete(AttackType $attackType): bool
  {
    // Check for related subtypes
    if ($attackType->subtypes()->count() > 0) {
      throw new \Exception("No se puede eliminar el tipo porque tiene subtipos asociados.");
    }
    
    return $attackType->delete();
  }
}