<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasAttributeModifiers;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeroClass extends Model
{
  use HasFactory;
  use HasAttributeModifiers;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'hero_classes';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'passive',
    'hero_superclass_id',
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
   * Get the superclass that owns the hero class.
   */
  public function heroSuperclass(): BelongsTo
  {
    return $this->belongsTo(HeroSuperclass::class, 'hero_superclass_id');
  }

  /**
   * Get the heros that belong to this hero class.
   */
  public function heroes()
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
    'max_modifiable_attributes' => 3,
    'attribute_limits' => 1
  ]);
}
}