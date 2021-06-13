<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/importador/{id}', [App\Http\Controllers\ImportadorController::class, 'index'])->name('importer');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home/{id}', [App\Http\Controllers\HomeController::class, 'show'])->name('lista.show');


Route::prefix('app')->group(function () {
    Route::resource('listas', App\Http\Controllers\ListaController::class);
    Route::resource('productos', App\Http\Controllers\ProductoController::class);
    Route::get('listaPrecios', [App\Http\Controllers\ProductoController::class,'generarReporte']);
});
