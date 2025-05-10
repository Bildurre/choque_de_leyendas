<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroAttributesConfiguration extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'hero_attributes_configurations';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'min_attribute_value',
    'max_attribute_value',
    'min_total_attributes',
    'max_total_attributes',
    'agility_multiplier',
    'mental_multiplier',
    'will_multiplier',
    'strength_multiplier',
    'armor_multiplier',
    'total_health_base',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'min_attribute_value' => 'integer',
    'max_attribute_value' => 'integer',
    'min_total_attributes' => 'integer',
    'max_total_attributes' => 'integer',
    'agility_multiplier' => 'integer',
    'mental_multiplier' => 'integer',
    'will_multiplier' => 'integer',
    'strength_multiplier' => 'integer',
    'armor_multiplier' => 'integer',
    'total_health_base' => 'integer',
  ];

  /**
   * Get the default configuration
   * 
   * @return \App\Models\HeroAttributesConfiguration
   */
  public static function getDefault(): self
  {
    return self::firstOrCreate([], [
      'min_attribute_value' => 1,
      'max_attribute_value' => 5,
      'min_total_attributes' => 12,
      'max_total_attributes' => 18,
      'agility_multiplier' => -1,
      'mental_multiplier' => -1,
      'will_multiplier' => 1,
      'strength_multiplier' => -1,
      'armor_multiplier' => 1,
      'total_health_base' => 30,
    ]);
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
    $health = $this->total_health_base;
    
    $health += $agility * $this->agility_multiplier;
    $health += $mental * $this->mental_multiplier;
    $health += $will * $this->will_multiplier;
    $health += $strength * $this->strength_multiplier;
    $health += $armor * $this->armor_multiplier;
    
    return max(1, $health); // Ensure health is at least 1
  }
}