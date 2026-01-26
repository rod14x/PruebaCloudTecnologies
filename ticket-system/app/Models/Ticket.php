<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
