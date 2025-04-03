<?php

namespace App\Services;

use App\Models\HeroAttributeConfiguration;

class HeroAttributeConfigurationService
{
  /**
   * Get the hero attribute configuration
   *
   * @return HeroAttributeConfiguration
   */
  public function getConfiguration(): HeroAttributeConfiguration
  {
    return HeroAttributeConfiguration::firstOrFail();
  }

  /**
   * Update hero attributes configuration
   *
   * @param array $data
   * @return HeroAttributeConfiguration
   * @throws \Exception
   */
  public function updateConfiguration(array $data): HeroAttributeConfiguration
  {
    // Calculate total base points
    $basePointsTotal = 
      $data['base_agility'] + 
      $data['base_mental'] + 
      $data['base_will'] + 
      $data['base_strength'] + 
      $data['base_armor'];

    // Validate that base points don't exceed total points
    if ($basePointsTotal > $data['total_points']) {
      throw new \Exception('The sum of base attributes cannot exceed the total points.');
    }

    // Get the configuration
    $configuration = HeroAttributeConfiguration::firstOrFail();
    $configuration->update($data);

    return $configuration;
  }

  /**
   * Get remaining points for hero creation based on current attributes
   *
   * @param array $attributes Current attributes
   * @return int
   */
  public function getRemainingPoints(array $attributes): int
  {
    $configuration = $this->getConfiguration();
    return $configuration->getRemainingPoints($attributes);
  }

  /**
   * Validate that the total points are correctly distributed
   *
   * @param array $attributes Attributes to validate
   * @return bool
   */
  public function validatePointDistribution(array $attributes): bool
  {
    $configuration = $this->getConfiguration();
    return $configuration->validatePointDistribution($attributes);
  }
}