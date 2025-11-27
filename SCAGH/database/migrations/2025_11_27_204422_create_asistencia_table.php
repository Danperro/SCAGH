<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();

            // FK principal
            $table->unsignedBigInteger('horario_curso_docente_id');
            $table->foreign('horario_curso_docente_id')->references('id')->on('horario_curso_docente');

            // Fecha y hora del registro
            $table->dateTime('fecha_registro');
            $table->time('hora_registro');

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
        Schema::dropIfExists('asistencia');
    }
};
