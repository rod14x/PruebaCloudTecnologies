<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Diferenciado por rol
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Tickets - Componentes Livewire
    Route::get('/tickets', \App\Livewire\Tickets\TicketList::class)->name('tickets.index');
    Route::get('/tickets/create', \App\Livewire\Tickets\TicketCreate::class)->name('tickets.create');
    Route::get('/tickets/{ticket}', \App\Livewire\Tickets\TicketShow::class)->name('tickets.show');

    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
