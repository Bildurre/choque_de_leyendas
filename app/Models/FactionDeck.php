<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\HasFilters;
use App\Models\Traits\HasImageAttribute;
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
  use HasImageAttribute;

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
    'epic_quote'
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
      ->withTimestamps();
  }

  /**
   * Get the cards that belong to this deck.
   */
  public function cards()
  {
    return $this->belongsToMany(Card::class, 'card_faction_deck')
      ->withPivot('copies')
      ->withTimestamps();
  }

  /**
   * Get the total number of cards (sum of copies)
   * 
   * @return int
   */
  public function getTotalCardsAttribute(): int
  {
    return $this->cards->sum('pivot.copies');
  }

  /**
   * Get the total number of heroes
   * 
   * @return int
   */
  public function getTotalHeroesAttribute(): int
  {
    return $this->heroes->count();
  }

  /**
   * Get card breakdown by type with copies
   * 
   * @return array
   */
  public function getCardCopiesBreakdown(): array
  {
    $breakdown = [];
    
    foreach ($this->cards as $card) {
      $typeName = $card->cardType->name ?? 'Unknown';
      $copies = $card->pivot->copies ?? 0;
      
      if (!isset($breakdown[$typeName])) {
        $breakdown[$typeName] = 0;
      }
      
      $breakdown[$typeName] += $copies;
    }
    
    return $breakdown;
  }

  /**
   * Get hero breakdown by class with count
   * 
   * @return array
   */
  public function getHeroCopiesByClassBreakdown(): array
  {
    $breakdown = [];
    
    foreach ($this->heroes as $hero) {
      $className = $hero->heroClass->name ?? 'Unknown';
      
      if (!isset($breakdown[$className])) {
        $breakdown[$className] = 0;
      }
      
      $breakdown[$className]++;
    }
    
    return $breakdown;
  }

  /**
   * Get hero breakdown by superclass (currently just class, for future expansion)
   * 
   * @return array
   */
  public function getHeroCopiesBreakdown(): array
  {
    // For now, same as by class
    // Can be expanded later for superclass grouping
    return $this->getHeroCopiesByClassBreakdown();
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

  /**
   * Get all non-mercenary factions
   * 
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getNonMercenaryFactions()
  {
    return $this->factions->filter(function ($faction) {
      return !$faction->isMercenaries();
    });
  }

  /**
   * Get color configuration for preview
   * 
   * @return array
   */
  public function getColorConfiguration(): array
  {
    $nonMercenaryFactions = $this->getNonMercenaryFactions();
    
    if ($nonMercenaryFactions->count() === 1) {
      // Single faction deck
      $faction = $nonMercenaryFactions->first();
      return [
        'is_multi_faction' => false,
        'colors' => [$faction->color],
        'rgb_values' => [$faction->rgb_values],
        'text_color' => $faction->text_is_dark ? '#000000' : '#ffffff',
      ];
    }
    
    // Multi-faction deck
    $colors = $nonMercenaryFactions->pluck('color')->toArray();
    $rgbValues = $nonMercenaryFactions->pluck('rgb_values')->toArray();
    
    return [
      'is_multi_faction' => true,
      'colors' => $colors,
      'rgb_values' => $rgbValues,
      'text_color' => '#ffffff', // Default to white for multi-faction
    ];
  }

  /**
   * Get gradient CSS for multi-faction decks
   * 
   * @return string
   */
  public function getGradientCss(): string
  {
    $config = $this->getColorConfiguration();
    
    if (!$config['is_multi_faction']) {
      return $config['colors'][0];
    }
    
    $colorCount = count($config['colors']);
    $percentage = 100 / $colorCount;
    
    $stops = [];
    foreach ($config['colors'] as $index => $color) {
      $start = $index * $percentage;
      $end = ($index + 1) * $percentage;
      $stops[] = "{$color} {$start}%, {$color} {$end}%";
    }
    
    return 'linear-gradient(135deg, ' . implode(', ', $stops) . ')';
  }

    /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/decks';
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
}