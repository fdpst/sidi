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
    return response()->json([ 'status' => 200, 'message' => 'Usuario Actualizado', 'user' => $user]);
  }

  public function saveUsuario(Request $request){

    $user_collet = collect(json_decode($request->usuario));
    $validate = Validator::make($user_collet->toArray(), [
        'nombre' => 'required',
        'password' => 'required',
        'email' => 'required',
        'role' => 'required',
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
    
    $user->nombre = $usuario->nombre;
    $user->email = $usuario->email;
    $user->password  = Hash::make($usuario->password);
    $user->role = $usuario->role;
    $user->saveOrFail();

    $email = $user->email;
    //Se envía mail luego de crear el usuario
    //Mail::to($email)->queue(new NewUserMail($user, $password));
    return response()->json($user, 200);
  }

  public function updateUsuario(UserUpdateRequest $request, $id){

    $usuario = json_decode($request->usuario);

    if($request->isMethod("POST")){
      $user = User::findOrFail($id);
      $user->nombre = $usuario->nombre;
      $user->email = $usuario->email;
      $user->role = $usuario->role;
      $user->update();

      //update user mail
      $email = $usuario->email;
      if (!isset($request->existeDatosEmpres)) {
        return 1;
        //Mail::to($email)->queue(new UpdateUser($user));
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
}
