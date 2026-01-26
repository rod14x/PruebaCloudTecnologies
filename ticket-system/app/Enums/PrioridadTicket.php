<?php

namespace App\Enums;

enum PrioridadTicket: int
{
    case BAJA = 0;
    case MEDIA = 1;
    case ALTA = 2;

    public function nombre(): string
    {
        return match($this) {
            self::BAJA => 'Baja',
            self::MEDIA => 'Media',
            self::ALTA => 'Alta',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::BAJA => 'green',
            self::MEDIA => 'yellow',
            self::ALTA => 'red',
        };
    }
}
