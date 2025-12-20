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
        Schema::create('estudiante_curso_docente', function (Blueprint $table) {
            $table->id();

            // Estudiante FK
            $table->unsignedBigInteger('estudiante_id');
            $table->foreign('estudiante_id')
                ->references('id')
                ->on('estudiante')
                ->onDelete('cascade');

            // 🔹 DocenteCurso FK (NO curso_id)
            $table->unsignedBigInteger('docente_curso_id');
            $table->foreign('docente_curso_id')
                ->references('id')
                ->on('docente_curso')
                ->onDelete('cascade');

            // Semestre FK (si lo quieres explícito)
            $table->unsignedBigInteger('semestre_id');
            $table->foreign('semestre_id')
                ->references('id')
                ->on('semestre')
                ->onDelete('cascade');

            // Estado 1 = activo, 0 = retirado
            $table->boolean('estado')->default(true);

            // Auditoría
            $table->dateTime('fecha_cr')->nullable();
            $table->unsignedBigInteger('usuario_cr')->nullable();
            $table->dateTime('fecha_md')->nullable();
            $table->unsignedBigInteger('usuario_md')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiante_curso_docente');
    }
};
