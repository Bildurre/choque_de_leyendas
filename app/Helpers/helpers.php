<?php

use App\Services\LocalizedRoutingService;
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
        // Si no se encuentra en LaravelLocalization, intentar con config
        if (empty($locales) || !isset($locales[$locale])) {
            $locales = config('laravellocalization.supportedLocales', []);
            return $locales[$locale]['name'] ?? strtoupper($locale);
        }
        return $locales[$locale]['name'] ?? strtoupper($locale);
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
        
        if (method_exists($model, 'getLocalizedRouteKey')) {
            return LaravelLocalization::getLocalizedURL(
                $locale,
                route($routeName, $model->getLocalizedRouteKey($locale), false)
            );
        }
        
        return LaravelLocalization::getLocalizedURL(
            $locale,
            route($routeName, $model, false)
        );
    }
}

if (!function_exists('localized_current_url')) {
    /**
     * Get the current URL localized to a specific locale.
     *
     * @param string|null $locale
     * @return string
     */
    function localized_current_url(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return app(LocalizedRoutingService::class)->getCurrentUrlInLocale($locale);
    }
}