<?php

if (!function_exists('locale_name')) {
    /**
     * Get the localized name of a locale code
     *
     * @param string|null $locale The locale code (defaults to current locale)
     * @return string The localized name of the locale
     */
    function locale_name(string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $supportedLocales = config('laravellocalization.supportedLocales');
        
        return $supportedLocales[$locale]['native'] ?? $locale;
    }
}