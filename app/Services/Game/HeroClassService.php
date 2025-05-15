<?php

namespace App\Services\Game;

use App\Models\HeroClass;
use Illuminate\Http\Request;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Database\Eloquent\Collection;

class HeroClassService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'passive'];

  /**
   * Get all hero classes with optional filtering and pagination
   * 
   * @param Request|null $request Request object for filtering
   * @param int|null $perPage Number of items per page (null for no pagination)
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only show trashed items
   * @return mixed Collection or LengthAwarePaginator
   */
  public function getAllHeroClasses(
    ?Request $request = null,
    ?int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false
  ): mixed {
    // Base query with relationships and counts
    $query = HeroClass::with('heroSuperclass')->withCount('heroes');
    
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
   * Delete a hero class (soft delete)
   *
   * @param HeroClass $heroClass
   * @return bool
   * @throws \Exception
   */
  public function delete(HeroClass $heroClass): bool
  {
    // Check for related heroes
    if ($heroClass->heroes()->count() > 0) {
      throw new \Exception("__('entities.hero_classes.errors.has_heroes')");
    }
    
    return $heroClass->delete(); // Ahora esto realizará un soft delete
  }

  /**
   * Restore a deleted hero class
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $heroClass = HeroClass::onlyTrashed()->findOrFail($id);
    return $heroClass->restore();
  }

  /**
   * Force delete a hero class permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $heroClass = HeroClass::onlyTrashed()->findOrFail($id);
    
    // Check for related heroes (incluso para los eliminados)
    if ($heroClass->heroes()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.hero_classes.errors.force_delete_has_heroes')");
    }
    
    return $heroClass->forceDelete();
  }
}