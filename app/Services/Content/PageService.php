<?php

namespace App\Services\Content;

use App\Models\Page;
use App\Services\Media\ImageService;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class PageService
{
  use HandlesTranslations;
  
  protected $imageService;
  protected $translatableFields = ['title', 'description', 'meta_title', 'meta_description', 'slug'];

  /**
   * Create a new service instance.
   */
  public function __construct(ImageService $imageService)
  {
    $this->imageService = $imageService;
  }

  /**
   * Create a new page with the provided data.
   */
  public function create(array $data): Page
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $page = new Page();
    
    // Apply translations
    $this->applyTranslations($page, $data, $this->translatableFields);
    
    // Set non-translatable fields
    $page->is_published = $data['is_published'] ?? false;
    $page->parent_id = $data['parent_id'] ?? null;
    $page->template = $data['template'] ?? 'default';
    $page->order = $data['order'] ?? 0;
    
    // Handle image uploads
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $page->image = $this->imageService->store($data['image'], $page->getImageDirectory());
    }
    
    if (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
      $page->background_image = $this->imageService->store($data['background_image'], $page->getImageDirectory());
    }
    
    $page->save();
    
    return $page;
  }

  /**
   * Update an existing page with the provided data.
   */
  public function update(Page $page, array $data): Page
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($page, $data, $this->translatableFields);
    
    // Update non-translatable fields
    $page->is_published = $data['is_published'] ?? $page->is_published;
    $page->parent_id = $data['parent_id'] ?? $page->parent_id;
    $page->template = $data['template'] ?? $page->template;
    $page->order = $data['order'] ?? $page->order;
    
    // Handle image updates
    if (isset($data['remove_image']) && $data['remove_image']) {
      $this->imageService->delete($page->image);
      $page->image = null;
    } elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $page->image = $this->imageService->update($data['image'], $page->image, $page->getImageDirectory());
    }
    
    if (isset($data['remove_background_image']) && $data['remove_background_image']) {
      $this->imageService->delete($page->background_image);
      $page->background_image = null;
    } elseif (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
      $page->background_image = $this->imageService->update(
        $data['background_image'], 
        $page->background_image, 
        $page->getImageDirectory()
      );
    }
    
    $page->save();
    
    return $page;
  }

  /**
   * Delete a page and its related resources.
   */
  public function delete(Page $page): bool
  {
    // Delete related images
    if ($page->image) {
      $this->imageService->delete($page->image);
    }
    
    if ($page->background_image) {
      $this->imageService->delete($page->background_image);
    }
    
    // Update any child pages to have no parent
    $page->children()->update(['parent_id' => null]);
    
    return $page->delete();
  }

  /**
   * Get available templates for pages.
   */
  public function getAvailableTemplates(): array
  {
    $templates = [];
    $path = resource_path('views/content/templates');
    
    // Ensure the directory exists
    if (!File::exists($path)) {
      return ['default' => 'Default'];
    }
    
    // Get all blade files in the templates directory
    $files = File::files($path);
    
    foreach ($files as $file) {
      $name = str_replace('.blade.php', '', $file->getFilename());
      $templates[$name] = ucfirst(str_replace('_', ' ', $name));
    }
    
    return $templates;
  }

  /**
   * Process translatable fields from request data
   *
   * @param array $data Input data
   * @param array $translatableFields List of translatable fields
   * @param string $defaultLocale Default locale to use if translations not provided as array
   * @return array Processed data
   */
  protected function processTranslatableFields(array $data, array $translatableFields, string $defaultLocale = null): array
  {
    $defaultLocale = $defaultLocale ?? app()->getLocale();
    $availableLocales = config('app.available_locales', ['es']);
    $processedData = $data;
    
    foreach ($translatableFields as $field) {
      // Skip if field is not in the input data
      if (!isset($data[$field])) {
        continue;
      }
      
      // If the field is already properly formatted as an array of translations, we're good
      if (is_array($data[$field])) {
        // Make sure we have at least the default locale
        if (!isset($data[$field][$defaultLocale]) || empty($data[$field][$defaultLocale])) {
          // If we have a plain string value, use it for the default locale
          if (isset($data["{$field}_plain"]) && !empty($data["{$field}_plain"])) {
            $processedData[$field][$defaultLocale] = $data["{$field}_plain"];
          }
        }
        
        // Ensure all locales have a value if not already set
        foreach ($availableLocales as $locale) {
          if (!isset($data[$field][$locale]) || (is_string($data[$field][$locale]) && trim($data[$field][$locale]) === '')) {
            // Try to fallback to default locale
            if (isset($data[$field][$defaultLocale]) && !empty($data[$field][$defaultLocale])) {
              $processedData[$field][$locale] = $data[$field][$defaultLocale];
            }
          }
        }
        
        continue;
      }
      
      // If we have a plain string, convert it to a translation array for all locales
      if (is_string($data[$field]) && !empty($data[$field])) {
        foreach ($availableLocales as $locale) {
          $processedData[$field][$locale] = $data[$field];
        }
      }
    }
    
    return $processedData;
  }
  
  /**
   * Apply translations from data to model
   *
   * @param \Illuminate\Database\Eloquent\Model $model The model to update
   * @param array $data Input data
   * @param array $translatableFields List of translatable fields
   * @return void
   */
  protected function applyTranslations($model, array $data, array $translatableFields): void
  {
    $availableLocales = config('app.available_locales', ['es']);
    
    foreach ($translatableFields as $field) {
      // Skip if field is not in the input data
      if (!isset($data[$field])) {
        continue;
      }
      
      // Handle array of translations
      if (is_array($data[$field])) {
        foreach ($availableLocales as $locale) {
          if (isset($data[$field][$locale]) && !empty($data[$field][$locale])) {
            $model->setTranslation($field, $locale, $data[$field][$locale]);
          }
        }
      } 
      // Handle plain string (assign to current locale)
      else if (is_string($data[$field]) && !empty($data[$field])) {
        $model->setTranslation($field, app()->getLocale(), $data[$field]);
      }
    }
  }
}