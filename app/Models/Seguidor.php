<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguidor extends Model
{
    use HasFactory;

    protected $table = 'seguidores';

    protected $appends = ['productos_lista'];

    public function getproductosListaAttribute()
    {
        if($this->attributes['vall_se'] || $this->attributes['creador_se']){
            return $this->allProductos;
        }else{
            return $this->misProductos;
        }
    }

    public function misProductos ()
    {
        return $this->allProductos()->where('id_us','=',auth()->id())->orderByDesc('estado_pr')->orderByDesc('updated_at');
    }

    public function allProductos ()
    {
        return $this->hasMany(Producto::class,'id_li','id_li')->orderBy('updated_at','desc')->with('dueno');
    }

    public function lista ()
    {
        return $this->hasOne(Lista::class,'id_li','id_li');
    }
}
