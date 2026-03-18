<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\DomainException;

readonly class SerieObjetivo
{
    public function __construct(
        public ?float $peso = null,
        public ?int $repeticiones = null,
        public ?int $tiempoSegundos = null,
        public ?float $distanciaMetros = null
    ) {
        if ($this->repeticiones !== null && $this->repeticiones < 0) {
            throw new DomainException("Las repeticiones no pueden ser negativas.");
        }
    }
}
