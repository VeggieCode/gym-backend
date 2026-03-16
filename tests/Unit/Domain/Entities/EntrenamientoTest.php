<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Entrenamiento;
use App\Domain\Entities\EjercicioEntrenado;
use App\Domain\ValueObjects\Periodo;
use App\Domain\Enums\TipoRegistroEjercicio;
use App\Domain\Exceptions\DomainException;
use DateTimeImmutable;

class EntrenamientoTest extends TestCase
{
    public function test_no_se_pueden_agregar_ejercicios_si_el_entrenamiento_ya_termino(): void
    {
        $inicio = new DateTimeImmutable('2026-03-15 10:00:00');
        $fin = new DateTimeImmutable('2026-03-15 11:00:00');
        $periodoFinalizado = new Periodo($inicio, $fin);

        $entrenamiento = new Entrenamiento(
            id: 1,
            usuarioId: 1,
            nombre: "Día de Pierna",
            periodo: $periodoFinalizado
        );

        $ejercicio = new EjercicioEntrenado(null, 1, "Sentadilla", TipoRegistroEjercicio::PESO_REPETICIONES);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("No puedes modificar un entrenamiento que ya ha finalizado.");

        $entrenamiento->agregarEjercicio($ejercicio);
    }

    public function test_finalizar_entrenamiento_actualiza_el_periodo(): void
    {
        $inicio = new DateTimeImmutable('2026-03-15 10:00:00');
        $periodoEnCurso = new Periodo($inicio, null);

        $entrenamiento = new Entrenamiento(1, 1, "Día de Pierna", $periodoEnCurso);

        $this->assertTrue($entrenamiento->periodo->estaEnCurso());

        $horaFin = new DateTimeImmutable('2026-03-15 11:30:00');
        $entrenamiento->finalizar($horaFin);

        $this->assertFalse($entrenamiento->periodo->estaEnCurso());
        $this->assertEquals(90, $entrenamiento->periodo->duracionEnMinutos());
    }
}
