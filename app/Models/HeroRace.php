<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeroRace extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'agility_modifier',
    'mental_modifier',
    'will_modifier',
    'strength_modifier',
    'armor_modifier'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'agility_modifier' => 'integer',
    'mental_modifier' => 'integer',
    'will_modifier' => 'integer',
    'strength_modifier' => 'integer',
    'armor_modifier' => 'integer'
  ];

  /**
   * Get the heroes that belong to this race.
   */
  public function heroes(): HasMany
  {
    return $this->hasMany(Hero::class);
  }

  /**
   * Validate attribute modifiers
   *
   * @return bool
   */
  public function validateModifiers(): bool
  {
    $totalModifiers = abs($this->agility_modifier) +
      abs($this->mental_modifier) +
      abs($this->will_modifier) +
      abs($this->strength_modifier) +
      abs($this->armor_modifier);

    // Assume a reasonable limit for total modifier points (same as hero classes)
    return $totalModifiers <= 3;
  }
}