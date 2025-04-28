<?php

namespace App\Services;

use App\Models\HeroSuperclass;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Traits\HandlesTranslations;

class HeroSuperclassService
{
  use HandlesTranslations;
  
  protected $imageService;
  protected $translatableFields = ['name'];

  /**
   * Create a new service instance.
   *
   * @param ImageService $imageService
   */
  public function __construct(ImageService $imageService)
  {
    $this->imageService = $imageService;
  }

  /**
   * Get all hero superclasses with count of related hero classes
   *
   * @return Collection
   */
  public function getAllHeroSuperclasses(): Collection
  {
    return HeroSuperclass::withCount('heroClasses')->get();
  }

  /**
   * Create a new superclass
   *
   * @param array $data
   * @return HeroSuperclass
   */
  public function create(array $data): HeroSuperclass
  {
    $heroSuperclass = new HeroSuperclass();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($heroSuperclass, $data, $this->translatableFields);
    
    // Handle icon if provided
    if (isset($data['icon']) && $data['icon'] instanceof \Illuminate\Http\UploadedFile) {
      $heroSuperclass->icon = $this->imageService->store($data['icon'], $heroSuperclass->getImageDirectory());
    }
    
    $heroSuperclass->save();
    
    return $heroSuperclass;
  }

  /**
   * Update an existing superclass
   *
   * @param HeroSuperclass $heroSuperclass
   * @param array $data
   * @return HeroSuperclass
   */
  public function update(HeroSuperclass $heroSuperclass, array $data): HeroSuperclass
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($heroSuperclass, $data, $this->translatableFields);
    
    // Handle icon removal
    if (isset($data['remove_icon']) && $data['remove_icon'] == "1") {
      $this->imageService->delete($heroSuperclass->icon);
      $heroSuperclass->icon = null;
    }
    // Handle icon update
    elseif (isset($data['icon']) && $data['icon'] instanceof \Illuminate\Http\UploadedFile) {
      $heroSuperclass->icon = $this->imageService->update($data['icon'], $heroSuperclass->icon, $heroSuperclass->getImageDirectory());
    }
    
    $heroSuperclass->save();
    
    return $heroSuperclass;
  }

  /**
   * Delete a superclass
   *
   * @param HeroSuperclass $heroSuperclass
   * @return bool
   * @throws \Exception
   */
  public function delete(HeroSuperclass $heroSuperclass): bool
  {
    // Check for related hero classes
    if ($heroSuperclass->heroClasses()->count() > 0) {
      throw new \Exception("No se puede eliminar la superclase porque está siendo utilizada por clases de héroe.");
    }
    
    // Delete icon if exists
    if ($heroSuperclass->icon) {
      $this->imageService->delete($heroSuperclass->icon);
    }
    
    return $heroSuperclass->delete();
  }
}