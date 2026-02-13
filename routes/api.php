<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\GameDataController;

Route::prefix('v1')->name('api.v1.')->group(function () {
  // Bulk endpoint - returns all game data in a single request
  Route::get('/game-data', [GameDataController::class, 'index'])->name('game-data');

  // Individual resource endpoints
  Route::get('/factions', [GameDataController::class, 'factions'])->name('factions');
  Route::get('/heroes', [GameDataController::class, 'heroes'])->name('heroes');
  Route::get('/cards', [GameDataController::class, 'cards'])->name('cards');
  Route::get('/hero-abilities', [GameDataController::class, 'heroAbilities'])->name('hero-abilities');
  Route::get('/card-types', [GameDataController::class, 'cardTypes'])->name('card-types');
  Route::get('/card-subtypes', [GameDataController::class, 'cardSubtypes'])->name('card-subtypes');
  Route::get('/hero-superclasses', [GameDataController::class, 'heroSuperclasses'])->name('hero-superclasses');
  Route::get('/hero-classes', [GameDataController::class, 'heroClasses'])->name('hero-classes');
  Route::get('/hero-races', [GameDataController::class, 'heroRaces'])->name('hero-races');
  Route::get('/equipment-types', [GameDataController::class, 'equipmentTypes'])->name('equipment-types');
  Route::get('/attack-ranges', [GameDataController::class, 'attackRanges'])->name('attack-ranges');
  Route::get('/attack-subtypes', [GameDataController::class, 'attackSubtypes'])->name('attack-subtypes');
  Route::get('/counters', [GameDataController::class, 'counters'])->name('counters');
  Route::get('/game-modes', [GameDataController::class, 'gameModes'])->name('game-modes');
  Route::get('/faction-decks', [GameDataController::class, 'factionDecks'])->name('faction-decks');
  Route::get('/config/hero-attributes', [GameDataController::class, 'heroAttributesConfig'])->name('config.hero-attributes');
});
