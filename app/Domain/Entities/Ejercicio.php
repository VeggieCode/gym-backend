<?php

namespace App\Domain\Entities;

use InvalidArgumentException;

class Ejercicio
{
    public ?int $id;
    public string $nombre;
    public int $series;
    public int $repeticiones;

    public function __construct(?int $id, string $nombre, int $series, int $repeticiones)
    {
        if ($series <= 0 || $repeticiones <= 0) {
            throw new InvalidArgumentException("Las series y repeticiones de '$nombre' deben ser mayores a cero.");
        }

        $this->id = $id;
        $this->nombre = $nombre;
        $this->series = $series;
        $this->repeticiones = $repeticiones;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'series' => $this->series,
            'repeticiones' => $this->repeticiones
        ];
    }
}
