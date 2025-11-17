<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RenovacionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('renovaciones')->insert([
            [
                'subscripcion_id' => 1,
                'fecha_renovacion' => '2024-12-01',
                'estado' => 'Activa',
            ],
            [
                'subscripcion_id' => 2,
                'fecha_renovacion' => '2024-12-01',
                'estado' => 'Pendiente',
            ],
        ]);
    }
}
