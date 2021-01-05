<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ApiController extends Controller
{
    public function buscarImg($hash=null){
        if(Storage::disk('productos')->exists($hash)){
            $archivo    =   Storage::disk('productos')->get($hash);
            $img        =   Image::make($archivo);
            return ($img)->response();
        }else{
            return abort(404);
        }
    }
}
