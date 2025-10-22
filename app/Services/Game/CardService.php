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
    $cardTypeId = $data['card_type_id'] ?? null;
    $equipmentTypeCategory = null;
    
    // Get equipment type category if exists
    if (isset($data['equipment_type_id']) && $data['equipment_type_id']) {
      $equipmentType = \App\Models\EquipmentType::find($data['equipment_type_id']);
      $equipmentTypeCategory = $equipmentType?->category;
    }
    
    // Initialize all fields
    $fields = [
      'faction_id' => $data['faction_id'] ?? null,
      'card_type_id' => $cardTypeId,
      'card_subtype_id' => null,
      'equipment_type_id' => null,
      'hero_ability_id' => null,
      'hands' => null,
      'attack_range_id' => null,
      'attack_type' => null,
      'attack_subtype_id' => null,
      'cost' => $data['cost'] ?? null,
    ];
    
    // Apply logic based on card type
    if (in_array($cardTypeId, [2, 3])) {
      // Ardid (2) or Apoyo (3) - all special fields remain null
      // No action needed, already set to null
    } elseif ($cardTypeId == 1) {
      // Equipment type
      $fields['equipment_type_id'] = $data['equipment_type_id'] ?? null;
      
      // Only weapons can have hands and hero abilities
      if ($equipmentTypeCategory === 'weapon') {
        $fields['hands'] = $data['hands'] ?? null;
        $fields['hero_ability_id'] = $data['hero_ability_id'] ?? null;
      }
    } elseif (in_array($cardTypeId, [Card::TECHNIQUE_TYPE_ID, Card::SPELL_TYPE_ID, Card::LITANY_TYPE_ID])) {
      // Technique (4) or Spell (5)
      $fields['card_subtype_id'] = $data['card_subtype_id'] ?? null;
      $fields['attack_range_id'] = $data['attack_range_id'] ?? null;
      $fields['attack_type'] = $data['attack_type'] ?? null;
      $fields['attack_subtype_id'] = $data['attack_subtype_id'] ?? null;
    }
    
    $card->fill($fields);
    
    // Boolean fields
    $card->area = (in_array($cardTypeId, [Card::TECHNIQUE_TYPE_ID, Card::SPELL_TYPE_ID, Card::LITANY_TYPE_ID]) && isset($data['area'])) 
      ? (bool)$data['area'] 
      : false;
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