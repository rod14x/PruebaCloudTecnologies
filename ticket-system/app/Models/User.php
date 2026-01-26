<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\RolUsuario;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'dni',
        'telefono',
        'password',
        'rol',
        'codigo_recuperacion',
        'codigo_recuperacion_expira',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'codigo_recuperacion',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'rol' => RolUsuario::class,
            'codigo_recuperacion_expira' => 'datetime',
        ];
    }

    // Relaciones
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'usuario_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'usuario_id');
    }

    public function historialEstados()
    {
        return $this->hasMany(HistorialEstado::class, 'usuario_id');
    }

    // Helpers
    public function esAdministrador()
    {
        return $this->rol === RolUsuario::ADMINISTRADOR;
    }

    public function esEmpleado()
    {
        return $this->rol === RolUsuario::EMPLEADO;
    }
}
