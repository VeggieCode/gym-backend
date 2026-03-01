<?php

namespace App\Domain\Entities;

use InvalidArgumentException;

class Usuario
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $nombre,
        public readonly string $email,
        public readonly string $rol = 'cliente' // Autorización básica
    ) {
        if (trim($nombre) === '') {
            throw new InvalidArgumentException("El nombre no puede estar vacío.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("El formato del email es inválido.");
        }
    }
}
