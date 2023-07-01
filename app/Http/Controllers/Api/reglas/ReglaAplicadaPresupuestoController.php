<?php

namespace App\Http\Controllers\Api\reglas;

use App\Http\Controllers\Controller;
use App\Models\ingresos\Ingreso;
use Illuminate\Http\Request;
use App\Models\reglas\ReglaAplicadaPresupuesto;
use App\Models\periodos\Periodo;
use Illuminate\Support\Facades\Auth;
use App\Models\presupuesto\Presupuesto;
use Carbon\Carbon;
class ReglaAplicadaPresupuestoController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reglas = ReglaAplicadaPresupuesto::find($id);
        return response()->json(['type' => 'object', 'items' => $reglas, 'name' => 'regla_aplicada_presupuesto']);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function getReglaAplicadaPresupuesto(Request $request)
    {
        $user_id = Auth::id();
        $periodo = Carbon::parse($request->input('periodo'))->format('Y-m');
        $ingreso = Ingreso::with(['periodo','presupuesto' => function ($query){
            $query->with(['reglaAplicadaPresupuesto' => function ($query) {
                $query->with('regla');
            }]);
        }])->where('usuario_id', $user_id)->get()->filter(function ($ingreso) use ($periodo) {
            return $ingreso->periodo->periodo === $periodo;
        });

        return response()->json(['type' => 'array', 'items' => $ingreso, 'name' => 'regla_aplicada_presupuesto']);
    }
}
