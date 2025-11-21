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
        if (!Schema::hasTable('plan_estudio')) {
            Schema::create('plan_estudio', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('curso_id');
                $table->string('nombre');
                $table->json('contenido')->nullable();
                $table->timestamps();
                
                $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
            });
        }
        
        // Agregar la columna nivel si no existe
        if (!Schema::hasColumn('plan_estudio', 'nivel')) {
            Schema::table('plan_estudio', function (Blueprint $table) {
                $table->string('nivel')->default('principiante')->after('contenido');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacemos nada en down para evitar eliminar datos importantes
    }
};
