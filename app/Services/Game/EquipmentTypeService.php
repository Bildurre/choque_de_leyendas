<?php

namespace App\Services\Game;

use Illuminate\Http\Request;
use App\Models\EquipmentType;
use App\Services\Traits\HandlesTranslations;

class EquipmentTypeService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];

  /**
   * Get all equipment types with optional filtering and pagination
   * 
   * @param Request|null $request Request object for filtering
   * @param int|null $perPage Number of items per page (null for no pagination)
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only show trashed items
   * @return mixed Collection or LengthAwarePaginator
   */
  public function getAllEquipmentTypes(
    ?Request $request = null,
    ?int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false
  ): mixed {
    // Base query with counts
    $query = EquipmentType::withCount('cards');
    
    // Apply trash filters
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    // Count total records (before filtering)
    $totalCount = $query->count();
    
    // Apply admin filters if request is provided
    if ($request) {
      $query->applyAdminFilters($request);
    }
    
    // Count filtered records
    $filteredCount = $query->count();
    
    // Apply default ordering only if no sort parameter is provided
    if (!$request || !$request->has('sort')) {
      $query->orderBy('category')->orderBy('id');
    }
    
    // Paginate if needed
    if ($perPage) {
      $result = $query->paginate($perPage)->withQueryString();
      
      // Add metadata to the pagination result
      $result->totalCount = $totalCount;
      $result->filteredCount = $filteredCount;
      
      return $result;
    }
    
    // Return collection if no pagination
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
      throw new \Exception("__('entities.equipment_types.errors.has_cards')");
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
      throw new \Exception("__('entities.equipment_types.errors.force_delete_has_cards')");
    }
    
    return $equipmentType->forceDelete();
  }
}