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
    static::saving(function (Model $model) {
      $model->generateSlugs();
    });
  }
  
  /**
   * Generate slugs for all available locales
   */
  public function generateSlugs(): void
  {
    // Skip if the model doesn't use HasTranslations trait
    if (!method_exists($this, 'getTranslations')) {
      $this->generateSlug();
      return;
    }
    
    $sourceField = $this->getSlugSourceField();
    $availableLocales = config('app.available_locales', ['es']);
    
    // Get current translations for the source field
    $sourceTranslations = $this->getTranslations($sourceField);
    
    foreach ($availableLocales as $locale) {
      // Skip if source field doesn't have a translation for this locale
      if (!isset($sourceTranslations[$locale]) || empty($sourceTranslations[$locale])) {
        continue;
      }
      
      $source = $sourceTranslations[$locale];
      $slug = Str::slug($source);
      
      // Check if model with same slug exists in this locale
      $slugExists = static::where('id', '!=', $this->id)
        ->whereRaw("JSON_EXTRACT(slug, '$.\"{$locale}\"') = ?", ['"'.$slug.'"'])
        ->exists();
      
      // If slug exists, add a unique identifier
      if ($slugExists) {
        $slug = "{$slug}-" . uniqid();
      }
      
      // Set the slug for this locale
      $this->setTranslation('slug', $locale, $slug);
    }
  }
  
  /**
   * Generate a slug for this model (for models without translations)
   */
  public function generateSlug(): void
  {
    $sourceField = $this->getSlugSourceField();
    $source = $this->{$sourceField};
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
    return defined('static::SLUG_SOURCE') ? static::SLUG_SOURCE : 'name';
  }
}