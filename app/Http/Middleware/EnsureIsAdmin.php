<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsAdmin
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
    if (!Auth::user() || !Auth::user()->isAdmin()) {
      return redirect()->route('login')->with('error', Acceso denegado. Solo administradores pueden acceder a esta área.');
    }

    return $next($request);
  }
}