<?php

namespace App\Services\Game;

use App\Models\Card;
use App\Models\CardType;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Traits\HandlesTranslations;

class CardService
{
  use HandlesTranslations;
  
  protected $imageService;
  protected $costTranslator;
  protected $translatableFields = ['name', 'lore_text', 'effect', 'restriction'];

  /**
   * Create a new service instance.
   *
   * @param ImageService $imageService
   * @param CostTranslatorService $costTranslator
   */
  public function __construct(ImageService $imageService, CostTranslatorService $costTranslator)
  {
    $this->imageService = $imageService;
    $this->costTranslator = $costTranslator;
  }

  /**
   * Get all cards with essential relationships
   *
   * @return Collection
   */
  public function getAllCards(): Collection
  {
    return Card::with(['faction', 'cardType', 'equipmentType'])->get();
  }

  /**
   * Get paginated cards with relationships
   *
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getPaginatedCards(int $perPage = 15): LengthAwarePaginator
  {
    return Card::with(['faction', 'cardType', 'equipmentType', 'attackRange', 'attackSubtype'])
      ->orderBy('name')
      ->paginate($perPage);
  }

  /**
   * Get cards by faction
   *
   * @param int $factionId
   * @return Collection
   */
  public function getCardsByFaction(int $factionId): Collection
  {
    return Card::where('faction_id', $factionId)
      ->with(['cardType', 'equipmentType'])
      ->get();
  }

  /**
   * Get cards by card type
   *
   * @param int $cardTypeId
   * @return Collection
   */
  public function getCardsByType(int $cardTypeId): Collection
  {
    return Card::where('card_type_id', $cardTypeId)
      ->with(['faction', 'equipmentType'])
      ->get();
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
    // Validate the cost if provided
    if (!empty($data['cost']) && !$this->costTranslator->isValidCost($data['cost'])) {
      throw new \Exception("El coste proporcionado no es válido. Debe usar solo caracteres R, G, B con un máximo de 5.");
    }

    // Process conditional fields
    $data = $this->processConditionalFields($data);

    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $card = new Card();
    
    // Apply translations
    $this->applyTranslations($card, $data, $this->translatableFields);
    
    // Handle image if provided
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $card->image = $this->imageService->store($data['image'], $card->getImageDirectory());
    }
    
    // Set non-translatable fields
    $card->faction_id = $data['faction_id'] ?? null;
    $card->card_type_id = $data['card_type_id'];
    $card->equipment_type_id = $data['equipment_type_id'] ?? null;
    $card->attack_range_id = $data['attack_range_id'] ?? null;
    $card->attack_subtype_id = $data['attack_subtype_id'] ?? null;
    $card->hero_ability_id = $data['hero_ability_id'] ?? null;
    $card->hands = $data['hands'] ?? null;
    $card->cost = $data['cost'] ?? null;
    $card->area = $data['area'] ?? false;
    $card->is_attack = $data['is_attack'] ?? false;
    $card->has_hero_ability = $data['has_hero_ability'] ?? false;
    
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
    // Validate the cost if provided
    if (!empty($data['cost']) && !$this->costTranslator->isValidCost($data['cost'])) {
      throw new \Exception("El coste proporcionado no es válido. Debe usar solo caracteres R, G, B con un máximo de 5.");
    }

    // Process conditional fields
    $data = $this->processConditionalFields($data);

    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($card, $data, $this->translatableFields);
    
    // Handle image removal
    if (isset($data['remove_image']) && $data['remove_image'] == "1") {
      $this->imageService->delete($card->image);
      $card->image = null;
    }
    // Handle image update
    elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $card->image = $this->imageService->update($data['image'], $card->image, $card->getImageDirectory());
    }
    
    // Update non-translatable fields
    $card->faction_id = $data['faction_id'] ?? null;
    $card->card_type_id = $data['card_type_id'];
    $card->equipment_type_id = $data['equipment_type_id'] ?? null;
    $card->attack_range_id = $data['attack_range_id'] ?? null;
    $card->attack_subtype_id = $data['attack_subtype_id'] ?? null;
    $card->hero_ability_id = $data['hero_ability_id'] ?? null;
    $card->hands = $data['hands'] ?? null;
    $card->cost = $data['cost'] ?? null;
    $card->area = $data['area'] ?? false;
    $card->is_attack = $data['is_attack'] ?? false;
    $card->has_hero_ability = $data['has_hero_ability'] ?? false;
    
    $card->save();
    
    return $card;
  }

  /**
   * Delete a card
   *
   * @param Card $card
   * @return bool
   */
  public function delete(Card $card): bool
  {
    // Delete image if exists
    if ($card->image) {
      $this->imageService->delete($card->image);
    }
    
    return $card->delete();
  }

  /**
   * Process conditional fields based on checkboxes and selects
   *
   * @param array $data
   * @return array
   */
  protected function processConditionalFields(array $data): array
  {
    // Set default values for checkboxes if not present
    $data['is_attack'] = isset($data['is_attack']) ? (bool)$data['is_attack'] : false;
    $data['has_hero_ability'] = isset($data['has_hero_ability']) ? (bool)$data['has_hero_ability'] : false;
    $data['area'] = isset($data['area']) ? (bool)$data['area'] : false;
    
    // Handle attack fields
    if (!$data['is_attack']) {
      $data['attack_range_id'] = null;
      $data['attack_subtype_id'] = null;
      $data['area'] = false;
    }
    
    // Handle hero ability
    if (!$data['has_hero_ability']) {
      $data['hero_ability_id'] = null;
    }
    
    // Handle equipment fields
    $isEquipmentCard = false;
    if (isset($data['card_type_id'])) {
      $cardType = CardType::find($data['card_type_id']);
      $isEquipmentCard = $cardType && $cardType->name === 'Equipo';
    }
    
    if (!$isEquipmentCard) {
      $data['equipment_type_id'] = null;
      $data['hands'] = null;
    } else if (isset($data['equipment_type_id'])) {
      $equipmentType = EquipmentType::find($data['equipment_type_id']);
      if (!$equipmentType || !$equipmentType->isWeapon()) {
        $data['hands'] = null;
      }
    }
    
    return $data;
  }
}