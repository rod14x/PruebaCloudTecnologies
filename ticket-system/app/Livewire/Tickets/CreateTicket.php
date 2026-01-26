<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ticket;
use App\Models\Categoria;
use App\Models\ArchivoAdjunto;
use App\Enums\PrioridadTicket;
use App\Enums\EstadoTicket;
use Illuminate\Support\Facades\Storage;

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
        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'prioridad' => 'required|in:0,1,2',
            'evidencia' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:2048', // 2MB máximo
        ];
    }

    public function messages()
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'prioridad.required' => 'Debe seleccionar una prioridad.',
            'prioridad.in' => 'La prioridad seleccionada no es válida.',
            'evidencia.file' => 'El archivo debe ser válido.',
            'evidencia.mimes' => 'Solo se permiten imágenes (JPEG, JPG, PNG, GIF).',
            'evidencia.max' => 'La imagen no debe superar los 2MB.',
        ];
    }

    public function createTicket()
    {
        $this->validate();

        // Crear el ticket
        $ticket = Ticket::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'categoria_id' => $this->categoria_id,
            'prioridad' => (int) $this->prioridad,
            'estado' => EstadoTicket::PENDIENTE,
            'usuario_id' => auth()->id(),
        ]);

        // Guardar evidencia si existe
        if ($this->evidencia) {
            // Validar MIME type real del archivo (primeros bytes)
            $mimeType = $this->evidencia->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            
            if (!in_array($mimeType, $allowedMimes)) {
                session()->flash('error', 'Archivo inválido. Solo se permiten imágenes reales (JPEG, PNG, GIF).');
                return;
            }

            $filename = 'ticket_' . $ticket->id . '_' . time() . '.' . $this->evidencia->getClientOriginalExtension();
            $path = $this->evidencia->storeAs('evidencias', $filename, 'public');

            ArchivoAdjunto::create([
                'ticket_id' => $ticket->id,
                'nombre_archivo' => $this->evidencia->getClientOriginalName(),
                'ruta_archivo' => $path,
                'tamano' => $this->evidencia->getSize(),
            ]);
        }

        session()->flash('message', 'Ticket creado exitosamente.');
        
        return redirect()->route('tickets.index');
    }

    public function render()
    {
        $categorias = Categoria::all();
        
        return view('livewire.tickets.create-ticket', [
            'categorias' => $categorias,
        ])->layout('components.layouts.app', ['title' => 'Crear Ticket']);
    }
}
