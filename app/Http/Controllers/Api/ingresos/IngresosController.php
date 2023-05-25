<?php

namespace App\Http\Controllers\Api\ingresos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ingresos\Ingreso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\periodos\Periodo;
use App\Models\presupuesto\Presupuesto;
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
                'periodo' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
            }

            $montoIngreso = (int) filter_var($request->input('ingreso'), FILTER_SANITIZE_NUMBER_INT);
            $user_id = Auth::id();
            $ingreso = $request->all();
            $date = date('Y-m-d H:i:s');

            $ingreso = Ingreso::updateOrCreate(
                ['tipo_ingreso_id' => $ingreso['tipo_ingreso_id'], 'created_by' => $user_id,],
                [
                    'ingreso' => $montoIngreso,
                    'updated_by' => $user_id,
                ]
            );

            Periodo::updateOrCreate(
                ['created_by' => $user_id, 'ingreso_id' => $ingreso->id],
                [
                    'updated_by' => $user_id,
                    'periodo' => $request->periodo,
                ]
            );

            Presupuesto::updateOrCreate(
                ['ingreso_id' => $ingreso->id, 'usuario_id' => $user_id],
                [
                    'total' => $ingreso->ingreso,
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]
            );

            return response()->json(['msg' => 'Ingreso creado correctamente.', 'items' => $ingreso]);
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
        Ingreso::find($id)->delete();
        Periodo::where('ingreso_id', $id)->delete();
        Presupuesto::where('ingreso_id',$id)->delete();
        return response()->json(['type' => 'Object', 'items' => ['message' => 'Ingreso eliminado correctamente']]);
    }
}
