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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('autor');
            $table->unsignedBigInteger('categoria_id');
            $table->decimal('precio', 8, 2)->default(0);
            $table->string('tiempo')->nullable();
            
            $table->string('estado')->default('publicado');
            $table->timestamp('fecha_creacion')->nullable(); // Agregar esta línea
            $table->string('imagen')->nullable();
            $table->timestamps();

            $table->foreign('autor')->references('id')->on('usuarios');
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
