<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Docente extends Model
{
    protected $table = 'docente';

    protected $fillable = [
        'persona_id',
        'especialidad_id',
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

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function especialidad()
    {
        return $this->belongsTo(Catalogo::class, 'especialidad_id');
    }
}
