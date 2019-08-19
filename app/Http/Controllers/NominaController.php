<?php
namespace App\Http\Controllers;

use App\Mail\RolNomina;
use App\nomina\cabecera;
use App\nomina\detalle;
use App\nomina\tipo;
use App\reportesNuevoSistema\tbl_campaigns;
use App\reportesNuevoSistema\tbl_products;
use App\Role;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
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
                    if(!isset($carga['periodo'])){ $error.=' BF'; $errores++;}

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
            $count=0;
            Excel::load($path, function($file) use (&$result, &$outa, &$count)
            {
                $result='';
                $errores=0;
                $error='';

                ini_set ( 'memory_limit' , '7000M' );
                ini_set('max_execution_time', 1200);

                foreach ($file->get() as $carga)
                {

                    $cabecera=cabecera::where('documento',$carga->identificador)->first();

                    if(isset($cabecera)){
                        $tipo=detalle::where('id_cabecera',$cabecera->id_cabecera)->where('periodo_mes',$carga->periodo_mes)->where('periodo_anio',$carga->periodo_anio)->first();
                    }else{
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
                        $cabecera->diferencia=$carga->diferencia;
                        $cabecera->tipo_cuenta=$carga->tipo_cuenta;
                        $cabecera->cuenta_bancaria=$carga->cuenta;
                        $cabecera->save();
                    }
                    if(isset($tipo)==false) {

                        if (!is_null($carga->sueldo_nominal)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'sueldo_nominal')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->sueldo_nominal;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->sueldo_mensual)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'sueldo_mensual')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->sueldo_mensual;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->valor_horas_suplementarias)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'valor_horas_suplementarias')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->valor_horas_suplementarias;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->valor_horas_extrahordinarias)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'valor_horas_extrahordinarias')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->valor_horas_extrahordinarias;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->ajuste_comisiones)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'ajuste_comisiones')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->ajuste_comisiones;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->comisiones)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'comisiones')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->comisiones;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->bonos)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'bonos')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->bonos;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->movilizacion)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'movilizacion')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->movilizacion;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->otros_ingresos)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'otros_ingresos')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->otros_ingresos;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->otros_ingresos_no)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'otros_ingresos_no')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->otros_ingresos_no;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->decimo_cuarto_mensualizado_d)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'decimo_cuarto_mensualizado_d')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->decimo_cuarto_mensualizado_d;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->decimo_tercero)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'decimo_tercero')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->decimo_tercero;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->pago_fondos_de_reserva)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'pago_fondos_de_reserva')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->pago_fondos_de_reserva;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->base_imponible)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'base_imponible')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->base_imponible;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->total_ingresos)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'total_ingresos')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->total_ingresos;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->aporte_personal)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'aporte_personal')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->aporte_personal;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->impuesto_a_la_renta)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'impuesto_a_la_renta')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->impuesto_a_la_renta;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->prestamos_quirografarios)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'prestamos_quirografarios')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->prestamos_quirografarios;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->prestamos_hipotecarios)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'prestamos_hipotecarios')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->prestamos_hipotecarios;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->anticipo_de_sueldo)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'anticipo_de_sueldo')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->anticipo_de_sueldo;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->prestamos_empresa)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'prestamos_empresa')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->prestamos_empresa;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }
                        if (!is_null($carga->prestamos_bgr)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'prestamos_bgr')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->prestamos_bgr;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->seguro_medico)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'seguro_medico')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->seguro_medico;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->comisiones_anticipadas)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'comisiones_anticipadas')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->comisiones_anticipadas;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->retencion_judicial)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'retencion_judicial')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->retencion_judicial;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->subsidio)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'subsidio')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->subsidio;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->subsidio_maternidad)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'subsidio_maternidad')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->subsidio_maternidad;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->viaticos)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'viaticos')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->viaticos;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->extension_conyugal)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'extension_conyugal')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->extension_conyugal;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->plan_celular)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'plan_celular')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->plan_celular;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->roaming_celular)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'roaming_celular')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->roaming_celular;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->megas_adicionales)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'megas_adicionales')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->megas_adicionales;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->chip_celular)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'chip_celular')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->chip_celular;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->adendum_celular)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'adendum_celular')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->adendum_celular;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->gimnasio)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'gimnasio')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->gimnasio;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->atrasos)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'atrasos')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->atrasos;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->llamados_de_atencion)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'llamados_de_atencion')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->llamados_de_atencion;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->descuento_dias_y_horas)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'descuento_dias_y_horas')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->descuento_dias_y_horas;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->otros_descuentos)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'otros_descuentos')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->otros_descuentos;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->total_egresos)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'total_egresos')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->total_egresos;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        if (!is_null($carga->liquido_a_recibir)) {
                            $detalle = new detalle();
                            $valor = tipo::where('nombre', 'liquido_a_recibir')->first();
                            $detalle->id_tipo = $valor->id_tipo;
                            $detalle->valor = $carga->liquido_a_recibir;
                            $detalle->periodo_mes = $carga->periodo_mes;
                            $detalle->periodo_anio = $carga->periodo_anio;
                            $detalle->periodo = $carga->periodo;
                            $detalle->id_cabecera = $cabecera->id_cabecera;
                            $detalle->save();
                        }

                        $count++;
                    }

                }
            });

            $outa=  array (
                0 => 'Archivo procesado exitosamente, no se encontraron errores: ',
                1 => 'Se añadieron '.$count.' empleados'
            );
            return \Response::json($outa, 200);
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

    public function procesarEmail()
    {
        $receivers = 'mcondor@cobefec.com';
        try {
            Mail::to($receivers)->send(new RolNomina());
        }catch (\Exception $e){
            return $e->getMessage();
        }

    }
    public function pdf($documento)
    {
        //dd(generarCodigo(10));
        $rol=cabecera::where('documento',$documento)->first();
        $detalles=detalle::where('id_cabecera',$rol->id_cabecera)->get();
        return view('nomina.pdfRoles', compact('rol','detalles'));

        $pdf = PDF::loadView('nomina.pdfRoles', compact('rol','detalles'));
        return $pdf->download('rol.pdf');
    }

}

function generarCodigo($longitud) {
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz*/#.,';
    $max = strlen($pattern)-1;
    for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
    return $key;
}