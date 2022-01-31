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
  public function getAllProyectos(){
    $proyecto = Proyecto::with('servicio','estado','usuario','presupuesto')->where('estado_id', '=', '2')->orderBy('fecha_alta', 'DESC')->get();
    return response()->json($proyecto, 200);
  }

  public function getProyectoByid($proyecto_id){
    $proyecto = Proyecto::with(['servicio', 'usuario', 'estadosProyecto', 'archivos'])->find($proyecto_id);
    return response()->json($proyecto, 200);
  }
  public function getProyectosByUserId($userid){
    $proyecto = Proyecto::with('servicio','estado','usuario','presupuesto')
                ->where('estado_id', '=', '2')
                ->where('usuario_id', '=', $userid)
                ->orderBy('fecha_alta', 'DESC')->get();
    return response()->json($proyecto, 200);
  }
  public function deleteProyecto($proyecto_id){
    $proyecto = Proyecto::find($proyecto_id);
    $proyecto->delete();
    return response()->json($proyecto, 200);
  }



  public function saveProyecto(ProyectoRequest $request){

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
  }

  public function getAllPotenciales(){
    $potencial = Proyecto::with('servicio','estado','usuario', 'presupuesto')->where('estado_id', '=', '3')->orderBy('fecha_alta', 'DESC')->get();
    return response()->json($potencial, 200);
  }

  public function getPotencialByid($potencial_id){
    $potencial = Proyecto::with(['servicio','usuario', 'archivos'])->find($potencial_id);
    return response()->json($potencial, 200);
  }

  public function deletePotencial($potencial_id){
    $potencial = Proyecto::find($potencial_id);
    $potencial->delete();
    return response()->json($potencial, 200);
  }

  public function savePotencial(Request $request){
    $potencial =  json_decode($request->potencial, true);

    $usuario = $potencial['usuario'];

    $idUser = $usuario['id'];

    if($idUser == null){
      $user = new User();
      $user->user_id       = $usuario['user_id'];
      $user->nombre        = $usuario['nombre'];
      $user->nombre_fiscal = $usuario['nombre_fiscal'];
      $user->cif           = $usuario['cif'];
      $user->telefono      = $usuario['telefono'];
      $user->email         = $usuario['email'];
      $user->direccion     = $usuario['direccion'];
      $user->codigo_postal = $usuario['codigo_postal'];
      $user->localidad     = $usuario['localidad'];
      $user->provincia_id  = $usuario['provincia_id'];
      $user->fecha_alta    = $usuario['fecha_alta'];
      $user->observaciones = $usuario['observaciones'];
      $user->cuenta        = $usuario['cuenta'];
      $password            = Str::random(10);
      $user->password      = Hash::make($password);
      $user->role          = 2;//$usuario['role'];
      $user->avatar        = $usuario['avatar'];
      $user->saveOrFail();
    }else{
      $user = User::where('id', '=', $idUser)->first();
      $user->user_id       = $usuario['user_id'];
      $user->nombre        = $usuario['nombre'];
      $user->nombre_fiscal = $usuario['nombre_fiscal'];
      $user->cif           = $usuario['cif'];
      $user->telefono      = $usuario['telefono'];
      $user->email         = $usuario['email'];
      $user->direccion     = $usuario['direccion'];
      $user->codigo_postal = $usuario['codigo_postal'];
      $user->localidad     = $usuario['localidad'];
      $user->provincia_id  = $usuario['provincia_id'];
      $user->fecha_alta    = $usuario['fecha_alta'];
      $user->observaciones = $usuario['observaciones'];
      $user->cuenta        = $usuario['cuenta'];
      $user->role          = 2;
      $user->avatar        = $usuario['avatar'];
      $user->update();
    }

    $potencial_guardado = Proyecto::updateOrcreate(['id' => $potencial['id']], [
      'usuario_id'       => $user->id,
      'servicio_id'      => $potencial['servicio_id'],
      'fecha_alta'       => $potencial['fecha_alta'],
      'detalle_servicio' => $potencial['detalle_servicio'],
      'estado_id'        => $potencial['estado_id'],
      'pvp'              => $potencial['pvp'],
      'pvp_gasto'        => $potencial['pvp_gasto'],
      'detalles_gasto'   => $potencial['detalles_gasto']
    ]);

    if($request->hasFile('itemsFiles')){
       $fileHelper = new FileHelper($potencial_guardado);
       $fileHelper->guardarArchivos($request->itemsFiles, 'potencial');
    }

    return response()->json(['potencial' => $potencial_guardado, 'user' => $user], 200);
  }

  public function updateProyectoEstado($proyecto_estado_id) {
    $estado = EstadoProyecto::find($proyecto_estado_id);
    $estado->finalizado = !$estado->finalizado;
    $estado->save();
    return response()->json('Estado actualizado', 200);
  }

  public function deleteEstadoProyecto($proyecto_estado_id) {
    $estado = EstadoProyecto::find($proyecto_estado_id);
    $estado->delete();
    return response()->json($estado, 200);
  }

  public function deleteFile($tipo, $id){
    $fileHelper = new FileHelper(new Proyecto());
    $fileHelper->deleteFile($id, $tipo);
    return response()->json(['status' => 'success'], 200);
  }
}
