<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocenteCurso extends Model
{
    protected $table = 'docente_curso';

    protected $fillable = [
        'curso_id',
        'docente_id',
        'semestre_id',
        'grupo_id',
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
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Catalogo::class, 'grupo_id');
    }
}
