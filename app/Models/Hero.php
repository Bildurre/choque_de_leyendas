<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hero extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'description',
    'faction_id',
    'hero_class_id',
    'agility',
    'mental',
    'will',
    'strength',
    'armor',
    'health'
  ];

  /**
   * Get the faction that owns the hero.
   */
  public function faction(): BelongsTo
  {
    return $this->belongsTo(Faction::class);
  }

  /**
   * Get the class that owns the hero.
   */
  public function heroClass(): BelongsTo
  {
    return $this->belongsTo(HeroClass::class);
  }
  
  /**
   * Get the abilities that belong to this hero.
   */
  public function abilities(): BelongsToMany
  {
    return $this->belongsToMany(HeroAbility::class, 'hero_hero_ability')
      ->withPivot('is_default')
      ->withTimestamps();
  }

  /**
   * Get only the default abilities of this hero.
   */
  public function defaultAbilities()
  {
    return $this->abilities()->wherePivot('is_default', true);
  }
}