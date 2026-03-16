<?php

namespace App\Application\UseCases;

use App\Domain\Entities\EjercicioEntrenado;
use App\Domain\Entities\Entrenamiento;
use App\Domain\Enums\TipoRegistroEjercicio;
use App\Domain\Exceptions\AccesoDenegadoException;
use App\Domain\Exceptions\EntrenamientoActivoExistenteException;
use App\Domain\Exceptions\RutinaNoEncontradaException;
use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Repositories\EntrenamientoRepositoryInterface;
use App\Domain\Repositories\RutinaRepositoryInterface;
use App\Domain\ValueObjects\Periodo;
use DateTimeImmutable;

class IniciarEntrenamientoUseCase
{
    public function __construct(
        public EntrenamientoRepositoryInterface $entrenamientoRepository,
        public RutinaRepositoryInterface        $rutinaRepository,
        public AuthRepositoryInterface          $authRepository
    )
    {
    }

    public function ejecutar(int $rutinaId): Entrenamiento
    {
        $usuario = $this->authRepository->obtenerUsuarioActual();

        // Autenticación: Verificar que el usuario esté autenticado
        if (!$usuario) {
            throw new AccesoDenegadoException("Debes iniciar sesión para entrenar.");
        }

        // Regla de Negocio: No permitir entrenamientos paralelos.
        $entrenamientoActivo = $this->entrenamientoRepository->obtenerActivoPorUsuario($usuario->id);
        if ($entrenamientoActivo !== null) {
            throw new EntrenamientoActivoExistenteException();
        }

        // Obtener la Rutina a partir de la cual se iniciará el entrenamiento
        $rutina = $this->rutinaRepository->buscarPorId($rutinaId);
        if (!$rutina) {
            throw new RutinaNoEncontradaException("La rutina con ID $rutinaId no existe.");
        }

        $periodoActual = new Periodo(new DateTimeImmutable(), null);

        $entrenamiento = new Entrenamiento(
            id: null,
            usuarioId: $usuario->id,
            nombre: $rutina->nombre,
            periodo: $periodoActual,
            rutinaBaseId: $rutina->id
        );

        // Clonar (Snapshot) los ejercicios de la rutina base al entrenamiento
        foreach ($rutina->ejercicios as $ejercicioPlantilla) {
            $ejercicioVivo = new EjercicioEntrenado(
                id: null,
                ejercicioOriginalId: $ejercicioPlantilla->id ?? 0,
                nombreSnapshot: $ejercicioPlantilla->nombre,
                tipoRegistro: $ejercicioPlantilla->tipoRegistro ?? TipoRegistroEjercicio::PESO_REPETICIONES,
            );
            $entrenamiento->agregarEjercicio($ejercicioVivo);
        }

        return $this->entrenamientoRepository->guardar($entrenamiento);
    }
}
