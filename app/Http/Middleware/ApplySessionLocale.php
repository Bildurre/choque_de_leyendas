<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ApplySessionLocale
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
        // Si hay un locale en la sesión o cookie, aplicarlo
        $locale = session('locale', $request->cookie('locale'));
        
        if ($locale && array_key_exists($locale, config('laravellocalization.supportedLocales'))) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}