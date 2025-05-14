<?php

namespace App\Services\Game;

use App\Models\HeroSuperclass;
use App\Services\Traits\HandlesTranslations;

class HeroSuperclassService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];

  /**
   * Get all hero superclasses with optional pagination
   *
   * @param int|null $perPage Number of items per page, or null for all items
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only trashed items
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getAllHeroSuperclasses(int $perPage = null, bool $withTrashed = false, bool $onlyTrashed = false): mixed
  {
    $query = HeroSuperclass::withCount(['heroClasses']);
    
    // Apply trash filters
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    // Default ordering
    $query->orderBy('id');
    
    if ($perPage) {
      return $query->paginate($perPage);
    }
    
    return $query->get();
  }

  /**
   * Create a new hero superclass
   *
   * @param array $data
   * @return HeroSuperclass
   * @throws \Exception
   */
  public function create(array $data): HeroSuperclass
  {
    $heroSuperclass = new HeroSuperclass();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($heroSuperclass, $data, $this->translatableFields);
    
    $heroSuperclass->save();
    
    return $heroSuperclass;
  }

  /**
   * Update an existing hero superclass
   *
   * @param HeroSuperclass $heroSuperclass
   * @param array $data
   * @return HeroSuperclass
   * @throws \Exception
   */
  public function update(HeroSuperclass $heroSuperclass, array $data): HeroSuperclass
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($heroSuperclass, $data, $this->translatableFields);
    
    $heroSuperclass->save();
    
    return $heroSuperclass;
  }

  /**
   * Delete a hero superclass (soft delete)
   *
   * @param HeroSuperclass $heroSuperclass
   * @return bool
   * @throws \Exception
   */
  public function delete(HeroSuperclass $heroSuperclass): bool
  {
    // Check for related hero classes
    if ($heroSuperclass->heroClasses()->count() > 0) {
      throw new \Exception("__('entities.hero_superclasses.errors.has_classes')");
    }
    
    // Check for related card type
    if ($heroSuperclass->cardType()->exists()) {
      throw new \Exception("__('entities.hero_superclasses.errors.has_card_type')");
    }
    
    return $heroSuperclass->delete();
  }

  /**
   * Restore a deleted hero superclass
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $heroSuperclass = HeroSuperclass::onlyTrashed()->findOrFail($id);
    return $heroSuperclass->restore();
  }

  /**
   * Force delete a hero superclass permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $heroSuperclass = HeroSuperclass::onlyTrashed()->findOrFail($id);
    
    // Check for related hero classes (including trashed)
    if ($heroSuperclass->heroClasses()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.hero_superclasses.errors.force_delete_has_classes')");
    }
    
    // Check for related card type (including trashed)
    if ($heroSuperclass->cardType()->withTrashed()->exists()) {
      throw new \Exception("__('entities.hero_superclasses.errors.force_delete_has_card_type')");
    }
    
    return $heroSuperclass->forceDelete();
  }
}