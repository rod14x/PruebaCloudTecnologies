<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ResetPassword extends Component
{
    public $email = '';
    public $codigo = '';
    public $password = '';
    public $password_confirmation = '';

    public function mount()
    {
        $this->email = session('email', '');
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'codigo' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function resetPassword()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if (!$user || 
            !$user->codigo_recuperacion || 
            !$user->codigo_recuperacion_expira ||
            $user->codigo_recuperacion_expira < now()) {
            $this->addError('codigo', 'El código ha expirado o no es válido.');
            return;
        }

        if (!Hash::check($this->codigo, $user->codigo_recuperacion)) {
            $this->addError('codigo', 'El código es incorrecto.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
            'codigo_recuperacion' => null,
            'codigo_recuperacion_expira' => null,
        ]);

        Auth::login($user);

        session()->flash('message', 'Contraseña actualizada exitosamente.');
        
        return redirect('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.reset-password')->layout('components.layouts.guest');
    }
}
