<?php

namespace App\Http\Controllers\Api\lista_articulos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\lista_articulos\ListaArticulo;
use Validator;

class ListaArticulosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listaArticulos = ListaArticulo::with(['categoria', 'etiqueta'])->get();
        return response()->json(['type' => 'array', 'items' => $listaArticulos, 'name' => 'lista_articulos']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $query = DB::transaction(function () use ($request) {
            $validator = Validator::make($request->all(), [
                'categoria_id' => 'required',
                'etiqueta_id' => 'required',
                'nombre_articulo' => 'required|unique:App\Models\lista_articulos\ListaArticulo,nombre_articulo'
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
            }

            $user_id = Auth::id();

            ListaArticulo::create(
                [
                    'nombre_articulo' => $request->input('nombre_articulo'),
                    'categoria_id' => $request->input('categoria_id'),
                    'etiqueta_id' => $request->input('etiqueta_id'),
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]
            );

            return response()->json(['type' => 'object', 'items' => ['msg' => 'Artículo creado correctamente'], 'name' => 'articulos']);

        });
        return $query;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Obtiene articulos por etiqueta y categoria
     * 
     * @param int $etiqueta_id
     * @param int $categoria_id
     * @return Illuminate\Contracts\Routing\ResponseFactory::json
     */
    public function getArticulos($etiqueta_id, $categoria_id)
    {
        $articulos = ListaArticulo::with([
            'categoria' => function ($query) {
                $query->select('id', 'nombre_categoria');
            },
        ])
            ->where('etiqueta_id', $etiqueta_id)
            ->where('categoria_id', $categoria_id)->get();
        return response()->json(['type' => 'array', 'items' => $articulos, 'name' => 'lista_articulos']);
    }
}
