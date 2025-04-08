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
    return HeroAbility::with(['subtype.type', 'range'])->get();
  }

  /**
   * Get a paginated list of abilities
   * 
   * @param int $perPage
   * @return \Illuminate\Pagination\LengthAwarePaginator
   */
  public function getPaginatedAbilities(int $perPage = 15)
  {
    return HeroAbility::with(['subtype.type', 'range'])
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
    
    // Assign to heroes if provided
    if (!empty($data['hero_ids'])) {
      $isDefault = !empty($data['is_default']);
      $this->assignToHeroes($heroAbility, $data['hero_ids'], $isDefault);
    }
    
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
    
    // Update hero assignments if provided
    if (isset($data['hero_ids'])) {
      $currentHeroIds = $heroAbility->heroes()->pluck('hero_id')->toArray();
      $newHeroIds = $data['hero_ids'] ?? [];
      
      // Determine which heroes to detach and which to attach
      $heroesToDetach = array_diff($currentHeroIds, $newHeroIds);
      $heroesToAttach = array_diff($newHeroIds, $currentHeroIds);
      
      // Detach heroes that aren't in the new list
      if (!empty($heroesToDetach)) {
        $heroAbility->heroes()->detach($heroesToDetach);
      }
      
      // Attach new heroes
      if (!empty($heroesToAttach)) {
        $isDefault = !empty($data['is_default']);
        $this->assignToHeroes($heroAbility, $heroesToAttach, $isDefault);
      }
      
      // Update default status for existing heroes
      if (isset($data['is_default'])) {
        $heroIds = array_intersect($currentHeroIds, $newHeroIds);
        foreach ($heroIds as $heroId) {
          $heroAbility->heroes()->updateExistingPivot($heroId, [
            'is_default' => !empty($data['is_default'])
          ]);
        }
      }
    }
    
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
   * @param bool $isDefault
   * @return void
   */
  public function assignToHeroes(HeroAbility $ability, array $heroIds, bool $isDefault = false): void
  {
    $pivotData = array_fill(0, count($heroIds), ['is_default' => $isDefault]);
    $heroIdsWithPivot = array_combine($heroIds, $pivotData);
    
    $ability->heroes()->attach($heroIdsWithPivot);
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