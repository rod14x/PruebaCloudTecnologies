<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\TicketService;

/**
 * Componente Livewire refactorizado usando Service Layer y Repository
 * Las queries están abstraídas en el TicketService
 */
class MyTickets extends Component
{
    #[On('ticket-updated')]
    public function refreshTickets()
    {
        // Este método se ejecutará cuando se actualice un ticket
        // Livewire recargará automáticamente el componente
    }

    public function render(TicketService $ticketService)
    {
        // Usar Service Layer para obtener los tickets
        $tickets = $ticketService->getUserTickets(auth()->id());
        
        // Obtener estadísticas usando el Repository
        $stats = $ticketService->getStats(auth()->id());

        return view('livewire.tickets.my-tickets', [
            'tickets' => $tickets,
            'stats' => $stats,
        ])->layout('components.layouts.app', ['title' => 'Mis Tickets']);
    }
}
