<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;

class UsuarioPersonaProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) return null;

        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (str_contains($key, 'password')) continue;


            $query->where($key, $value);
        }

        return $query->first();
    }
}
