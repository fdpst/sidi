<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            // 'nombre' => 'required',
            // 'nombre_fiscal' => 'required',
            // 'cif' => 'required|max:9',
            // 'telefono' => 'required|max:9',
            // 'email' => 'required|unique:users',
            // 'role' => 'required',
            // 'provincia_id' => 'required', 
            // 'localidad' => 'required|max:60',
            // 'codigo_postal' => 'numeric|digits_between:1,5|nullable',
        ];
    }

    public function messages(){
        return [
            'nombre.required' => 'El campo nombre es requerido',
            'email.required' => 'El campo email es requerido',
            'role.required' => 'El campo rol es requerido',
        ];
    }
}