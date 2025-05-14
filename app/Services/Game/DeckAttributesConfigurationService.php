<?php

namespace App\Services\Game;

use App\Models\DeckAttributesConfiguration;
use Exception;

class DeckAttributesConfigurationService
{
  /**
   * Get a configuration for a specific game mode or create a default one
   *
   * @param int|null $gameModeId
   * @return DeckAttributesConfiguration
   */
  public function getConfiguration(?int $gameModeId = null): DeckAttributesConfiguration
  {
    if ($gameModeId) {
      $config = DeckAttributesConfiguration::where('game_mode_id', $gameModeId)->first();
      if ($config) {
        return $config;
      }
    }
    
    // Get default configuration (without game mode or with ID 1)
    return DeckAttributesConfiguration::whereNull('game_mode_id')
      ->orWhere('game_mode_id', 1)
      ->first() ?? $this->createDefaultConfiguration();
  }
  
  /**
   * Create a default configuration
   *
   * @return DeckAttributesConfiguration
   */
  private function createDefaultConfiguration(): DeckAttributesConfiguration
  {
    $config = new DeckAttributesConfiguration();
    $config->game_mode_id = 1; // Default game mode ID
    $config->min_cards = 30;
    $config->max_cards = 40;
    $config->max_copies_per_card = 2;
    $config->max_copies_per_hero = 1;
    $config->save();
    
    return $config;
  }

  /**
   * Create a new configuration
   *
   * @param array $data
   * @return DeckAttributesConfiguration
   * @throws Exception
   */
  public function create(array $data): DeckAttributesConfiguration
  {
    // Check if configuration already exists for this game mode
    if (isset($data['game_mode_id']) && 
        DeckAttributesConfiguration::where('game_mode_id', $data['game_mode_id'])->exists()) {
      throw new Exception(__('deck_attributes.validation.game_mode_exists'));
    }
    
    try {
      $config = new DeckAttributesConfiguration();
      $config->fill($data);
      $config->save();
      
      return $config;
    } catch (Exception $e) {
      throw new Exception("__('entities.deck_attributes.errors.create') + ' '" . $e->getMessage());
    }
  }

  /**
   * Update an existing configuration
   *
   * @param DeckAttributesConfiguration $config
   * @param array $data
   * @return DeckAttributesConfiguration
   * @throws Exception
   */
  public function update(DeckAttributesConfiguration $config, array $data): DeckAttributesConfiguration
  {
    try {
      $config->fill($data);
      $config->save();
      
      return $config;
    } catch (Exception $e) {
      throw new Exception("__('entities.deck_attributes.errors.update') + ' '" . $e->getMessage());
    }
  }

  /**
   * Delete a configuration
   *
   * @param DeckAttributesConfiguration $config
   * @return bool
   * @throws Exception
   */
  public function delete(DeckAttributesConfiguration $config): bool
  {
    try {
      return $config->delete();
    } catch (Exception $e) {
      throw new Exception("__('entities.deck_attributes.errors.delete') + ' '" . $e->getMessage());
    }
  }

  /**
   * Validate a deck against the appropriate configuration
   * 
   * @param int $totalCards
   * @param bool $hasExceededCardCopies
   * @param bool $hasExceededHeroCopies
   * @param int|null $gameModeId
   * @return array Validation results
   */
  public function validateDeck(
    int $totalCards, 
    bool $hasExceededCardCopies, 
    bool $hasExceededHeroCopies, 
    ?int $gameModeId = null
  ): array {
    $config = $this->getConfiguration($gameModeId);
    return $config->validateDeck($totalCards, $hasExceededCardCopies, $hasExceededHeroCopies);
  }
}