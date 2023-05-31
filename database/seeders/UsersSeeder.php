<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = date('Y-m-d H:i:s');
        DB::table('users')->insert(
            [
                'name' => 'Valentin',
                'last_name' => 'Ortiz Santiago',
                'email' => 'ortizsantiago9303@gmail.com',
                'password' => bcrypt('misther13'),
                'tipo_usuario_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ]
        );
    }
}
