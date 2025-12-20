<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Semestre extends Model
{
    protected $table = 'semestre';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
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
            $model->usuario_md = Auth::id() ?? null;
        });
    }

    public function getEstadoTextoAttribute()
    {
        return $this->estado == 1 ? 'ACTIVO' : 'INACTIVO';
    }

    public function scopeSearch(
        $query,
        $busqueda = null,
        $estado = null,
        $fecha_inicio = null,
        $fecha_fin = null
    ) {
        return $query
            ->when($busqueda, function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%");
            })

            ->when($estado !== null && $estado !== '', function ($q) use ($estado) {
                $q->where('estado', $estado);
            })

            ->when($fecha_inicio, function ($q) use ($fecha_inicio) {
                $q->whereDate('fecha_inicio', '>=', $fecha_inicio);
            })

            ->when($fecha_fin, function ($q) use ($fecha_fin) {
                $q->whereDate('fecha_fin', '<=', $fecha_fin);
            });
    }
}
