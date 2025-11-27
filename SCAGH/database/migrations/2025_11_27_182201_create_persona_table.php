<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 40);
            $table->string('apellido_paterno', 40);
            $table->string('apellido_materno', 40)->nullable();

            $table->string('dni', 20)->unique();
            $table->string('telefono', 9)->nullable();
            $table->string('correo', 120)->unique();

            $table->date('fecha_nacimiento')->nullable();

            $table->boolean('estado')->default(true);

            // Auditoría
            $table->dateTime('fecha_cr')->nullable();
            $table->unsignedBigInteger('usuario_cr')->nullable();
            $table->dateTime('fecha_md')->nullable();
            $table->unsignedBigInteger('usuario_md')->nullable();

            // created_at, updated_at
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};
