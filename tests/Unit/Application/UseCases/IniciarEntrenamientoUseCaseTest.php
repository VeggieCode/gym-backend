<?php

namespace Tests\Unit\Application\UseCases;

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\IniciarEntrenamientoUseCase;
use App\Domain\Repositories\RutinaRepositoryInterface;
use App\Domain\Repositories\EntrenamientoRepositoryInterface;
use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Entities\Usuario;
use App\Domain\Entities\Rutina;
use App\Domain\Entities\Ejercicio;
use App\Domain\Enums\TipoRegistroEjercicio;
use App\Domain\Entities\Entrenamiento;
use App\Domain\Exceptions\EntrenamientoActivoExistenteException;
use App\Domain\Exceptions\RutinaNoEncontradaException;
use App\Domain\Exceptions\RutinaSinEjerciciosException;

class IniciarEntrenamientoUseCaseTest extends TestCase
{
    private $rutinaRepoMock;
    private $entrenamientoRepoMock;
    private $authRepoMock;
    private $useCase;
    private $usuarioFake;

    protected function setUp(): void
    {
        $this->rutinaRepoMock = $this->createMock(RutinaRepositoryInterface::class);
        $this->entrenamientoRepoMock = $this->createMock(EntrenamientoRepositoryInterface::class);
        $this->authRepoMock = $this->createMock(AuthRepositoryInterface::class);

        $this->usuarioFake = new Usuario(1, "Juan", "juan@test.com", "cliente");
        $this->authRepoMock->method('obtenerUsuarioActual')->willReturn($this->usuarioFake);

        $this->useCase = new IniciarEntrenamientoUseCase(
            $this->entrenamientoRepoMock,
            $this->rutinaRepoMock,
            $this->authRepoMock
        );
    }

    public function test_falla_si_el_usuario_ya_tiene_un_entrenamiento_en_curso(): void
    {
        // Simulamos que la base de datos dice "Sí, Juan tiene un entrenamiento sin terminar"
        $this->entrenamientoRepoMock->method('obtenerActivoPorUsuario')->willReturn($this->createMock(Entrenamiento::class));

        $this->expectException(EntrenamientoActivoExistenteException::class);

        $this->useCase->ejecutar(99); // Intentamos iniciar la rutina 99
    }

    public function test_falla_si_la_rutina_no_existe(): void
    {
        // Simulamos que el usuario está libre
        $this->entrenamientoRepoMock->method('obtenerActivoPorUsuario')->willReturn(null);
        // Simulamos que la rutina no existe
        $this->rutinaRepoMock->method('buscarPorId')->willReturn(null);

        $this->expectException(RutinaNoEncontradaException::class);

        $this->useCase->ejecutar(99);
    }

    public function test_inicia_entrenamiento_correctamente_clonando_los_ejercicios(): void
    {
        $this->entrenamientoRepoMock->method('obtenerActivoPorUsuario')->willReturn(null);

        // Simulamos una Rutina que viene de la BD (sin ejercicios para simplificar el test)
        $rutinaBase = new Rutina(5, nombre: "Empuje", diasAsignados: ["Lunes"], usuarioId: $this->usuarioFake->id, ejercicios: [
            new Ejercicio(null, nombre: "Dominadas", grupoMuscular: 'core', tipoRegistro: TipoRegistroEjercicio::PESO_REPETICIONES),
            new Ejercicio(null, nombre: "Plancha", grupoMuscular: 'core', tipoRegistro: TipoRegistroEjercicio::DURACION),
        ]);
        $this->rutinaRepoMock->method('buscarPorId')->willReturn($rutinaBase);

        // Interceptamos el método guardar para verificar qué Entidad construyó el Caso de Uso
        $this->entrenamientoRepoMock->expects($this->once())
            ->method('guardar')
            ->willReturnCallback(function (Entrenamiento $ent) {
                $this->assertEquals("Empuje", $ent->nombre);
                $this->assertEquals(5, $ent->rutinaBaseId);
                $this->assertTrue($ent->periodo->estaEnCurso());
                $ent->id = 100; // Simulamos que MySQL le dio el ID 100
                return $ent;
            });

        $entrenamientoIniciado = $this->useCase->ejecutar(5);

        $this->assertEquals(100, $entrenamientoIniciado->id);
    }
}
