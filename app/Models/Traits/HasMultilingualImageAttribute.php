<?php

namespace App\Models\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasMultilingualImageAttribute
{
  /**
   * Get the field name for storing images for this model
   * Defaults to 'image' if not overridden
   * 
   * @return string
   */
  public function getMultilingualImageField(): string
  {
    return 'image';
  }

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  abstract public function getImageDirectory(): string;

  /**
   * Get the full URL to the image for a specific locale
   * 
   * @param string|null $locale
   * @param string|null $field Custom field name (optional)
   * @return string|null
   */
  public function getMultilingualImageUrl(?string $locale = null, ?string $field = null): ?string
  {
    $field = $field ?? $this->getMultilingualImageField();
    $locale = $locale ?? app()->getLocale();
    
    $images = $this->{$field};
    
    if (!$images) {
      return null;
    }
    
    // If images is a string (legacy single image), return it
    if (is_string($images)) {
      return asset('storage/' . $images);
    }
    
    // If images is already an array
    if (is_array($images)) {
      // First try to get the image for the requested locale
      if (isset($images[$locale]) && $images[$locale]) {
        return asset('storage/' . $images[$locale]);
      }
      
      // If not found, return the first available image
      foreach ($images as $imageLocale => $imagePath) {
        if ($imagePath) {
          return asset('storage/' . $imagePath);
        }
      }
    }
    
    return null;
  }
  
  /**
   * Determines if model has an image for a specific locale
   * 
   * @param string|null $locale
   * @param string|null $field Custom field name (optional)
   * @return bool
   */
  public function hasMultilingualImage(?string $locale = null, ?string $field = null): bool
  {
    $field = $field ?? $this->getMultilingualImageField();
    $locale = $locale ?? app()->getLocale();
    
    $images = $this->{$field};
    
    if (!$images) {
      return false;
    }
    
    // Legacy support for single string image
    if (is_string($images)) {
      return Storage::disk('public')->exists($images);
    }
    
    if (is_array($images)) {
      // Check specific locale
      if ($locale && isset($images[$locale]) && $images[$locale]) {
        return Storage::disk('public')->exists($images[$locale]);
      }
      
      // Check if any image exists
      foreach ($images as $imagePath) {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
          return true;
        }
      }
    }
    
    return false;
  }
  
  /**
   * Generate a filename based on the model's slug or name with locale suffix
   * 
   * @param UploadedFile $file Original file
   * @param string $locale
   * @return string
   */
  protected function generateMultilingualImageFilename(UploadedFile $file, string $locale): string
  {
    // Try to use the model's slug if available
    if (method_exists($this, 'getSlug')) {
      $baseFilename = $this->getSlug();
    } elseif (isset($this->slug)) {
      // If a slug attribute exists
      $baseFilename = is_array($this->slug) ? ($this->slug['es'] ?? array_values($this->slug)[0]) : $this->slug;
    } elseif (isset($this->name)) {
      // If a name attribute exists, create a slug from it
      $name = is_array($this->name) ? ($this->name['es'] ?? array_values($this->name)[0]) : $this->name;
      $baseFilename = Str::slug($name);
    } else {
      // If all above fails, use the original filename
      $baseFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $baseFilename = Str::slug($baseFilename);
    }
    
    // Get the file extension
    $extension = $file->getClientOriginalExtension();
    
    // Create the base filename with locale
    $filename = $baseFilename . '-' . $locale . '.' . $extension;
    
    // Check if a file with that name already exists and add a numeric suffix if needed
    $count = 1;
    $newFilename = $filename;
    
    while (Storage::disk('public')->exists($this->getImageDirectory() . '/' . $newFilename)) {
      $newFilename = $baseFilename . '-' . $locale . '-' . $count . '.' . $extension;
      $count++;
    }
    
    return $newFilename;
  }
  
  /**
   * Store an image for this model for a specific locale
   * 
   * @param UploadedFile $file
   * @param string $locale
   * @param string|null $field Custom field name (optional)
   * @return string Path to the stored image
   */
  public function storeMultilingualImage(UploadedFile $file, string $locale, ?string $field = null): string
  {
    $field = $field ?? $this->getMultilingualImageField();
    
    // Get current images array
    $images = $this->{$field} ?? [];
    
    // Handle legacy string format
    if (is_string($images)) {
      $images = [];
    }
    
    // Delete old image for this locale if exists
    if (isset($images[$locale]) && $images[$locale]) {
      Storage::disk('public')->delete($images[$locale]);
    }
    
    // Generate a filename based on the model's slug or name with locale
    $filename = $this->generateMultilingualImageFilename($file, $locale);
    
    // Store the image with the generated filename
    $path = Storage::disk('public')->putFileAs(
      $this->getImageDirectory(),
      $file,
      $filename
    );
    
    // Update images array
    $images[$locale] = $path;
    
    // Update model
    $this->{$field} = $images;
    $this->save();
    
    return $path;
  }
  
  /**
   * Delete the image for a specific locale
   * 
   * @param string $locale
   * @param string|null $field Custom field name (optional)
   * @return bool
   */
  public function deleteMultilingualImage(string $locale, ?string $field = null): bool
  {
    $field = $field ?? $this->getMultilingualImageField();
    
    $images = $this->{$field};
    
    if (!$images || !is_array($images) || !isset($images[$locale])) {
      return false;
    }
    
    // Delete the file from storage
    $deleted = Storage::disk('public')->delete($images[$locale]);
    
    if ($deleted) {
      // Remove from array
      unset($images[$locale]);
      
      // Update model
      $this->{$field} = empty($images) ? null : $images;
      $this->save();
    }
    
    return $deleted;
  }
  
  /**
   * Delete all images for this field
   * 
   * @param string|null $field Custom field name (optional)
   * @return bool
   */
  public function deleteAllMultilingualImages(?string $field = null): bool
  {
    $field = $field ?? $this->getMultilingualImageField();
    
    $images = $this->{$field};
    
    if (!$images) {
      return true;
    }
    
    $allDeleted = true;
    
    // Handle legacy string format
    if (is_string($images)) {
      $allDeleted = Storage::disk('public')->delete($images);
    } elseif (is_array($images)) {
      // Delete all images
      foreach ($images as $imagePath) {
        if ($imagePath && !Storage::disk('public')->delete($imagePath)) {
          $allDeleted = false;
        }
      }
    }
    
    if ($allDeleted) {
      $this->{$field} = null;
      $this->save();
    }
    
    return $allDeleted;
  }
  
  /**
   * Boot method to handle model deletion
   */
  protected static function bootHasMultilingualImageAttribute()
  {
    static::deleting(function ($model) {
      // Get all fields that might contain multilingual images
      $reflection = new \ReflectionClass($model);
      $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
      
      // Check if model has a method to get multilingual image fields
      $imageFields = [];
      if (method_exists($model, 'getMultilingualImageFields')) {
        $imageFields = $model->getMultilingualImageFields();
      } else {
        // Default to the main multilingual image field
        $imageFields[] = $model->getMultilingualImageField();
      }
      
      // Delete all images for each field
      foreach ($imageFields as $field) {
        $model->deleteAllMultilingualImages($field);
      }
    });
  }
  
  /**
   * Get all available locales for a field
   * 
   * @param string|null $field Custom field name (optional)
   * @return array
   */
  public function getAvailableImageLocales(?string $field = null): array
  {
    $field = $field ?? $this->getMultilingualImageField();
    
    $images = $this->{$field};
    
    if (!$images || !is_array($images)) {
      return [];
    }
    
    return array_keys(array_filter($images));
  }
}