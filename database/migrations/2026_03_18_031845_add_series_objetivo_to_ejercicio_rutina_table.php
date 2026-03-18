<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ejercicio_rutina', function (Blueprint $table) {
            $table->json('series_objetivo')->nullable()->after('orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ejercicio_rutina', function (Blueprint $table) {
            $table->dropColumn('series_objetivo');
        });
    }
};
