<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->string('correo', 100)->unique();
            $table->string('contrasena');
            $table->unsignedBigInteger('rol_id')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('avatar_url')->nullable();
            $table->jsonb('config_preferencias')->default(DB::raw("'{}'::jsonb"));
            $table->string('role', 50)->default('USER');
            $table->softDeletes();
            $table->timestamps();
    
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
