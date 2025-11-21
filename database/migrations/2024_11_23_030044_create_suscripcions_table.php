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
        Schema::create('suscripcions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consumidor_id')->constrained('usuarios')->cascadeOnUpdate()->cascadeOnDelete();       
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnUpdate()->cascadeOnDelete();       
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');            
            $table->boolean('estado');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripcions');
    }
};
