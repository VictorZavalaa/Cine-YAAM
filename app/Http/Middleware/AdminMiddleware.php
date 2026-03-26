<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Validar si el usuario tiene sesion activa
        if (!Auth::check()) {
            return redirect()->route('registro')->with('error', 'Se debe registrar e iniciar sesion');
        }

        //Validar si el usuario es admin
        if (!Auth::user()->is_admin) {
            return redirect()->route('admin-dashboard')->with('error', 'No tienes permiso para acceder a esta pagina');
        }




        return $next($request);
    }
}
