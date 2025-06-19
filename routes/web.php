<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\CardController;
use App\Http\Controllers\Public\HeroController;
use App\Http\Controllers\Content\PageController;
use App\Http\Controllers\Public\FactionController;
use App\Http\Controllers\Public\FactionDeckController;
use App\Http\Controllers\Public\PrintCollectionController;
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
    
    // // Redirección de dashboard a home
    // Route::get('/dashboard', function () {
    //     return redirect()->route('welcome');
    // });
    
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


    // Print Collection routes
    Route::prefix('print-collection')->name('public.print-collection.')->group(function () {
        Route::get('/', [PrintCollectionController::class, 'index'])->name('index');
        Route::post('/add', [PrintCollectionController::class, 'add'])->name('add');
        Route::post('/update', [PrintCollectionController::class, 'update'])->name('update');
        Route::post('/remove', [PrintCollectionController::class, 'remove'])->name('remove');
        Route::post('/clear', [PrintCollectionController::class, 'clear'])->name('clear');
        Route::get('/generate-pdf', [PrintCollectionController::class, 'generatePdf'])->name('generate-pdf');
    });


    if (app()->environment('local')) {
        Route::get('/debug-pdf', function () {
            // Parámetros de debug
            $numHeroes = request()->get('heroes', 5);
            $numCards = request()->get('cards', 10);
            $onlyHeroes = request()->get('only_heroes', false);
            $onlyCards = request()->get('only_cards', false);
            
            $items = [];
            
            // Añadir héroes
            if (!$onlyCards) {
                $heroes = \App\Models\Hero::with(['faction', 'heroClass', 'heroRace'])
                    ->published()
                    ->take($numHeroes)
                    ->get();
                    
                foreach ($heroes as $hero) {
                    $copies = request()->get('hero_copies', 1);
                    for ($i = 0; $i < $copies; $i++) {
                        $items[] = [
                            'type' => 'hero',
                            'entity' => $hero
                        ];
                    }
                }
            }
            
            // Añadir cartas
            if (!$onlyHeroes) {
                $cards = \App\Models\Card::with(['faction', 'cardType'])
                    ->published()
                    ->take($numCards)
                    ->get();
                    
                foreach ($cards as $card) {
                    $copies = request()->get('card_copies', 1);
                    for ($i = 0; $i < $copies; $i++) {
                        $items[] = [
                            'type' => 'card',
                            'entity' => $card
                        ];
                    }
                }
            }
            
            // Mezclar si se solicita
            if (request()->get('shuffle')) {
                shuffle($items);
            }
            
            // Mostrar HTML
            if (request()->get('html')) {
                return view('public.print-collection.pdf', compact('items'));
            }
            
            // Generar PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('public.print-collection.pdf', compact('items'));
            $pdf->setPaper('a4', 'portrait');
            
            // Configuraciones adicionales
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => false,
                'defaultFont' => 'sans-serif',
                'dpi' => 150,
                'enable_font_subsetting' => false,
            ]);
            
            if (request()->get('inline')) {
                return $pdf->stream('debug.pdf');
            }
            
            return $pdf->download('debug-' . date('Y-m-d-H-i-s') . '.pdf');
        })->name('debug.pdf');
    }
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