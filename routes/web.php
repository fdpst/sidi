<?php

//use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('crear-tabla-chat', 'App\Http\Controllers\MigrationController@crear_tabla_chat');
Route::get('crear-tabla-mensajes', 'App\Http\Controllers\MigrationController@crear_tabla_mensajes');
Route::get('add-device-token', 'App\Http\Controllers\MigrationController@add_device_token');
Route::get('tansform-to-polimorfic', 'App\Http\Controllers\MigrationController@tansform_to_polimorfic');
Route::get('/tests', 'App\Http\Controllers\TestController@index');

Route::get('/', function () {
    return view('base');
});

Route::get('symlink', 'App\Http\Controllers\SymLinkController@create');

Route::get('crear-directorio', function(){
  return Storage::makeDirectory('public/lotes');
});

/*

alter table `recibo` modify column `presupuesto_url` varchar(60) null AFTER `has_iva` ,modify column `factura_url` varchar(60) null AFTER `presupuesto_url` ,modify column `nota_url` varchar(60) null AFTER `factura_url` ,comment = 'utf8mb4_unicode_ci'
DELETE FROM recibo;
DELETE FROM recibo_servicios;
DELETE FROM nro_presupuesto;
DELETE FROM nro_parte_trabajo;
DELETE FROM nro_factura;
DELETE FROM nro_nota;
DELETE FROM nro_nota;
DELETE FROM deuda;
*/
/*
return response()->download($file_path, 'prueba.pdf', [
  'Content-Disposition' => 'attachment; filename="prueba.pdf"',
  'X-Suggested-Filename' => 'prueba.pdf'
]);

The Content-Type seems invalid. Try:
{
  responseType: ‘arraybuffer’,
  headers: {
  ‘Accept’: ‘application/octet-stream’,
  }
}
*/


Route::get('/clear', function() {

   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');

   return "Cleared!";

});
