<?php

namespace App\Services;

use App\Models\Faction;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;

class FactionService
{
  protected $imageService;

  /**
   * Create a new FactionService instance
   *
   * @param ImageService $imageService
   */
  public function __construct(ImageService $imageService)
  {
    $this->imageService = $imageService;
  }

  /**
   * Get all factions
   *
   * @return Collection
   */
  public function getAllFactions(): Collection
  {
    return Faction::all();
  }

  /**
   * Create a new faction
   *
   * @param array $data
   * @return Faction
   */
  public function create(array $data): Faction
  {
    $faction = new Faction();
    $faction->name = $data['name'];
    $faction->lore_text = $data['lore_text'] ?? null;
    $faction->color = $data['color'];
    
    // Set text color based on background
    $faction->setTextColorBasedOnBackground();
    
    // Handle icon if provided
    if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $faction->icon = $this->imageService->store($data['icon'], 'faction-icons');
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
   */
  public function update(Faction $faction, array $data): Faction
  {
    $faction->name = $data['name'];
    $faction->lore_text = $data['lore_text'] ?? null;
    $faction->color = $data['color'];
    
    // Set text color based on background
    $faction->setTextColorBasedOnBackground();
    
    // Handle icon removal
    if (isset($data['remove_icon']) && $data['remove_icon'] == "1") {
      $this->imageService->delete($faction->icon);
      $faction->icon = null;
    }
    // Handle icon update
    elseif (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $faction->icon = $this->imageService->update($data['icon'], $faction->icon, 'faction-icons');
    }
    
    $faction->save();
    
    return $faction;
  }
  
  /**
   * Delete a faction
   *
   * @param Faction $faction
   * @return bool
   * @throws \Exception
   */
  public function delete(Faction $faction): bool
  {
    // Check for related entities
    if ($faction->heroes()->count() > 0 || $faction->cards()->count() > 0) {
      throw new \Exception("No se puede eliminar la facción porque tiene héroes o cartas asociadas.");
    }
    
    // Delete icon if exists
    if ($faction->icon) {
      $this->imageService->delete($faction->icon);
    }
    
    return $faction->delete();
  }
}