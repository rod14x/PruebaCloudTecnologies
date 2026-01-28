<?php
/**
 * Script de prueba para funcionalidad de auto-ocultación
 * Ejecutar con: php artisan tinker < test-ocultacion.php
 * O copiar y pegar en tinker
 */

use App\Models\Ticket;
use App\Enums\EstadoTicket;

// 1. Ver tickets resueltos actuales
echo "\n📋 TICKETS RESUELTOS:\n";
$resueltos = Ticket::where('estado', EstadoTicket::RESUELTO)->get(['id', 'titulo', 'updated_at']);
foreach($resueltos as $t) {
    $dias = $t->updated_at->diffInDays(now());
    echo "ID: {$t->id} | {$t->titulo} | Hace {$dias} días | " . ($t->debeOcultarse() ? '❌ OCULTO' : '✅ VISIBLE') . "\n";
}

// 2. Hacer que el primer ticket resuelto tenga 8 días (DEBE OCULTARSE)
$ticket = Ticket::where('estado', EstadoTicket::RESUELTO)->first();
if ($ticket) {
    $ticket->updated_at = now()->subDays(8);
    $ticket->save(['timestamps' => false]); // No actualizar automáticamente
    echo "\n✅ Ticket #{$ticket->id} actualizado a hace 8 días\n";
}

// 3. Crear ticket de prueba si no existe
if (Ticket::where('estado', EstadoTicket::RESUELTO)->count() === 0) {
    echo "\n⚠️  No hay tickets resueltos. Creando uno de prueba...\n";
    
    $ticket = Ticket::create([
        'titulo' => 'Ticket de prueba - Auto ocultación',
        'descripcion' => 'Este ticket es para probar la funcionalidad de auto-ocultación',
        'categoria_id' => 1,
        'prioridad' => 0,
        'estado' => EstadoTicket::RESUELTO->value,
        'usuario_id' => 1,
    ]);
    
    $ticket->updated_at = now()->subDays(10);
    $ticket->save(['timestamps' => false]);
    
    echo "✅ Ticket #{$ticket->id} creado hace 10 días (DEBE ESTAR OCULTO)\n";
}

// 4. Ver tickets visibles vs ocultos
echo "\n📊 RESUMEN:\n";
echo "Total resueltos: " . Ticket::resueltos()->count() . "\n";
echo "Visibles (< 7 días): " . Ticket::resueltos()->visiblesParaUsuario()->count() . "\n";
echo "Ocultos (>= 7 días): " . (Ticket::resueltos()->count() - Ticket::resueltos()->visiblesParaUsuario()->count()) . "\n";
