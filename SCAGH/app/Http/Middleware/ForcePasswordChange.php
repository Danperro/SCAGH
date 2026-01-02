<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->must_change_password) {
                // Permitir solo las rutas de cambio de contraseña y logout
                if (!$request->routeIs('password.change.form', 'password.change.update', 'logout')) {
                    return redirect()->route('password.change.form');
                }
            }
        }

        return $next($request);
    }
}
