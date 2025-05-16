<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

if (!function_exists('localized_route')) {
  /**
   * Get localized route URL for a named route with proper slug translation.
   *
   * @param string $routeName
   * @param mixed $parameters
   * @param string|null $locale
   * @param bool $absolute
   * @return string
   */
  function localized_route(string $routeName, $parameters = null, ?string $locale = null, bool $absolute = true): string
  {
      $locale = $locale ?? app()->getLocale();
      
      // Handle models that implement LocalizedUrlRoutable interface
      if ($parameters instanceof \Illuminate\Database\Eloquent\Model && 
          $parameters instanceof \Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable) {
          
          // Get the localized slug for the new locale
          $localizedKey = $parameters->getLocalizedRouteKey($locale);
          
          // Generate the route with the localized slug
          return LaravelLocalization::getLocalizedURL(
              $locale,
              route($routeName, $localizedKey, false)
          );
      }
      
      // Normal route generation with localization
      return LaravelLocalization::getLocalizedURL(
          $locale,
          route($routeName, $parameters, $absolute)
      );
  }
}

if (!function_exists('localized_current_url')) {
  /**
   * Get the current URL localized to a specific locale with slug translation.
   *
   * @param string|null $locale
   * @return string
   */
  function localized_current_url(?string $locale = null): string
  {
      $locale = $locale ?? app()->getLocale();
      $currentRoute = Route::current();
      
      if (!$currentRoute) {
          return LaravelLocalization::getLocalizedURL($locale);
      }
      
      // Handle special case for content.page route
      if ($currentRoute->getName() === 'content.page') {
          $page = $currentRoute->parameter('page');
          
          if ($page instanceof \App\Models\Page && 
              $page instanceof \Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable) {
              // Get the localized slug for the current page
              $localizedSlug = $page->getLocalizedRouteKey($locale);
              
              // Generate the URL with the localized slug
              return LaravelLocalization::getLocalizedURL(
                  $locale,
                  url($localizedSlug)
              );
          }
      }
      
      // Handle other routes with model binding
      $parameters = $currentRoute->parameters();
      $translated = false;
      
      foreach ($parameters as $key => $value) {
          if ($value instanceof \Illuminate\Database\Eloquent\Model && 
              $value instanceof \Mcamara\LaravelLocalization\Interfaces\LocalizedUrlRoutable) {
              
              // Replace the parameter with the localized key
              $parameters[$key] = $value->getLocalizedRouteKey($locale);
              $translated = true;
          }
      }
      
      if ($translated) {
          // Generate a new route with translated parameters
          $url = route($currentRoute->getName(), $parameters, false);
          return LaravelLocalization::getLocalizedURL($locale, $url);
      }
      
      // Default behavior
      return LaravelLocalization::getLocalizedURL($locale);
  }
}