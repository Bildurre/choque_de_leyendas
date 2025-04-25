<?php

namespace App\Services;

use App\Models\Card;
use App\Models\CardType;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class CardService
{
  protected $imageService;
  protected $costTranslator;

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

    // Check equipment type and hands consistency
    $this->validateEquipmentTypeAndHands($data);

    $card = new Card();
    
    // Handle image if provided
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $data['image'] = $this->imageService->store($data['image'], $card->getImageDirectory());
    }
    
    $card->fill($data);
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

    // Check equipment type and hands consistency
    $this->validateEquipmentTypeAndHands($data);

    // Handle image removal
    if (isset($data['remove_image']) && $data['remove_image'] == "1") {
      $this->imageService->delete($card->image);
      $data['image'] = null;
    }
    // Handle image update
    elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $data['image'] = $this->imageService->update($data['image'], $card->image, $card->getImageDirectory());
    }
    
    $card->fill($data);
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
   * Validate equipment type and hands consistency
   *
   * @param array $data
   * @throws \Exception
   */
  private function validateEquipmentTypeAndHands(array $data): void
  {
    // If equipment type is provided, check if it exists
    if (!empty($data['equipment_type_id'])) {
      $equipmentType = EquipmentType::find($data['equipment_type_id']);
      
      if (!$equipmentType) {
        throw new \Exception("El tipo de equipo seleccionado no existe.");
      }
      
      // If it's a weapon, hands must be specified
      if ($equipmentType->isWeapon()) {
        if (empty($data['hands']) || !in_array($data['hands'], [1, 2])) {
          throw new \Exception("Para las armas debe especificar el número de manos (1 o 2).");
        }
      } else {
        // If it's not a weapon, hands should be null
        $data['hands'] = null;
      }
    } else {
      // If no equipment type, hands should be null
      $data['hands'] = null;
    }
  }

  /**
   * Get card count by type for faction
   *
   * @param int $factionId
   * @return array
   */
  public function getCardCountByTypeForFaction(int $factionId): array
  {
    $cardTypes = CardType::all();
    $result = [];
    
    foreach ($cardTypes as $type) {
      $count = Card::where('faction_id', $factionId)
        ->where('card_type_id', $type->id)
        ->count();
      
      $result[$type->name] = $count;
    }
    
    return $result;
  }
}