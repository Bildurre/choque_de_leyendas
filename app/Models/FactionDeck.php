<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\HasAdminFilters;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasImageAttribute;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasTranslatableSlug;
use App\Models\Traits\HasPublishedAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FactionDeck extends Model
{
  use HasFactory;
  use HasTranslations;
  use HasTranslatableSlug;
  use SoftDeletes;
  use HasImageAttribute;
  use HasAdminFilters;
  use HasPublishedAttribute;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'slug',
    'icon',
    'faction_id',
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
  ];

  /**
  * Get fields that can be filtered
  *
  * @return array
  */
  public function getAdminFilterable(): array
  {
    return [
      [
        'type' => 'relation',
        'field' => 'faction_id',
        'relation' => 'faction',
        'label' => __('entities.factions.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
      ],
      [
        'type' => 'relation',
        'field' => 'game_mode_id',
        'relation' => 'gameMode',
        'label' => __('entities.game_modes.singular'),
        'option_label' => 'name',
        'option_value' => 'id'
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
  * Get fields that can be sorted
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
        'field' => 'faction.name',
        'label' => __('entities.factions.singular')
      ],
      [
        'field' => 'gameMode.name',
        'label' => __('entities.game_modes.singular')
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
    return 'images/faction-decks';
  }

  /**
   * Get the faction that owns the faction deck.
   */
  public function faction()
  {
    return $this->belongsTo(Faction::class);
  }

  /**
   * Get the game mode associated with this faction deck.
   */
  public function gameMode()
  {
    return $this->belongsTo(GameMode::class);
  }

  /**
   * Get the cards in this faction deck.
   */
  public function cards()
  {
    return $this->belongsToMany(Card::class, 'card_faction_deck')
      ->withPivot('copies')
      ->withTimestamps();
  }

  /**
   * Get the heroes in this faction deck.
   */
  public function heroes()
  {
    return $this->belongsToMany(Hero::class, 'faction_deck_hero')
      ->withPivot('copies')
      ->withTimestamps();
  }

  /**
   * Get the total number of cards in the deck (including copies)
   * 
   * @return int
   */
  public function getTotalCardsAttribute(): int
  {
    return $this->cards->sum('pivot.copies');
  }

  /**
   * Get the total number of heroes in the deck (including copies)
   * 
   * @return int
   */
  public function getTotalHeroesAttribute(): int
  {
    return $this->heroes->sum('pivot.copies');
  }
}