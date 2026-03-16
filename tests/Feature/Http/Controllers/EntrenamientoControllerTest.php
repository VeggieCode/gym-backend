<?php

namespace Tests\Feature\Http\Controllers;

use App\Domain\Enums\TipoRegistroEjercicio;
use App\Models\Ejercicio;
use App\Models\Rutina;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntrenamientoControllerTest extends TestCase
{
    use RefreshDatabase; // Resetea la BD en cada test

    private User $usuario;
    private Rutina $rutina;

    protected function setUp(): void
    {
        parent::setUp();
        $this->usuario = User::factory()->create();

        $this->rutina = Rutina::create([
            'nombre' => 'Rutina Torso',
            'dias_asignados' => json_encode(['Lunes', 'Martes']),
            'user_id' => $this->usuario->id,
        ]);

        $ejercicio1 = Ejercicio::create([
            'nombre' => 'Press de Banca',
            'grupo_muscular' => 'Pecho',
            'tipo_registro' => TipoRegistroEjercicio::PESO_REPETICIONES->value,
        ]);

        $ejercicio2 = Ejercicio::create([
            'nombre' => 'Plancha',
            'grupo_muscular' => 'Core',
            'tipo_registro' => TipoRegistroEjercicio::DURACION->value,
        ]);

        // Atamos los ejercicios a la rutina usando la tabla pivote
        $this->rutina->ejercicios()->attach([
            $ejercicio1->id => ['orden' => 1],
            $ejercicio2->id => ['orden' => 2]
        ]);
    }

    public function test_puede_iniciar_un_entrenamiento_desde_una_rutina_existente(): void
    {
        // Act: Hacemos la petición POST simulando estar logueados
        $response = $this->actingAs($this->usuario)
            ->postJson('/api/entrenamientos/iniciar', [
                'rutina_id' => $this->rutina->id
            ]);

        // Assert: Verificamos el código 201 Created y la estructura del JSON
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'entrenamiento' => [
                        'id',
                        'nombre',
                        'fecha_inicio',
                        'ejercicios' => [
                            '*' => [
                                'id',
                                'ejercicio_original_id',
                                'nombre',
                                'tipo_registro',
                                'series'
                            ]
                        ]
                    ]
                ]
            ]);

        // Assert BD: Verificamos que realmente se guardó en MySQL/SQLite
        $this->assertDatabaseHas('entrenamientos', [
            'usuario_id' => $this->usuario->id,
            'rutina_base_id' => $this->rutina->id,
            'nombre' => 'Rutina Torso',
            'fecha_fin' => null // Debe estar En Curso
        ]);

        // Verificamos que se clonaron los ejercicios a la tabla de snapshots
        $this->assertDatabaseHas('ejercicios_entrenados', [
            'nombre_snapshot' => 'Press de Banca',
            'tipo_registro' => TipoRegistroEjercicio::PESO_REPETICIONES->value
        ]);

        $this->assertDatabaseHas('ejercicios_entrenados', [
            'nombre_snapshot' => 'Plancha',
            'tipo_registro' => TipoRegistroEjercicio::DURACION->value
        ]);
    }
}
