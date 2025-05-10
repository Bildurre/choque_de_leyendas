<?php

namespace App\Services\Game;

use App\Models\HeroAttributesConfiguration;
use Exception;

class HeroAttributesConfigurationService
{
  /**
   * Get the current configuration
   *
   * @return HeroAttributesConfiguration
   */
  public function getConfiguration(): HeroAttributesConfiguration
  {
    return HeroAttributesConfiguration::getDefault();
  }

  /**
   * Update the configuration
   *
   * @param array $data
   * @return HeroAttributesConfiguration
   * @throws Exception
   */
  public function updateConfiguration(array $data): HeroAttributesConfiguration
  {
    try {
      $config = $this->getConfiguration();
      $config->fill($data);
      $config->save();
      
      return $config;
    } catch (Exception $e) {
      throw new Exception("Error al actualizar la configuración de atributos: " . $e->getMessage());
    }
  }

  /**
   * Calculate health for a hero with given attributes
   *
   * @param int $agility
   * @param int $mental
   * @param int $will
   * @param int $strength
   * @param int $armor
   * @return int
   */
  public function calculateHealth(int $agility, int $mental, int $will, int $strength, int $armor): int
  {
    $config = $this->getConfiguration();
    return $config->calculateHealth($agility, $mental, $will, $strength, $armor);
  }

  /**
   * Verify if a set of attributes is valid according to configuration
   *
   * @param int $agility
   * @param int $mental
   * @param int $will
   * @param int $strength
   * @param int $armor
   * @return bool
   */
  public function validateAttributes(int $agility, int $mental, int $will, int $strength, int $armor): bool
  {
    $config = $this->getConfiguration();
    
    // Check if each attribute is within allowed range
    if ($agility < $config->min_attribute_value || $agility > $config->max_attribute_value) {
      return false;
    }
    
    if ($mental < $config->min_attribute_value || $mental > $config->max_attribute_value) {
      return false;
    }
    
    if ($will < $config->min_attribute_value || $will > $config->max_attribute_value) {
      return false;
    }
    
    if ($strength < $config->min_attribute_value || $strength > $config->max_attribute_value) {
      return false;
    }
    
    if ($armor < $config->min_attribute_value || $armor > $config->max_attribute_value) {
      return false;
    }
    
    // Check if total attributes are within allowed range
    $totalAttributes = $agility + $mental + $will + $strength + $armor;
    
    return $totalAttributes >= $config->min_total_attributes && $totalAttributes <= $config->max_total_attributes;
  }
}