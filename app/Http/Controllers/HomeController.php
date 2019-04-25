<?php

namespace App\Http\Controllers;

use App\avon\tbl_campana_avon;
use App\avon\tbl_campana_peru_avon;
use App\bmi\tbl_asesores;
use App\bmi\tbl_citas;
use App\bmi\tbl_citas_historial;
use App\bmi\tbl_citas_propias;
use App\bmi\tbl_citas_propias_historial;
use App\bmi\tbl_producto;
use App\bmi\tbl_tipo;
use App\reportesNuevoSistema\tbl_brands;
use App\Role;
use App\tbl_estado_gestion;
use Illuminate\Http\Request;
use App\User;
use App\tbl_gestiones as gestiones;
use App\tbl_gestiones_historico as gestiones_historico;
use App\tbl_estado_gestion as estado_gestion;
use Illuminate\Support\Facades\Auth;
use App\tbl_archivos as archivos;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\Redirect;
use App\predictivos\prd2_campaign as campaign;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use TCG\Voyager\Facades\Voyager;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();
        //DINERS
        if($user->role_id==1){
            return view('movistar.movistar');
        }
        if($user->role_id==2){
            return view('home');
        }
        if($user->role_id==3){
            if (\Voyager::can('browse_tbl_reportes_cex')){
                return view('welcome.index');
            }
            $gestiones_d = gestiones::where('marca','DINERS CLUB')->
            where('id_formato',1)->
            where('id_estado_gestion', '!=',8)->
            where('consolidado',0)->orderBy('id', 'desc')->get();
            $gestiones_d=valida_duplicados($gestiones_d);

            $gestiones_vr = gestiones::where('marca','VISA INTERDIN')->
            where('id_formato',2)->
            where('id_estado_gestion', '!=',8)->
            where('consolidado',0)->orderBy('id', 'desc')->get();
            $gestiones_vr=valida_duplicados($gestiones_vr);

            $gestiones_v = gestiones::where('marca','VISA INTERDIN')->
            where('id_formato',1)->
            where('id_estado_gestion', '!=',8)->
            where('consolidado',0)->orderBy('id', 'desc')->get();
            $gestiones_v=valida_duplicados($gestiones_v);

            $gestiones_disr = gestiones::where('marca','DISCOVER')->
            where('id_formato',2)->
            where('id_estado_gestion', '!=',8)->
            where('consolidado',0)->orderBy('id', 'desc')->get();
            $gestiones_disr=valida_duplicados($gestiones_disr);

            $gestiones_dis = gestiones::where('marca','DISCOVER')->
            where('id_formato',1)->
            where('id_estado_gestion', '!=',8)->
            where('consolidado',0)->orderBy('id', 'desc')->get();
            $gestiones_dis=valida_duplicados($gestiones_dis);

            return view('supervisor' ,compact('gestiones_d','gestiones_v','gestiones_dis','gestiones_dr','gestiones_vr','gestiones_disr', 'archivos'));
        }

        //IVRS
        if($user->role_id==4 || $user->role_id==5){
            return view('welcome.index');
        }

        //PREDICTIVOS DINERS
        if($user->role_id==6){
            $campanias=campaign::where('estatus','A')->get();
            return view('predictivos.predictivo', compact('campanias'));
        }

        //VARIOS ROLES
        if($user->role_id==7){
            return view('welcome.index');
        }

        //AVON
        if($user->role_id==8){
            $campanias=tbl_campana_avon::where('producto_id',98)->orderBy('id','desc')->get();
            $campaniaspe=tbl_campana_peru_avon::where('producto_id',153)->orderBy('id','desc')->get();
            return view('avon.avon', compact('campanias','campaniaspe'));
        }

        //BMI
        if($user->role_id==Role::where('name','bmisupervisor')->first()->id || $user->role_id==Role::where('name','bmiagente')->first()->id){
            $estilo2='';
            if($user->role_id==Role::where('name','bmiagente')->first()->id){
                $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();
                $citas=tbl_citas::where('asesor',$asesor->cedula_asesor)->get();
                $citas_historial=tbl_citas_historial::where('created_at', '>=', date('Y-m-d').' 00:00:00')->where('asesor',$asesor->cedula_asesor)->where('estado','!=',0)->get();

                $citasPropias=tbl_citas_propias::where('asesor',$asesor->cedula_asesor)->where('cita_propia',1)->get();
                $citasPropiasHistorial=tbl_citas_propias_historial::where('created_at', '>=', date('Y-m-d').' 00:00:00')->where('estado',2)->where('estado_aprobado',3)->where('asesor',$asesor->cedula_asesor)->get();

                $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
                $productos=tbl_producto::pluck("descripcion","id_producto")->all();
                if ($asesor->r_contrasena==0){
                    $estilo='hidden';
                    $mensaje='';
                    return view('bmi.contrasena.rcontrasena', compact('asesor','estilo','mensaje','estilo2'));
                }else{
                    return view('bmi.bmi', compact('citas','citasPropias','tipo','productos','estilo2','citas_historial','citasPropiasHistorial'));
                }
            }elseif($user->role_id==9) {
                $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();
                $citas=tbl_citas::get();
                $citas_historial=tbl_citas_historial::where('created_at', '>=', date('Y-m-d').' 00:00:00')->get();

                $citasPropiasHistorial=tbl_citas_propias_historial::where('created_at', '>=', date('Y-m-d').' 00:00:00')->where('estado',2)->where('estado_aprobado',3)->get();
                $citasPropias=tbl_citas_propias::where('cita_propia',1)->get();

                if ($asesor->r_contrasena==0){
                    $estilo='hidden';
                    $mensaje='';
                    return view('bmi.contrasena.rcontrasena', compact('asesor','estilo','mensaje','estilo2'));
                }else {
                    return view('bmi.bmiSupervisor', compact('citas', 'citasPropias','citas_historial','citasPropiasHistorial'));
                }
            }
        }
        //CENTRALES
        if($user->role_id==10){
            $centrales = array();
            return view('centrales/index', compact('centrales'));
        }
        //REPORTES NUEVO SISTEMA
        if($user->role_id>=13){
            return view('welcome.index');
        }
    }

    public function inicio($id)
    {
        $user = Auth::user();
        if($user->role_id==1){
            return view('movistar.movistar');
        }
        if($user->role_id==2){return view('home');}
        if($user->role_id==3 ){
            if (\Voyager::can('browse_tbl_reportes_cex') && $id==12){
                return redirect()->action('ReportesNuevoSistema\ReportesCexController@reportes');
            }
            if ($id==6){
                $gestiones_d = gestiones::where('marca','DINERS CLUB')->
                where('id_formato',1)->
                where('id_estado_gestion', '!=',8)->
                where('consolidado',0)->orderBy('id', 'desc')->get();
                $gestiones_d=valida_duplicados($gestiones_d);

                $gestiones_vr = gestiones::where('marca','VISA INTERDIN')->
                where('id_formato',2)->
                where('id_estado_gestion', '!=',8)->
                where('consolidado',0)->orderBy('id', 'desc')->get();
                $gestiones_vr=valida_duplicados($gestiones_vr);

                $gestiones_v = gestiones::where('marca','VISA INTERDIN')->
                where('id_formato',1)->
                where('id_estado_gestion', '!=',8)->
                where('consolidado',0)->orderBy('id', 'desc')->get();
                $gestiones_v=valida_duplicados($gestiones_v);

                $gestiones_disr = gestiones::where('marca','DISCOVER')->
                where('id_formato',2)->
                where('id_estado_gestion', '!=',8)->
                where('consolidado',0)->orderBy('id', 'desc')->get();
                $gestiones_disr=valida_duplicados($gestiones_disr);

                $gestiones_dis = gestiones::where('marca','DISCOVER')->
                where('id_formato',1)->
                where('id_estado_gestion', '!=',8)->
                where('consolidado',0)->orderBy('id', 'desc')->get();
                $gestiones_dis=valida_duplicados($gestiones_dis);

                return view('supervisor' ,compact('gestiones_d','gestiones_v','gestiones_dis','gestiones_dr','gestiones_vr','gestiones_disr', 'archivos'));
            }
        }
        if($user->role_id==4 || $user->role_id==5){
            return redirect()->action('Ivr\IvrController@index');
        }
        if($user->role_id==6){
            $campanias=campaign::where('estatus','A')->get();
            return view('predictivos.predictivo', compact('campanias'));
        }

        if($user->role_id==7 || $user->role_id>=13){
            if($id==6) {
                $gestiones_d = gestiones::where('marca','DINERS CLUB')->
                where('id_formato',1)->
                where('id_estado_gestion', '!=',8)->
                where('consolidado',0)->orderBy('id', 'desc')->get();
                $gestiones_d=valida_duplicados($gestiones_d);

                $gestiones_vr = gestiones::where('marca','VISA INTERDIN')->
                where('id_formato',2)->
                where('id_estado_gestion', '!=',8)->
                where('consolidado',0)->orderBy('id', 'desc')->get();
                $gestiones_vr=valida_duplicados($gestiones_vr);

                $gestiones_v = gestiones::where('marca','VISA INTERDIN')->
                where('id_formato',1)->
                where('id_estado_gestion', '!=',8)->
                where('consolidado',0)->orderBy('id', 'desc')->get();
                $gestiones_v=valida_duplicados($gestiones_v);

                $gestiones_disr = gestiones::where('marca','DISCOVER')->
                where('id_formato',2)->
                where('id_estado_gestion', '!=',8)->
                where('consolidado',0)->orderBy('id', 'desc')->get();
                $gestiones_disr=valida_duplicados($gestiones_disr);

                $gestiones_dis = gestiones::where('marca','DISCOVER')->
                where('id_formato',1)->
                where('id_estado_gestion', '!=',8)->
                where('consolidado',0)->orderBy('id', 'desc')->get();
                $gestiones_dis=valida_duplicados($gestiones_dis);

                return view('supervisor' ,compact('gestiones_d','gestiones_v','gestiones_dis','gestiones_dr','gestiones_vr','gestiones_disr', 'archivos'));
            }
            if($id==7) {
                $campanias = campaign::where('estatus', 'A')->get();
                return view('predictivos.predictivo', compact('campanias'));
            }
            if($id==8) {
                return redirect()->action('ReportesNuevoSistema\ReportesNsController@reportesDiners');
            }
            if($id==9) {
                return redirect()->action('ReportesNuevoSistema\Reportes29Controller@reportes29');
            }
            if($id==10) {
                return redirect()->action('ReportesNuevoSistema\ReportesEquifaxController@reportes');
            }
            if($id==11) {
                return redirect()->action('ReportesNuevoSistema\ReportesBcoGuayaquilController@reportes');
            }
            if($id==12) {
                return redirect()->action('ReportesNuevoSistema\ReportesCexController@reportes');
            }
            if($id==13) {
                return redirect()->action('ReportesNuevoSistema\ReportesCexController@encuestas');
            }
            if($id==14) {
                return redirect()->action('ReportesNuevoSistema\ReportesBelcorpController@reportes');
            }
            if($id==15) {
                return view('home');
            }
            if($id==16) {
                return redirect()->action('ReportesNuevoSistema\ReportesAmtController@reportes');
            }
            if($id==17){
                return redirect()->action('Ivr\IvrController@index');
            }
            if($id==18){
                return redirect()->action('ReportesNuevoSistema\ReportesPeruController@reportes');
            }
        }
    }

    public function nuevaBusqueda()
    {
        $user = Auth::user();
        return view('home');
    }

    public function enviados()
    {
        $user = Auth::user();
        if($user->role_id==3 || $user->role_id==7){
            $gestiones_d = gestiones::where('marca','DINERS CLUB')->
            where('id_formato',1)->
            where('consolidado',1)->
            whereBetween('id_estado_gestion', [2, 5])->orderBy('id', 'desc')->get();

            $gestiones_vr = gestiones::where('marca','VISA INTERDIN')->
            where('id_formato',2)->
            where('consolidado',1)->
            whereBetween('id_estado_gestion', [2, 5])->orderBy('id', 'desc')->get();

            $gestiones_v = gestiones::where('marca','VISA INTERDIN')->
            where('id_formato',1)->
            where('consolidado',1)->
            whereBetween('id_estado_gestion', [2, 5])->orderBy('id', 'desc')->get();

            $gestiones_disr = gestiones::where('marca','DISCOVER')->
            where('id_formato',2)->
            where('consolidado',1)->
            whereBetween('id_estado_gestion', [2, 5])->orderBy('id', 'desc')->get();

            $gestiones_dis = gestiones::where('marca','DISCOVER')->
            where('id_formato',1)->
            where('consolidado',1)->
            whereBetween('id_estado_gestion', [2, 5])->orderBy('id', 'desc')->get();

            return view('enviados' ,compact('gestiones_d','gestiones_v','gestiones_dis','gestiones_dr','gestiones_vr','gestiones_disr', 'archivos'));

        }
        if($user->role_id==4 || $user->role_id==5){
            return redirect()->action('Ivr\IvrController@index');
        }
    }

    public function respondidos()
    {
        $user = Auth::user();
        if($user->role_id==3 || $user->role_id==7){
            $gestiones_d = gestiones::where('marca','DINERS CLUB')->
            where('id_estado_gestion','>=',6)->
            orderBy('id', 'desc')->get();

            $gestiones_vr = gestiones::where('marca','=','VISA INTERDIN')->
            where('id_formato',2)->
            where('id_estado_gestion','>=',6)->orderBy('id', 'desc')->get();

            $gestiones_v = gestiones::where('marca','=','VISA INTERDIN')->
            where('id_formato',1)->
            where('id_estado_gestion','>=',6)->orderBy('id', 'desc')->get();

            $gestiones_disr = gestiones::where('marca','DISCOVER')->
            where('id_formato',2)->
            where('id_estado_gestion','>=',6)->orderBy('id', 'desc')->get();

            $gestiones_dis = gestiones::where('marca','DISCOVER')->
            where('id_formato',1)->
            where('id_estado_gestion','>=',6)->orderBy('id', 'desc')->get();

            return view('respondidos' ,compact('gestiones_d','gestiones_v','gestiones_dis','gestiones_dr','gestiones_vr','gestiones_disr', 'archivos'));
        }

        if($user->role_id==4 || $user->role_id==5){
            return redirect()->action('Ivr\IvrController@index');
        }
    }

    public function historico()
    {
        $user = Auth::user();

        if($user->role_id==3 || $user->role_id==7){

            $gestiones_d = gestiones_historico::where('marca','DINERS CLUB')->
            OrWhere('marca','DINERS')
            ->orderBy('id', 'desc')->limit(500)->get();


            $gestiones_vr = gestiones_historico::where('marca','=','VISA INTERDIN')->
            where('id_formato',2)
            ->orderBy('id', 'desc')->get();

            $gestiones_v = gestiones_historico::where('marca','=','VISA INTERDIN')->
            where('id_formato',1)
            ->orderBy('id', 'desc')->get();

            $gestiones_disr = gestiones_historico::where('marca','DISCOVER')->
            where('id_formato',2)
            ->orderBy('id', 'desc')->get();

            $gestiones_dis = gestiones_historico::where('marca','DISCOVER')->
            where('id_formato',1)
            ->orderBy('id', 'desc')->get();

            return view('historico' ,compact('gestiones_d','gestiones_v','gestiones_dis','gestiones_dr','gestiones_vr','gestiones_disr', 'archivos'));
        }

        if($user->role_id==4 || $user->role_id==5){
            return redirect()->action('Ivr\IvrController@index');
        }
    }

    public function procesarEnviados(Request $request)
    {
        if($request->descarga!=null) {
            $cabecera_refinanciamiento = array('Nros', 'FECHA SOLICITUD', 'MARCA', 'COD MOTIVO', 'MOTIVO DE NO PAGO', 'EMPRESA EXTERNA', 'OFICIAL RESPONSABLE', 'COD ENCARGADO', 'TIPO', 'DIGITOS CÉDULA', 'VALIDA CI', 'NOMBRE DEL CLIENTE', 'PLAZO', 'CICLO', 'CONSOLIDACION', 'OBSERVACIONES CONSOLIDACION', 'TIPO DE REFINANCIACION', 'CIUDAD', 'ZONA',
                'VALOR DEUDA A REF','FIRMA DOCUMENTOS','VALIDACION CONYUGE',
                'ESTADO CIVIL', 'CI CONYUGE ', 'NOMBRE CONYUGE',   'OBSERVACION EXCEPCION', 'DIRECCION NEG. CON FIRMA DOCUMENTOS', 'INGRESOS', 'GASTOS', 'VALOR ABONO MISMO DIA DEL CORTE', 'TELEFONOS', 'CAMPAÑA', 'GESTOR', 'DEBITO AUTOMÁTICO', 'ACTIVIDAD ECONOMICA');

            $cabecera_rotativo = array('Nro', 'FECHA SOLICITUD', 'MARCA', 'GESTOR', 'CORTE', 'CUENTA', 'CEDULA', 'NOMBRE', 'CODIGO DE CANCELACION ACTUAL', 'EDAD REAL', 'STS CANC SOLICITADO', 'SOLICITA CAMBIO FORMA DE PAGO A MINIMO', 'PRECANCELACION DIFERIDO', 'VALOR PAGO EXIGIBLE', 'VALOR ABONO', 'VALOR CREDITO', 'VALOR DEBITO', 'CUPO', 'TOTAL RIESGO DEUDA','DEBITO AUTOMATICO','TIPO DE CUENTA NORMAL/ESPECIAL','INGRESOS REALES','TIPO DE TRABAJO (FIJO / TEMPORAL / SIN TRABAJO'
            );


            if ($request->tarjeta == 'DINERS CLUB') {
                $gestiones = gestiones::select('id','created_at','marca','cod_motivo','motivo_no_pago','empresa_externa','oficial_responsable','cod_encargado','tipo','digitos_cedula','valida_ci',
                    'nombre_cliente','plazo','ciclo','consolidacion','observaciones_consolidacion','tipo_de_refinanciacion','ciudad','zona','valor_deuda_a_ref','firma_documentos',
                    'validacion_conyugue',
                    'estado_civil','ci_conyugue','nombre_conyugue',
                    'observaciones_excepcion','direccion_neg_con_firma_documentos','ingresos_reales','gastos_reales',
                    'valor_abono_mismo_dia_del_corte','telefonos','campana','gestor',
                    'debito_automatico','actividad_economica')->whereIn('id', $request->gestion)
                    ->where('marca', $request->tarjeta)->get();
                foreach ($gestiones as $gestion){
                    $gestion['valor_deuda_a_ref']=number_format($gestion['valor_deuda_a_ref'], 2, ',', '');
                    $gestion['ingresos_reales']=number_format($gestion['ingresos_reales'], 2, ',', '');
                    $gestion['gastos_reales']=number_format($gestion['gastos_reales'], 2, ',', '');
                }
                $gestiones = $gestiones->toArray();

                array_unshift($gestiones, $cabecera_refinanciamiento);
                generarExcel($request->tarjeta, 'REFINANCIAMIENTO', $gestiones);
            }

            if ($request->tarjeta=='VISA INTERDIN' && $request->formato=='rotativo') {
                $gestiones = gestiones::select('id','created_at','marca','gestor','ciclo','cuenta','digitos_cedula', 'nombre_cliente','sts_canc_actual','edad_real','sts_canc_solicitado','solicita_cambio_forma_de_pago_minimo','precancelacion_diferido','valor_pago_exigible','valor_abono','valor_credito','valor_debito',
                    'cupo','total_riesgo_deuda','debito_automatico','tipo_cuenta','ingresos_reales','actividad_economica')->whereIn('id', $request->gestion)
                    ->where('marca',$request->tarjeta)
                    ->where('id_formato','=',2)
                    ->where('consolidado','=',1)->get();
                foreach ($gestiones as $gestion){
                    $gestion['valor_pago_exigible']=number_format($gestion['valor_pago_exigible'], 2, ',', '');
                    $gestion['valor_abono']=number_format($gestion['valor_abono'], 2, ',', '');
                    $gestion['valor_credito']=number_format($gestion['valor_credito'], 2, ',', '');
                    $gestion['valor_debito']=number_format($gestion['valor_debito'], 2, ',', '');
                    $gestion['total_riesgo_deuda']=number_format($gestion['total_riesgo_deuda'], 2, ',', '');
                    $gestion['ingresos_reales']=number_format($gestion['ingresos_reales'], 2, ',', '');
                }
                $gestiones=$gestiones->toArray();

                if($request->descarga==null){
                    gestiones::whereIn('id', $request->gestion)->update(['consolidado' => 1, 'id_estado_gestion' => 2]);
                }
                $gestioneszip=$gestiones;
                array_unshift($gestiones,$cabecera_rotativo);
                generarExcel($request->tarjeta,'ROTATIVO',$gestiones);
                generarZip($gestioneszip);
                $zip=public_path('storage/refinanciamientos/consolidado/'.date('Y-m-d').'.zip');

                try
                {
                    return response()->download($zip);
                }catch (\Exception $e) {
                    return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
                }

            }elseif ($request->tarjeta=='VISA INTERDIN' && $request->formato=='refinanciamiento'){
                $gestiones=gestiones::select('id','created_at','marca','cod_motivo','motivo_no_pago','empresa_externa','oficial_responsable','cod_encargado','tipo','digitos_cedula','valida_ci',
                    'nombre_cliente','plazo','ciclo','consolidacion','observaciones_consolidacion','tipo_de_refinanciacion','ciudad','zona','valor_deuda_a_ref','firma_documentos',
                    'validacion_conyugue',
                    'estado_civil','ci_conyugue','nombre_conyugue',
                    'observaciones_excepcion','direccion_neg_con_firma_documentos','ingresos_reales','gastos_reales',
                    'valor_abono_mismo_dia_del_corte','telefonos','campana','gestor',
                    'debito_automatico','actividad_economica')->whereIn('id', $request->gestion)
                    ->where('marca',$request->tarjeta)
                    ->where('id_formato','=',1)
                    ->where('consolidado','=',1)->get();
                foreach ($gestiones as $gestion){
                    $gestion['valor_deuda_a_ref']=number_format($gestion['valor_deuda_a_ref'], 2, ',', '');
                    $gestion['ingresos_reales']=number_format($gestion['ingresos_reales'], 2, ',', '');
                    $gestion['gastos_reales']=number_format($gestion['gastos_reales'], 2, ',', '');
                }
                $gestiones=$gestiones->toArray();

                if($request->descarga==null){
                    gestiones::whereIn('id', $request->gestion)->update(['consolidado' => 1, 'id_estado_gestion' => 2]);
                }
                $gestioneszip=$gestiones;
                array_unshift($gestiones,$cabecera_refinanciamiento);
                generarExcel($request->tarjeta,'REFINANCIAMIENTO',$gestiones);
                generarZip($gestioneszip);
                $zip=public_path('storage/refinanciamientos/consolidado/'.date('Y-m-d').'.zip');

                try
                {
                    return response()->download($zip);
                }catch (\Exception $e) {
                    return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
                }
            }

            if($request->tarjeta=='DISCOVER' && $request->formato=='rotativo'){
                $gestiones = gestiones::select('id','created_at','marca','gestor','ciclo','cuenta','digitos_cedula', 'nombre_cliente','sts_canc_actual','edad_real','sts_canc_solicitado','solicita_cambio_forma_de_pago_minimo','precancelacion_diferido','valor_pago_exigible','valor_abono','valor_credito','valor_debito',
                    'cupo','total_riesgo_deuda','debito_automatico','tipo_cuenta','ingresos_reales','actividad_economica')->whereIn('id', $request->gestion)
                    ->where('marca',$request->tarjeta)
                    ->where('id_formato','=',2)
                    ->where('consolidado','=',1)->get();
                foreach ($gestiones as $gestion){
                    $gestion['valor_pago_exigible']=number_format($gestion['valor_pago_exigible'], 2, ',', '');
                    $gestion['valor_abono']=number_format($gestion['valor_abono'], 2, ',', '');
                    $gestion['valor_credito']=number_format($gestion['valor_credito'], 2, ',', '');
                    $gestion['valor_debito']=number_format($gestion['valor_debito'], 2, ',', '');
                    $gestion['total_riesgo_deuda']=number_format($gestion['total_riesgo_deuda'], 2, ',', '');
                    $gestion['ingresos_reales']=number_format($gestion['ingresos_reales'], 2, ',', '');
                }
                $gestiones=$gestiones->toArray();
                $gestioneszip=$gestiones;
                array_unshift($gestiones,$cabecera_rotativo);

                if($request->descarga==null){
                    gestiones::whereIn('id', $request->gestion)->update(['consolidado' => 1, 'id_estado_gestion' => 2]);
                }

                generarExcel($request->tarjeta,'ROTATIVO',$gestiones);
                generarZip($gestioneszip);
                $zip=public_path('storage/refinanciamientos/consolidado/'.date('Y-m-d').'.zip');

                try
                {
                    return response()->download($zip);
                }catch (\Exception $e) {
                    return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
                }
            }elseif ($request->tarjeta=='DISCOVER' && $request->formato=='refinanciamiento'){
                $gestiones=gestiones::select('id','created_at','marca','cod_motivo','motivo_no_pago','empresa_externa','oficial_responsable','cod_encargado','tipo','digitos_cedula','valida_ci',
                    'nombre_cliente','plazo','ciclo','consolidacion','observaciones_consolidacion','tipo_de_refinanciacion','ciudad','zona','valor_deuda_a_ref','firma_documentos',
                    'validacion_conyugue',
                    'estado_civil','ci_conyugue','nombre_conyugue',
                    'observaciones_excepcion','direccion_neg_con_firma_documentos','ingresos_reales','gastos_reales',
                    'valor_abono_mismo_dia_del_corte','telefonos','campana','gestor',
                    'debito_automatico','actividad_economica')->whereIn('id', $request->gestion)
                    ->where('marca',$request->tarjeta)
                    ->where('id_formato','=',1)
                    ->where('consolidado','=',1)->get();
                foreach ($gestiones as $gestion){
                    $gestion['valor_deuda_a_ref']=number_format($gestion['valor_deuda_a_ref'], 2, ',', '');
                    $gestion['ingresos_reales']=number_format($gestion['ingresos_reales'], 2, ',', '');
                    $gestion['gastos_reales']=number_format($gestion['gastos_reales'], 2, ',', '');
                }
                $gestiones=$gestiones->toArray();

                if($request->descarga==null){
                    gestiones::whereIn('id', $request->gestion)->update(['consolidado' => 1, 'id_estado_gestion' => 2]);
                }
                $gestioneszip=$gestiones;
                array_unshift($gestiones,$cabecera_refinanciamiento);
                generarExcel($request->tarjeta,'REFINANCIAMIENTO',$gestiones);
                generarZip($gestioneszip);
                $zip=public_path('storage/refinanciamientos/consolidado/'.date('Y-m-d').'.zip');

                try
                {
                    return response()->download($zip);
                }catch (\Exception $e) {
                    return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
                }
            }
        }else {
            $user = Auth::user();
            //dd($request);

            if ($user->role_id == 3) {
                $observacion = '';
                if ($request->observaciondin) {
                    $observacion = $request->observaciondin;
                }
                if ($request->observacionv) {
                    $observacion = $request->observacionv;
                }
                if ($request->observaciondis) {
                    $observacion = $request->observaciondis;
                }

                foreach ($request->gestion as $g){
                    $observa=gestiones::where('id', $g)->first()->observacion;
                    gestiones::where('id', $g)->update(['id_estado_gestion' => $request->estado, 'observacion' => $observa.' // '.$observacion]);
                }

                return redirect()->action('HomeController@enviados');

            } else {
                return false;
            }

            if ($user->role_id == 4 || $user->role_id == 5) {
                return redirect()->action('Ivr\IvrController@index');
            }
        }
    }

    public function eliminarDuplicados(Request $request)
    {
        //dd($request->id);
        $gestion=gestiones::where('id',$request->id)->first();

        $gestion->id_estado_gestion=estado_gestion::where('descripcion','duplicados')->first()->id;
        $gestion->save();

        return redirect()->action('HomeController@index');
    }

    public function comprimir($id)
    {
        $archivos=archivos::select('ruta','nombre')->where('id_gestion',$id)->get();
        //dd(public_path($archivos[0]->ruta.'/*'));
        $files = glob(public_path($archivos[0]->ruta.'/*'));
        //Zipper::make('storage/'.$id.'.zip')->add($files)->close();

//SHELL
        chdir('storage/refinanciamientos/');
        shell_exec('rm -rf tmp/');
        shell_exec('mkdir tmp');

        shell_exec('mkdir tmp/tmp-'.$id.'/');
        shell_exec('cp -r '.$id.'/ tmp/tmp-'.$id.'/');
        $gestion=gestiones::where('id',$id)->first();
        $nombre='tmp/tmp-'.$id.'/'.$id;
        $renombre='tmp/tmp-'.$id.'/'.str_replace(" ", "_", $gestion->nombre_cliente);
        $cliente=str_replace(" ", "_", $gestion->nombre_cliente);
        //echo 'mv tmp-'.$id.'/'.$id.' '.$renombre;

        shell_exec('mv '.$nombre.' '.$renombre);
        //shell_exec('cd ');
        chdir('tmp/tmp-'.$id.'/');
        shell_exec('zip -r '.$id.'.zip '.$cliente);


        //$salida = shell_exec('ls -lart');
        //echo "<pre>$salida</pre>";

//FIN SHELL

        //return $archivos[0]->ruta.'/*';
        return response()->download(public_path('storage/refinanciamientos/tmp/tmp-'.$id.'/'.$id.'.zip'))->deleteFileAfterSend(true);
    }
}

function valida_duplicados($objeto){
/*
    //Inicio valida duplicados
    $gestiones_da=$objeto->toArray();
    $ges=gestiones_historico::where('digitos_cedula',$objeto[22]->digitos_cedula)->first();

    $i=0;
    foreach ($objeto as $k)
    {

        $total=gestiones_historico::where('digitos_cedula',$k['digitos_cedula'])->first();
        if ($total){
            $k->duplicado='duplicado';
        }else{

        }
        $i++;


    }

    return $objeto;
*/

    $gestiones_da=$objeto->toArray();

    $pila= array();
    foreach ($gestiones_da as $k)
    {
        array_push($pila, $k['digitos_cedula']);
    }

    $total=array();
    $i=0;
    foreach ($gestiones_da as $k)
    {
        $array=array_intersect ($pila, $k);
        $gestiones_da[$i]['duplicado']='duplicado';
        if(count($array)>1){
            array_push($total, $array);
        }
        $i++;
    }

    foreach ($total as $k) {
        foreach ($k as $key=>$value){
            $objeto[$key]->duplicado='duplicado-'.$value;
            $duplicados[$key]='duplicado-'.$value;
        }
    }

    return $objeto;
    //fin valida duplicados

}

function generarExcel($tarjeta,$formato,Array $proceso){
    Excel::create($formato.' '.$tarjeta.' '.date("Y-m-d h:m:s"), function ($excel) use ($proceso) {
        $excel->sheet('Sheetname', function ($sheet) use ($proceso) {
            $sheet->fromArray($proceso, null, 'A1', false, false);
            $sheet->row(1, function ($row) {
                $row->setBackground('#95b3d7');
            });
        });
    })->download('xlsx');
}