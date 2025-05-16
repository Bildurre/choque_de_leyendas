<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\CardController;
use App\Http\Controllers\Public\HeroController;
use App\Http\Controllers\Content\PageController;
use App\Http\Controllers\Public\FactionController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Wrap routes in LaravelLocalization group
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
    // Homepage
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    // Profile routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Factions routes
    Route::get('/factions', [FactionController::class, 'index'])->name('public.factions.index');
    Route::get('/factions/{faction:slug}', [FactionController::class, 'show'])->name('public.factions.show');

    // Heroes routes
    Route::get('/heroes', [HeroController::class, 'index'])->name('public.heroes.index');
    Route::get('/heroes/{hero:slug}', [HeroController::class, 'show'])->name('public.heroes.show');

    // Cards routes
    Route::get('/cards', [CardController::class, 'index'])->name('public.cards.index');
    Route::get('/cards/{card:slug}', [CardController::class, 'show'])->name('public.cards.show');

    // Rutas de contenido
    Route::get('/{page}', [PageController::class, 'show'])
      ->name('content.page')
      ->where('page', '(?!admin|api|login|register|profile|pages).*');
});

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';