<?php

namespace App\Services\Media;

use Illuminate\Support\Facades\Storage;

class WysiwygImageService
{
  /**
   * Get a list of all images available for the WYSIWYG editor
   *
   * @return array
   */
  public function getAvailableImages(): array
  {
    $imageList = [];
    
    // Directorio principal de imágenes
    $directories = [
      'images/dices',
      'images/icons',
      'images'
    ];
    
    foreach ($directories as $directory) {
      if (Storage::disk('public')->exists($directory)) {
        $files = Storage::disk('public')->files($directory);
        
        foreach ($files as $file) {
          // Solo procesamos imágenes
          if ($this->isImageFile($file)) {
            $imageList[] = [
              'title' => $this->formatImageTitle($file),
              'value' => asset('storage/' . $file)
            ];
          }
        }
      }
    }
    
    return $imageList;
  }
  
  /**
   * Check if a file is an image
   *
   * @param string $file
   * @return bool
   */
  private function isImageFile(string $file): bool
  {
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg']);
  }
  
  /**
   * Format a filename for display in the image list
   *
   * @param string $file
   * @return string
   */
  private function formatImageTitle(string $file): string
  {
    $baseName = pathinfo($file, PATHINFO_FILENAME);
    $baseName = str_replace(['-', '_'], ' ', $baseName);
    return ucwords($baseName);
  }
}