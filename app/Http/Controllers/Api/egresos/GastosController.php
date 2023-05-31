<?php

namespace App\Http\Controllers\Api\egresos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\egresos\Gasto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\periodos\Periodo;
use App\Models\reglas\ReglaAplicadaPresupuesto;
use App\Models\egresos\GastoReporte;
use App\Models\presupuesto\Presupuesto;
use Validator;

class GastosController extends Controller
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
                'total' => 'required',
                'lista_articulo_id' => 'required',
                'regla_aplicada_presupuesto_id' => 'required',
                'periodo' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
            }

            $gastoTotal = (int) filter_var($request->input('total'), FILTER_SANITIZE_NUMBER_INT);
            $user_id = Auth::id();

            $gasto = Gasto::updateOrCreate(
                ['lista_articulo_id' => $request->input('lista_articulo_id'), 'created_by' => $user_id],
                ['total' => $gastoTotal, 'updated_by' => $user_id]
            );

            $this->gastoReporte($request, $gasto, $gastoTotal, $user_id);

            $this->periodo($request, $gasto, $user_id);

            $this->ajustesPresupuestoIngreso($request, $gastoTotal);

            return response()->json(['type' => 'object', 'items' => ['msg' => 'Gasto aplicado correctamente'], 'name' => 'gastos']);
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

    private function gastoReporte(Request $request, $gastoModel, $gastoTotal, $user_id)
    {
        GastoReporte::updateOrCreate(
            [
                'regla_aplicada_presupuesto_id' => $request->input('regla_aplicada_presupuesto_id'),
                'gasto_id' => $gastoModel->id
            ],
            [
                'total' => $gastoTotal,
                'created_by' => $user_id,
                'updated_by' => $user_id
            ]
        );
    }

    private function periodo(Request $request, $gastoModel, $user_id)
    {
        Periodo::updateOrCreate(
            ['created_by' => $user_id, 'gasto_id' => $gastoModel->id],
            [
                'updated_by' => $user_id,
                'periodo' => $request->input('periodo'),
            ]
        );
    }

    private function ajustesPresupuestoIngreso(Request $request, $gastoTotal)
    {
        $reglaAplicadaPresupuesto = ReglaAplicadaPresupuesto::find($request->input('regla_aplicada_presupuesto_id'));
        $presupuesto = Presupuesto::with('ingreso')->find($reglaAplicadaPresupuesto->presupuesto_id);
        $reglaAplicadaPresupuesto->total = $reglaAplicadaPresupuesto->total - $gastoTotal;
        $presupuesto->ingreso->ingreso = $presupuesto->ingreso->ingreso - $gastoTotal;
        $presupuesto->total = $presupuesto->total - $gastoTotal;
        $presupuesto->ingreso->save();
        $presupuesto->save();
        $reglaAplicadaPresupuesto->save();
    }
}
