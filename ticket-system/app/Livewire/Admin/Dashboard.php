<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\TicketService;
use App\Models\Ticket;

/**
 * Dashboard administrativo con estadísticas y vista general
 */
#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public function render(TicketService $ticketService)
    {
        // Estadísticas globales
        $stats = $ticketService->getStats();

        // Tickets recientes (últimos 10)
        $ticketsRecientes = Ticket::withBasicRelations()
            ->recent()
            ->limit(10)
            ->get();

        // Tickets pendientes urgentes
        $ticketsPendientesUrgentes = Ticket::pendientes()
            ->byPrioridad(\App\Enums\PrioridadTicket::ALTA)
            ->withBasicRelations()
            ->recent()
            ->limit(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'ticketsRecientes' => $ticketsRecientes,
            'ticketsPendientesUrgentes' => $ticketsPendientesUrgentes,
        ])->layout('components.layouts.app', ['title' => 'Dashboard Admin']);
    }
}
