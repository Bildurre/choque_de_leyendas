<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Public\ContentPageController;

// Página de bienvenida actual
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas públicas de contenido
Route::get('/home', [ContentPageController::class, 'home'])->name('home');
Route::get('/rules', [ContentPageController::class, 'rules'])->name('rules');
Route::get('/components', [ContentPageController::class, 'components'])->name('components');
Route::get('/annexes', [ContentPageController::class, 'annexes'])->name('annexes');

// Ruta dinámica para otras páginas de contenido
Route::get('/content/{slug}', [ContentPageController::class, 'show'])->name('content.page');

// Rutas de idioma
Route::get('/language/{locale}', [LanguageController::class, 'change'])->name('language.change');

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';