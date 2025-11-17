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
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id(); // ID único
            $table->unsignedBigInteger('usuario_id'); // Usuario que califica
            $table->unsignedBigInteger('curso_id');  // Curso calificado
            $table->tinyInteger('calificacion')->comment('Calificación entre 1 y 5'); // Calificación (1 a 5)
            $table->text('comentario')->nullable()->comment('Comentario opcional'); // Comentarios del usuario
            $table->timestamps(); // Fechas de creación y actualización

            // Relaciones
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade'); // Relación con usuarios
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade'); // Relación con cursos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
