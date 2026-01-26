<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Enums\RolUsuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $dni = '';
    public $telefono = '';
    public $password = '';
    public $password_confirmation = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'dni' => 'required|string|max:20|unique:users,dni',
            'telefono' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'dni' => $this->dni,
            'telefono' => $this->telefono,
            'password' => Hash::make($this->password),
            'rol' => RolUsuario::EMPLEADO, // Por defecto empleado
        ]);

        Auth::login($user);
        
        return redirect('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('components.layouts.guest');
    }
}
