<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
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

    // Rutas del perfil
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Rutas traducibles con transRoute
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

    // Rutas de páginas de contenido
    Route::get('/{page}', [PageController::class, 'show'])
        ->name('content.page')
        ->where('page', '(?!admin|api|login|register|profile|pages).*');
});

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';