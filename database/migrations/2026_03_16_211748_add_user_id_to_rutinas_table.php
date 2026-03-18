<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rutinas', function (Blueprint $table) {
            // Añadimos la columna user_id y la conectamos con la tabla users.
            // Nota: Le ponemos nullable() por seguridad, por si ya tenemos rutinas
            // guardadas en la base de datos de pruebas; de lo contrario, MySQL rechazaría
            // la migración por no tener un valor por defecto para los registros existentes.
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rutinas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
