<?php

namespace App\Services;

use App\Models\CardType;
use Illuminate\Database\Eloquent\Collection;

class CardTypeService
{
  /**
   * Get all card types with related superclass
   *
   * @return Collection
   */
  public function getAllCardTypes(): Collection
  {
    return CardType::with('heroSuperclass')->get();
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
    // Verificar si la superclase ya está asignada a otro tipo de carta
    if (isset($data['hero_superclass_id']) && $data['hero_superclass_id']) {
      $existingCardType = CardType::where('hero_superclass_id', $data['hero_superclass_id'])->first();
      if ($existingCardType) {
        throw new \Exception(trans('card_types.superclass_assigned'));
      }
    }
    
    $cardType = new CardType();
    $cardType->fill($data);
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
    // Si cambia la superclase, verificar que no esté asignada a otro tipo
    if (isset($data['hero_superclass_id']) && 
        $data['hero_superclass_id'] != $cardType->hero_superclass_id) {
      
      // Verificar si la nueva superclase ya está asignada
      if ($data['hero_superclass_id']) {
        $existingCardType = CardType::where('hero_superclass_id', $data['hero_superclass_id'])
                                    ->where('id', '!=', $cardType->id)
                                    ->first();
        if ($existingCardType) {
          throw new \Exception(trans('card_types.superclass_assigned'));
        }
      }
    }
    
    $cardType->fill($data);
    $cardType->save();
    
    return $cardType;
  }

  /**
   * Delete a card type
   *
   * @param CardType $cardType
   * @return bool
   */
  public function delete(CardType $cardType): bool
  {
    return $cardType->delete();
  }

  /**
   * Get available hero superclasses (not linked to any card type)
   *
   * @param CardType|null $excludeCardType
   * @return Collection
   */
  public function getAvailableSuperclasses(?CardType $excludeCardType = null): Collection
  {
    // Obtener las superclases que ya están asignadas a tipos de carta
    $assignedSuperclassIds = CardType::whereNotNull('hero_superclass_id')
      ->where(function ($query) use ($excludeCardType) {
        if ($excludeCardType) {
          $query->where('id', '!=', $excludeCardType->id);
        }
      })
      ->pluck('hero_superclass_id')
      ->toArray();
    
    // Obtener todas las superclases excepto las ya asignadas
    $query = \App\Models\HeroSuperclass::query();
    
    if (!empty($assignedSuperclassIds)) {
      $query->whereNotIn('id', $assignedSuperclassIds);
    }
    
    // Si estamos editando, incluir la superclase actual del tipo de carta
    if ($excludeCardType && $excludeCardType->hero_superclass_id) {
      $query->orWhere('id', $excludeCardType->hero_superclass_id);
    }
    
    return $query->get();
  }
}