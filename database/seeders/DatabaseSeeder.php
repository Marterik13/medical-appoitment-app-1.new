<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Llamamos a los seeders en el ORDEN correcto
        // Primero Roles, luego Tipos de Sangre, luego Usuarios
        $this->call([ 
            RoleSeeder::class,
            BloodTypeSeeder::class, 
            UserSeeder::class 
        ]);

        // 2. Crear usuario administrador de prueba
        // Lo guardamos en una variable para poder asignarle el rol de Spatie
        $admin = User::factory()->create([
            'name' => 'Admin Principal',
            'email' => 'odioloslunes@mx',
            'password' => bcrypt('12345678'),
            'id_number' => 'ADMIN-001', // Asegúrate de que cumpla tus validaciones
            'phone' => '9990001122',
            'address' => 'Mérida, Centro',
        ]);

        // 3. ASIGNAR ROL (Súper importante para poder entrar al panel)
        $admin->assignRole('Admin');
    }
}