<?php

namespace App\Factories;

use App\Models\Ticket;
use App\Models\ArchivoAdjunto;
use App\Enums\EstadoTicket;
use App\Enums\PrioridadTicket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Factory Pattern - Encapsula la creación de objetos Ticket
 * Ventajas:
 * - Centraliza la lógica de creación
 * - Permite diferentes estrategias de creación
 * - Facilita testing con diferentes configuraciones
 * - Desacopla la creación del uso
 */
class TicketFactory
{
    /**
     * Crear un ticket básico sin evidencia
     */
    public function createBasic(array $attributes): Ticket
    {
        return Ticket::create(array_merge([
            'estado' => EstadoTicket::PENDIENTE->value,
        ], $attributes));
    }

    /**
     * Crear un ticket con evidencia adjunta
     */
    public function createWithEvidence(array $attributes, UploadedFile $evidencia): Ticket
    {
        return DB::transaction(function () use ($attributes, $evidencia) {
            // Crear ticket
            $ticket = $this->createBasic($attributes);

            // Adjuntar evidencia
            $this->attachEvidence($ticket, $evidencia);

            return $ticket->load(['categoria', 'usuario']);
        });
    }

    /**
     * Crear un ticket urgente (prioridad alta + estado especial si se requiere)
     */
    public function createUrgent(array $attributes, ?UploadedFile $evidencia = null): Ticket
    {
        $attributes['prioridad'] = PrioridadTicket::ALTA->value;

        if ($evidencia) {
            return $this->createWithEvidence($attributes, $evidencia);
        }

        return $this->createBasic($attributes);
    }

    /**
     * Crear ticket desde array de datos validados (Form Request)
     */
    public function createFromValidatedData(array $validated, ?UploadedFile $evidencia = null): Ticket
    {
        if ($evidencia) {
            return $this->createWithEvidence($validated, $evidencia);
        }

        return $this->createBasic($validated);
    }

    /**
     * Adjuntar evidencia a un ticket existente
     */
    public function attachEvidence(Ticket $ticket, UploadedFile $file): ArchivoAdjunto
    {
        // Validar MIME type
        $this->validateMimeType($file);

        // Generar nombre único
        $extension = $file->getClientOriginalExtension();
        $filename = 'ticket_' . $ticket->id . '_' . time() . '.' . $extension;

        // Guardar archivo
        $path = $file->storeAs('evidencias', $filename, 'public');

        // Crear registro
        return ArchivoAdjunto::create([
            'ticket_id' => $ticket->id,
            'nombre_archivo' => $file->getClientOriginalName(),
            'ruta_archivo' => $path,
            'tipo_archivo' => $file->getMimeType(),
            'tamano_archivo' => $file->getSize(),
        ]);
    }

    /**
     * Validar tipo MIME del archivo
     */
    private function validateMimeType(UploadedFile $file): void
    {
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException(
                'El archivo no es una imagen válida. Solo se permiten: JPEG, JPG, PNG, GIF'
            );
        }
    }

    /**
     * Crear múltiples tickets en lote (útil para imports/migraciones)
     */
    public function createBatch(array $ticketsData): array
    {
        $tickets = [];

        DB::transaction(function () use ($ticketsData, &$tickets) {
            foreach ($ticketsData as $data) {
                $tickets[] = $this->createBasic($data);
            }
        });

        return $tickets;
    }
}
