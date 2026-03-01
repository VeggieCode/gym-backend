<?php

namespace Tests\Unit\Application\UseCases;

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\CrearRutinaUseCase;
use App\Domain\Repositories\RutinaRepositoryInterface;
use App\Domain\Entities\Rutina;

class CrearRutinaUseCaseTest extends TestCase
{
    public function test_ejecutar_ensambla_el_agregado_y_llama_al_repositorio(): void
    {
        // 1. Arrange: Preparamos el Mock del Repositorio
        $mockRepo = $this->createMock(RutinaRepositoryInterface::class);

        // Le decimos que ESPERAMOS que el método 'guardar' sea llamado exactamente 1 vez
        $mockRepo->expects($this->once())
            ->method('guardar')
            ->willReturnCallback(function (Rutina $rutina) {
                $rutina->id = 999; // Simulamos que la BD le asignó el ID 999
                return $rutina;
            });

        $useCase = new CrearRutinaUseCase($mockRepo);

        $datosEjercicios = [
            ['nombre' => 'Dominadas', 'series' => 4, 'repeticiones' => 10]
        ];

        // 2. Act: Ejecutamos el caso de uso
        $rutinaGuardada = $useCase->ejecutar("Espalda", ["Miércoles"], $datosEjercicios);

        // 3. Assert: Verificamos que el resultado sea correcto
        $this->assertEquals(999, $rutinaGuardada->id);
        $this->assertEquals("Espalda", $rutinaGuardada->nombre);
        $this->assertCount(1, $rutinaGuardada->ejercicios);
    }
}
