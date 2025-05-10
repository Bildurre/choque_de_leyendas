<?php

namespace App\Models;

use App\Models\Traits\HasColorAttribute;
use App\Models\Traits\HasImageAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Faction extends Model
{
  use HasFactory;
  use HasTranslations;
  use HasTranslatableSlug;
  use SoftDeletes;
  use HasColorAttribute;
  use HasImageAttribute;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'slug',
    'lore_text',
    'color',
    'icon',
    'text_is_dark',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'text_is_dark' => 'boolean',
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
    'lore_text'
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
    return 'images/factions';
  }

  /**
   * Get the heroes that belong to this faction.
   */
  public function heroes()
  {
    return $this->hasMany(Hero::class);
  }

  /**
   * Get the cards that belong to this faction.
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }

  /**
   * Get the icon URL attribute
   * 
   * @return string|null
   */
  public function getIconUrlAttribute(): ?string
  {
    if (!$this->icon) {
      return null;
    }
    
    return asset('storage/' . $this->icon);
  }
}