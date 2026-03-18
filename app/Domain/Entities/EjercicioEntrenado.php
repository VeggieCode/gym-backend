<?php

namespace App\Domain\Entities;

use App\Domain\Enums\TipoRegistroEjercicio;

class EjercicioEntrenado
{
    /** @var SerieRealizada[] */
    public array $series = [];

    public function __construct(
        public ?int $id,
        public int $ejercicioOriginalId,
        public string $nombreSnapshot, // Guardamos el nombre tal como era ese día
        public TipoRegistroEjercicio $tipoRegistro
    ) {}

    public function agregarSerie(SerieRealizada $serie): void
    {
        $this->series[] = $serie;
    }
}
