<?php

namespace App\Domain\Entities;

use App\Domain\Enums\TipoRegistroEjercicio;
use App\Domain\Exceptions\SerieInvalidaException;

class SerieRealizada
{
    /**
     * @throws SerieInvalidaException
     */
    public function __construct(
        public ?int $id,
        public int $ejercicioEntrenadoId,
        public TipoRegistroEjercicio $tipoRegistro,
        public ?float $peso = null,
        public ?int $repeticiones = null,
        public ?int $tiempoSegundos = null,
        public ?int $distanciaMetros = null,
        public bool $completada = false
    ){
        $this->validarReglasDeNegocio();
    }

    /**
     * @throws SerieInvalidaException
     */
    private function validarReglasDeNegocio(): void
    {
        // Limpiamos datos basura por si el frontend mandó de más
        $this->limpiarDatosIncompatibles();

        // Validamos requerimientos estrictos según el tipo
        switch ($this->tipoRegistro) {
            case TipoRegistroEjercicio::PESO_REPETICIONES:
            case TipoRegistroEjercicio::PESO_ANADIDO_REPETICIONES:
            case TipoRegistroEjercicio::PESO_CORPORAL_REPETICIONES:
                if ($this->repeticiones === null || $this->repeticiones < 0) {
                    throw new SerieInvalidaException("Los ejercicios de peso y repeticiones exigen registrar las repeticiones.");
                }
                break;

            case TipoRegistroEjercicio::DISTANCIA_DURACION:
                if ($this->distanciaMetros === null || $this->tiempoSegundos === null) {
                    throw new SerieInvalidaException("Debes registrar la distancia y el tiempo.");
                }
                if ($this->peso !== null || $this->repeticiones !== null) {
                    throw new SerieInvalidaException("Los ejercicios de distancia y duración no deben registrar peso ni repeticiones.");
                }
                break;

            case TipoRegistroEjercicio::DURACION:
                if ($this->tiempoSegundos === null) {
                    throw new SerieInvalidaException("Debes registrar el tiempo en segundos.");
                }
                break;
        }
    }

    private function limpiarDatosIncompatibles(): void
    {
        if ($this->tipoRegistro === TipoRegistroEjercicio::PESO_CORPORAL_REPETICIONES) {
            $this->peso = null;
        }

        if ($this->tipoRegistro === TipoRegistroEjercicio::DURACION) {
            $this->peso = null;
            $this->repeticiones = null;
            $this->distanciaMetros = null;
        }
    }

}
