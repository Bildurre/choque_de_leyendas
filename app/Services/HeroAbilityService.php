<?php

namespace App\Services;

use App\Models\Hero;
use App\Models\HeroAbility;
use Illuminate\Database\Eloquent\Collection;

class HeroAbilityService
{
  protected $costTranslator;

  /**
   * Create a new service instance.
   *
   * @param CostTranslatorService $costTranslator
   */
  public function __construct(CostTranslatorService $costTranslator)
  {
    $this->costTranslator = $costTranslator;
  }

  /**
   * Get all hero abilities with relations
   *
   * @return Collection
   */
  public function getAllAbilities(): Collection
  {
    return HeroAbility::with(['subtype', 'range', 'heroes'])->get();
  }

  /**
   * Get a paginated list of abilities
   * 
   * @param int $perPage
   * @return \Illuminate\Pagination\LengthAwarePaginator
   */
  public function getPaginatedAbilities(int $perPage = 15)
  {
    return HeroAbility::with(['subtype', 'range', 'heroes'])
      ->withCount('heroes')
      ->orderBy('name')
      ->paginate($perPage);
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
    // Validate cost if provided
    if (!empty($data['cost']) && !$this->costTranslator->isValidCost($data['cost'])) {
      throw new \Exception("El coste proporcionado no es válido. Debe usar solo caracteres R, G, B con un máximo de 5.");
    }
    
    $heroAbility = new HeroAbility();
    $heroAbility->fill($data);
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
    // Validate cost if provided
    if (!empty($data['cost']) && !$this->costTranslator->isValidCost($data['cost'])) {
      throw new \Exception("El coste proporcionado no es válido. Debe usar solo caracteres R, G, B con un máximo de 5.");
    }
    
    $heroAbility->fill($data);
    $heroAbility->save();
    
    return $heroAbility;
  }

  /**
   * Delete a hero ability
   *
   * @param HeroAbility $heroAbility
   * @return bool
   */
  public function delete(HeroAbility $heroAbility): bool
  {
    // Detach all associated heroes first
    $heroAbility->heroes()->detach();
    
    return $heroAbility->delete();
  }

  /**
   * Assign an ability to multiple heroes
   *
   * @param HeroAbility $ability
   * @param array $heroIds
   * @return void
   */
  public function assignToHeroes(HeroAbility $ability, array $heroIds): void
  {
    $ability->heroes()->attach($heroIds);
  }

  /**
   * Get hero abilities grouped by hero
   * 
   * @param Hero $hero
   * @return Collection
   */
  public function getAbilitiesByHero(Hero $hero): Collection
  {
    return $hero->abilities()->with(['subtype.type', 'range'])->get();
  }
}