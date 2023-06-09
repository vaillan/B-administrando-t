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
        $grupoGastos = collect();
        $gasto = Gasto::with(['periodo' => function ($query) {
            $query->select('id', 'gasto_id', 'periodo');
        }, 'articulo' => function ($query) {
            $query->select('id', 'nombre_articulo');
        }])
            ->where('created_by', Auth::id())
            ->select('id', 'lista_articulo_id', 'total')
            ->get()->groupBy('periodo.periodo');

        $gasto->each(function ($gasto, $key) use (&$grupoGastos) {
            $grupoGastos->push(
                [
                    'periodo' => $key,
                    'total' => $gasto->sum('total'),
                    'gasto' => $gasto
                ]
            );
        });

        return response()->json(['type' => 'array', 'items' => $grupoGastos->sortBy(['periodo', 'desc']), 'name' => 'gastos']);
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

            $ajustes = $this->ajustesPresupuestoIngreso($request, $gastoTotal);

            if ($ajustes['type'] === 'error') {
                return response()->json($ajustes, Response::HTTP_PRECONDITION_FAILED);
            }

            $gasto = Gasto::create(
                [
                    'total' => $gastoTotal,
                    'lista_articulo_id' => $request->input('lista_articulo_id'),
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ]
            );

            $this->gastoReporte($request, $gasto, $gastoTotal, $user_id);

            $this->periodo($request, $gasto, $user_id);

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
        $gasto = Gasto::with([
            'reglaAplicadaPresupuesto' => function ($query) {
                $query->with(['presupuesto' => function ($query) {
                    $query->with('ingreso');
                }]);
            },
            'gastoReporte',
            'periodo'
        ])->find($id);
        $rembolso = $gasto->total;
        $gasto->reglaAplicadaPresupuesto->total += $rembolso;
        $gasto->reglaAplicadaPresupuesto->presupuesto->total += $rembolso;
        $gasto->reglaAplicadaPresupuesto->presupuesto->ingreso->ingreso += $rembolso;
        $gasto->reglaAplicadaPresupuesto->presupuesto->save();
        $gasto->reglaAplicadaPresupuesto->presupuesto->ingreso->save();
        $gasto->reglaAplicadaPresupuesto->save();
        $gasto->gastoReporte->delete();
        $gasto->periodo->delete();
        $gasto->delete();
        return response()->json(['type' => 'object', 'items' => ['msg' => 'Gasto elimido correctamente']]);
    }

    private function gastoReporte(Request $request, $gastoModel, $gastoTotal, $user_id)
    {
        GastoReporte::create(
            [
                'regla_aplicada_presupuesto_id' => $request->input('regla_aplicada_presupuesto_id'),
                'gasto_id' => $gastoModel->id,
                'total' => $gastoTotal,
                'created_by' => $user_id,
                'updated_by' => $user_id
            ]
        );
    }

    private function periodo(Request $request, $gastoModel, $user_id)
    {
        Periodo::create(
            [
                'periodo' => $request->input('periodo'),
                'gasto_id' => $gastoModel->id,
                'created_by' => $user_id,
                'updated_by' => $user_id,
            ]
        );
    }

    private function ajustesPresupuestoIngreso(Request $request, $gastoTotal)
    {
        $reglaAplicadaPresupuesto = ReglaAplicadaPresupuesto::find($request->input('regla_aplicada_presupuesto_id'));
        $presupuesto = Presupuesto::with(['ingreso'])->find($reglaAplicadaPresupuesto->presupuesto_id);
        $diferenciaReglaAplicada = $reglaAplicadaPresupuesto->total - $gastoTotal;
        if ($diferenciaReglaAplicada < 0) {
            return ['type' => 'error', 'items' => ['msg' => 'Sobrepasa el presupuesto seleccionado', 'presupuesto' => $reglaAplicadaPresupuesto]];
        }
        $reglaAplicadaPresupuesto->total = $diferenciaReglaAplicada;
        $presupuesto->ingreso->ingreso = $presupuesto->ingreso->ingreso - $gastoTotal;
        $presupuesto->total = $presupuesto->total - $gastoTotal;
        $presupuesto->ingreso->save();
        $presupuesto->save();
        $reglaAplicadaPresupuesto->save();
        return ['type' => 'success', 'items' => $diferenciaReglaAplicada];
    }
}
