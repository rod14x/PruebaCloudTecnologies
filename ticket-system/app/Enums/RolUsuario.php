<?php

namespace App\Enums;

enum RolUsuario: int
{
    case EMPLEADO = 0;
    case ADMINISTRADOR = 1;

    public function nombre(): string
    {
        return match($this) {
            self::EMPLEADO => 'Empleado',
            self::ADMINISTRADOR => 'Administrador',
        };
    }

    public function esAdministrador(): bool
    {
        return $this === self::ADMINISTRADOR;
    }

    public function esEmpleado(): bool
    {
        return $this === self::EMPLEADO;
    }
}
