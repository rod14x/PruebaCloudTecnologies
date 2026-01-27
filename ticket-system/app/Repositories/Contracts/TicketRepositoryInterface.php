<?php

namespace App\Repositories\Contracts;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

interface TicketRepositoryInterface
{
    /**
     * Obtener todos los tickets del usuario autenticado
     */
    public function getByUser(int $userId): Collection;

    /**
     * Obtener todos los tickets (admin)
     */
    public function getAll(): Collection;

    /**
     * Obtener tickets por estado
     */
    public function getByEstado(string $estado, ?int $userId = null): Collection;

    /**
     * Obtener tickets por prioridad
     */
    public function getByPrioridad(string $prioridad, ?int $userId = null): Collection;

    /**
     * Crear un nuevo ticket
     */
    public function create(array $data): Ticket;

    /**
     * Actualizar un ticket
     */
    public function update(Ticket $ticket, array $data): bool;

    /**
     * Encontrar ticket por ID
     */
    public function findById(int $id): ?Ticket;

    /**
     * Obtener estadísticas de tickets
     */
    public function getStats(?int $userId = null): array;

    /**
     * Obtener tickets pendientes
     */
    public function getPendientes(?int $userId = null): Collection;

    /**
     * Obtener tickets en proceso
     */
    public function getEnProceso(?int $userId = null): Collection;

    /**
     * Obtener tickets resueltos
     */
    public function getResueltos(?int $userId = null): Collection;
}
