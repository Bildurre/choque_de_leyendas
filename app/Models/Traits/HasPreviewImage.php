<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Storage;

trait HasPreviewImage
{
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
    
    $this->preview_image = $previewImages;
    $this->save();
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
      $this->save();
      
      return true;
    }
    
    // Delete specific locale preview image
    if (isset($previewImages[$locale])) {
      Storage::disk('public')->delete($previewImages[$locale]);
      
      unset($previewImages[$locale]);
      
      $this->preview_image = empty($previewImages) ? null : $previewImages;
      $this->save();
      
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
}