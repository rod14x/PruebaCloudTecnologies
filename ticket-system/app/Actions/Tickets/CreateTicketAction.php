<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use App\Services\TicketService;
use App\Http\Requests\CreateTicketRequest;
use Illuminate\Http\UploadedFile;

/**
 * Action Pattern - Encapsula la lógica de creación de tickets
 * Siguiendo Single Responsibility Principle
 */
class CreateTicketAction
{
    public function __construct(
        private TicketService $ticketService
    ) {}

    /**
     * Ejecutar la acción de crear un ticket
     */
    public function execute(array $data, ?UploadedFile $evidencia = null): Ticket
    {
        return $this->ticketService->createTicketWithEvidence(
            usuarioId: $data['usuario_id'],
            titulo: $data['titulo'],
            descripcion: $data['descripcion'],
            categoriaId: $data['categoria_id'],
            prioridad: $data['prioridad'],
            evidencia: $evidencia
        );
    }

    /**
     * Ejecutar desde un FormRequest
     */
    public function executeFromRequest(CreateTicketRequest $request): Ticket
    {
        return $this->execute(
            data: array_merge($request->validated(), [
                'usuario_id' => auth()->id(),
            ]),
            evidencia: $request->file('evidencia')
        );
    }
}
