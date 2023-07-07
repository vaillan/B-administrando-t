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
use App\Models\egresos\GastoReporte;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Api\presupuesto\PresupuestoController;
use Carbon\Carbon;
use Validator;

class IngresosController extends Controller
{
    protected $presupuestoController;

    public function __construct()
    {
        $this->presupuestoController = new PresupuestoController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ingresos = Ingreso::where('usuario_id', Auth::id())->get();
        return response()->json(['type' => 'array', 'items' => $ingresos, 'name' => 'ingresos']);
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
            $user_id = Auth::id();
            $request['periodo'] = Carbon::parse($request->input('periodo'))->format('Y-m');
            $validator = Validator::make($request->all(), [
                'ingreso' => 'required',
                'tipo_ingreso_id' => 'required',
                'periodo' => [
                    'required',
                    Rule::unique('periodos')->where(function ($query) use ($request, $user_id) {
                        return $query->where('created_by', $user_id)->where('periodo', $request->periodo)->whereNull('deleted_at');
                    })
                ]
            ]);

            if ($validator->fails()) {
                return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
            }


            $montoIngreso = (int) filter_var($request->input('ingreso'), FILTER_SANITIZE_NUMBER_INT);
            $ingresoData = $request->all();
            $ingresoData['ingreso'] = $montoIngreso;
            $ingresoData['usuario_id'] = $user_id;

            $ingreso = Ingreso::create($ingresoData);

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
        $query = DB::transaction(function () use ($request, $id) {
            $user_id = Auth::id();
            $ingreso = Ingreso::find($id);
            $nuevoMontoIngreso = (int) filter_var($request->input('ingreso'), FILTER_SANITIZE_NUMBER_INT);
            $montoRealIngreso = $ingreso->ingreso;
            $presupuesto = Presupuesto::where('ingreso_id', $ingreso->id)->first();
            $diferencia = $montoRealIngreso - $nuevoMontoIngreso;
            if ($diferencia < 0) {
                $diferencia = $diferencia * -1;
            }
            $diferencia = $montoRealIngreso > $nuevoMontoIngreso ? -$diferencia : $diferencia;
            $ingreso->ingreso += $diferencia;
            $presupuesto->total += $diferencia;
            $ingreso->save();
            $presupuesto->save();
            $this->aplicarRegla($presupuesto, $user_id, $diferencia);
            $_presupuesto = json_decode($this->presupuestoController->show($user_id)->getContent());
            return response()->json($_presupuesto);
        });
        return $query;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = DB::transaction(function () use ($id) {
            $ingreso = Ingreso::with(['periodo', 'presupuesto' => function ($query) {
                $query->with(['reglaAplicadaPresupuesto' => function ($query) {
                    $query->with(['gastosReporte' => function ($query) {
                        $query->with(['gasto' => function ($query) {
                            $query->with('periodo');
                        }]);
                    }]);
                }]);
            }])->find($id);

            $ingreso->presupuesto->reglaAplicadaPresupuesto->each(function ($reglaAplicada) {
                $reglaAplicada->gastosReporte->each(function ($gastoReporte) {
                    $gastoReporte->delete();
                    $gastoReporte->gasto->periodo->delete();
                    $gastoReporte->gasto->delete();
                });
                $reglaAplicada->delete();
            });

            $ingreso->presupuesto->delete();
            $ingreso->periodo->delete();
            $ingreso->delete();

            return response()->json(['type' => 'Object', 'items' => ['message' => 'Ingreso eliminado correctamente']]);
        });
        return $query;
    }

    private function aplicarRegla($presupuesto, $user_id, $_diferencia = 0)
    {
        $reglas = Regla::all();
        $reglas->each(function ($regla) use ($presupuesto, $user_id, $_diferencia) {
            $reglaActualizada = 0;
            $reglaPresupuestoTotal = ($presupuesto->total * ($regla->porcentaje / 100));
            $reglaPresupuesto = ReglaAplicadaPresupuesto::where('regla_id', $regla->id)
            ->where('presupuesto_id' , $presupuesto->id)->first();
            if(isset($reglaPresupuesto)) {
                $reglaActualizada = $reglaPresupuesto->total + ($_diferencia * ($regla->porcentaje / 100));
                $reglaPresupuestoTotal = $reglaActualizada;
            }

            ReglaAplicadaPresupuesto::updateOrCreate(
                ['regla_id' => $regla->id, 'presupuesto_id' => $presupuesto->id],
                [
                    'total' => $reglaPresupuestoTotal,
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]
            );
        });
    }

    private function periodo(Request $request, $ingreso, $user_id)
    {
        $periodoData = $request->all();
        $periodoData['ingreso_id'] = $ingreso->id;
        $periodoData['created_by'] = $user_id;
        $periodoData['updated_by'] = $user_id;
        Periodo::create($periodoData);
    }

    /**
     * 
     * @param $ingreso
     * @param $user_id
     * @return App\Models\presupuesto\Presupuesto
     */
    private function presupuesto($ingreso, $user_id)
    {
        $presupuestoData = [
            'total' => $ingreso->ingreso,
            'ingreso_id' => $ingreso->id,
            'usuario_id' => $user_id,
        ];
        $presupuesto = Presupuesto::create($presupuestoData);
        return $presupuesto;
    }
}
