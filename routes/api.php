<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\authenticate\AuthenticateController;
use App\Http\Controllers\Api\forgot_password\ForgotPasswordController;
use App\Http\Controllers\Api\code_check\CodeCheckController;
use App\Http\Controllers\Api\egresos\GastosController;
use App\Http\Controllers\Api\egresos\GastosReporteController;
use App\Http\Controllers\Api\graficas\GraficasController;
use App\Http\Controllers\Api\reset_password\ResetPasswordController;
use App\Http\Controllers\Api\tipo_ingresos\TipoIngresosController;
use App\Http\Controllers\Api\ingresos\IngresosController;
use App\Http\Controllers\Api\lista_articulos\CategoriasController;
use App\Http\Controllers\Api\lista_articulos\EtiquetasController;
use App\Http\Controllers\Api\lista_articulos\ListaArticulosController;
use App\Http\Controllers\Api\presupuesto\PresupuestoController;
use App\Http\Controllers\Api\reglas\ReglaAplicadaPresupuestoController;
use App\Http\Controllers\Api\users\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
#PASSWORD RESET API's
Route::post('forgot_password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('check_code', [CodeCheckController::class, 'checkCode']);
Route::post('reset_password', [ResetPasswordController::class, 'resetPassword']);

#REGISTER AND LOGIN API's
Route::post('register', [AuthenticateController::class, 'signUp']);
Route::post('login', [AuthenticateController::class, 'logIn']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthenticateController::class, 'logOut']);
    Route::get('articulos_espesificos/{etiqueta_id}/{categoria_id}', [ListaArticulosController::class, 'getArticulos']);
    Route::post('regla_aplicada_presupuesto', [ReglaAplicadaPresupuestoController::class, 'getReglaAplicadaPresupuesto']);
    Route::get('gasto_reporte_usuario/{usuario_id}', [GastosReporteController::class, 'getGastoReportePorUsuario']);
    Route::post('grafica_gastos', [GraficasController::class, 'getGastosGrafica']);
    Route::get('grafica_ingresos', [GraficasController::class, 'getIngresosGrafica']);
    Route::post('gastos_x_periodo', [GastosController::class, 'getGastosPorperiodo']);
    Route::post('imagen_usuario', [UserController::class, 'loadImagen']);
    Route::delete('delete_imagen_usuario/{user_id}', [UserController::class, 'deleteUserImage']);

    Route::apiResource('gastos', GastosController::class);
    Route::apiResource('articulos', ListaArticulosController::class, ['index', 'show', 'store']);

    Route::apiResources(
        [
            'tipo_ingresos' => TipoIngresosController::class,
            'presupuesto' => PresupuestoController::class,
            'etiquetas' => EtiquetasController::class,
            'categorias' => CategoriasController::class,
        ],
        ['index', 'show']
    );
    Route::resource('ingresos', IngresosController::class);
});
