<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('curso', function (Blueprint $table) {
            $table->id();

            // FK Carrera (catálogo)
            $table->unsignedBigInteger('carrera_id');
            $table->foreign('carrera_id')->references('id')->on('catalogo');

            // FK Ciclo (catálogo)
            $table->unsignedBigInteger('ciclo_id');
            $table->foreign('ciclo_id')->references('id')->on('catalogo');

            $table->string('nombre', 50);
            $table->string('codigo', 20)->unique();

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
        Schema::dropIfExists('curso');
    }
};
