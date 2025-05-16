<?php

namespace App\Services\Game;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Services\Traits\HandlesTranslations;

class CardService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'lore_text', 'effect', 'restriction'];

  /**
   * Get all cards with optional filtering and pagination
   * 
   * @param Request|null $request Request object for filtering
   * @param int|null $perPage Number of items per page (null for no pagination)
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only show trashed items
   * @return mixed Collection or LengthAwarePaginator
   */
  public function getAllCards(
    ?Request $request = null,
    ?int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false
  ): mixed {
    // Base query with relationships
    $query = Card::with([
      'faction', 
      'cardType', 
      'equipmentType', 
      'attackRange', 
      'attackSubtype',
      'heroAbility'
    ]);
    
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
      $query->orderBy('card_type_id')->orderBy('id');
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
   * Create a new card
   *
   * @param array $data
   * @return Card
   * @throws \Exception
   */
  public function create(array $data): Card
  {
    $card = new Card();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($card, $data, $this->translatableFields);
    
    // Set regular fields
    $this->setCardFields($card, $data);
    
    // Handle image upload
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $card->storeImage($data['image']);
    }
    
    $card->save();
    
    return $card;
  }

  /**
   * Update an existing card
   *
   * @param Card $card
   * @param array $data
   * @return Card
   * @throws \Exception
   */
  public function update(Card $card, array $data): Card
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($card, $data, $this->translatableFields);
    
    // Set regular fields
    $this->setCardFields($card, $data);
    
    // Handle image updates
    if (isset($data['remove_image']) && $data['remove_image']) {
      $card->deleteImage();
    } elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $card->storeImage($data['image']);
    }
    
    $card->save();
    
    return $card;
  }

  /**
   * Set the card fields from data array
   *
   * @param Card $card
   * @param array $data
   * @return void
   */
  private function setCardFields(Card $card, array $data): void
  {
    $fields = [
      'faction_id', 'card_type_id', 'equipment_type_id',
      'attack_range_id', 'attack_subtype_id', 'hero_ability_id',
      'hands', 'cost'
    ];
    
    $fillable = [];
    
    foreach ($fields as $field) {
      if (isset($data[$field])) {
        $fillable[$field] = $data[$field];
      }
    }
    
    $card->fill($fillable);
    
    // Area needs special handling for boolean conversion
    $card->area = isset($data['area']) ? (bool)$data['area'] : false;
    $card->is_published = isset($data['is_published']) ? (bool)$data['is_published'] : false;
  }

  /**
   * Delete a card (soft delete)
   *
   * @param Card $card
   * @return bool
   * @throws \Exception
   */
  public function delete(Card $card): bool
  {
    return $card->delete();
  }

  /**
   * Restore a deleted card
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $card = Card::onlyTrashed()->findOrFail($id);
    return $card->restore();
  }

  /**
   * Force delete a card permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $card = Card::onlyTrashed()->findOrFail($id);
    
    // Delete image if exists
    if ($card->hasImage()) {
      $card->deleteImage();
    }
    
    return $card->forceDelete();
  }
}