<?php

namespace Tests\Unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\ValueObjects\Periodo;
use App\Domain\Exceptions\DomainException;
use DateTimeImmutable;

class PeriodoTest extends TestCase
{
    public function test_falla_si_fecha_fin_es_anterior_a_inicio(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("La fecha de fin no puede ser anterior a la fecha de inicio.");

        $inicio = new DateTimeImmutable('2026-03-15 10:00:00');
        $fin = new DateTimeImmutable('2026-03-15 09:00:00'); // ¡Terminó una hora antes de empezar!

        new Periodo($inicio, $fin);
    }

    public function test_calcula_duracion_en_minutos_correctamente(): void
    {
        $inicio = new DateTimeImmutable('2026-03-15 10:00:00');
        $fin = new DateTimeImmutable('2026-03-15 11:30:00'); // Hora y media después

        $periodo = new Periodo($inicio, $fin);

        $this->assertEquals(90, $periodo->duracionEnMinutos());
    }

    public function test_duracion_es_cero_si_sigue_en_curso(): void
    {
        $inicio = new DateTimeImmutable('2026-03-15 10:00:00');
        $periodo = new Periodo($inicio, null);

        $this->assertEquals(0, $periodo->duracionEnMinutos());
        $this->assertTrue($periodo->estaEnCurso());
    }
}
