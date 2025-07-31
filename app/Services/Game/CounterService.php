<?php

namespace App\Services\Game;

use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Services\Traits\HandlesTranslations;

class CounterService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'effect'];

  /**
   * Get all counters with optional filtering and pagination
   * 
   * @param Request|null $request Request object for filtering
   * @param int|null $perPage Number of items per page (null for no pagination)
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only show trashed items
   * @param bool $onlyPublished Only show published items
   * @param bool $onlyUnpublished Only show unpublished items
   * @return mixed Collection or LengthAwarePaginator
   */
  public function getAllCounters(
    ?Request $request = null,
    ?int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false,
    bool $onlyPublished = false,
    bool $onlyUnpublished = false
  ): mixed {
    // Base query
    $query = Counter::query();
    
    // Apply trash filters
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    // Apply published filters
    if ($onlyPublished) {
      $query->where('is_published', true);
    } elseif ($onlyUnpublished) {
      $query->where('is_published', false);
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

    $counter->is_published = isset($data['is_published']) ? (bool)$data['is_published'] : false;
    
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

    if (isset($data['is_published'])) {
      $counter->is_published = (bool)$data['is_published'];
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