<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;

class Usuario extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable, CanResetPassword;
    protected $table = 'usuario';

    protected $fillable = [
        'rol_id',
        'persona_id',
        'username',
        'email',
        'password',
        'estado',
        'fecha_cr',
        'usuario_cr',
        'fecha_md',
        'usuario_md',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = false;

    // Auditoría automática
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

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    // IMPORTANTE: tu correo está en persona, así que lo devolvemos desde ahí


    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function routeNotificationForMail($notification = null)
    {
        return $this->email;
    }

    public function scopeSearch($query, $valor, $rol_id, $estado)
    {
        return $query
            ->when(
                $estado !== '' && $estado !== null,
                fn($q) =>
                $q->where('estado', $estado)
            )
            ->when(
                $rol_id !== '' && $rol_id !== null,
                fn($q) =>
                $q->where('rol_id', $rol_id)
            )
            ->when(
                $valor !== '' && $valor !== null,
                fn($q) =>
                $q->where(function ($sub) use ($valor) {

                    // 🔎 Buscar por USERNAME
                    $sub->where('username', 'like', "%{$valor}%")

                        // 🔎 Buscar por PERSONA
                        ->orWhereHas('persona', function ($p) use ($valor) {
                            $p->where('nombre', 'like', "%{$valor}%")
                                ->orWhere('apellido_paterno', 'like', "%{$valor}%")
                                ->orWhere('apellido_materno', 'like', "%{$valor}%")
                                ->orWhere('dni', 'like', "%{$valor}%");
                        });
                })
            );
    }
}
