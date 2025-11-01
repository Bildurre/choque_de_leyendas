<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasTranslatableSlug;
use App\Models\Traits\HasPublishedAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;

class FactionDeck extends Model implements LocalizedUrlRoutable
{
  use HasFactory;
  use HasTranslations;
  use HasTranslatableSlug;
  use SoftDeletes;
  use HasFilters;
  use HasPublishedAttribute;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'slug',
    'description',
    'game_mode_id',
    'is_published',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'deleted_at' => 'datetime',
    'is_published' => 'boolean',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
    'slug',
    'description',
  ];

  /**
   * Get fields that can be searched in admin
   *
   * @return array
   */
  public function getAdminSearchable(): array
  {
    return ['description'];
  }

  /**
   * Get fields that can be searched in public
   *
   * @return array
   */
  public function getPublicSearchable(): array
  {
    return ['description'];
  }

  /**
   * Get fields that can be filtered in admin
   *
   * @return array
   */
  public function getAdminFilterable(): array
  {
    return [
      [
        'type' => 'relationship',
        'field' => 'game_mode_id',
        'label' => __('entities.game_modes.singular'),
        'model' => GameMode::class,
        'display_field' => 'name'
      ],
      [
        'type' => 'enum',
        'field' => 'is_published',
        'label' => __('admin.publication_status'),
        'options' => [
          '1' => __('admin.published'),
          '0' => __('admin.draft')
        ]
      ],
    ];
  }

  /**
   * Get fields that can be filtered in public
   *
   * @return array
   */
  public function getPublicFilterable(): array
  {
    return [
      [
        'type' => 'relationship',
        'field' => 'game_mode_id',
        'label' => __('entities.game_modes.singular'),
        'model' => GameMode::class,
        'display_field' => 'name'
      ],
    ];
  }

  /**
   * Get fields that can be sorted in admin
   *
   * @return array
   */
  public function getAdminSortable(): array
  {
    return [
      [
        'field' => 'name',
        'label' => __('entities.faction_decks.name')
      ],
      [
        'field' => 'created_at',
        'label' => __('common.created_at')
      ],
      [
        'field' => 'is_published',
        'label' => __('admin.publication_status')
      ],
    ];
  }

  /**
   * Get fields that can be sorted in public
   *
   * @return array
   */
  public function getPublicSortable(): array
  {
    return [
      [
        'field' => 'name',
        'label' => __('entities.faction_decks.name')
      ],
    ];
  }

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
   * Get the route key for the model.
   */
  public function getRouteKeyName(): string
  {
    return 'slug';
  }

  /**
   * Get the localized route key for a specific locale.
   *
   * @param string $locale
   * @return string|null
   */
  public function getLocalizedRouteKey($locale)
  {
    return $this->getTranslation('slug', $locale, false);
  }

  /**
   * Get the factions that belong to this deck (many-to-many).
   */
  public function factions()
  {
    return $this->belongsToMany(Faction::class, 'faction_deck_faction')
      ->withTimestamps();
  }

  /**
   * Get the game mode that this deck belongs to.
   */
  public function gameMode()
  {
    return $this->belongsTo(GameMode::class);
  }

  /**
   * Get the heroes that belong to this deck.
   */
  public function heroes()
  {
    return $this->belongsToMany(Hero::class, 'faction_deck_hero')
      ->withPivot('position')
      ->withTimestamps()
      ->orderBy('faction_deck_hero.position');
  }

  /**
   * Get the cards that belong to this deck.
   */
  public function cards()
  {
    return $this->belongsToMany(Card::class, 'card_faction_deck')
      ->withTimestamps();
  }

  /**
   * Check if this deck is multi-faction (has more than one non-mercenary faction).
   * 
   * @return bool
   */
  public function isMultiFaction(): bool
  {
    $nonMercenaryFactions = $this->factions()
      ->where('factions.id', '!=', 1) // Mercenaries ID = 1
      ->count();
    
    return $nonMercenaryFactions > 1;
  }

  /**
   * Get the primary faction for display purposes.
   * Returns the first non-mercenary faction, or mercenary if that's all there is.
   * 
   * @return Faction|null
   */
  public function getPrimaryFaction(): ?Faction
  {
    // Try to get first non-mercenary faction
    $primaryFaction = $this->factions()
      ->where('factions.id', '!=', 1)
      ->first();
    
    // If no non-mercenary faction, return mercenary or null
    return $primaryFaction ?? $this->factions()->first();
  }

  /**
   * Get all faction IDs for this deck.
   * 
   * @return array
   */
  public function getFactionIds(): array
  {
    return $this->factions()->pluck('factions.id')->toArray();
  }

  /**
   * Check if this deck has mercenary faction.
   * 
   * @return bool
   */
  public function hasMercenaries(): bool
  {
    return $this->factions()->where('factions.id', 1)->exists();
  }
}