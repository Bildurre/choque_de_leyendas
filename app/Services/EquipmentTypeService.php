<?php

namespace App\Services;

use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Collection;

class EquipmentTypeService
{
  /**
   * Get all equipment types
   *
   * @return Collection
   */
  public function getAllTypes(): Collection
  {
    return EquipmentType::all();
  }

  /**
   * Get equipment types by category
   *
   * @param string $category
   * @return Collection
   */
  public function getTypesByCategory(string $category): Collection
  {
    return EquipmentType::where('category', $category)->get();
  }

  /**
   * Get all weapon types
   *
   * @return Collection
   */
  public function getWeaponTypes(): Collection
  {
    return $this->getTypesByCategory('weapon');
  }

  /**
   * Get all armor types
   *
   * @return Collection
   */
  public function getArmorTypes(): Collection
  {
    return $this->getTypesByCategory('armor');
  }

  /**
   * Create a new equipment type
   *
   * @param array $data
   * @return EquipmentType
   */
  public function create(array $data): EquipmentType
  {
    $equipmentType = new EquipmentType();
    
    // Set translatable fields
    $equipmentType->setTranslations('name', $data['name']);
    
    // Set non-translatable fields
    $equipmentType->category = $data['category'];
    
    $equipmentType->save();
    
    return $equipmentType;
  }

  /**
   * Update an existing equipment type
   *
   * @param EquipmentType $equipmentType
   * @param array $data
   * @return EquipmentType
   */
  public function update(EquipmentType $equipmentType, array $data): EquipmentType
  {
    // Update translatable fields
    $equipmentType->setTranslations('name', $data['name']);
    
    // Update non-translatable fields
    $equipmentType->category = $data['category'];
    
    $equipmentType->save();
    
    return $equipmentType;
  }

  /**
   * Delete an equipment type
   *
   * @param EquipmentType $equipmentType
   * @return bool
   * @throws \Exception
   */
  public function delete(EquipmentType $equipmentType): bool
  {
    // Check for related equipment
    if ($equipmentType->equipment()->count() > 0) {
      throw new \Exception("No se puede eliminar el tipo de equipo porque tiene equipos asociados.");
    }
    
    return $equipmentType->delete();
  }
}