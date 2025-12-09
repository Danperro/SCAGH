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

    public function getEstadoTextoAttribute()
    {
        return $this->estado == 1 ? 'ACTIVO' : 'INACTIVO';
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function especialidad()
    {
        return $this->belongsTo(Catalogo::class, 'especialidad_id');
    }

    public function scopeSearch($query, $valor, $especialidad, $estado)
    {
        return $query
            ->when(
                $estado !== '' && $estado !== null,
                fn($q) =>
                $q->where('estado', $estado)
            )
            ->when(
                $especialidad !== '' && $especialidad !== null,
                fn($q) =>
                $q->where('especialidad_id', $especialidad)
            )
            ->when(
                $valor !== '' && $valor !== null,
                fn($q) =>
                $q->whereHas('persona', function ($p) use ($valor) {
                    $p->where('nombre', 'like', "%{$valor}%")
                        ->orWhere('apellido_paterno', 'like', "%{$valor}%")
                        ->orWhere('apellido_materno', 'like', "%{$valor}%")
                        ->orWhere('dni', 'like', "%{$valor}%");
                })
            );
    }
}
