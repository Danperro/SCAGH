<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Laboratorio extends Model
{
    protected $table = 'laboratorio';
    // Definir los campos que pueden ser llenados (mass assignment)
    protected $fillable = [
        'nombre',
        'area_id',
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
    // Relación con la tabla 'catalogo' (area)
    public function area()
    {
        return $this->belongsTo(Catalogo::class, 'area_id');
    }

    // Relación con los cursos que pertenecen a esta carrera
    public function Horario()
    {
        return $this->hasMany(Horario::class);
    }
}
