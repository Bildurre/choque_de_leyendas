<?php

namespace App\Services\Game;

use App\Models\Hero;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Traits\HandlesTranslations;

class HeroService
{
  use HandlesTranslations;
  
  protected $imageService;
  protected $attributesConfigService;
  protected $translatableFields = ['name', 'lore_text', 'passive_name', 'passive_description'];

  /**
   * Create a new service instance.
   *
   * @param ImageService $imageService
   * @param HeroAttributesConfigurationService $attributesConfigService
   */
  public function __construct(
    ImageService $imageService,
    HeroAttributesConfigurationService $attributesConfigService
  ) {
    $this->imageService = $imageService;
    $this->attributesConfigService = $attributesConfigService;
  }

  /**
   * Get all heroes with relationships
   *
   * @return Collection
   */
  public function getAllHeroes(): Collection
  {
    return Hero::with(['faction', 'race', 'heroClass', 'heroClass.heroSuperclass'])->get();
  }

  /**
   * Create a new hero
   *
   * @param array $data
   * @return Hero
   * @throws \Exception
   */
  public function create(array $data): Hero
  {
    // Validate attribute constraints
    $this->validateHeroAttributes($data);
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $hero = new Hero();
    
    // Apply translations
    $this->applyTranslations($hero, $data, $this->translatableFields);
    
    // Set non-translatable fields
    $hero->faction_id = $data['faction_id'] ?? null;
    $hero->hero_race_id = $data['hero_race_id'];
    $hero->hero_class_id = $data['hero_class_id'];
    $hero->gender = $data['gender'];
    $hero->agility = $data['agility'];
    $hero->mental = $data['mental'];
    $hero->will = $data['will'];
    $hero->strength = $data['strength'];
    $hero->armor = $data['armor'];
    
    // Handle image if provided
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $hero->image = $this->imageService->store($data['image'], $hero->getImageDirectory());
    }
    
    $hero->save();
    
    // Associate abilities if selected
    if (isset($data['abilities']) && is_array($data['abilities'])) {
      $hero->abilities()->sync($data['abilities']);
    }
    
    return $hero;
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
    // Validate attribute constraints
    $this->validateHeroAttributes($data);
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($hero, $data, $this->translatableFields);
    
    // Handle image removal
    if (isset($data['remove_image']) && $data['remove_image'] == "1") {
      $this->imageService->delete($hero->image);
      $hero->image = null;
    }
    // Handle image update
    elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $hero->image = $this->imageService->update($data['image'], $hero->image, $hero->getImageDirectory());
    }
    
    // Update non-translatable fields
    $hero->faction_id = $data['faction_id'] ?? null;
    $hero->hero_race_id = $data['hero_race_id'];
    $hero->hero_class_id = $data['hero_class_id'];
    $hero->gender = $data['gender'];
    $hero->agility = $data['agility'];
    $hero->mental = $data['mental'];
    $hero->will = $data['will'];
    $hero->strength = $data['strength'];
    $hero->armor = $data['armor'];
    
    $hero->save();
    
    // Update abilities
    if (isset($data['abilities'])) {
      $hero->abilities()->sync($data['abilities']);
    } else {
      // If no abilities are submitted, remove all relationships
      $hero->abilities()->detach();
    }
    
    return $hero;
  }

  /**
   * Delete a hero
   *
   * @param Hero $hero
   * @return bool
   * @throws \Exception
   */
  public function delete(Hero $hero): bool
  {
    // Delete image if exists
    if ($hero->image) {
      $this->imageService->delete($hero->image);
    }
    
    // Detach abilities
    $hero->abilities()->detach();
    
    return $hero->delete();
  }

  /**
   * Validate hero attributes against configuration
   *
   * @param array $data
   * @return bool
   * @throws \Exception
   */
  protected function validateHeroAttributes(array $data): bool
  {
    // Extract attribute data
    $attributes = [
      'agility' => $data['agility'] ?? 0,
      'mental' => $data['mental'] ?? 0,
      'will' => $data['will'] ?? 0,
      'strength' => $data['strength'] ?? 0,
      'armor' => $data['armor'] ?? 0
    ];
    
    // Get configuration
    $config = $this->attributesConfigService->getConfiguration();
    
    // Check individual attribute constraints
    foreach ($attributes as $name => $value) {
      if ($value < $config->min_attribute_value || $value > $config->max_attribute_value) {
        throw new \Exception("El valor del atributo {$name} debe estar entre {$config->min_attribute_value} y {$config->max_attribute_value}.");
      }
    }
    
    // Calculate total attributes
    $totalAttributes = array_sum($attributes);
    
    // Check total attributes constraints
    if ($totalAttributes < $config->min_total_attributes || $totalAttributes > $config->max_total_attributes) {
      throw new \Exception("La suma total de atributos debe estar entre {$config->min_total_attributes} y {$config->max_total_attributes}.");
    }
    
    return true;
  }
}