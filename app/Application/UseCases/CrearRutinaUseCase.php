<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Rutina;
use App\Domain\Entities\Ejercicio;
use App\Domain\Repositories\RutinaRepositoryInterface;

class CrearRutinaUseCase
{
    public function __construct(private RutinaRepositoryInterface $repository) {}

    public function ejecutar(string $nombre, array $diasAsignados, array $datosEjercicios): Rutina
    {
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
