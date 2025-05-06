<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Controllers\Game\CardController;
use App\Http\Controllers\Game\HeroController;
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
use App\Http\Controllers\Content\Admin\ContentPageController;
use App\Http\Controllers\Content\Admin\ContentBlockController;
use App\Http\Controllers\Content\Admin\ContentImageController;
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

  // Content Management
  Route::prefix('content')->name('content.')->group(function () {
    // Content Pages
    Route::resource('pages', ContentPageController::class);
    
    // Content Blocks
    Route::get('pages/{page}/sections/{section}/blocks/create', [ContentBlockController::class, 'create'])
      ->name('blocks.create');
    Route::post('pages/{page}/sections/{section}/blocks', [ContentBlockController::class, 'store'])
      ->name('blocks.store');
    Route::get('pages/{page}/sections/{section}/blocks/{block}/edit', [ContentBlockController::class, 'edit'])
      ->name('blocks.edit');
    Route::put('pages/{page}/sections/{section}/blocks/{block}', [ContentBlockController::class, 'update'])
      ->name('blocks.update');
    Route::delete('pages/{page}/sections/{section}/blocks/{block}', [ContentBlockController::class, 'destroy'])
      ->name('blocks.destroy');
    Route::post('pages/{page}/sections/{section}/blocks/reorder', [ContentBlockController::class, 'reorder'])
      ->name('blocks.reorder');
    
    // Content Images
    Route::get('images', [ContentImageController::class, 'index'])
      ->name('images.index');
    Route::post('images', [ContentImageController::class, 'store'])
      ->name('images.store');
    Route::delete('images', [ContentImageController::class, 'destroy'])
      ->name('images.destroy');
  });
});