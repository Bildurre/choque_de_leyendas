<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
  /**
   * Store an image and return the path
   *
   * @param UploadedFile $image
   * @param string $directory
   * @return string|null
   */
  public function store(UploadedFile $image, string $directory): ?string
  {
    return $image->store($directory, 'public');
  }

  /**
   * Delete an image from storage
   *
   * @param string|null $path
   * @return bool
   */
  public function delete(?string $path): bool
  {
    if (!$path) {
      return false;
    }

    return Storage::disk('public')->delete($path);
  }

  /**
   * Update image - delete old one if exists and store new one
   *
   * @param UploadedFile|null $newImage
   * @param string|null $oldImagePath
   * @param string $directory
   * @return string|null
   */
  public function update(?UploadedFile $newImage, ?string $oldImagePath, string $directory): ?string
  {
    // If no new image and no request to remove, keep old path
    if (!$newImage) {
      return $oldImagePath;
    }

    // Remove old image if exists
    $this->delete($oldImagePath);

    // Store and return new image path
    return $this->store($newImage, $directory);
  }
}