<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Enums\EstadoTicket;
use App\Services\TicketService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class CambiarEstado extends Component
{
    public Ticket $ticket;
    public $nuevoEstado;
    public $contenido = '';
    
    public function mount(Ticket $ticket)
    {
        // Verificar que el usuario sea administrador
        if (!auth()->user()->esAdministrador()) {
            abort(403);
        }
        
        $this->ticket = $ticket;
        $this->nuevoEstado = $ticket->estado->value;
    }
    public function cambiarEstado(TicketService $ticketService)
    {
        $this->validate([
            'nuevoEstado' => 'required|in:0,1,2',
            'contenido' => 'nullable|string|max:500'
        ]);
        
        try {
            $ticketService->changeEstado(
                $this->ticket,
                EstadoTicket::from($this->nuevoEstado),
                auth()->id(),
                $this->contenido
            );
            
            session()->flash('message', 'Estado del ticket actualizado correctamente.');
            session()->flash('type', 'success');
            
            return redirect()->route('tickets.show', $this->ticket->id);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
            return redirect()->route('tickets.show', $this->ticket->id);
        }
    }
    
    public function render()
    {
        return view('livewire.tickets.cambiar-estado', [
            'estados' => EstadoTicket::cases()
        ]);
    }
}
