<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GeneratePreviewImage implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * The model instance
   *
   * @var Model
   */
  protected $model;

  /**
   * The locales to generate previews for
   *
   * @var array
   */
  protected $locales;

  /**
   * Create a new job instance.
   *
   * @param Model $model
   * @param array|null $locales If null, generates for all supported locales
   */
  public function __construct(Model $model, ?array $locales = null)
  {
    $this->model = $model;
    $this->locales = $locales ?? array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    try {
      Log::info('Generating preview images for all locales', [
        'model' => get_class($this->model),
        'id' => $this->model->id,
        'locales' => $this->locales
      ]);

      // Store current locale to restore it later
      $originalLocale = app()->getLocale();

      // Generate preview for each locale
      foreach ($this->locales as $locale) {
        $this->generatePreviewForLocale($locale);
      }

      // Restore original locale
      app()->setLocale($originalLocale);

      Log::info('All preview images generated successfully', [
        'model' => get_class($this->model),
        'id' => $this->model->id
      ]);

    } catch (\Exception $e) {
      Log::error('Failed to generate preview images', [
        'error' => $e->getMessage(),
        'model' => get_class($this->model),
        'id' => $this->model->id
      ]);

      throw $e;
    }
  }

  /**
   * Generate preview for a specific locale
   *
   * @param string $locale
   * @return void
   */
  protected function generatePreviewForLocale(string $locale): void
  {
    try {
      Log::info('Generating preview image for locale', [
        'locale' => $locale
      ]);
      
      // Get existing preview image path BEFORE generating new one
      $existingImages = $this->model->getAllPreviewImages();
      $oldImagePath = $existingImages[$locale] ?? null;
      
      Log::info('Current preview status', [
        'locale' => $locale,
        'old_path' => $oldImagePath,
        'old_exists' => $oldImagePath ? Storage::disk('public')->exists($oldImagePath) : false
      ]);

      // Set the locale for this generation
      app()->setLocale($locale);

      // Generate the HTML for the preview
      $html = $this->generatePreviewHtml($locale);

      // Generate filename and directory path
      $filename = $this->model->generatePreviewImageFilename($locale);
      $directory = $this->model->getPreviewImageDirectoryForLocale($locale);
      $path = $directory . '/' . $filename;

      // Ensure directory exists
      Storage::disk('public')->makeDirectory($directory);

      // Full path for Browsershot
      $fullPath = Storage::disk('public')->path($path);

      // Generate image using Browsershot with optimized settings
      Browsershot::html($html)
        ->windowSize(333, 477) // Reduced size: ~66% of original (was 333x477) (220, 315)
        ->deviceScaleFactor(1) // Reduced from 3 to 1 for smaller file size
        ->waitUntilNetworkIdle()
        ->delay(300) // Reduced delay for faster processing
        ->save($fullPath);

      // Now delete the old image if it exists and is different from the new one
      if ($oldImagePath && $oldImagePath !== $path && Storage::disk('public')->exists($oldImagePath)) {
        Storage::disk('public')->delete($oldImagePath);
        Log::info('Deleted old preview image', [
          'locale' => $locale,
          'old_path' => $oldImagePath,
          'new_path' => $path
        ]);
      }

      // Update model with new preview image path
      $this->model->setPreviewImagePath($locale, $path);

      Log::info('Preview image generated for locale', [
        'locale' => $locale,
        'path' => $path,
        'size' => filesize($fullPath) / 1024 . ' KB' // Log file size
      ]);

    } catch (\Exception $e) {
      Log::error('Failed to generate preview image for locale', [
        'error' => $e->getMessage(),
        'locale' => $locale
      ]);

      throw $e;
    }
  }

  /**
   * Generate the HTML for the preview
   *
   * @param string $locale
   * @return string
   */
  protected function generatePreviewHtml(string $locale): string
  {
    // Load all relationships needed for the preview
    if ($this->model instanceof \App\Models\Hero) {
      $this->model->load([
        'faction',
        'heroClass',
        'heroRace',
        'heroClass.heroSuperclass',
        'heroAbilities',
        'heroAbilities.attackRange',
        'heroAbilities.attackSubtype',
      ]);
      
      $viewName = 'components.previews.hero';
      $viewData = ['hero' => $this->model];
    } else if ($this->model instanceof \App\Models\Card) {
      $this->model->load([
        'faction',
        'cardType',
        'equipmentType',
        'attackRange',
        'attackSubtype',
        'heroAbility',
        'heroAbility.attackRange',
        'heroAbility.attackSubtype',
      ]);
      
      $viewName = 'components.previews.card';
      $viewData = ['card' => $this->model];
    } else {
      throw new \Exception('Model type not supported for preview generation');
    }

    // Generate the preview HTML
    $previewHtml = view($viewName, $viewData)->render();
    
    // Convert image URLs to base64
    $previewHtml = $this->convertImagesToBase64($previewHtml);

    // Wrap in a complete HTML document with styles
    return $this->wrapInHtmlDocument($previewHtml, $locale);
  }
  
  /**
   * Convert image URLs to base64 data URIs
   *
   * @param string $html
   * @return string
   */
  protected function convertImagesToBase64(string $html): string
  {
    // Pattern to match img src attributes
    $pattern = '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i';
    
    return preg_replace_callback($pattern, function($matches) {
      $fullTag = $matches[0];
      $src = $matches[1];
      
      try {
        // Skip if already base64
        if (strpos($src, 'data:') === 0) {
          return $fullTag;
        }
        
        // Handle different URL formats
        $imagePath = null;
        
        // Handle dice images from WYSIWYG (they come as relative paths)
        if (strpos($src, 'dice-') !== false && strpos($src, '.svg') !== false) {
          // Extract just the filename
          $filename = basename($src);
          $imagePath = storage_path('app/public/images/dices/' . $filename);
        } elseif (strpos($src, 'storage/') === 0) {
          // URL like "storage/images/..."
          $imagePath = storage_path('app/public/' . substr($src, 8));
        } elseif (strpos($src, '/storage/') === 0) {
          // URL like "/storage/images/..."
          $imagePath = storage_path('app/public/' . substr($src, 9));
        } elseif (strpos($src, 'http') === 0) {
          // Full URL - extract the storage path
          $storagePath = parse_url($src, PHP_URL_PATH);
          if (strpos($storagePath, '/storage/') === 0) {
            $imagePath = storage_path('app/public/' . substr($storagePath, 9));
          }
        }
        
        // Try common dice paths if not found
        if (!$imagePath || !file_exists($imagePath)) {
          $filename = basename($src);
          $possiblePaths = [
            storage_path('app/public/images/dices/' . $filename),
            storage_path('app/public/images/' . $filename),
            public_path('images/dices/' . $filename),
            public_path('images/' . $filename),
          ];
          
          foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
              $imagePath = $path;
              break;
            }
          }
        }
        
        if ($imagePath && file_exists($imagePath)) {
          $imageData = file_get_contents($imagePath);
          $mimeType = mime_content_type($imagePath);
          
          // Fix for SVG mime type
          if (pathinfo($imagePath, PATHINFO_EXTENSION) === 'svg') {
            $mimeType = 'image/svg+xml';
          }
          
          $base64 = base64_encode($imageData);
          $dataUri = "data:{$mimeType};base64,{$base64}";
          
          // Replace src with data URI
          return str_replace($src, $dataUri, $fullTag);
        }
        
        Log::warning('Image not found for preview', [
          'src' => $src, 
          'tried_path' => $imagePath,
          'exists' => $imagePath ? file_exists($imagePath) : false
        ]);
        return $fullTag;
        
      } catch (\Exception $e) {
        Log::error('Error converting image to base64', [
          'src' => $src,
          'error' => $e->getMessage()
        ]);
        return $fullTag;
      }
    }, $html);
  }

  /**
   * Wrap the preview HTML in a complete document with styles
   *
   * @param string $content
   * @param string $locale
   * @return string
   */
  protected function wrapInHtmlDocument(string $content, string $locale): string
  {
    // Try multiple possible paths for the CSS file
    $possiblePaths = [
      public_path('build/assets/style-*.css'), // Vite may add hash to filename
      public_path('build/assets/style.css'),
      public_path('css/style.css'), // Fallback path
    ];
    
    $css = '';
    foreach ($possiblePaths as $pattern) {
      $files = glob($pattern);
      if (!empty($files)) {
        $cssPath = $files[0]; // Take the first match
        if (file_exists($cssPath)) {
          $css = file_get_contents($cssPath);
          Log::info('CSS loaded from: ' . $cssPath);
          
          // Process CSS to convert relative paths to absolute and include fonts
          $css = $this->processCssForInline($css, dirname($cssPath));
          break;
        }
      }
    }
    
    if (empty($css)) {
      Log::warning('No CSS file found for preview generation');
    }

    // Get the faction color CSS variables if available
    $factionCss = '';
    if (isset($this->model->faction)) {
      $faction = $this->model->faction;
      $factionCss = <<<CSS
        :root {
          --faction-color: {$faction->color};
          --faction-color-rgb: {$faction->rgb_values};
          --faction-text: {($faction->text_is_dark ? '#000000' : '#ffffff')};
        }
        
        .entity-preview {
          --faction-color: {$faction->color};
          --faction-color-rgb: {$faction->rgb_values};
          --faction-text: {($faction->text_is_dark ? '#000000' : '#ffffff')};
        }
CSS;
    }

    return <<<HTML
<!DOCTYPE html>
<html lang="{$locale}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* CSS Variables and theme setup */
        :root {
          /* Default theme variables */
          --color-background: #1A1A1A;
          --color-card-bg: #262626;
          --color-card-border: #333333;
          --color-header-bg: #262626;
          --color-text-primary: #dddddd;
          --color-text-secondary: #929292;
          
          /* Game colors */
          --color-game-red: #f15959;
          --color-game-blue: #408cfd;
          --color-game-green: #29ab5f;
          
          /* Default accent color */
          --random-accent-color: #408cfd;
          --random-accent-color-hover: #6fadfe;
          --random-accent-color-dark: #195ec0;
          --random-accent-color-bg-light: rgba(64, 140, 253, 0.2);
          --random-accent-color-bg-semi: rgba(64, 140, 253, 0.5);
          --random-accent-color-bg-hard: rgba(64, 140, 253, 0.8);
          --random-accent-color-bg: rgba(64, 140, 253, 0.2);
          --random-accent-color-rgb: 64, 140, 253;
          
          /* Z-index variables */
          --z-index-base: 1;
          --z-index-preview: 5;
          --z-index-preview-top: 7;
          
          /* Border radius */
          --border-radius-sm: 0.25rem;
          --border-radius-md: 0.5rem;
          --border-radius-lg: 1rem;
        }
        
        {$factionCss}
        
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            font-size: 16px;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 88mm;
            height: 126mm;
            overflow: hidden;
        }
        
        body {
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Include compiled CSS */
        {$css}
        
        /* Specific overrides for preview generation */
        .entity-preview {
            margin: 0 !important;
            box-shadow: none !important;
            /* Original dimensions */
            width: 88mm !important;
            height: 126mm !important;
        }
        
        /* Cost display positioning fix */
        .cost-display {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .entity-preview__active .cost-display {
            display: flex;
            flex-direction: row !important;
            gap: 0;
        }
    </style>
</head>
<body>
    {$content}
</body>
</html>
HTML;
  }
  
  /**
   * Process CSS to convert relative paths and include fonts as base64
   *
   * @param string $css
   * @param string $basePath
   * @return string
   */
  protected function processCssForInline(string $css, string $basePath): string
  {
    // Convert url() references to base64
    $css = preg_replace_callback('/url\([\'"]?([^\'"\)]+)[\'"]?\)/i', function($matches) use ($basePath) {
      $url = $matches[1];
      
      // Skip if already base64
      if (strpos($url, 'data:') === 0) {
        return $matches[0];
      }
      
      // Build absolute path
      $filePath = null;
      
      if (strpos($url, '/') === 0) {
        // Absolute path from web root
        $filePath = public_path(ltrim($url, '/'));
      } else {
        // Relative path
        $filePath = $basePath . '/' . $url;
      }
      
      // Clean up the path
      $filePath = realpath($filePath);
      
      if ($filePath && file_exists($filePath)) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        // Process fonts
        if (in_array($extension, ['woff', 'woff2', 'ttf', 'otf', 'eot'])) {
          $mimeTypes = [
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'otf' => 'font/otf',
            'eot' => 'application/vnd.ms-fontobject'
          ];
          
          $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
          $fontData = file_get_contents($filePath);
          $base64 = base64_encode($fontData);
          
          return "url('data:{$mimeType};base64,{$base64}')";
        }
        
        // Process images
        if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'svg'])) {
          $imageData = file_get_contents($filePath);
          $mimeType = mime_content_type($filePath);
          
          if ($extension === 'svg') {
            $mimeType = 'image/svg+xml';
          }
          
          $base64 = base64_encode($imageData);
          return "url('data:{$mimeType};base64,{$base64}')";
        }
      }
      
      Log::warning('Asset not found in CSS', [
        'url' => $url,
        'tried_path' => $filePath,
        'exists' => $filePath ? file_exists($filePath) : false 
      ]);
      
      return $matches[0];
    }, $css);
    
    return $css;
  }
}