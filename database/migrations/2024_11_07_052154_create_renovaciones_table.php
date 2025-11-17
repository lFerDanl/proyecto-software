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
        Schema::create('renovaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscripcion_id')->constrained('subscripciones')->onDelete('cascade');
            $table->date('fecha_renovacion');
            $table->string('estado'); // Ej: "Activa", "Pendiente"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renovaciones');
    }
};
