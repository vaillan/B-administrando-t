<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReglasSeeder extends Seeder
{
    protected $reglas;

    public function __construct()
    {
        $date = date('Y-m-d H:i:s');
        $this->reglas = collect([
            [
                'porcentaje' => 50.00,
                'nombre_regla' => 'Fijos/Deudas',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'porcentaje' => 30.00,
                'nombre_regla' => 'Gustos',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'porcentaje' => 20.00,
                'nombre_regla' => 'Ahorros',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ]
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->crearReglas();
    }

    private function crearReglas()
    {
        $this->reglas->each(function ($regla) {
            DB::table('reglas')->insert($regla);
        });
    }
}
