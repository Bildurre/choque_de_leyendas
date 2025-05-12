<?php

namespace App\Services\Game;

use App\Models\DeckAttributesConfiguration;
use Exception;

class DeckAttributesConfigurationService
{
  /**
   * Get the current configuration
   *
   * @return DeckAttributesConfiguration
   */
  public function getConfiguration(): DeckAttributesConfiguration
  {
    return DeckAttributesConfiguration::getDefault();
  }

  /**
   * Update the configuration
   *
   * @param array $data
   * @return DeckAttributesConfiguration
   * @throws Exception
   */
  public function updateConfiguration(array $data): DeckAttributesConfiguration
  {
    try {
      $config = $this->getConfiguration();
      $config->fill($data);
      $config->save();
      
      return $config;
    } catch (Exception $e) {
      throw new Exception("Error al actualizar la configuración de mazos: " . $e->getMessage());
    }
  }

  /**
   * Validate a deck against the current configuration
   * 
   * @param int $totalCards
   * @param bool $hasExceededCardCopies
   * @param bool $hasExceededHeroCopies
   * @return array Validation results
   */
  public function validateDeck(int $totalCards, bool $hasExceededCardCopies, bool $hasExceededHeroCopies): array
  {
    $config = $this->getConfiguration();
    return $config->validateDeck($totalCards, $hasExceededCardCopies, $hasExceededHeroCopies);
  }
}