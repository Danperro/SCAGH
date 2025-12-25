<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carrera';  // Nombre de la tabla

    // Definir los campos que pueden ser llenados (mass assignment)
    protected $fillable = [
        'nombre',
        'facultad_id',
        'ciclos_total',
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

    public function facultad()
    {
        return $this->belongsTo(Catalogo::class, 'facultad_id');
    }


    public function curso()
    {
        return $this->hasMany(Curso::class);
    }

    public function scopeSearch($query, $valor = null, $facultadId = null, $estado = null)
    {
        return $query
            ->when($estado !== '' && $estado !== null, function ($q) use ($estado) {
                $q->where('estado', (int)$estado);
            })
            ->when($facultadId !== '' && $facultadId !== null, function ($q) use ($facultadId) {
                $q->where('facultad_id', (int)$facultadId);
            })
            ->when($valor !== '' && $valor !== null, function ($q) use ($valor) {
                $valor = trim($valor);
                $q->where('nombre', 'like', "%{$valor}%");
            });
    }
}
