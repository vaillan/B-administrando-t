<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListaArticulosSeeder extends Seeder
{

    protected $listaArticulos;

    public function __construct()
    {
        $this->listaArticulos = [
            'Fijos' => [

                'Básicos' => [
                    'Alimentación',
                    'Celular',
                    'Internet',
                    'Agua',
                    'Luz',
                    'Gas',
                    'Bestido',
                    'Medicina',
                    'Transporte',
                ],

                'Fijos'   => [
                    'Renta',
                    'Tenencia de automóvil',
                    'Gasolina para automóvil',
                    'Seguros para automóvil',
                    'Inscripciones escolares',
                    'Uniformes escolares',
                    'Utiles escolares',
                    'Seguro de vida',
                    'Seguro GMM',
                    'Suguro para el retiro',
                ],

                'Deudas' => [
                    'Hipoteca',
                    'Automóvil',
                    'Tarjetas de crédito',
                    'Préstamos',
                    'Empeños',
                ],
            ],

            'Gustos' => [

                'Recreación' => [
                    'Viajes',
                    'Cine',
                    'Conciertos',
                    'Restaurantes',
                ],

                'Centaveros' => [
                    'Cafés',
                    'Lavado auto',
                    'Cigarros',
                    'Alcohol',
                    'Refrescos',
                    'Dulces',
                ],

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
        $this->crear();
    }

    private function crear()
    {
        $listas = collect($this->listaArticulos);

        $listas->each(function ($item, $key) {
            $etiqueta_id = DB::table('etiquetas')->insertGetId([
                'nombre_etiqueta' => $key,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => date('Y-m-d H:i:m'),
                'updated_at' => date('Y-m-d H:i:m'),
            ]);

            $sublistas = collect($item);

            $sublistas->each(function ($lista, $keyLista) use ($etiqueta_id) {
                $lista = collect($lista);
                
                $categoria_id = DB::table('categorias')->insertGetId([
                    'nombre_categoria' => $keyLista,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => date('Y-m-d H:i:m'),
                    'updated_at' => date('Y-m-d H:i:m'),
                ]);

                $lista->each(function ($articulo) use ($categoria_id, $etiqueta_id) {
                    DB::table('lista_articulos')->insert([
                        'nombre_articulo' => $articulo,
                        'categoria_id' => $categoria_id,
                        'etiqueta_id' => $etiqueta_id,
                        'created_by' => 1,
                        'updated_by' => 1,
                        'created_at' => date('Y-m-d H:i:m'),
                        'updated_at' => date('Y-m-d H:i:m'),
                    ]);
                });
            });
        });
    }
}
