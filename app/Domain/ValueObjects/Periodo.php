<?php

namespace App\Domain\ValueObjects;

use DateTimeImmutable;
use App\Domain\Exceptions\DomainException;

readonly class Periodo
{
    public function __construct(
        public DateTimeImmutable $inicio,
        public ?DateTimeImmutable $fin = null,
    ) {
        if ($this->fin !== null && $this->fin < $this->inicio) {
            throw new DomainException("La fecha de fin no puede ser anterior a la fecha de inicio.");
        }
    }

    public function duracionEnMinutos(): int
    {
        if ($this->fin === null) {
            return 0;
        }
        $intervalo = $this->fin->diff($this->inicio);

        $minutosTotales = $intervalo->days * 24 * 60;
        $minutosTotales += $intervalo->h * 60;
        $minutosTotales += $intervalo->i;

        return $minutosTotales;
    }

    public function estaEnCurso(): bool
    {
        return $this->fin === null;
    }

    /**
     * @throws DomainException
     */
    public function finalizar(DateTimeImmutable $fechaFin): self
    {
        return new self($this->inicio, $fechaFin);
    }

}
