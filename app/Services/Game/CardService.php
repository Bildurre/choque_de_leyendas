<?php

namespace App\Services\Game;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Services\Traits\HandlesTranslations;

class CardService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'lore_text', 'epic_quote', 'effect', 'restriction'];

  public function getAllCards(
    ?Request $request = null,
    ?int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false,
    bool $onlyPublished = false,
    bool $onlyUnpublished = false
  ): mixed {
    $query = Card::with([
      'faction', 
      'cardType',
      'cardType.heroSuperclass',
      'cardSubtype',
      'equipmentType', 
      'attackRange', 
      'attackSubtype',
      'heroAbility',
      'heroAbility.attackRange',
      'heroAbility.attackSubtype'
    ]);
    
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    if ($onlyPublished) {
      $query->where('is_published', true);
    } elseif ($onlyUnpublished) {
      $query->where('is_published', false);
    }
    
    $totalCount = $query->count();
    
    if ($request) {
      $query->applyAdminFilters($request);
    }
    
    $filteredCount = $query->count();
    
    if (!$request || !$request->has('sort')) {
      $query->orderBy('card_type_id')->orderBy('id');
    }

    if ($perPage) {
      $result = $query->paginate($perPage)->withQueryString();
      
      $result->totalCount = $totalCount;
      $result->filteredCount = $filteredCount;
      
      return $result;
    }
    
    return $query->get();
  }

  public function create(array $data): Card
  {
    $card = new Card();
    
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $this->applyTranslations($card, $data, $this->translatableFields);
    
    $this->setCardFields($card, $data);
    
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $card->storeImage($data['image']);
    }
    
    $card->save();
    
    return $card;
  }

  public function update(Card $card, array $data): Card
  {
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $this->applyTranslations($card, $data, $this->translatableFields);
    
    $this->setCardFields($card, $data);
    
    if (isset($data['remove_image']) && $data['remove_image']) {
      $card->deleteImage();
    } elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $card->storeImage($data['image']);
    }
    
    $card->save();
    
    return $card;
  }

  private function setCardFields(Card $card, array $data): void
  {
    $fields = [
      'faction_id', 'card_type_id', 'card_subtype_id', 'equipment_type_id',
      'attack_range_id', 'attack_subtype_id', 'hero_ability_id',
      'hands', 'cost', 'attack_type'
    ];
    
    $fillable = [];
    
    foreach ($fields as $field) {
      if (isset($data[$field])) {
        $fillable[$field] = $data[$field];
      }
    }
    
    $card->fill($fillable);
    
    $card->area = isset($data['area']) ? (bool)$data['area'] : false;
    $card->is_unique = isset($data['is_unique']) ? (bool)$data['is_unique'] : false;
    $card->is_published = isset($data['is_published']) ? (bool)$data['is_published'] : false;
  }

  public function delete(Card $card): bool
  {
    return $card->delete();
  }

  public function restore(int $id): bool
  {
    $card = Card::onlyTrashed()->findOrFail($id);
    return $card->restore();
  }

  public function forceDelete(int $id): bool
  {
    $card = Card::onlyTrashed()->findOrFail($id);
    
    if ($card->hasImage()) {
      $card->deleteImage();
    }
    
    return $card->forceDelete();
  }
}