<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase; // Importante: Aquí SÍ usamos el TestCase de Laravel

class RutinaControllerTest extends TestCase
{
    // Este trait limpia la base de datos de prueba después de cada test
    use RefreshDatabase;

    public function test_comprobar_que_estoy_usando_sqlite(): void
    {
        // Obtenemos el nombre de la conexión activa en este momento
        $conexionActiva = config('database.default');

        // Obtenemos el nombre de la base de datos física (o en memoria)
        $nombreBaseDatos = \DB::connection()->getDatabaseName();

        // Imprimimos en la consola para verlo con nuestros propios ojos
        dump('Conexión actual: ' . $conexionActiva);
        dump('Base de datos: ' . $nombreBaseDatos);

        // Hacemos una aserción para que la prueba falle si NO es sqlite
        $this->assertEquals('sqlite', $conexionActiva);
    }
    public function test_puede_crear_una_rutina_completa_via_api(): void
    {
        // 1. Payload simulando una petición HTTP desde Android o React
        $payload = [
            "nombre" => "Hipertrofia Avanzada",
            "dias_asignados" => ["Lunes", "Jueves"],
            "ejercicios" => [
                ["nombre" => "Press Militar", "series" => 4, "repeticiones" => 8],
                ["nombre" => "Elevaciones Laterales", "series" => 3, "repeticiones" => 15]
            ]
        ];

        // 2. Hacemos la petición POST
        $response = $this->postJson('/api/rutinas', $payload);

        // 3. Verificamos que responda con 201 Created y el JSON esperado
        $response->assertStatus(201)
            ->assertJsonPath('data.nombre', 'Hipertrofia Avanzada')
            ->assertJsonCount(2, 'data.ejercicios');

        // 4. Verificamos que LA INFRAESTRUCTURA (Base de datos transaccional) funcionó
        $this->assertDatabaseHas('rutinas', [
            'nombre' => 'Hipertrofia Avanzada'
        ]);

        $this->assertDatabaseHas('ejercicios', [
            'nombre' => 'Press Militar',
            'series' => 4
        ]);
    }

    public function test_api_rechaza_payload_incompleto(): void
    {
        // Payload inválido (faltan los ejercicios)
        $payload = [
            "nombre" => "Rutina Incompleta",
            "dias_asignados" => ["Lunes"]
        ];

        $response = $this->postJson('/api/rutinas', $payload);

        // Laravel debe atrapar esto en el $request->validate() del controlador
        $response->assertStatus(422) // Unprocessable Entity
        ->assertJsonValidationErrors(['ejercicios']);
    }
}
