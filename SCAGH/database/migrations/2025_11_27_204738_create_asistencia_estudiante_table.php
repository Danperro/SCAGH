<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asistencia_estudiante', function (Blueprint $table) {
            $table->id();

            // FK Asistencia
            $table->unsignedBigInteger('asistencia_id');
            $table->foreign('asistencia_id')->references('id')->on('asistencia');

            // FK Estudiante
            $table->unsignedBigInteger('estudiante_id');
            $table->foreign('estudiante_id')->references('id')->on('estudiante');

            // Tipo Asistencia → catálogo
            // (grupo = TIPO_ASISTENCIA)
            $table->unsignedBigInteger('tipo_asistencia_id');
            $table->foreign('tipo_asistencia_id')->references('id')->on('catalogo');

            $table->boolean('estado')->default(true);

            // Auditoría
            $table->dateTime('fecha_cr')->nullable();
            $table->unsignedBigInteger('usuario_cr')->nullable();

            $table->dateTime('fecha_md')->nullable();
            $table->unsignedBigInteger('usuario_md')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencia_estudiante');
    }
};
