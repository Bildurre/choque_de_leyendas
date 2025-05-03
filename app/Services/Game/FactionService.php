<?php

namespace App\Services\Game;

use App\Models\Faction;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Traits\HandlesTranslations;

class FactionService
{
  use HandlesTranslations;
  
  protected $imageService;
  protected $translatableFields = ['name', 'lore_text'];

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
    return Faction::withCount(['heroes', 'cards'])->get();
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
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations to model
    $this->applyTranslations($faction, $data, $this->translatableFields);
    
    // Set non-translatable fields
    $faction->color = $data['color'];
    
    // Set text color based on background
    $faction->setTextColorBasedOnBackground();
    
    // Handle icon if provided
    if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $faction->icon = $this->imageService->store($data['icon'], $faction->getImageDirectory());
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
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations to model
    $this->applyTranslations($faction, $data, $this->translatableFields);
    
    // Update non-translatable fields
    if (isset($data['color'])) {
      $faction->color = $data['color'];
      // Set text color based on background
      $faction->setTextColorBasedOnBackground();
    }
    
    // Handle icon removal
    if (isset($data['remove_icon']) && $data['remove_icon'] == "1") {
      $this->imageService->delete($faction->icon);
      $faction->icon = null;
    }
    // Handle icon update
    elseif (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $faction->icon = $this->imageService->update($data['icon'], $faction->icon, $faction->getImageDirectory());
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