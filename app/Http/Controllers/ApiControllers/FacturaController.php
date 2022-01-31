<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Resources\FacturaResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\NroFactura;
use App\Models\Recibo;
use Storage;
use App\Models\Proyecto;
use App\Models\Albaranes\AlbaranesEnviado;
use App\Models\Factura;
use App\Models\ItemsFactura;
use PDF;
use Validator;

class FacturaController extends Controller
{
  public function getFacturas($user_id){
    $facturas = Recibo::whereHas('nro_factura')->with(['nro_factura' => function($query){
        $query->orderBy('nro_factura', 'ASC');
    }])->where('user_id', '=', $user_id)->orderBy('id', 'DESC')->get();

    $facturas_resource = FacturaResource::collection($facturas);

    return response()->json($facturas_resource, 200);
  }

  public function deleteFactura($factura_id){
    $factura = Recibo::find($factura_id);

    $nro_factura = NroFactura::get(['id'])->last();

    if($factura->nro_factura['id'] == $nro_factura->id){

      if(Storage::disk('recibos')->exists($factura->factura_url)){
         Storage::disk('recibos')->delete($factura->factura_url);
      }

      $factura->nro_factura()->delete();

      if($factura->nro_presupuesto()->exists()){
         $factura->factura_url = null;
         $factura->save();
         return response()->json('factura eliminada', 200);
      }

      $factura->servicios()->delete();
      $factura->delete();
      return response()->json('factura eliminada', 200);
    }

    return response()->json('no se puede eliminar', 301);
  }


  public function getDataAlbaranes(Request $request, $user_id){

    $albaranesEnviados = AlbaranesEnviado::with('cliente')
                                          ->where('user_id', '=', $user_id)
                                          ->where('contabilizado', '=', null)
                                          ->orderBy('created_at', 'DESC')
                                          ->get();

      return response()->json([
        'status' => 200,
        'albaranesEnviados' => $albaranesEnviados,

      ]);

  }

  public function indexFactura(){

    $factura = Factura::with('items_factura','proyecto.usuario')->orderBy('fecha', 'desc')->get();
    return response()->json($factura, 200);
  }

  public function showFactura($factura_id){
    $factura = Factura::find($factura_id);

    if($factura == null) {
        return response()->json('factura no existe', 400);
    }

    $factura = Factura::where('id', $factura_id)->with('items_factura')->first();
    return response()->json($factura, 200);
  }

  public function storeFactura(Request $request){

    $validator = Validator::make($request->all(), [
        'id_proyecto' => 'required',
        'items_factura' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error'=>$validator->errors()
        ], 422);
    }

    $count_proyecto = Proyecto::where('id', $request->id_proyecto)->count();

    if($count_proyecto == 0) {
        return response()->json('Proyecto no existe', 400);
    }

    $factura_count = Factura::latest('id')->first();

    if ($factura_count){
      $request['nro_factura'] = $factura_count['id'] + 1;
    }else{
      $request['nro_factura'] = 1;
    }
    

    $proyecto = Proyecto::where('id', $request->id_proyecto)
                            ->with('usuario')
                            ->first();

    $data_report = $this->generateReports('factura', $request->all(), $proyecto);

    $request['url'] = $data_report['url'];
    $request['file_name'] = $data_report['path'];

    // return $request->all();

    $factura = Factura::create($request->except('items_factura'));

    $factura->items_factura()->createMany($request['items_factura']);

    return response()->json('Registrado con exito', 200);

  }

  public function updateFactura($factura_id, Request $request){
    $validator = Validator::make($request->all(), [
        'id_proyecto' => 'required',
        'items_factura' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error'=>$validator->errors()
        ], 422);
    }

    $count_proyecto = Proyecto::where('id', $request->id_proyecto)->count();

    if($count_proyecto == 0) {
        return response()->json('Proyecto no existe', 400);
    }

    $factura_count = Factura::where('id_proyecto',$request->id_proyecto)
                                ->first();

    $request['nro_factura'] = $factura_count['nro_factura'];

    $proyecto = Proyecto::where('id', $request->id_proyecto)
                            ->with('usuario')
                            ->first();

    $data_report = $this->generateReports('factura', $request->all(), $proyecto);

    $request['url'] = $data_report['url'];
    $request['file_name'] = $data_report['path'];

    // return $request->all();

    $factura = Factura::where('id', $factura_id)
                ->update($request->except('items_factura'));

    foreach ($request['items_factura'] as $item) {

        ItemsFactura::updateOrCreate(
            ['id' => $item['id']],
            [
                'id_factura' => $factura_id,
                'descripcion' => $item['descripcion'],
                'cantidad' => $item['cantidad'],
                'importe' => $item['importe'],
                'precio' => $item['precio'],
            ]);
    }

    return response()->json('editado con exito', 200);

  }

  public function deleteItemFactura(Request $request) {
    $items_factura = ItemsFactura::whereIn('id', $request->id_items_factura)->count();

     if($items_factura == 0) {
        return response()->json('No existe Items', 400);
    }

    ItemsFactura::destroy($request->id_items_factura);

    return response()->json('eliminado con exito', 200);
  }

  public function generateReports($file_config, $report_pdf, $proyecto)
	{
        $pdf = PDF::setPaper('A4', 'portrait');

        $data = $pdf->loadView('pdf.facturas', compact('report_pdf','proyecto'))->output();

        $report_name = 'reporte_'.$file_config.'_'.uniqid().'.pdf';

        $path_file_name = 'reports-all/'.$file_config.'/'.$report_name;

        Storage::disk('public')->put($path_file_name, $data);

        $url = config('app.url').'/storage/'.$path_file_name.'#toolbar=0';

        $str_path = str_replace('/', DIRECTORY_SEPARATOR, $path_file_name);

        // $path = Storage::disk('public')->path($str_path);

        return [
            'url'  => $url,
            'path' => $str_path
        ];
	}
}
