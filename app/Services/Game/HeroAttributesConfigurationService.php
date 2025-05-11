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
}