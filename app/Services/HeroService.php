<?php

namespace App\Services;

use App\Models\Hero;
use App\Models\HeroAttributesConfiguration;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;

class HeroService
{
  protected $imageService;
  protected $attributesConfigService;

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
    
    $hero = new Hero();
    $hero->fill($data);
    
    // Handle image if provided
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $hero->image = $this->imageService->store($data['image'], $hero->getImageDirectory());
    }
    
    $hero->save();
    
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
    
    // Handle image removal
    if (isset($data['remove_image']) && $data['remove_image'] == "1") {
      $this->imageService->delete($hero->image);
      $hero->image = null;
    }
    // Handle image update
    elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $hero->image = $this->imageService->update($data['image'], $hero->image, $hero->getImageDirectory());
    }
    
    $hero->fill($data);
    $hero->save();
    
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