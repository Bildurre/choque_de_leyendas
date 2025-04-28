<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\CostTranslatorService;
use Spatie\Translatable\HasTranslations;

class HeroAbility extends Model
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
    'description',
    'attack_range_id',
    'attack_type_id',
    'attack_subtype_id',
    'blast',
    'cost'
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
   * Get the type (physical or magical) for this ability.
   */
  public function getTypeAttribute()
  {
    return $this->subtype ? $this->subtype->type : null;
  }

  /**
   * Check if the ability is a physical attack
   * 
   * @return bool
   */
  public function isPhysicalAttack(): bool
  {
    return $this->subtype && $this->subtype->isPhysical();
  }

  /**
   * Check if the ability is a magical attack
   * 
   * @return bool
   */
  public function isMagicalAttack(): bool
  {
    return $this->subtype && $this->subtype->isMagical();
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