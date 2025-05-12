<?php

namespace App\Models;

use App\Models\Traits\HasImageAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class FactionDeck extends Model
{
  use HasFactory;
  use HasTranslations;
  use HasTranslatableSlug;
  use SoftDeletes;
  use HasImageAttribute;

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
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
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
    return 'images/faction-decks';
  }

  /**
   * Get the field name for storing images for this model
   * 
   * @return string
   */
  public function getImageField(): string
  {
    return 'icon';
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