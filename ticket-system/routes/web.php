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
    // Dashboard - Diferenciado por rol
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Tickets - Componentes Livewire (por implementar)
    // Route::get('/tickets', \App\Livewire\Tickets\TicketList::class)->name('tickets.index');
    // Route::get('/tickets/create', \App\Livewire\Tickets\TicketCreate::class)->name('tickets.create');
    // Route::get('/tickets/{ticket}', \App\Livewire\Tickets\TicketShow::class)->name('tickets.show');

    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// require __DIR__.'/auth.php';
