<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Game\FactionController;
use App\Http\Controllers\Game\CardTypeController;
use App\Http\Controllers\Game\HeroRaceController;
use App\Http\Controllers\Game\HeroClassController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Game\AttackRangeController;
use App\Http\Controllers\Game\AttackSubtypeController;
use App\Http\Controllers\Game\EquipmentTypeController;
use App\Http\Controllers\Game\HeroSuperclassController;
use App\Http\Controllers\Game\HeroAttributesConfigurationController;

Route::middleware(['auth', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
  // Dashboard
  Route::get('/', [DashboardController::class, 'view'])->name('dashboard');
  Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');

  // Hero Attributes Configuration
  Route::get('hero-attributes-configurations/edit', [HeroAttributesConfigurationController::class, 'edit'])->name('hero-attributes-configurations.edit');
  Route::put('hero-attributes-configurations', [HeroAttributesConfigurationController::class, 'update'])->name('hero-attributes-configurations.update');

  // Hero Classes
  Route::resource('hero-classes', HeroClassController::class);
  Route::post('hero-classes/{id}/restore', [HeroClassController::class, 'restore'])->name('hero-classes.restore');
  Route::delete('hero-classes/{id}/force-delete', [HeroClassController::class, 'forceDelete'])->name('hero-classes.force-delete');

  // Hero Superclasses
  Route::resource('hero-superclasses', HeroSuperclassController::class);
  Route::post('hero-superclasses/{id}/restore', [HeroSuperclassController::class, 'restore'])->name('hero-superclasses.restore');
  Route::delete('hero-superclasses/{id}/force-delete', [HeroSuperclassController::class, 'forceDelete'])->name('hero-superclasses.force-delete');

  // Hero Races
  Route::resource('hero-races', HeroRaceController::class);
  Route::post('hero-races/{id}/restore', [HeroRaceController::class, 'restore'])->name('hero-races.restore');
  Route::delete('hero-races/{id}/force-delete', [HeroRaceController::class, 'forceDelete'])->name('hero-races.force-delete');

  // Card Types
  Route::resource('card-types', CardTypeController::class);
  Route::post('card-types/{id}/restore', [CardTypeController::class, 'restore'])->name('card-types.restore');
  Route::delete('card-types/{id}/force-delete', [CardTypeController::class, 'forceDelete'])->name('card-types.force-delete');

  // Equipment Types
  Route::resource('equipment-types', EquipmentTypeController::class);
  Route::post('equipment-types/{id}/restore', [EquipmentTypeController::class, 'restore'])->name('equipment-types.restore');
  Route::delete('equipment-types/{id}/force-delete', [EquipmentTypeController::class, 'forceDelete'])->name('equipment-types.force-delete');

  // Attack Subtypes
  Route::resource('attack-subtypes', AttackSubtypeController::class);
  Route::post('attack-subtypes/{id}/restore', [AttackSubtypeController::class, 'restore'])->name('attack-subtypes.restore');
  Route::delete('attack-subtypes/{id}/force-delete', [AttackSubtypeController::class, 'forceDelete'])->name('attack-subtypes.force-delete');

  // Attack Ranges
  Route::resource('attack-ranges', AttackRangeController::class);
  Route::post('attack-ranges/{id}/restore', [AttackRangeController::class, 'restore'])->name('attack-ranges.restore');
  Route::delete('attack-ranges/{id}/force-delete', [AttackRangeController::class, 'forceDelete'])->name('attack-ranges.force-delete');

  // Factions
  Route::resource('factions', FactionController::class);
  Route::post('factions/{id}/restore', [FactionController::class, 'restore'])->name('factions.restore');
  Route::delete('factions/{id}/force-delete', [FactionController::class, 'forceDelete'])->name('factions.force-delete');
      
  // Pages
  Route::resource('pages', PageController::class);
  Route::post('pages/{id}/restore', [PageController::class, 'restore'])->name('pages.restore');
  Route::delete('pages/{id}/force-delete', [PageController::class, 'forceDelete'])->name('pages.force-delete');

  Route::prefix('pages/{page}/blocks')->name('pages.blocks.')->group(function () {
    Route::get('/create/{type}', [BlockController::class, 'create'])->name('create');
    Route::post('/', [BlockController::class, 'store'])->name('store');
    Route::get('/{block}/edit', [BlockController::class, 'edit'])->name('edit');
    Route::put('/{block}', [BlockController::class, 'update'])->name('update');
    Route::delete('/{block}', [BlockController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [BlockController::class, 'reorder'])->name('reorder');    
    Route::post('/{id}/restore', [BlockController::class, 'restore'])->name('restore');
    Route::delete('/{id}/force-delete', [BlockController::class, 'forceDelete'])->name('force-delete');
});
});