<?php

namespace App\Services\Game;

use App\Models\HeroAbility;
use App\Services\Traits\HandlesTranslations;

class HeroAbilityService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'description'];

  /**
   * Get all hero abilities with optional pagination
   *
   * @param int|null $perPage Number of items per page, or null for all items
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only trashed items
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getAllHeroAbilities(int $perPage = null, bool $withTrashed = false, bool $onlyTrashed = false): mixed
  {
    $query = HeroAbility::with(['attackRange', 'attackSubtype'])
      ->withCount(['heroes', 'cards']);
    
    // Apply trash filters
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    // Default ordering
    $query->orderBy('id');
    
    if ($perPage) {
      return $query->paginate($perPage)->withQueryString();
    }
    
    return $query->get();
  }

  /**
   * Create a new hero ability
   *
   * @param array $data
   * @return HeroAbility
   * @throws \Exception
   */
  public function create(array $data): HeroAbility
  {
    $heroAbility = new HeroAbility();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($heroAbility, $data, $this->translatableFields);
    
    // Set regular fields
    $this->setHeroAbilityFields($heroAbility, $data);
    
    $heroAbility->save();
    
    return $heroAbility;
  }

  /**
   * Update an existing hero ability
   *
   * @param HeroAbility $heroAbility
   * @param array $data
   * @return HeroAbility
   * @throws \Exception
   */
  public function update(HeroAbility $heroAbility, array $data): HeroAbility
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($heroAbility, $data, $this->translatableFields);
    
    // Set regular fields
    $this->setHeroAbilityFields($heroAbility, $data);
    
    $heroAbility->save();
    
    return $heroAbility;
  }

  /**
   * Set the hero ability fields from data array
   *
   * @param HeroAbility $heroAbility
   * @param array $data
   * @return void
   */
  private function setHeroAbilityFields(HeroAbility $heroAbility, array $data): void
  {
    // Set relation IDs with null checks
    $heroAbility->attack_range_id = $data['attack_range_id'] ?? null;
    $heroAbility->attack_subtype_id = $data['attack_subtype_id'] ?? null;
    
    // Set other fields with null checks
    $heroAbility->cost = $data['cost'] ?? null;
    $heroAbility->area = isset($data['area']) ? (bool)$data['area'] : false;
  }

  /**
   * Delete a hero ability (soft delete)
   *
   * @param HeroAbility $heroAbility
   * @return bool
   * @throws \Exception
   */
  public function delete(HeroAbility $heroAbility): bool
  {
    // Check for related heroes
    if ($heroAbility->heroes()->count() > 0) {
      throw new \Exception("__('entities.hero_abilities.errors.has_heroes')");
    }
    
    // Check for related cards
    if ($heroAbility->cards()->count() > 0) {
      throw new \Exception("__('entities.hero_abilities.errors.has_cards')");
    }
    
    return $heroAbility->delete();
  }

  /**
   * Restore a deleted hero ability
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $heroAbility = HeroAbility::onlyTrashed()->findOrFail($id);
    return $heroAbility->restore();
  }

  /**
   * Force delete a hero ability permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $heroAbility = HeroAbility::onlyTrashed()->findOrFail($id);
    
    // Check for related heroes (including trashed)
    if ($heroAbility->heroes()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.hero_abilities.errors.force_delete_has_heroes')");
    }
    
    // Check for related cards (including trashed)
    if ($heroAbility->cards()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.hero_abilities.errors.force_delete_has_cards')");
    }
    
    return $heroAbility->forceDelete();
  }
}