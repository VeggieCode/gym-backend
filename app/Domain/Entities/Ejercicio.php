<?php

namespace App\Domain\Entities;

use App\Domain\Enums\TipoRegistroEjercicio;
use InvalidArgumentException;

class Ejercicio
{
    public ?int $id;
    public string $nombre;
    public ?string $grupoMuscular;
    public TipoRegistroEjercicio $tipoRegistro;

    public function __construct(
        ?int $id, string $nombre, ?string $grupoMuscular, TipoRegistroEjercicio $tipoRegistro = TipoRegistroEjercicio::PESO_REPETICIONES)
    {
        if ($grupoMuscular === '') {
            throw new InvalidArgumentException("El grupo muscular de '$nombre' no puede estar vacío.");
        }
        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipoRegistro = $tipoRegistro;
        $this->grupoMuscular = $grupoMuscular;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'tipoRegistro' => $this->tipoRegistro->value,
            'grupoMuscular' => $this->grupoMuscular
        ];
    }
}
