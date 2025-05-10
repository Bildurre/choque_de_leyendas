<?php

namespace App\Services\Game;

use App\Models\HeroSuperclass;
use App\Services\Media\ImageService;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;

class HeroSuperclassService
{
  use HandlesTranslations;
  
  protected $imageService;
  protected $translatableFields = ['name'];

  /**
   * Create a new service instance.
   */
  public function __construct(ImageService $imageService)
  {
    $this->imageService = $imageService;
  }

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
    $query = HeroSuperclass::withCount(['heroClasses', 'cardType']);
    
    // Aplicar filtros de elementos eliminados
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
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
    
    // Handle icon upload
    if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $heroSuperclass->storeImage($data['icon']);
    }
    
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
    
    // Handle icon updates
    if (isset($data['remove_icon']) && $data['remove_icon']) {
      $heroSuperclass->deleteImage();
    } elseif (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $heroSuperclass->storeImage($data['icon']);
    }
    
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
      throw new \Exception("No se puede eliminar la superclase porque tiene clases asociadas.");
    }
    
    // Check for related card type
    if ($heroSuperclass->cardType()->exists()) {
      throw new \Exception("No se puede eliminar la superclase porque tiene un tipo de carta asociado.");
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
    
    // Check for related hero classes (incluso para los eliminados)
    if ($heroSuperclass->heroClasses()->withTrashed()->count() > 0) {
      throw new \Exception("No se puede eliminar permanentemente la superclase porque tiene clases asociadas.");
    }
    
    // Check for related card type (incluso para los eliminados)
    if ($heroSuperclass->cardType()->withTrashed()->exists()) {
      throw new \Exception("No se puede eliminar permanentemente la superclase porque tiene un tipo de carta asociado.");
    }
    
    // Delete icon if exists
    if ($heroSuperclass->hasImage()) {
      $heroSuperclass->deleteImage();
    }
    
    return $heroSuperclass->forceDelete();
  }
}