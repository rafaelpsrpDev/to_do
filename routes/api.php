<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TarefasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {

    Route::post('register',  'register');
    Route::post('login',  'login');
    Route::get('me', 'me');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::middleware(['auth'])->group(function() {
    Route::controller(TarefasController::class)->group(function() {
        Route::post('createTarefa', 'createTarefa');
        Route::get('tarefasUsuario', 'tarefasUsuario');
        Route::put('updateTarefaUser/{id}', 'updateTarefaUser');
        Route::delete('deleteTarefaUser/{id}', 'deleteTarefaUser');
    });
});
