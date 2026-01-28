<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Categoria;
use App\Enums\PrioridadTicket;
use App\Actions\Tickets\CreateTicketAction;
use App\Http\Requests\CreateTicketRequest;

/**
 * Componente Livewire refactorizado usando Action Pattern
 * La lógica de negocio está delegada a CreateTicketAction
 */
class CreateTicket extends Component
{
    use WithFileUploads;

    public $titulo = '';
    public $descripcion = '';
    public $categoria_id = '';
    public $prioridad = '';
    public $evidencia;

    public function rules()
    {
        // Reutilizamos las reglas del FormRequest
        return (new CreateTicketRequest())->rules();
    }

    public function messages()
    {
        // Reutilizamos los mensajes del FormRequest
        return (new CreateTicketRequest())->messages();
    }

    public function createTicket(CreateTicketAction $action)
    {
        $this->validate();

        try {
            // Delegar la creación al Action Pattern
            $ticket = $action->execute(
                data: [
                    'usuario_id' => auth()->id(),
                    'titulo' => $this->titulo,
                    'descripcion' => $this->descripcion,
                    'categoria_id' => $this->categoria_id,
                    'prioridad' => (int) $this->prioridad,
                ],
                evidencia: $this->evidencia
            );

            session()->flash('message', 'Ticket creado exitosamente.');
            
            return redirect()->route('tickets.index');

        } catch (\InvalidArgumentException $e) {
            session()->flash('error', $e->getMessage());
            return;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el ticket. Intente nuevamente.');
            return;
        }
    }

    public function render()
    {
        $categorias = Categoria::all();
        
        return view('livewire.tickets.create-ticket', [
            'categorias' => $categorias,
        ])->layout('components.layouts.app', ['title' => 'Crear Ticket']);
    }
}
