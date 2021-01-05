<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarListaImportador;
use App\Http\Requests\ActualizarProductoImportadorRequest;
use App\Models\Lista;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ImportadorController extends Controller
{

    public function index($id)
    {
        $lista = Lista::where(DB::raw('md5(id_li)'),$id)->first();
        if($lista){
            return view('importador',['id'=>$id]);
        }else{
            return abort(404);
        }
    }

    public function show($id)
    {
        $lista = Lista::with(['productos','dueno'])->where(DB::raw('md5(id_li)'),$id)->first();
        return response()->json($lista);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ActualizarProductoImportadorRequest $request)
    {
        $producto   =   Producto::find($request->id);
        if($request->estado){
            $producto->estado_pr    =    'Entregado en USA';
        }

        if($request->peso){
            $producto->librasR_pr   =   number_format($request->peso,2);
            $producto->estado_pr    =   'Recibido en Ecuador';
        }
        $producto->save();
        return response()->json(['val'=>true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateList(ActualizarListaImportador $request)
    {
        $lista                  =   Lista::find($request->lista);
        $lista->precioxl_li     =   number_format($request->precio,2);
        $lista->enviolocal_li   =   number_format($request->envio,2);
        $lista->save();
        return response()->json(['val'=>true]);
    }
}
