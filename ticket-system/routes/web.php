<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\AdminTickets;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Tickets\CambiarEstado;
use App\Livewire\Tickets\CreateTicket;
use App\Livewire\Tickets\MyTickets;
use App\Livewire\Tickets\TicketShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password', ResetPassword::class)->name('password.reset');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Solo para Administradores
    Route::get('/dashboard', function () {
        // Si es empleado, redirigir a mis tickets
        if (Auth::user()->esEmpleado()) {
            return redirect()->route('tickets.index');
        }
        // Si es admin, mostrar dashboard administrativo
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // Rutas de Admin
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/tickets', AdminTickets::class)->name('tickets.index');
    });

    // Tickets - Solo usuarios autenticados
    Route::get('/tickets', MyTickets::class)->name('tickets.index');
    Route::get('/tickets/create', CreateTicket::class)->name('tickets.create');
    Route::get('/tickets/{ticket}', TicketShow::class)->name('tickets.show');
    Route::get('/tickets/{ticket}/cambiar-estado', CambiarEstado::class)
        ->middleware('admin')
        ->name('tickets.cambiar-estado');

    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

