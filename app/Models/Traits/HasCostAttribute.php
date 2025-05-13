<?php

namespace App\Models\Traits;

trait HasCostAttribute
{
  /**
   * Get ordered cost attribute
   * 
   * @param string|null $cost Cost to order (uses model cost if null)
   * @return string
   */
  public function getOrderedCost(?string $cost = null): string
  {
    $costToOrder = $cost ?? $this->attributes['cost'] ?? '';
    
    if (empty($costToOrder)) {
      return '';
    }
    
    // Count occurrences of each color
    $costArray = str_split(strtoupper($costToOrder));
    $red = 0;
    $green = 0;
    $blue = 0;
    
    foreach ($costArray as $dice) {
      if ($dice === 'R') $red++;
      elseif ($dice === 'G') $green++;
      elseif ($dice === 'B') $blue++;
    }
    
    // Rebuild the cost string in RGB order
    return str_repeat('R', $red) . str_repeat('G', $green) . str_repeat('B', $blue);
  }
  
  /**
   * Set the cost attribute with automatic ordering
   * 
   * @param string|null $value
   * @return void
   */
  public function setCostAttribute(?string $value): void
  {
    if ($value) {
      $this->attributes['cost'] = $this->getOrderedCost($value);
    } else {
      $this->attributes['cost'] = null;
    }
  }
  
  /**
   * Parse the cost string to an array of colors
   * 
   * @return array
   */
  public function getParsedCostAttribute(): array
  {
    $orderedCost = $this->getOrderedCost();
    
    if (empty($orderedCost)) {
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
    
    $costArray = str_split(strtoupper($orderedCost));
    
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
    return $this->getOrderedCost() ? strlen($this->getOrderedCost()) : 0;
  }
}