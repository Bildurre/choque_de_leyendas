<?php

namespace App\Helpers;

class LocaleHelper
{
  /**
   * Get readable name for a locale
   *
   * @param string $locale
   * @return string
   */
  public static function getLocaleName(string $locale): string
  {
    $names = [
      'es' => 'español',
      'en' => 'inglés',
      'ca' => 'catalán',
      'fr' => 'francés',
      'de' => 'alemán',
      'it' => 'italiano',
      'pt' => 'portugués',
      // Añade más según necesites
    ];
    
    return $names[$locale] ?? $locale;
  }

  /**
   * Get available locales from config
   *
   * @return array
   */
  public static function getAvailableLocales(): array
  {
    return config('app.available_locales', ['es']);
  }
}