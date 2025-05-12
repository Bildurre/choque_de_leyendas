<?php

namespace App\Services\Game;

use App\Models\GameMode;
use App\Services\Traits\HandlesTranslations;

class GameModeService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'description'];

  /**
   * Get all game modes with optional pagination
   *
   * @param int|null $perPage Number of items per page, or null for all items
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only trashed items
   * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
   */
  public function getAllGameModes(int $perPage = null, bool $withTrashed = false, bool $onlyTrashed = false): mixed
  {
    $query = GameMode::withCount('factionDecks');
    
    // Apply trash filters
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    // Default ordering
    $query->orderBy('id');
    
    if ($perPage) {
      return $query->paginate($perPage);
    }
    
    return $query->get();
  }

  /**
   * Create a new game mode
   *
   * @param array $data
   * @return GameMode
   * @throws \Exception
   */
  public function create(array $data): GameMode
  {
    $gameMode = new GameMode();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($gameMode, $data, $this->translatableFields);
    
    $gameMode->save();
    
    return $gameMode;
  }

  /**
   * Update an existing game mode
   *
   * @param GameMode $gameMode
   * @param array $data
   * @return GameMode
   * @throws \Exception
   */
  public function update(GameMode $gameMode, array $data): GameMode
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($gameMode, $data, $this->translatableFields);
    
    $gameMode->save();
    
    return $gameMode;
  }

  /**
   * Delete a game mode (soft delete)
   *
   * @param GameMode $gameMode
   * @return bool
   * @throws \Exception
   */
  public function delete(GameMode $gameMode): bool
  {
    // Check for related faction decks
    if ($gameMode->factionDecks()->count() > 0) {
      throw new \Exception("No se puede eliminar el modo de juego porque tiene mazos de facción asociados.");
    }
    
    return $gameMode->delete();
  }

  /**
   * Restore a deleted game mode
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $gameMode = GameMode::onlyTrashed()->findOrFail($id);
    return $gameMode->restore();
  }

  /**
   * Force delete a game mode permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $gameMode = GameMode::onlyTrashed()->findOrFail($id);
    
    // Check for related faction decks (including trashed)
    if ($gameMode->factionDecks()->withTrashed()->count() > 0) {
      throw new \Exception("No se puede eliminar permanentemente el modo de juego porque tiene mazos de facción asociados.");
    }
    
    return $gameMode->forceDelete();
  }
}