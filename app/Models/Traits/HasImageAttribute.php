<?php

namespace App\Models\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    return 'icon';
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
   * Generate a filename based on the model's slug or name
   * 
   * @param UploadedFile $file Original file
   * @return string
   */
  protected function generateImageFilename(UploadedFile $file): string
  {
    // Intenta usar el slug del modelo si está disponible
    if (method_exists($this, 'getSlug')) {
      $baseFilename = $this->getSlug();
    } elseif (isset($this->slug)) {
      // Si existe un atributo slug
      $baseFilename = is_array($this->slug) ? ($this->slug['es'] ?? array_values($this->slug)[0]) : $this->slug;
    } elseif (isset($this->name)) {
      // Si existe un atributo name, crea un slug a partir de él
      $name = is_array($this->name) ? ($this->name['es'] ?? array_values($this->name)[0]) : $this->name;
      $baseFilename = Str::slug($name);
    } else {
      // Si todo lo anterior falla, usa el nombre original del archivo
      $baseFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $baseFilename = Str::slug($baseFilename);
    }
    
    // Obtener la extensión del archivo original
    $extension = $file->getClientOriginalExtension();
    
    // Crear el nombre de archivo base
    $filename = $baseFilename . '.' . $extension;
    
    // Verificar si ya existe un archivo con ese nombre y, si es así, añadir un sufijo numérico
    $count = 1;
    $newFilename = $filename;
    
    while (Storage::disk('public')->exists($this->getImageDirectory() . '/' . $newFilename)) {
      $newFilename = $baseFilename . '-' . $count . '.' . $extension;
      $count++;
    }
    
    return $newFilename;
  }
  
  /**
   * Store an image for this model using the slug as filename
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
    
    // Generate a filename based on the model's slug or name
    $filename = $this->generateImageFilename($file);
    
    // Store the image with the generated filename
    $path = Storage::disk('public')->putFileAs(
      $this->getImageDirectory(),
      $file,
      $filename
    );
    
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