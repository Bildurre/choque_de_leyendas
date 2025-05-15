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
  
  /**
   * Get accessor for cost total
   * 
   * @return int
   */
  public function getCostTotalAttribute(): int
  {
    return $this->getOrderedCost() ? strlen($this->getOrderedCost()) : 0;
  }

  /**
   * Get accessor for cost order - for sorting purposes
   * This creates a string that can be used for sorting with R first, then G, then B
   * 
   * @return string
   */
  public function getCostOrderAttribute(): string
  {
    if (empty($this->attributes['cost'])) {
      return '';
    }
    
    $cost = strtoupper($this->attributes['cost']);
    $r = substr_count($cost, 'R');
    $g = substr_count($cost, 'G');
    $b = substr_count($cost, 'B');
    
    // Creating a string that will sort properly when using string comparison
    // Padding with zeros to ensure proper sorting (up to 9 dice of each color)
    return sprintf('%02d%02d%02d', $r, $g, $b);
  }

  /**
   * Get an array of the colors in this cost
   * 
   * @return array
   */
  public function getCostColorsAttribute(): array
  {
    if (empty($this->attributes['cost'])) {
      return [];
    }
    
    $cost = strtoupper($this->attributes['cost']);
    $colors = [];
    
    if (strpos($cost, 'R') !== false) $colors[] = 'R';
    if (strpos($cost, 'G') !== false) $colors[] = 'G';
    if (strpos($cost, 'B') !== false) $colors[] = 'B';
    
    return $colors;
  }

  /**
   * Get HTML representation of the cost
   * 
   * @return string
   */
  public function getCostHtmlAttribute(): string
  {
    if (empty($this->attributes['cost'])) {
      return '';
    }
    
    $cost = strtoupper($this->attributes['cost']);
    $html = '';
    
    for ($i = 0; $i < strlen($cost); $i++) {
      $dice = $cost[$i];
      $colorClass = '';
      
      switch ($dice) {
        case 'R':
          $colorClass = 'filter-cost__tag--red';
          break;
        case 'G':
          $colorClass = 'filter-cost__tag--green';
          break;
        case 'B':
          $colorClass = 'filter-cost__tag--blue';
          break;
      }
      
      $html .= '<span class="filter-cost__tag ' . $colorClass . '">' . $dice . '</span>';
    }
    
    return $html;
  }
  
  /**
   * Get predefined cost options for filtering
   * 
   * @return array
   */
  public function getPredefinedCostOptions(): array
  {
    // Lista de costes comunes predefinidos
    $costs = [
      '',       // Sin coste
      'R',      // Un dado rojo
      'G',      // Un dado verde
      'B',      // Un dado azul
      'RR',     // Dos dados rojos
      'GG',     // Dos dados verdes
      'BB',     // Dos dados azules
      'RG',     // Un dado rojo y un dado verde
      'RB',     // Un dado rojo y un dado azul
      'GB',     // Un dado verde y un dado azul
      'RRR',    // Tres dados rojos
      'GGG',    // Tres dados verdes
      'BBB',    // Tres dados azules
      'RRG',    // Dos dados rojos y un dado verde
      'RRB',    // Dos dados rojos y un dado azul
      'RGG',    // Un dado rojo y dos dados verdes
      'RBB',    // Un dado rojo y dos dados azules
      'GGB',    // Dos dados verdes y un dado azul
      'GBB',    // Un dado verde y dos dados azules
      'RGB'     // Un dado de cada color
    ];
    
    $options = [];
    foreach ($costs as $cost) {
      if (empty($cost)) {
        $options[$cost] = __('common.no_cost');
      } else {
        // Usamos el coste como clave y como valor
        $options[$cost] = $cost;
      }
    }
    
    return $options;
  }
}