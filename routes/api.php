<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('importer')->group(function () {
    Route::get('listas/{id}', [App\Http\Controllers\ImportadorController::class,'show']);
    Route::post('productos', [App\Http\Controllers\ImportadorController::class,'update']);
    Route::post('listas', [App\Http\Controllers\ImportadorController::class,'updateList']);
});


Route::get('/image/{hash}',[App\Http\Controllers\ApiController::class, 'buscarImg'])->name('img');
