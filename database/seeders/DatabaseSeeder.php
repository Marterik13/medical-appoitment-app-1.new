<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //llamar a los seeders creador
        $this->call([ RoleSeeder::class]);
        $this->call([ UserSeeder::class ]);
    

        //crear usuario de prueba cada que se ejecute la migracion

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'odioloslunes@mx',
            'password' => bcrypt('12345678'),
        ]);
    }
}
