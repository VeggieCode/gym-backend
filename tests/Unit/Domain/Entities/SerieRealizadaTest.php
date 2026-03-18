<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\SerieRealizada;
use App\Domain\Enums\TipoRegistroEjercicio;
use App\Domain\Exceptions\SerieInvalidaException;

class SerieRealizadaTest extends TestCase
{
    public function test_falla_si_ejercicio_de_fuerza_no_tiene_repeticiones(): void
    {
        $this->expectException(SerieInvalidaException::class);
        $this->expectExceptionMessage("Los ejercicios de peso y repeticiones exigen registrar las repeticiones.");

        // Intentamos crear una serie de Press de Banca solo con peso, sin repeticiones
        new SerieRealizada(
            id: null,
            ejercicioEntrenadoId: 1,
            tipoRegistro: TipoRegistroEjercicio::PESO_REPETICIONES,
            peso: 80.5,
            repeticiones: null // <-- Esto debería hacer explotar el dominio
        );
    }

    public function test_falla_si_ejercicio_de_cardio_tiene_peso(): void
    {
        $this->expectException(SerieInvalidaException::class);
        $this->expectExceptionMessage("Los ejercicios de distancia y duración no deben registrar peso ni repeticiones.");

        // Intentamos crear una serie de Correr metiéndole 20kg de peso
        new SerieRealizada(
            id: null,
            ejercicioEntrenadoId: 1,
            tipoRegistro: TipoRegistroEjercicio::DISTANCIA_DURACION,
            peso: 20.0, // <-- Ilegal para este tipo de ejercicio
            tiempoSegundos: 1800,
            distanciaMetros: 5000
        );
    }

    public function test_crea_serie_valida_correctamente(): void
    {
        // Una serie de sentadillas libre (solo peso corporal y reps)
        $serie = new SerieRealizada(
            id: null,
            ejercicioEntrenadoId: 1,
            tipoRegistro: TipoRegistroEjercicio::PESO_CORPORAL_REPETICIONES,
            peso: null,
            repeticiones: 15
        );

        $this->assertEquals(15, $serie->repeticiones);
        $this->assertNull($serie->peso); // El dominio forzó o aceptó que no haya peso
    }
}
