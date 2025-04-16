<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasAttributeModifiers;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeroRace extends Model
{
  use HasFactory;
  use HasAttributeModifiers;

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
   * Check if modifiers are valid using configured limits
   *
   * @return bool
   */
  public function isValidModifiers(): bool
  {
    return $this->validateModifiers([
      'max_absolute_sum' => 3,
      'attribute_limits' => [
        'agility' => 3,
        'mental' => 3,
        'will' => 3,
        'strength' => 3,
        'armor' => 3
      ]
    ]);
  }
}