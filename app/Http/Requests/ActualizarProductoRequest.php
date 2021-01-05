<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return @auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'titulo'    =>  'required|string',
            'fragil'    =>  'boolean',
            'peso'      =>  'nullable|numeric',
            'precio'    =>  'nullable|numeric',
            'url'       =>  'nullable|url',
            'codigoe'   =>  'nullable|string',
            'estado'    =>  'nullable|string',
            'codigo'    =>  'nullable|string',
            'lista'     =>  'exists:listas,id_li',
            'id'        =>  'exists:productos,id_pr',
        ];
    }
}
