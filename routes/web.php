<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\tbl_archivos as archivos;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/inicio/{id}', 'HomeController@inicio');
Route::get('/nuevaBusqueda', 'HomeController@nuevaBusqueda');
Route::get('/enviados', 'HomeController@enviados');
Route::post('/procesarEnviados', 'HomeController@procesarEnviados');
Route::get('/respondidos', 'HomeController@respondidos');
Route::get('/historico', 'HomeController@historico');
Route::post('/eliminarDuplicados', 'HomeController@eliminarDuplicados');
Route::get('/gestion/{id}', 'VerGestionesController@gestion');
Route::get('ver/{id}', 'VerGestionesController@verRefinanciamiento');
Route::get('/observacion/{id}', 'VerGestionesController@observacion');
Route::post('/observacion/{id}', 'VerGestionesController@enviarObservacion');


Route::get('/comprimir/{id}', 'HomeController@comprimir');

Route::post('/busqueda', 'BusquedaController@actionPasarCedula');
Route::post('/procesar', 'BusquedaController@procesarRefinanciamiento');
Route::post('/generar', 'BusquedaController@guardarGestion');

Route::get('download/{id_archivo}', function ($id_archivo)
{
    $archivos=archivos::select('id','ruta','nombre')->where('id',$id_archivo)->first();
    //dd(public_path().$archivos->ruta.'/'.$archivos->nombre);
    $file = public_path().$archivos->ruta.'/'.$archivos->nombre; // or wherever you have stored your PDF files
    return response()->download($file);
});

Route::post('/getIntereses', 'BusquedaController@getIntereses');
Route::post('/consolidarTarjetas', 'BusquedaController@consolidarTarjetas');

Route::get('/verGestiones', 'VerGestionesController@index');


Route::get('/cargaDatos', 'CargaDatosController@index');
Route::get('importExport', 'CargaDatosController@importExport');
Route::get('downloadExcel/{type}', 'CargaDatosController@downloadExcel');
Route::post('importExcel', 'CargaDatosController@importExcel');
Route::post('procesarExcel', 'CargaDatosController@procesarExcel');

Route::get('leerConvertirExcel', 'CargaDatosController@leerConvertirExcel');


/*Route::post('getIntereses', function()
{
    //$tasa_interes = tasas_intereses::where('id', meses);
    dd(mes);
    $datos=DB::table('tbl_tasas_interes')->select('meses_plazo', 1)->get();
    $resultado =$datos->factor_calculado;
    return Response::json( array(
        'resultado' => $resultado,
    ));
});
*/
//IVR'S
Route::get('/ivr', 'Ivr\IvrController@index');
Route::get('/administrarIvr', 'Ivr\IvrController@administrarIvr');
Route::get('/scriptsEstaticosIvr', 'Ivr\IvrController@scriptsEstaticosIvr');

Route::get('/clientesIvr/{id}', 'Ivr\IvrController@clienteIvr');
Route::post('/campanias{id}', 'Ivr\IvrController@campanias');
Route::get('/nuevoIvr', 'Ivr\IvrController@nuevoIvr');
Route::post('/nuevoIvr2', 'Ivr\IvrController@nuevoIvr2');
Route::post('/depurarIvr', 'Ivr\IvrController@depurarIvr');
Route::post('/procesarIvr', 'Ivr\IvrController@procesarIvr');
//Route::post('/enviarIvr', 'Ivr\IvrController@enviarIvr');
Route::post('/mapeoExcel', 'Ivr\IvrController@mapeoExcel');
Route::post('/tiposcript', 'Ivr\IvrController@tiposcript');

Route::post('/aprobarIvr', 'Ivr\IvrController@aprobarIvr');
Route::post('/denegarIvr', 'Ivr\IvrController@denegarIvr');

//ADMINISTRADOR IVR'S
Route::post('/usuariosClientes', 'Ivr\IvrController@usuariosClientes');
Route::post('/scriptsClientes', 'Ivr\IvrController@scriptsClientes');
Route::get('/getScriptCliente{cliente}', 'Ivr\IvrController@getScriptCliente');
Route::get('/pause/{id}', 'Ivr\IvrController@pause');
Route::get('/play/{id}', 'Ivr\IvrController@play');
Route::get('/ivr/comandos', 'Ivr\IvrController@comandos');

Route::resource('campania','Ivr\CampaniaController');
Route::get('campaniascript','Ivr\CampaniaController@campaniascript');

Route::resource('cliente','Ivr\ClienteController');
Route::resource('scripts','Ivr\ScriptController');

Route::post('enviarIvrPrueba','Ivr\IvrController@enviarIvrPrueba');

Route::get('/reportesIvr', 'Ivr\IvrController@reportesIvr');
Route::get('/canalesIvr', 'Ivr\IvrController@canalesIvr');
Route::post('/procesarReporteIvr', 'Ivr\IvrController@procesarReporteIvr');
Route::post('/procesarCanalesIvr', 'Ivr\IvrController@procesarCanalesIvr');
Route::post('/procesarCampanaIvr/{id}', 'Ivr\IvrController@procesarCampanaIvr');


//MOVISTAR
Route::get('/movistar', 'Movistar\MovistarController@index');
Route::post('/depurarMovistar', 'Movistar\MovistarController@depurarMovistar');
Route::post('/procesarMovistar', 'Movistar\MovistarController@procesarMovistar');

//PREDICTIVOS
Route::resource('predictivo','Predictivos\Predictivo2Controller');
Route::post('procesarPredictivo','Predictivos\Predictivo2Controller@procesarPredictivo');

//JOBS
Route::get('sendIvrs',function(){
    $job= new \App\Jobs\SendIvrs();
    dispatch($job);
});



//CENTRALES
route::resource('centrales','Centrales\CentralesController');
route::post('procesarCentral','Centrales\CentralesController@procesar');

//REPORTES
Route::get('/reporte{id_carga}', 'Ivr\IvrController@reporteIvr');
Route::get('/getIvrs', 'Ivr\IvrController@getIvrs');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});


//AVON
Route::get('avon',function(){
   return view('avon/avon');
});
Route::post('generarReporteAvon','Avon\AvonController@generarReporteAvon');


//BMI
Auth::routes();
Route::get('/bmiActG', 'Bmi\BmiController@actualizaUltimaGestion');
Route::get('/bmiActMG', 'Bmi\BmiController@actualizaMejorGestion');

Route::get('/bmi', 'Bmi\BmiController@index');
Route::get('/bmiIngresar', 'Bmi\BmiController@ingresar');
Route::get('/busquedabmi', 'Bmi\BmiController@busquedaBmi');
Route::get('/confirmacionCitaEmail', 'Bmi\BmiController@confirmacionCitaEmail');
Route::post('/confirmacionCitaEmails', 'Bmi\BmiController@confirmacionCitaEmails');
Route::get('/nuevaCarga', 'Bmi\BmiController@nuevaCarga');
Route::post('/validarArchivo', 'Bmi\BmiController@validarArchivo');
Route::get('/clientesbmi', 'Bmi\BmiController@clientesBmi');

Route::get('/asesorBmi', 'Bmi\BmiController@asesorBmi');
Route::post('/ingresarAsesorBmi', 'Bmi\BmiController@ingresarAsesorBmi');
Route::get('/verAsesoresBmi', 'Bmi\BmiController@verAsesoresBmi');
Route::get('/activarAsesorBmi/{cedula}', 'Bmi\BmiController@activarAsesorBmi');
Route::get('/desactivarAsesorBmi/{cedula}', 'Bmi\BmiController@desactivarAsesorBmi');

Route::get('/agendarCitasBmi', 'Bmi\BmiController@agendarCitasBmi');
Route::get('/agendarCitassBmi/{id}', 'Bmi\BmiController@agendarCitassBmi');
Route::post('/agendarCitas2Bmi', 'Bmi\BmiController@agendarCitas2Bmi');
Route::get('/agendamientoAutomatico', 'Bmi\BmiController@agendamientoAutomatico');
Route::get('/asignacionAutomatica', 'Bmi\BmiController@asignacionAutomatica');
Route::get('/quitarAsignacionAutomatica', 'Bmi\BmiController@quitarAsignacionAutomatica');



Route::get('/gestion', 'Bmi\BmiController@gestion');
Route::get('/clienteRk/{cedula}', 'Bmi\BmiController@clienteRk');

Route::get('/rankingClientes', 'Bmi\BmiController@rankingClientes');
Route::get('/rankingCliente/{id}', 'Bmi\BmiController@rankingCliente');
Route::post('/rankingClienteP', 'Bmi\BmiController@rankingClienteP');
Route::get('/rankingClienteN',function(){
    return view('bmi/parametros/rankingClienteN');
});

Route::get('/verRankingAsesores', 'Bmi\BmiController@verRankingAsesores');
Route::get('/rankingAsesores', 'Bmi\BmiController@rankingAsesores');
Route::get('/rankingAsesor/{id}', 'Bmi\BmiController@rankingAsesor');
Route::post('/rankingAsesorP', 'Bmi\BmiController@rankingAsesorP');
Route::get('/rankingAsesorN',function(){
    return view('bmi/parametros/rankingAsesoresN');
});
Route::post('/rankingAsesorNu', 'Bmi\BmiController@rankingAsesorNu');
Route::get('/rankingAsesorD/{id}', 'Bmi\BmiController@rankingAsesorD');

Route::post('/select-ajax', 'Bmi\BmiController@selectAccion');
Route::post('/select-accion', 'Bmi\BmiController@selectAcciones');
Route::get('/tipoAccion', 'Bmi\BmiController@tipoAccion');
Route::get('/tipoAccionE/{id}', 'Bmi\BmiController@tipoAccionE');
Route::post('/tipoAccionU', 'Bmi\BmiController@tipoAccionU');
Route::post('/tipoAccionN', 'Bmi\BmiController@tipoAccionN');
Route::get('/tipoAccionD/{id}', 'Bmi\BmiController@tipoAccionD');

Route::get('/citas', 'Bmi\BmiController@citas');
Route::post('/citasU', 'Bmi\BmiController@citasU');
Route::post('/citasUp', 'Bmi\BmiController@citasUp');
Route::get('/habdesc/{id}/{estado}', 'Bmi\BmiController@habdesc');
Route::get('/dcita/{estado}/{asesor}/{cita}', 'Bmi\BmiController@dcita');
Route::get('/dcitaAlert/{estado}/{asesor}/{cita}', 'Bmi\BmiController@dcitaAlert');
Route::get('/eliminarCita/{estado}/{asesor}/{cita}', 'Bmi\BmiController@eliminarCita');
Route::post('/eliminarCitaPropia', 'Bmi\BmiController@eliminarCitaPropia');

Route::get('/citapropiaAp/{cita}', 'Bmi\BmiController@citapropiaAp');
Route::get('/citapropiaAn/{cita}', 'Bmi\BmiController@citapropiaAn');

Route::get('/productos', 'Bmi\BmiController@productos');
Route::get('/productosS/{id}', 'Bmi\BmiController@productosS');
Route::post('/productoU', 'Bmi\BmiController@productoU');
Route::get('/productoD/{id}', 'Bmi\BmiController@productoD');
Route::get('/productosN',function(){
    return view('bmi/parametros/productosN');
});
Route::post('/productosNu', 'Bmi\BmiController@productosNu');

Route::get('/gestion/{id}', 'Bmi\BmiController@gestionA');
Route::post('/gestionR/{id}', 'Bmi\BmiController@gestionR');
Route::get('/gestionP/{id}', 'Bmi\BmiController@gestionAp');
Route::post('/gestionRp/{id}', 'Bmi\BmiController@gestionRp');
Route::get('/gestionPm/{id}', 'Bmi\BmiController@gestionApm');
Route::post('/gestionRpm/{id}', 'Bmi\BmiController@gestionRpm');
Route::get('/gestionShow/{id}', 'Bmi\BmiController@gestionShow');
Route::get('/gestionesShow/{id}', 'Bmi\BmiController@gestionesShow');
Route::get('/gestionPShow/{id}', 'Bmi\BmiController@gestionPShow');
Route::get('/gestionPmShow/{id}', 'Bmi\BmiController@gestionPmShow');

Route::get('/cambiarP',function(){
    $estilo='hidden';
    $mensaje='hidden';
    return view('bmi/contrasena/cambiarP', compact('estilo','mensaje'));
});
Route::post('/cambiarPs', 'Bmi\BmiController@cambiarPs');
Route::post('/rcontrasena', 'Bmi\BmiController@rcontrasena');

Route::get('/agendarCitasPropias',function(){
    $estilo='hidden';
    $mensaje='hidden';
    return view('bmi/asesor/agendarCitasPropias', compact('estilo','mensaje'));
});


Route::post('/agendarCitasPropiasN', 'Bmi\BmiController@agendarCitasPropiasN');
Route::get('/historialAs', 'Bmi\BmiController@historialAsesor');
Route::get('/historialAsMan', 'Bmi\BmiController@historialAsesorManuales');

Route::get('/historialCitas', 'Bmi\BmiController@historialCitasA');
Route::get('/historialCitasP', 'Bmi\BmiController@historialCitasP');
Route::get('/historialCitasM', 'Bmi\BmiController@historialCitasM');

Route::post('/actualizarClientes', 'Bmi\BmiController@actualizarClientes');

Route::get('/clienteP/{id}', 'Bmi\BmiController@clienteP');
Route::post('/editarCitasPropias', 'Bmi\BmiController@editarCitasPropias');

Route::get('/reportEfectividadBmi', 'Bmi\BmiController@reportEfectividad');
Route::post('/reportEfectividadBmiPost', 'Bmi\BmiController@reportEfectividadPost');
Route::get('/descargarReportEfectividadBmi', 'Bmi\BmiController@reportEfectividadExcel');

Route::get('/reportCitasConfirmadasBmi', 'Bmi\BmiController@reportCitasConfirmadas');
Route::post('/reportCitasConfirmadasBmiPost', 'Bmi\BmiController@reportCitasConfirmadasPost');
Route::get('/descargaReportCitasConfirmadas', 'Bmi\BmiController@reportCitasConfirmadasExcel');

Route::get('/reportGeneralCuentas', 'Bmi\BmiController@reportGeneralCuentas');
Route::post('/generalCuentasBmi', 'Bmi\BmiController@generalCuentasBmi');

Route::post('/reportGeneralCuentasFecha', 'Bmi\BmiController@reportGeneralCuentasFecha');


Route::get('/usuarioGestionesShow/{cedula_gestor}', 'Bmi\BmiController@usuarioGestionesShow');

//REPORTES NUEVO SISTEMA
//DINERS
Route::get('/rDiners', 'ReportesNuevoSistema\ReportesNsController@reportesDiners');
Route::post('/gProducto', 'ReportesNuevoSistema\ReportesNsController@getProducto');
Route::post('/gCampana', 'ReportesNuevoSistema\ReportesNsController@getCampana');
Route::post('/gCuentas', 'ReportesNuevoSistema\ReportesNsController@getCuentas');
Route::post('/sftpDiners', 'ReportesNuevoSistema\ReportesNsController@sftpDiners');
//Route::get('/recupMetaDiners', 'ReportesNuevoSistema\ReportesNsController@recupMetaDiners');
Route::post('/recupMetaDinersR', 'ReportesNuevoSistema\ReportesNsController@recupMetaDinersR');
Route::post('/focalizacionCartera', 'ReportesNuevoSistema\ReportesNsController@focalizacionCartera');
Route::post('/reporteMarcaciones', 'ReportesNuevoSistema\ReportesNsController@reporteMarcaciones');
Route::post('/rgeneralCuentasDinersC', 'ReportesNuevoSistema\ReportesNsController@generalCuentasDinersCampo');
Route::post('/rgeneralCuentasDinersLegal', 'ReportesNuevoSistema\ReportesNsController@generalCuentasDinersLegal');

//29DEOCTUBRE
Route::get('/r29Octubre', 'ReportesNuevoSistema\Reportes29Controller@reportes29');
Route::post('/gCuentasGenerico', 'ReportesNuevoSistema\Reportes29Controller@getCuentasGenerico');
Route::post('/rAcumuladoGestiones29', 'ReportesNuevoSistema\Reportes29Controller@rAcumuladoGestiones');
Route::post('/rSeguimientoFacturacion29', 'ReportesNuevoSistema\Reportes29Controller@rSeguimientoFacturacion');
Route::post('/gCuentas29', 'ReportesNuevoSistema\Reportes29Controller@gCuentas29');

//EQUIFAX
Route::get('/rEquifax', 'ReportesNuevoSistema\ReportesEquifaxController@reportes');
Route::post('/rConsolidadoSM', 'ReportesNuevoSistema\ReportesEquifaxController@rConsolidadoPagoSemanalMensual');

//BCO GUAYAQUIL
Route::get('/rGuayaquil', 'ReportesNuevoSistema\ReportesBcoGuayaquilController@reportes');
Route::post('/rSemaforo', 'ReportesNuevoSistema\ReportesBcoGuayaquilController@rSemaforo');
Route::post('/rRecuperacion', 'ReportesNuevoSistema\ReportesBcoGuayaquilController@rRecuperacion');

//BELCORP
Route::get('/rBelcorp', 'ReportesNuevoSistema\ReportesBelcorpController@reportes');
Route::post('/rBGeneral', 'ReportesNuevoSistema\ReportesBelcorpController@generalCuentas');
Route::post('/rBRecuperacion', 'ReportesNuevoSistema\ReportesBelcorpController@recuperacion');
Route::post('/gCuentasBelcorp', 'ReportesNuevoSistema\ReportesBelcorpController@gCuentasBelcorp');
Route::post('/rBHistorial', 'ReportesNuevoSistema\ReportesBelcorpController@historialGestiones');

//CEX
Route::get('/rCex', 'ReportesNuevoSistema\ReportesCexController@reportes');
Route::get('/rCexCumplimiento', 'ReportesNuevoSistema\ReportesCexController@rCexCumplimiento');
Route::post('/rCexCumplimientoP', 'ReportesNuevoSistema\ReportesCexController@rCexCumplimientoP');
Route::get('/rCexAsignaciones', 'ReportesNuevoSistema\ReportesCexController@rCexAsignaciones');
Route::get('/rCexAsignaciones2', 'ReportesNuevoSistema\ReportesCexController@rCexAsignaciones2');
Route::get('/rCexInventario', 'ReportesNuevoSistema\ReportesCexController@rCexInventario');
Route::get('/rCexZonificacion', 'ReportesNuevoSistema\ReportesCexController@rCexZonificacion');

Route::get('/encuestasCex', 'ReportesNuevoSistema\ReportesCexController@encuestas');
Route::post('/rnuevoUsuario', 'ReportesNuevoSistema\ReportesCexController@ingresarAsesorCex');
Route::post('/rnuevaPregunta', 'ReportesNuevoSistema\ReportesCexController@ingresarPreguntaCex');
Route::get('/usuariosGrupos/{id}', 'ReportesNuevoSistema\ReportesCexController@usuariosGrupos');

Route::post('/gTipos', 'ReportesNuevoSistema\ReportesCexController@gTipos');

//Route::post('/rControlAsistencia', 'ReportesNuevoSistema\ReportesCexController@rControlAsistencia');
Route::post('/rAsistencia', 'ReportesNuevoSistema\ReportesCexController@rAsistencia');
Route::post('/rAsistenciaExcel', 'ReportesNuevoSistema\ReportesCexController@rAsistenciaExcel');
//Route::get('/rControlAsistenciaAjax', 'ReportesNuevoSistema\ReportesCexController@rControlAsistenciaAjax');
Route::post('/obCexAsistencia', 'ReportesNuevoSistema\ReportesCexController@obCexAsistencia');
Route::post('/rControlAsistenciaObs', 'ReportesNuevoSistema\ReportesCexController@rControlAsistenciaObs');
Route::post('/rRegresarCex', 'ReportesNuevoSistema\ReportesCexController@rRegresarCex');

Route::post('/rAsignaciones', 'ReportesNuevoSistema\ReportesCexController@rAsignaciones');
Route::post('/rAsignaciones2', 'ReportesNuevoSistema\ReportesCexController@rAsignaciones2');
Route::post('/rInventario', 'ReportesNuevoSistema\ReportesCexController@rInventario');
Route::post('/rZonificacion', 'ReportesNuevoSistema\ReportesCexController@rZonificacion');

//DINERS CUENTAS X88
Route::get('/cuentasX88', 'ReportesNuevoSistema\ReportesCx88Controller@index');
Route::get('/cuentasX88G', 'ReportesNuevoSistema\ReportesCx88Controller@gestionadas');

Route::get('/cuentasX88S', 'ReportesNuevoSistema\ReportesCx88Controller@indexSupervisor');
Route::post('/cuentasX88SP', 'ReportesNuevoSistema\ReportesCx88Controller@indexSupervisorP');
Route::get('/cuentasX88SG', 'ReportesNuevoSistema\ReportesCx88Controller@gestionadasSupervisor');
Route::post('/cuentasX88SGP', 'ReportesNuevoSistema\ReportesCx88Controller@gestionadasSupervisorP');

Route::get('/cuentasX88Carga', 'ReportesNuevoSistema\ReportesCx88Controller@indexSupervisorCarga');
Route::get('/cuentasX88Reasignar', 'ReportesNuevoSistema\ReportesCx88Controller@indexSupervisorReasignar');
Route::post('/cuentasX88ReasignarP', 'ReportesNuevoSistema\ReportesCx88Controller@indexSupervisorReasignarP');
Route::post('/cuentasX88ReasignarN', 'ReportesNuevoSistema\ReportesCx88Controller@cuentasX88ReasignarN');
Route::post('/cuentasX88ReasignarN2', 'ReportesNuevoSistema\ReportesCx88Controller@cuentasX88ReasignarN2');
Route::post('/eliminarAsignacionCx88', 'ReportesNuevoSistema\ReportesCx88Controller@eliminarAsignacionCx88');
Route::post('/cuentasX88Eliminar', 'ReportesNuevoSistema\ReportesCx88Controller@cuentasX88EliminarTodas');
Route::post('/cuentasX88ReasignarTodos', 'ReportesNuevoSistema\ReportesCx88Controller@cuentasX88ReasignarTodos');
Route::post('/agregarAgenteX88', 'ReportesNuevoSistema\ReportesCx88Controller@agregarAgenteX88');
Route::post('/eliminarAgenteX88', 'ReportesNuevoSistema\ReportesCx88Controller@eliminarAgenteX88');


Route::post('/importExcelCx88', 'ReportesNuevoSistema\ReportesCx88Controller@importExcelCx88');
Route::post('/cargaDatosExcel', 'ReportesNuevoSistema\ReportesCx88Controller@cargaDatosExcel');
Route::get('/gestionAgenteX88/{id}', 'ReportesNuevoSistema\ReportesCx88Controller@gestionAgenteX88');
Route::get('/gestionAgenteX88S/{id}', 'ReportesNuevoSistema\ReportesCx88Controller@gestionAgenteX88S');
Route::post('/gMotivoNoPago', 'ReportesNuevoSistema\ReportesCx88Controller@getMotivo');
Route::post('/gestionCx88', 'ReportesNuevoSistema\ReportesCx88Controller@guardarGestionCx88');
Route::post('/gestionCx88S', 'ReportesNuevoSistema\ReportesCx88Controller@guardarGestionCx88S');

Route::post('/descargaExcelCx88S', 'ReportesNuevoSistema\ReportesCx88Controller@descargaExcelCx88S');
Route::post('/gestionRealizadaCx88S', 'ReportesNuevoSistema\ReportesCx88Controller@gestionRealizadaCx88S');

//AMT
Route::get('/rAmt', 'ReportesNuevoSistema\ReportesAmtController@reportes');
Route::post('/reporteAmt', 'ReportesNuevoSistema\ReportesAmtController@reporteAmt');
Route::post('/reporteGeneralCuentasAtm', 'ReportesNuevoSistema\ReportesAmtController@reporteGeneralCuentas');
Route::get('/sivr', '\App\Console\Commands\SendIvrs@handle');
Route::post('/reporteMarcacionesAtm', 'ReportesNuevoSistema\ReportesAmtController@reporteMarcacionesAtm');

//APOYO
Route::get('/actZonificacion', 'ReportesNuevoSistema\ReportesNsController@actualizarZonificacion');
Route::get('/xdelete', 'ReportesNuevoSistema\ReportesNsController@xdelete');

//BELCORP PERU
Route::get('/rReportesPeru', 'ReportesNuevoSistema\ReportesPeruController@reportes');
Route::post('/rBGeneralPeru', 'ReportesNuevoSistema\ReportesPeruController@generalCuentasBelcorp');
Route::post('/rFGeneralPeru', 'ReportesNuevoSistema\ReportesPeruController@generalCuentasFinancieras');

Route::post('/gCuentasPe', 'ReportesNuevoSistema\ReportesPeruController@gCuentasBelcorp');
Route::post('/gProductoPe', 'ReportesNuevoSistema\ReportesPeruController@getProductoPe');
Route::post('/gCampanaPe', 'ReportesNuevoSistema\ReportesPeruController@getCampanaPe');

//SISTEMA MONITOREO
Route::get('/monitoreoCexRes', 'ReportesNuevoSistema\ReportesCexController@monitoreoCexRes');
Route::post('/monitoreoCexRes', 'ReportesNuevoSistema\ReportesCexController@monitoreoCexResPost');
Route::get('/monitoreoCex', 'ReportesNuevoSistema\ReportesCexController@monitoreoCex');
Route::get('/mapaCex/{cedula}/{fecha}', 'ReportesNuevoSistema\ReportesCexController@monitoreoCexMapa');
Route::get('/mapaCexTotal/{cedula}/{fecha}', 'ReportesNuevoSistema\ReportesCexController@monitoreoCexMapaTotal');
Route::get('/dashCex/{cedula}/{imei}', 'ReportesNuevoSistema\ReportesCexController@dashboardCexMapa');
Route::get('/dashCexG/{cedula}/{imei}', 'ReportesNuevoSistema\ReportesCexController@dashboardCexMapaG');
Route::get('/paradasCexG/{cedula}/{imei}', 'ReportesNuevoSistema\ReportesCexController@paradasCexMapaG');
Route::get('/refreshCexGps/{imei}/{fecha}', 'ReportesNuevoSistema\ReportesCexController@refreshCexGps');

Route::post('/rMonitoreo', 'ReportesNuevoSistema\ReportesCexController@rMonitoreo');
Route::post('/calculoDistanciaCex', 'ReportesNuevoSistema\ReportesCexController@calculoDistanciaCex');
Route::get('/rParametrosCex', 'ReportesNuevoSistema\ReportesCexController@rParametrosCex');
Route::post('/procesarParametrosCex', 'ReportesNuevoSistema\ReportesCexController@procesarParametrosCex');

Route::post('/guardaDireccionesCex', 'ReportesNuevoSistema\ReportesCexController@guardaDireccion');

//NOMINA
Route::get('/nominaIngresar','NominaController@index');
Route::post('/depurarNomina', 'NominaController@depurarNomina');
Route::post('/nomina', 'NominaController@nomina');