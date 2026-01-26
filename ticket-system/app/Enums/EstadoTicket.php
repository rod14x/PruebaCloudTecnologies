<?php

namespace App\Enums;

enum EstadoTicket: int
{
    case PENDIENTE = 0;
    case EN_PROCESO = 1;
    case RESUELTO = 2;

    public function nombre(): string
    {
        return match($this) {
            self::PENDIENTE => 'Pendiente',
            self::EN_PROCESO => 'En Proceso',
            self::RESUELTO => 'Resuelto',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDIENTE => 'gray',
            self::EN_PROCESO => 'blue',
            self::RESUELTO => 'green',
        };
    }
}
