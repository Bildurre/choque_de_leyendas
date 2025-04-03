<?php

namespace App\Models\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImageAttribute
{
  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  abstract public function getImageDirectory(): string;

  /**
   * Get the full URL to the image
   * 
   * @return string|null
   */
  public function getImageUrlAttribute(): ?string
  {
    if (!$this->image) {
      return null;
    }
    
    return Storage::disk('public')->url($this->image);
  }
  
  /**
   * Determines if model has an image
   * 
   * @return bool
   */
  public function hasImage(): bool
  {
    return !empty($this->image) && Storage::disk('public')->exists($this->image);
  }
  
  /**
   * Store an image for this model
   * 
   * @param UploadedFile $file
   * @return string Path to the stored image
   */
  public function storeImage(UploadedFile $file): string
  {
    // Delete old image if exists
    $this->deleteImage();
    
    // Store new image
    $path = $file->store($this->getImageDirectory(), 'public');
    
    // Update model
    $this->image = $path;
    $this->save();
    
    return $path;
  }
  
  /**
   * Delete the image for this model
   * 
   * @return bool
   */
  public function deleteImage(): bool
  {
    if ($this->hasImage()) {
      $deleted = Storage::disk('public')->delete($this->image);
      
      if ($deleted) {
        $this->image = null;
        $this->save();
      }
      
      return $deleted;
    }
    
    return false;
  }
}
