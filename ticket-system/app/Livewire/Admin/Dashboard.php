<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\TicketService;
use App\Models\Ticket;
use App\Enums\EstadoTicket;
use App\Enums\PrioridadTicket;
use Illuminate\Support\Facades\DB;

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

        // KPI: Tickets abiertos vs cerrados
        $totalTickets = Ticket::count();
        $ticketsAbiertos = Ticket::whereIn('estado', [
            EstadoTicket::PENDIENTE->value,
            EstadoTicket::EN_PROCESO->value
        ])->count();
        $ticketsCerrados = Ticket::where('estado', EstadoTicket::RESUELTO->value)->count();

        // Estadísticas por prioridad
        $ticketsPorPrioridad = [
            'baja' => Ticket::where('prioridad', PrioridadTicket::BAJA->value)->count(),
            'media' => Ticket::where('prioridad', PrioridadTicket::MEDIA->value)->count(),
            'alta' => Ticket::where('prioridad', PrioridadTicket::ALTA->value)->count(),
        ];

        // Tickets recientes (últimos 10)
        $ticketsRecientes = Ticket::withBasicRelations()
            ->recent()
            ->limit(10)
            ->get();

        // Tickets pendientes urgentes
        $ticketsPendientesUrgentes = Ticket::pendientes()
            ->byPrioridad(PrioridadTicket::ALTA)
            ->withBasicRelations()
            ->recent()
            ->limit(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'totalTickets' => $totalTickets,
            'ticketsAbiertos' => $ticketsAbiertos,
            'ticketsCerrados' => $ticketsCerrados,
            'ticketsPorPrioridad' => $ticketsPorPrioridad,
            'ticketsRecientes' => $ticketsRecientes,
            'ticketsPendientesUrgentes' => $ticketsPendientesUrgentes,
        ])->layout('components.layouts.app', ['title' => 'Dashboard Admin']);
    }
}
