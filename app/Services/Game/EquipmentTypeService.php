<?php

namespace App\Services\Game;

use App\Models\EquipmentType;
use App\Services\Traits\HandlesTranslations;

class EquipmentTypeService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];

  /**
   * Get all equipment types with optional pagination
   *
   * @param int|null $perPage Number of items per page, or null for all items
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only trashed items
   * @param string|null $category Filter by category
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getAllEquipmentTypes(int $perPage = null, bool $withTrashed = false, bool $onlyTrashed = false, ?string $category = null): mixed
  {
    $query = EquipmentType::withCount('cards');
    
    // Aplicar filtros de elementos eliminados
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    // Filtrar por categoría si se especifica
    if ($category) {
      $query->where('category', $category);
    }
    
    // Ordenar por categoría y nombre
    $query->orderBy('category')->orderBy('id');
    
    if ($perPage) {
      return $query->paginate($perPage);
    }
    
    return $query->get();
  }

  /**
   * Create a new equipment type
   *
   * @param array $data
   * @return EquipmentType
   * @throws \Exception
   */
  public function create(array $data): EquipmentType
  {
    $equipmentType = new EquipmentType();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($equipmentType, $data, $this->translatableFields);
    
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
   * @throws \Exception
   */
  public function update(EquipmentType $equipmentType, array $data): EquipmentType
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($equipmentType, $data, $this->translatableFields);
    
    // Update non-translatable fields
    if (isset($data['category'])) {
      $equipmentType->category = $data['category'];
    }
    
    $equipmentType->save();
    
    return $equipmentType;
  }

  /**
   * Delete an equipment type (soft delete)
   *
   * @param EquipmentType $equipmentType
   * @return bool
   * @throws \Exception
   */
  public function delete(EquipmentType $equipmentType): bool
  {
    // Check for related cards
    if ($equipmentType->cards()->count() > 0) {
      throw new \Exception("No se puede eliminar el tipo de equipo porque tiene cartas asociadas.");
    }
    
    return $equipmentType->delete();
  }

  /**
   * Restore a deleted equipment type
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $equipmentType = EquipmentType::onlyTrashed()->findOrFail($id);
    return $equipmentType->restore();
  }

  /**
   * Force delete an equipment type permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $equipmentType = EquipmentType::onlyTrashed()->findOrFail($id);
    
    // Check for related cards (incluso para los eliminados)
    if ($equipmentType->cards()->withTrashed()->count() > 0) {
      throw new \Exception("No se puede eliminar permanentemente el tipo de equipo porque tiene cartas asociadas.");
    }
    
    return $equipmentType->forceDelete();
  }

  /**
   * Get counts by category
   * 
   * @return array
   */
  public function getCountsByCategory(): array
  {
    $counts = [];
    $categories = EquipmentType::getCategories();
    
    foreach ($categories as $key => $name) {
      $counts[$key] = EquipmentType::where('category', $key)->count();
    }
    
    return $counts;
  }
}