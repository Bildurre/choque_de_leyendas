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
      $slug = $this->getTranslation('slug', $locale, false);
      
      // Si la página tiene un padre, incluir el slug del padre
      if ($this->parent_id) {
        // Cargar explícitamente la relación parent si no está cargada
        if (!$this->relationLoaded('parent')) {
          $this->load('parent');
        }
        
        if ($this->parent) {
          $parentSlug = $this->parent->getTranslation('slug', $locale, false);
          return $parentSlug . '/' . $slug;
        }
      }
      
      return $slug;
    }

    /**
     * Resolve route binding by localized slug.
     */
    public function resolveRouteBinding($value, $field = null)
    {
      $isAdminRoute = request()->is('admin/*');
      $locale = app()->getLocale();
      
      // Dividir la ruta completa en segmentos
      $segments = explode('/', $value);
      $lastSegment = end($segments);
      
      // Construir la consulta base
      $query = self::where(function ($q) use ($lastSegment, $locale) {
        $q->whereJsonContains("slug->{$locale}", $lastSegment);
      });
      
      // Solo aplicar filtro de publicación para rutas públicas
      if (!$isAdminRoute) {
        $query->where('is_published', true);
      }
      
      if (count($segments) > 1) {
        // Ruta jerárquica: buscar primero la página padre por su slug
        $parentSlug = $segments[0];
        $parentQuery = self::where(function ($q) use ($parentSlug, $locale) {
          $q->whereJsonContains("slug->{$locale}", $parentSlug);
        });
        
        // Solo aplicar filtro de publicación para rutas públicas
        if (!$isAdminRoute) {
          $parentQuery->where('is_published', true);
        }
        
        $parent = $parentQuery->first();
        
        if (!$parent) {
          abort(404);
        }
        
        // Luego buscar la página hija por su slug y ID del padre
        return $query->where('parent_id', $parent->id)->firstOrFail();
      } else {
        // Ruta directa: buscar la página por su slug sin padre
        return $query->firstOrFail();
      }
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
}