<?php

namespace App\Services\Game;

use App\Models\Counter;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;

class CounterService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'effect'];

  /**
   * Get counters by tab with pagination
   *
   * @param string $tab The active tab (boons, banes, trashed)
   * @param int|null $perPage Number of items per page, or null for all items
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getCountersByTab(string $tab, ?int $perPage = null)
  {
    $query = Counter::query();
    
    switch ($tab) {
      case 'boons':
        $query->where('type', 'boon');
        break;
      case 'banes':
        $query->where('type', 'bane');
        break;
      case 'trashed':
        $query->onlyTrashed();
        break;
    }
    
    // Default ordering by name
    $query->orderBy('id');
    
    if ($perPage) {
      return $query->paginate($perPage)->withQueryString();
    }
    
    return $query->get();
  }

  /**
   * Get counts of counters by category and trash status
   * 
   * @return array
   */
  public function getCountsByCategoryAndTrash(): array
  {
    return [
      'boons' => Counter::where('type', 'boon')->count(),
      'banes' => Counter::where('type', 'bane')->count(),
      'trashed' => Counter::onlyTrashed()->count()
    ];
  }

  /**
   * Create a new counter
   *
   * @param array $data
   * @return Counter
   * @throws \Exception
   */
  public function create(array $data): Counter
  {
    $counter = new Counter();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($counter, $data, $this->translatableFields);
    
    // Set type
    $counter->type = $data['type'];
    
    // Handle icon upload
    if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $counter->storeImage($data['icon']);
    }
    
    $counter->save();
    
    return $counter;
  }

  /**
   * Update an existing counter
   *
   * @param Counter $counter
   * @param array $data
   * @return Counter
   * @throws \Exception
   */
  public function update(Counter $counter, array $data): Counter
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($counter, $data, $this->translatableFields);
    
    // Update type if present
    if (isset($data['type'])) {
      $counter->type = $data['type'];
    }
    
    // Handle icon updates
    if (isset($data['remove_icon']) && $data['remove_icon']) {
      $counter->deleteImage();
    } elseif (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $counter->storeImage($data['icon']);
    }
    
    $counter->save();
    
    return $counter;
  }

  /**
   * Delete a counter (soft delete)
   *
   * @param Counter $counter
   * @return bool
   * @throws \Exception
   */
  public function delete(Counter $counter): bool
  {
    // Check if counter is being used by any related entities
    // Add implementation for checking relations when they exist
    
    return $counter->delete();
  }

  /**
   * Restore a deleted counter
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $counter = Counter::onlyTrashed()->findOrFail($id);
    return $counter->restore();
  }

  /**
   * Force delete a counter permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $counter = Counter::onlyTrashed()->findOrFail($id);
    
    // Check if counter is being used by any related entities (including trashed)
    // Add implementation for checking relations when they exist
    
    // Delete icon if exists
    if ($counter->hasImage()) {
      $counter->deleteImage();
    }
    
    return $counter->forceDelete();
  }
}