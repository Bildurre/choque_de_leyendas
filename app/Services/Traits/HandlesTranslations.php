<?php

namespace App\Services\Traits;

trait HandlesTranslations
{
  /**
   * Process translatable fields from request data
   *
   * @param array $data Input data
   * @param array $translatableFields List of translatable fields
   * @param string $defaultLocale Default locale to use if translations not provided as array
   * @return array Processed data
   */
  protected function processTranslatableFields(array $data, array $translatableFields, string $defaultLocale = null): array
  {
    $defaultLocale = $defaultLocale ?? app()->getLocale();
    $availableLocales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
    $processedData = $data;
    
    foreach ($translatableFields as $field) {
      // Skip if field is not in the input data
      if (!isset($data[$field])) {
        continue;
      }
      
      // If the field is already properly formatted as an array of translations, we're good
      if (is_array($data[$field])) {
        // Make sure we have at least the default locale
        if (!isset($data[$field][$defaultLocale]) || empty($data[$field][$defaultLocale])) {
          // If we have a plain string value, use it for the default locale
          if (isset($data["{$field}_plain"]) && !empty($data["{$field}_plain"])) {
            $processedData[$field][$defaultLocale] = $data["{$field}_plain"];
          }
        }
        continue;
      }
      
      // If we have a plain string, convert it to a translation array for the default locale
      if (is_string($data[$field]) && !empty($data[$field])) {
        $processedData[$field] = [$defaultLocale => $data[$field]];
      }
    }
    
    return $processedData;
  }
  
  /**
   * Apply translations from data to model
   *
   * @param \Illuminate\Database\Eloquent\Model $model The model to update
   * @param array $data Input data
   * @param array $translatableFields List of translatable fields
   * @return void
   */
  protected function applyTranslations($model, array $data, array $translatableFields, bool $allowEmpty = false): void
  {
    $availableLocales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
    
    foreach ($translatableFields as $field) {
      // Skip if field is not in the input data
      if (!isset($data[$field])) {
        continue;
      }
      
      // Handle array of translations
      if (is_array($data[$field])) {
        foreach ($availableLocales as $locale) {
          if (array_key_exists($locale, $data[$field])) {
            // Update if value is not empty OR if we allow empty values
            if (!empty($data[$field][$locale]) || $allowEmpty) {
              $model->setTranslation($field, $locale, $data[$field][$locale]);
            }
          }
        }
      } 
      // Handle plain string (assign to current locale)
      else if (is_string($data[$field])) {
        // Update if value is not empty OR if we allow empty values
        if (!empty($data[$field]) || $allowEmpty) {
          $model->setTranslation($field, app()->getLocale(), $data[$field]);
        }
      }
    }
  }
}