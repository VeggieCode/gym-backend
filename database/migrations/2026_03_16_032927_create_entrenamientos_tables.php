<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. El Aggregate Root
        Schema::create('entrenamientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rutina_base_id')->nullable()->constrained('rutinas')->nullOnDelete();
            $table->string('nombre');
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin')->nullable(); // Si es null, el entrenamiento está "En Curso"
            $table->timestamps();
        });

        // 2. La Entidad Hija (El Snapshot)
        Schema::create('ejercicios_entrenados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrenamiento_id')->constrained('entrenamientos')->cascadeOnDelete();
            $table->foreignId('ejercicio_original_id')->nullable()->constrained('ejercicios')->nullOnDelete();
            $table->string('nombre_snapshot'); // Historial inmutable
            $table->string('tipo_registro');
            $table->integer('orden')->default(0); // Para mantener el orden que le dimos en la UI
            $table->timestamps();
        });

        // 3. El Molde Universal de Series
        Schema::create('series_realizadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ejercicio_entrenado_id')->constrained('ejercicios_entrenados')->cascadeOnDelete();

            // Todas las métricas son nullables para soportar cualquier TipoRegistroEjercicio
            $table->decimal('peso', 8, 2)->nullable();
            $table->integer('repeticiones')->nullable();
            $table->integer('tiempo_segundos')->nullable();
            $table->decimal('distancia_metros', 8, 2)->nullable();

            $table->boolean('completada')->default(false);
            $table->integer('orden')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_realizadas');
        Schema::dropIfExists('ejercicios_entrenados');
        Schema::dropIfExists('entrenamientos');
    }
};
