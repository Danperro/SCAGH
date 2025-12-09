<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('docente_curso', function (Blueprint $table) {
            $table->id();

            // FK Curso
            $table->unsignedBigInteger('curso_id');
            $table->foreign('curso_id')->references('id')->on('curso');

            // FK Docente
            $table->unsignedBigInteger('docente_id');
            $table->foreign('docente_id')->references('id')->on('docente');

            // FK Semestre
            $table->unsignedBigInteger('semestre_id');
            $table->foreign('semestre_id')->references('id')->on('semestre');

            // FK Grupo (catálogo)
            $table->unsignedBigInteger('grupo_id');
            $table->foreign('grupo_id')->references('id')->on('catalogo');

            $table->boolean('estado')->default(true);
            
            // Auditoría
            $table->dateTime('fecha_cr')->nullable();
            $table->unsignedBigInteger('usuario_cr')->nullable();
            $table->dateTime('fecha_md')->nullable();
            $table->unsignedBigInteger('usuario_md')->nullable();

            $table->timestamps();
            // combinación única
            $table->unique(
                ['curso_id', 'docente_id', 'semestre_id', 'grupo_id'],
                'docente_curso_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente_curso');
    }
};
