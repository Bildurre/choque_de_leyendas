<?php

namespace App\Services;

use App\Models\HeroAttributesConfiguration;

class HeroAttributesConfigurationService
{
  /**
   * Get the hero attribute configuration
   *
   * @return HeroAttributesConfiguration
   */
  public function getConfiguration(): HeroAttributesConfiguration
  {
    return HeroAttributesConfiguration::firstOrCreate([]);
  }

  /**
   * Update hero attributes configuration
   *
   * @param array $data
   * @return HeroAttributesConfiguration
   * @throws \Exception
   */
  public function updateConfiguration(array $data): HeroAttributesConfiguration
  {
    // Validate that min value is not greater than max value
    if ($data['min_attribute_value'] > $data['max_attribute_value']) {
      throw new \Exception('El valor mínimo de atributo no puede ser mayor que el valor máximo.');
    }
    
    // Validate that min total is not greater than max total
    if ($data['min_total_attributes'] > $data['max_total_attributes']) {
      throw new \Exception('El valor mínimo total de atributos no puede ser mayor que el valor máximo total.');
    }
    
    // Get the configuration (or create if not exists)
    $configuration = HeroAttributesConfiguration::firstOrCreate([]);
    $configuration->update($data);

    return $configuration;
  }

  /**
   * Validate a set of attributes against configuration constraints
   *
   * @param array $attributes
   * @return bool
   */
  public function validateAttributes(array $attributes): bool
  {
    $configuration = $this->getConfiguration();
    return $configuration->validateAttributes($attributes);
  }

  /**
   * Calculate health for a set of attributes
   *
   * @param array $attributes
   * @return int
   */
  public function calculateHealth(array $attributes): int
  {
    $configuration = $this->getConfiguration();
    
    return $configuration->calculateHealth(
      $attributes['agility'] ?? 0,
      $attributes['mental'] ?? 0,
      $attributes['will'] ?? 0,
      $attributes['strength'] ?? 0,
      $attributes['armor'] ?? 0
    );
  }
}