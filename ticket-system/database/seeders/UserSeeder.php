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
            'password' => Hash::make('password'),
            'rol' => RolUsuario::ADMINISTRADOR,
        ]);

        // Empleado
        User::create([
            'name' => 'Empleado',
            'email' => 'empleado@test.com',
            'password' => Hash::make('password'),
            'rol' => RolUsuario::EMPLEADO,
        ]);
    }
}
