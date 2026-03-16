<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Ejercicio;
use App\Domain\Entities\Rutina;
use PHPUnit\Framework\TestCase;

// Importante: Usamos el TestCase puro de PHPUnit

class RutinaTest extends TestCase
{

    public function test_rutina_se_instancia_correctamente_con_datos_validos(): void
    {
        $ejercicio = new Ejercicio(null, "Sentadilla", grupoMuscular: "cuadriceps");
        $rutina = new Rutina(id: null, nombre: "Pierna Pesada", diasAsignados: ["Martes"], usuarioId: 1, ejercicios: [$ejercicio]);

        $this->assertEquals("Pierna Pesada", $rutina->nombre);
        $this->assertCount(1, $rutina->ejercicios);
        $this->assertEquals("Sentadilla", $rutina->ejercicios[0]->nombre);
    }
}
