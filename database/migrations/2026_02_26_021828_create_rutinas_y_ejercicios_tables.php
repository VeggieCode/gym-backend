<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutinas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->json('dias_asignados'); // Guardaremos el array de días como JSON
            $table->timestamps();
        });

        Schema::create('ejercicios', function (Blueprint $table) {
            $table->id();
            // Llave foránea que elimina los ejercicios en cascada si se borra la rutina
            $table->foreignId('rutina_id')->constrained('rutinas')->onDelete('cascade');
            $table->string('nombre');
            $table->integer('series');
            $table->integer('repeticiones');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejercicios');
        Schema::dropIfExists('rutinas');
    }
};
