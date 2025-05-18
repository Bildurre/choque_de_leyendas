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
     * 
     * @param string $locale
     * @return string|null
     */
    public function getLocalizedRouteKey($locale)
{
    // Simplemente devolver el slug de la página en el idioma solicitado
    // sin incluir el del padre
    $slug = $this->getTranslation('slug', $locale, false);
    
    // Si no hay traducción, intentamos el idioma por defecto
    if (empty($slug)) {
        $slug = $this->getTranslation('slug', config('app.fallback_locale'), false);
    }
    
    return $slug;
}

    /**
     * Resolve route binding by localized slug.
     * 
     * @param mixed $value
     * @param string|null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Si el valor es nulo o vacío, devolver null inmediatamente
        if (!$value) {
            return null;
        }
        
        $isAdminRoute = request()->is('admin/*');
        $locale = app()->getLocale();
        
        // Construir la consulta base - buscar directamente por el slug
        $query = self::where(function ($q) use ($value, $locale) {
            $q->whereJsonContains("slug->{$locale}", $value)
              ->orWhere(function($subQ) use ($value) {
                  // Buscar en cualquier idioma si no se encuentra en el idioma actual
                  foreach (config('app.available_locales', ['es', 'en']) as $fallbackLocale) {
                      $subQ->orWhereJsonContains("slug->{$fallbackLocale}", $value);
                  }
              });
        });
        
        // Solo aplicar filtro de publicación para rutas públicas
        if (!$isAdminRoute) {
            $query->where('is_published', true);
        }
        
        return $query->first();
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