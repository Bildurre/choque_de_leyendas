<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasSlug;
use App\Services\CostTranslatorService;

class HeroAbility extends Model
{
  use HasFactory;
  use HasSlug;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'slug',
    'description',
    'attack_subtype_id',
    'attack_range_id',
    'cost',
    'is_passive'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'is_passive' => 'boolean',
  ];

  /**
   * Get the subtype for this ability.
   */
  public function subtype(): BelongsTo
  {
    return $this->belongsTo(AttackSubtype::class, 'attack_subtype_id');
  }

  /**
   * Get the range for this ability.
   */
  public function range(): BelongsTo
  {
    return $this->belongsTo(AttackRange::class, 'attack_range_id');
  }

  /**
   * Get the heroes that have this ability.
   */
  public function heroes(): BelongsToMany
  {
    return $this->belongsToMany(Hero::class, 'hero_hero_ability')
      ->withPivot('is_default')
      ->withTimestamps();
  }

  /**
   * Get formatted cost as array of color occurrences
   *
   * @return array
   */
  public function getFormattedCostAttribute(): array
  {
    return app(CostTranslatorService::class)->translateToArray($this->cost);
  }

  /**
   * Get the cost translated to HTML components
   *
   * @return string
   */
  public function getHtmlCostAttribute(): string
  {
    return app(CostTranslatorService::class)->translateToHtml($this->cost);
  }
}