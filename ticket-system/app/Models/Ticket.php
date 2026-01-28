<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\PrioridadTicket;
use App\Enums\EstadoTicket;

class Ticket extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria_id',
        'prioridad',
        'estado',
        'usuario_id',
    ];

    protected $casts = [
        'prioridad' => PrioridadTicket::class,
        'estado' => EstadoTicket::class,
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function archivosAdjuntos()
    {
        return $this->hasMany(ArchivoAdjunto::class);
    }

    public function historialEstados()
    {
        return $this->hasMany(HistorialEstado::class);
    }

    // Query Scopes - Patrón para queries reutilizables

    /**
     * Scope para incluir todas las relaciones necesarias
     */
    public function scopeWithFullRelations(Builder $query): Builder
    {
        return $query->with(['categoria', 'usuario', 'archivosAdjuntos', 'comentarios', 'historialEstados']);
    }

    /**
     * Scope para incluir relaciones básicas
     */
    public function scopeWithBasicRelations(Builder $query): Builder
    {
        return $query->with(['categoria', 'usuario']);
    }

    /**
     * Scope para filtrar por usuario
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('usuario_id', $userId);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeByEstado(Builder $query, EstadoTicket|string $estado): Builder
    {
        $estadoValue = $estado instanceof EstadoTicket ? $estado->value : $estado;
        return $query->where('estado', $estadoValue);
    }

    /**
     * Scope para filtrar por prioridad
     */
    public function scopeByPrioridad(Builder $query, PrioridadTicket|string $prioridad): Builder
    {
        $prioridadValue = $prioridad instanceof PrioridadTicket ? $prioridad->value : $prioridad;
        return $query->where('prioridad', $prioridadValue);
    }

    /**
     * Scope para filtrar por categoría
     */
    public function scopeByCategoria(Builder $query, int $categoriaId): Builder
    {
        return $query->where('categoria_id', $categoriaId);
    }

    /**
     * Scope para tickets pendientes
     */
    public function scopePendientes(Builder $query): Builder
    {
        return $query->where('estado', EstadoTicket::PENDIENTE->value);
    }

    /**
     * Scope para tickets en proceso
     */
    public function scopeEnProceso(Builder $query): Builder
    {
        return $query->where('estado', EstadoTicket::EN_PROCESO->value);
    }

    /**
     * Scope para tickets resueltos
     */
    public function scopeResueltos(Builder $query): Builder
    {
        return $query->where('estado', EstadoTicket::RESUELTO->value);
    }

    /**
     * Scope para tickets visibles (excluye resueltos con más de 1 semana)
     * Los tickets resueltos desaparecen automáticamente después de 1 semana
     */
    public function scopeVisiblesParaUsuario(Builder $query): Builder
    {
        return $query->where(function($q) {
            // Incluir todos los tickets NO resueltos
            $q->where('estado', '!=', EstadoTicket::RESUELTO->value)
              // O incluir resueltos con menos de 1 semana
              ->orWhere(function($subQuery) {
                  $subQuery->where('estado', EstadoTicket::RESUELTO->value)
                           ->where('updated_at', '>=', now()->subWeek());
              });
        });
    }

    /**
     * Verificar si el ticket debe ocultarse automáticamente
     * Se oculta si está resuelto y tiene más de 1 semana
     */
    public function debeOcultarse(): bool
    {
        $estadoValue = $this->estado instanceof EstadoTicket 
            ? $this->estado->value 
            : $this->estado;
            
        return $estadoValue === EstadoTicket::RESUELTO->value 
            && $this->updated_at->lt(now()->subWeek());
    }

    /**
     * Scope para ordenar por fecha de creación
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope para ordenar por fecha de creación (ascendente)
     */
    public function scopeOldest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'asc');
    }
}
