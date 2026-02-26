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

    public function __construct(?int $id, string $nombre, array $diasAsignados, array $ejercicios)
    {
        if (empty($ejercicios)) {
            throw new RutinaSinEjerciciosException("Una rutina de fuerza debe contener al menos un ejercicio.");
        }

        $this->id = $id;
        $this->nombre = $nombre;
        $this->diasAsignados = $diasAsignados;
        $this->ejercicios = $ejercicios;
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
