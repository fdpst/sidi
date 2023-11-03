<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tareas;
use App\Models\TiposTarea;
use App\Models\User;
use App\Models\Proyecto;
use App\Traits\Basic;
use Carbon\Carbon;
use App\Http\Resources\FichajeResource;

class TareasController extends Controller
{
    public function saveTarea(Request $request){
        $tarea = Tareas::updateOrCreate(['id' => $request->id], $request->all());
        
        $tareas = Tareas::where('id_usuario', $request->id_usuario)->where('fecha', $request->fecha)->get();
        foreach ($tareas as $tarea) {
            $proyecto = Proyecto::find($tarea->id_proyecto);
            $tarea->nombre_proyecto = $proyecto->nombre;
            $tarea->fecha = Carbon::createFromFormat('Y-m-d', $tarea->fecha)->format('d-m-Y');
         }
        
        return response()->json($tareas, 200);
    }
    
    public function saveTipoTarea(Request $request){
        $tarea = TiposTarea::updateOrCreate(['id' => $request->id], $request->all());
        
        $tareas = TiposTarea::get();
        
        return response()->json($tareas, 200);
    }

    public function getTareas(Request $request){
        $tareas = Tareas::where('id_usuario', $request->id_usuario)->where('fecha', $request->fecha)->with('empleado', 'tipoTarea', 'proyecto')->get();
        foreach ($tareas as $tarea) {
            $proyecto = Proyecto::find($tarea->id_proyecto);
            $tarea->nombre_proyecto = $proyecto->nombre;
            $tarea->fecha = Carbon::createFromFormat('Y-m-d', $tarea->fecha)->format('d-m-Y');
         }
        return response()->json($tareas, 200);
    }

    public function getTiposTarea(Request $request){
        $tiposTarea = TiposTarea::get();
        
        return response()->json($tiposTarea, 200);
    }

    public function buscarTareas(Request $request){
        $tareas = Tareas::where(function ($query) use ($request) {
            if($request->usuario!=null){
                $query->where('id_usuario', $request->usuario);
            }
            if($request->proyecto!=null){
                $query->where('id_proyecto', $request->proyecto);
            }
            if($request->fecha_inicio!=""){
                $query->where('fecha', '>=', $request->fecha_inicio);
            }
            if($request->fecha_fin!=""){
                $query->where('fecha', '<=', $request->fecha_fin);
            }
        })->orderBy('fecha', 'ASC')->get();
      
        foreach ($tareas as $tarea) {
            $proyecto = Proyecto::find($tarea->id_proyecto);
            $usuario = User::find($tarea->id_usuario);
            $tarea->nombre_proyecto = $proyecto->nombre;
            $tarea->nombre_usuario = $usuario->nombre;
            $tipo_tarea = TiposTarea::find($tarea->id_tipo_tarea);
            $tarea->tipo_tarea = $tipo_tarea->nombre;
            $tarea->fecha = Carbon::createFromFormat('Y-m-d', $tarea->fecha)->format('d-m-Y');
         }
        return response()->json($tareas, 200);
    }

    public function eliminarTarea($tarea_id){
        $tarea = Tareas::find($tarea_id);
        $tarea->delete();
        return response()->json($tarea, 200);
    }
}