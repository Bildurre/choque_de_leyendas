<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LocalizedRoutingService
{
    /**
     * Get localized URL for current route.
     *
     * @param string $locale
     * @return string
     */
    public function getCurrentUrlInLocale(string $locale): string
    {
        $route = request()->route();
        
        if (!$route) {
            return LaravelLocalization::getLocalizedURL($locale);
        }
        
        $parameters = $route->parameters();
        $routeName = $route->getName();
        
        // Si no hay parámetros o no hay ruta nombrada, usa el método estándar
        if (empty($parameters) || !$routeName) {
            return LaravelLocalization::getLocalizedURL($locale);
        }
        
        // Verificar si algún parámetro es un modelo con getLocalizedRouteKey
        $needsCustomHandling = false;
        $localizedParameters = [];
        
        foreach ($parameters as $key => $value) {
            if ($value instanceof Model && method_exists($value, 'getLocalizedRouteKey')) {
                $needsCustomHandling = true;
                $localizedParameters[$key] = $value->getLocalizedRouteKey($locale);
            } else {
                $localizedParameters[$key] = $value;
            }
        }
        
        // Si no necesita manejo personalizado, usa el método estándar
        if (!$needsCustomHandling) {
            return LaravelLocalization::getLocalizedURL($locale);
        }
        
        // Construir URL con parámetros localizados
        $url = route($routeName, $localizedParameters, false);
        
        // Aplicar prefijo de localización
        return LaravelLocalization::getLocalizedURL($locale, $url);
    }
}