<?php

namespace App\Services\Game;

use App\Models\AttackSubtype;
use Illuminate\Http\Request;
use App\Services\Traits\HandlesTranslations;

class AttackSubtypeService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];

  /**
   * Get all attack subtypes with optional filtering and pagination
   * 
   * @param int|null $perPage Number of items per page (null for no pagination)
   * @param Request $request Request object for filtering
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only show trashed items
   * @return mixed Collection or LengthAwarePaginator
   */
  public function getAllAttackSubtypes(
    ?int $perPage = null, 
    ?Request $request = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false
  ): mixed {
    // Base query with relationships
    $query = AttackSubtype::withCount(['heroAbilities', 'cards']);
    
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
      $query->orderBy('id');
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
   * Create a new attack subtype
   *
   * @param array $data
   * @return AttackSubtype
   * @throws \Exception
   */
  public function create(array $data): AttackSubtype
  {
    $attackSubtype = new AttackSubtype();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($attackSubtype, $data, $this->translatableFields);
    
    $attackSubtype->save();
    
    return $attackSubtype;
  }

  /**
   * Update an existing attack subtype
   *
   * @param AttackSubtype $attackSubtype
   * @param array $data
   * @return AttackSubtype
   * @throws \Exception
   */
  public function update(AttackSubtype $attackSubtype, array $data): AttackSubtype
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($attackSubtype, $data, $this->translatableFields);
    
    $attackSubtype->save();
    
    return $attackSubtype;
  }

  /**
   * Delete an attack subtype (soft delete)
   *
   * @param AttackSubtype $attackSubtype
   * @return bool
   * @throws \Exception
   */
  public function delete(AttackSubtype $attackSubtype): bool
  {
    // Check for related cards
    if ($attackSubtype->cards()->count() > 0) {
      throw new \Exception("__('entities.attack_subtypes.errors.has_cards')");
    }
    
    // Check for related hero abilities
    if ($attackSubtype->heroAbilities()->count() > 0) {
      throw new \Exception("__('entities.attack_subtypes.errors.has_abilities')");
    }
    
    return $attackSubtype->delete();
  }

  /**
   * Restore a deleted attack subtype
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $attackSubtype = AttackSubtype::onlyTrashed()->findOrFail($id);
    return $attackSubtype->restore();
  }

  /**
   * Force delete an attack subtype permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $attackSubtype = AttackSubtype::onlyTrashed()->findOrFail($id);
    
    // Check for related cards (incluso para los eliminados)
    if ($attackSubtype->cards()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.attack_subtypes.errors.force_delete_has_cards')");
    }
    
    // Check for related hero abilities (incluso para los eliminados)
    if ($attackSubtype->heroAbilities()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.attack_subtypes.errors.force_delete_has_abilities')");
    }
    
    return $attackSubtype->forceDelete();
  }
}