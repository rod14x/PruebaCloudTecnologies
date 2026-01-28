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
    public $ticket;
    public $nuevoComentario = '';
    public $lastUpdated = null;
    
    // Notificaciones
    public $notification = null;
    public $notificationType = 'success';

    public function mount(Ticket $ticket)
    {
        // Verificar permisos
        if (!auth()->user()->esAdministrador() && $ticket->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este ticket.');
        }
        
        $this->ticket = $ticket->load([
            'usuario',
            'categoria',
            'comentarios.usuario',
            'archivosAdjuntos',
            'historialEstados.usuario'
        ]);
        
        $this->lastUpdated = $this->ticket->updated_at;
    }
    
    public function refresh()
    {
        // Recargar el ticket desde la base de datos
        $this->ticket->refresh();
        $this->ticket->load([
            'usuario',
            'categoria',
            'comentarios.usuario',
            'archivosAdjuntos',
            'historialEstados.usuario'
        ]);
        
        // Si el ticket fue actualizado y el usuario no es admin, mostrar notificación
        $previousUpdate = $this->lastUpdated;
        if ($previousUpdate && $this->ticket->updated_at->gt($previousUpdate) && !auth()->user()->esAdministrador()) {
            $this->notification = 'El ticket ha sido actualizado por un administrador.';
            $this->notificationType = 'info';
        }
        
        $this->lastUpdated = $this->ticket->updated_at;
    }

    public function agregarComentario()
    {
        $this->validate([
            'nuevoComentario' => 'required|string|max:1000',
        ]);

        try {
            \App\Models\Comentario::create([
                'ticket_id' => $this->ticket->id,
                'usuario_id' => auth()->id(),
                'contenido' => $this->nuevoComentario,
            ]);

            $this->nuevoComentario = '';
            $this->refresh();
            
            $this->notification = 'Comentario agregado correctamente.';
            $this->notificationType = 'success';
        } catch (\Exception $e) {
            $this->notification = 'Error al agregar comentario: ' . $e->getMessage();
            $this->notificationType = 'error';
        }
    }

    #[On('ticket-updated')]
    public function refreshTicket()
    {
        $this->refresh();
        
        // Mostrar notificación al usuario de que el ticket fue actualizado
        if (!auth()->user()->esAdministrador()) {
            $this->notification = 'El ticket ha sido actualizado por un administrador.';
            $this->notificationType = 'info';
        }
    }

    public function render()
    {
        return view('livewire.tickets.ticket-show');
    }
}
