<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\HasSlug;
use App\Models\Traits\HasGameStatistics;

class HeroClass extends Model
{
  use HasFactory;
  use HasSlug;
  use HasGameStatistics;

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
    'superclass_id', // Nuevo campo
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
  public function superclass(): BelongsTo
  {
    return $this->belongsTo(Superclass::class);
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

    // Assume a reasonable limit for total modifier points
    return $totalModifiers <= 3;
  }
}