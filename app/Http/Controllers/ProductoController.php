<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarProductoImportadorRequest;
use App\Http\Requests\ActualizarProductoRequest;
use App\Http\Requests\NuevoProductoRequest;
use App\Models\Lista;
use App\Models\Producto;
use App\Models\Seguidor;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
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
    public function store(NuevoProductoRequest $request)
    {
        $nueva              = new Producto();
        $nueva->id_us       = auth()->id();
        $nueva->id_li       = $request->lista;
        $nueva->detalle_pr  = urldecode($request->titulo);
        $nueva->estado_pr   = urldecode($request->estado);
        $nueva->rastreo_pr  = urldecode($request->codigo);
        $nueva->erastreo_pr = urldecode($request->codigoe);
        $nueva->fragil_pr   = urldecode($request->fragil);
        $nueva->url_pr      = $request->url ? mb_strtolower($request->url) : null;
        $nueva->pvp_pr      = $request->precio ? number_format($request->precio,2) : null;
        $nueva->libras_pr   = number_format($request->peso,1);
        $nueva->cant_pr     = urldecode($request->cantidad);
        $nueva->save();
        if ($request->imagen) {
            Storage::disk('productos')->putFileAs(
                '', $request->file('imagen'), hash('fnv132',$nueva->id_pr)
            );
        }
        return response()->json(['val'=>true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $idu = @auth()->id();
        $lista = Seguidor::with('lista.usuarios')->where('id_li',$id)->where('id_us',$idu)->first();

        return response()->json($lista);

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

    public function generarReporte(Request $datos) {
        $fecha = Carbon::now();
        $idu = @auth()->id();
        $lista = Seguidor::with('lista.usuarios')->where('id_li',$datos->id)->where('id_us',$idu)->first();
        $pdf = PDF::loadView('reports.precios', [
            'fecha'=> $fecha,
            'lista'=> $lista,
            'datos'=> $datos
        ]);
        return $pdf->stream('itsolutionstuff.pdf');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ActualizarProductoRequest $request, $id)
    {
        $nueva              = Producto::find($request->id);
        if(@$request->cambioEstado){
            $nueva->estado_pr = 'Entregado';
        }else{
            $nueva->detalle_pr  = urldecode($request->titulo);
            $nueva->estado_pr   = urldecode($request->estado);
            $nueva->rastreo_pr  = urldecode($request->codigo);
            $nueva->erastreo_pr = urldecode($request->codigoe);
            $nueva->fragil_pr   = urldecode($request->fragil);
            $nueva->url_pr      = $request->url ? mb_strtolower($request->url) : null;
            $nueva->pvp_pr      = $request->precio ? number_format($request->precio,2) : null;
            $nueva->libras_pr   = number_format($request->peso,1);
            $nueva->cant_pr     = urldecode($request->cantidad);
        }
        $nueva->save();
        return response()->json(['val'=>true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $nueva              = Producto::find($id);
        if($nueva){
            $nueva->delete();
            return response()->json(['val'=>true]);
        }else{
            return response()->json(['val'=>false],432);
        }
    }
}
