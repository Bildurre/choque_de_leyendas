<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasAttributeModifiers;
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
      'max_modifiable_attributes' => 2,
      'attribute_limits' => 1
    ]);
  }
}