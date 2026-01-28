<?php

namespace App\Livewire\Admin;

use App\Enums\EstadoTicket;
use App\Enums\PrioridadTicket;
use App\Services\TicketService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class AdminTickets extends Component
{
    use WithPagination;

    // Filtros
    public $search = '';
    public $estadoFilter = '';
    public $prioridadFilter = '';
    public $categoriaFilter = '';

    // Para cambio de estado
    public $ticketIdToChange = null;
    public $nuevoEstado = null;
    public $contenido = '';
    public $showModal = false;
    
    // Notificaciones
    public $notification = null;
    public $notificationType = 'success';

    protected $queryString = [
        'search' => ['except' => ''],
        'estadoFilter' => ['except' => ''],
        'prioridadFilter' => ['except' => ''],
        'categoriaFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEstadoFilter()
    {
        $this->resetPage();
    }

    public function updatingPrioridadFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoriaFilter()
    {
        $this->resetPage();
    }

    public function openChangeEstadoModal($ticketId, $estadoActual)
    {
        $this->ticketIdToChange = $ticketId;
        $this->nuevoEstado = $estadoActual;
        $this->contenido = '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->ticketIdToChange = null;
        $this->nuevoEstado = null;
        $this->contenido = '';
    }

    public function cambiarEstado(TicketService $ticketService)
    {
        $this->validate([
            'nuevoEstado' => 'required|integer',
            'contenido' => 'nullable|string|max:500',
        ]);

        try {
            $ticketService->changeEstadoById(
                $this->ticketIdToChange,
                EstadoTicket::from($this->nuevoEstado),
                $this->contenido
            );

            $this->dispatch('ticket-updated');
            $this->notification = 'Estado del ticket actualizado correctamente.';
            $this->notificationType = 'success';
            $this->closeModal();
        } catch (\Exception $e) {
            $this->notification = 'Error al actualizar el estado: ' . $e->getMessage();
            $this->notificationType = 'error';
        }
    }

    #[On('ticket-updated')]
    public function refreshTickets()
    {
        // Refrescar la lista
    }

    public function render(TicketService $ticketService)
    {
        $query = $ticketService->getAllTicketsWithRelations();

        // Aplicar búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('titulo', 'ILIKE', '%' . $this->search . '%')
                  ->orWhere('descripcion', 'ILIKE', '%' . $this->search . '%')
                  ->orWhereHas('usuario', function ($qu) {
                      $qu->where('name', 'ILIKE', '%' . $this->search . '%');
                  });
            });
        }

        // Aplicar filtros
        if ($this->estadoFilter !== '') {
            $query->byEstado(EstadoTicket::from((int)$this->estadoFilter));
        }

        if ($this->prioridadFilter !== '') {
            $query->byPrioridad(PrioridadTicket::from((int)$this->prioridadFilter));
        }

        if ($this->categoriaFilter) {
            $query->byCategoria((int)$this->categoriaFilter);
        }

        $tickets = $query->recent()->paginate(10);

        // Obtener categorías para el filtro
        $categorias = \App\Models\Categoria::orderBy('nombre')->get();

        return view('livewire.admin.admin-tickets', [
            'tickets' => $tickets,
            'categorias' => $categorias,
            'estados' => EstadoTicket::cases(),
            'prioridades' => PrioridadTicket::cases(),
        ]);
    }
}
