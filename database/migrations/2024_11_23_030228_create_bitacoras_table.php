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
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();

            $table->string('descripcion');
            $table->string('usuario');
            $table->string('usuario_id');
            $table->string('direccion_ip');
            $table->string('navegador');
            $table->string('tabla');
            $table->string('registro_id');
            $table->string('fecha_hora');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
