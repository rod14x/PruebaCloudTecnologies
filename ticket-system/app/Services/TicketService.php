<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\ArchivoAdjunto;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Factories\TicketFactory;
use App\Enums\EstadoTicket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
        private TicketFactory $ticketFactory
    ) {}

    /**
     * Obtener instancia del repositorio para casos específicos
     */
    public function getRepository(): TicketRepositoryInterface
    {
        return $this->ticketRepository;
    }

    /**
     * Crear un ticket con evidencia adjunta usando Factory Pattern
     */
    public function createTicketWithEvidence(
        int $usuarioId,
        string $titulo,
        string $descripcion,
        int $categoriaId,
        string $prioridad,
        ?UploadedFile $evidencia = null
    ): Ticket {
        $attributes = [
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'categoria_id' => $categoriaId,
            'prioridad' => $prioridad,
            'usuario_id' => $usuarioId,
        ];

        // Usar Factory Pattern para crear el ticket
        if ($evidencia) {
            return $this->ticketFactory->createWithEvidence($attributes, $evidencia);
        }

        return $this->ticketFactory->createBasic($attributes);
    }

    /**
     * Cambiar el estado de un ticket
     */
    public function changeEstado(Ticket $ticket, EstadoTicket $nuevoEstado, ?string $comentario = null): bool
    {
        return DB::transaction(function () use ($ticket, $nuevoEstado, $comentario) {
            $estadoAnterior = $ticket->estado;

            // Actualizar el ticket
            $updated = $this->ticketRepository->update($ticket, [
                'estado' => $nuevoEstado->value,
            ]);

            if ($updated) {
                // Registrar en historial
                $ticket->historialEstados()->create([
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo' => $nuevoEstado->value,
                    'usuario_id' => auth()->id(),
                    'comentario' => $comentario,
                ]);
                
                // Disparar evento de broadcasting
                $ticket->refresh();
                event(new \App\Events\TicketUpdated($ticket));
            }

            return $updated;
        });
    }

    /**
     * Cambiar estado por ID del ticket
     */
    public function changeEstadoById(int $ticketId, EstadoTicket $nuevoEstado, ?string $comentario = null): bool
    {
        $ticket = $this->ticketRepository->findById($ticketId);
        
        if (!$ticket) {
            throw new \Exception('Ticket no encontrado');
        }

        return $this->changeEstado($ticket, $nuevoEstado, $comentario);
    }

    /**
     * Obtener tickets del usuario
     */
    public function getUserTickets(int $userId)
    {
        return $this->ticketRepository->getByUser($userId);
    }

    /**
     * Obtener todos los tickets (admin)
     */
    public function getAllTickets()
    {
        return $this->ticketRepository->getAll();
    }

    /**
     * Obtener query builder de todos los tickets con relaciones (para paginación)
     */
    public function getAllTicketsWithRelations()
    {
        return $this->ticketRepository->getAllWithRelations();
    }

    /**
     * Obtener estadísticas
     */
    public function getStats(?int $userId = null): array
    {
        return $this->ticketRepository->getStats($userId);
    }

    /**
     * Encontrar ticket por ID
     */
    public function findTicket(int $id): ?Ticket
    {
        return $this->ticketRepository->findById($id);
    }

    /**
     * Eliminar archivo adjunto
     */
    public function deleteAttachment(ArchivoAdjunto $archivo): bool
    {
        // Eliminar archivo del storage
        if (Storage::disk('public')->exists($archivo->ruta_archivo)) {
            Storage::disk('public')->delete($archivo->ruta_archivo);
        }

        // Eliminar registro
        return $archivo->delete();
    }
}
