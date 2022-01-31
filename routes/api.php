 <?php

/*Rutas login*/
Route::post('/login', 'AuthController@login')->name('login');
Route::post('/loginApp', 'AuthController@loginApp')->name('loginApp');
Route::post('/logout', 'AuthController@logout');

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/index-app/{idUser}', 'AppController@main');

    /*Ruta para el chat*/
    Route::get('get-chats', 'ChatController@getAllChats');
    Route::get('get-chat/{id?}', 'ChatController@getChat');
    Route::post('send-mensaje/{id?}', 'ChatController@guardarMensaje');
    Route::get('set-seen-messages/{chat_id?}', 'ChatController@setSeen');

    /*Ruta para obtener las provincias*/
    Route::get('get-provincias', 'ProvinciaController@getProvincias');
    /*Ruta para obtener los servicios*/
    Route::get('get-servicios', 'ServicioController@getServicios');
    /*Ruta para obtener los estados*/
    Route::get('get-estados', 'EstadoController@getEstados');

    /*Rutas Proyectos*/
    Route::get('get-all-proyectos/', 'ProyectoController@getAllProyectos');
    Route::get('get-proyecto-by-id/{proyecto_id}', 'ProyectoController@getProyectoByid');
    Route::get('get-proyectos-by-user-id/{userid}', 'ProyectoController@getProyectosByUserId');
    Route::post('save-proyecto', 'ProyectoController@saveProyecto');
    Route::get('delete-proyecto/{proyecto_id}', 'ProyectoController@deleteProyecto');// Tiene softdelete ( de elimina de la app pero se quedan los datos en BD )
    Route::get('update-project-status/{proyecto_estado_id}', 'ProyectoController@updateProyectoEstado'); // Pedimos al api que actualice el campo finalizado del estado de este Proyecto
    Route::get('delete-proyecto-estado/{proyecto_estado_id}', 'ProyectoController@deleteEstadoProyecto'); //Borramos un estado de proyecto existente
    Route::get('delete-file/{tipo}/{id}', 'ProyectoController@deleteFile');

    /*Rutas Potenciales*/
    Route::get('get-all-potenciales/', 'ProyectoController@getAllPotenciales');
    Route::get('get-potencial-by-id/{potencial_id}', 'ProyectoController@getPotencialByid');
    Route::post('save-potencial', 'ProyectoController@savePotencial');
    Route::get('delete-potencial/{potencial_id}', 'ProyectoController@deletePotencial');// Tiene softdelete ( de elimina de la app pero se quedan los datos en BD )

    /*Rutas Password*/
    Route::get('get-all-passwords/','PasswordController@getAllPasswords');
    Route::get('get-password-by-id/{password_id}', 'PasswordController@getPasswordByid');
    Route::post('save-password', 'PasswordController@savePassword');
    Route::get('delete-password/{password_id}', 'PasswordController@deletePassword');// Tiene softdelete ( de elimina de la app pero se quedan los datos en BD )

    /*Rutas Usuarios*/
    Route::get('get-usuarios', 'UsuarioController@getUsuarios');
    Route::post('get-usuarios-empleados', 'UsuarioController@getUsuariosEmpleados');
    Route::get('get-usuarios-clientes', 'UsuarioController@getUsuariosClientes');
    Route::get('get-usuario-by-id/{usuario_id}', 'UsuarioController@getUsuarioByid');
    Route::post('save-usuario', 'UsuarioController@saveUsuario');
    Route::post('update-usuario/{id}', 'UsuarioController@updateUsuario');
    Route::get('delete-usuario/{usuario_id}', 'UsuarioController@deleteUsuario');
    Route::get('get-methods-form', 'UsuarioController@getMethodsForm');

    /*Rutas Fichar*/
    Route::post('fichar/{id}', 'FicharController@fichar');
    Route::post('get-fichajes', 'FicharController@getFichajes');
    Route::post('get-fichajes-by-user/{id}', 'FicharController@getFichajesByUser');
    /*Rutas Tareas*/
    Route::post('save-tarea', 'TareasController@saveTarea');
    Route::post('get-tareas', 'TareasController@getTareas');
    Route::post('buscar-tareas', 'TareasController@buscarTareas');
    Route::post('delete-tarea/{tarea_id}', 'TareasController@eliminarTarea');

    /*Rutas Promociones*/
    Route::get('get-all-promociones', 'PromocionController@getAllpromociones');

/* END Funcionan bien PROBADOS OK */


    /*Rutas Proveedor*/
    Route::get('get-proveedores/{user_id}', 'ProveedorController@getProveedores');
    Route::get('get-proveedor-by-id/{proveedor_id}', 'ProveedorController@getProveedorByid');
    Route::post('save-proveedor', 'ProveedorController@saveProveedor');
    Route::get('delete-proveedor/{proveedor_id}', 'ProveedorController@deleteProveedor');

    /*Rutas Albaranes*/
    Route::get('get-albaranes/{user_id}', 'AlbaranController@getAlbaranes');
    Route::get('get-albaran-by-id/{albaran_id}', 'AlbaranController@getAlbaranById');
    Route::post('save-albaran', 'AlbaranController@saveAlbaran');
    Route::post('update-albaran/{id}', 'AlbaranController@updatelbaran');
    Route::get('get-albaranes-enviados/{user_id}', 'AlbaranController@getnviados');
    Route::get('get-albaranes-enviados-show/{albaranId}', 'AlbaranController@albaranEnvidoShow');
    Route::post('save-albaran-enviado', 'AlbaranController@albaranesEnviadosF');
    Route::post('save-albaran-recibido', 'AlbaranController@albaranesRecibido');
    Route::post('delete-albaran-enviados/{albaran_id}', 'AlbaranController@deleteAlbaranEnviado');
    Route::post('save-factura-albaran', 'AlbaranController@generarFactura');
    Route::post('save-nota-albaran', 'AlbaranController@generarNota');
    Route::post('update-albaran-enviados/{id}', 'AlbaranController@updatealbaranesEnviadosF');
    Route::post('update-albaran-enviados-factura/{id}', 'AlbaranController@updateFactAlbaran');
    Route::get('delete-albaran/{albaran_id}', 'AlbaranController@deleteAlbaran');

    /*Rutas Recibo*/
    Route::get('get-recibos/{user_id}', 'PresupuestoController@getRecibos');
    Route::post('save-recibo/{tipo}/{convertir_factura}', 'ReciboController@saveRecibo');
    Route::get('get-recibo-by-id/{recibo_id}', 'ReciboController@getReciboById');
    Route::get('delete-recibo/{recibo_id}', 'PresupuestoController@deletePresupuesto');
    Route::get('remove-contabilizado/{elemento}/{idServicio}/{idRecibo}', 'ReciboController@removeContabilizado');
    Route::get('get-recibo-by-name/{elemento}', 'ReciboController@getReciboByName');

    /*Rutas factura*/
    Route::get('get-facturas/{user_id}', 'FacturaController@getFacturas');
    Route::get('delete-factura/{factura_id}', 'FacturaController@deleteFactura');
    Route::get('facturas-recibidas/{idUser}', 'FacturaRecibidasController@index');
    Route::get('facturas-recibidas-show/{idFac}', 'FacturaRecibidasController@show');
    Route::post('facturas-recibidas/{idUser}', 'FacturaRecibidasController@store');
    Route::post('facturas-recibidas-update/{idFac}', 'FacturaRecibidasController@update');
    Route::post('facturas-recibidas-delete/{idFactRec}', 'FacturaRecibidasController@destroy');
    Route::get('get-data-albaranes/{user_id}', 'FacturaController@getDataAlbaranes');

    /*Rutas Notas*/
    Route::get('get-notas/{user_id}', 'NotaController@getNotas');
    Route::get('delete-nota/{recibo_id}', 'NotaController@deleteNota');

    /*Rutas Parte Trabajo*/
    Route::get('get-parte-trabajo/{user_id}', 'ParteTrabajoController@getParteTrabajo');
    Route::get('get-presupuestos-for-parte-trabajo/{user_id}', 'ParteTrabajoController@getPrespuestos');
    Route::get('get-presupuesto-asociado/{recibo_id}', 'ParteTrabajoController@getPresupuestoAsociado');
    Route::get('delete-parte-trabajo/{recibo_id}', 'ParteTrabajoController@deleteParteTrabajo');

    /*Rutas Ingreso*/
    Route::get('get-ingresos/{user_id}', 'IngresoController@getIngresos');
    Route::get('get-ingreso-by-id/{ingreso_id}', 'IngresoController@getIngresoById');
    Route::post('save-ingreso', 'IngresoController@saveIngreso');
    Route::get('delete-ingreso/{ingreso_id}', 'IngresoController@deleteIngreso');

    /*Rutas Gastos*/
    Route::get('get-gastos/{user_id}', 'GastosController@index');
    Route::get('get-gasto-by-id/{gasto_id}', 'GastosController@getGastoById');
    Route::post('save-gasto', 'GastosController@store');
    Route::post('update-gasto', 'GastosController@updateGasto');
    Route::get('delete-gasto/{gasto_id}', 'GastosController@destroy');

    /*Rutas estadisticas ingreso*/
    Route::get('get-ingreso-bruto/{user_id}/{desde}/{hasta}', 'EstadisticasContabilidadController@getIngresoBruto');

    /*Rutas Morosos*/
    Route::get('get-morosos/{user_id}', 'MorososController@getMorosos');

    /*Rutas Email*/
    Route::post('send-email', 'MailController@sendEmail');

    /*Lote facturas*/
    Route::get('get-lote-facturas/{user_id}', 'LoteController@getFacturasByRango');
    Route::post('enviar-lote-facturas/{user_id}', 'LoteController@enviarFacturas');

    /*Rutas Gestor Documental*/
    Route::get('get-documentos/{user_id}', 'GestorDocumentalController@getDocumentos');
    Route::post('delete-documentos', 'GestorDocumentalController@deleteDocument');
    Route::post('create-folder', 'GestorDocumentalController@createFolder');
    Route::post('save-documents', 'GestorDocumentalController@saveDocuments');
    Route::post('download-document/{user_id}', 'GestorDocumentalController@downloadDocuments');
    Route::get('gestor-admin', 'GestorDocumentalController@adminGestionDocumental');
    Route::post('view-document', 'GestorDocumentalController@viewDocument');
    Route::get('get-tasks/{user_id}', 'GestorTareasController@index');
    Route::post('new-drag/{user_id}', 'GestorTareasController@newDrag');
    Route::put('update-drag/{drag_id}', 'GestorTareasController@updateDrag');
    Route::delete('delete-drag/{drag_id}', 'GestorTareasController@deleteDrag');
    Route::post('save-tasks/', 'GestorTareasController@store');
    Route::put('update-tasks/{tarea_id}', 'GestorTareasController@update');
    Route::put('update-tasks-status/{tarea_id}', 'GestorTareasController@updateStatus');
    Route::delete('delete-tasks/{tarea_id}', 'GestorTareasController@destroy');

    /*Rutas Tipos Gastos*/
    Route::get('get-tipos-gasto/{user_id}', 'TiposGastoController@getTiposGasto');
    Route::post('save-tipos-gasto', 'TiposGastoController@saveTiposGasto');
    Route::get('delete-tipos-gasto/{tipos_gasto_id}', 'TiposGastoController@deleteTiposGasto');

    Route::get('show-presupuestos/{proyecto_id}', 'PresupuestoController@showPresupuesto');
    Route::post('update-presupuestos/{presupuesto_id}', 'PresupuestoController@updatePresupuesto');
    Route::post('store-presupuestos/{proyecto_id}', 'PresupuestoController@storePresupuesto');
    Route::post('send-mail-presupuesto/{presupuesto_id}', 'PresupuestoController@sendMail');

    Route::get('index-facturas', 'FacturaController@indexFactura');
    Route::get('show-facturas/{factura_id}', 'FacturaController@showFactura');
    Route::post('update-facturas/{factura_id}', 'FacturaController@updateFactura');
    Route::post('store-facturas', 'FacturaController@storeFactura');
    Route::post('delete-items-facturas', 'FacturaController@deleteItemFactura');

    //Rutas de promociones
    Route::get('get-all-promociones', 'PromocionController@getAllpromociones');
    Route::get('get-promocion-by-id/{promocion_id}', 'PromocionController@getPromocionById');
    Route::get('delete-promocion/{promocion_id}', 'PromocionController@deletePromocion');
    Route::get('change-promocion-active/{promocion_id}', 'PromocionController@changePromocionActive');
    Route::post('save-promocion', 'PromocionController@savePromocion');

    //Rutas Reservas de Sala
    Route::get('get-all-reservas', 'ReservasSalaController@getAllReservas');
    Route::get('get-desde-horas/{fecha}', 'ReservasSalaController@getDesdeHoras');
    Route::get('get-hasta-horas/{fecha}/{desdeHora}', 'ReservasSalaController@getHastaHoras');
    Route::post('save-reserva', 'ReservasSalaController@saveReunion');
    Route::get('delete-reserva/{reserva_id}', 'ReservasSalaController@deleteReserva');

    /*Rutas estadisticas*/
    Route::get('get-proyectos-data/{desde}/{hasta}', 'EstadisticasApiController@getProyectosData');
    Route::get('detalle-data/{desde}/{hasta}/{servicio_id}', 'EstadisticasApiController@getDetalleData');
});
