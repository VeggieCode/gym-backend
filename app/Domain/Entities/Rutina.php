<?php

namespace App\Domain\Entities;

use App\Domain\Exceptions\DomainException;
use App\Domain\Exceptions\RutinaSinEjerciciosException;

class Rutina
{
    public ?int $id;
    public string $nombre;
    public array $diasAsignados; // Ej: ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"]

    /** @var Ejercicio[] */
    public array $ejercicios;
    public ?int $usuarioId;

    /**
     * @param int|null $id
     * @param string $nombre
     * @param array $diasAsignados
     * @param int|null $usuarioId
     * @param array $ejercicios
     */
    public function __construct(?int $id, string $nombre, array $diasAsignados, ?int $usuarioId, array $ejercicios = [])
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->diasAsignados = $diasAsignados;
        $this->ejercicios = $ejercicios;
        $this->usuarioId = $usuarioId ?? null;
    }

    /**
     * Comportamiento de dominio para añadir ejercicios a la plantilla
     */
    public function agregarEjercicio(Ejercicio $ejercicio): void
    {
        $this->ejercicios[] = $ejercicio;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'dias_asignados' => $this->diasAsignados,
            'ejercicios' => array_map(fn(Ejercicio $ej) => $ej->toArray(), $this->ejercicios)
        ];
    }
}
