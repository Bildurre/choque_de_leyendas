<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Controllers\Admin\FactionController;
use App\Http\Controllers\Admin\HeroRaceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroClassController;
use App\Http\Controllers\Admin\AttackTypeController;
use App\Http\Controllers\Admin\AttackRangeController;
use App\Http\Controllers\Admin\HeroAbilityController;
use App\Http\Controllers\Admin\AttackSubtypeController;
use App\Http\Controllers\Admin\HeroSuperclassController;
use App\Http\Controllers\Admin\HeroAttributesConfigurationController;

Route::middleware(['auth', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
  // Dashboard
  Route::get('/', [DashboardController::class, 'view'])->name('dashboard');
  Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');

  // Factions
  Route::resource('factions', FactionController::class);

  // Hero Abilities
  Route::controller(HeroAbilityController::class)->prefix('hero-abilities')->name('hero-abilities.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{heroAbility}/edit', 'edit')->name('edit');
    Route::put('/{heroAbility}', 'update')->name('update');
    Route::delete('/{heroAbility}', 'destroy')->name('destroy');
    Route::post('/validate-cost', 'validateCost')->name('validate-cost');
  });

  // Hero Attribute Configuration
  Route::get('/hero-attributes-configurations', [HeroAttributesConfigurationController::class, 'edit'])
    ->name('hero-attributes-configurations.edit');
  Route::put('/hero-attributes-configurations', [HeroAttributesConfigurationController::class, 'update'])
    ->name('hero-attributes-configurations.update');

  // Hero Superclasses
  Route::resource('hero-superclasses', HeroSuperclassController::class);

  // Hero Classes
  Route::resource('hero-classes', HeroClassController::class);

  // Attack Types
  Route::resource('attack-types', AttackTypeController::class);

  // Attack Subtypes
  Route::resource('attack-subtypes', AttackSubtypeController::class);

  // Attack Ranges
  Route::resource('attack-ranges', AttackRangeController::class);

  // Hero Races
  Route::resource('hero-races', HeroRaceController::class);
});