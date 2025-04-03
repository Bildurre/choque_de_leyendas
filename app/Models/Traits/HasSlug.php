<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
  /**
   * Hook into the boot process to register slug generation
   */
  public static function bootHasSlug()
  {
    static::creating(function (Model $model) {
      $model->generateSlug();
    });
    
    static::updating(function (Model $model) {
      // Only regenerate slug if source field has changed
      if ($model->isDirty($model->getSlugSourceField())) {
        $model->generateSlug();
      }
    });
  }
  
  /**
   * Generate a slug for this model
   */
  public function generateSlug(): void
  {
    $source = $this->{$this->getSlugSourceField()};
    $slug = Str::slug($source);
    
    // Check if model with same slug exists
    $count = static::where('slug', $slug)
      ->where('id', '!=', $this->id)
      ->count();
    
    // If slug exists, add a unique identifier
    if ($count > 0) {
      $slug = "{$slug}-" . uniqid();
    }
    
    $this->slug = $slug;
  }
  
  /**
   * Get the field that should be used to generate the slug
   * 
   * @return string
   */
  public function getSlugSourceField(): string
  {
    return 'name';
  }
}