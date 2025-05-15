<?php

namespace App\Services\Game;

use App\Models\Hero;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Services\Traits\HandlesTranslations;

class HeroService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'lore_text', 'passive_name', 'passive_description'];

  /**
   * Get all heroes with optional filtering and pagination
   * 
   * @param int|null $perPage Number of items per page (null for no pagination)
   * @param Request $request Request object for filtering
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only show trashed items
   * @return mixed Collection or LengthAwarePaginator
   */
  public function getAllHeroes(
    ?int $perPage = null, 
    ?Request $request = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false
  ): mixed {
    // Base query with relationships
    $query = Hero::with([
      'faction', 
      'heroRace', 
      'heroClass', 
      'heroAbilities.attackRange', 
      'heroAbilities.attackSubtype'
    ]);
    
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
      $query->orderBy('faction_id')->orderBy('id');
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
   * Create a new hero
   *
   * @param array $data
   * @return Hero
   * @throws \Exception
   */
  public function create(array $data): Hero
  {
    try {
      // Start a transaction
      DB::beginTransaction();
      
      $hero = new Hero();
      
      // Process translatable fields
      $data = $this->processTranslatableFields($data, $this->translatableFields);
      
      // Apply translations
      $this->applyTranslations($hero, $data, $this->translatableFields);
      
      // Set regular fields
      $this->setHeroFields($hero, $data);
      
      // Handle image upload
      if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
        $hero->storeImage($data['image']);
      }
      
      $hero->save();
      
      // Sync hero abilities
      if (isset($data['hero_abilities']) && is_array($data['hero_abilities'])) {
        $hero->heroAbilities()->sync($data['hero_abilities']);
      }
      
      // Commit the transaction
      DB::commit();
      
      return $hero;
    } catch (\Exception $e) {
      // Rollback the transaction in case of error
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Update an existing hero
   *
   * @param Hero $hero
   * @param array $data
   * @return Hero
   * @throws \Exception
   */
  public function update(Hero $hero, array $data): Hero
  {
    try {
      // Start a transaction
      DB::beginTransaction();
      
      // Process translatable fields
      $data = $this->processTranslatableFields($data, $this->translatableFields);
      
      // Apply translations
      $this->applyTranslations($hero, $data, $this->translatableFields);
      
      // Set regular fields
      $this->setHeroFields($hero, $data);
      
      // Handle image updates
      if (isset($data['remove_image']) && $data['remove_image']) {
        $hero->deleteImage();
      } elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
        $hero->storeImage($data['image']);
      }
      
      $hero->save();
      
      // Sync hero abilities
      if (isset($data['hero_abilities']) && is_array($data['hero_abilities'])) {
        $hero->heroAbilities()->sync($data['hero_abilities']);
      }
      
      // Commit the transaction
      DB::commit();
      
      return $hero;
    } catch (\Exception $e) {
      // Rollback the transaction in case of error
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Set the hero fields from data array
   *
   * @param Hero $hero
   * @param array $data
   * @return void
   */
  private function setHeroFields(Hero $hero, array $data): void
  {
    $fillable = [
      'faction_id' => $data['faction_id'] ?? null,
      'hero_race_id' => $data['hero_race_id'] ?? null,
      'hero_class_id' => $data['hero_class_id'] ?? null,
      'gender' => $data['gender'] ?? 'male',
      'agility' => $data['agility'] ?? 2,
      'mental' => $data['mental'] ?? 2,
      'will' => $data['will'] ?? 2,
      'strength' => $data['strength'] ?? 2,
      'armor' => $data['armor'] ?? 2,
    ];
    
    $hero->fill($fillable);
  }

  /**
   * Delete a hero (soft delete)
   *
   * @param Hero $hero
   * @return bool
   * @throws \Exception
   */
  public function delete(Hero $hero): bool
  {
    return $hero->delete();
  }

  /**
   * Restore a deleted hero
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $hero = Hero::onlyTrashed()->findOrFail($id);
    return $hero->restore();
  }

  /**
   * Force delete a hero permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $hero = Hero::onlyTrashed()->findOrFail($id);
    
    // Delete image if exists
    if ($hero->hasImage()) {
      $hero->deleteImage();
    }
    
    // Remove ability associations
    $hero->heroAbilities()->detach();
    
    return $hero->forceDelete();
  }
}