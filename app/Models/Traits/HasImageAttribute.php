<?php

namespace App\Models\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImageAttribute
{
  /**
   * Get the field name for storing images for this model
   * Defaults to 'image' if not overridden
   * 
   * @return string
   */
  public function getImageField(): string
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
   * Get the full URL to the image
   * 
   * @param string|null $field Custom field name (optional)
   * @return string|null
   */
  public function getImageUrl(?string $field = null): ?string
  {
    $field = $field ?? $this->getImageField();
    
    if (!$this->{$field}) {
      return null;
    }
    
    // Usar asset() en lugar de URL directa del storage
    return asset('storage/' . $this->{$field});
  }
  
  /**
   * Determines if model has an image
   * 
   * @param string|null $field Custom field name (optional)
   * @return bool
   */
  public function hasImage(?string $field = null): bool
  {
    $field = $field ?? $this->getImageField();
    
    return !empty($this->{$field}) && Storage::disk('public')->exists($this->{$field});
  }
  
  /**
   * Store an image for this model
   * 
   * @param UploadedFile $file
   * @param string|null $field Custom field name (optional)
   * @return string Path to the stored image
   */
  public function storeImage(UploadedFile $file, ?string $field = null): string
  {
    $field = $field ?? $this->getImageField();
    
    // Delete old image if exists
    $this->deleteImage($field);
    
    // Store new image
    $path = $file->store($this->getImageDirectory(), 'public');
    
    // Update model
    $this->{$field} = $path;
    $this->save();
    
    return $path;
  }
  
  /**
   * Delete the image for this model
   * 
   * @param string|null $field Custom field name (optional)
   * @return bool
   */
  public function deleteImage(?string $field = null): bool
  {
    $field = $field ?? $this->getImageField();
    
    if ($this->hasImage($field)) {
      $deleted = Storage::disk('public')->delete($this->{$field});
      
      if ($deleted) {
        $this->{$field} = null;
        $this->save();
      }
      
      return $deleted;
    }
    
    return false;
  }
}