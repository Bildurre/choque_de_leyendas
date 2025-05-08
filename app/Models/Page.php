<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;
use Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable;

class Page extends Model implements LocalizedUrlRoutable
{
    use HasFactory;
    use SoftDeletes;
    use HasTranslatableSlug;
    use HasTranslations;

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
     * Get the blocks for the page.
     */
    public function blocks()
    {
      return $this->hasMany(Block::class)->orderBy('order', 'asc');
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
     */
    public function getLocalizedRouteKey($locale)
    {
        return $this->getTranslation('slug', $locale, false);
    }

    /**
     * Resolve route binding by localized slug.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $locale = app()->getLocale();
        return $this->where(function ($query) use ($value, $locale) {
            $query->whereJsonContains("slug->{$locale}", $value);
        })->firstOrFail();
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