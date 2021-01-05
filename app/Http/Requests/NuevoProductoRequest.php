<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NuevoProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'cantidad'  =>  'required|string',
            'lista'     =>  'exists:listas,id_li',
            'imagen'    =>  'nullable:image',
        ];
    }
}
