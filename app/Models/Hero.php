<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasImageAttribute;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasTranslatableSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\Game\HeroAttributesConfigurationService;

class Hero extends Model
{
  use HasFactory;
  use HasTranslations;
  use HasTranslatableSlug;
  use SoftDeletes;
  use HasImageAttribute;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'heroes';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'slug',
    'lore_text',
    'passive_name',
    'passive_description',
    'image',
    'faction_id',
    'hero_race_id',
    'hero_class_id',
    'gender',
    'agility',
    'mental',
    'will',
    'strength',
    'armor',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'agility' => 'integer',
    'mental' => 'integer',
    'will' => 'integer',
    'strength' => 'integer',
    'armor' => 'integer',
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
    'passive_name',
    'passive_description'
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
    return 'images/heroes';
  }

  /**
   * Get the faction that owns the hero.
   */
  public function faction()
  {
    return $this->belongsTo(Faction::class);
  }
  
  /**
   * Get the race of the hero.
   */
  public function heroRace()
  {
    return $this->belongsTo(HeroRace::class);
  }
  
  /**
   * Get the class of the hero.
   */
  public function heroClass()
  {
    return $this->belongsTo(HeroClass::class);
  }

  /**
   * Get the abilities of the hero.
   */
  public function heroAbilities()
  {
    return $this->belongsToMany(HeroAbility::class, 'hero_hero_ability');
  }

  /**
   * Calculate total health based on attributes and configuration
   * 
   * @return int
   */
  public function getHealthAttribute(): int
  {
    $configService = app(HeroAttributesConfigurationService::class);
    return $configService->calculateHealth(
      $this->agility,
      $this->mental,
      $this->will,
      $this->strength,
      $this->armor
    );
  }

  /**
   * Get the total attribute points used
   * 
   * @return int
   */
  public function getTotalAttributesAttribute(): int
  {
    return $this->agility + $this->mental + $this->will + $this->strength + $this->armor;
  }

  /**
   * Check if hero attributes are valid according to configuration
   * 
   * @return bool
   */
  public function hasValidAttributes(): bool
  {
    $configService = app(HeroAttributesConfigurationService::class);
    return $configService->validateAttributes(
      $this->agility,
      $this->mental,
      $this->will,
      $this->strength,
      $this->armor
    );
  }
}