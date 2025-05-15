<?php

namespace App\Models;

use App\Models\Traits\HasAdminFilters;
use App\Models\Traits\HasCostAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class HeroAbility extends Model
{
  use HasFactory;
  use HasTranslations;
  use SoftDeletes;
  use HasCostAttribute;
  use HasAdminFilters;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'attack_range_id',
    'attack_subtype_id',
    'area',
    'cost',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'area' => 'boolean',
    'deleted_at' => 'datetime',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
    'description'
  ];

  /**
   * Get the attack range that owns the hero ability.
   */
  public function attackRange()
  {
    return $this->belongsTo(AttackRange::class);
  }

  /**
   * Get the attack subtype that owns the hero ability.
   */
  public function attackSubtype()
  {
    return $this->belongsTo(AttackSubtype::class);
  }

  /**
   * Get the heroes that have this ability.
   */
  public function heroes()
  {
    return $this->belongsToMany(Hero::class, 'hero_hero_ability');
  }

  /**
   * Get the cards that are based on this hero ability.
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }
}