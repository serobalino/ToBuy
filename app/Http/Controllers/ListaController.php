<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarListaRequest;
use App\Http\Requests\NuevaListaRequest;
use App\Models\Lista;
use App\Models\Seguidor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $lista = Lista::rightJoin('seguidores','listas.id_li','=','seguidores.id_li')
            ->where('id_us',auth()->id())
            ->orderBy('listas.updated_at','desc')
            ->get();
        return response()->json($lista);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NuevaListaRequest $request)
    {
        DB::beginTransaction();
        try {
            $nueva              = new Lista();
            $nueva->titulo_li   = $request->titulo;
            $nueva->precioxl_li = $request->precio ? number_format($request->precio,2) : null;
            $nueva->save();

            $dueno              = new Seguidor();
            $dueno->id_us       = auth()->id();
            $dueno->id_li       = $nueva->id_li;
            $dueno->creador_se  = true;
            $dueno->save();
            DB::commit();
            return response()->json(['val'=>true]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['val'=>false,'error' => $ex->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ActualizarListaRequest $request, $id)
    {
        $usuario = User::where('email',$request->email)->first();
        $colaborador            =   new Seguidor();
        $colaborador->id_us     = $usuario->id;
        $colaborador->id_li     = $request->lista;
        $colaborador->add_se    = $request->agregar;
        $colaborador->vall_se   = $request->ver;
        $colaborador->contra_se = $request->importador;
        $colaborador->save();
        return response()->json(['val'=>true]);
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
