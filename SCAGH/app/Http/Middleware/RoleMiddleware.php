<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $rolesUsuario = Auth::user()
            ->roles()
            ->pluck('rol.id') // 👈 CLAVE: evitar id ambiguo
            ->map(fn($id) => (string) $id)
            ->toArray();

        if (empty(array_intersect($rolesUsuario, $roles))) {
            abort(403, 'No tienes permisos para acceder a este módulo.');
        }

        return $next($request);
    }
}
