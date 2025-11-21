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
        Schema::create('plan_estudio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curso_id');
            $table->string('nombre'); // Nombre del tema del plan de estudio
            $table->json('contenido')->nullable()->after('nombre');
            $table->timestamps();

            // Llave foránea
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_estudio');
    }
};
