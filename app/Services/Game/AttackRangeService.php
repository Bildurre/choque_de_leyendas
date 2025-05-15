<?php

namespace App\Services\Game;

use App\Models\AttackRange;
use Illuminate\Http\Request;
use App\Services\Traits\HandlesTranslations;

class AttackRangeService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];

  /**
 * Get all attack ranges with optional filtering and pagination
 * 
 * @param Request|null $request Request object for filtering
 * @param int|null $perPage Number of items per page (null for no pagination)
 * @param bool $withTrashed Include trashed items
 * @param bool $onlyTrashed Only show trashed items
 * @return mixed Collection or LengthAwarePaginator
 */
public function getAllAttackRanges(
  ?Request $request = null,
  ?int $perPage = null, 
  bool $withTrashed = false, 
  bool $onlyTrashed = false
): mixed {
  // Base query with relationships and counts
  $query = AttackRange::withCount(['heroAbilities', 'cards']);
  
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
   * Create a new attack range
   *
   * @param array $data
   * @return AttackRange
   * @throws \Exception
   */
  public function create(array $data): AttackRange
  {
    $attackRange = new AttackRange();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($attackRange, $data, $this->translatableFields);
    
    $attackRange->save();
    
    return $attackRange;
  }

  /**
   * Update an existing attack range
   *
   * @param AttackRange $attackRange
   * @param array $data
   * @return AttackRange
   * @throws \Exception
   */
  public function update(AttackRange $attackRange, array $data): AttackRange
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($attackRange, $data, $this->translatableFields);
    
    $attackRange->save();
    
    return $attackRange;
  }

  /**
   * Delete an attack range (soft delete)
   *
   * @param AttackRange $attackRange
   * @return bool
   * @throws \Exception
   */
  public function delete(AttackRange $attackRange): bool
  {
    // Check for related hero abilities
    if ($attackRange->heroAbilities()->count() > 0) {
      throw new \Exception("__('entities.attack_ranges.errors.has_abilities')");
    }
    
    // Check for related cards
    if ($attackRange->cards()->count() > 0) {
      throw new \Exception("__('entities.attack_ranges.errors.has_cards')");
    }
    
    return $attackRange->delete();
  }

  /**
   * Restore a deleted attack range
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $attackRange = AttackRange::onlyTrashed()->findOrFail($id);
    return $attackRange->restore();
  }

  /**
   * Force delete an attack range permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $attackRange = AttackRange::onlyTrashed()->findOrFail($id);
    
    // Check for related hero abilities (incluso para los eliminados)
    if ($attackRange->heroAbilities()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.attack_ranges.errors.force_delete_has_abilities')");
    }
    
    // Check for related cards (incluso para los eliminados)
    if ($attackRange->cards()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.attack_ranges.errors.force_delete_has_cards')");
    }
    
    return $attackRange->forceDelete();
  }
}