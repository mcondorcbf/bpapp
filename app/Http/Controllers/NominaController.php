<?php
namespace App\Http\Controllers;

use App\nomina\cabecera;
use App\nomina\detalle;
use App\nomina\tipo;
use App\reportesNuevoSistema\tbl_campaigns;
use App\reportesNuevoSistema\tbl_products;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class NominaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $rol= Role::where('id',$user->role_id)->first();
        $usuarios = User::where('role_id',$rol->id)->get();

        $dir = public_path() . '/storage/temporalivr/';

        return view('nomina/index', compact('user','campanias','clientes','scripts','usuarios','dir'));
    }

    public function depurarNomina()
    {
        if(Input::hasFile('file')){
            $path = Input::file('file')->getRealPath();
            $outa=array();

            $out = array (
                0 =>'FILA ',     // resultado (numero corregido)
                1 =>' ERROR',     // error en texto
            );
            array_push($outa, $out);
            $result='';



            Excel::load($path, function($file) use (&$result, &$outa)
            {
                $result='';
                $errores=0;
                $error='';
                foreach ($file->get() as $carga) {

                    if(!isset($carga['n'])){ $error.=' A -'; $errores++; }
                    if(!isset($carga['nombre_tipo_de_documento'])){ $error.=' B -'; $errores++;}
                    if(!isset($carga['ciudad'])){ $error.=' C -'; $errores++;}
                    if(!isset($carga['identificador'])){ $error.=' D -'; $errores++;}
                    if(!isset($carga['nombre_comercial'])){ $error.=' E -'; $errores++;}
                    if(!isset($carga['departamento'])){ $error.=' F -'; $errores++;}
                    if(!isset($carga['producto'])){ $error.=' G -'; $errores++;}
                    if(!isset($carga['cargo'])){ $error.=' H -'; $errores++;}
                    if(!isset($carga['categoria_contable'])){ $error.=' I -'; $errores++;}
                    if(!isset($carga['fecha_de_ingreso'])){ $error.=' J -'; $errores++;}
                    if(!isset($carga['dias_trabajados'])){ $error.=' K -'; $errores++;}
                    if(!isset($carga['sueldo_nominal'])){ $error.=' L -'; $errores++;}
                    if(!isset($carga['sueldo_mensual'])){ $error.=' M -'; $errores++;}
                    if(!isset($carga['valor_horas_suplementarias'])){ $error.=' N -'; $errores++;}
                    if(!isset($carga['valor_horas_extrahordinarias'])){ $error.=' O -'; $errores++;}
                    if(!isset($carga['ajuste_comisiones'])){ $error.=' P -'; $errores++;}
                    if(!isset($carga['comisiones'])){ $error.=' Q -'; $errores++;}
                    if(!isset($carga['bonos'])){ $error.=' R -'; $errores++;}
                    if(!isset($carga['movilizacion'])){ $error.=' S -'; $errores++;}
                    if(!isset($carga['otros_ingresos'])){ $error.=' T -'; $errores++;}
                    if(!isset($carga['otros_ingresos_no'])){ $error.=' U -'; $errores++;}
                    if(!isset($carga['decimo_cuarto_mensualizado_d'])){ $error.=' V -'; $errores++;}
                    if(!isset($carga['decimo_tercero'])){ $error.=' W -'; $errores++;}
                    if(!isset($carga['pago_fondos_de_reserva'])){ $error.=' X -'; $errores++;}
                    if(!isset($carga['base_imponible'])){ $error.=' Y -'; $errores++;}
                    if(!isset($carga['total_ingresos'])){ $error.=' Z -'; $errores++;}
                    if(!isset($carga['aporte_personal'])){ $error.=' AA -'; $errores++;}
                    if(!isset($carga['impuesto_a_la_renta'])){ $error.=' AB -'; $errores++;}
                    if(!isset($carga['prestamos_quirografarios'])){ $error.=' AC -'; $errores++;}
                    if(!isset($carga['prestamos_hipotecarios'])){ $error.=' AD -'; $errores++;}
                    if(!isset($carga['anticipo_de_sueldo'])){ $error.=' AE -'; $errores++;}
                    if(!isset($carga['prestamos_empresa'])){ $error.=' AF -'; $errores++;}
                    if(!isset($carga['prestamos_bgr'])){ $error.=' AG -'; $errores++;}
                    if(!isset($carga['seguro_medico'])){ $error.=' AH -'; $errores++;}
                    if(!isset($carga['comisiones_anticipadas'])){ $error.=' AI -'; $errores++;}
                    if(!isset($carga['retencion_judicial'])){ $error.=' AJ -'; $errores++;}
                    if(!isset($carga['subsidio'])){ $error.=' AK -'; $errores++;}
                    if(!isset($carga['subsidio_maternidad'])){ $error.=' AL -'; $errores++;}
                    if(!isset($carga['viaticos'])){ $error.=' AM -'; $errores++;}
                    if(!isset($carga['extension_conyugal'])){ $error.=' AN -'; $errores++;}
                    if(!isset($carga['plan_celular'])){ $error.=' AO -'; $errores++;}
                    if(!isset($carga['roaming_celular'])){ $error.=' AP -'; $errores++;}
                    if(!isset($carga['megas_adicionales'])){ $error.=' AQ -'; $errores++;}
                    if(!isset($carga['chip_celular'])){ $error.=' AR -'; $errores++;}
                    if(!isset($carga['adendum_celular'])){ $error.=' AS -'; $errores++;}
                    if(!isset($carga['gimnasio'])){ $error.=' AT -'; $errores++;}
                    if(!isset($carga['atrasos'])){ $error.=' AU -'; $errores++;}
                    if(!isset($carga['llamados_de_atencion'])){ $error.=' AV -'; $errores++;}
                    if(!isset($carga['descuento_dias_y_horas'])){ $error.=' AX -'; $errores++;}
                    if(!isset($carga['otros_descuentos'])){ $error.=' AY -'; $errores++;}
                    if(!isset($carga['total_egresos'])){ $error.=' AY -'; $errores++;}
                    if(!isset($carga['liquido_a_recibir'])){ $error.=' AZ -'; $errores++;}
                    if(!isset($carga['diferencia'])){ $error.=' BA -'; $errores++;}
                    if(!isset($carga['tipo_cuenta'])){ $error.=' BB -'; $errores++;}
                    if(!isset($carga['cuenta'])){ $error.=' BC'; $errores++;}
                    if(!isset($carga['periodo_mes'])){ $error.=' BD'; $errores++;}
                    if(!isset($carga['periodo_anio'])){ $error.=' BE'; $errores++;}

                    if($errores>0){
                        $result=500;
                        $outa=  array (
                            0 => 'Error: '.$error.' - '.$errores,
                            1 => 'SE ENCONTRARON ERRORES EL ARCHIVO NO CONTIENE TODAS LAS COLUMNAS!!'
                        );
                    }else{
                        $result=200;
                        $outa=  array (
                            0 => 'Archivo analizado: ',
                            1 => 'Validado exitosamente'
                        );
                    }
                    return \Response::json($outa, $result);
                    dd();
                }




            });//carga::all();
            //-> download('xls');
            //return \Response::json(['resultado' => $result], 200);
            //$outa = json_encode($outa);
            return \Response::json($outa, 200);
            $data =Excel::load($path, function($reader){})->get();

            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $insert[] = ['title' => $value->title, 'description' => $value->description];
                }
                /*if(!empty($insert)){
                    DB::table('items')->insert($insert);
                    dd('Insert Record successfully.');
                }*/
            }
            dd($data->count());
        }
        return back();
        $user = Auth::user();
        return view('ivr/cargaBase/nuevoIvr', compact('user'));
    }

    public function nomina()
    {
        if(Input::hasFile('file')){
            $path = Input::file('file')->getRealPath();
            $outa=array();

            $out = array (
                0 =>'FILA ',     // resultado (numero corregido)
                1 =>' ERROR',     // error en texto
            );
            array_push($outa, $out);
            $result='';


            ini_set ( 'memory_limit' , '7000M' );
            ini_set('max_execution_time', 1200);
            Excel::load($path, function($file) use (&$result, &$outa)
            {
                $result='';
                $errores=0;
                $error='';

                ini_set ( 'memory_limit' , '7000M' );
                ini_set('max_execution_time', 1200);
                foreach ($file->get() as $carga)
                {

                    $cabecera=new cabecera();
                    $cabecera->tipo_documento=$carga->nombre_tipo_de_documento;
                    $cabecera->documento=$carga->identificador;
                    $cabecera->ciudad=$carga->ciudad;
                    $cabecera->nombre_comercial=$carga->nombre_comercial;
                    $cabecera->departamento=$carga->departamento;
                    $cabecera->producto=$carga->producto;
                    $cabecera->cargo=$carga->cargo;
                    $cabecera->categoria_contable=$carga->categoria_contable;
                    $cabecera->fecha_ingreso=$carga->fecha_de_ingreso;
                    $cabecera->dias_trabajados=$carga->dias_trabajados;
                    $cabecera->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','sueldo_nominal')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->sueldo_nominal;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','sueldo_mensual')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->sueldo_mensual;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','valor_horas_suplementarias')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->valor_horas_suplementarias;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','valor_horas_extrahordinarias')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->valor_horas_extrahordinarias;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','ajuste_comisiones')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->ajuste_comisiones;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','comisiones')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->comisiones;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','bonos')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->bonos;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','movilizacion')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->movilizacion;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','otros_ingresos')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->otros_ingresos;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','otros_ingresos_no')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->otros_ingresos_no;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','decimo_cuarto_mensualizado_d')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->decimo_cuarto_mensualizado_d;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','decimo_tercero')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->decimo_tercero;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','pago_fondos_de_reserva')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->pago_fondos_de_reserva;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','base_imponible')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->base_imponible;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','total_ingresos')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->total_ingresos;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','aporte_personal')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->aporte_personal;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','impuesto_a_la_renta')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->impuesto_a_la_renta;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','prestamos_quirografarios')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->prestamos_quirografarios;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','prestamos_hipotecarios')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->prestamos_hipotecarios;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','anticipo_de_sueldo')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->anticipo_de_sueldo;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','prestamos_empresa')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->prestamos_empresa;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','prestamos_bgr')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->prestamos_bgr;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','seguro_medico')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->seguro_medico;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','comisiones_anticipadas')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->comisiones_anticipadas;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','retencion_judicial')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->retencion_judicial;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','subsidio')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->subsidio;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','subsidio_maternidad')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->subsidio_maternidad;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','viaticos')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->viaticos;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','extension_conyugal')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->extension_conyugal;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','plan_celular')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->plan_celular;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','roaming_celular')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->roaming_celular;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','megas_adicionales')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->megas_adicionales;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','chip_celular')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->chip_celular;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','adendum_celular')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->adendum_celular;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','gimnasio')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->gimnasio;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','atrasos')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->atrasos;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','llamados_de_atencion')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->llamados_de_atencion;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','descuento_dias_y_horas')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->descuento_dias_y_horas;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','otros_descuentos')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->otros_descuentos;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','liquido_a_recibir')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->liquido_a_recibir;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','diferencia')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->diferencia;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','tipo_cuenta')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->tipo_cuenta;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','cuenta')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->cuenta;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();

                    $detalle = new detalle();
                    $valor=tipo::where('nombre','total_egresos')->first();
                    $detalle->id_tipo=$valor->id_tipo;
                    $detalle->valor=$carga->total_egresos;
                    $detalle->periodo_mes=$carga->periodo_mes;
                    $detalle->periodo_anio=$carga->periodo_anio;
                    $detalle->id_cabecera=$cabecera->id_cabecera;
                    $detalle->save();
                }

            });

            return \Response::json('Archivo subido exitosamente', 200);
            $data=Excel::load($path, function($reader){})->get();

            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $insert[] = ['title' => $value->title, 'description' => $value->description];
                }
            }
            dd($data->count());
        }
        return back();
        $user = Auth::user();
        return view('ivr/cargaBase/nuevoIvr', compact('user'));
    }

    public function reportes()
    {
        $campanas=tbl_campaigns::where('product_id',24)->where('enabled',1)->whereNull('deleted_at')->orderBy('id','DESC')->pluck("name","id")->all();
        return view('reporteNuevoSistema/amt/index', compact('campanas'));
    }

    public function reporteAmt(Request $request)
    {
        $fecha = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Ymd');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time',900);
        try{
            $reportes=DB::connection('cobefec3151')->select("call cobefec_reportes.sp_atm2('".$fecha."',$request->id_campana);");
            $reportes=json_decode(json_encode($reportes), True);
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
            set_time_limit(0);
            ini_set ( 'memory_limit','-1' );
            ini_set('max_execution_time',900);
        try{
            \Excel::create('REPORTE HISTORIAL DE GESTIONES ATM COBEFEC '.date('d-m-Y'), function($excel) use (&$reportes){
                $excel->sheet('REPORTE', function($sheet) use($reportes) {
                    $sheet->loadView('reporteNuevoSistema/amt/tableAcumuladoGestiones')->with('reportes',$reportes);
                    //$sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
        return view('reporteNuevoSistema/amt/index');
    }

    public function reporteMarcacionesAtm(Request $request)
    {
        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio_m)->format('Ymd');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin_m)->format('Ymd');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time',900);
        try{
            $reportes=DB::connection('cobefec3')->select("
select d.id, date(d.created_at) fecha, TIME(d.created_at) hora, d.document documento, a.data ->> '$.nombres_completos' nombres, d.phone telefono, d.contact_type tipo_contacto, d.type tipo, d.action accion, d.sub_action subaccion, if(d.type='MN',if(d.contact_type='NC','NO ANSWER','ANSWER'),'') estatus, d.reason motivo, d.description descripcion, d.extra ->> '$.pp_date'  fecha_pp, d.extra ->> '$.pp_amount' valor, c.name nombre_campana, a.data ->> '$.tipo_de_cartera' tipo_de_cartera, a.data ->> '$.saldo' saldos
from cobefec3.demarches d, cobefec3.accounts a, cobefec3.campaigns c where account_id in (select id from cobefec3.accounts where campaign_id in (select id from cobefec3.campaigns where product_id in (select id from cobefec3.products where brand_id=13))) 
and date(d.created_at) BETWEEN date('".$fecha_inicio."') and date('".$fecha_fin."') and a.id=d.account_id and a.campaign_id=c.id and d.validated=1 and d.action <> 'ENVIAR IVR'
UNION ALL
select d.id, date(d.created_at) fecha, TIME(d.created_at) hora, d.document documento, a.data ->> '$.nombres_completos' nombres, d.phone telefono, d.contact_type tipo_contacto, d.type tipo, d.action accion, d.sub_action subaccion, if(d.sub_action='CONTESTA IVR','ANSWER','NO ANSWER') estatus, d.reason motivo, d.description descripcion, d.extra ->> '$.pp_date'  fecha_pp, d.extra ->> '$.pp_amount' valor, c.name nombre_campana, a.data ->> '$.tipo_de_cartera' tipo_de_cartera, a.data ->> '$.saldo' saldos
from cobefec3.demarches d, cobefec3.accounts a, cobefec3.campaigns c where 
d.id in (
select id_gestion_original from cobefec_reportes.atm_gestionivrs where id_carga in (SELECT id_carga FROM cobefec_reportes.atm_ivr_idcarga where date(fecha) BETWEEN date('".$fecha_inicio."') and date('".$fecha_fin."')) and id_gestion_original is not null
) and a.id=d.account_id and a.campaign_id=c.id and d.validated=1;
");

            $reportes=json_decode(json_encode($reportes), true);
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
        set_time_limit(0);
        ini_set ( 'memory_limit','-1' );
        ini_set('max_execution_time',900);
        try{
            \Excel::create('REPORTE DE MARCACIONES ATM DESDE '.$fecha_inicio.' HASTA '.$fecha_fin, function($excel) use (&$reportes){
                $excel->sheet('REPORTE', function($sheet) use($reportes) {
                    //$sheet->loadView('reporteNuevoSistema/amt/tableAcumuladoGestiones')->with('reportes',$reportes);
                    $sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
        return view('reporteNuevoSistema/amt/index');
    }

    public function reporteGeneralCuentas(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '800M');
        ini_set('max_execution_time',900);
        try{
            $reportes=DB::connection('cobefec3')->select("call cobefec_reportes.sp_atm_gral_cuentas(".$request->id_campana.");");

            $reportes=json_decode(json_encode($reportes), True);
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }

        set_time_limit(0);
        ini_set ( 'memory_limit','-1' );
        ini_set('max_execution_time',900);
        try{
            \Excel::create('REPORTE GENERAL DE CUENTAS AMT COBEFEC '.date('d-m-Y'), function($excel) use (&$reportes){
                $excel->sheet('REPORTE', function($sheet) use($reportes) {
                    $sheet->loadView('reporteNuevoSistema/amt/tableGeneralCuentas')->with('reportes',$reportes);
                    //$sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
        return view('reporteNuevoSistema/amt/index');
    }
}