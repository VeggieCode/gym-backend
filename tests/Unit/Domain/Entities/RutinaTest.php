<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase; // Importante: Usamos el TestCase puro de PHPUnit
use App\Domain\Entities\Rutina;
use App\Domain\Entities\Ejercicio;
use App\Domain\Exceptions\RutinaSinEjerciciosException;
use InvalidArgumentException;

class RutinaTest extends TestCase
{
    public function test_no_se_puede_crear_ejercicio_con_series_invalidas(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Las series y repeticiones de 'Press' deben ser mayores a cero.");

        // Intentamos crear un ejercicio con 0 series
        new Ejercicio(null, "Press", 0, 10);
    }

    public function test_no_se_puede_crear_rutina_sin_ejercicios(): void
    {
        $this->expectException(RutinaSinEjerciciosException::class);

        // Intentamos crear una rutina con un array vacío de ejercicios
        new Rutina(null, "Fuerza", ["Lunes"], []);
    }

    public function test_rutina_se_instancia_correctamente_con_datos_validos(): void
    {
        $ejercicio = new Ejercicio(null, "Sentadilla", 4, 12);
        $rutina = new Rutina(null, "Pierna Pesada", ["Martes"], [$ejercicio]);

        $this->assertEquals("Pierna Pesada", $rutina->nombre);
        $this->assertCount(1, $rutina->ejercicios);
        $this->assertEquals("Sentadilla", $rutina->ejercicios[0]->nombre);
    }
}
