<?php

namespace App\Services\Content;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\Media\ImageService;

class ContentImageService
{
  protected $imageService;
  
  /**
   * Create a new service instance.
   *
   * @param ImageService $imageService
   */
  public function __construct(ImageService $imageService)
  {
    $this->imageService = $imageService;
  }
  
  /**
   * Store a page background image
   *
   * @param UploadedFile $image
   * @return string|null
   */
  public function storePageBackground(UploadedFile $image): ?string
  {
    return $this->imageService->store($image, 'images/uploads/content-pages');
  }
  
  /**
   * Update a page background image
   *
   * @param UploadedFile|null $newImage
   * @param string|null $oldImagePath
   * @return string|null
   */
  public function updatePageBackground(?UploadedFile $newImage, ?string $oldImagePath): ?string
  {
    return $this->imageService->update($newImage, $oldImagePath, 'images/uploads/content-pages');
  }
  
  /**
   * Delete a page background image
   *
   * @param string|null $path
   * @return bool
   */
  public function deletePageBackground(?string $path): bool
  {
    return $this->imageService->delete($path);
  }
  
  /**
   * Store a block image
   *
   * @param UploadedFile $image
   * @return string|null
   */
  public function storeBlockImage(UploadedFile $image): ?string
  {
    return $this->imageService->store($image, 'images/uploads/content-blocks');
  }
  
  /**
   * Update a block image
   *
   * @param UploadedFile|null $newImage
   * @param string|null $oldImagePath
   * @return string|null
   */
  public function updateBlockImage(?UploadedFile $newImage, ?string $oldImagePath): ?string
  {
    return $this->imageService->update($newImage, $oldImagePath, 'images/uploads/content-blocks');
  }
  
  /**
   * Delete a block image
   *
   * @param string|null $path
   * @return bool
   */
  public function deleteBlockImage(?string $path): bool
  {
    return $this->imageService->delete($path);
  }
  
  /**
   * Get all images available for the content editor
   *
   * @return array
   */
  public function getAvailableContentImages(): array
  {
    $imageList = [];
    
    // Directories to scan
    $directories = [
      'images/uploads/content-pages',
      'images/uploads/content-blocks',
      'images/uploads/heroes',
      'images/uploads/cards',
      'images/uploads/faction',
      'images/icons',
      'images'
    ];
    
    foreach ($directories as $directory) {
      if (Storage::disk('public')->exists($directory)) {
        $files = Storage::disk('public')->files($directory);
        
        foreach ($files as $file) {
          // Only process images
          if ($this->isImageFile($file)) {
            $imageList[] = [
              'title' => $this->formatImageTitle($file),
              'path' => $file,
              'url' => asset('storage/' . $file)
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