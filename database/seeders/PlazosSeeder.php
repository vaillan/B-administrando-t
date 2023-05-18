<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlazosSeeder extends Seeder
{
    protected $plazos;

    public function __construct()
    {
        $date = date('Y-m-d H:i:s');
        $this->plazos = [
            [
                'plazo' => 'Diario',
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'plazo' => 'Semanal',
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'plazo' => 'Quincenal',
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'plazo' => 'Mensual',
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'plazo' => 'Anual',
                'created_at' => $date,
                'updated_at' => $date
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
        DB::table('plazos')->insert($this->plazos);
    }
}
