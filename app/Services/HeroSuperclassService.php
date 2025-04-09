<?php

namespace App\Services;

use App\Models\HeroSuperclass;
use Illuminate\Database\Eloquent\Collection;

class HeroSuperclassService
{
  protected $imageService;

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
   * Get all superclasses with count of related hero classes
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
    $superclass = new HeroSuperclass();
    $superclass->name = $data['name'];
    
    // Handle icon if provided
    if (isset($data['icon']) && $data['icon'] instanceof \Illuminate\Http\UploadedFile) {
      $superclass->icon = $this->imageService->store($data['icon'], 'hero-superclass-icons');
    }
    
    $superclass->save();
    
    return $superclass;
  }

  /**
   * Update an existing superclass
   *
   * @param HeroSuperclass $superclass
   * @param array $data
   * @return HeroSuperclass
   */
  public function update(HeroSuperclass $superclass, array $data): HeroSuperclass
  {
    $superclass->name = $data['name'];
    
    // Handle icon removal
    if (isset($data['remove_icon']) && $data['remove_icon'] == "1") {
      $this->imageService->delete($superclass->icon);
      $superclass->icon = null;
    }
    // Handle icon update
    elseif (isset($data['icon']) && $data['icon'] instanceof \Illuminate\Http\UploadedFile) {
      $superclass->icon = $this->imageService->update($data['icon'], $superclass->icon, 'hero-superclass-icons');
    }
    
    $superclass->save();
    
    return $superclass;
  }

  /**
   * Delete a superclass
   *
   * @param HeroSuperclass $superclass
   * @return bool
   * @throws \Exception
   */
  public function delete(HeroSuperclass $superclass): bool
  {
    // Check for related hero classes
    if ($superclass->heroClasses()->count() > 0) {
      throw new \Exception("No se puede eliminar la superclase porque está siendo utilizada por clases de héroe.");
    }
    
    // Delete icon if exists
    if ($superclass->icon) {
      $this->imageService->delete($superclass->icon);
    }
    
    return $superclass->delete();
  }
}