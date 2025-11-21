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
        Schema::table('plan_estudio', function (Blueprint $table) {
            $table->string('nivel')->default('principiante')->after('contenido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_estudio', function (Blueprint $table) {
            $table->dropColumn('nivel');
        });
    }
};
