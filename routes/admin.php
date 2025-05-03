<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\HeroController;
use App\Http\Controllers\Admin\FactionController;
use App\Http\Controllers\Admin\CardTypeController;
use App\Http\Controllers\Admin\HeroRaceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroClassController;
use App\Http\Controllers\Admin\AttackTypeController;
use App\Http\Controllers\Admin\AttackRangeController;
use App\Http\Controllers\Admin\ContentPageController;
use App\Http\Controllers\Admin\HeroAbilityController;
use App\Http\Controllers\Admin\ContentBlockController;
use App\Http\Controllers\Admin\AttackSubtypeController;
use App\Http\Controllers\Admin\EquipmentTypeController;
use App\Http\Controllers\Admin\ContentSectionController;
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

  // Content Pages
  Route::resource('content-pages', ContentPageController::class);
  
  // Content Sections
  Route::post('content-sections', [ContentSectionController::class, 'store'])->name('content-sections.store');
  Route::put('content-sections/{contentSection}', [ContentSectionController::class, 'update'])->name('content-sections.update');
  Route::delete('content-sections/{contentSection}', [ContentSectionController::class, 'destroy'])->name('content-sections.destroy');
  Route::put('content-sections/{contentSection}/reorder/{order}', [ContentSectionController::class, 'reorder'])->name('content-sections.reorder');
  
  // Content Blocks
  Route::post('content-blocks', [ContentBlockController::class, 'store'])->name('content-blocks.store');
  Route::put('content-blocks/{contentBlock}', [ContentBlockController::class, 'update'])->name('content-blocks.update');
  Route::delete('content-blocks/{contentBlock}', [ContentBlockController::class, 'destroy'])->name('content-blocks.destroy');
  Route::put('content-blocks/{contentBlock}/reorder/{order}', [ContentBlockController::class, 'reorder'])->name('content-blocks.reorder');
});