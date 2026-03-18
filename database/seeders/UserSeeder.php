<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //crear usuario de prueba
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'Admin@admin.com',
            'password' => bcrypt('12345678'),
            'id_number' => '123456789',
            'phone' => '1234567890',
            'address' => 'Test Address'
        ])->assignRole('Administrador');
    }
}