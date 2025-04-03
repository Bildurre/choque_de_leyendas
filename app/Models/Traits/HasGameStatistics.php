<?php

namespace App\Models\Traits;

trait HasGameStatistics
{
  /**
   * Calculate the average value for a given attribute
   * 
   * @param string $attribute
   * @return float
   */
  public function average(string $attribute): float
  {
    return $this->newQuery()->avg($attribute) ?? 0;
  }
  
  /**
   * Get the distribution of values for a given attribute
   * 
   * @param string $attribute
   * @return array
   */
  public function distribution(string $attribute): array
  {
    return $this->newQuery()
      ->selectRaw("$attribute, COUNT(*) as count")
      ->groupBy($attribute)
      ->orderBy($attribute)
      ->pluck('count', $attribute)
      ->toArray();
  }
  
  /**
   * Calculate the balance index (ratio between max and min values)
   * 
   * @param string $attribute
   * @return float|null
   */
  public function balanceIndex(string $attribute): ?float
  {
    $max = $this->newQuery()->max($attribute);
    $min = $this->newQuery()->min($attribute);
    
    if (!$min) {
      return null;
    }
    
    return $max / $min;
  }
}