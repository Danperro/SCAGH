<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Acceso extends Model
{
    protected $table = 'acceso';

    protected $fillable = [
        'rol_id',
        'menu_id',
        'permiso_id',
        'nombre',
        'estado',
        'fecha_cr',
        'usuario_cr',
        'fecha_md',
        'usuario_md',
    ];

    protected static function boot()
    {
        parent::boot();

        // Cuando se crea un registro
        static::creating(function ($model) {
            $model->fecha_cr = now();
            $model->usuario_cr = Auth::id() ?? null;
        });

        // Cuando se actualiza un registro
        static::updating(function ($model) {
            $model->fecha_md = now();
            $model->usuario_md = Auth::id() ?? null;
        });
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function permiso()
    {
        return $this->belongsTo(Permiso::class);
    }
}
