<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivoAdjunto extends Model
{
    protected $table = 'archivos_adjuntos';
    
    protected $fillable = [
        'ticket_id',
        'nombre_archivo',
        'ruta_archivo',
        'tamano',
    ];

    protected $casts = [
        'tamano' => 'integer',
    ];

    // Relaciones
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
