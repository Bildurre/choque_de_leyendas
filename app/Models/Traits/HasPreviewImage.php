<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Storage;
use App\Jobs\GeneratePreviewImage;

trait HasPreviewImage
{
  /**
   * Boot the trait
   */
  public static function bootHasPreviewImage()
  {
    // Solo ejecutar en entorno local
    // if (app()->environment('local')) {
      // After creating a model
      static::created(function ($model) {
        $model->dispatchPreviewGeneration();
      });
      
      // After updating a model
      static::updated(function ($model) {
        // Check if we should regenerate preview
        if ($model->shouldRegeneratePreview()) {
            $model->dispatchPreviewGeneration();
        }
      });
    // }
      
    // Before deleting, clean up preview images (esto sí en todos los entornos)
    static::deleting(function ($model) {
      $model->deletePreviewImage();
    });
  }
  
  /**
   * Get the preview image URL for a specific locale
   * 
   * @param string|null $locale
   * @return string|null
   */
  public function getPreviewImageUrl(?string $locale = null): ?string
  {
    $locale = $locale ?? app()->getLocale();
    
    $previewImages = $this->preview_image ?? [];
    
    if (!isset($previewImages[$locale])) {
      return null;
    }
    
    // Return full URL using asset() helper
    return asset('storage/' . $previewImages[$locale]);
  }
  
  /**
   * Check if preview image exists for a specific locale
   * 
   * @param string|null $locale
   * @return bool
   */
  public function hasPreviewImage(?string $locale = null): bool
  {
    $locale = $locale ?? app()->getLocale();
    
    $previewImages = $this->preview_image ?? [];
    
    if (!isset($previewImages[$locale])) {
      return false;
    }
    
    return Storage::disk('public')->exists($previewImages[$locale]);
  }
  
  /**
   * Get the directory for storing preview images
   * 
   * @return string
   */
  abstract public function getPreviewImageDirectory(): string;
  
  /**
   * Set preview image path for a specific locale
   * 
   * @param string $locale
   * @param string $path
   * @return void
   */
  public function setPreviewImagePath(string $locale, string $path): void
  {
    $previewImages = $this->preview_image ?? [];
    $previewImages[$locale] = $path;
    
    // Use saveQuietly to avoid triggering events
    $this->preview_image = $previewImages;
    $this->saveQuietly();
  }
  
  /**
   * Delete preview image for a specific locale
   * 
   * @param string|null $locale If null, deletes all preview images
   * @return bool
   */
  public function deletePreviewImage(?string $locale = null): bool
  {
    $previewImages = $this->preview_image ?? [];
    
    if ($locale === null) {
      // Delete all preview images
      foreach ($previewImages as $imagePath) {
        Storage::disk('public')->delete($imagePath);
      }
      
      $this->preview_image = null;
      $this->saveQuietly();
      
      return true;
    }
    
    // Delete specific locale preview image
    if (isset($previewImages[$locale])) {
      Storage::disk('public')->delete($previewImages[$locale]);
      
      unset($previewImages[$locale]);
      
      $this->preview_image = empty($previewImages) ? null : $previewImages;
      $this->saveQuietly();
      
      return true;
    }
    
    return false;
  }
  
  /**
   * Get all available preview images
   * 
   * @return array
   */
  public function getAllPreviewImages(): array
  {
    return $this->preview_image ?? [];
  }
  
  /**
   * Generate filename for preview image
   * 
   * @param string $locale
   * @return string
   */
  public function generatePreviewImageFilename(string $locale): string
  {
    $slug = $this->getTranslation('slug', $locale, false);
    
    if (empty($slug)) {
      // Fallback to ID if no slug available
      $slug = $this->getTable() . '-' . $this->id;
    }
    
    return $slug . '-preview.png';
  }
  
  /**
   * Get the full directory path for a specific locale
   * 
   * @param string $locale
   * @return string
   */
  public function getPreviewImageDirectoryForLocale(string $locale): string
  {
    return $this->getPreviewImageDirectory() . '/' . $locale;
  }
  
  /**
   * Dispatch the preview generation job
   * 
   * @return void
   */
  public function dispatchPreviewGeneration(): void
  {
    // Solo despachar en local
    // if (app()->environment('local')) {
      GeneratePreviewImage::dispatch($this);
    // }
  }
  
  /**
   * Determine if preview should be regenerated
   * 
   * @return bool
   */
  protected function shouldRegeneratePreview(): bool
  {
    // Don't regenerate if only is_published changed
    if ($this->wasChanged(['is_published']) && count($this->getChanges()) === 2) {
      // 2 because getChanges includes 'updated_at'
      return false;
    }
    
    // Don't regenerate if only timestamps changed
    $changes = array_keys($this->getChanges());
    $timestampOnly = count(array_diff($changes, ['created_at', 'updated_at'])) === 0;
    
    if ($timestampOnly) {
      return false;
    }
    
    // Regenerate for any other change
    return true;
  }
  
  /**
   * Force preview regeneration
   * 
   * @return void
   */
  public function regeneratePreviews(): void
  {
    $this->dispatchPreviewGeneration();
  }
}