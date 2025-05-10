<?php

namespace App\Models;

use App\Models\Traits\HasImageAttribute;
use App\Models\Traits\HasCostAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Card extends Model
{
  use HasFactory;
  use HasTranslations;
  use HasTranslatableSlug;
  use SoftDeletes;
  use HasImageAttribute;
  use HasCostAttribute;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'slug',
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
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'hands' => 'integer',
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
    'slug',
    'lore_text',
    'effect',
    'restriction'
  ];

  /**
   * Get the options for generating the slug.
   */
  public function getSlugOptions(): SlugOptions
  {
    return SlugOptions::create()
      ->generateSlugsFrom('name')
      ->saveSlugsTo('slug');
  }

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/cards';
  }

  /**
   * Get the faction that owns the card.
   */
  public function faction()
  {
    return $this->belongsTo(Faction::class);
  }

  /**
   * Get the card type that owns the card.
   */
  public function cardType()
  {
    return $this->belongsTo(CardType::class);
  }

  /**
   * Get the equipment type that owns the card.
   */
  public function equipmentType()
  {
    return $this->belongsTo(EquipmentType::class);
  }

  /**
   * Get the attack range that owns the card.
   */
  public function attackRange()
  {
    return $this->belongsTo(AttackRange::class);
  }

  /**
   * Get the attack subtype that owns the card.
   */
  public function attackSubtype()
  {
    return $this->belongsTo(AttackSubtype::class);
  }

  /**
   * Get the hero ability that this card is linked to (if any).
   */
  public function heroAbility()
  {
    return $this->belongsTo(HeroAbility::class);
  }

  /**
   * Check if this card is a weapon
   * 
   * @return bool
   */
  public function isWeapon(): bool
  {
    return $this->equipmentType && $this->equipmentType->category === 'weapon';
  }

  /**
   * Check if this card is armor
   * 
   * @return bool
   */
  public function isArmor(): bool
  {
    return $this->equipmentType && $this->equipmentType->category === 'armor';
  }

  /**
   * Check if this card is equipment (weapon or armor)
   * 
   * @return bool
   */
  public function isEquipment(): bool
  {
    return $this->equipmentType !== null;
  }

  /**
   * Check if this card is an attack
   * 
   * @return bool
   */
  public function isAttack(): bool
  {
    return $this->attackSubtype !== null;
  }

  /**
   * Check if this card is an area attack
   * 
   * @return bool
   */
  public function isAreaAttack(): bool
  {
    return $this->isAttack() && $this->area;
  }
}