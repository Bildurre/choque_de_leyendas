<?php

namespace App\Services\Game;

use App\Models\Faction;
use App\Models\GameMode;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Services\Traits\HandlesTranslations;

class FactionService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'lore_text'];

  /**
   * Get all factions with optional filtering and pagination
   * 
   * @param Request|null $request Request object for filtering
   * @param int|null $perPage Number of items per page (null for no pagination)
   * @param bool $withTrashed Include trashed items
   * @param bool $onlyTrashed Only show trashed items
   * @return mixed Collection or LengthAwarePaginator
   */
  public function getAllFactions(
    ?Request $request = null,
    ?int $perPage = null, 
    bool $withTrashed = false, 
    bool $onlyTrashed = false
  ): mixed {
    // Base query with relationship counts
    $query = Faction::withCount(['heroes', 'cards']);
    
    // Apply trash filters
    if ($onlyTrashed) {
      $query->onlyTrashed();
    } elseif ($withTrashed) {
      $query->withTrashed();
    }
    
    // Count total records (before filtering)
    $totalCount = $query->count();
    
    // Apply admin filters if request is provided
    if ($request) {
      $query->applyAdminFilters($request);
    }
    
    // Count filtered records
    $filteredCount = $query->count();
    
    // Apply default ordering only if no sort parameter is provided
    if (!$request || !$request->has('sort')) {
      $query->orderBy('id');
    }
    
    // Paginate if needed
    if ($perPage) {
      $result = $query->paginate($perPage)->withQueryString();
      
      // Add metadata to the pagination result
      $result->totalCount = $totalCount;
      $result->filteredCount = $filteredCount;
      
      return $result;
    }
    
    // Return collection if no pagination
    return $query->get();
  }

  /**
   * Get faction details with related data for the specified tab
   *
   * @param Faction $faction
   * @param string $tab
   * @return array
   */
  public function getFactionWithTabData(Faction $faction, string $tab = 'details'): array
  {
    // Base data that's needed for all tabs
    $data = [
      'faction' => $faction,
      'tab' => $tab
    ];
    
    // For all tabs, we need the counts
    $faction->loadCount(['heroes', 'cards', 'factionDecks']);
    
    // Load specific data based on selected tab
    switch ($tab) {
      case 'heroes':
        // Load heroes with pagination
        $data['heroes'] = $this->getFactionHeroes($faction);
        break;
      
      case 'cards':
        // Load cards with pagination
        $data['cards'] = $this->getFactionCards($faction);
        break;
        
      case 'decks':
        // Load faction decks with pagination
        $data['decks'] = $this->getFactionDecks($faction);
        $data['gameModes'] = GameMode::orderBy('name')->get();
        break;
        
      case 'details':
      default:
        // For details tab, we already have the counts
        break;
    }
    
    return $data;
  }

  /**
   * Get faction heroes with pagination
   *
   * @param Faction $faction
   * @param int $perPage
   * @return \Illuminate\Pagination\LengthAwarePaginator
   */
  public function getFactionHeroes(Faction $faction, int $perPage = 12)
  {
    return $faction->heroes()
      ->with(['heroRace', 'heroClass'])
      ->paginate($perPage);
  }

  /**
   * Get faction cards with pagination
   *
   * @param Faction $faction
   * @param int $perPage
   * @return \Illuminate\Pagination\LengthAwarePaginator
   */
  public function getFactionCards(Faction $faction, int $perPage = 16)
  {
    return $faction->cards()
      ->with(['cardType'])
      ->paginate($perPage);
  }

  /**
   * Get faction decks with pagination
   *
   * @param Faction $faction
   * @param int $perPage
   * @return \Illuminate\Pagination\LengthAwarePaginator
   */
  public function getFactionDecks(Faction $faction, int $perPage = 8)
  {
    return $faction->factionDecks()
      ->with(['gameMode'])
      ->paginate($perPage);
  }
  
  /**
   * Create a new faction
   *
   * @param array $data
   * @return Faction
   * @throws \Exception
   */
  public function create(array $data): Faction
  {
    $faction = new Faction();
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($faction, $data, $this->translatableFields);
    
    // Set color
    if (isset($data['color'])) {
      $faction->color = $data['color'];
      // text_is_dark will be set automatically by HasColorAttribute trait
    }
    
    // Handle icon upload
    if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $faction->storeImage($data['icon'], 'icon');
    }
    
    $faction->save();
    
    return $faction;
  }

  /**
   * Update an existing faction
   *
   * @param Faction $faction
   * @param array $data
   * @return Faction
   * @throws \Exception
   */
  public function update(Faction $faction, array $data): Faction
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($faction, $data, $this->translatableFields);
    
    // Update color
    if (isset($data['color'])) {
      $faction->color = $data['color'];
      // text_is_dark will be updated automatically by HasColorAttribute trait
    }
    
    // Handle icon updates
    if (isset($data['remove_icon']) && $data['remove_icon']) {
      $faction->deleteImage();
    } elseif (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $faction->storeImage($data['icon'], 'icon');
    }
    
    $faction->save();
    
    return $faction;
  }

  /**
   * Delete a faction (soft delete)
   *
   * @param Faction $faction
   * @return bool
   * @throws \Exception
   */
  public function delete(Faction $faction): bool
  {
    // Check for related heroes
    if ($faction->heroes()->count() > 0) {
      throw new \Exception("__('entities.factions.errors.has_heroes')");
    }
    
    // Check for related cards
    if ($faction->cards()->count() > 0) {
      throw new \Exception("__('entities.factions.errors.has_cards')");
    }
    
    return $faction->delete();
  }

  /**
   * Restore a deleted faction
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $faction = Faction::onlyTrashed()->findOrFail($id);
    return $faction->restore();
  }

  /**
   * Force delete a faction permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $faction = Faction::onlyTrashed()->findOrFail($id);
    
    // Check for related heroes (even deleted ones)
    if ($faction->heroes()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.factions.errors.force_delete_has_heroes')");
    }
    
    // Check for related cards (even deleted ones)
    if ($faction->cards()->withTrashed()->count() > 0) {
      throw new \Exception("__('entities.factions.errors.force_delete_has_cards')");
    }
    
    // Delete icon if exists
    if ($faction->hasImage()) {
      $faction->deleteImage();
    }
    
    return $faction->forceDelete();
  }
}