<?php

namespace App\Http\Controllers\ApiControllers;

use App\Models\User;
use App\Mail\NewUserMail;
use App\Models\Provincia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\User\UpdateUser;
use App\Models\GestorDocumental;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Users\UserRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use Storage;
use App\Models\DraggableList;
use App\Traits\Files\HandlerFiles;
//use Dotenv\Validator;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{

  protected function pathServer(){
    $PATH = $_SERVER['DOCUMENT_ROOT'];
    $pathPublicOut = explode('public',$PATH);
    $res = $pathPublicOut[0];
    return $res;
  }
  public function getUsuarios(){
    $user = User::orderBy('created_at', 'DESC')->get();
    foreach ($user as  $value) {
      if ($value->role == 1) {
        $value['role'] = 'Administrador';
      }
      if ($value->role == 2) {
        $value['role'] = 'Cliente';
      }
      if ($value->role == 3) {
        $value['role'] = 'Empleado';
      }
    }
    return response()->json([ 'status' => 200, 'message' => 'Ok', 'users' => $user]);
  }
  public function getUsuariosEmpleados(){
    $user = User::where('role', 1)->orWhere('role', 3)->orderBy('created_at', 'DESC')->paginate(15);

    return response()->json([ 'status' => 200, 'message' => 'Ok', 'users' => $user]);
  }
  public function getUsuariosClientes(){
    $user = User::where('role', 2)->orderBy('created_at', 'DESC')->get();

    return response()->json([ 'status' => 200, 'message' => 'Ok', 'users' => $user]);
  }
  public function getUsuarioByid($user_id){
    $user = User::find($user_id);
    $provincias = Provincia::orderBy('nombre')->get(['id', 'nombre']);
    return response()->json([ 'status' => 200, 'message' => 'Usuario Actualizado', 'user' => $user, 'provincias' => $provincias ]);
  }

  public function saveUsuario(Request $request){

    /*$validate = $request->validate([
        'usuario.nombre' => 'required',
        'usuario.nombre_fiscal' => 'required',
        'usuario.cif' => 'required|max:9',
        'usuario.telefono' => 'required|max:9',
        'usuario.email' => 'required',
        'usuario.role' => 'required',
        'usuario.provincia_id' => 'required',
        'usuario.localidad' => 'required|max:60',
        'usuario.codigo_postal' => 'required|numeric',
    ]);*/

    /*if(array_key_exists("message", $validate)) {
        return response()->json([
            'error' => $validate
        ], 422);
    }*/

    $user_collet = collect(json_decode($request->usuario));
    $validate = Validator::make($user_collet->toArray(), [
        'nombre' => 'required',
        'nombre_fiscal' => 'required',
        'cif' => 'required|max:9',
        'telefono' => 'required|max:9',
        'email' => 'required',
        'role' => 'required',
        'provincia_id' => 'required',
        'localidad' => 'required|max:60',
        'codigo_postal' => 'required|numeric',
    ]);

    if ($validate->fails()) {
        return response()->json([
            'errors' => $validate->errors()
        ], 422);
    }

    $usuario = json_decode($request->usuario);

    if($usuario->id == 0 || $usuario->id == null ||  $usuario->id == '' ){
      $user = new User();
    }else{
      $user = User::where('id','=',$usuario->id)->first();
      return response()->json($user, 200);
    }
    $destination = $this->pathServer() . 'storage/app/public/users/userId_' . $user->id . '/';
    $store = HandlerFiles::store($request, $destination);
    $this->crearCarpetasPrinciales($user->id);

    $user->user_id = $usuario->user_id;
    $user->nombre = $usuario->nombre;
    $user->nombre_fiscal = $usuario->nombre_fiscal;
    $user->cif = $usuario->cif;
    $user->telefono = $usuario->telefono;
    $user->email = $usuario->email;
    $user->direccion = $usuario->direccion;
    $user->codigo_postal = $usuario->codigo_postal;
    $user->localidad = $usuario->localidad;
    $user->provincia_id = $usuario->provincia_id;
    $user->fecha_alta = $usuario->fecha_alta;
    $user->observaciones =  strlen($usuario->observaciones) > 0 ? $usuario->observaciones : '';
    $user->cuenta = $usuario->cuenta;
    $password = Str::random(10);
    $user->password  = Hash::make($password);
    $user->role = $usuario->role;
    $user->avatar = $usuario->avatar;
    $user->saveOrFail();

    $email = $user->email;
    //Se envía mail luego de crear el usuario
    Mail::to($email)->queue(new NewUserMail($user, $password));
    return response()->json($user, 200);
  }

  public function updateUsuario(UserUpdateRequest $request, $id){

    $usuario = json_decode($request->usuario);

    if($request->isMethod("POST")){
      $user = User::findOrFail($id);
      $user->user_id = $usuario->user_id;
      $user->nombre = $usuario->nombre;
      $user->nombre_fiscal = $usuario->nombre_fiscal;
      $user->cif = $usuario->cif;
      $user->telefono = $usuario->telefono;
      $user->email = $usuario->email;
      $user->direccion = $usuario->direccion;
      $user->codigo_postal = $usuario->codigo_postal;
      $user->localidad = $usuario->localidad;
      $user->provincia_id = $usuario->provincia_id;
      $user->fecha_alta = $usuario->fecha_alta;
      $user->observaciones = strlen($usuario->observaciones) > 0 ? $usuario->observaciones : '';
      $user->cuenta = $usuario->cuenta;
      $user->role = $usuario->role;
      $user->avatar = $usuario->avatar;
      $user->update();

      $destination = $this->pathServer() . '/laravel/storage/app/public/users/userId_' . $user->id . '/';
      $store = HandlerFiles::store($request,  $destination);

      //update user mail
      $email = $usuario->email;
      if (!isset($request->existeDatosEmpres)) {
        return 1;
        Mail::to($email)->queue(new UpdateUser($user));
      }
      return response()->json([ 'status' => 200, 'message' => 'Usuario Actualizado', 'user' => $user ]);
    }else{
      return response()->json([ 'status' => 400, 'message' => 'Método no permitido', ]);
    }
  }
  public function deleteUsuario($user_id){
    $user = User::find($user_id);
    $user->delete();
    return response()->json($user, 200);
  }
  //datos que se necesitaran en el formulario
  public function getMethodsForm(){
    $provincias = Provincia::orderBy('nombre')->get(['id', 'nombre']);
    return response()->json([ 'status' => 200, 'message' => 'Ok', 'provincias' => $provincias ]);
  }
  public function crearCarpetasPrinciales($user_id){
      $principal = ["documentacion_general", "factura", "factura_recibidas"];
      foreach ($principal as $value) {
        Storage::makeDirectory('public/documentos/userId_'.$user_id .'/' . $value);
      }
        Storage::makeDirectory('public/users/userId_'.$user_id);
  }
  public function crearDragPrincipales($user_id){
    $status = ['Pendientes', 'En Progreso', 'Finalizadas'];
    foreach ($status as $value) {
        $gD = new  DraggableList;
        $gD->user_id = $user_id;
        $gD->drag = $value;
        $gD->newTask = false;
        $gD->save();
    }
  }

  // public function getAllUsuarios(){
  //   $usuarios = UserResource::collection(Usuario::orderBy('nombre', 'ASC')->get());
  //   return response()->json($usuarios, 200);
  // }

  // public function getUsuarios($user_id){
  //   $usuarios = UserResource::collection(Usuario::orderBy('created_at', 'DESC')->where('user_id', '=', $user_id)->get());
  //   return response()->json($usuarios, 200);
  // }

  // public function getUsuarioByid($usuario_id){
  //   $usuario = Usuario::find($usuario_id);
  //   return response()->json($usuario, 200);
  // }

  // public function saveUsuario(UserRequest $request){
  //   $usuario = Usuario::updateOrCreate(['id' => $request->id], $request->all());
  //   return response()->json($usuario, 200);
  // }

  // public function deleteUsuario($usuario_id){
  //   $usuario = Usuario::find($usuario_id);
  //   $usuario->delete();
  //   return response()->json($usuario, 200);
  // }
}
