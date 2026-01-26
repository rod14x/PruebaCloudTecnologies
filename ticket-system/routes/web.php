<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
    Route::get('/forgot-password', \App\Livewire\Auth\ForgotPassword::class)->name('password.request');
    Route::get('/reset-password', \App\Livewire\Auth\ResetPassword::class)->name('password.reset');
});

Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Solo para Administradores
    Route::get('/dashboard', function () {
        // Si es empleado, redirigir a mis tickets
        if (auth()->user()->esEmpleado()) {
            return redirect()->route('tickets.index');
        }
        return view('dashboard');
    })->name('dashboard');

    // Tickets - Solo usuarios autenticados
    Route::get('/tickets', \App\Livewire\Tickets\MyTickets::class)->name('tickets.index');
    Route::get('/tickets/create', \App\Livewire\Tickets\CreateTicket::class)->name('tickets.create');
    // Route::get('/tickets/{ticket}', \App\Livewire\Tickets\TicketShow::class)->name('tickets.show'); // por implementar

    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// require __DIR__.'/auth.php';
