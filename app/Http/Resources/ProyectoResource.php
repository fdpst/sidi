<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProyectoResource extends JsonResource
{
  public $preserveKeys = true;
  public function toArray($request)
  {
    return [
      // 'user_id'         => $request->user_id,
      // 'servicio_id'     => $request->servicio_id,
      // 'fecha_alta'      => $request->fecha_alta,
      // 'detalle_servicio'=> $request->detalle_servicio,
      // 'estado_id'       => $request->estado_id,
      // 'pvp'             => $request->pvp,
      // 'pvp_gasto'       => $request->pvp_gasto,
      // 'detalleGasto'    => $request->detalleGasto,
      // 'potencial'       => $request->potencial,
      // 'proyecto'        => $request->proyecto,
       
      'id'                    => $this->id,
      'fecha_alta'            => $this->fecha_alta,
      'detalle_servicio'      => $this->detalle_servicio,
      'estado_id'             => $this->estado->id,
      'pvp'                   => $this->pvp,
      'pvp_gasto'             => $this->pvp_gasto,
      'detalles_gasto'        => $this->detalles_gasto,      
        
      'usuario.id'            => $this->usuario->id,
      'usuario.user_id'       => $this->usuario->user_id,
      'usuario.nombre'        => $this->usuario->nombre,
      'usuario.nombre_fiscal' => $this->usuario->nombre_fiscal,
      'usuario.dni'           => $this->usuario->dni,
      'usuario.telefono'      => $this->usuario->telefono,
      'usuario.email'         => $this->usuario->email,
      'usuario.direccion'     => $this->usuario->direccion,
      'usuario.codigo_postal' => $this->usuario->codigo_postal,
      'usuario.localidad'     => $this->usuario->localidad,
      'usuario.provincia_id'  => $this->usuario->provincia_id,
      'usuario.fecha_alta'    => $this->usuario->fecha_alta,
      'usuario.observaciones' => $this->usuario->observaciones,

      'estado_id'             => $this->estado->id,                  
      'estado.nombre'         => $this->estado->nombre, 

      'servicio_id'           => $this->servicio->id,              
      'servicio.nombre'       => $this->servicio->nombre,
    ];
  }
}
