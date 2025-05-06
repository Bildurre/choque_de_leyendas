<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\HasSlug;
use Spatie\Translatable\HasTranslations;

class ContentPage extends Model
{
  use HasFactory;
  use HasSlug;
  use HasTranslations;

  protected $fillable = [
    'title',
    'slug',
    'is_published',
    'meta_description',
    'background_image',
    'order',
    'show_in_menu',
    'parent_slug'
  ];

  public $translatable = [
    'title',
    'meta_description'
  ];

  /**
   * Get the blocks for this page
   */
  public function blocks(): HasMany
  {
    return $this->hasMany(ContentBlock::class, 'content_page_id')->orderBy('order');
  }

  /**
   * Get the source field for slug generation
   */
  public function getSlugSourceField(): string
  {
    return 'title';
  }

  /**
   * Get child pages
   */
  public function children()
  {
    return $this->hasMany(ContentPage::class, 'parent_slug', 'slug');
  }

  /**
   * Get parent page
   */
  public function parent()
  {
    return $this->belongsTo(ContentPage::class, 'parent_slug', 'slug');
  }

  /**
   * Check if the page has a specific locale version
   */
  public function hasLocale(string $locale): bool
  {
    return $this->getTranslation('title', $locale, false) !== false;
  }

  /**
   * Get the localized slug for this page
   */
  public function getLocalizedSlug(string $locale = null): string
  {
    $locale = $locale ?? app()->getLocale();
    
    // If we have a translation for this locale, generate a slug from it
    if ($this->hasLocale($locale)) {
      return str_slug($this->getTranslation('title', $locale));
    }
    
    // Otherwise return the default slug
    return $this->slug;
  }
}