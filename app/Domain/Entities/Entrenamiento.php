<?php

namespace App\Domain\Entities;

use App\Domain\Exceptions\DomainException;
use App\Domain\ValueObjects\Periodo;
use DateTimeImmutable;

class Entrenamiento
{
    /** @var EjercicioEntrenado[] */
    public array $ejercicios;
    public function __construct(
        public ?int $id,
        public int $usuarioId,
        public string $nombre,
        public Periodo $periodo,
        public ?int $rutinaBaseId = null, // Puede ser null si es un entrenamiento libre
    ){

    }

    /**
     * @throws DomainException
     */
    public function agregarEjercicio(EjercicioEntrenado $ejercicio): void
    {
        $this->asegurarQueEstaEnCurso();
        $this->ejercicios[] = $ejercicio;
    }

    /**
     * @throws DomainException
     */
    public function finalizar(DateTimeImmutable $fechaFin): void
    {
        $this->asegurarQueEstaEnCurso();
        // Reemplazamos el VO inmutable por uno nuevo finalizado
        $this->periodo = $this->periodo->finalizar($fechaFin);

    }

    /**
     * @throws DomainException
     */
    private function asegurarQueEstaEnCurso(): void
    {
        if(!$this->periodo->estaEnCurso()) {
            throw new DomainException("No puedes modificar un entrenamiento que ya ha finalizado.");
        }
    }
}
