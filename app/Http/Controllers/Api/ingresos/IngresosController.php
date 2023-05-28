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
use App\Models\reglas\Regla;
use App\Models\reglas\ReglaAplicadaPresupuesto;

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

            $ingreso = Ingreso::updateOrCreate(
                ['tipo_ingreso_id' => $ingreso['tipo_ingreso_id'], 'created_by' => $user_id,],
                [
                    'ingreso' => $montoIngreso,
                    'updated_by' => $user_id,
                ]
            );

            $this->periodo($request, $ingreso, $user_id);

            $presupuesto = $this->presupuesto($ingreso, $user_id);

            $this->aplicarRegla($presupuesto, $user_id);

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
        $presupuestos = Presupuesto::where('ingreso_id', $id)->get();
        $presupuestos->each(function ($presupuesto) {
            ReglaAplicadaPresupuesto::where('presupuesto_id', $presupuesto->id)->delete();
            $presupuesto->delete();
        });
        return response()->json(['type' => 'Object', 'items' => ['message' => 'Ingreso eliminado correctamente']]);
    }

    private function aplicarRegla($presupuesto, $user_id)
    {
        $reglas = Regla::all();
        $reglas->each(function ($regla) use ($presupuesto, $user_id) {
            ReglaAplicadaPresupuesto::updateOrCreate(
                ['regla_id' => $regla->id, 'presupuesto_id' => $presupuesto->id],
                [
                    'total' => ($presupuesto->total * ($regla->porcentaje / 100)),
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]
            );
        });
    }

    private function periodo(Request $request, $ingreso, $user_id)
    {
        Periodo::updateOrCreate(
            ['created_by' => $user_id, 'ingreso_id' => $ingreso->id],
            [
                'updated_by' => $user_id,
                'periodo' => $request->periodo,
            ]
        );
    }

    /**
     * 
     * @param $ingreso
     * @param $user_id
     * @return App\Models\presupuesto\Presupuesto
     */
    private function presupuesto($ingreso, $user_id)
    {
        $presupuesto = Presupuesto::updateOrCreate(
            ['ingreso_id' => $ingreso->id, 'usuario_id' => $user_id],
            [
                'total' => $ingreso->ingreso,
                'created_by' => $user_id,
                'updated_by' => $user_id,
            ]
        );

        return $presupuesto;
    }
}
