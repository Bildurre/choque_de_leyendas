<?php

namespace App\Models;

use App\Models\Traits\HasImageAttribute;
use App\Models\Traits\HasSlug;
use App\Services\Game\CostTranslatorService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Card extends Model
{
  use HasFactory;
  use HasSlug;
  use HasImageAttribute;
  use HasTranslations;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'image',
    'lore_text',
    'faction_id',
    'card_type_id',
    'equipment_type_id',
    'attack_range_id',
    'attack_subtype_id',
    'hero_ability_id',
    'hands',
    'cost',
    'effect',
    'restriction',
    'area',
    'is_attack',
    'has_hero_ability',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'area' => 'boolean',
    'hands' => 'integer',
    'is_attack' => 'boolean',
    'has_hero_ability' => 'boolean',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
    'lore_text',
    'effect',
    'restriction',
  ];

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/uploads/cards';
  }

  /**
   * Get the faction that owns the card.
   */
  public function faction(): BelongsTo
  {
    return $this->belongsTo(Faction::class);
  }

  /**
   * Get the card type that owns the card.
   */
  public function cardType(): BelongsTo
  {
    return $this->belongsTo(CardType::class);
  }

  /**
   * Get the equipment type that owns the card.
   */
  public function equipmentType(): BelongsTo
  {
    return $this->belongsTo(EquipmentType::class);
  }

  /**
   * Get the attack range that owns the card.
   */
  public function attackRange(): BelongsTo
  {
    return $this->belongsTo(AttackRange::class);
  }

  /**
   * Get the attack subtype that owns the card.
   */
  public function attackSubtype(): BelongsTo
  {
    return $this->belongsTo(AttackSubtype::class);
  }

  /**
   * Get the hero ability that owns the card.
   */
  public function heroAbility(): BelongsTo
  {
    return $this->belongsTo(HeroAbility::class);
  }

  /**
   * Check if the card is a weapon.
   */
  public function isWeapon(): bool
  {
    return $this->equipmentType && $this->equipmentType->isWeapon();
  }

  /**
   * Check if the card is an armor.
   */
  public function isArmor(): bool
  {
    return $this->equipmentType && $this->equipmentType->isArmor();
  }

  /**
   * Check if the card is equipment.
   */
  public function isEquipment(): bool
  {
    return $this->equipmentType !== null;
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

  /**
   * Get translated hands text based on current locale
   * 
   * @return string|null
   */
  public function getHandsTextAttribute(): ?string
  {
    if ($this->hands === null) {
      return null;
    }

    return trans_choice('cards.hands', $this->hands, ['count' => $this->hands]);
  }
}