<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Services\TicketService;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class TicketShow extends Component
{
    public $ticketId;
    public $ticket;
    public $nuevoComentario = '';

    public function mount($ticketId, TicketService $ticketService)
    {
        $this->ticketId = $ticketId;
        $this->loadTicket($ticketService);
    }

    public function loadTicket(TicketService $ticketService)
    {
        $this->ticket = $ticketService->getRepository()
            ->findById($this->ticketId)
            ->load([
                'usuario',
                'categoria',
                'archivosAdjuntos',
                'comentarios.usuario',
                'historialEstados.usuario'
            ]);

        // Verificar permisos
        if (!auth()->user()->esAdministrador() && $this->ticket->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este ticket.');
        }
    }

    public function agregarComentario(TicketService $ticketService)
    {
        $this->validate([
            'nuevoComentario' => 'required|string|max:1000',
        ]);

        try {
            \App\Models\Comentario::create([
                'ticket_id' => $this->ticketId,
                'usuario_id' => auth()->id(),
                'contenido' => $this->nuevoComentario,
            ]);

            $this->nuevoComentario = '';
            $this->loadTicket($ticketService);
            session()->flash('success', 'Comentario agregado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al agregar comentario: ' . $e->getMessage());
        }
    }

    #[On('ticket-updated')]
    public function refreshTicket(TicketService $ticketService)
    {
        $this->loadTicket($ticketService);
    }

    public function render()
    {
        return view('livewire.tickets.ticket-show');
    }
}
