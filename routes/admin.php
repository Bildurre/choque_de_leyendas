<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Game\HeroRaceController;
use App\Http\Controllers\Game\HeroClassController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Game\HeroSuperclassController;

Route::middleware(['auth', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
  // Dashboard
  Route::get('/', [DashboardController::class, 'view'])->name('dashboard');
  Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');

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