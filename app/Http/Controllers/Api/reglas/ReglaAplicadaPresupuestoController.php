<?php

namespace App\Http\Controllers\Api\reglas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\reglas\ReglaAplicadaPresupuesto;

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
        $reglas = ReglaAplicadaPresupuesto::with('regla')
        ->whereIn('regla_id', $request->input('regla_ids'))->get();
        return response()->json(['type' => 'array', 'items' => $reglas, 'name' => 'regla_aplicada_presupuesto']);
    }
}
