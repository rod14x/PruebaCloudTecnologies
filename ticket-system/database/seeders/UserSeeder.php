<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\RolUsuario;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@test.com',
            'dni' => '12345678',
            'telefono' => '987654321',
            'password' => Hash::make('password'),
            'rol' => RolUsuario::ADMINISTRADOR,
        ]);

        // Empleado
        User::create([
            'name' => 'Empleado',
            'email' => 'empleado@test.com',
            'dni' => '87654321',
            'telefono' => '912345678',
            'password' => Hash::make('password'),
            'rol' => RolUsuario::EMPLEADO,
        ]);
    }
}
