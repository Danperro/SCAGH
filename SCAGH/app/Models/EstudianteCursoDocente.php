<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EstudianteCursoDocente extends Model
{
    // Nombre de la tabla REAL en la BD
    protected $table = 'estudiante_curso_docente';

    protected $fillable = [
        'estudiante_id',
        'docente_curso_id',
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

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    // 🔹 AQUÍ VA LA RELACIÓN CORRECTA
    public function docenteCurso()
    {
        return $this->belongsTo(DocenteCurso::class, 'docente_curso_id');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
}
