<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Http\Requests\Users\UserRequest;
use App\Http\Requests\Users\UserUpdateRequest;

use App\Models\Proyecto;
use App\Models\EstadoProyecto;
use App\Http\Requests\ProyectoRequest;
use App\Http\Resources\ProyectoResource;
use App\Models\Estado;
use Validator;
use App\Helpers\FileHelper;

class ProyectoController extends Controller
{
  /*public function getAllProyectos(){
    $proyecto = Proyecto::with('servicio','estado','usuario','presupuesto')->where('estado_id', '=', '2')->orderBy('fecha_alta', 'DESC')->get();
    return response()->json($proyecto, 200);
  }*/
  public function getAllProyectos(){
    $proyecto = Proyecto::orderBy('created_at', 'DESC')->get();
    return response()->json($proyecto, 200);
  }

  /*public function getProyectoByid($proyecto_id){
    $proyecto = Proyecto::with(['servicio', 'usuario', 'estadosProyecto', 'archivos'])->find($proyecto_id);
    return response()->json($proyecto, 200);
  }*/

  public function getProyectoByid($proyecto_id){
    $proyecto = Proyecto::find($proyecto_id);
    return response()->json($proyecto, 200);
  }

  public function getProyectosByUserId($userid){
    $proyecto = Proyecto::where('usuario_id', '=', $userid)
                ->orderBy('created_at', 'DESC')->get();
    return response()->json($proyecto, 200);
  }
  public function deleteProyecto($proyecto_id){
    $proyecto = Proyecto::find($proyecto_id);
    $proyecto->delete();
    return response()->json($proyecto, 200);
  }

  public function saveProyecto(Request $request){
    $proyecto = Proyecto::updateOrcreate(['id' => $request->id], [
      'nombre'          => $request->nombre
    ]);

    $proyectos = Proyecto::orderBy('created_at', 'DESC')->get();
    return response()->json($proyectos, 200);
  }

  /*public function saveProyecto(ProyectoRequest $request){

    $proyecto = json_decode($request->proyecto, true);

    $itemsEstado = json_decode($request->itemsEstado, true);

    $idItemsEstado = json_decode($request->idItemsEstado, true);

    $idUser = $proyecto['usuario']['id'];

    $usuario = $proyecto['usuario'];

    if($idUser == null){
      $user = new User();
      $user->fill($usuario);
      $password            = Str::random(10);
      $user->password      = Hash::make($password);
      $user->role          = 2;
      //$user->avatar        = $proyecto['usuario']['avatar'];
      $user->saveOrFail();
    }else{
      $user = User::where('id', '=', $idUser)->first();
      $user->fill($usuario);
      $user->role = 2;
      $user->update();
    }

    $proyecto = Proyecto::updateOrcreate(['id' => $proyecto['id']], [
      'usuario_id'      => $user->id,
      'servicio_id'     => $proyecto['servicio_id'],
      'fecha_alta'      => $proyecto['fecha_alta'],
      'detalle_servicio'=> $proyecto['detalle_servicio'],
      'estado_id'       => $proyecto['estado_id'],
      'pvp'             => $proyecto['pvp'],
      'pvp_gasto'       => $proyecto['pvp_gasto'],
      'detalles_gasto'  => $proyecto['detalles_gasto'],
      'nombre'          => $proyecto['nombre'],
      'porc_realizado'  => $proyecto['porc_realizado'],
    ]);

    //save or update the project states corregir

    $items = [];

    if($itemsEstado != []) {
        foreach($itemsEstado as $key => $item) {
            $proyecto->estadosProyecto()->updateOrCreate(
                ['id' => $idItemsEstado[$key]],
                [
                    'descripcion' => $item['descripcion'],
                    'fecha'       => $item['fecha'],
                    'finalizado'  => $item['finalizado']
                ]
            );
            array_push($items, $item);
        }
    }

    if($request->hasFile('itemsFiles')){
       $fileHelper = new FileHelper($proyecto);
       $fileHelper->guardarArchivos($request->itemsFiles, 'proyecto');
    }

    $proyecto->load(['servicio', 'usuario', 'estadosProyecto', 'archivos']);

    return response()->json(['proyecto' => $proyecto], 200);
  }*/
}
