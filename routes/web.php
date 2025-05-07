<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Content\PageController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Wrap routes in LaravelLocalization group
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {
    // Homepage
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    // Profile routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Content routes - using translated route
    Route::get(LaravelLocalization::transRoute('routes.content') . '/{page}', [PageController::class, 'show'])->name('content.page');
    Route::get(LaravelLocalization::transRoute('routes.content'), [PageController::class, 'index'])->name('content.index');
});

// Include admin routes - these are separate and not localized in the same way
require __DIR__.'/admin.php';
require __DIR__.'/auth.php';