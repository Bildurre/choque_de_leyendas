<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeckConfigurationController;
use App\Http\Controllers\Api\FactionController;
use App\Http\Controllers\Api\ComponentController;

// No uses el prefijo 'api' aquí, ya que Laravel 12 lo añade automáticamente
// cuando se define como 'api' en bootstrap/app.php

// Rutas para la configuración de mazos
Route::get('/game-modes/{gameMode}/configuration', [DeckConfigurationController::class, 'getForGameMode']);

// Rutas para cartas y héroes de facción
Route::get('/factions/{faction}/cards', [FactionController::class, 'getCards']);
Route::get('/factions/{faction}/heroes', [FactionController::class, 'getHeroes']);

// Rutas para renderizar componentes
Route::match(['get', 'post'], '/components/cards-selector', [ComponentController::class, 'renderCardsSelector']);
Route::match(['get', 'post'], '/components/heroes-selector', [ComponentController::class, 'renderHeroesSelector']);