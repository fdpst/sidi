<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fichar;
use App\Models\User;
use App\Traits\Basic;
use Carbon\Carbon;
use App\Http\Resources\FichajeResource;

class FicharController extends Controller
{
    public function fichar($id){

        $fecha = Carbon::now()->format('Y-m-d H:i:s');
        $fichar = new Fichar();
        $fichar->usuario_id = $id;
        $fichar->fecha = $fecha;
        $fichar->saveOrFail();
        return response()->json('enviado con exito', 200);
    }

    public function getFichajes(Request $request){
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $usuario = false;
        if ($request->usuario != null){
            $usuario = true;
        }
        if ($usuario){
            $fichajes = Fichar::where('usuario_id', $request->usuario)->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
        }else{
            $fichajes = Fichar::whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
        }

        foreach ($fichajes as $fichaje) {
           $usuario = User::find($fichaje->usuario_id);
           $fichaje->nombre_usuario = $usuario->nombre;
           $fichaje->fecha_fichaje = Carbon::parse($fichaje->fecha)->format('d-m-Y');
           $fichaje->hora_fichaje = Carbon::parse($fichaje->fecha)->format('H:i:s');
        }

        return response()->json(FichajeResource::collection($fichajes), 200);
    }

    public function getFichajesByUser(Request $request, $id){
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        
        $fichajes = Fichar::where('usuario_id', $id)->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
        
        foreach ($fichajes as $fichaje) {
           $usuario = User::find($fichaje->usuario_id);
           $fichaje->nombre_usuario = $usuario->nombre;
           $fichaje->fecha_fichaje = Carbon::parse($fichaje->fecha)->format('d-m-Y');
           $fichaje->hora_fichaje = Carbon::parse($fichaje->fecha)->format('H:i:s');
        }

        return response()->json(FichajeResource::collection($fichajes), 200);
    }

}
