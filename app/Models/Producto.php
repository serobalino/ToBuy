<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_pr';

    protected $appends = ['img_pr','pvpxc_pr','estxc_pr','realxc_pr'];//imagen, precio x cantidad, estimado de enevio x cantidad, real de envio x cantidad

    protected $casts = [
        'librasR_pr' => 'float',
    ];

    public function dueno () {
        return $this->hasOne(User::class,'id','id_us');
    }
    public function getpvpxcPrAttribute(){
        if($this->attributes['pvp_pr']){
            return (float)number_format($this->attributes['pvp_pr']*$this->attributes['cant_pr'],2);
        }else{
            return 0;
        }
    }

    public function getestxcPrAttribute(){
        if($this->attributes['libras_pr']){
            return (float)number_format($this->attributes['libras_pr']*$this->attributes['cant_pr'],2);
        }else{
            return 0;
        }
    }

    public function getrealxcPrAttribute(){
        if($this->attributes['librasR_pr']){
            return (float)number_format($this->attributes['librasR_pr']*$this->attributes['cant_pr'],2);
        }else{
            return 0;
        }
    }

    public function getimgPrAttribute()
    {
        $hash   =   hash('fnv132',$this->attributes['id_pr']);
        $existe =   Storage::disk('productos')->exists($hash);
        if($existe){
            return route('img',$hash);
        }else{
            return $existe;
        }
    }
}
