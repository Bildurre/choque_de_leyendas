<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Controllers\Game\CardController;
use App\Http\Controllers\Game\HeroController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Game\FactionController;
use App\Http\Controllers\Game\CardTypeController;
use App\Http\Controllers\Game\HeroRaceController;
use App\Http\Controllers\Game\HeroClassController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Game\AttackRangeController;
use App\Http\Controllers\Game\HeroAbilityController;
use App\Http\Controllers\Game\AttackSubtypeController;
use App\Http\Controllers\Game\EquipmentTypeController;
use App\Http\Controllers\Game\HeroSuperclassController;
use App\Http\Controllers\Game\HeroAttributesConfigurationController;

Route::middleware(['auth', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
  // Dashboard
  Route::get('/', [DashboardController::class, 'view'])->name('dashboard');
  Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');

  // Factions
  Route::resource('factions', FactionController::class);

  // Hero Abilities
  Route::resource('hero-abilities', HeroAbilityController::class)->except(['show']);
  Route::post('hero-abilities/validate-cost', [HeroAbilityController::class, 'validateCost'])
    ->name('hero-abilities.validate-cost');

  // Hero Attribute Configuration
  Route::get('/hero-attributes-configurations', [HeroAttributesConfigurationController::class, 'edit'])
    ->name('hero-attributes-configurations.edit');
  Route::put('/hero-attributes-configurations', [HeroAttributesConfigurationController::class, 'update'])
    ->name('hero-attributes-configurations.update');

  // Hero Superclasses
  Route::resource('hero-superclasses', HeroSuperclassController::class);

  // Hero Classes
  Route::resource('hero-classes', HeroClassController::class);

  // Attack Subtypes
  Route::resource('attack-subtypes', AttackSubtypeController::class);

  // Attack Ranges
  Route::resource('attack-ranges', AttackRangeController::class);

  // Hero Races
  Route::resource('hero-races', HeroRaceController::class);

  // Heroes
  Route::resource('heroes', HeroController::class);

  // Equipment Types
  Route::resource('equipment-types', EquipmentTypeController::class);

  // Card Types
  Route::resource('card-types', CardTypeController::class);

  // Cards
  Route::resource('cards', CardController::class);

  // Pages
  Route::resource('pages', PageController::class);
});