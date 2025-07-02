<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasImageAttribute;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasTranslatableSlug;
use App\Models\Traits\HasPublishedAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;

class Page extends Model implements LocalizedUrlRoutable
{
  use HasFactory;
  use SoftDeletes;
  use HasTranslatableSlug;
  use HasTranslations;
  use HasImageAttribute;
  use HasPublishedAttribute;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'description',
    'is_published',
    'is_printable',
    'meta_title',
    'meta_description',
    'background_image',
    'parent_id',
    'template',
    'order'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'is_published' => 'boolean',
    'is_printable' => 'boolean',
    'order' => 'integer',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'title',
    'description',
    'meta_title',
    'meta_description',
    'slug'
  ];

  /**
   * Get the blocks for the page.
   */
  public function blocks()
  {
    return $this->hasMany(Block::class)->orderBy('order', 'asc');
  }
  
  /**
   * Get only printable blocks for the page.
   */
  public function printableBlocks()
  {
    return $this->hasMany(Block::class)
      ->where('is_printable', true)
      ->orderBy('order', 'asc');
  }

  /**
   * Get the options for generating the slug.
   */
  public function getSlugOptions(): SlugOptions
  {
    return SlugOptions::create()
      ->generateSlugsFrom('title')
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
   * Get the parent page if any
   */
  public function parent()
  {
    return $this->belongsTo(Page::class, 'parent_id');
  }

  /**
   * Get the child pages
   */
  public function children()
  {
    return $this->hasMany(Page::class, 'parent_id')
      ->orderBy('order', 'asc');
  }

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/pages';
  }

  /**
   * Get the field name for storing images for this model
   * 
   * @return string
   */
  public function getImageField(): string
  {
    return 'background_image';
  }

  /**
   * Scope a query to only include root pages (without parent).
   */
  public function scopeRoot($query)
  {
    return $query->whereNull('parent_id');
  }

  /**
   * Scope a query to only include printable pages.
   */
  public function scopePrintable($query)
  {
    return $query->where('is_printable', true);
  }

  /**
   * Check if page has any PDF generated
   */
  public function hasPdf(): bool
  {
    return \App\Models\GeneratedPdf::where('type', $this->slug)
      ->where('is_permanent', true)
      ->exists();
  }

  /**
   * Get all PDFs for this page
   */
  public function pdfs()
  {
    return \App\Models\GeneratedPdf::where('type', $this->slug)
      ->where('is_permanent', true)
      ->get();
  }
}