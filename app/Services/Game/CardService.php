<?php

namespace App\Services\Game;

use App\Models\Card;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;

class CardService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'lore_text', 'effect', 'restriction'];

  /**
   * Get all cards with optional pagination
   *
   * @param int|null $perPage Number of items per page, or null for all items
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only trashed items
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getAllCards(int $perPage = null, bool $withTrashed = false, bool $onlyTrashed = false): mixed
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