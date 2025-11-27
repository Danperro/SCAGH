<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('horario_curso_docente', function (Blueprint $table) {
            $table->id();

            // FK Horario
            $table->unsignedBigInteger('horario_id');
            $table->foreign('horario_id')->references('id')->on('horario');

            // FK DocenteCurso
            $table->unsignedBigInteger('docente_curso_id');
            $table->foreign('docente_curso_id')->references('id')->on('docente_curso');

            // FK Semana (catálogo)
            $table->unsignedBigInteger('semana_id');
            $table->foreign('semana_id')->references('id')->on('catalogo');

            // Horas
            $table->time('hora_inicio');
            $table->time('hora_fin');

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
        Schema::dropIfExists('horario_curso_docente');
    }
};
