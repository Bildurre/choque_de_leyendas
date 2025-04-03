<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroAttributeConfiguration extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'base_agility',
    'base_mental', 
    'base_will',
    'base_strength',
    'base_armor',
    'total_points'
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'base_agility' => 'integer',
    'base_mental' => 'integer',
    'base_will' => 'integer', 
    'base_strength' => 'integer',
    'base_armor' => 'integer',
    'total_points' => 'integer'
  ];

  /**
   * Validate that the total points are correctly distributed
   * 
   * @param array $attributes Attributes to validate
   * @return bool
   */
  public function validatePointDistribution(array $attributes): bool
  {
    $baseTotal = 
      $this->base_agility + 
      $this->base_mental + 
      $this->base_will + 
      $this->base_strength + 
      $this->base_armor;
    
    $additionalPoints = array_sum([
      $attributes['agility'] ?? 0,
      $attributes['mental'] ?? 0,
      $attributes['will'] ?? 0,
      $attributes['strength'] ?? 0,
      $attributes['armor'] ?? 0
    ]);

    return ($baseTotal + $additionalPoints) <= $this->total_points;
  }

  /**
   * Calculate remaining points for hero creation
   * 
   * @param array $attributes Current attributes
   * @return int
   */
  public function getRemainingPoints(array $attributes): int
  {
    $baseTotal = 
      $this->base_agility + 
      $this->base_mental + 
      $this->base_will + 
      $this->base_strength + 
      $this->base_armor;
    
    $additionalPoints = array_sum([
      $attributes['agility'] ?? 0,
      $attributes['mental'] ?? 0,
      $attributes['will'] ?? 0,
      $attributes['strength'] ?? 0,
      $attributes['armor'] ?? 0
    ]);

    return $this->total_points - ($baseTotal + $additionalPoints);
  }
}