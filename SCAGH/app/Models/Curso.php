<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Curso extends Model
{
    protected $table = 'curso';

    protected $fillable = [
        'carrera_id',
        'ciclo_id',
        'nombre',
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
            $model->usuario_md = Auth::id() ?? null;
        });
    }

    // Relaciones
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    public function ciclo()
    {
        return $this->belongsTo(Catalogo::class, 'ciclo_id');
    }

    public function getEstadoTextoAttribute()
    {
        return $this->estado == 1 ? 'ACTIVO' : 'INACTIVO';
    }

    public function scopeSearch($query, $busqueda, $carrera_id, $facultad_id, $ciclo_id)
    {
        if ($busqueda) {
            $query->where(
                fn($q) =>
                $q->where('nombre', 'like', "%$busqueda%")
                    ->orWhere('codigo', 'like', "%$busqueda%")
            );
        }

        if ($carrera_id) {
            $query->where('carrera_id', $carrera_id);
        }

        if ($facultad_id) {
            $query->whereRelation('carrera', 'facultad_id', $facultad_id);
        }
        if ($ciclo_id) {
            $query->where('ciclo_id', $ciclo_id);
        }

        return $query;
    }
}
