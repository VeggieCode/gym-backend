<?php

namespace Application\UseCases;

use App\Application\UseCases\ArchivarPlanUseCase;
use App\Application\UseCases\CrearPlanUseCase;
use App\Domain\Entities\Plan;
use App\Domain\Entities\Usuario;
use App\Domain\Exceptions\AccesoDenegadoException;
use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Repositories\PlanRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ArchivarPlanUseCaseTest extends TestCase
{
    private $planRepoMock;
    private $authRepoMock;
    private ArchivarPlanUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        // Preparamos los dobles de riesgo
        $this->planRepoMock = $this->createMock(PlanRepositoryInterface::class);
        $this->authRepoMock = $this->createMock(AuthRepositoryInterface::class);

        $this->useCase = new ArchivarPlanUseCase($this->planRepoMock, $this->authRepoMock);
    }

    public function test_un_cliente_regular_no_puede_archivar_planes(): void
    {
        // 1. Simulamos que el usuario logueado es un "cliente"
        $usuarioCliente = new Usuario(1, "Juan", "juan@test.com", "cliente");
        $this->authRepoMock->method('obtenerUsuarioActual')->willReturn($usuarioCliente);

        // 2. Le decimos a PHPUnit que ESPERAMOS que se lance la excepción de acceso denegado
        $this->expectException(AccesoDenegadoException::class);
        $this->expectExceptionMessage("No tienes permisos para realizar esta acción. Solo el dueño del gimnasio puede hacerlo.");

        // 3. Ejecutamos (esto debe fallar inmediatamente)
        $this->useCase->ejecutar(100);
    }

    public function test_el_dueno_puede_crear_planes_correctamente(): void
    {
        // 1. Simulamos que el usuario logueado es el "dueño"
        $usuarioDueno = new Usuario(2, "Jefe", "jefe@gym.com", "dueño");
        $this->authRepoMock->method('obtenerUsuarioActual')->willReturn($usuarioDueno);

        $planEncontrado = new Plan(
            id: 100,
            nombre: "Plan 1",
            nivel: 'Intermedio',
            precio: 100,
            activo: true,

        );
        // 2. Simulamos que el plan se guarda exitosamente
        $this->planRepoMock->method('buscarPorId')->withAnyParameters()->willReturn($planEncontrado);

        // 3. Ejecutamos
        $this->useCase->ejecutar(100);
    }

}
