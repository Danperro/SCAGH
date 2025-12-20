<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Asistencia extends Model
{
    protected $table = 'asistencia';

    protected $fillable = [
        'horario_curso_docente_id',
        'fecha_registro',
        'hora_registro',
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

    public function horarioCursoDocente()
    {
        return $this->belongsTo(HorarioCursoDocente::class);
    }


    public function asistenciaEstudiantes()
    {
        return $this->hasMany(
            AsistenciaEstudiante::class,
            'asistencia_id'
        );
    }

    
    public function scopeSearch(Builder $query, array $filters)
    {
        return $query
            ->when($filters['query'] ?? null, function ($q, $search) {
                $q->whereHas('horarioCursoDocente.docenteCurso.docente.persona', function ($p) use ($search) {
                    $p->whereRaw("CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) LIKE ?", ["%{$search}%"]);
                });
            })

            ->when($filters['laboratorio_id'] ?? null, function ($q, $lab) {
                $q->whereHas('horarioCursoDocente.horario', function ($h) use ($lab) {
                    $h->where('laboratorio_id', $lab);
                });
            })

            ->when($filters['semestre_id'] ?? null, function ($q, $semestre) {
                $q->whereHas('horarioCursoDocente.docenteCurso', function ($dc) use ($semestre) {
                    $dc->where('semestre_id', $semestre);
                });
            })

            ->when($filters['facultad_id'] ?? null, function ($q, $facultad) {
                $q->whereHas('horarioCursoDocente.docenteCurso.curso.carrera', function ($c) use ($facultad) {
                    $c->where('facultad_id', $facultad);
                });
            })

            ->when($filters['carrera_id'] ?? null, function ($q, $carrera) {
                $q->whereHas('horarioCursoDocente.docenteCurso.curso', function ($c) use ($carrera) {
                    $c->where('carrera_id', $carrera);
                });
            })

            ->when($filters['curso_id'] ?? null, function ($q, $curso) {
                $q->whereHas('horarioCursoDocente.docenteCurso', function ($dc) use ($curso) {
                    $dc->where('curso_id', $curso);
                });
            })

            ->when($filters['fecha_inicio'] ?? null, function ($q, $inicio) {
                $q->whereDate('fecha_registro', '>=', $inicio);
            })

            ->when($filters['fecha_fin'] ?? null, function ($q, $fin) {
                $q->whereDate('fecha_registro', '<=', $fin);
            });
    }
}
