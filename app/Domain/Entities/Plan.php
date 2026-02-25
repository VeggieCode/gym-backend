<?php

namespace App\Domain\Entities;

use App\Domain\Exceptions\PrecioInvalidoException;
use App\Domain\Exceptions\NivelInvalidoException;
use App\Domain\Exceptions\PlanYaInactivoException;

class Plan
{
    public ?int $id;
    public string $nombre;
    public string $nivel;
    public float $precio;
    public bool $activo;

    public function __construct(?int $id, string $nombre, string $nivel, float $precio, bool $activo = true)
    {
        // REGLA 1: Validación simple
        if ($precio < 0) {
            throw new PrecioInvalidoException("El precio ($precio) no puede ser negativo.");
        }

        // REGLA 2: Lista blanca (Regla de negocio estricta)
        $nivelesPermitidos = ['Principiante', 'Intermedio', 'Avanzado'];
        if (!in_array($nivel, $nivelesPermitidos)) {
            throw new NivelInvalidoException("El nivel '$nivel' no existe en el gimnasio.");
        }

        // REGLA 3: Validación cruzada (Propiedades que dependen entre sí)
        if ($nivel === 'Avanzado' && $precio < 50.00) {
            throw new PrecioInvalidoException("Los planes avanzados son premium. Deben costar al menos $50.00.");
        }

        $this->id = $id;
        $this->nombre = $nombre;
        $this->nivel = $nivel;
        $this->precio = $precio;
        $this->activo = $activo;
    }

    // REGLA 4: Transición de estado (Comportamiento)
    // Fíjate que no usamos un simple setActivo(false). Las acciones tienen intención.
    public function archivar(): void
    {
        if (!$this->activo) {
            throw new PlanYaInactivoException("Este plan ya fue archivado previamente.");
        }

        $this->activo = false;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'nivel' => $this->nivel,
            'precio' => $this->precio,
            'activo' => $this->activo
        ];
    }
}
