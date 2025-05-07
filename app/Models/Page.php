<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HasSlug;
use App\Models\Traits\HasImageAttribute;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
  use HasFactory;
  use SoftDeletes;
  use HasSlug;
  use HasImageAttribute;
  use HasTranslations;

   /**
   * Define the source field for slug generation
   */
  const SLUG_SOURCE = 'title';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'description',
    'image',
    'is_published',
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
    return 'images/uploads/pages';
  }

  /**
   * Check if the page is published
   */
  public function isPublished(): bool
  {
    return $this->is_published;
  }

  /**
   * Get the full URL to the page
   */
  public function getUrl(): string
  {
    $locale = app()->getLocale();
    $slug = $this->getTranslation('slug', $locale);
    return route('content.page', $slug);
  }

  /**
   * Scope a query to only include published pages.
   */
  public function scopePublished($query)
  {
    return $query->where('is_published', true);
  }

  /**
   * Scope a query to only include root pages (without parent).
   */
  public function scopeRoot($query)
  {
    return $query->whereNull('parent_id');
  }
}