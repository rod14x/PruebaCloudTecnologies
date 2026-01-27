<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use App\Services\TicketService;
use App\Enums\EstadoTicket;

/**
 * Action Pattern - Encapsula la lógica de cambio de estado
 * Incluye lógica de notificación y eventos
 */
class ChangeTicketEstadoAction
{
    public function __construct(
        private TicketService $ticketService
    ) {}

    /**
     * Ejecutar el cambio de estado
     */
    public function execute(
        Ticket $ticket,
        EstadoTicket $nuevoEstado,
        ?string $comentario = null
    ): bool {
        $success = $this->ticketService->changeEstado($ticket, $nuevoEstado, $comentario);

        if ($success) {
            // Disparar evento para actualización en tiempo real
            $this->dispatchUpdateEvent($ticket);
            
            // TODO: Enviar notificación al usuario
            // $this->notifyUser($ticket);
        }

        return $success;
    }

    /**
     * Disparar evento de actualización (Livewire)
     */
    private function dispatchUpdateEvent(Ticket $ticket): void
    {
        // Este evento será escuchado por el componente MyTickets
        event(new \App\Events\TicketUpdated($ticket));
    }

    /**
     * Notificar al usuario (por implementar)
     */
    private function notifyUser(Ticket $ticket): void
    {
        // TODO: Implementar notificación por email
        // Notification::send($ticket->usuario, new TicketEstadoChanged($ticket));
    }
}
