<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\CardController;
use App\Http\Controllers\Public\HeroController;
use App\Http\Controllers\Content\PageController;
use App\Http\Controllers\Public\FactionController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Grupo de rutas con localización
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localizationRedirect', 'localeSessionRedirect']
], function () {
    // Página de inicio
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    
    // Redirección de dashboard a home
    Route::get('/dashboard', function () {
        return redirect()->route('welcome');
    });
    
    // Rutas específicas de contenido público
    Route::get(LaravelLocalization::transRoute('routes.factions'), [FactionController::class, 'index'])
        ->name('public.factions.index');
    Route::get(LaravelLocalization::transRoute('routes.faction_show'), [FactionController::class, 'show'])
        ->name('public.factions.show');
    Route::get(LaravelLocalization::transRoute('routes.heroes'), [HeroController::class, 'index'])
        ->name('public.heroes.index');
    Route::get(LaravelLocalization::transRoute('routes.hero_show'), [HeroController::class, 'show'])
        ->name('public.heroes.show');
    Route::get(LaravelLocalization::transRoute('routes.cards'), [CardController::class, 'index'])
        ->name('public.cards.index');
    Route::get(LaravelLocalization::transRoute('routes.card_show'), [CardController::class, 'show'])
        ->name('public.cards.show');
    
    
});

Route::post('/set-locale', [App\Http\Controllers\LocaleController::class, 'setLocale'])->name('set-locale');
    
require __DIR__.'/auth.php';
  
require __DIR__.'/admin.php';

Route::group([
  'prefix' => LaravelLocalization::setLocale(),
  'middleware' => ['localize', 'localizationRedirect', 'localeSessionRedirect']
], function () {
  Route::get(LaravelLocalization::transRoute('routes.page_show'), [PageController::class, 'show'])
    ->name('content.page')
    ->where('page', '[a-z0-9\-]+'); // Restricción para admitir solo slugs válidos
});