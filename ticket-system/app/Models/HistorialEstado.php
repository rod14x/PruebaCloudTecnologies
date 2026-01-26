<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\EstadoTicket;

class HistorialEstado extends Model
{
    protected $table = 'historial_estados';

    protected $fillable = [
        'ticket_id',
        'usuario_id',
        'estado_anterior',
        'estado_nuevo',
    ];

    protected $casts = [
        'estado_anterior' => EstadoTicket::class,
        'estado_nuevo' => EstadoTicket::class,
    ];

    // Relaciones
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
