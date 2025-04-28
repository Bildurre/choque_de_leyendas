<?php

use App\Helpers\ColorHelper;
use App\Helpers\LocaleHelper;
use App\Helpers\TextHelper;

/**
 * Color manipulation helpers
 */
if (!function_exists('hex_to_rgb')) {
  /**
   * Convert hex color to RGB format
   *
   * @param string $hex
   * @return string
   */
  function hex_to_rgb($hex)
  {
    return ColorHelper::hexToRgb($hex);
  }
}

/**
 * Localization helpers
 */
if (!function_exists('locale_name')) {
  /**
   * Get readable name for a locale
   *
   * @param string $locale
   * @return string
   */
  function locale_name(string $locale): string
  {
    return LocaleHelper::getLocaleName($locale);
  }
}

if (!function_exists('available_locales')) {
  /**
   * Get available locales from config
   *
   * @return array
   */
  function available_locales(): array
  {
    return LocaleHelper::getAvailableLocales();
  }
}