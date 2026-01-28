<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento que se dispara cuando un ticket es actualizado
 * Broadcasting - Permite notificaciones en tiempo real a todos los usuarios
 */
class TicketUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Ticket $ticket
    ) {}
    
    /**
     * Canal donde se transmitirá el evento
     */
    public function broadcastOn(): Channel
    {
        return new Channel('ticket.' . $this->ticket->id);
    }
    
    /**
     * Datos que se enviarán al cliente
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->ticket->id,
            'estado' => $this->ticket->estado->value,
            'updated_at' => $this->ticket->updated_at->toIso8601String(),
        ];
    }
}
