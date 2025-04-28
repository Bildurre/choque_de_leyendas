<?php

namespace App\Services;

use App\Models\AttackRange;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class AttackRangeService
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
   * Get all attack ranges
   *
   * @return Collection
   */
  public function getAllRanges(): Collection
  {
    return AttackRange::withCount('abilities')->get();
  }

  /**
   * Create a new attack range
   *
   * @param array $data
   * @return AttackRange
   */
  public function create(array $data): AttackRange
  {
    $attackRange = new AttackRange();
    
    // Establecer traducciones directamente si es un array
    if (isset($data['name']) && is_array($data['name'])) {
      $attackRange->setTranslations('name', $data['name']);
    }
    
    // Handle icon if provided
    if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $attackRange->icon = $this->imageService->store($data['icon'], $attackRange->getImageDirectory());
    }
    
    $attackRange->save();
    
    return $attackRange;
  }

  /**
   * Update an existing attack range
   *
   * @param AttackRange $attackRange
   * @param array $data
   * @return AttackRange
   */
  public function update(AttackRange $attackRange, array $data): AttackRange
  {
    // Establecer traducciones directamente si es un array
    if (isset($data['name']) && is_array($data['name'])) {
      $attackRange->setTranslations('name', $data['name']);
    }
    
    // Handle icon removal
    if (isset($data['remove_icon']) && $data['remove_icon'] == "1") {
      $this->imageService->delete($attackRange->icon);
      $attackRange->icon = null;
    }
    // Handle icon update
    elseif (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $attackRange->icon = $this->imageService->update($data['icon'], $attackRange->icon, $attackRange->getImageDirectory());
    }
    
    $attackRange->save();
    
    return $attackRange;
  }

  /**
   * Delete an attack range
   *
   * @param AttackRange $attackRange
   * @return bool
   * @throws \Exception
   */
  public function delete(AttackRange $attackRange): bool
  {
    // Delete icon if exists
    if ($attackRange->icon) {
      $this->imageService->delete($attackRange->icon);
    }
    
    return $attackRange->delete();
  }
}