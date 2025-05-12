<?php

namespace App\Services\Game;

use App\Models\AttackSubtype;
use App\Services\Traits\HandlesTranslations;

class AttackSubtypeService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name'];

  /**
   * Get all attack subtypes with optional pagination
   *
   * @param int|null $perPage Number of items per page, or null for all items
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only trashed items
   * @param string|null $type Filter by type
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getAllAttackSubtypes(int $perPage = null, bool $withTrashed = false, bool $onlyTrashed = false): mixed
  {
    $query = AttackSubtype::withCount(['heroAbilities', 'cards']);
    
    // Aplicar filtros de elementos eliminados
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    // Ordenar por tipo y nombre
    $query->orderBy('type')->orderBy('id');
    
    if ($perPage) {
      return $query->paginate($perPage);
    }
    
    return $query->get();
}

  /**
   * Create a new attack subtype
   *
   * @param array $data
   * @return AttackSubtype
   * @throws \Exception
   */
  public function create(array $data): AttackSubtype
  {
    $attackSubtype = new AttackSubtype();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($attackSubtype, $data, $this->translatableFields);
    
    // Set non-translatable fields
    $attackSubtype->type = $data['type'];
    
    $attackSubtype->save();
    
    return $attackSubtype;
  }

  /**
   * Update an existing attack subtype
   *
   * @param AttackSubtype $attackSubtype
   * @param array $data
   * @return AttackSubtype
   * @throws \Exception
   */
  public function update(AttackSubtype $attackSubtype, array $data): AttackSubtype
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($attackSubtype, $data, $this->translatableFields);
    
    // Update non-translatable fields
    if (isset($data['type'])) {
      $attackSubtype->type = $data['type'];
    }
    
    $attackSubtype->save();
    
    return $attackSubtype;
  }

  /**
   * Delete an attack subtype (soft delete)
   *
   * @param AttackSubtype $attackSubtype
   * @return bool
   * @throws \Exception
   */
  public function delete(AttackSubtype $attackSubtype): bool
  {
    // Check for related cards
    if ($attackSubtype->cards()->count() > 0) {
      throw new \Exception("No se puede eliminar el subtipo de ataque porque tiene cartas asociadas.");
    }
    
    // Check for related hero abilities
    if ($attackSubtype->heroAbilities()->count() > 0) {
      throw new \Exception("No se puede eliminar el subtipo de ataque porque tiene habilidades de héroe asociadas.");
    }
    
    return $attackSubtype->delete();
  }

  /**
   * Restore a deleted attack subtype
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $attackSubtype = AttackSubtype::onlyTrashed()->findOrFail($id);
    return $attackSubtype->restore();
  }

  /**
   * Force delete an attack subtype permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $attackSubtype = AttackSubtype::onlyTrashed()->findOrFail($id);
    
    // Check for related cards (incluso para los eliminados)
    if ($attackSubtype->cards()->withTrashed()->count() > 0) {
      throw new \Exception("No se puede eliminar permanentemente el subtipo de ataque porque tiene cartas asociadas.");
    }
    
    // Check for related hero abilities (incluso para los eliminados)
    if ($attackSubtype->heroAbilities()->withTrashed()->count() > 0) {
      throw new \Exception("No se puede eliminar permanentemente el subtipo de ataque porque tiene habilidades de héroe asociadas.");
    }
    
    return $attackSubtype->forceDelete();
  }
}