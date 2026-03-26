<?php

use App\Models\User;
use Tests\TestCase; 
use Illuminate\Foundation\Testing\RefreshDatabase;

// Al agregar TestCase::class aquí, Pest sabe que debe "bootear" Laravel antes del test
uses(TestCase::class, RefreshDatabase::class);

test('un usuario no puede eliminarse a si mismo', function () {
    // 1) Crear un usuario
    // Ahora User::factory() funcionará porque el motor ya está encendido
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    // 2) Actuar como el usuario
    $this->actingAs($user, 'web');

    // 3) Intentar borrar
    // Nota: Si esto falla después, es porque la ruta 'admin.users.destroy' no existe en tus archivos de rutas
    $response = $this->delete(route('admin.users.destroy', $user));

    // 4) Esperar bloqueo (Forbidden)
    $response->assertStatus(403);

    // 5) Verificar que el usuario Sigue en la base de datos
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});