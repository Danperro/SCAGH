<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Estudiante extends Model
{
    protected $table = 'estudiante';

    protected $fillable = [
        'persona_id',
        'carrera_id',
        'codigo',
        'estado',
        'fecha_cr',
        'usuario_cr',
        'fecha_md',
        'usuario_md',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->fecha_cr = now();
            $model->usuario_cr = Auth::id() ?? null;
        });

        static::updating(function ($model) {
            $model->fecha_md = now();
            $model->usuario_md =  Auth::id() ?? null;
        });
    }

    public function getEstadoTextoAttribute()
    {
        return $this->estado == 1 ? 'ACTIVO' : 'INACTIVO';
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function scopeSearch($query, $valor, $carrera_id, $estado)
    {
        return $query
            ->when(
                $estado !== '' && $estado !== null,
                fn($q) =>
                $q->where('estado', $estado)
            )
            ->when(
                $carrera_id !== '' && $carrera_id !== null,
                fn($q) =>
                $q->where('carrera_id', $carrera_id)
            )
            ->when(
                $valor !== '' && $valor !== null,
                fn($q) =>
                $q->where(function ($sub) use ($valor) {
                    $sub->whereHas('persona', function ($p) use ($valor) {
                        $p->where('nombre', 'like', "%{$valor}%")
                            ->orWhere('apellido_paterno', 'like', "%{$valor}%")
                            ->orWhere('apellido_materno', 'like', "%{$valor}%")
                            ->orWhere('dni', 'like', "%{$valor}%");
                    })
                        ->orWhere('codigo', 'like', "%{$valor}%");
                })
            );
    }
}
