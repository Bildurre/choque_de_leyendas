<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasImageAttribute;
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
  use HasImageAttribute;
  use HasFilters;
  use HasPublishedAttribute;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'epic_quote',
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
    'description',
    'epic_quote',
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

  /**
   * Get the available PDF for a specific locale
   */
  public function getAvailablePdf(string $locale = null): ?GeneratedPdf
  {
    $locale = $locale ?? app()->getLocale();
    
    return GeneratedPdf::where('type', 'deck')
      ->where('deck_id', $this->id)
      ->where('locale', $locale)
      ->where('is_permanent', true)
      ->first();
  }

  /**
   * Get a breakdown of card copies by type
   * Equipment cards are further broken down by their category
   * 
   * @return \Illuminate\Support\Collection Collection with card types as keys and copy counts as values
   */
  public function getCardCopiesBreakdown(): \Illuminate\Support\Collection
  {

    $cards = $this->cards()->with(['equipmentType', 'cardType'])->get();
    
    $breakdown = collect();
    
    // Group non-equipment cards by type
    $nonEquipmentCards = $cards->where('card_type_id', '!=', '1');
    $nonEquipmentBreakdown = $nonEquipmentCards
      ->groupBy(function ($card) {
        return $card->cardType ? $card->cardType->name : 'uncategorized';
      })
      ->map(function ($cards) {
        return $cards->sum('pivot.copies');
      });
    
    // Merge non-equipment breakdown
    $breakdown = $breakdown->merge($nonEquipmentBreakdown);
    
    // Handle equipment cards separately
    $equipmentCards = $cards->where('card_type_id', '1');
    
    if ($equipmentCards->isNotEmpty()) {
      // Group equipment cards by category
      $equipmentBreakdown = $equipmentCards
        ->groupBy(function ($card) {
          return $card->equipmentType ? $card->equipmentType->category : 'equipment_other';
        })
        ->map(function ($cards) {
          return $cards->sum('pivot.copies');
        });
      
      // Add equipment breakdown with prefixed keys
      $equipmentBreakdown->each(function ($count, $category) use ($breakdown) {
        $key = $category === 'equipment_other' ? 'equipment_other' : 'equipment_' . $category;
        $breakdown->put($key, $count);
      });
    }
    
    return $breakdown;
  }

  /**
   * Get a breakdown of hero copies by their superclass
   * 
   * @return \Illuminate\Support\Collection Collection with hero superclass names as keys and copy counts as values
   */
  public function getHeroCopiesBreakdown(): \Illuminate\Support\Collection
  {
    $heroes = $this->heroes()->with('heroClass.heroSuperclass')->get();
    
    $breakdown = $heroes->groupBy(function ($hero) {
      // Check if hero has a class and that class has a superclass
      if ($hero->heroClass && $hero->heroClass->heroSuperclass) {
        return $hero->heroClass->heroSuperclass->name;
      }
      return 'uncategorized'; // For heroes without class or superclass
    })->map(function ($heroes) {
      return $heroes->sum('pivot.copies');
    });
    
    return $breakdown;
  }

  /**
   * Get a breakdown of hero copies by their specific class
   * 
   * @return \Illuminate\Support\Collection Collection with hero class names as keys and copy counts as values
   */
  public function getHeroCopiesByClassBreakdown(): \Illuminate\Support\Collection
  {
    $heroes = $this->heroes()->with('heroClass')->get();
    
    $breakdown = $heroes->groupBy(function ($hero) {
      return $hero->heroClass ? $hero->heroClass->name : 'uncategorized';
    })->map(function ($heroes) {
      return $heroes->sum('pivot.copies');
    });
    
    return $breakdown;
  }  
}