<?php

namespace App\Http\Controllers\Api\ingresos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ingresos\Ingreso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class IngresosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                'ingreso' => 'required',
                'tipo_ingreso_id' => 'required',
                'plazo_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
            }

            $montoIngreso = (int) filter_var($request->input('ingreso'), FILTER_SANITIZE_NUMBER_INT);
            $user_id = Auth::id();
            $ingreso = $request->all();
            $ingreso['ingreso'] = $montoIngreso;
            $ingreso['created_by'] = $user_id;
            $ingreso['updated_by'] = $user_id;
            Ingreso::create($ingreso);
            return response()->json(['msg' => 'Ingreso creado correctamente.']);
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
}
