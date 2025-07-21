<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\CardController;
use App\Http\Controllers\Public\HeroController;
use App\Http\Controllers\Content\PageController;
use App\Http\Controllers\Public\FactionController;
use App\Http\Controllers\Public\FactionDeckController;
use App\Http\Controllers\Public\PdfCollectionController;
use App\Http\Controllers\Public\TemporaryCollectionController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Temporary Collection Routes (AJAX - no localization needed)
Route::prefix('pdf-collection')->name('public.pdf-collection.')->group(function () {
    Route::post('/add', [TemporaryCollectionController::class, 'add'])->name('add');
    Route::post('/remove', [TemporaryCollectionController::class, 'remove'])->name('remove');
    Route::post('/update-copies', [TemporaryCollectionController::class, 'updateCopies'])->name('update-copies');
    Route::delete('/clear', [TemporaryCollectionController::class, 'clear'])->name('clear');
    Route::post('/generate', [TemporaryCollectionController::class, 'generate'])->name('generate');
    Route::get('/status', [TemporaryCollectionController::class, 'status'])->name('status');
    Route::get('/items', [TemporaryCollectionController::class, 'items'])->name('items');
});

// Grupo de rutas con localización
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localizationRedirect', 'localeSessionRedirect']
], function () {
    // Página de inicio
    Route::get('/', [PageController::class, 'welcome'])
    ->name('welcome');
    
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
    Route::get(LaravelLocalization::transRoute('routes.faction_deck_show'), [FactionDeckController::class, 'show'])
        ->name('public.faction-decks.show');

    Route::prefix(LaravelLocalization::transRoute('routes.downloads'))->name('public.pdf-collection.')->group(function () {
      Route::get('/', [PdfCollectionController::class, 'index'])
        ->name('index');
      Route::get(LaravelLocalization::transRoute('routes.pdf_view'), [PdfCollectionController::class, 'view'])
        ->name('view');
      Route::get(LaravelLocalization::transRoute('routes.pdf_download'), [PdfCollectionController::class, 'download'])
        ->name('download');        
      Route::delete(LaravelLocalization::transRoute('routes.pdf_delete'), [PdfCollectionController::class, 'destroy'])
        ->name('destroy');
    });
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