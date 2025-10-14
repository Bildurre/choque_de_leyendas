<?php

namespace App\Services\Game;

use App\Models\HeroAbility;
use Illuminate\Http\Request;
use App\Services\Traits\HandlesTranslations;

class HeroAbilityService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'description'];

  public function getAllHeroAbilities(
    ?Request $request = null,
    ?int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false
  ): mixed {
    $query = HeroAbility::with(['attackRange', 'attackSubtype'])
      ->withCount(['heroes', 'cards']);
    
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    $totalCount = $query->count();
    
    if ($request) {
      $query->applyAdminFilters($request);
    }
    
    $filteredCount = $query->count();
    
    if (!$request || !$request->has('sort')) {
      $query->orderBy('id');
    }
    
    if ($perPage) {
      $result = $query->paginate($perPage)->withQueryString();
      
      $result->totalCount = $totalCount;
      $result->filteredCount = $filteredCount;
      
      return $result;
    }
    
    return $query->get();
  }

  public function create(array $data): HeroAbility
  {
    $heroAbility = new HeroAbility();
    
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $this->applyTranslations($heroAbility, $data, $this->translatableFields);
    
    $this->setHeroAbilityFields($heroAbility, $data);
    
    $heroAbility->save();
    
    return $heroAbility;
  }

  public function update(HeroAbility $heroAbility, array $data): HeroAbility
  {
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $this->applyTranslations($heroAbility, $data, $this->translatableFields);
    
    $this->setHeroAbilityFields($heroAbility, $data);
    
    $heroAbility->save();
    
    return $heroAbility;
  }

  private function setHeroAbilityFields(HeroAbility $heroAbility, array $data): void
  {
    $heroAbility->attack_type = $data['attack_type'] ?? null;
    $heroAbility->attack_range_id = $data['attack_range_id'] ?? null;
    $heroAbility->attack_subtype_id = $data['attack_subtype_id'] ?? null;
    $heroAbility->cost = $data['cost'] ?? null;
    $heroAbility->area = isset($data['area']) ? (bool)$data['area'] : false;
  }

  public function delete(HeroAbility $heroAbility): bool
  {
    if ($heroAbility->heroes()->count() > 0) {
      throw new \Exception(__('entities.hero_abilities.errors.has_heroes'));
    }
    
    if ($heroAbility->cards()->count() > 0) {
      throw new \Exception(__('entities.hero_abilities.errors.has_cards'));
    }
    
    return $heroAbility->delete();
  }

  public function restore(int $id): bool
  {
    $heroAbility = HeroAbility::onlyTrashed()->findOrFail($id);
    return $heroAbility->restore();
  }

  public function forceDelete(int $id): bool
  {
    $heroAbility = HeroAbility::onlyTrashed()->findOrFail($id);
    
    if ($heroAbility->heroes()->withTrashed()->count() > 0) {
      throw new \Exception(__('entities.hero_abilities.errors.force_delete_has_heroes'));
    }
    
    if ($heroAbility->cards()->withTrashed()->count() > 0) {
      throw new \Exception(__('entities.hero_abilities.errors.force_delete_has_cards'));
    }
    
    return $heroAbility->forceDelete();
  }
}