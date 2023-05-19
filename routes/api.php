<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\authenticate\AuthenticateController;
use App\Http\Controllers\Api\forgot_password\ForgotPasswordController;
use App\Http\Controllers\Api\code_check\CodeCheckController;
use App\Http\Controllers\Api\reset_password\ResetPasswordController;
use App\Http\Controllers\Api\tipo_ingresos\TipoIngresosController;
use App\Http\Controllers\Api\ingresos\IngresosController;
use App\Http\Controllers\Api\plazos\PlazosController;
use App\Http\Controllers\Api\presupuesto\PresupuestoController;
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

#REGISTER ANS LOGIN API's
Route::post('register', [AuthenticateController::class, 'signUp']);
Route::post('login', [AuthenticateController::class, 'logIn']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthenticateController::class, 'logOut']);
    Route::apiResources(
        [
            'tipo_ingresos' => TipoIngresosController::class,
            'plazos' => PlazosController::class,
            'presupuesto' => PresupuestoController::class,
        ],
        ['index', 'show']
    );
    Route::resource('ingresos', IngresosController::class);
});
