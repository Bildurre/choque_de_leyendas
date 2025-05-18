<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Page;
use App\Models\Hero;
use App\Models\Card;
use App\Models\Faction;
use App\Models\FactionDeck;

class TranslatableSlugServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Para cada modelo con slug traducible, configurar cómo se resuelve
        Route::bind('page', function ($value) {
            return $this->resolveModelBySlug(Page::class, $value);
        });
        
        Route::bind('hero', function ($value) {
            return $this->resolveModelBySlug(Hero::class, $value);
        });
        
        Route::bind('card', function ($value) {
            return $this->resolveModelBySlug(Card::class, $value);
        });
        
        Route::bind('faction', function ($value) {
            return $this->resolveModelBySlug(Faction::class, $value);
        });
        
        Route::bind('faction_deck', function ($value) {
            return $this->resolveModelBySlug(FactionDeck::class, $value);
        });
        
        // Agrega más modelos según sea necesario
    }
    
    /**
     * Resolve a model by its slug in any available locale.
     *
     * @param string $modelClass The class of the model to resolve
     * @param mixed $value The slug value
     * @return mixed The model instance
     */
    protected function resolveModelBySlug($modelClass, $value)
    {
        // Si ya es un modelo, devolverlo tal cual
        if ($value instanceof $modelClass) {
            return $value;
        }
        
        // Si es un ID numérico, intentar resolver por ID primero
        if (is_numeric($value)) {
            $model = $modelClass::find($value);
            if ($model) {
                return $model;
            }
        }
        
        $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
        
        // Intentar encontrar por cada idioma disponible
        foreach ($locales as $locale) {
            $query = $modelClass::query();
            
            // Incluir modelos eliminados si el modelo utiliza SoftDeletes
            if (in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, 
                           class_uses_recursive($modelClass))) {
                $query->withTrashed();
            }
            
            // Buscar por el slug en el idioma actual
            $model = $query->where(function ($q) use ($value, $locale) {
                // Para MySQL
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(slug, '$.{$locale}')) = ?", [$value]);
            })->first();
            
            if ($model) {
                return $model;
            }
        }
        
        // Si no se encuentra, el comportamiento por defecto es lanzar un 404
        abort(404);
    }
}