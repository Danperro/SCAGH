<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Persona extends Model
{
    protected $table = 'persona';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'dni',
        'telefono',
        'correo',
        'fecha_nacimiento',
        'estado',
        'fecha_cr',
        'usuario_cr',
        'fecha_md',
        'usuario_md',
    ];

    // Auditoría automática
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->fecha_cr = now();
            $model->usuario_cr = Auth::id() ?? null;
        });

        static::updating(function ($model) {
            $model->fecha_md = now();
            $model->usuario_md = Auth::id() ?? null;
        });
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class);
    }

    public function docente()
    {
        return $this->hasOne(Docente::class);
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }
}
