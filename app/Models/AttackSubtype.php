<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class AttackSubtype extends Model
{
  use HasFactory;
  use HasTranslations;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'type'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'type' => 'string',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name'
  ];

  /**
   * Get the abilities with this subtype.
   */
  public function abilities(): HasMany
  {
    return $this->hasMany(HeroAbility::class);
  }

  /**
   * Check if the attack subtype is physical
   * 
   * @return bool
   */
  public function isPhysical(): bool
  {
    return $this->type === 'physical';
  }

  /**
   * Check if the attack subtype is magical
   * 
   * @return bool
   */
  public function isMagical(): bool
  {
    return $this->type === 'magical';
  }

  /**
   * Get a human-readable type name
   * 
   * @return string
   */
  public function getTypeNameAttribute(): string
  {
    return __('attack_subtypes.types.' . $this->type);
  }
}