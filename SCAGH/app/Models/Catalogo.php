<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $table = 'catalogo';

    protected $fillable = [
        'grupo',
        'nombre',
        'estado',
        'padre_id',
        'fecha_cr',
        'usuario_cr',
        'fecha_md',
        'usuario_md',
    ];
    protected static function boot()
    {
        parent::boot();

        // Cuando se crea un registro
        static::creating(function ($model) {
            $model->fecha_cr = now();
            $model->usuario_cr = Auth::id() ?? null;
        });

        // Cuando se actualiza un registro
        static::updating(function ($model) {
            $model->fecha_md = now();
            $model->usuario_md = Auth::id() ?? null;
        });
    }

    // Relaciones automáticas sin especificar FK
    public function padre()
    {
        return $this->belongsTo(Catalogo::class);
        // Laravel buscará padre_id automáticamente
    }

    public function hijo()
    {
        return $this->hasMany(Catalogo::class);
        // Laravel buscará padre_id automáticamente
    }

    // Scopes para filtrar por tipo de catálogo
    public function scopeFacultades($query)
    {
        return $query->where('grupo', 'FACULTAD');
    }

    public function scopeCarreras($query)
    {
        return $query->where('grupo', 'CARRERA');
    }

    public function scopeAreas($query)
    {
        return $query->where('grupo', 'AREA');
    }

    public function scopeSemanas($query)
    {
        return $query->where('grupo', 'SEMANA');
    }

    public function scopeMenus($query)
    {
        return $query->where('grupo', 'MENU');
    }

    public function scopePermisos($query)
    {
        return $query->where('grupo', 'PERMISO');
    }

    public function scopeTiposAsistencia($query)
    {
        return $query->where('grupo', 'TIPO_ASISTENCIA');
    }
}
