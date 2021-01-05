<?php

namespace App\Http\Requests;

use App\Models\Lista;
use App\Models\Seguidor;
use Illuminate\Foundation\Http\FormRequest;
use function PHPUnit\Framework\once;

class ActualizarListaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->creador();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'importador'    =>  'boolean',
            'agregar'       =>  'boolean',
            'email'         =>  'required|email:rfc,dns|exists:users,email',
            'lista'         =>  'required|exists:listas,id_li',
            'ver'           =>  'boolean',
        ];
    }

    public function creador()
    {
        return Seguidor::where('id_li',$this->input('lista'))
            ->where('id_us',auth()->id())
            ->where('creador_se',true)
            ->count();
    }
}
