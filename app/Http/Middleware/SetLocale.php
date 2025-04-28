<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    // Obtener locale de la sesión o usar el predeterminado
    $locale = Session::get('locale', config('app.locale'));
    
    // Si el locale está en la URL, actualizarlo en la sesión
    if ($request->has('locale') && in_array($request->locale, config('app.available_locales'))) {
      $locale = $request->locale;
      Session::put('locale', $locale);
    }
    
    // Establecer el locale para esta solicitud
    App::setLocale($locale);
    
    return $next($request);
  }
}