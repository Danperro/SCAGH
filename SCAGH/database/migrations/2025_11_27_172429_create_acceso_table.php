<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acceso', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rol_id');
            $table->foreign('rol_id')->references('id')->on('rol');

            $table->unsignedBigInteger('menu_id');
            $table->foreign('menu_id')->references('id')->on('menu');

            $table->unsignedBigInteger('permiso_id');
            $table->foreign('permiso_id')->references('id')->on('permiso');

            $table->string('nombre', 50)->nullable();
            $table->boolean('estado')->default(true);

            $table->dateTime('fecha_cr')->nullable();
            $table->unsignedBigInteger('usuario_cr')->nullable();
            $table->dateTime('fecha_md')->nullable();
            $table->unsignedBigInteger('usuario_md')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acceso');
    }
};
