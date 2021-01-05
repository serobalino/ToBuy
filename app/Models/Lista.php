<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_li';

    protected $appends = ['route_li','code_li'];

    public function getrouteLiAttribute()
    {
        return route('lista.show',$this->attributes['id_li']);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class,'id_li','id_li');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class,Seguidor::class,'id_li','id_us','id_li','id');
    }

    public function getcodeLiAttribute()
    {
        return route('importer',md5($this->attributes['id_li']));
    }

    public function dueno()
    {
        return $this->usuarios()->where('creador_se',true);
    }
}
