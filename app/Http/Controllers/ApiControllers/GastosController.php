<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gasto;
use App\Http\Requests\Gastos\StoreGastosRequest;
use App\Http\Requests\Gastos\UpdateGastosRequest;

use Illuminate\Support\Str;
use App\Traits\Files\HandlerFiles;
use Exception;
use Storage;
use File;

class GastosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $user_id)
    {
        if ($request->isMethod("GET")) {

            $gastos = Gasto::with('tiposgasto')->where('user_id', $user_id)->paginate();

            return response()->json([
                'status' =>  200,
                'message' => 'Succesfull',
                'gastos' => $gastos,
            ]);

        }else{

            return response()->json([
                'message' => 'Invalid Method'
            ]);
        }
    }

    protected function pathServer(){
        $PATH = $_SERVER['DOCUMENT_ROOT'];
        $pathPublicOut = explode('public',$PATH);
        $res = $pathPublicOut[0];
        return $res;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreGastosRequest $request)
    {
       $user_id = $request->user_id;
       $gasto = null;
       if ($request->isMethod("POST")) {
            try {
                $destination = $this->pathServer(). 'storage/app/public/documentos/userId_' .$user_id .'/factura_recibidas';
                $storeFile =  HandlerFiles::store($request, $destination);
                if(sizeof($storeFile->original['images']) >0){
                    foreach ($storeFile->original['images'] as $imageFile) {
                        list($fileName, $title) = $imageFile;
                        $split = explode("/", $fileName);
                        $lastFilename = end($split);
                        $split2 = explode('\\',$lastFilename);
                        $endFileName = end($split2);

                        $gasto = new Gasto;
                        $gasto->user_id = $request->user_id;
                        $gasto->proyecto_id = $request->proyecto_id;
                        $gasto->codigo = $request->codigo . '_'. Str::random(5);
                        $gasto->importe = $request->importe;
                        $gasto->descripcion = $request->descripcion;
                        $gasto->archivo = $endFileName;
                        $gasto->fecha = $request->fecha;
                        $gasto->tipo_gasto_id = $request->tipo_gasto_id;
                        $gasto->saveOrFail();
                        break;
                    }
                }else{
                    $gasto = new Gasto;
                    $gasto->user_id = $request->user_id;
                    $gasto->proyecto_id = $request->proyecto_id;
                    $gasto->codigo = $request->codigo . '_'. Str::random(5);
                    $gasto->importe = $request->importe;
                    $gasto->descripcion = $request->descripcion;
                    $gasto->archivo = null;
                    $gasto->fecha = $request->fecha;
                    $gasto->tipo_gasto_id = $request->tipo_gasto_id;
                    $gasto->saveOrFail();
                }


            } catch (Exception $e) {

                return response()->json(['error' => $e]);
            }
        }

        return response()->json([
            'status' =>  200,
            'message' => 'Save!',
            'gasto' => $gasto,
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getGastoById(Request $request, $id)
    {
        if ($request->isMethod("GET")) {

            $gasto = Gasto::where('id', $id)->first();


            return response()->json([
                'status' =>  200,
                'message' => 'Succesfull',
                'gasto' => $gasto,
            ]);

        }else{

            return response()->json([
                'message' => 'Invalid Method'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateGasto(Request $request)
    {
        $idGasto = $request->user_id;
        try {
            $destination = $this->pathServer(). 'storage/app/public/documentos/userId_' .$idGasto.'/factura_recibidas';
            $storeFile =  HandlerFiles::store($request, $destination);

            $gasto = Gasto::find($request->id);
            if(sizeof($storeFile->original['images']) > 0){
                foreach ($storeFile->original['images'] as $imageFile) {
                    list($fileName, $title) = $imageFile;
                    $split = explode("/", $fileName);
                    $lastFilename = end($split);
                    $split2 = explode('\\',$lastFilename);
                    $endFileName = end($split2);

                    $gasto->user_id = $request->user_id;
                    $gasto->proyecto_id = $request->proyecto_id;
                    //$gasto->codigo = $request->codigo;
                    $gasto->importe = $request->importe;
                    $gasto->descripcion = $request->descripcion;
                    $gasto->archivo = $endFileName;
                    $gasto->tipo_gasto_id = $request->tipo_gasto_id;
                    $gasto->fecha = $request->fecha;
                    $gasto->save();
                }
                //$gasto=">0";

            }else{
                $gasto->user_id = $request->user_id;
                $gasto->proyecto_id = $request->proyecto_id;
                //$gasto->codigo = $request->codigo;
                $gasto->importe = $request->importe;
                $gasto->descripcion = $request->descripcion;
                //$gasto->archivo = $endFileName;
                $gasto->tipo_gasto_id = $request->tipo_gasto_id;
                $gasto->fecha = $request->fecha;
                $gasto->save();
                //$gasto="0";
            }



        } catch (Exception $e) {
            return response()->json(['error' => $e]);
        }
        //$gasto = Gasto::find($request->id);
        //return $gasto->archivo;

        /*if ($request->isMethod("POST")) {
            try {
                $destination = $this->pathServer(). 'storage/app/public/documentos/userId_' .$idGasto.'/factura_recibidas';
                $storeFile =  HandlerFiles::store($request, $destination);

                $gasto = Gasto::find($request->id);
                if(sizeof($storeFile->original['images']) > 0){
                    foreach ($storeFile->original['images'] as $imageFile) {
                        list($fileName, $title) = $imageFile;
                        $split = explode("/", $fileName);
                        $lastFilename = end($split);
                        $split2 = explode('\\',$lastFilename);
                        $endFileName = end($split2);

                        $gasto->user_id = $request->user_id;
                        $gasto->codigo = $request->codigo . '_'. Str::random(5);
                        $gasto->importe = $request->importe;
                        $gasto->descripcion = $request->descripcion;
                        $gasto->archivo = $endFileName;
                        $gasto->tipo_gasto_id = $request->tipo_gasto_id;
                        $gasto->fecha = $request->fecha;
                        $gasto->save();
                    }
                    //$gasto=">0";

                }else{
                    $gasto->user_id = $request->user_id;
                    $gasto->codigo = $request->codigo. '_'. Str::random(5);
                    $gasto->importe = $request->importe;
                    $gasto->descripcion = $request->descripcion;
                    //$gasto->archivo = $endFileName;
                    $gasto->tipo_gasto_id = $request->tipo_gasto_id;
                    $gasto->fecha = $request->fecha;
                    $gasto->save();
                    //$gasto="0";
                }



            } catch (Exception $e) {
                return response()->json(['error' => $e]);
            }

        }*/

        return response()->json([
            'status' =>  200,
            'message' => 'Save!',
            'gasto' => $request->all(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gasto = Gasto::find($id);
        $gasto->delete();
        return response()->json($gasto, 200);
    }

     /*public function ocr(Request $request){


        //datos principales
        $user_id = $request->user_id*1;
        $pathServe = $this->pathServer();



        //Path ejecuta ocr
        $pathExeOcr = $pathServe . 'ocr_api/stack.py';
        //$path = $splitPathPrincipal[0] . 'ocr_api/ocr.py';


        //ruta guardar imagenes que se van a procesar
        $destination = $pathServe . 'storage/app/public/documentos/userId_'.$user_id.'/ocr/sin_procesar/';

        //ruta de archivo para pasar imagen a python
        $txtBase = $pathServe . 'storage/app/public/documentos';
        $archivoBase = $txtBase . '/' . 'procesarImagenes.txt';



        try {
            //guardar imagenes
            $store = HandlerFiles::store($request,  $destination);

            //crear archivo con rutas para procesar
            $nombresImagenes =  $store->original['nombresArchivos'];


            $archivo = fopen($archivoBase, "w+b");

            foreach ($nombresImagenes as $image) {

                fwrite($archivo, $destination . $image . "\r\n");

            }

            fflush($archivo);
            fclose($archivo);


            $command = escapeshellcmd($pathExeOcr);
            $output = shell_exec($command);


            return response()->json([

                "message" => "Documento guardado",
                'ocr' => utf8_decode($output)
            ]);
        } catch (Exception $e) {

        }



    }*/
}
