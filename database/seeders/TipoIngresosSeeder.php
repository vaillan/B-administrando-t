<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoIngresosSeeder extends Seeder
{
    protected $tipo_ingresos;
    public function __construct()
    {
        $date = date('Y-m-d H:i:s');
        $this->tipo_ingresos = [
            [
                'nombre_ingreso' => 'Sueldo/Honorarios/Prestaciones',
                'created_at' => $date,
                'updated_at' => $date,
            ],
        ];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_ingresos')->insert(
            $this->tipo_ingresos
        );
    }
}
