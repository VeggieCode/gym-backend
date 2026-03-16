<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Creamos la tabla pivote
        Schema::create('ejercicio_rutina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rutina_id')->constrained('rutinas')->cascadeOnDelete();
            $table->foreignId('ejercicio_id')->constrained('ejercicios')->cascadeOnDelete();

            // Dato extra en el pivote: El orden en el que aparece en esta rutina específica
            $table->integer('orden')->default(0);

            $table->timestamps();

            // Evitamos que el mismo ejercicio se agregue dos veces a la misma rutina por error
            $table->unique(['rutina_id', 'ejercicio_id']);
        });

        // 2. Quitamos la relación 1:N de la tabla de ejercicios (si existía)
        if (Schema::hasColumn('ejercicios', 'rutina_id')) {
            Schema::table('ejercicios', function (Blueprint $table) {
                // Primero quitamos la llave foránea (el nombre puede variar según cómo la creaste,
                // si falla esta línea, comenta el dropForeign y deja solo el dropColumn)
                $table->dropForeign(['rutina_id']);
                $table->dropColumn('rutina_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ejercicio_rutina');

        // Opcional: Revertir los cambios si hacemos rollback
        Schema::table('ejercicios', function (Blueprint $table) {
            $table->foreignId('rutina_id')->nullable()->constrained('rutinas')->cascadeOnDelete();
        });
    }
};
