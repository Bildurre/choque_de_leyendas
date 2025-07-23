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
        // Si no hay usuario autenticado, devolver 404
        if (!Auth::user()) {
            abort(404);
        }
        
        // Si hay usuario pero no es admin, redirigir al login con mensaje
        if (!Auth::user()->isAdmin()) {
            Auth::logout(); // Opcional: cerrar sesión si no es admin
            return redirect()->route('login')
                ->with('error', 'Acceso denegado. Solo administradores pueden acceder a esta área.');
        }

        return $next($request);
    }
}