<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FichajeResource extends JsonResource
{
    public function toArray($request)
    {
      return [
        'nombre'      => $this->nombre_usuario,
        'fecha_fichaje' => $this->fecha_fichaje,
        'hora_fichaje' => $this->hora_fichaje,
      ];
    }
}