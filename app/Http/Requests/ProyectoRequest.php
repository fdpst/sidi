<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyectoRequest extends FormRequest
{
    public function validationData(){
      return json_decode($this->proyecto, true);
    }

    public function authorize(){
        return true;
    }

    public function rules()
    {
        return [
          'servicio_id'           => 'required',
           'fecha_alta'            => 'required',
           'estado_id'             => 'required',
           'pvp'                   => 'required',
           'usuario.nombre'        => 'required',
           'usuario.nombre_fiscal' => 'required',
           'usuario.cif'           => 'required',
           'usuario.telefono'      => 'required',
           'usuario.email'         => 'required',
           'usuario.codigo_postal' => 'required',
           'usuario.cuenta'        => 'required',
        ];
    }

    public function messages()
    {
        return [
          'servicio_id.required'  => 'Servicio es obligatorio',
           'fecha_alta.required'    => 'Fecha es obligatorio',
           'estado_id.required'    => 'Estado es obligatorio',
           'pvp.required'          => 'Precio es obligatorio',
           'servicio_id.required'          => 'Servicio es obligatorio',
           'fecha_alta.required'           => 'Fecha alta',
           'estado_id.required'            => 'Estado',
           'usuario.nombre.required'       => 'Nombre es obligatorio',
           'usuario.nombre_fiscal.required'=> 'Nombre Fiscal es obligatorio',
           'usuario.dni.required'          => 'DNI es obligatorio',
           'usuario.telefono.required'     => 'Telefono es obligatorio',
           'usuario.email.required'       => 'Email es obligatorio', 
        ];
    }
}
