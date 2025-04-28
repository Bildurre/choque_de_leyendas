<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\HasColorAttribute;
use App\Models\Traits\HasImageAttribute;
use App\Models\Traits\HasSlug;
use Spatie\Translatable\HasTranslations;

class Faction extends Model
{
  use HasFactory;
  use HasColorAttribute;
  use HasImageAttribute;
  use HasSlug;
  use HasTranslations;

  public $translatable = [
    'name',
    'lore_text'
  ];

  protected $fillable = [
    'name',
    'lore_text',
    'color',
    'icon',
    'text_is_dark'
  ];

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/uploads/faction';
  }

  /**
   * Get all heroes belonging to this faction
   */
  public function heroes(): HasMany
  {
    return $this->hasMany(Hero::class);
  }

  /**
   * Get all cards belonging to this faction
   */
  public function cards(): HasMany
  {
    return $this->hasMany(Card::class);
  }

  /**
   * Get all decks belonging to this faction
   */
  public function decks(): HasMany
  {
    return $this->hasMany(Deck::class);
  }
}