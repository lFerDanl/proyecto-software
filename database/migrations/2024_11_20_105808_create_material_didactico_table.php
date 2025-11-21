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
        Schema::create('material_didactico', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion')->nullable(); // Descripción del material
            $table->string('archivo');               // Ruta del archivo
            $table->string('tipo');                  // Tipo del material (PDF, video, imagen, etc.)
            $table->foreignId('curso_id')            // Relación con la tabla 'cursos'
                  ->constrained('cursos')            // Enlaza con la tabla 'cursos'
                  ->onDelete('cascade');             // Borra los materiales si se elimina el curso
            $table->timestamps();                    // Campos created_at y updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_didactico');
    }
};
