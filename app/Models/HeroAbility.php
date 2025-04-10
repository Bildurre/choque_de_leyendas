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

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'description',
    'attack_range_id',
    'attack_type_id',
    'attack_subtype_id',
    'cost'
  ];

  /**
   * Get the subtype for this ability.
   */
  public function type(): BelongsTo
  {
    return $this->belongsTo(AttackType::class, 'attack_type_id');
  }

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