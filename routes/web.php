<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Content\ContentPageController;
use App\Http\Controllers\Content\ContentSectionController;
use App\Http\Controllers\Content\ContentBlockController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/language/{locale}', [LanguageController::class, 'change'])->name('language.change');

// Content pages
Route::get('/content', [ContentPageController::class, 'index'])->name('content.index');
Route::get('/content/{slug}', [ContentPageController::class, 'show'])->name('content.show');
Route::get('/page/{slug}', [ContentPageController::class, 'localizedRedirect'])->name('content.localized');

// Content sections & blocks
Route::get('/content/{pageSlug}/section/{sectionAnchor}', [ContentSectionController::class, 'show'])
  ->name('content.section.show');
Route::get('/api/content/{pageSlug}/sections', [ContentSectionController::class, 'getSectionsByPage'])
  ->name('api.content.sections');
Route::get('/content/{pageSlug}/section/{sectionAnchor}/block/{blockId}', [ContentBlockController::class, 'show'])
  ->name('content.block.show');
Route::get('/api/content/block/{blockId}/data', [ContentBlockController::class, 'getModelListData'])
  ->name('api.content.block.data');

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';