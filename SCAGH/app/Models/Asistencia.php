<?php

namespace App\Models;

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
}
