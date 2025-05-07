<?php

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

if (!function_exists('locale_name')) {
    /**
     * Get the locale name from its code.
     *
     * @param string $locale
     * @return string
     */
    function locale_name(string $locale): string
    {
        $locales = LaravelLocalization::getSupportedLocales();
        return $locales[$locale]['name'] ?? $locale;
    }
}

if (!function_exists('localized_route')) {
    /**
     * Get localized route URL for a model.
     *
     * @param string $routeName
     * @param mixed $model
     * @param string|null $locale
     * @return string
     */
    function localized_route(string $routeName, $model, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return LaravelLocalization::getLocalizedURL(
            $locale,
            route($routeName, $model->getLocalizedRouteKey($locale), false)
        );
    }
}

if (!function_exists('locale_name')) {
  /**
   * Get the locale name from its code.
   *
   * @param string $locale
   * @return string
   */
  function locale_name(string $locale): string
  {
      $locales = config('laravellocalization.supportedLocales', []);
      return $locales[$locale]['name'] ?? strtoupper($locale);
  }
}