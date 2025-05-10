<?php

namespace App\Models\Traits;

trait HasCostAttribute
{
  /**
   * Get ordered cost string in RGB format
   * 
   * @param string|null $cost
   * @return string
   */
  public function getOrderedCost(?string $cost = null): string
  {
    $costToOrder = $cost ?? $this->cost ?? '';
    
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
   * Get HTML representation of cost with dice icons
   * 
   * @return string
   */
  public function getIconHtmlAttribute(): string
  {
    $orderedCost = $this->getOrderedCost();
    
    if (empty($orderedCost)) {
      return '';
    }

    $html = '';
    $costArray = str_split(strtoupper($orderedCost));
    
    foreach ($costArray as $dice) {
      if ($dice === 'R') {
        $html .= '<span class="dice-icon dice-icon--red">' . $this->getDiceIcon('#f15959') . '</span>';
      } elseif ($dice === 'G') {
        $html .= '<span class="dice-icon dice-icon--green">' . $this->getDiceIcon('#29ab5f') . '</span>';
      } elseif ($dice === 'B') {
        $html .= '<span class="dice-icon dice-icon--blue">' . $this->getDiceIcon('#408cfd') . '</span>';
      }
    }

    return $html;
  }
  
  /**
   * Get an array of SVG icons for the cost
   * 
   * @return array
   */
  public function getCostIconsArrayAttribute(): array
  {
    $orderedCost = $this->getOrderedCost();
    
    if (empty($orderedCost)) {
      return [];
    }

    $icons = [];
    $costArray = str_split(strtoupper($orderedCost));
    
    foreach ($costArray as $dice) {
      if ($dice === 'R') {
        $icons[] = $this->getDiceIcon('#f15959');
      } elseif ($dice === 'G') {
        $icons[] = $this->getDiceIcon('#29ab5f');
      } elseif ($dice === 'B') {
        $icons[] = $this->getDiceIcon('#408cfd');
      }
    }

    return $icons;
  }

  /**
   * Get dice SVG icon with specified fill color
   * 
   * @param string $fillColor Hex color code
   * @return string
   */
  private function getDiceIcon(string $fillColor): string
  {
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" stroke-linejoin="round" width="20px" height="20px">
      <polygon 
        points="100,180 30,140 30,60 100,100" 
        fill="' . $fillColor . '" 
        stroke="black" 
        stroke-width="2"
      />
      <polygon 
        points="100,180 100,100 170,60 170,140" 
        fill="' . $fillColor . '" 
        stroke="black" 
        stroke-width="2"
      />
      <polygon 
        points="100,100 30,60 100,20 170,60" 
        fill="' . $fillColor . '" 
        stroke="black" 
        stroke-width="2"
      />
    </svg>';
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