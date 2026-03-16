<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Rutina;
use App\Domain\Entities\Ejercicio;
use App\Domain\Enums\TipoRegistroEjercicio;
use App\Domain\Exceptions\AccesoDenegadoException;
use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Repositories\RutinaRepositoryInterface;

class CrearRutinaUseCase
{
    public function __construct(private readonly RutinaRepositoryInterface $repository, private readonly AuthRepositoryInterface $authRepository) {}

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
                id: null,
                nombre: $dato['nombre'], grupoMuscular: $dato['grupo_muscular'], tipoRegistro: $dato['tipo_registro'] ?? TipoRegistroEjercicio::PESO_REPETICIONES,
            );
        }

        // Construimos la Raíz de Agregado.
        $rutina = new Rutina(id: null, nombre: $nombre, diasAsignados: $diasAsignados, usuarioId: $usuario->id, ejercicios: $ejerciciosPuros);

        return $this->repository->guardar($rutina);
    }
}
