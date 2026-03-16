<?php

namespace Tests\Unit\Application\UseCases;

use App\Domain\Entities\Usuario;
use App\Domain\Enums\TipoRegistroEjercicio;
use App\Domain\Repositories\AuthRepositoryInterface;
use PHPUnit\Framework\TestCase;
use App\Application\UseCases\CrearRutinaUseCase;
use App\Domain\Repositories\RutinaRepositoryInterface;
use App\Domain\Entities\Rutina;

class CrearRutinaUseCaseTest extends TestCase
{
    public function test_ejecutar_ensambla_el_agregado_y_llama_al_repositorio(): void
    {
        $mockRepo = $this->createMock(RutinaRepositoryInterface::class);
        $mockAuthRepo = $this->createMock(AuthRepositoryInterface::class);

        // 1. Simulamos que hay un usuario logueado usando la Entidad de Dominio
        $usuarioFake = new Usuario(1, "Juan", "juan@test.com", "cliente");
        $mockAuthRepo->method('obtenerUsuarioActual')->willReturn($usuarioFake);

        $mockRepo->expects($this->once())
            ->method('guardar')
            ->willReturnCallback(function (Rutina $rutina) {
                $rutina->id = 999;
                return $rutina;
            });

        // 2. Inyectamos AMBOS repositorios falsos
        $useCase = new CrearRutinaUseCase($mockRepo, $mockAuthRepo);

        $datosEjercicios = [
            ['nombre' => 'Dominadas', 'grupo_muscular' => 'core', 'tipo_registro' => TipoRegistroEjercicio::PESO_REPETICIONES->value]
        ];

        $rutinaGuardada = $useCase->ejecutar("Espalda", ["Miércoles"], $datosEjercicios);

        $this->assertEquals(999, $rutinaGuardada->id);
    }
}
