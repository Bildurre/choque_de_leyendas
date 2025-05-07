<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;

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
  Route::get('/', [\App\Http\Controllers\Content\PageController::class, 'index'])->name('index');
  Route::get('/{slug}', [\App\Http\Controllers\Content\PageController::class, 'show'])->name('page.show');
});

Route::get('/language/{locale}', [LanguageController::class, 'change'])->name('language.change');


require __DIR__.'/admin.php';
require __DIR__.'/auth.php';