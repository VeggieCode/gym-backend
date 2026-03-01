<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class PlanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_dueno_puede_crear_un_plan(): void
    {
        // Creamos un usuario DUEÑO y lo "logueamos"
        Sanctum::actingAs(User::factory()->create(['role' => 'dueño']), ['*']);

        $payload = [
            "nombre" => "Plan Titan",
            "precio" => 499.99,
            "duracion_meses" => 1,
            "nivel" => "Avanzado"
        ];

        $response = $this->postJson('/api/planes', $payload);
        $response->assertStatus(201)->assertJsonPath('data.nombre', 'Plan Titan');
    }

    public function test_un_cliente_no_puede_crear_un_plan(): void
    {
        // Creamos un usuario CLIENTE regular
        Sanctum::actingAs(User::factory()->create(['role' => 'cliente']), ['*']);

        $payload = [
            "nombre" => "Plan Ilegal",
            "precio" => 10,
            "duracion_meses" => 1,
            "nivel" => "Principiante"
        ];

        $response = $this->postJson('/api/planes', $payload);

        // Debe fallar por la regla de nuestro Caso de Uso (Atrapada en el Controller)
        $response->assertStatus(403)
            ->assertJsonPath('message', 'No tienes permisos para realizar esta acción. Solo el dueño del gimnasio puede hacerlo.');
    }

}
