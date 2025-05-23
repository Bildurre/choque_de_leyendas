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

      // Generate image using Browsershot
      Browsershot::html($html)
        ->windowSize(600, 850) // Card/Hero preview size
        ->deviceScaleFactor(2) // For better quality
        ->waitUntilNetworkIdle()
        ->save($fullPath);

      // Update model with preview image path
      $this->model->setPreviewImagePath($locale, $path);

      Log::info('Preview image generated for locale', [
        'locale' => $locale,
        'path' => $path
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

    // Wrap in a complete HTML document with styles
    return $this->wrapInHtmlDocument($previewHtml, $locale);
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
    // Get the compiled CSS path
    $cssPath = public_path('build/assets/app.css');
    $css = file_exists($cssPath) ? file_get_contents($cssPath) : '';

    return <<<HTML
<!DOCTYPE html>
<html lang="{$locale}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 20px;
            background: transparent;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Include compiled CSS */
        {$css}
        
        /* Ensure preview is properly sized */
        .entity-preview {
            margin: 0;
            /* Remove any box-shadow for the image */
            box-shadow: none !important;
        }
    </style>
</head>
<body>
    {$content}
</body>
</html>
HTML;
  }
}