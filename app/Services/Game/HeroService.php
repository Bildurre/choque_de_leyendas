<?php

namespace App\Services\Game;

use App\Models\Hero;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class HeroService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['name', 'lore_text', 'passive_name', 'passive_description'];

  /**
   * Create a new hero
   *
   * @param array $data
   * @return Hero
   * @throws \Exception
   */
  public function create(array $data): Hero
  {
    try {
      // Start a transaction
      DB::beginTransaction();
      
      $hero = new Hero();
      
      // Process translatable fields
      $data = $this->processTranslatableFields($data, $this->translatableFields);
      
      // Apply translations
      $this->applyTranslations($hero, $data, $this->translatableFields);
      
      // Set regular fields
      $this->setHeroFields($hero, $data);
      
      // Handle image upload
      if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
        $hero->storeImage($data['image']);
      }
      
      $hero->save();
      
      // Sync hero abilities
      if (isset($data['hero_abilities']) && is_array($data['hero_abilities'])) {
        $hero->heroAbilities()->sync($data['hero_abilities']);
      }
      
      // Commit the transaction
      DB::commit();
      
      return $hero;
    } catch (\Exception $e) {
      // Rollback the transaction in case of error
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Update an existing hero
   *
   * @param Hero $hero
   * @param array $data
   * @return Hero
   * @throws \Exception
   */
  public function update(Hero $hero, array $data): Hero
  {
    try {
      // Start a transaction
      DB::beginTransaction();
      
      // Process translatable fields
      $data = $this->processTranslatableFields($data, $this->translatableFields);
      
      // Apply translations
      $this->applyTranslations($hero, $data, $this->translatableFields);
      
      // Set regular fields
      $this->setHeroFields($hero, $data);
      
      // Handle image updates
      if (isset($data['remove_image']) && $data['remove_image']) {
        $hero->deleteImage();
      } elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
        $hero->storeImage($data['image']);
      }
      
      $hero->save();
      
      // Sync hero abilities
      if (isset($data['hero_abilities']) && is_array($data['hero_abilities'])) {
        $hero->heroAbilities()->sync($data['hero_abilities']);
      }
      
      // Commit the transaction
      DB::commit();
      
      return $hero;
    } catch (\Exception $e) {
      // Rollback the transaction in case of error
      DB::rollBack();
      throw $e;
    }
  }

  /**
   * Set the hero fields from data array
   *
   * @param Hero $hero
   * @param array $data
   * @return void
   */
  private function setHeroFields(Hero $hero, array $data): void
  {
    // Set relation IDs
    $hero->faction_id = $data['faction_id'] ?? null;
    $hero->hero_race_id = $data['hero_race_id'] ?? null;
    $hero->hero_class_id = $data['hero_class_id'] ?? null;
    
    // Set attributes
    $hero->gender = $data['gender'] ?? 'male';
    $hero->agility = $data['agility'] ?? 2;
    $hero->mental = $data['mental'] ?? 2;
    $hero->will = $data['will'] ?? 2;
    $hero->strength = $data['strength'] ?? 2;
    $hero->armor = $data['armor'] ?? 2;
  }

  /**
   * Delete a hero (soft delete)
   *
   * @param Hero $hero
   * @return bool
   * @throws \Exception
   */
  public function delete(Hero $hero): bool
  {
    return $hero->delete();
  }

  /**
   * Restore a deleted hero
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function restore(int $id): bool
  {
    $hero = Hero::onlyTrashed()->findOrFail($id);
    return $hero->restore();
  }

  /**
   * Force delete a hero permanently
   *
   * @param int $id
   * @return bool
   * @throws \Exception
   */
  public function forceDelete(int $id): bool
  {
    $hero = Hero::onlyTrashed()->findOrFail($id);
    
    // Delete image if exists
    if ($hero->hasImage()) {
      $hero->deleteImage();
    }
    
    // Remove ability associations
    $hero->heroAbilities()->detach();
    
    return $hero->forceDelete();
  }
}