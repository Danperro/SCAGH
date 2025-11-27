<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Horario extends Model
{
    protected $table = 'horario';

    protected $fillable = [
        'nombre',
        'laboratorio_id',
        'semestre_id',
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

    public function laboratorio()
    {
        return $this->belongsTo(Catalogo::class, 'laboratorio_id');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    // Obtener el área desde el laboratorio
    public function area()
    {
        return $this->laboratorio->padre ?? null;
    }
}
