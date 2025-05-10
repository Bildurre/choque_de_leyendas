<?php

namespace App\Models\Traits;

trait HasCostAttribute
{
  /**
   * Parse the cost string to an array of colors
   * 
   * @return array
   */
  public function getParsedCostAttribute(): array
  {
    if (empty($this->cost)) {
      return [
        'red' => 0,
        'green' => 0,
        'blue' => 0
      ];
    }

    $result = [
      'red' => 0,
      'green' => 0,
      'blue' => 0
    ];

    $costArray = str_split(strtoupper($this->cost));
    
    foreach ($costArray as $dice) {
      if ($dice === 'R') {
        $result['red']++;
      } elseif ($dice === 'G') {
        $result['green']++;
      } elseif ($dice === 'B') {
        $result['blue']++;
      }
    }

    return $result;
  }

  /**
   * Get the total dice cost
   * 
   * @return int
   */
  public function getTotalCostAttribute(): int
  {
    return $this->cost ? strlen($this->cost) : 0;
  }

  /**
   * Get a formatted representation of the card's cost
   * 
   * @return string
   */
  public function getFormattedCostAttribute(): string
  {
    $parsedCost = $this->parsed_cost;
    $formatted = '';
    
    if ($parsedCost['red'] > 0) {
      $formatted .= $parsedCost['red'] . ' ' . __('game.cost.red') . ' ';
    }
    
    if ($parsedCost['green'] > 0) {
      $formatted .= $parsedCost['green'] . ' ' . __('game.cost.green') . ' ';
    }
    
    if ($parsedCost['blue'] > 0) {
      $formatted .= $parsedCost['blue'] . ' ' . __('game.cost.blue') . ' ';
    }
    
    return trim($formatted) ?: __('game.cost.free');
  }

  /**
   * Validate if a cost string is valid
   * 
   * @param string|null $cost
   * @return bool
   */
  public function isValidCost(?string $cost = null): bool
  {
    $costToValidate = $cost ?? $this->cost;
    
    if (empty($costToValidate)) {
      return true; // Empty cost is valid
    }

    // Must be between 0 and 5 characters
    if (strlen($costToValidate) > 5) {
      return false;
    }

    // Must only contain R, G, B (case insensitive)
    return preg_match('/^[RGBrgb]*$/', $costToValidate) === 1;
  }
}