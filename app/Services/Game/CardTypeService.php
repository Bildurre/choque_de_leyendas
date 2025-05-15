<?php

namespace App\Services\Game;

use App\Models\CardType;
use Illuminate\Http\Request;
use App\Services\Traits\HandlesTranslations;

class CardTypeService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];

  /**
   * Get all card types with optional filtering and pagination
   * 
   * @param Request|null $request Request object for filtering
   * @param int|null $perPage Number of items per page (null for no pagination)
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only show trashed items
   * @return mixed Collection or LengthAwarePaginator
   */
  public function getAllCardTypes(
    ?Request $request = null,
    ?int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false
  ): mixed {
    // Base query with relationships and counts
    $query = CardType::with('heroSuperclass')->withCount('cards');
    
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
   * Create a new card type
   *
   * @param array $data
   * @return CardType
   * @throws \Exception
   */
  public function create(array $data): CardType
  {
    $cardType = new CardType();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($cardType, $data, $this->translatableFields);
    
    // Set non-translatable fields
    if (isset($data['hero_superclass_id']) && !empty($data['hero_superclass_id'])) {
      $cardType->hero_superclass_id = $data['hero_superclass_id'];
    }
    
    $cardType->save();
    
    return $cardType;
  }

  /**
   * Update an existing card type
   *
   * @param CardType $cardType
   * @param array $data
   * @return CardType
   * @throws \Exception
   */
  public function update(CardType $cardType, array $data): CardType
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($cardType, $data, $this->translatableFields);
    
    // Update non-translatable fields
    if (array_key_exists('hero_superclass_id', $data)) {
      $cardType->hero_superclass_id = !empty($data['hero_superclass_id']) ? $data['hero_superclass_id'] : null;
    }
    
    $cardType->save();
    
    return $cardType;
  }

  /**
   * Delete a card type (soft delete)
   *
   * @param CardType $cardType
   * @return bool
   * @throws \Exception
   */
  public function delete(CardType $cardType): bool
  {
    // Check for related cards
    if ($cardType->cards()->count() > 0) {
      throw new \Exception("__('entities.card_types.errors.has_cards')");
    }
    
    return $cardType->delete();
  }

  /**
   * Restore a deleted card type
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $cardType = CardType::onlyTrashed()->findOrFail($id);
    return $cardType->restore();
  }

  /**
   * Force delete a card type permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $cardType = CardType::onlyTrashed()->findOrFail($id);
    
    // Check for related cards (incluso para los eliminados)
    if ($cardType->cards()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.card_types.errors.force_delete_has_cards')");
    }
    
    return $cardType->forceDelete();
  }

  /**
   * Get available hero superclasses for assigning to card types
   * 
   * @param int|null $exceptCardTypeId Card type to exclude from the check
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getAvailableHeroSuperclasses(?int $exceptCardTypeId = null): \Illuminate\Database\Eloquent\Collection
  {
    // Get all superclasses
    $allSuperclasses = \App\Models\HeroSuperclass::all();
    
    // Get IDs of superclasses already assigned to card types
    $assignedSuperclassIds = CardType::query()
      ->when($exceptCardTypeId, function ($query) use ($exceptCardTypeId) {
        $query->where('id', '!=', $exceptCardTypeId);
      })
      ->whereNotNull('hero_superclass_id')
      ->pluck('hero_superclass_id');
    
    // Filter out already assigned superclasses
    return $allSuperclasses->filter(function ($superclass) use ($assignedSuperclassIds) {
      return !$assignedSuperclassIds->contains($superclass->id);
    });
  }
}