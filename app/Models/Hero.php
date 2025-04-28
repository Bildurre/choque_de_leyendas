<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasImageAttribute;
use App\Models\Traits\HasSlug;
use Spatie\Translatable\HasTranslations;

class Hero extends Model
{
    use HasFactory;
    use HasImageAttribute;
    use HasSlug;
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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
        'armor'
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'lore_text',
        'passive_name',
        'passive_description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'gender' => 'string',
        'agility' => 'integer',
        'mental' => 'integer',
        'will' => 'integer',
        'strength' => 'integer',
        'armor' => 'integer'
    ];

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/uploads/heroes';
  }

  /**
   * Get the source field for slug generation
   * 
   * @return string
   */
  public function getSlugSourceField(): string
  {
    return 'name';
  }

  /**
   * Get the faction that owns the hero.
   */
  public function faction(): BelongsTo
  {
    return $this->belongsTo(Faction::class);
  }

  /**
   * Get the race that owns the hero.
   */
  public function race(): BelongsTo
  {
    return $this->belongsTo(HeroRace::class, 'hero_race_id');
  }
  
  /**
   * Get the class that owns the hero.
   */
  public function heroClass(): BelongsTo
  {
    return $this->belongsTo(HeroClass::class);
  }

  /**
   * Get the abilities off the hero.
   */
  public function abilities()
  {
    return $this->belongsToMany(HeroAbility::class, 'hero_hero_ability');
  }

  /**
   * Get the superclass through class relation.
   */
  public function getSuperclassAttribute()
  {
    return $this->heroClass ? $this->heroClass->heroSuperclass : null;
  }

  /**
   * Calculate hero's health based on current configuration
   * 
   * @return int
   */
  public function calculateHealth(): int
  {
    $config = app(HeroAttributesConfiguration::class)->first();
    
    if (!$config) {
      return 10; // Default value if no configuration exists
    }
    
    return $config->calculateHealth(
      $this->agility,
      $this->mental,
      $this->will,
      $this->strength,
      $this->armor
    );
  }

  /**
   * Get the total attribute points spent
   * 
   * @return int
   */
  public function getTotalAttributePoints(): int
  {
    return $this->agility + $this->mental + $this->will + $this->strength + $this->armor;
  }

  /**
   * Check if hero attributes are valid according to current configuration
   * 
   * @return bool
   */
  public function hasValidAttributes(): bool
  {
    $config = app(HeroAttributesConfiguration::class)->first();
    
    if (!$config) {
      return true; // If no configuration exists, consider it valid
    }
    
    return $config->validateAttributes([
      'agility' => $this->agility,
      'mental' => $this->mental,
      'will' => $this->will,
      'strength' => $this->strength,
      'armor' => $this->armor
    ]);
  }
  
}