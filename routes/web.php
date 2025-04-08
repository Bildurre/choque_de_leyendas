<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\FactionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroClassController;
use App\Http\Controllers\Admin\AttackTypeController;
use App\Http\Controllers\Admin\SuperclassController;
use App\Http\Controllers\Admin\AttackRangeController;
use App\Http\Controllers\Admin\HeroAbilityController;
use App\Http\Controllers\Admin\AttackSubtypeController;
use App\Http\Controllers\Admin\HeroAttributeConfigurationController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para el administrador protegidas por middleware
Route::middleware(['auth', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
  // Dashboard
  Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');
  Route::get('/', function () {
    return redirect('admin/dashboard');
  });

  // Facciones
  Route::resource('factions', FactionController::class);

  // Hero Attributes Configuration
  Route::get('/hero-attributes', [HeroAttributeConfigurationController::class, 'edit'])->name('hero-attributes.edit');
  Route::put('/hero-attributes', [HeroAttributeConfigurationController::class, 'update'])->name('hero-attributes.update');

  // Hero Classes
  Route::resource('hero-classes', HeroClassController::class);

  // Superclasses
  Route::resource('superclasses', SuperclassController::class);

 // Attack Types
  Route::resource('attack-types', AttackTypeController::class);

  // Attack Subtypes
  Route::resource('attack-subtypes', AttackSubtypeController::class);
  Route::get('attack-subtypes/by-type/{typeId}', [AttackSubtypeController::class, 'getByType'])
    ->name('attack-subtypes.by-type');

  // Attack Ranges
  Route::resource('attack-ranges', AttackRangeController::class);

  // Hero Abilities
  Route::resource('hero-abilities', HeroAbilityController::class);
  Route::post('hero-abilities/validate-cost', [HeroAbilityController::class, 'validateCost'])
    ->name('hero-abilities.validate-cost');
  });


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
