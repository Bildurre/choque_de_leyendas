<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroAttributesConfiguration extends Model
{
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
    'total_health_base'
  ];

  /**
   * The attributes that should be cast to native types.
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
    'total_health_base' => 'integer'
  ];

  /**
   * Calculate health for a set of attributes
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
    return $this->total_health_base + 
           ($agility * $this->agility_multiplier) +
           ($mental * $this->mental_multiplier) +
           ($will * $this->will_multiplier) +
           ($strength * $this->strength_multiplier) +
           ($armor * $this->armor_multiplier);
  }

  /**
   * Validate attributes against configuration constraints
   * 
   * @param array $attributes
   * @return bool
   */
  public function validateAttributes(array $attributes): bool
  {
    // Extract attributes
    $agility = $attributes['agility'] ?? 0;
    $mental = $attributes['mental'] ?? 0;
    $will = $attributes['will'] ?? 0;
    $strength = $attributes['strength'] ?? 0;
    $armor = $attributes['armor'] ?? 0;
    
    // Check individual attribute constraints
    if ($agility < $this->min_attribute_value || $agility > $this->max_attribute_value ||
        $mental < $this->min_attribute_value || $mental > $this->max_attribute_value ||
        $will < $this->min_attribute_value || $will > $this->max_attribute_value ||
        $strength < $this->min_attribute_value || $strength > $this->max_attribute_value ||
        $armor < $this->min_attribute_value || $armor > $this->max_attribute_value) {
      return false;
    }
    
    // Calculate total attributes
    $totalAttributes = $agility + $mental + $will + $strength + $armor;
    
    // Check total attributes constraints
    return $totalAttributes >= $this->min_total_attributes && 
           $totalAttributes <= $this->max_total_attributes;
  }
}