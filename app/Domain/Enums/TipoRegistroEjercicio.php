<?php

namespace App\Domain\Enums;

enum TipoRegistroEjercicio: string
{
    case PESO_REPETICIONES = 'peso_repeticiones'; // Ej: Press de banca
    case PESO_CORPORAL_REPETICIONES = 'peso_corporal_repeticiones'; // Ej: Sentadillas libres
    case DURACION = 'duracion'; // Ej: Plancha (Plank)
    case DURACION_PESO = 'duracion_peso'; // Ej: Sentadilla isométrica con disco
    case DISTANCIA_DURACION = 'distancia_duracion'; // Ej: Correr en cinta
    case DISTANCIA_PESO = 'distancia_peso'; // Ej: Caminata de granjero
    const PESO_ANADIDO_REPETICIONES = 'peso_añadido_repeticiones'; // Ej: Dominadas con lastre
}
