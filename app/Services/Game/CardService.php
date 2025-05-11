<?php

namespace App\Services\Game;

use App\Models\Card;
use App\Services\Media\ImageService;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;

class CardService
{
  use HandlesTranslations;
  
  protected $imageService;
  protected $translatableFields = ['name', 'lore_text', 'effect', 'restriction'];

  /**
   * Create a new service instance.
   */
  public function __construct(ImageService $imageService)
  {
    $this->imageService = $imageService;
  }

  /**
   * Get all cards with optional pagination and filters
   *
   * @param int|null $perPage Number of items per page, or null for all items
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only trashed items
   * @param array $filters Additional filters
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getAllCards(int $perPage = null, bool $withTrashed = false, bool $onlyTrashed = false, array $filters = []): mixed
  {
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
    
    // Apply additional filters
    if (!empty($filters)) {
      // Filter by faction
      if (isset($filters['faction_id'])) {
        $query->where('faction_id', $filters['faction_id']);
      }
      
      // Filter by card type
      if (isset($filters['card_type_id'])) {
        $query->where('card_type_id', $filters['card_type_id']);
      }
      
      // Filter by equipment type
      if (isset($filters['equipment_type_id'])) {
        $query->where('equipment_type_id', $filters['equipment_type_id']);
      }
      
      // Filter by attack subtype
      if (isset($filters['attack_subtype_id'])) {
        $query->where('attack_subtype_id', $filters['attack_subtype_id']);
      }
      
      // Filter by cost
      if (isset($filters['cost'])) {
        $costValue = $filters['cost'];
        if ($costValue === 'free') {
          $query->whereNull('cost')->orWhere('cost', '');
        } else {
          $query->where('cost', 'like', "%$costValue%");
        }
      }
      
      // Filter by area attacks
      if (isset($filters['area']) && $filters['area']) {
        $query->where('area', true);
      }
      
      // Filter by name search
      if (isset($filters['search']) && !empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function($q) use ($search) {
          $q->whereRaw("JSON_CONTAINS(LOWER(name), LOWER(?), '$')", [json_encode($search)])
            ->orWhereRaw("JSON_CONTAINS(LOWER(effect), LOWER(?), '$')", [json_encode($search)])
            ->orWhereRaw("JSON_CONTAINS(LOWER(restriction), LOWER(?), '$')", [json_encode($search)]);
        });
      }
    }
    
    // Default ordering by card type and name
    $query->orderBy('card_type_id')->orderBy('id');
    
    if ($perPage) {
      return $query->paginate($perPage)->withQueryString();
    }
    
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
    // Set relation IDs with null checks
    $card->faction_id = $data['faction_id'] ?? null;
    $card->card_type_id = $data['card_type_id'] ?? null;
    $card->equipment_type_id = $data['equipment_type_id'] ?? null;
    $card->attack_range_id = $data['attack_range_id'] ?? null;
    $card->attack_subtype_id = $data['attack_subtype_id'] ?? null;
    $card->hero_ability_id = $data['hero_ability_id'] ?? null;
    
    // Set other fields with null checks
    $card->hands = $data['hands'] ?? null;
    $card->cost = $data['cost'] ?? null;
    $card->area = isset($data['area']) ? (bool)$data['area'] : false;
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