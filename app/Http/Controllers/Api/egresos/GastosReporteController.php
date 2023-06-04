<?php

namespace App\Http\Controllers\Api\egresos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\egresos\GastoReporte;

class GastosReporteController extends Controller
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
        GastoReporte::find($id)->delete();
    }

    public function getGastoReportePorUsuario($usuario_id)
    {
        $gastoPorPeriodo = collect();
        $reporte = GastoReporte::with(['gasto' => function ($query) {
            $query->with('periodo');
        }])->where('created_by', $usuario_id)->get()->groupBy('gasto.periodo.periodo');

        $reporte->each(function ($gasto, $gastoKey) use (&$gastoPorPeriodo) {
            $gastoPorPeriodo->push([
                'periodo' => $gastoKey,
                'total' => $gasto->sum('total')
            ]);
        });

        return response()->json(['type' => 'array', 'items' => $gastoPorPeriodo]);
    }
}
