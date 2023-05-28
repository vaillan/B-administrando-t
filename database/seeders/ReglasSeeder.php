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
        $this->reglas = [
            [
                'porcentaje' => 20.00,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'porcentaje' => 30.00,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'porcentaje' => 30.00,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'porcentaje' => 20.00,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ]
        ];
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
        DB::table('reglas')->insert($this->reglas);
    }
}
