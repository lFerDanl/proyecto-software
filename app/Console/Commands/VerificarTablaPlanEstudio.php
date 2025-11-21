<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerificarTablaPlanEstudio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verificar:plan-estudio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica si la tabla plan_estudio existe y muestra su estructura';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando tabla plan_estudio...');
        
        if (Schema::hasTable('plan_estudio')) {
            $this->info('✅ La tabla plan_estudio EXISTE en la base de datos.');
            
            // Obtener las columnas de la tabla
            $columns = Schema::getColumnListing('plan_estudio');
            
            $this->info('Columnas de la tabla plan_estudio:');
            $headers = ['Columna', 'Tipo', 'Nullable', 'Default'];
            $rows = [];
            
            foreach ($columns as $column) {
                try {
                $columnInfo = DB::select("SELECT column_name, data_type, is_nullable, column_default 
                                         FROM information_schema.columns 
                                         WHERE table_name = 'plan_estudio' AND column_name = ?", [$column]);
                
                if (count($columnInfo) > 0) {
                    $info = $columnInfo[0];
                    $rows[] = [
                        $info->column_name,
                        $info->data_type,
                        $info->is_nullable,
                        $info->column_default ?? 'NULL'
                    ];
                }
            } catch (\Exception $e) {
                $this->error("Error al obtener información de la columna: " . $e->getMessage());
                $rows[] = [$column, 'Error', 'Error', 'Error'];
            }
            }
            
            $this->table($headers, $rows);
            
            // Verificar si la columna nivel existe
            if (in_array('nivel', $columns)) {
                $this->info('✅ La columna "nivel" EXISTE en la tabla.');
            } else {
                $this->error('❌ La columna "nivel" NO EXISTE en la tabla.');
            }
        } else {
            $this->error('❌ La tabla plan_estudio NO EXISTE en la base de datos.');
            
            // Mostrar las tablas que sí existen
            $tables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = ?', ['public']);
            
            $this->info('Tablas existentes en la base de datos:');
            $tableNames = array_map(function($table) {
                return [$table->table_name];
            }, $tables);
            
            $this->table(['Nombre de la tabla'], $tableNames);
        }
        
        return Command::SUCCESS;
    }
}
