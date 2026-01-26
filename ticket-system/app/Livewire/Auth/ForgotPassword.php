<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ForgotPassword extends Component
{
    public $email = '';
    public $message = '';

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function sendResetCode()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();
        
        // Generar código de 6 dígitos
        $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'codigo_recuperacion' => Hash::make($codigo),
            'codigo_recuperacion_expira' => now()->addMinutes(30),
        ]);

        // Aquí iría el envío de email (por ahora solo mostramos el código)
        // Mail::to($user->email)->send(new ResetPasswordMail($codigo));
        
        $this->message = "Código de recuperación: {$codigo} (válido por 30 minutos)";
        
        session()->flash('codigo_enviado', true);
        session()->flash('email', $this->email);
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('components.layouts.guest');
    }
}
