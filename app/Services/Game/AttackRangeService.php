<?php

namespace App\Services\Game;

use App\Models\AttackRange;
use App\Services\Media\ImageService;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;

class AttackRangeService
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
   * Get all attack ranges with optional pagination
   *
   * @param int|null $perPage Number of items per page, or null for all items
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only trashed items
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getAllAttackRanges(int $perPage = null, bool $withTrashed = false, bool $onlyTrashed = false): mixed
  {
    $query = AttackRange::withCount(['heroAbilities', 'cards']);
    
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
    
    // Handle icon upload
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
   * @throws \Exception
   */
  public function update(AttackRange $attackRange, array $data): AttackRange
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($attackRange, $data, $this->translatableFields);
    
    // Handle icon updates
    if (isset($data['remove_icon']) && $data['remove_icon']) {
      $this->imageService->delete($attackRange->icon);
      $attackRange->icon = null;
    } elseif (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $attackRange->icon = $this->imageService->update(
        $data['icon'], 
        $attackRange->icon, 
        $attackRange->getImageDirectory()
      );
    }
    
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
      throw new \Exception("No se puede eliminar el rango de ataque porque tiene habilidades de héroe asociadas.");
    }
    
    // Check for related cards
    if ($attackRange->cards()->count() > 0) {
      throw new \Exception("No se puede eliminar el rango de ataque porque tiene cartas asociadas.");
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
      throw new \Exception("No se puede eliminar permanentemente el rango de ataque porque tiene habilidades de héroe asociadas.");
    }
    
    // Check for related cards (incluso para los eliminados)
    if ($attackRange->cards()->withTrashed()->count() > 0) {
      throw new \Exception("No se puede eliminar permanentemente el rango de ataque porque tiene cartas asociadas.");
    }
    
    // Delete icon if exists
    if ($attackRange->icon) {
      $this->imageService->delete($attackRange->icon);
    }
    
    return $attackRange->forceDelete();
  }
}