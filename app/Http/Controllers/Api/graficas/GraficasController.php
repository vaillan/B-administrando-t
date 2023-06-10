<?php

namespace App\Http\Controllers\Api\graficas;

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
use Carbon\Carbon;
use Validator;

class GraficasController extends Controller
{


    public function getGastosGrafica(Request $request)
    {
        $dataset = collect();
        $labels = collect();
        $periodo = Carbon::parse($request->input('periodo'))->format('Y');
        $gasto = Gasto::with(['periodo'])
            ->where('created_by', Auth::id())
            ->select('id', 'lista_articulo_id', 'total')
            ->get()
            ->sortBy(['periodo.periodo', 'desc'])
            ->groupBy('periodo.periodo')
            ->filter(function ($gasto, $keyGasto) use ($periodo) {
                if(Carbon::parse($keyGasto)->format('Y') === $periodo) {
                    return $gasto;
                }
            });

        $gasto->each(function ($gasto, $keyGasto) use (&$dataset, &$labels) {
            $dataset->push($gasto->sum('total'));
            $labels->push($keyGasto);
        });

        return response()->json(['type' => 'array', 'items' => ['labels' => $labels, 'dataset' => $dataset->toArray()], 'name' => 'gastos']);
    }

    public function getIngresosGrafica()
    {
        $dataset = collect();
        $labels = collect();
        $presupuesto = Presupuesto::with(['ingreso' => function ($query) {
            $query->with(['periodo']);
        }])->where('usuario_id', Auth::id())->get()->groupBy(function ($query) {
            return Carbon::parse($query->ingreso->periodo->periodo)->format('Y');
        });

        $presupuesto->each(function ($presupuesto, $presupuestoKey) use (&$dataset, &$labels) {
            $dataset->push($presupuesto->sum('total'));
            $labels->push($presupuestoKey);
        });

        return response()->json(['type' => 'array', 'items' => ['labels' => $labels, 'dataset' => $dataset], 'name' => 'ingresos']);
    }
}
