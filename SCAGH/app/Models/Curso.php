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
        return $this->belongsTo(Catalogo::class, 'carrera_id');
    }

    public function ciclo()
    {
        return $this->belongsTo(Catalogo::class, 'ciclo_id');
    }

    
}
