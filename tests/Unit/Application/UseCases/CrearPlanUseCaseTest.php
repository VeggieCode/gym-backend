<?php

namespace Tests\Unit\Application\UseCases;

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\CrearPlanUseCase;
use App\Domain\Repositories\PlanRepositoryInterface;
use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Entities\Plan;
use App\Domain\Entities\Usuario;
use App\Domain\Exceptions\AccesoDenegadoException;

class CrearPlanUseCaseTest extends TestCase
{
    private $planRepoMock;
    private $authRepoMock;
    private CrearPlanUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        // Preparamos los dobles de riesgo
        $this->planRepoMock = $this->createMock(PlanRepositoryInterface::class);
        $this->authRepoMock = $this->createMock(AuthRepositoryInterface::class);

        $this->useCase = new CrearPlanUseCase($this->planRepoMock, $this->authRepoMock);
    }

    public function test_un_cliente_regular_no_puede_crear_planes(): void
    {
        // 1. Simulamos que el usuario logueado es un "cliente"
        $usuarioCliente = new Usuario(1, "Juan", "juan@test.com", "cliente");
        $this->authRepoMock->method('obtenerUsuarioActual')->willReturn($usuarioCliente);

        // 2. Le decimos a PHPUnit que ESPERAMOS que se lance la excepción de acceso denegado
        $this->expectException(AccesoDenegadoException::class);
        $this->expectExceptionMessage("No tienes permisos para realizar esta acción. Solo el dueño del gimnasio puede hacerlo.");

        // 3. Ejecutamos (esto debe fallar inmediatamente)
        $this->useCase->ejecutar("Plan VIP", 500, 1, "Avanzado");
    }

    public function test_el_dueno_puede_crear_planes_correctamente(): void
    {
        // 1. Simulamos que el usuario logueado es el "dueño"
        $usuarioDueno = new Usuario(2, "Jefe", "jefe@gym.com", "dueño");
        $this->authRepoMock->method('obtenerUsuarioActual')->willReturn($usuarioDueno);

        // 2. Simulamos que el plan se guarda exitosamente
        $this->planRepoMock->method('guardar')->willReturnCallback(function (Plan $plan) {
            $plan->id = 100;
            return $plan;
        });

        // 3. Ejecutamos
        $resultado = $this->useCase->ejecutar("Plan VIP", 500, 1, "Avanzado");

        // 4. Verificamos
        $this->assertEquals("Plan VIP", $resultado->nombre);
        $this->assertEquals(100, $resultado->id);
    }
}
