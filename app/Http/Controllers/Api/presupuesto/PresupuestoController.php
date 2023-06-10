<?php

namespace App\Http\Controllers\Api\presupuesto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\presupuesto\Presupuesto;
use Carbon\Carbon;
class PresupuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $presupuesto = DB::table('presupuesto')->whereNull('deleted_at')->get();
        return response()->json(['type' => 'array', 'items' => $presupuesto]);
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
        $presupuestoReporte = collect();
        $presupuesto = Presupuesto::with(['ingreso' => function ($query) {
            $query->with(['tipoIngreso', 'periodo']);
        }])->where('usuario_id', $id)->get()->groupBy(function ($query) {
            return Carbon::parse($query->ingreso->periodo->periodo)->format('Y');
        });

        $presupuesto->each(function ($presupuesto, $presupuestoKey) use (&$presupuestoReporte) {
            $presupuestoReporte->push([
                'periodo' => $presupuestoKey,
                'total' => $presupuesto->sum('total'),
                'presupuesto' => $presupuesto
            ]);
        });

        return response()->json(['type' => 'array', 'items' => $presupuestoReporte->sortBy(['periodo', 'desc']), 'name' => 'presupuesto']);
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
