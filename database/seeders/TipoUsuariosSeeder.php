<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuariosSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date('Y-m-d H:i:s');
        DB::table('tipo_usuarios')->insert(
            array(
                [
                    'tipo_usuario' => 216,
                    'nombre_tipo_usuario' => "Administrador",
                    'created_at' => $date,
                    'updated_at' => $date,
                ],
                [
                    'tipo_usuario' => 218,
                    'nombre_tipo_usuario' => "Usuario Clasico",
                    'created_at' => $date,
                    'updated_at' => $date,
                ]
            )
        );
    }
}
