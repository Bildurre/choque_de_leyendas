<?php

namespace App\Services\Game;

class CostTranslatorService
{
  /**
   * Map of dice colors
   *
   * @var array
   */
  protected $colorMap = [
    'R' => 'red',
    'G' => 'green',
    'B' => 'blue'
  ];

  /**
   * Translate a cost string to an array of occurrences
   *
   * @param string|null $cost
   * @return array
   */
  public function translateToArray(?string $cost): array
  {
    if (!$cost) {
      return [];
    }

    $result = [
      'red' => 0,
      'green' => 0,
      'blue' => 0
    ];

    $costArray = str_split(strtoupper($cost));
    
    foreach ($costArray as $dice) {
      if (isset($this->colorMap[$dice])) {
        $color = $this->colorMap[$dice];
        $result[$color]++;
      }
    }

    return $result;
  }

  /**
   * Translate a cost string to HTML dice components
   *
   * @param string|null $cost
   * @return string
   */
  public function translateToHtml(?string $cost): string
  {
    if (!$cost) {
      return '';
    }

    $html = '';
    $costArray = str_split(strtoupper($cost));
    
    foreach ($costArray as $dice) {
      if (isset($this->colorMap[$dice])) {
        $variant = 'mono-' . $this->colorMap[$dice];
        $html .= '<x-game.game-dice variant="' . $variant . '" size="sm" class="cost-dice" />';
      }
    }

    return $html;
  }

  /**
   * Validate if a cost string is valid
   *
   * @param string|null $cost
   * @return bool
   */
  public function isValidCost(?string $cost): bool
  {
    if (!$cost) {
      return true; // Empty cost is valid
    }

    // Must be between 0 and 5 characters
    if (strlen($cost) > 5) {
      return false;
    }

    // Must only contain R, G, B (case insensitive)
    return preg_match('/^[RGBrgb]*$/', $cost) === 1;
  }

  /**
   * Get the total dice count of a cost
   *
   * @param string|null $cost
   * @return int
   */
  public function getTotalDiceCount(?string $cost): int
  {
    if (!$cost) {
      return 0;
    }

    return strlen($cost);
  }
}