<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Controllers\Game\CardController;
use App\Http\Controllers\Game\HeroController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Game\CounterController;
use App\Http\Controllers\Game\FactionController;
use App\Http\Controllers\Game\CardTypeController;
use App\Http\Controllers\Game\GameModeController;
use App\Http\Controllers\Game\HeroRaceController;
use App\Http\Controllers\Game\HeroClassController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PdfExportController;
use App\Http\Controllers\Game\AttackRangeController;
use App\Http\Controllers\Game\FactionDeckController;
use App\Http\Controllers\Game\HeroAbilityController;
use App\Http\Controllers\Game\AttackSubtypeController;
use App\Http\Controllers\Game\EquipmentTypeController;
use App\Http\Controllers\Game\HeroSuperclassController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\PreviewManagementController;
use App\Http\Controllers\Game\DeckAttributesConfigurationController;
use App\Http\Controllers\Game\HeroAttributesConfigurationController;

Route::middleware([EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
  // Dashboard
  Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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

  // Hero Abilities
  Route::resource('hero-abilities', HeroAbilityController::class);
  Route::post('hero-abilities/{id}/restore', [HeroAbilityController::class, 'restore'])->name('hero-abilities.restore');
  Route::delete('hero-abilities/{id}/force-delete', [HeroAbilityController::class, 'forceDelete'])->name('hero-abilities.force-delete');

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

  // Heroes
  Route::resource('heroes', HeroController::class);
  Route::post('heroes/{id}/restore', [HeroController::class, 'restore'])->name('heroes.restore');
  Route::delete('heroes/{id}/force-delete', [HeroController::class, 'forceDelete'])->name('heroes.force-delete');
  Route::post('heroes/{hero}/toggle-published', [HeroController::class, 'togglePublished'])->name('heroes.toggle-published');

  // Factions
  Route::resource('factions', FactionController::class);
  Route::post('factions/{id}/restore', [FactionController::class, 'restore'])->name('factions.restore');
  Route::delete('factions/{id}/force-delete', [FactionController::class, 'forceDelete'])->name('factions.force-delete');
  Route::post('factions/{faction}/toggle-published', [FactionController::class, 'togglePublished'])->name('factions.toggle-published');

  // Cards
  Route::resource('cards', CardController::class);
  Route::post('cards/{id}/restore', [CardController::class, 'restore'])->name('cards.restore');
  Route::delete('cards/{id}/force-delete', [CardController::class, 'forceDelete'])->name('cards.force-delete');
  Route::post('cards/{card}/toggle-published', [CardController::class, 'togglePublished'])->name('cards.toggle-published');

  // Game Modes
  Route::resource('game-modes', GameModeController::class);
  Route::post('game-modes/{id}/restore', [GameModeController::class, 'restore'])->name('game-modes.restore');
  Route::delete('game-modes/{id}/force-delete', [GameModeController::class, 'forceDelete'])->name('game-modes.force-delete');

  // Faction Decks
  Route::resource('faction-decks', FactionDeckController::class);
  Route::post('faction-decks/{id}/restore', [FactionDeckController::class, 'restore'])->name('faction-decks.restore');
  Route::delete('faction-decks/{id}/force-delete', [FactionDeckController::class, 'forceDelete'])->name('faction-decks.force-delete');
  Route::get('faction-decks/available-items', [FactionDeckController::class, 'getAvailableItems'])->name('faction-decks.available-items');
  Route::post('faction-decks/{faction_deck}/toggle-published', [FactionDeckController::class, 'togglePublished'])->name('faction-decks.toggle-published');

  // Deck Attributes Configuration
  Route::resource('deck-attributes-configurations', DeckAttributesConfigurationController::class);
      
  // Pages
  Route::resource('pages', PageController::class);
  Route::post('pages/set-home', [PageController::class, 'setHome'])->name('pages.set-home');
  Route::post('pages/{id}/restore', [PageController::class, 'restore'])->name('pages.restore');
  Route::delete('pages/{id}/force-delete', [PageController::class, 'forceDelete'])->name('pages.force-delete');
  Route::post('pages/{page}/toggle-published', [PageController::class, 'togglePublished'])->name('pages.toggle-published');

  // Blocks
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

  // Counters
  Route::resource('counters', CounterController::class);
  Route::post('counters/{id}/restore', [CounterController::class, 'restore'])->name('counters.restore');
  Route::delete('counters/{id}/force-delete', [CounterController::class, 'forceDelete'])->name('counters.force-delete');
  Route::post('counters/{counter}/toggle-published', [CounterController::class, 'togglePublished'])->name('counters.toggle-published');

  
  // PDF Export - Rutas de visualización (siempre disponibles)
  Route::prefix('pdf-export')->name('pdf-export.')->group(function () {
    Route::get('/', [PdfExportController::class, 'index'])->name('index');
    Route::get('/view/{pdf}', [PdfExportController::class, 'view'])->name('view');
    Route::delete('/{pdf}', [PdfExportController::class, 'destroy'])->name('destroy');
    
    Route::post('/faction/{faction}/generate', [PdfExportController::class, 'generateFaction'])->name('generate-faction');
    Route::post('/deck/{deck}/generate', [PdfExportController::class, 'generateDeck'])->name('generate-deck');
    Route::post('/page/{page}/generate', [PdfExportController::class, 'generatePage'])->name('generate-page');
    // Route::post('/generate-counters-list', [PdfExportController::class, 'generateCountersList'])->name('generate-counters-list');
    Route::post('/generate-cut-out-counters', [PdfExportController::class, 'generateCutOutCounters'])->name('generate-cut-out-counters');
    Route::post('/cleanup', [PdfExportController::class, 'cleanup'])->name('cleanup');
  });

  Route::prefix('previews')->name('previews.')->group(function () {
    Route::get('/', [PreviewManagementController::class, 'index'])->name('index');
    
    // Bulk actions
    Route::post('/generate-all', [PreviewManagementController::class, 'generateAll'])->name('generate-all');
    Route::post('/regenerate-all', [PreviewManagementController::class, 'regenerateAll'])->name('regenerate-all');
    Route::post('/delete-all', [PreviewManagementController::class, 'deleteAll'])->name('delete-all');
    Route::post('/delete-all-heroes', [PreviewManagementController::class, 'deleteAllHeroes'])->name('delete-all-heroes');
    Route::post('/delete-all-cards', [PreviewManagementController::class, 'deleteAllCards'])->name('delete-all-cards');
    Route::post('/clean-orphaned', [PreviewManagementController::class, 'cleanOrphaned'])->name('clean-orphaned');

    // Individual actions
    Route::post('/individual-hero', [PreviewManagementController::class, 'individualHero'])->name('individual-hero');
    Route::post('/individual-card', [PreviewManagementController::class, 'individualCard'])->name('individual-card');

    // Faction actions
    Route::post('/faction-action', [PreviewManagementController::class, 'factionAction'])->name('faction-action');

    Route::post('/regenerate/{model}/{id}', [PreviewManagementController::class, 'regenerate'])->name('regenerate');
    Route::post('/delete/{model}/{id}', [PreviewManagementController::class, 'delete'])->name('delete');
    Route::post('/regenerate-faction/{faction}', [PreviewManagementController::class, 'regenerateFaction'])->name('regenerate-faction');
    Route::post('/delete-faction/{faction}', [PreviewManagementController::class, 'deleteFaction'])->name('delete-faction');
  });

  Route::prefix('export')->name('export.')->group(function () {
    Route::get('/', [ExportController::class, 'index'])->name('index');
    Route::post('/', [ExportController::class, 'export'])->name('create');
    Route::get('/download/{filename}', [ExportController::class, 'download'])->name('download');
    Route::delete('/delete/{filename}', [ExportController::class, 'deleteSingle'])->name('delete-single');
    Route::delete('/delete-all', [ExportController::class, 'deleteAll'])->name('delete-all');
    Route::post('/download-all', [ExportController::class, 'downloadAll'])->name('download-all');
    
      // JSON Exports
    Route::post('/cards', [ExportController::class, 'exportCards'])->name('cards');
    Route::post('/heroes', [ExportController::class, 'exportHeroes'])->name('heroes');
    Route::post('/counters', [ExportController::class, 'exportCounters'])->name('counters');
    Route::post('/classes', [ExportController::class, 'exportClasses'])->name('classes');
  });

  // Icons
  Route::view('/icons', 'admin.icons')->name('icons');
});