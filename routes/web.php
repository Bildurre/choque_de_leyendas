<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\CardController;
use App\Http\Controllers\Public\HeroController;
use App\Http\Controllers\Content\PageController;
use App\Http\Controllers\Public\FactionController;
use App\Http\Controllers\Public\FactionDeckController;
use App\Http\Controllers\Public\PrintCollectionController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Grupo de rutas con localización
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localizationRedirect', 'localeSessionRedirect']
], function () {
    // Página de inicio
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    
    // // Redirección de dashboard a home
    // Route::get('/dashboard', function () {
    //     return redirect()->route('welcome');
    // });
    
    // Rutas específicas de contenido público
    Route::get(LaravelLocalization::transRoute('routes.factions'), [FactionController::class, 'index'])
        ->name('public.factions.index');
    Route::get(LaravelLocalization::transRoute('routes.faction_show'), [FactionController::class, 'show'])
        ->name('public.factions.show');
    Route::get(LaravelLocalization::transRoute('routes.heroes'), [HeroController::class, 'index'])
        ->name('public.heroes.index');
    Route::get(LaravelLocalization::transRoute('routes.hero_show'), [HeroController::class, 'show'])
        ->name('public.heroes.show');
    Route::get(LaravelLocalization::transRoute('routes.cards'), [CardController::class, 'index'])
        ->name('public.cards.index');
    Route::get(LaravelLocalization::transRoute('routes.card_show'), [CardController::class, 'show'])
        ->name('public.cards.show');
    Route::get(LaravelLocalization::transRoute('routes.faction_deck_show'), [FactionDeckController::class, 'show'])
        ->name('public.faction-decks.show');


    // Print Collection routes
    Route::prefix('print-collection')->name('public.print-collection.')->group(function () {
        Route::get('/', [PrintCollectionController::class, 'index'])->name('index');
        Route::post('/add', [PrintCollectionController::class, 'add'])->name('add');
        Route::post('/update', [PrintCollectionController::class, 'update'])->name('update');
        Route::post('/remove', [PrintCollectionController::class, 'remove'])->name('remove');
        Route::post('/clear', [PrintCollectionController::class, 'clear'])->name('clear');
        Route::get('/generate-pdf', [PrintCollectionController::class, 'generatePdf'])->name('generate-pdf');
        Route::get('/faction/{faction}/pdf', [PrintCollectionController::class, 'generateFactionPdf'])->name('faction-pdf');
        Route::get('/deck/{deck}/pdf', [PrintCollectionController::class, 'generateDeckPdf'])->name('deck-pdf');
    });
});


// Development route for icon and color showcase
if (app()->environment('local')) {
    Route::get('/icons', function () {
        // Get icons from the icon component file
        $iconComponentPath = resource_path('views/components/icon.blade.php');
        $icons = [];
        
        if (File::exists($iconComponentPath)) {
            $content = File::get($iconComponentPath);
            
            // Extract all case statements from the switch
            preg_match_all("/@case\('([^']+)'\)/", $content, $matches);
            
            if (!empty($matches[1])) {
                $icons = $matches[1];
                // Remove 'default' if it exists
                $icons = array_filter($icons, function($icon) {
                    return $icon !== 'default';
                });
                sort($icons);
            }
        }
        
        // Define block colors
        $blockColors = [
            'red' => '#f15959',
            'orange' => '#f1753a',
            'lime' => '#88b033',
            'green' => '#29ab5f',
            'teal' => '#31a28e',
            'cyan' => '#3999cd',
            'blue' => '#408cfd',
            'purple' => '#7a64c8',
            'magenta' => '#a75da5',
            'accent-primary' => 'Random (Primary)',
            'accent-secondary' => 'Random (Secondary)',
            'accent-tertiary' => 'Random (Tertiary)',
            'theme-card' => 'Theme Card BG',
            'theme-border' => 'Theme Border',
            'theme-header' => 'Theme Header BG',
        ];
        
        return response('
            <!DOCTYPE html>
            <html>
            <head>
                <title>Icons & Colors</title>
                <style>
                    :root {
                        /* Dark theme colors */
                        --color-bg-dark: #1A1A1A;
                        --color-bg-dark-secondary: #262626;
                        --color-bg-dark-border: #333333;
                        
                        /* Light theme colors */
                        --color-bg-light: #E8E8E8;
                        --color-bg-light-secondary: #DADADA;
                        --color-bg-light-border: #CCCCCC;
                        
                        /* Text colors */
                        --color-text-light: #dddddd;
                        --color-text-dark: #272727;
                        --color-text-muted: #929292;
                        
                        /* Theme colors with transparency - Dark theme */
                        --color-bg-transparent-light: rgba(26, 26, 26, 0.2);
                        --color-bg-transparent-semi: rgba(26, 26, 26, 0.5);
                        --color-bg-transparent-hard: rgba(26, 26, 26, 0.8);
                        
                        /* Additional theme backgrounds for blocks */
                        --color-theme-card-bg: rgba(38, 38, 38, 0.5);
                        --color-theme-border-bg: rgba(51, 51, 51, 0.5);
                        --color-theme-header-bg: rgba(38, 38, 38, 0.5);
                        
                        /* Block colors with opacity */
                        --color-block-bg-red: rgba(241, 89, 89, 0.2);
                        --color-block-bg-orange: rgba(241, 117, 58, 0.2);
                        --color-block-bg-lime: rgba(136, 176, 51, 0.2);
                        --color-block-bg-green: rgba(41, 171, 95, 0.2);
                        --color-block-bg-teal: rgba(49, 162, 142, 0.2);
                        --color-block-bg-cyan: rgba(57, 153, 205, 0.2);
                        --color-block-bg-blue: rgba(64, 140, 253, 0.2);
                        --color-block-bg-purple: rgba(122, 100, 200, 0.2);
                        --color-block-bg-magenta: rgba(167, 93, 165, 0.2);
                        
                        /* Random accent colors - will be set by JS but we need defaults */
                        --random-accent-color-bg-light: rgba(64, 140, 253, 0.2);
                        --random-accent-color-secondary-bg-light: rgba(241, 89, 89, 0.2);
                        --random-accent-color-tertiary-bg-light: rgba(41, 171, 95, 0.2);
                    }
                    
                    body {
                        font-family: system-ui, -apple-system, sans-serif;
                        padding: 2rem;
                        background: #f5f5f5;
                        margin: 0;
                    }
                    
                    body.dark {
                        background: var(--color-bg-dark);
                        color: var(--color-text-light);
                    }
                    
                    body.dark {
                        /* Override theme colors for dark mode */
                        --color-bg-transparent-light: rgba(26, 26, 26, 0.2);
                        --color-bg-transparent-semi: rgba(26, 26, 26, 0.5);
                        --color-bg-transparent-hard: rgba(26, 26, 26, 0.8);
                        --color-theme-card-bg: rgba(38, 38, 38, 0.5);
                        --color-theme-border-bg: rgba(51, 51, 51, 0.5);
                        --color-theme-header-bg: rgba(38, 38, 38, 0.5);
                    }
                    
                    body.light {
                        background: var(--color-bg-light);
                        color: var(--color-text-dark);
                    }
                    
                    body.light {
                        /* Override theme colors for light mode */
                        --color-bg-transparent-light: rgba(232, 232, 232, 0.2);
                        --color-bg-transparent-semi: rgba(232, 232, 232, 0.5);
                        --color-bg-transparent-hard: rgba(232, 232, 232, 0.8);
                        --color-theme-card-bg: rgba(218, 218, 218, 0.5);
                        --color-theme-border-bg: rgba(204, 204, 204, 0.5);
                        --color-theme-header-bg: rgba(218, 218, 218, 0.5);
                    }
                    
                    h1, h2 {
                        text-align: center;
                        color: #333;
                        margin-bottom: 2rem;
                    }
                    
                    body.dark h1,
                    body.dark h2 {
                        color: var(--color-text-light);
                    }
                    
                    body.light h1,
                    body.light h2 {
                        color: var(--color-text-dark);
                    }
                    
                    /* Theme switcher */
                    .theme-switcher {
                        text-align: center;
                        margin-bottom: 3rem;
                    }
                    
                    .theme-btn {
                        padding: 0.5rem 1rem;
                        margin: 0 0.5rem;
                        border: 1px solid #ddd;
                        background: white;
                        color: #333;
                        border-radius: 0.25rem;
                        cursor: pointer;
                        font-size: 0.875rem;
                    }
                    
                    body.dark .theme-btn {
                        background: var(--color-bg-dark-secondary);
                        border-color: var(--color-bg-dark-border);
                        color: var(--color-text-light);
                    }
                    
                    body.light .theme-btn {
                        background: var(--color-bg-light-secondary);
                        border-color: var(--color-bg-light-border);
                        color: var(--color-text-dark);
                    }
                    
                    .theme-btn.active {
                        background: #408cfd;
                        color: white;
                        border-color: #408cfd;
                    }
                    
                    /* Icons section */
                    .grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                        gap: 1rem;
                        max-width: 1200px;
                        margin: 0 auto;
                    }
                    
                    .icon-card {
                        background: white;
                        border: 1px solid #ddd;
                        border-radius: 0.5rem;
                        padding: 1rem;
                        text-align: center;
                    }
                    
                    body.dark .icon-card {
                        background: var(--color-bg-dark-secondary);
                        border-color: var(--color-bg-dark-border);
                    }
                    
                    body.light .icon-card {
                        background: var(--color-bg-light-secondary);
                        border-color: var(--color-bg-light-border);
                    }
                    
                    .icon-preview {
                        height: 48px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-bottom: 0.5rem;
                    }
                    
                    .icon-preview svg {
                        width: 32px;
                        height: 32px;
                    }
                    
                    body.dark .icon-preview svg {
                        fill: none;
                    }
                    
                    body.light .icon-preview svg {
                        fill: none;
                    }
                    
                    .icon-name {
                        font-size: 0.875rem;
                        color: #666;
                    }
                    
                    body.dark .icon-name {
                        color: var(--color-text-muted);
                    }
                    
                    body.light .icon-name {
                        color: #666;
                    }
                    
                    /* Colors section */
                    .colors-section {
                        margin-top: 4rem;
                        max-width: 1200px;
                        margin-left: auto;
                        margin-right: auto;
                    }
                    
                    .colors-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                        gap: 1rem;
                    }
                    
                    .color-card {
                        border-radius: 0.5rem;
                        overflow: hidden;
                        border: 1px solid #ddd;
                    }
                    
                    body.dark .color-card {
                        border-color: var(--color-bg-dark-border);
                    }
                    
                    body.light .color-card {
                        border-color: var(--color-bg-light-border);
                    }
                    
                    .color-preview {
                        height: 100px;
                        position: relative;
                    }
                    
                    .block {
                        width: 100%;
                        height: 100%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 0.875rem;
                    }
                    
                    .block[data-background="none"] {
                        background-color: transparent;
                        border: 2px dashed currentColor;
                        opacity: 0.3;
                    }
                    
                    .block[data-background="red"] {
                        background-color: var(--color-block-bg-red);
                    }
                    
                    .block[data-background="orange"] {
                        background-color: var(--color-block-bg-orange);
                    }
                    
                    .block[data-background="lime"] {
                        background-color: var(--color-block-bg-lime);
                    }
                    
                    .block[data-background="green"] {
                        background-color: var(--color-block-bg-green);
                    }
                    
                    .block[data-background="teal"] {
                        background-color: var(--color-block-bg-teal);
                    }
                    
                    .block[data-background="cyan"] {
                        background-color: var(--color-block-bg-cyan);
                    }
                    
                    .block[data-background="blue"] {
                        background-color: var(--color-block-bg-blue);
                    }
                    
                    .block[data-background="purple"] {
                        background-color: var(--color-block-bg-purple);
                    }
                    
                    .block[data-background="magenta"] {
                        background-color: var(--color-block-bg-magenta);
                    }
                    
                    .block[data-background="accent-primary"] {
                        background-color: var(--random-accent-color-bg-light);
                    }
                    
                    .block[data-background="accent-secondary"] {
                        background-color: var(--random-accent-color-secondary-bg-light);
                    }
                    
                    .block[data-background="accent-tertiary"] {
                        background-color: var(--random-accent-color-tertiary-bg-light);
                    }
                    
                    .block[data-background="theme-card"] {
                        background-color: var(--color-theme-card-bg);
                    }
                    
                    .block[data-background="theme-border"] {
                        background-color: var(--color-theme-border-bg);
                    }
                    
                    .block[data-background="theme-header"] {
                        background-color: var(--color-theme-header-bg);
                    }
                    
                    .color-info {
                        padding: 0.75rem;
                        background: white;
                        border-top: 1px solid #eee;
                    }
                    
                    body.dark .color-info {
                        background: var(--color-bg-dark-secondary);
                        border-top-color: var(--color-bg-dark-border);
                    }
                    
                    body.light .color-info {
                        background: var(--color-bg-light-secondary);
                        border-top-color: var(--color-bg-light-border);
                    }
                    
                    .color-name {
                        font-weight: 600;
                        margin-bottom: 0.25rem;
                        text-transform: capitalize;
                    }
                    
                    .color-code {
                        font-size: 0.75rem;
                        color: #666;
                        font-family: monospace;
                    }
                    
                    body.dark .color-code {
                        color: var(--color-text-muted);
                    }
                    
                    /* Divider */
                    .divider {
                        height: 1px;
                        background: #ddd;
                        margin: 3rem auto;
                        max-width: 1200px;
                    }
                    
                    body.dark .divider {
                        background: var(--color-bg-dark-border);
                    }
                    
                    body.light .divider {
                        background: var(--color-bg-light-border);
                    }
                </style>
            </head>
            <body class="dark">
                <h1>Icons & Colors</h1>
                
                <div class="theme-switcher">
                    <button class="theme-btn active" onclick="setTheme(\'dark\')">Dark Theme</button>
                    <button class="theme-btn" onclick="setTheme(\'light\')">Light Theme</button>
                </div>
                
                <h2>Icons (' . count($icons) . ')</h2>
                
                <div class="grid">
                    ' . collect($icons)->map(function ($icon) {
                        return '
                        <div class="icon-card">
                            <div class="icon-preview">
                                ' . Blade::render('<x-icon name="' . $icon . '" />') . '
                            </div>
                            <div class="icon-name">' . $icon . '</div>
                        </div>';
                    })->implode('') . '
                </div>
                
                <div class="divider"></div>
                
                <div class="colors-section">
                    <h2>Block Colors</h2>
                    
                    <div class="colors-grid">
                        <div class="color-card">
                            <div class="color-preview">
                                <div class="block" data-background="none">
                                    Transparent
                                </div>
                            </div>
                            <div class="color-info">
                                <div class="color-name">none</div>
                                <div class="color-code">transparent</div>
                            </div>
                        </div>
                        ' . collect($blockColors)->map(function ($hex, $name) {
                            $isAccent = str_starts_with($name, 'accent-');
                            $isTheme = str_starts_with($name, 'theme-');
                            if ($isAccent || $isTheme) {
                                $colorCode = $hex;
                            } else {
                                $colorCode = $hex . ' @ 20%';
                            }
                            return '
                            <div class="color-card">
                                <div class="color-preview">
                                    <div class="block" data-background="' . $name . '">
                                        Sample Text
                                    </div>
                                </div>
                                <div class="color-info">
                                    <div class="color-name">' . $name . '</div>
                                    <div class="color-code">' . $colorCode . '</div>
                                </div>
                            </div>';
                        })->implode('') . '
                    </div>
                </div>
                
                <script>
                    function setTheme(theme) {
                        document.body.className = theme;
                        document.querySelectorAll(".theme-btn").forEach(btn => {
                            btn.classList.remove("active");
                        });
                        event.target.classList.add("active");
                    }
                </script>
            </body>
            </html>
        ');
    });
}

Route::post('/set-locale', [App\Http\Controllers\LocaleController::class, 'setLocale'])->name('set-locale');
    
require __DIR__.'/auth.php';
  
require __DIR__.'/admin.php';

Route::group([
  'prefix' => LaravelLocalization::setLocale(),
  'middleware' => ['localize', 'localizationRedirect', 'localeSessionRedirect']
], function () {
  Route::get(LaravelLocalization::transRoute('routes.page_show'), [PageController::class, 'show'])
    ->name('content.page')
    ->where('page', '[a-z0-9\-]+'); // Restricción para admitir solo slugs válidos
});


