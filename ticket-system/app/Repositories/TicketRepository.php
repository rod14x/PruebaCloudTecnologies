<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Enums\EstadoTicket;
use App\Enums\PrioridadTicket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Implementación del Repository Pattern usando Query Scopes
 * Elimina queries directas, delegando a métodos reutilizables del modelo
 */
class TicketRepository implements TicketRepositoryInterface
{
    /**
     * Obtener todos los tickets del usuario autenticado
     */
    public function getByUser(int $userId): Collection
    {
        return Ticket::forUser($userId)
            ->withBasicRelations()
            ->recent()
            ->get();
    }

    /**
     * Obtener todos los tickets (admin)
     */
    public function getAll(): Collection
    {
        return Ticket::withBasicRelations()
            ->recent()
            ->get();
    }

    /**
     * Obtener query builder de todos los tickets con relaciones (para paginación)
     */
    public function getAllWithRelations()
    {
        return Ticket::withBasicRelations();
    }

    /**
     * Obtener tickets por estado
     */
    public function getByEstado(string $estado, ?int $userId = null): Collection
    {
        $query = Ticket::byEstado($estado)
            ->withBasicRelations()
            ->recent();

        if ($userId) {
            $query->forUser($userId);
        }

        return $query->get();
    }

    /**
     * Obtener tickets por prioridad
     */
    public function getByPrioridad(string $prioridad, ?int $userId = null): Collection
    {
        $query = Ticket::byPrioridad($prioridad)
            ->withBasicRelations()
            ->recent();

        if ($userId) {
            $query->forUser($userId);
        }

        return $query->get();
    }

    /**
     * Crear un nuevo ticket
     */
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    /**
     * Actualizar un ticket
     */
    public function update(Ticket $ticket, array $data): bool
    {
        return $ticket->update($data);
    }

    /**
     * Encontrar ticket por ID
     */
    public function findById(int $id): ?Ticket
    {
        return Ticket::withFullRelations()->find($id);
    }

    /**
     * Obtener estadísticas de tickets
     * Optimizado con queries individuales en vez de clones
     */
    public function getStats(?int $userId = null): array
    {
        $baseQuery = Ticket::query();

        if ($userId) {
            $baseQuery->forUser($userId);
        }

        return [
            'total' => (clone $baseQuery)->count(),
            'pendientes' => (clone $baseQuery)->pendientes()->count(),
            'en_proceso' => (clone $baseQuery)->enProceso()->count(),
            'resueltos' => (clone $baseQuery)->resueltos()->count(),
        ];
    }

    /**
     * Obtener tickets pendientes
     */
    public function getPendientes(?int $userId = null): Collection
    {
        return $this->getByEstado(EstadoTicket::PENDIENTE->value, $userId);
    }

    /**
     * Obtener tickets en proceso
     */
    public function getEnProceso(?int $userId = null): Collection
    {
        return $this->getByEstado(EstadoTicket::EN_PROCESO->value, $userId);
    }

    /**
     * Obtener tickets resueltos
     */
    public function getResueltos(?int $userId = null): Collection
    {
        return $this->getByEstado(EstadoTicket::RESUELTO->value, $userId);
    }
}
