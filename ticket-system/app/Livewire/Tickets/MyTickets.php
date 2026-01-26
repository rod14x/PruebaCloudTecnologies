<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Ticket;

class MyTickets extends Component
{
    #[On('ticket-updated')]
    public function refreshTickets()
    {
        // Este método se ejecutará cuando se actualice un ticket
        // Livewire recargará automáticamente el componente
    }

    public function render()
    {
        $tickets = Ticket::where('usuario_id', auth()->id())
            ->with(['categoria'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.tickets.my-tickets', [
            'tickets' => $tickets,
        ])->layout('components.layouts.app', ['title' => 'Mis Tickets']);
    }
}
