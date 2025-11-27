<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\DocenteCurso;

class HorarioCursoDocente extends Model
{
    protected $table = 'horario_curso_docente';

    protected $fillable = [
        'horario_id',
        'docente_curso_id',
        'semana_id',
        'hora_inicio',
        'hora_fin',
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
    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    public function docenteCurso()
    {
        return $this->belongsTo(DocenteCurso::class);
    }

    public function semana()
    {
        return $this->belongsTo(Catalogo::class, 'semana_id');
    }
}
