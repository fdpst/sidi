<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Http\Requests\IngresoRequest;
use App\Models\Ingreso;
use App\Models\Deuda;
use App\Models\NroFactura;
use App\Models\NroNota;
use DB;

class IngresoController extends Controller
{
  public function getIngresos(Request $request, $user_id){
    if ($request->has(['desde', 'hasta'])) {
        $ingresos = Ingreso::where('user_id', '=', $user_id)->orderBy('created_at', 'DESC')->whereBetween('created_at', [$request->desde, $request->hasta])->get();
        return response()->json($ingresos, 200);
    }

    $ingreso = Ingreso::where('user_id', '=', $user_id)->orderBy('created_at', 'DESC')->get();
    return response()->json($ingreso, 200);
  }

  public function getIngresoById($ingreso_id){
    $ingreso = Ingreso::find($ingreso_id);
    return response()->json($ingreso, 200);
  }

  /**
   * Metodo para guardar un ingreso.
   *
   * @param  \App\Http\Requests\IngresoRequest  $request
   * @return \App\Models\Ingreso;
   */
  public function saveIngreso(IngresoRequest $request){
    $ingreso = Ingreso::updateOrCreate(['id' => $request->id], $request->all());

    return response()->json($ingreso, 200);
  }

  /**
   * Metodo para asociar ingreso a una factura.
   *
   * @param string $codigo_to_upper
   * @param string $model_name
   * @param string $column_name
   * @param string $tipo
   * @return void;
   */
  public function asociarFactura(String $codigo_to_upper, String $model_name, String $column_name, String $tipo){
    $extraer_cod = str_replace($tipo, '', $codigo_to_upper);

    $numero_factura = ltrim($extraer_cod, '0');

    $nro_factura = $this->getFacturaOrNota($model_name, $column_name, $numero_factura);

    $current_deuda = $nro_factura->deuda;

    if(!$current_deuda){
       return null;
    }

    $suma_ingresos = $this->sumaIngresos($tipo, $numero_factura);

    return $this->updateDeuda($current_deuda, $suma_ingresos[0]->total);
  }

  private function sumaIngresos(String $tipo, $numero_factura){
    return DB::select("SELECT COALESCE(sum(importe), 0) as total FROM ingresos WHERE TRIM(LEADING '0' FROM REPLACE(codigo, '{$tipo}', '')) = ?", [$numero_factura]);
  }

  private function updateDeuda(Deuda $current_deuda, $suma_ingresos){
    $current_deuda->pagado = $suma_ingresos;
    $current_deuda->save();
    return $current_deuda;
  }

  private function getFacturaOrNota($model_name, $column_name, $numero_factura){
    if($model_name == 'nro_factura'){
       return NroFactura::where($column_name, $numero_factura)->get()->first();
    }

    if($model_name == 'nro_nota'){
       return NroNota::where($column_name, $numero_factura)->get()->first();
    }
    return null;
  }

  public function deleteIngreso($ingreso_id){
    $ingreso = Ingreso::find($ingreso_id);

    $codigo = $ingreso->codigo;

    $ingreso->delete();

    $codigo_to_upper = mb_strtoupper($codigo);

    $codigo_inicio = substr($codigo_to_upper, 0, 3);

    if($codigo_inicio == mb_strtoupper('FAC')){
       $this->asociarFactura($codigo_to_upper, 'nro_factura', 'nro_factura', 'FAC');
    }

    if($codigo_inicio == mb_strtoupper('NOT')){
       $this->asociarFactura($codigo_to_upper, 'nro_nota', 'nro_nota', 'NOT');
    }

    return response()->json('Ingreso eliminado', 200);
  }
}
