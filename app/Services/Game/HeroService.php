<?php

namespace App\Services\Game;

use App\Models\Hero;
use App\Models\HeroAbility;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class HeroService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'lore_text', 'passive_name', 'passive_description'];

  /**
   * Get all heroes with optional pagination and filters
   *
   * @param int|null $perPage Number of items per page, or null for all items
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only trashed items
   * @param array $filters Additional filters
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getAllHeroes(int $perPage = null, bool $withTrashed = false, bool $onlyTrashed = false, array $filters = []): mixed
  {
    $query = Hero::with([
      'faction', 
      'heroRace', 
      'heroClass', 
      'heroAbilities'
    ]);
    
    // Apply trash filters
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    // Apply additional filters
    if (!empty($filters)) {
      // Filter by faction
      if (isset($filters['faction_id']) && $filters['faction_id'] !== '') {
        if ($filters['faction_id'] === 'no_faction') {
          $query->whereNull('faction_id');
        } else {
          $query->where('faction_id', $filters['faction_id']);
        }
      }
      
      // Filter by race
      if (isset($filters['hero_race_id']) && $filters['hero_race_id'] !== '') {
        $query->where('hero_race_id', $filters['hero_race_id']);
      }
      
      // Filter by class
      if (isset($filters['hero_class_id']) && $filters['hero_class_id'] !== '') {
        $query->where('hero_class_id', $filters['hero_class_id']);
      }
      
      // Filter by gender
      if (isset($filters['gender']) && $filters['gender'] !== '') {
        $query->where('gender', $filters['gender']);
      }
      
      // Filter by ability
      if (isset($filters['hero_ability_id']) && $filters['hero_ability_id'] !== '') {
        $query->whereHas('heroAbilities', function ($q) use ($filters) {
          $q->where('hero_ability_id', $filters['hero_ability_id']);
        });
      }
      
      // Filter by search term
      if (isset($filters['search']) && !empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function($q) use ($search) {
          $q->whereRaw("JSON_CONTAINS(LOWER(name), LOWER(?), '$')", [json_encode($search)])
            ->orWhereRaw("JSON_CONTAINS(LOWER(lore_text), LOWER(?), '$')", [json_encode($search)])
            ->orWhereRaw("JSON_CONTAINS(LOWER(passive_name), LOWER(?), '$')", [json_encode($search)])
            ->orWhereRaw("JSON_CONTAINS(LOWER(passive_description), LOWER(?), '$')", [json_encode($search)]);
        });
      }
    }
    
    // Default ordering
    $query->orderBy('faction_id')->orderBy('id');
    
    if ($perPage) {
      return $query->paginate($perPage)->withQueryString();
    }
    
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
    // Set relation IDs
    $hero->faction_id = $data['faction_id'] ?? null;
    $hero->hero_race_id = $data['hero_race_id'] ?? null;
    $hero->hero_class_id = $data['hero_class_id'] ?? null;
    
    // Set attributes
    $hero->gender = $data['gender'] ?? 'male';
    $hero->agility = $data['agility'] ?? 2;
    $hero->mental = $data['mental'] ?? 2;
    $hero->will = $data['will'] ?? 2;
    $hero->strength = $data['strength'] ?? 2;
    $hero->armor = $data['armor'] ?? 2;
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

  /**
   * Get heroes count by faction
   * 
   * @return array
   */
  public function getCountsByFaction(): array
  {
    $counts = [];
    $factions = \App\Models\Faction::all();
    
    foreach ($factions as $faction) {
      $counts[$faction->id] = Hero::where('faction_id', $faction->id)->count();
    }
    
    // Add count for heroes without faction
    $counts['no_faction'] = Hero::whereNull('faction_id')->count();
    
    return $counts;
  }

  /**
   * Get heroes count by race
   * 
   * @return array
   */
  public function getCountsByRace(): array
  {
    $counts = [];
    $races = \App\Models\HeroRace::all();
    
    foreach ($races as $race) {
      $counts[$race->id] = Hero::where('hero_race_id', $race->id)->count();
    }
    
    return $counts;
  }

  /**
   * Get heroes count by class
   * 
   * @return array
   */
  public function getCountsByClass(): array
  {
    $counts = [];
    $classes = \App\Models\HeroClass::all();
    
    foreach ($classes as $class) {
      $counts[$class->id] = Hero::where('hero_class_id', $class->id)->count();
    }
    
    return $counts;
  }
}