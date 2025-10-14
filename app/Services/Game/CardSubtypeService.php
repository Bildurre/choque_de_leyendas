<?php

namespace App\Services\Game;

use App\Models\CardSubtype;
use Illuminate\Http\Request;
use App\Services\Traits\HandlesTranslations;

class CardSubtypeService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];

  public function getAllCardSubtypes(
    ?Request $request = null,
    ?int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false
  ): mixed {
    $query = CardSubtype::withCount(['cards']);
    
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

  public function create(array $data): CardSubtype
  {
    $cardSubtype = new CardSubtype();
    
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $this->applyTranslations($cardSubtype, $data, $this->translatableFields);
    
    $cardSubtype->save();
    
    return $cardSubtype;
  }

  public function update(CardSubtype $cardSubtype, array $data): CardSubtype
  {
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $this->applyTranslations($cardSubtype, $data, $this->translatableFields);
    
    $cardSubtype->save();
    
    return $cardSubtype;
  }

  public function delete(CardSubtype $cardSubtype): bool
  {
    if ($cardSubtype->cards()->count() > 0) {
      throw new \Exception(__('entities.card_subtypes.errors.has_cards'));
    }
    
    return $cardSubtype->delete();
  }

  public function restore(int $id): bool
  {
    $cardSubtype = CardSubtype::onlyTrashed()->findOrFail($id);
    return $cardSubtype->restore();
  }

  public function forceDelete(int $id): bool
  {
    $cardSubtype = CardSubtype::onlyTrashed()->findOrFail($id);
    
    if ($cardSubtype->cards()->withTrashed()->count() > 0) {
      throw new \Exception(__('entities.card_subtypes.errors.force_delete_has_cards'));
    }
    
    return $cardSubtype->forceDelete();
  }
}