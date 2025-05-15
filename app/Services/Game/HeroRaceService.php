<?php

namespace App\Services\Game;

use App\Models\HeroRace;
use Illuminate\Http\Request;
use App\Services\Traits\HandlesTranslations;

class HeroRaceService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];

  /**
   * Get all hero races with optional filtering and pagination
   * 
   * @param Request|null $request Request object for filtering
   * @param int|null $perPage Number of items per page (null for no pagination)
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only show trashed items
   * @return mixed Collection or LengthAwarePaginator
   */
  public function getAllHeroRaces(
    ?Request $request = null,
    ?int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false
  ): mixed {
    // Base query with counts
    $query = HeroRace::withCount('heroes');
    
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
   * Create a new hero race
   *
   * @param array $data
   * @return HeroRace
   * @throws \Exception
   */
  public function create(array $data): HeroRace
  {
    $heroRace = new HeroRace();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($heroRace, $data, $this->translatableFields);
    
    $heroRace->save();
    
    return $heroRace;
  }

  /**
   * Update an existing hero race
   *
   * @param HeroRace $heroRace
   * @param array $data
   * @return HeroRace
   * @throws \Exception
   */
  public function update(HeroRace $heroRace, array $data): HeroRace
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($heroRace, $data, $this->translatableFields);
    
    $heroRace->save();
    
    return $heroRace;
  }

  /**
   * Delete a hero race (soft delete)
   *
   * @param HeroRace $heroRace
   * @return bool
   * @throws \Exception
   */
  public function delete(HeroRace $heroRace): bool
  {
    // Check for related heroes
    if ($heroRace->heroes()->count() > 0) {
      throw new \Exception("__('entities.hero_races.errors.has_heroes')");
    }
    
    return $heroRace->delete();
  }

  /**
   * Restore a deleted hero race
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $heroRace = HeroRace::onlyTrashed()->findOrFail($id);
    return $heroRace->restore();
  }

  /**
   * Force delete a hero race permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $heroRace = HeroRace::onlyTrashed()->findOrFail($id);
    
    // Check for related heroes (including trashed)
    if ($heroRace->heroes()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.hero_races.errors.force_delete_has_heroes')");
    }
    
    return $heroRace->forceDelete();
  }
}