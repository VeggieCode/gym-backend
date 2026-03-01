<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Rutina;
use App\Domain\Entities\Ejercicio;
use App\Domain\Exceptions\AccesoDenegadoException;
use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Repositories\RutinaRepositoryInterface;

class CrearRutinaUseCase
{
    public function __construct(private RutinaRepositoryInterface $repository, private AuthRepositoryInterface $authRepository) {}

    /**
     * @throws AccesoDenegadoException
     */
    public function ejecutar(string $nombre, array $diasAsignados, array $datosEjercicios): Rutina
    {
        // Regla: Solo usuarios con sesión iniciada pueden crear rutinas
        $usuario = $this->authRepository->obtenerUsuarioActual();
        if (!$usuario) {
            throw new AccesoDenegadoException("Debes iniciar sesión para crear rutinas.");
        }

        $ejerciciosPuros = [];

        // Convertimos los arrays crudos en Entidades de Dominio
        foreach ($datosEjercicios as $dato) {
            $ejerciciosPuros[] = new Ejercicio(
                null,
                $dato['nombre'],
                $dato['series'],
                $dato['repeticiones']
            );
        }

        // Construimos la Raíz de Agregado.
        // ¡Si la lista viene vacía, Rutina arrojará la excepción aquí!
        $rutina = new Rutina(null, $nombre, $diasAsignados, $ejerciciosPuros);

        return $this->repository->guardar($rutina);
    }
}
