<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use App\Auth\UsuarioPersonaProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Auth::provider('usuario_persona', function ($app, array $config) {
            return new UsuarioPersonaProvider($app['hash'], $config['model']);
        });

        Blade::if('role', function (...$roles) {
            if (!Auth::check()) return false;

            $user = Auth::user();
            if (!$user) return false;

            $roles = collect($roles)->flatten()->map(fn($r) => (int) $r)->toArray();

            // OJO: tu rol PK es "id" en la tabla rol, y la pivote es usuario_rol.rol_id
            return $user->roles()->whereIn('rol.id', $roles)->exists();
        });
    }
}
