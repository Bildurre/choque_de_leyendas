<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Content\PageController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Content routes
Route::prefix('content')->name('content.')->group(function () {
  Route::get('/', [PageController::class, 'index'])->name('index');
  Route::get('/{slug}', [PageController::class, 'show'])->name('page');
});

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';