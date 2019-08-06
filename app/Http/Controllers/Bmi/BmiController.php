<?php
namespace App\Http\Controllers\Bmi;
use App\bmi\tbl_accion;
use App\bmi\tbl_asesores;
use App\bmi\tbl_citas;
use App\bmi\tbl_citas_con_clientes;
use App\bmi\tbl_citas_historial;
use App\bmi\tbl_citas_historial_manuales;
use App\bmi\tbl_citas_propias;
use App\bmi\tbl_citas_propias_historial;
use App\bmi\tbl_ciudad;
use App\bmi\tbl_clientes2;
use App\bmi\tbl_clientes;
use App\bmi\tbl_direccion;
use App\bmi\tbl_empresa;
use App\bmi\tbl_fecha_hora_cita;
use App\bmi\tbl_gestiones;
use App\bmi\tbl_gestiones_propias;
use App\bmi\tbl_gestiones_propias_manuales;
use App\bmi\tbl_notificaciones;
use App\bmi\tbl_parametros_citas;
use App\bmi\tbl_parentesco;
use App\bmi\tbl_pariente;
use App\bmi\tbl_producto;
use App\bmi\tbl_provincia;
use App\bmi\tbl_ranking_asesor;
use App\bmi\tbl_ranking_cliente;
use App\bmi\tbl_telefono;

use App\bmi\tbl_tipo;
use App\bmi\tbl_tipo_asesor;
use App\bmi\tbl_tipo_cliente;
use App\ivrs\tbl_cliente;
use App\tbl_gestiones_historico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Exception;
use DB;
use PhpParser\Node\Expr\Cast\Array_;
use TCG\Voyager\Facades\Voyager;

class bmiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = \Auth::user();
    }

    public function index()
    {
        $user = Auth::user();
        if($user->role_id==Role::where('name','bmisupervisor')->first()->id){
            $citas=tbl_citas::where('estado',1)->get();
            return view('bmi.bmi', compact('citas'));
        }else{
            return view('Acceso no permitido');
        }
    }

    public function ingresar(){
        $user=Auth::user();
        if(Voyager::can('browse_supervisor_bmi')){

                $citas=tbl_citas::get();
                $citas_historial=tbl_citas_historial::where('created_at', '>=', date('Y-m-d').' 00:00:00')->get();

                $citasPropiasHistorial=tbl_citas_propias_historial::where('created_at', '>=', date('Y-m-d').' 00:00:00')->where('estado',2)->where('estado_aprobado',3)->get();
                $citasPropias=tbl_citas_propias::where('cita_propia',1)->get();

            return view('bmi.bmiSupervisor', compact('citas', 'citasPropias','citas_historial','citasPropiasHistorial'));
        }
        return 'Usted no tiene acceso a este sistema';
    }


    public function validarArchivo(Request $request)
    {
        ini_set('max_execution_time',0);

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
                $sheet = $file->setActiveSheetIndex(0);
                //$sheet->setCellValue('D28', 'Test sdsadsa d sad sa');
                $i=1;
                $o=0;
                $result='';
                foreach ($file->get() as $carga) {

                    $dat = $carga->cedula;//asignacion de la variable de telefono en la variable dat
                    $dat = trim($dat);//eliminacion de los espacios en blancos
                    $dat = preg_replace('#^[^\d]*#','',$dat);//eliminando los caracteres alfanumericos del inicio de la cadena
                    $dat = preg_replace('#o|O#','0',$dat);//remplaza la letra o y O por cero
                    $dat = preg_replace('#[^\d]#','',$dat);//eliminando los caracteres alfanumericos


                    $i++;


                    if($dat=='' || strlen($dat)>10 || strlen($dat)<10)//control de campos vacios o incompletos
                    {
                        $o++;
                        if($o==1){$result='SE ENCONTRARON ERRORES!!';}

                        array_push($outa, $out = array (
                            0 => 'Fila '.$i.': ',
                            1 => 'Error en la longitud de la cÃ©dula ')
                        );

                        $result=500;

                    }

                }


                if($result=='' || count($outa)==1){
                    $ingresos=0;
                    if(Input::hasFile('file')){
                        $path = Input::file('file')->getRealPath();
                        $outa=array();
                        DB::connection('bmi')->table('clientes2')->truncate();

                        Excel::load($path, function($file) use (&$ingresos, &$outa) {
                            $sheet = $file->setActiveSheetIndex(0);
                            foreach ($file->get() as $carga) {

                                $cliente = new tbl_clientes2();
                                $cedula_cliente=trim($carga->cedula);
                                if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cedula_cliente;}

                                $cliente->cedula = $cedula_cliente;
                                //$cliente->cuenta=$carga->cuenta;
                                $cliente->nombres = $carga->nombre;
                                $cliente->fecha_nacimiento =$carga->fecha_nacimiento;
                                //$cliente->fecha_nacimiento = date("d-m-Y", strtotime($carga->fecha_nacimiento));
                                $cliente->edad = $carga->edad;
                                $cliente->estado_civil = $carga->estado_civil;
                                $cliente->cod_profesion = $carga->cod_profesion;
                                $cliente->salario = doubleval($carga->salario);
                                $cliente->empresa = $carga->empresa;

                                $cliente->direccion_empresa = $carga->direcciones;
                                //$cliente->celular_movistar = $carga->celular_movistar;
                                //$cliente->celular_bad = $carga->celular_bad;
                                $cliente->telefono = $carga->telefonos;
                                $cliente->celular = $carga->celular;
                                //$cliente->descripcion = $carga->descripcion;
                                $cliente->provincia = $carga->provincia;
                                $cliente->ciudad = $carga->canton;
                                $cliente->parroquia = $carga->parroquia;
                                $cliente->cargo = $carga->cargo;
                                $cliente->ingreso = $carga->ingreso;
                                //$cliente->salida = $carga->salida;
                                $cliente->tiempo_de_trabajo = $carga->tiempo_de_trabajo;
                                /*$cliente->telefono_sri = $carga->telefono_sri;
                                $cliente->celular_claro_1 = $carga['celular_claro.1'];
                                $cliente->celular_claro_2 = $carga['celular_claro.2'];
                                $cliente->celular_claro_3 = $carga['celular_claro.3'];
                                $cliente->celular_claro_4 = $carga['celular_claro.4'];
                                $cliente->celular_claro_5 = $carga['celular_claro.5'];
                                $cliente->telefono_1 = $carga['telefono.1'];*/
                                //$cliente->direccion = $carga['direccion'];
                                /*$cliente->telefono_2 = $carga['telefono.2'];
                                $cliente->telefono_3 = $carga['telefono.3'];
                                $cliente->telefono_4 = $carga['telefono.4'];
                                $cliente->telefono_en_matricula = $carga->telefono_en_matricula;*/
                                $cliente->email = $carga->email;
                                //$cliente->telefono_domicilio = $carga->telefono_domicilio;

                                $cliente->cedula_conyugue=$carga->cedula_conyuge;
                                $cliente->nombres_conyugue=$carga->nombres_conyuge;
                                $cliente->fecha_nacimiento_conyugue=$carga->fecha_nacimiento_conyuge;
                                $cliente->edad_conyugue=$carga->edad_conyuge;
                                $cliente->salario_conyugue=doubleval($carga->salario_conyuge);
                                $cliente->empresa_conyugue=$carga->empresa_conyuge;
                                /*$cliente->direccion_conyugue=$carga->direccion_conyuge;
                                $cliente->telefono_conyugue=$carga->telefono_conyuge;
                                $cliente->telefono_conyugue_2=$carga->telefono_conyuge_2;
                                //$cliente->descripcion_conyugue=$carga->descripcion_conyuge;
                                $cliente->actividad_conyugue=$carga->actividad_conyuge;
                                $cliente->provincia_conyugue=$carga->provincia_conyuge;
                                $cliente->canton_conyugue=$carga->canton_conyuge;
                                $cliente->cargo_conyugue=$carga->cargo_conyuge;
                                $cliente->ingreso_conyugue=$carga->ingreso_conyuge;*/
                                /*$cliente->salida_conyugue=$carga->salida_conyuge;
                                $cliente->celular_claro_1_conyugue=$carga['celular_claro.1_conyuge'];
                                $cliente->celular_claro_2_conyugue=$carga['celular_claro.2_conyuge'];
                                $cliente->telefono_1_conyugue=$carga['telefono.1_conyuge'];
                                $cliente->celular_movistar_conyugue=$carga->celular_movistar_conyuge;
                                $cliente->deuda = $carga->deuda;
                                $cliente->producto = $carga->producto;
                                $cliente->datos = $carga->datos;*/

                                $cliente->ultima_gestion_gestor=$carga->ultima_gestion_gestor;
                                $cliente->ultima_gestion_gestor_fecha_hora=$carga->ultima_gestion_fechahora;
                                $cliente->ultima_gestion_gestor_tipo=$carga->ultima_gestion_tipo;
                                $cliente->ultima_gestion_gestor_accion=$carga->ultima_gestion_accion;
                                $cliente->hora_cita=$carga->ultima_gestion_sub_motivo;
                                $cliente->fecha_cita=$carga->ultima_gestion_fecha_de_promesa_de_pago;

                                $chora_cita=$carga->ultima_gestion_sub_motivo;
                                $chora_cita=trim ($chora_cita);
                                $chora_cita=substr($chora_cita, 0, -3);

                                $cliente->hora_cita=$chora_cita;
                                $cliente->observacion=$carga->ultima_gestion_observacion;






                                //Datos clientes
                                $cnombres='';
                                $cobservacion=$carga->ultima_gestion_observacion;
                                //echo $cobservacion."\n\n";
                                $contador=1;
                                $cnombres=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Nombres: ".$cnombres."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $cfechanacieminto=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Fecha de nacimiento: ".$cfechanacieminto."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $cedad=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Edad: ".$cedad."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $ccedula=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "CI: ".$ccedula."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $cestado_civil=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Estado civil: ".$cestado_civil."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $cfecha_hora_cita=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Fecha y hora de cita: ".$cfecha_hora_cita."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $ctelefono_contacto=strstr($carga->ultima_gestion_observacion, 'TELEFONO', false);
                                $ctelefono_contacto=strstr($ctelefono_contacto, '..',true);
                                $cobservacion=strstr($cobservacion, '..', false);

                                //echo "TelÃ©fono de contacto: ".$ctelefono_contacto."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $cdireccion_cita=strstr($carga->ultima_gestion_observacion, 'DIRECCI', false);
                                $cdireccion_cita=strstr($cdireccion_cita, '..', true);
                                $cobservacion=strstr($cobservacion, '.. ', false);
                                //echo "Direccion de la Cita: ".$cdireccion_cita."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $cempresa=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Empresa: ".$cempresa."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $ccargo=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Cargo: ".$ccargo."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $csueldo=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Sueldo: ".$csueldo."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $cfehca_ingreso_empresa=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Fecha ingreso a la empresa: ".$cfehca_ingreso_empresa."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $cemail=strstr($cobservacion, '..', true);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Correo electrÃ³nico: ".$cemail."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";
                                //
                                $cobservacion=ltrim($cobservacion);
                                $cobservacion=substr($cobservacion, 2);
                                $cobservacion=ltrim($cobservacion);

                                $cdatos_acicionales=strstr($carga->ultima_gestion_observacion, 'DATOS ADICI', false);
                                $cobservacion=strstr($cobservacion, '..', false);
                                //echo "Datos adicionales: ".$cdatos_acicionales."\n";
                                //echo "Observacion: ".$cobservacion."\n\n";

                                $cliente->celular = $ctelefono_contacto;
                                $cliente->direccion_visita = $cdireccion_cita;
                                $cliente->datos_adicionales = $cdatos_acicionales;


                                $cliente->save();

                                if (is_null($cliente->ingreso)){$cliente->ingreso=null;}else{$cliente->ingreso=str_replace('/','-',$cliente->ingreso);}
                                if (is_null($cliente->salida)){$cliente->salida=null;}else{$cliente->salida=str_replace('/','-',$cliente->salida);}


                                $empresa=tbl_empresa::where('nombre',$cliente->empresa)->first();
                                if(is_null($empresa)){
                                    $empresa = new tbl_empresa();
                                    $empresa->nombre = $cliente->empresa;
                                    $empresa->direccion = $cliente->direccion_empresa;
                                    $empresa->descripcion = $cliente->descripcion;
                                    $empresa->save();
                                }else{
                                    if ($cliente->empresa == $empresa->nombre) {
                                        $empresa->direccion = $cliente->direccion_empresa;
                                        $empresa->descripcion = $cliente->descripcion;
                                        $empresa->save();
                                    } else {
                                        $empresa = new tbl_empresa();
                                        $empresa->nombre = $cliente->empresa;
                                        $empresa->direccion = $cliente->direccion_empresa;
                                        $empresa->descripcion = $cliente->descripcion;
                                        $empresa->save();
                                    }
                                }

                                if(tbl_clientes::where('cedula_cliente', $cliente->cedula)->first()){
                                    $clientes=tbl_clientes::where('cedula_cliente', $cliente->cedula)->first();
                                    $clientes->contador_carga=$clientes->contador_carga+1;
                                }else{
                                    $clientes=new tbl_clientes();
                                    $clientes->cedula_cliente=$cliente->cedula;
                                    $clientes->contador_carga=1;
                                }

                                $clientes->id_tipo_cliente=tbl_tipo_cliente::where('nombre','Cliente')->first()->id_tipo_cliente;
                                $clientes->nombres=$cliente->nombres;
                                $clientes->fecha_nacimiento=$cliente->fecha_nacimiento;
                                $clientes->edad=$cliente->edad;
                                $clientes->estado_civil=$cliente->estado_civil;
                                $clientes->cod_profesion=$cliente->cod_profesion;
                                $clientes->salario=$cliente->salario;
                                $clientes->direccion_visita = $cliente->direccion_visita;
                                $clientes->datos_adicionales = $cdatos_acicionales;
                                $clientes->tiempo_trabajo=$cliente->tiempo_de_trabajo;
                                $clientes->fecha_ingreso=is_null($cliente->ingreso) ? null : Carbon::parse($cliente->ingreso)->format('Y-m-d');
                                //$clientes->fecha_salida=is_null($cliente->salida) ? null : Carbon::parse($cliente->salida)->format('Y-m-d');

                                $ranking_cliente=tbl_ranking_cliente::get();
                                foreach ($ranking_cliente as $k){
                                    if ($cliente->salario>=$k->monto_ini && $cliente->salario<=$k->monto_fin){
                                        $clientes->id_ranking=$k->id_ranking_cliente;
                                    }
                                }

                                $clientes->telefono=$cliente->telefono;
                                $clientes->celular=$cliente->celular;
                                $clientes->cargo=$cliente->cargo;
                                $clientes->email=$cliente->email;
                                /*$clientes->deuda=$cliente->deuda;
                                $clientes->producto=$cliente->producto;
                                $clientes->datos=$cliente->datos;*/
                                $clientes->id_empresa=$empresa->id_empresa;
                                $clientes->estado=1;
                                $clientes->select=0;
                                $clientes->observacion=$cliente->observacion;
                                $clientes->fecha_cita=$cliente->fecha_cita;
                                $clientes->hora_cita=$cliente->hora_cita;
                                $clientes->save();

                                //inactivamos al cliente
                                //tbl_telefono::where('cedula_cliente',$cedula_cliente)->update(['estado' => 0]);


                                /*for ($i=2;$i<=4;$i++){
                                    if (!is_null($carga['telefono_'.$i])) {
                                        $telefono=tbl_telefono::where('cedula_cliente',$cedula_cliente)->where('telefono',$carga['telefono_'.$i])->first();
                                        if (is_null($telefono)){}else{
                                        $telefono = new tbl_telefono();
                                        $telefono->cedula_cliente = $cedula_cliente;
                                        $telefono->telefono = $carga['telefono_.' . $i];
                                        $telefono->estado = 1;
                                        $telefono->save();
                                        }
                                    }
                                }*/


                                if(tbl_provincia::where('nombre', $cliente->provincia)->first()){
                                    $provincia=tbl_provincia::where('nombre', $cliente->provincia)->first();
                                }else{
                                    $provincia=new tbl_provincia();
                                    $provincia->nombre=$cliente->provincia;
                                    $provincia->save();
                                }


                                if(tbl_ciudad::where('nombre', $cliente->ciudad)->first()){
                                    $ciudad=tbl_ciudad::where('nombre', $cliente->ciudad)->first();
                                }else{
                                    $ciudad=new tbl_ciudad();
                                    $ciudad->nombre=$cliente->ciudad;
                                    $ciudad->id_provincia=$provincia->id_provincia;
                                    $ciudad->save();
                                }

                                if(tbl_direccion::where('cedula_cliente', $cedula_cliente)->first()){
                                    $direccion=tbl_direccion::where('cedula_cliente', $cedula_cliente)->first();
                                }else{
                                    $direccion=new tbl_direccion();
                                    $direccion->cedula_cliente=$cedula_cliente;
                                }
                                $direccion->id_ciudad=$ciudad->id_ciudad;
                                $direccion->direccion=$cliente->direccion;
                                $direccion->parroquia=$cliente->parroquia;
                                $direccion->save();

                                if (!is_null($cliente->cedula_conyugue)) {
                                    if (tbl_pariente::where('cedula_cliente', $cedula_cliente)->first()) {
                                        $pariente = tbl_pariente::where('cedula_cliente', $cedula_cliente)->first();
                                    } else {
                                        $pariente = new tbl_pariente();
                                        $pariente->cedula_cliente = $cedula_cliente;
                                    }
                                    $pariente->id_parentesco = tbl_parentesco::where('descripcion','CONYUGUE')->first()->id_parentesco;
                                    $pariente->cedula = $cliente->cedula_conyugue;
                                    $pariente->nombres = $cliente->nombres_conyugue;
                                    $pariente->fecha_nacimiento = $cliente->fecha_nacimiento_conyugue;
                                    $pariente->edad = $cliente->edad_conyugue;
                                    $pariente->salario = $cliente->salario_conyugue;
                                    //$pariente->descripcion = $cliente->descripcion_conyugue;
                                    $pariente->actividad = $cliente->actividad_conyugue;
                                    $pariente->cargo = $cliente->cargo_conyugue;
                                    $pariente->empresa = $cliente->empresa_conyugue;
                                    $pariente->fecha_ingreso = $cliente->ingreso_conyugue;
                                    $pariente->telefono = $cliente->telefono_conyugue;
                                    $pariente->telefono2 = $cliente->telefono_conyugue_2;
                                    /*$pariente->celular_claro = $cliente->celular_claro_1_conyugue;
                                    $pariente->celular_claro2 = $cliente->celular_claro_2_conyugue;
                                    $pariente->celular_movistar = $cliente->celular_movistar_conyugue;
                                    $pariente->celular_movistar2 = $cliente->id_provincia;*/
                                    $pariente->direccion = $cliente->direccion;
                                    $pariente->save();
                                }
                                $cedula_cliente='';
                                $ingresos++;
                            }


                            try{}catch(\Exception $e){
                                $out = array (
                                    0 =>'FILA ',
                                    1 =>' ERROR',
                                );
                                array_push($outa, $out);
                                return \Response::json('Ocurrio un error: '.$out, 500);
                            }


                        });

                    }

                    $outa = array (
                        0 =>' ACTUALIZACIÃ“N CORRECTA',     // resultado (numero corregido)
                        1 =>$ingresos.' INGRESOS',    // error en texto
                    );
                    return \Response::json($outa, 200);

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

        $ingresos=0;
        if(Input::hasFile('file')){
            $path = Input::file('file')->getRealPath();
            $outa=array();
            DB::connection('bmi')->table('clientes2')->truncate();

            Excel::load($path, function($file) use (&$ingresos, &$outa) {
                $sheet = $file->setActiveSheetIndex(0);
                foreach ($file->get() as $carga) {

                    $cliente = new tbl_clientes2();
                    $cedula_cliente=trim($carga->cedula);
                    if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cedula_cliente;}

                    $cliente->cedula = $cedula_cliente;
                    //$cliente->cuenta=$carga->cuenta;
                    $cliente->nombres = $carga->nombre;
                    $cliente->fecha_nacimiento =$carga->fecha_nacimiento;
                    //$cliente->fecha_nacimiento = date("d-m-Y", strtotime($carga->fecha_nacimiento));
                    $cliente->edad = $carga->edad;
                    $cliente->estado_civil = $carga->estado_civil;
                    $cliente->cod_profesion = $carga->cod_profesion;
                    $cliente->salario = doubleval($carga->salario);
                    $cliente->empresa = $carga->empresa;

                    $cliente->direccion_empresa = $carga->direcciones;
                    //$cliente->celular_movistar = $carga->celular_movistar;
                    //$cliente->celular_bad = $carga->celular_bad;
                    $cliente->telefono = $carga->telefonos;
                    $cliente->celular = $carga->celular;
                    //$cliente->descripcion = $carga->descripcion;
                    $cliente->provincia = $carga->provincia;
                    $cliente->ciudad = $carga->canton;
                    $cliente->parroquia = $carga->parroquia;
                    $cliente->cargo = $carga->cargo;
                    $cliente->ingreso = $carga->ingreso;
                    //$cliente->salida = $carga->salida;
                    $cliente->tiempo_de_trabajo = $carga->tiempo_de_trabajo;
                    /*$cliente->telefono_sri = $carga->telefono_sri;
                    $cliente->celular_claro_1 = $carga['celular_claro.1'];
                    $cliente->celular_claro_2 = $carga['celular_claro.2'];
                    $cliente->celular_claro_3 = $carga['celular_claro.3'];
                    $cliente->celular_claro_4 = $carga['celular_claro.4'];
                    $cliente->celular_claro_5 = $carga['celular_claro.5'];
                    $cliente->telefono_1 = $carga['telefono.1'];*/
                    //$cliente->direccion = $carga['direccion'];
                    /*$cliente->telefono_2 = $carga['telefono.2'];
                    $cliente->telefono_3 = $carga['telefono.3'];
                    $cliente->telefono_4 = $carga['telefono.4'];
                    $cliente->telefono_en_matricula = $carga->telefono_en_matricula;*/
                    $cliente->email = $carga->email;
                    //$cliente->telefono_domicilio = $carga->telefono_domicilio;

                    $cliente->cedula_conyugue=$carga->cedula_conyuge;
                    $cliente->nombres_conyugue=$carga->nombres_conyuge;
                    $cliente->fecha_nacimiento_conyugue=$carga->fecha_nacimiento_conyuge;
                    $cliente->edad_conyugue=$carga->edad_conyuge;
                    $cliente->salario_conyugue=doubleval($carga->salario_conyuge);
                    $cliente->empresa_conyugue=$carga->empresa_conyuge;
                    /*$cliente->direccion_conyugue=$carga->direccion_conyuge;
                    $cliente->telefono_conyugue=$carga->telefono_conyuge;
                    $cliente->telefono_conyugue_2=$carga->telefono_conyuge_2;
                    //$cliente->descripcion_conyugue=$carga->descripcion_conyuge;
                    $cliente->actividad_conyugue=$carga->actividad_conyuge;
                    $cliente->provincia_conyugue=$carga->provincia_conyuge;
                    $cliente->canton_conyugue=$carga->canton_conyuge;
                    $cliente->cargo_conyugue=$carga->cargo_conyuge;
                    $cliente->ingreso_conyugue=$carga->ingreso_conyuge;*/
                    /*$cliente->salida_conyugue=$carga->salida_conyuge;
                    $cliente->celular_claro_1_conyugue=$carga['celular_claro.1_conyuge'];
                    $cliente->celular_claro_2_conyugue=$carga['celular_claro.2_conyuge'];
                    $cliente->telefono_1_conyugue=$carga['telefono.1_conyuge'];
                    $cliente->celular_movistar_conyugue=$carga->celular_movistar_conyuge;
                    $cliente->deuda = $carga->deuda;
                    $cliente->producto = $carga->producto;
                    $cliente->datos = $carga->datos;*/

                    $cliente->ultima_gestion_gestor=$carga->ultima_gestion_gestor;
                    $cliente->ultima_gestion_gestor_fecha_hora=$carga->ultima_gestion_fechahora;
                    $cliente->ultima_gestion_gestor_tipo=$carga->ultima_gestion_tipo;
                    $cliente->ultima_gestion_gestor_accion=$carga->ultima_gestion_accion;
                    $cliente->hora_cita=$carga->ultima_gestion_sub_motivo;
                    $cliente->fecha_cita=$carga->ultima_gestion_fecha_de_promesa_de_pago;

                    $chora_cita=$carga->ultima_gestion_sub_motivo;
                    $chora_cita=trim ($chora_cita);
                    $chora_cita=substr($chora_cita, 0, -3);

                    $cliente->hora_cita=$chora_cita;
                    $cliente->observacion=$carga->ultima_gestion_observacion;






                    //Datos clientes
                    $cnombres='';
                    $cobservacion=$carga->ultima_gestion_observacion;
                    //echo $cobservacion."\n\n";
                    $contador=1;
                    $cnombres=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Nombres: ".$cnombres."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $cfechanacieminto=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Fecha de nacimiento: ".$cfechanacieminto."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $cedad=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Edad: ".$cedad."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $ccedula=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "CI: ".$ccedula."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $cestado_civil=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Estado civil: ".$cestado_civil."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $cfecha_hora_cita=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Fecha y hora de cita: ".$cfecha_hora_cita."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $ctelefono_contacto=strstr($carga->ultima_gestion_observacion, 'TELEFONO', false);
                    $ctelefono_contacto=strstr($ctelefono_contacto, '..',true);
                    $cobservacion=strstr($cobservacion, '..', false);

                    //echo "TelÃ©fono de contacto: ".$ctelefono_contacto."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $cdireccion_cita=strstr($carga->ultima_gestion_observacion, 'DIRECCION', false);
                    $cdireccion_cita=strstr($cdireccion_cita, '..', true);
                    $cobservacion=strstr($cobservacion, '.. ', false);
                    //echo "Direccion de la Cita: ".$cdireccion_cita."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $cempresa=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Empresa: ".$cempresa."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $ccargo=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Cargo: ".$ccargo."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $csueldo=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Sueldo: ".$csueldo."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $cfehca_ingreso_empresa=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Fecha ingreso a la empresa: ".$cfehca_ingreso_empresa."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $cemail=strstr($cobservacion, '..', true);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Correo electrÃ³nico: ".$cemail."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";
                    //
                    $cobservacion=ltrim($cobservacion);
                    $cobservacion=substr($cobservacion, 2);
                    $cobservacion=ltrim($cobservacion);

                    $cdatos_acicionales=strstr($carga->ultima_gestion_observacion, 'DATOS ADICI', false);
                    $cobservacion=strstr($cobservacion, '..', false);
                    //echo "Datos adicionales: ".$cdatos_acicionales."\n";
                    //echo "Observacion: ".$cobservacion."\n\n";

                    $cliente->celular = $ctelefono_contacto;
                    $cliente->direccion_visita = $cdireccion_cita;
                    $cliente->datos_adicionales = $cdatos_acicionales;


                    $cliente->save();

                    if (is_null($cliente->ingreso)){$cliente->ingreso=null;}else{$cliente->ingreso=str_replace('/','-',$cliente->ingreso);}
                    if (is_null($cliente->salida)){$cliente->salida=null;}else{$cliente->salida=str_replace('/','-',$cliente->salida);}


                    $empresa=tbl_empresa::where('nombre',$cliente->empresa)->first();
                    if(is_null($empresa)){
                        $empresa = new tbl_empresa();
                        $empresa->nombre = $cliente->empresa;
                        $empresa->direccion = $cliente->direccion_empresa;
                        $empresa->descripcion = $cliente->descripcion;
                        $empresa->save();
                    }else{
                        if ($cliente->empresa == $empresa->nombre) {
                            $empresa->direccion = $cliente->direccion_empresa;
                            $empresa->descripcion = $cliente->descripcion;
                            $empresa->save();
                        } else {
                            $empresa = new tbl_empresa();
                            $empresa->nombre = $cliente->empresa;
                            $empresa->direccion = $cliente->direccion_empresa;
                            $empresa->descripcion = $cliente->descripcion;
                            $empresa->save();
                        }
                    }

                    if(tbl_clientes::where('cedula_cliente', $cliente->cedula)->first()){
                        $clientes=tbl_clientes::where('cedula_cliente', $cliente->cedula)->first();
                        $clientes->contador_carga=$clientes->contador_carga+1;
                    }else{
                        $clientes=new tbl_clientes();
                        $clientes->cedula_cliente=$cliente->cedula;
                        $clientes->contador_carga=1;
                    }

                    $clientes->id_tipo_cliente=tbl_tipo_cliente::where('nombre','Cliente')->first()->id_tipo_cliente;
                    $clientes->nombres=$cliente->nombres;
                    $clientes->fecha_nacimiento=$cliente->fecha_nacimiento;
                    $clientes->edad=$cliente->edad;
                    $clientes->estado_civil=$cliente->estado_civil;
                    $clientes->cod_profesion=$cliente->cod_profesion;
                    $clientes->salario=$cliente->salario;
                    $clientes->direccion_visita = $cliente->direccion_visita;
                    $clientes->datos_adicionales = $cdatos_acicionales;
                    $clientes->tiempo_trabajo=$cliente->tiempo_de_trabajo;
                    $clientes->fecha_ingreso=is_null($cliente->ingreso) ? null : Carbon::parse($cliente->ingreso)->format('Y-m-d');
                    //$clientes->fecha_salida=is_null($cliente->salida) ? null : Carbon::parse($cliente->salida)->format('Y-m-d');

                    $ranking_cliente=tbl_ranking_cliente::get();
                    foreach ($ranking_cliente as $k){
                        if ($cliente->salario>=$k->monto_ini && $cliente->salario<=$k->monto_fin){
                            $clientes->id_ranking=$k->id_ranking_cliente;
                        }
                    }

                    $clientes->telefono=$cliente->telefono;
                    $clientes->celular=$cliente->celular;
                    $clientes->cargo=$cliente->cargo;
                    $clientes->email=$cliente->email;
                    /*$clientes->deuda=$cliente->deuda;
                    $clientes->producto=$cliente->producto;
                    $clientes->datos=$cliente->datos;*/
                    $clientes->id_empresa=$empresa->id_empresa;
                    $clientes->estado=1;
                    $clientes->observacion=$cliente->observacion;
                    $clientes->fecha_cita=$cliente->fecha_cita;
                    $clientes->hora_cita=$cliente->hora_cita;
                    $clientes->save();

                    //inactivamos al cliente
                    //tbl_telefono::where('cedula_cliente',$cedula_cliente)->update(['estado' => 0]);


                    /*for ($i=2;$i<=4;$i++){
                        if (!is_null($carga['telefono_'.$i])) {
                            $telefono=tbl_telefono::where('cedula_cliente',$cedula_cliente)->where('telefono',$carga['telefono_'.$i])->first();
                            if (is_null($telefono)){}else{
                            $telefono = new tbl_telefono();
                            $telefono->cedula_cliente = $cedula_cliente;
                            $telefono->telefono = $carga['telefono_.' . $i];
                            $telefono->estado = 1;
                            $telefono->save();
                            }
                        }
                    }*/


                    if(tbl_provincia::where('nombre', $cliente->provincia)->first()){
                        $provincia=tbl_provincia::where('nombre', $cliente->provincia)->first();
                    }else{
                        $provincia=new tbl_provincia();
                        $provincia->nombre=$cliente->provincia;
                        $provincia->save();
                    }


                    if(tbl_ciudad::where('nombre', $cliente->ciudad)->first()){
                        $ciudad=tbl_ciudad::where('nombre', $cliente->ciudad)->first();
                    }else{
                        $ciudad=new tbl_ciudad();
                        $ciudad->nombre=$cliente->ciudad;
                        $ciudad->id_provincia=$provincia->id_provincia;
                        $ciudad->save();
                    }

                    if(tbl_direccion::where('cedula_cliente', $cedula_cliente)->first()){
                        $direccion=tbl_direccion::where('cedula_cliente', $cedula_cliente)->first();
                    }else{
                        $direccion=new tbl_direccion();
                        $direccion->cedula_cliente=$cedula_cliente;
                    }
                    $direccion->id_ciudad=$ciudad->id_ciudad;
                    $direccion->direccion=$cliente->direccion;
                    $direccion->parroquia=$cliente->parroquia;
                    $direccion->save();

                    if (!is_null($cliente->cedula_conyugue)) {
                        if (tbl_pariente::where('cedula_cliente', $cedula_cliente)->first()) {
                            $pariente = tbl_pariente::where('cedula_cliente', $cedula_cliente)->first();
                        } else {
                            $pariente = new tbl_pariente();
                            $pariente->cedula_cliente = $cedula_cliente;
                        }
                        $pariente->id_parentesco = tbl_parentesco::where('descripcion','CONYUGUE')->first()->id_parentesco;
                        $pariente->cedula = $cliente->cedula_conyugue;
                        $pariente->nombres = $cliente->nombres_conyugue;
                        $pariente->fecha_nacimiento = $cliente->fecha_nacimiento_conyugue;
                        $pariente->edad = $cliente->edad_conyugue;
                        $pariente->salario = $cliente->salario_conyugue;
                        //$pariente->descripcion = $cliente->descripcion_conyugue;
                        $pariente->actividad = $cliente->actividad_conyugue;
                        $pariente->cargo = $cliente->cargo_conyugue;
                        $pariente->empresa = $cliente->empresa_conyugue;
                        $pariente->fecha_ingreso = $cliente->ingreso_conyugue;
                        $pariente->telefono = $cliente->telefono_conyugue;
                        $pariente->telefono2 = $cliente->telefono_conyugue_2;
                        /*$pariente->celular_claro = $cliente->celular_claro_1_conyugue;
                        $pariente->celular_claro2 = $cliente->celular_claro_2_conyugue;
                        $pariente->celular_movistar = $cliente->celular_movistar_conyugue;
                        $pariente->celular_movistar2 = $cliente->id_provincia;*/
                        $pariente->direccion = $cliente->direccion;
                        $pariente->save();
                    }
                    $cedula_cliente='';
                    $ingresos++;
                }


                try{}catch(\Exception $e){
                    $out = array (
                        0 =>'FILA ',
                        1 =>' ERROR',
                    );
                    array_push($outa, $out);
                    return \Response::json('Ocurrio un error: '.$out, 500);
                }


            });

        }

        $outa = array (
            0 =>' ACTUALIZACIÃ“N CORRECTA',     // resultado (numero corregido)
            1 =>$ingresos.' INGRESOS',    // error en texto
        );
        return \Response::json($outa, 200);
    }

    public function busquedaBmi()
    {
        $fecha = date('Y-m-d');

        $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );

        $clientesHoy=tbl_clientes::where('estado',1)->where('fecha_cita',$fecha)->get();
        $clientesManana=tbl_clientes::where('estado',1)->where('fecha_cita',$nuevafecha)->get();

        $raking=tbl_ranking_cliente::get();

        foreach($clientesHoy as $cliente)
        {
            foreach ($raking as $k){
                if (floatval($cliente->salario)>=floatval($k->monto_ini) && floatval($cliente->salario)<=floatval($k->monto_fin)){
                    $cliente->id_ranking=$k->id_ranking_cliente;
                }
            }
            $cliente->save();
        }

        foreach ($clientesManana as $cliente)
        {
            foreach ($raking as $k){
                if (floatval($cliente->salario)>=floatval($k->monto_ini) && floatval($cliente->salario)<=floatval($k->monto_fin)){
                    $cliente->id_ranking=$k->id_ranking_cliente;
                }
            }
            $cliente->save();
        }

        return view('bmi.buscarbmi',compact('clientesHoy','clientesManana','fecha','nuevafecha'));
    }

    public function confirmacionCitaEmail()
    {

        return view('bmi.confirmacionCitaEmail');
    }

    public function confirmacionCitaEmails(Request $request)
    {
        $to_name = 'BMI';
        $to_email = 'informacion.bmi@gmail.com';
        $data = array('name'=>"Agente BMI", "body" => "Email de prueba");

        Mail::send('bmi.email.body', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Artisans Testing Mail');
            $message->from('mcondor@cobefec.com','Artisans Web');
        });

        dd();
        $mail='información.bmi@gmail.com';
        $nombres = 'Agente Prueba';
        $email ='mcondor@cobefec.com';
        $telefono = '2222333';
        $celular = '09999999';
        $empresa = 'BMI Empresa';
        $asunto = 'Asunto de prueba';
        $mensaje = 'Mensaje cita de prueba.';
        $texto="<html><head><title>BMI, agradece tu contacto.</title></head>
<body><strong>BMI Ecuador</strong>, agradece tu contacto.
<br>En breve uno de nuestros asesores tomará contacto con usted.
<br><br>
Este fue su mensaje:
<br>Nombres: ".$nombres."
<br>Email: ".$email."
<br>Teléfono: ".$telefono."
<br>Celular: ".$celular."
<br>Empresa: ".$empresa."
<br>Asunto: ".$asunto."
<br>Mensaje: ".$mensaje."
</body>
</html>";
        // Para enviar un correo HTML, debe establecerse la cabecera Content-type
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // Cabeceras adicionales
        $cabeceras .= 'From: '.$mail."\r\n";

        mail($mail,"Nuevo Contacto BMI ",$texto,$cabeceras);
        //mail($email,"Moneytech Solutions Ecuador agradece su contacto",$texto,$cabeceras);
        //mail("mcondor@cobefec.com","CC Moneytech Solutions Ecuador agradece su contacto",$texto,$cabeceras);

        $mensaje='Mensaje enviado exitosamente';
        return view('bmi.confirmacionCitaEmail');
    }

    public function clientesBmi()
    {
        return view('bmi.clientes');
    }

    public function asesorBmi()
    {
        $tipo_asesor=tbl_tipo_asesor::get();
        $error='';
        $mensaje='';
        return view('bmi.asesores.ingresarAsesores', compact('tipo_asesor','error','mensaje'));
    }

    public function verAsesoresBmi()
    {
        $asesores=tbl_asesores::get();
        return view('bmi.asesores.verAsesoresBmi', compact('asesores'));
    }

    public function activarAsesorBmi($cedula)
    {
        tbl_asesores::where('cedula_asesor',$cedula)->update(['estado'=>1]);
        return redirect()->action('Bmi\BmiController@verAsesoresBmi');
    }

    public function desactivarAsesorBmi($cedula)
    {
        tbl_asesores::where('cedula_asesor',$cedula)->update(['estado'=>0]);
        return redirect()->action('Bmi\BmiController@verAsesoresBmi');
    }

    public function ingresarAsesorBmi(Request $request)
    {
        $error='';
        $mensaje='';
        try{
            $asesor=new tbl_asesores();
            $asesor->cedula_asesor=$request->cedula;
            $asesor->nombres=$request->nombres;
            $asesor->email_corporativo=$request->email;
            $asesor->email_personal=$request->email_personal;

            $dateTime = date_create_from_format('d/m/Y', $request->fecha_contrato);
            $asesor->fecha_contrato=date_format($dateTime, 'Y-m-d');
            $dateTime = date_create_from_format('d/m/Y', $request->fecha_nacimiento);
            $asesor->fecha_nacimiento=date_format($dateTime, 'Y-m-d');

            $asesor->celular=$request->celular;
            $asesor->id_tipo_asesor=$request->tipo;
            $asesor->estado=1;
            $asesor->r_contrasena=0;
            $asesor->id_ranking=tbl_ranking_asesor::where('nivel',0)->first()->id_ranking;
            $asesor->save();
            if ($asesor->save()){
                try{
                    $user=new User();
                    $user->name=$asesor->nombres;
                    $user->email=$asesor->email_corporativo;
                    $user->avatar='users/default.png';
                    $user->password=Hash::make('123456');
                    $user->role_id=Role::where('name','bmiagente')->first()->id;
                    $user->save();
                }catch (\Exception $e){
                    if($e->errorInfo[1]== 1062){
                        $error= 'Error, este asesor ya existe.';
                    }
                }
                $mensaje=$asesor->nombres.', correctamente ingresado.';
            }



        }catch (\Exception $e){
            if($e->errorInfo[1]== 1062){
                $error= 'Error, este asesor ya existe.';
            }
        }
        $tipo_asesor=tbl_tipo_asesor::get();
        return view('bmi.asesores.ingresarAsesores', compact('tipo_asesor','error','mensaje'));
    }

    public function agendarCitasBmi()
    {
        $error='';
        $mensaje='';
        $clientes=tbl_clientes::where('estado',1)->get();
        $asesores=tbl_asesores::where('estado',1)->get();
        $citas=tbl_citas::where('cita_automatica',1)->count();


        return view('bmi.agendarCitas', compact('tipo_asesor','error','mensaje','clientes','asesores','citas'));
    }

    public function agendarCitassBmi($cedula)
    {

        $error='';
        $mensaje='';
        $clientes=tbl_clientes::where('estado',1)->get();
        //$citas=tbl_citas::where('asesor',$cedula)->get();
        $asesor=tbl_asesores::where('cedula_asesor',$cedula)->first();
        $citas=tbl_citas::where('asesor',$cedula)->where('estado',1)->orderBy('hora_cita','ASC')->get();

        $hora = Array();

        foreach ($citas as $cita) {
            $hora[$cita->id_cita]=$cita->hora_cita;
            //var_dump($cita->toarray());
        }
        //quito todos los valores nulos del array
        foreach($hora as $clave=>$valor){
            if(empty($valor)) unset($hora[$clave]);
        }

        $valores_unicos=array_unique($hora);
        $hora=array_count_values($hora);
        foreach ($valores_unicos as $k){
            //echo $k;
            if (array_key_exists($k,$hora)){
                //print_r(array_search($k,$valores_unicos));
                $valores_unicos[array_search($k,$valores_unicos)]=array($valores_unicos[array_search($k,$valores_unicos)],'success');
            }
            //echo $k;
        }

        foreach ($citas as $cita){
            if(array_key_exists($cita->id_cita,$valores_unicos)){
                $cita->success='';
            }else{
                $cita->success='bg-danger';
            }

        }

        //var_dump($valores_unicos);

        return view('bmi.agendarCitass', compact('tipo_asesor','error','mensaje','clientes','asesor','citas'));
    }

    public function agendarCitas2Bmi(Request $request)
    {

        $asesor=tbl_asesores::where('cedula_asesor',$request->asesor)->where('estado',1)->first();


        foreach ($request->cliente as $cedula_cliente) {

            $cliente=tbl_clientes::where('estado',1)->where('cedula_cliente',$cedula_cliente)->first();
            $cita=new tbl_citas();
            $cita->id_gestion_cobefec=$cliente->id_gestion;
            $cedula_cliente=$cliente->cedula_cliente;
            if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cliente->cedula_cliente;}
            $cita->cedula_cliente=$cedula_cliente;
            $cita->telefono=$cliente->celular;
            $cita->direccion_cita=$cliente->direccion_visita;

            //$cita->fecha_cita=$fecha_cita;

            $cita->fecha_cita=$cliente->fecha_cita;
            $cita->hora_cita=$cliente->hora_cita;
            $cita->nombres=$cliente->nombres;
            $cita->asesor=$asesor->cedula_asesor;
            $cita->usuario_gestion=$cliente->usuario_gestion;
            $cita->pais=$cliente->pais;
            $cita->estado=1;
            $cita->save();

            $cliente->estado=0;
            $cliente->save();
        }

        return redirect()->back();


        /*
        $parametros_citas=tbl_parametros_citas::first();
        if($request->destino){
            $i=1;
            $fecha_cita='';
            foreach ($request->destino as $k){
                $cliente=tbl_clientes::where('cedula_cliente','like','%'.$k)->first();
                $citas=new tbl_citas();
                $citas->asesor=$request->cedula_asesor;
                $cedula_cliente=$cliente->cedula_cliente;
                if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cedula_cliente;}
                $citas->cedula_cliente=$cedula_cliente;
                $citas->nombres=$cliente->nombres;
                $citas->direccion_cita=$cliente->empresa->direccion;
                $citas->observacion = '';
                $citas->estado = 1;

                if ($i==1){
                    $fecha_cita=Carbon::createFromFormat('H:i', $parametros_citas->hora_inicio)->addDay(1);
                    $citas->fecha_cita=$fecha_cita;
                }else{
                    $fecha_cita=$fecha_cita->addMinute($parametros_citas->tiempo_citas);
                    $citas->fecha_cita=$fecha_cita;
                }
                $citas->save();

                $cliente->estado=9;
                $cliente->save();
                $i++;
            }
        }else{
            $citas=tbl_citas::where('asesor',$request->cedula_asesor)->get();
            foreach($citas as $key){
                $cliente=tbl_clientes::where('cedula_cliente','like','%'.$key->cedula)->first();
                $cliente->estado=1;
                $cliente->save();
            }
            tbl_citas::where('asesor',$request->cedula_asesor)->delete();
        }
        $error='';
        $mensaje='';
        $clientes=tbl_clientes::where('estado',1)->get();
        $asesores=tbl_asesores::where('estado',1)->get();
        return view('bmi.agendarCitas', compact('tipo_asesor','error','mensaje','clientes','asesores'));*/
    }

    public function agendamientoAutomatico()
    {
        $citas=tbl_parametros_citas::where('estado',1)->get();
        return view('bmi/citas/citas', compact('citas'));
    }

    public function asignacionAutomatica()
    {
        $citas_old=tbl_citas::where('estado',1)->where('cita_automatica',1)->get();
        foreach ($citas_old as $cita_old){
            $cita_old->delete();
        }

        tbl_clientes::where('select',1)->update(['select'=>0]);

        $citas_con_clientes=tbl_citas_con_clientes::where('estado',1)->where('total','>',0)->get();
        $total_citas=tbl_citas_con_clientes::where('estado',1)->where('total','>',0)->sum('total');
        $lista_ranking_clientes=tbl_ranking_cliente::where('estado',1)->orderBy('nivel','ASC')->get();
        $t=1;
        $cliente_nivel=Array();

        foreach ($lista_ranking_clientes as $ranking_cliente){
            for ($j=6;$j<=21;$j++){
                $lista_clientes=tbl_clientes::where('estado',1)->where('select',0)->where('id_ranking',$ranking_cliente->id_ranking_cliente)->whereBetween('hora_cita', [$j.":00", ($j).":59"])->orderBy('hora_cita','ASC')->get();
                foreach ($lista_clientes as $cliente){
                    $cliente_cedula=(string)$cliente->cedula_cliente;
                    if(strlen($cliente_cedula)==9){$cliente_cedula= (string) '0'.$cliente_cedula;}
                    $cliente_nivel[$cliente->id_ranking][$j][$t]=array($cliente_cedula,substr($cliente->hora_cita,0,-3));//array($cliente_cedula, substr($cliente->hora_cita,0,-3));
                    //echo "<br>".$t." Cliente ".$cliente->cedula_cliente." - ".$cliente->ranking_cliente->descripcion." hora:".$cliente->hora_cita;

                    $t++;
                }
                //echo "<br>";
            }
            //echo "<br>";

            /*$horas_citas=tbl_fecha_hora_cita::where('id_parametros_citas',1)->get();
            foreach ($horas_citas as $hora_cita){
                $lista_clientes=tbl_clientes::where('estado',1)->where('hora_cita','>',substr($hora_cita->hora_cita_ini,0,-3))->where('hora_cita','<=',substr($hora_cita->hora_cita_fin,0,-3))->orderBy('hora_cita','ASC')->get();
                foreach ($lista_clientes as $cliente){
                    echo "<br>Cliente ".$cliente->cedula_cliente." - ".$cliente->ranking_cliente->descripcion." hora:".$cliente->hora_cita;
                }
                echo "<br>";
            }*/

        }

        //var_dump($cliente_nivel);

        $lista_ranking_asesor=tbl_ranking_asesor::where('estado',1)->orderBy('nivel','DESC')->get();
        $count=1;
        $asesores_nivel=Array();
        foreach ($lista_ranking_asesor as $ranking_asesor){
            $lista_asesores=tbl_asesores::where('estado',1)->where('id_ranking',$ranking_asesor->id_ranking)->orderBy('id_ranking','DESC')->get();

            foreach ($lista_asesores as $asesor){
                //echo "<br>asesor Ranking: ".$asesor->cedula_asesor." - ".$asesor->ranking_asesor->nivel;
                $cliente_asesor=(string)$asesor->cedula_asesor;
                //if(strlen($cliente_asesor)==9){$cliente_asesor= (string) '0'.$cliente_asesor;}
                $asesores_nivel[$asesor->ranking_asesor->id_ranking][$count]=$cliente_asesor;
                $count++;
            }
            $count=1;
        }

        //var_dump($asesores_nivel);

        //echo "asesores:<br>";
        //var_dump($asesores_nivel);
        //echo "clientes:<br>";
        //var_dump($cliente_nivel);

        $lista_parametros_citas_clientes=tbl_citas_con_clientes::where('estado',1)->where('total','>',0)->orderBy('id_parametros_citas','ASC')->get();
        $count_cliente=1;
        $count_asesor=0;

        $citas_asesores=Array();
        foreach ($lista_parametros_citas_clientes as $parametro){
            echo "<br><br>id: ".$parametro->id_parametro_citas_asesores." ranking asesor:".$parametro->parametro_cita->ranking_asesor->id_ranking." ranking cliente:".$parametro->ranking_cliente->id_ranking_cliente." citas max:".$parametro->parametro_cita->citas_max." total citas:".$parametro->total." tiempo entre citas:".$parametro->parametro_cita->tiempo_citas."minutos hora inicio:".$parametro->parametro_cita->hora_inicio;

            $total_asesores=count($asesores_nivel[$parametro->parametro_cita->ranking_asesor->id_ranking]);
            $count=1;
            for ($j=1; $j<=$parametro->total; $j++){
                if ($count_asesor==0) {
                    $count_asesor++;
                }
                echo "<br> total:".$parametro->total." j:".$j." total_asesores:".$total_asesores." count:".$count;
                if ($count<=$parametro->total){
                    //echo "<br>Asesor:".$count_asesor;
                    //var_dump($asesores_nivel[$parametro->parametro_cita->ranking_asesor->id_ranking][$count_asesor]);
                    for ($k=7;$k<=22;$k++){
                        //echo "<br>ranking cliente: ".$parametro->ranking_cliente->id_ranking_cliente." k:".$k." count:".$count." count_cliente:".$count_cliente;
                        if (isset($cliente_nivel[$parametro->ranking_cliente->id_ranking_cliente][$k][$count_cliente])){
                            //echo isset($cliente_nivel[$parametro->ranking_cliente->id_ranking_cliente][$k][$count_cliente]);
                            //for ($l=1;$l<=count($cliente_nivel[$parametro->ranking_cliente->id_ranking_cliente][$k]);$l++){
                            //echo "<br>Cliente:".$count_cliente;
                            //var_dump($cliente_nivel[$parametro->ranking_cliente->id_ranking_cliente][$k][$count_cliente]);

                            $citas_asesores[$count_cliente]=array($asesores_nivel[$parametro->parametro_cita->ranking_asesor->id_ranking][$count_asesor],$cliente_nivel[$parametro->ranking_cliente->id_ranking_cliente][$k][$count_cliente]);

                            unset($cliente_nivel[$parametro->ranking_cliente->id_ranking_cliente][$k][$count_cliente]);

                            if ($count_asesor<$total_asesores){
                                $count_asesor++;
                            }elseif($count_asesor==$total_asesores){
                                $count_asesor=1;
                            }else{
                                $count_asesor=0;
                            }

                            $count_cliente++;
                            $k=23;
                            //}
                        }
                    }
                    $count++;
                }else{
                    //echo "<br>entro else count ".$count." total:".count($asesores_nivel[$parametro->parametro_cita->ranking_asesor->id_ranking]);
                    $count=$count-count($asesores_nivel[$parametro->parametro_cita->ranking_asesor->id_ranking]);
                    //echo "<br>count: ".$count;
                    //var_dump($asesores_nivel[$parametro->parametro_cita->ranking_asesor->id_ranking][$count][$count_cliente]);
                    $count++;
                }


            }


        }

        /*echo "<br>CITAS DISTRIBUIDAS: ";
        var_dump($citas_asesores);
*/

        foreach ($citas_asesores as $cita_asesor) {


            echo($cita_asesor[1][0]);
            $cliente=tbl_clientes::where('cedula_cliente',$cita_asesor[1][0])->where('estado',1)->first();
            $asesor=tbl_asesores::where('cedula_asesor',$cita_asesor[0])->where('estado',1)->first();

            $cedula_cliente=$cliente->cedula_cliente;
            if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cliente->cedula_cliente;}

            $distribuir_citas=new tbl_citas();
            $distribuir_citas->cedula_cliente=$cedula_cliente;
            $distribuir_citas->nombres=$cliente->nombres;
            $distribuir_citas->telefono=$cliente->celular;
            $distribuir_citas->direccion_cita=$cliente->direccion_visita;
            $distribuir_citas->asesor=$asesor->cedula_asesor;
            $distribuir_citas->fecha_cita=$cliente->fecha_cita;
            $distribuir_citas->hora_cita=$cliente->hora_cita;
            $distribuir_citas->estado=1;
            $distribuir_citas->cita_automatica=1;
            $distribuir_citas->usuario_gestion=$cliente->usuario_gestion;
            $distribuir_citas->pais=$cliente->pais;
            $distribuir_citas->save();

            //Selecciona al cliente y le pongo como estado = inactivo
            $cliente->select=1;
            $cliente->estado=0;
            $cliente->save();


        }

        return redirect()->action('Bmi\BmiController@agendarCitasBmi');

        tbl_clientes::where('estado',0)->update(['estado'=>1]);
        tbl_citas::where('estado',1)->delete();

        $cedula_asesor='';
        $i=1;
        foreach ($citas_con_clientes as $k){
            //dd(Carbon::createFromFormat('H:i', $k->parametro_cita->hora_inicio)->addDay(1)->addMinute($k->parametro_cita->tiempo_citas));
            echo "<br>Asesores: ".$k->parametro_cita->ranking_asesor->descripcion;

            $asesores=tbl_asesores::where('estado',1)->where('id_ranking',$k->parametro_cita->id_ranking)->orderBy('fecha_contrato','ASC')->get();
            echo "<br>total:".count($asesores);
            $total=0;
            $i=1;

            $fecha_cita=Carbon::createFromFormat('H:i', $k->parametro_cita->hora_inicio)->addDay(1);
            echo $fecha_cita;
            foreach ($asesores as $asesor) {
                if ($cedula_asesor==$asesor->cedula_asesor){
                    $fecha_cita=$fecha_cita->addMinute($k->parametro_cita->tiempo_citas);
                    $i=1;
                }else{
                    $fecha_cita=Carbon::createFromFormat('H:i', $k->parametro_cita->hora_inicio)->addDay(1);
                    $cedula_asesor=$asesor->cedula_asesor;
                }

                echo "<br>cedula_asesor: ".$cedula_asesor;
                echo "<br>Clientes: ".$k->ranking_cliente->descripcion;

                $citas_max=$k->parametro_cita->citas_max;
                $total=$k->total;
                if ($k->total>$citas_max){
                    $total=$citas_max;
                }
                $clientes=tbl_clientes::where('estado',1)->where('id_ranking',$k->id_ranking_cliente)->orderBy('salario','DESC')->limit($total)->get();
                $total=$k->parametro_cita->citas_max-$k->total;

                foreach ($clientes as $cliente) {

                    $cita=new tbl_citas();
                    $cedula_cliente=$cliente->cedula_cliente;
                    if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cliente->cedula_cliente;}
                    $cita->cedula_cliente=$cedula_cliente;
                    $cita->telefono=$cliente->celular;
                    $cita->telefono=$cliente->observacion;
                    $cita->direccion_cita=$cliente->direccion_visita;

                    $cita->fecha_cita=$fecha_cita;

                    //$cita->fecha_cita=Carbon::createFromFormat('H:i', $parametros_citas->hora_inicio)->addDay(1);
                    $cita->hora_cita=$cliente->a;
                    $cita->nombres=$cliente->nombres;
                    $cita->asesor=$asesor->cedula_asesor;
                    $cita->estado=1;
                    $cita->save();

                    $cliente->estado=0;
                    $cliente->save();
                    $total_citas=$total_citas-1;

                    echo "<br>fecha_cita:".$fecha_cita;

                    //$citas_max=$citas_max-1;
                    echo "<br>".$i;
                    $i++;
                    $fecha_cita=$fecha_cita->addMinute($k->parametro_cita->tiempo_citas);
                }
            }
        }
        return redirect()->action('Bmi\BmiController@agendarCitasBmi');
        dd();

    }

    public function quitarAsignacionAutomatica()
    {
        tbl_citas::where('cita_automatica',1)->delete();
        tbl_clientes::where('select',1)->where('estado',0)->update(['select'=>0,'estado'=>1]);
        return redirect()->action('Bmi\BmiController@agendarCitasBmi');
    }

    /*
    public function asignacionAutomatica()
    {
        $citas_con_clientes=tbl_citas_con_clientes::where('estado',1)->where('total','>',0)->get();
        $total_citas=tbl_citas_con_clientes::where('estado',1)->where('total','>',0)->sum('total');

        tbl_clientes::where('estado',0)->update(['estado'=>1]);
        tbl_citas::where('estado',1)->delete();

        $cedula_asesor='';
        $i=1;
        foreach ($citas_con_clientes as $k){
            //dd(Carbon::createFromFormat('H:i', $k->parametro_cita->hora_inicio)->addDay(1)->addMinute($k->parametro_cita->tiempo_citas));
            echo "<br>Asesores: ".$k->parametro_cita->ranking_asesor->descripcion;

            $asesores=tbl_asesores::where('estado',1)->where('id_ranking',$k->parametro_cita->id_ranking)->orderBy('fecha_contrato','ASC')->get();
            echo "<br>total:".count($asesores);
            $total=0;
            $i=1;

            $fecha_cita=Carbon::createFromFormat('H:i', $k->parametro_cita->hora_inicio)->addDay(1);
            echo $fecha_cita;
            foreach ($asesores as $asesor) {
                if ($cedula_asesor==$asesor->cedula_asesor){
                    $fecha_cita=$fecha_cita->addMinute($k->parametro_cita->tiempo_citas);
                    $i=1;
                }else{
                    $fecha_cita=Carbon::createFromFormat('H:i', $k->parametro_cita->hora_inicio)->addDay(1);
                    $cedula_asesor=$asesor->cedula_asesor;
                }

                echo "<br>cedula_asesor: ".$cedula_asesor;
                echo "<br>Clientes: ".$k->ranking_cliente->descripcion;

                    $citas_max=$k->parametro_cita->citas_max;
                    $total=$k->total;
                    if ($k->total>$citas_max){
                        $total=$citas_max;
                    }
                    $clientes=tbl_clientes::where('estado',1)->where('id_ranking',$k->id_ranking_cliente)->orderBy('salario','DESC')->limit($total)->get();
                    $total=$k->parametro_cita->citas_max-$k->total;

                    foreach ($clientes as $cliente) {

                        $cita=new tbl_citas();
                        $cedula_cliente=$cliente->cedula_cliente;
                        if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cliente->cedula_cliente;}
                        $cita->cedula_cliente=$cedula_cliente;
                        $cita->telefono=$cliente->celular;
                        $cita->telefono=$cliente->observacion;
                        $cita->direccion_cita=$cliente->direccion_visita;

                        $cita->fecha_cita=$fecha_cita;

                        //$cita->fecha_cita=Carbon::createFromFormat('H:i', $parametros_citas->hora_inicio)->addDay(1);
                        $cita->hora_cita=$cliente->a;
                        $cita->nombres=$cliente->nombres;
                        $cita->asesor=$asesor->cedula_asesor;
                        $cita->estado=1;
                        $cita->save();

                        $cliente->estado=0;
                        $cliente->save();
                        $total_citas=$total_citas-1;

                        echo "<br>fecha_cita:".$fecha_cita;

                        //$citas_max=$citas_max-1;
                        echo "<br>".$i;
                        $i++;
                        $fecha_cita=$fecha_cita->addMinute($k->parametro_cita->tiempo_citas);
                    }
            }
        }
        return redirect()->action('Bmi\BmiController@agendarCitasBmi');
        dd();

        $ranking_clientes=tbl_ranking_cliente::get();
        $clientes=array();
        $asesores=tbl_asesores::where('estado',1)->count();
        $i=0;
        $total='';
        foreach ($ranking_clientes as $k){
            $total=tbl_clientes::where('estado',1)->where('id_ranking',$k->id_ranking_cliente)->count();
            if ($total>0){
                //guardo en este arreglo como llave el id ranking del cliente y le doy de valor el numero de clientes
                $clientes[tbl_ranking_cliente::where('id_ranking_cliente',$k->id_ranking_cliente)->first()->id_ranking_cliente]=tbl_clientes::where('estado',1)->where('id_ranking',$k->id_ranking_cliente)->count();
                //obtengo la lista de asesores habilitados
                $asesores=tbl_asesores::where('estado',1)->orderBy('fecha_contrato', 'ASC')->get();
                echo count($asesores);
                //echo count($asesores[0]->id_ranking);
                $i++;
            }
        }

        $parametros_citas=tbl_parametros_citas::get();

        dd($parametros_citas);
        //return view('bmi/citas/citas', compact('citas'));
    }*/



//GESTION
    public function gestion()
    {
        $user = Auth::user();
        if($user->role_id==9){
            $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
            $productos=tbl_producto::pluck("descripcion","id_producto")->all();
            return view('bmi.gestion.gestion', compact('tipo','productos'));

        }elseif($user->role_id==12){
            $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
            $productos=tbl_producto::pluck("descripcion","id_producto")->all();
            return view('bmi.gestion.gestion', compact('tipo','productos'));
        }
    }

    public function gestionA($id)
    {
        $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
        $productos=tbl_producto::where('estado',1)->pluck("descripcion","id_producto")->all();
        $user = Auth::user();
        $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();
        $id_cita=$id;
        $cita=tbl_citas::where('id_cita',$id_cita)->where('estado',1)->first();
        $url='gestionR';
        return view('bmi.gestion.gestionAsesor', compact('tipo','productos','cita','asesor','id_cita','url'));
    }

    public function gestionR(Request $request)
    {
        $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
        $productos=tbl_producto::pluck("descripcion","id_producto")->all();
        $user = Auth::user();
        $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();
        $gestion=new tbl_gestiones();
        $gestion->id_gestion_cobefec=$request->id_gestion_cobefec;
        $gestion->cedula_asesor=$asesor->cedula_asesor;
        $gestion->fecha_cita_programada=$request->fecha_cita_programada;
        $gestion->fecha_visita=$request->fecha_visita;
        $gestion->id_tipo=$request->tipo;
        $gestion->id_accion=$request->accion;
        $gestion->subaccion=$request->subaccion;
        $gestion->suma_asegurada=$request->suma_asegurada;
        $gestion->valor_prima=$request->valor_prima;
        if ($request->producto==''){
            $gestion->id_producto=tbl_producto::where('descripcion','SIN PRODUCTO')->first()->id_producto;
        }else{
            $gestion->id_producto=$request->producto;
        }
        $gestion->observaciones=$request->observaciones;

        if($request->accion==1){
            $gestion->cita_efectiva=1;
        }else{
            $gestion->cita_efectiva=0;
        }
        $gestion->fecha_proxima_visita=$request->fecha_pvisita;
        $gestion->id_cita=$request->id_cita;
        $gestion->usuario_gestion=$request->usuario_gestion;
        $gestion->pais=$request->pais;
        $gestion->estado=1;
        $gestion->save();


        // actualizo la ultima gestion
        $cliente=tbl_clientes::where('id_gestion',$gestion->id_gestion_cobefec)->first();
        $cliente->id_ultima_gestion=$gestion->id_gestion;
        $cliente->ultimo_peso=$gestion->accion->peso;
        $cliente->id_accion_ultimo_peso=$gestion->id_accion;
        $cliente->fecha_ultimo_peso=$gestion->updated_at;
        if($gestion->accion->necesita_calendario==1){
            $cliente->fecha_ultimo_peso_confirmacion_cita=$gestion->fecha_cita_programada;
        }else{
            $cliente->fecha_ultimo_peso_confirmacion_cita=null;
        }
        $cliente->save();
        //

        // actualizo la mejor gestion
        if ($cliente->ultimo_peso>=$cliente->mayor_peso && $cliente->ultimo_peso!=null && $cliente->ultimo_peso!='')
        {
            $cliente->id_mayor_gestion=$gestion->id_gestion;
            $cliente->mayor_peso=$gestion->accion->peso;
            $cliente->id_accion_mayor_peso=$gestion->id_accion;
            $cliente->fecha_mayor_peso=$gestion->updated_at;
            if($gestion->accion->necesita_calendario==1){
                $cliente->fecha_mayor_peso_confirmacion_cita=$request->fecha_cita_programada;
            }else{
                $cliente->fecha_mayor_peso_confirmacion_cita=null;
            }
            $cliente->save();
        }
        //


        tbl_citas::where('id_cita',$request->id_cita)->update(['estado'=>2]);
        $cita_gestionada=tbl_citas::where('id_cita',$gestion->id_cita)->first();
        $cita_historial=new tbl_citas_historial();
        $cita_historial->id_gestion_cobefec=$gestion->id_gestion_cobefec;
        $cita_historial->id_gestion=$cita_gestionada->id_gestion;
        $cita_historial->cedula_cliente=$cita_gestionada->cedula_cliente;
        $cita_historial->telefono=$cita_gestionada->telefono;
        $cita_historial->observacion= $gestion->observaciones;
        $cita_historial->asesor=$cita_gestionada->asesor;
        $cita_historial->estado=$cita_gestionada->estado;
        $cita_historial->direccion_cita=$cita_gestionada->direccion_cita;
        $cita_historial->fecha_cita=$cita_gestionada->fecha_cita;
        $cita_historial->hora_cita=$cita_gestionada->hora_cita;
        $cita_historial->estado_cita=$cita_gestionada->estado_cita;
        $cita_historial->cita_propia=$cita_gestionada->cita_propia;
        $cita_historial->nombres=$cita_gestionada->nombres;
        $cita_historial->id_cita_orig=$gestion->id_cita;
        $cita_historial->usuario_gestion=$cita_gestionada->usuario_gestion;
        $cita_historial->pais=$cita_gestionada->pais;
        $cita_historial->save();

        $fecha_pvisita=tbl_accion::where('id_accion',$request->accion)->first()->necesita_calendario;
        if ($fecha_pvisita==1){
            $hora_pcita=substr($request->fecha_pvisita,-5);
            $hora_pcita=trim($hora_pcita);
            $fecha_pcita=substr($request->fecha_pvisita,0,-5);
            $fecha_pcita=trim($fecha_pcita);

            $cita=new tbl_citas();
            $cita->id_gestion_cobefec=$gestion->id_gestion_cobefec;
            $cita->id_gestion=$gestion->id_gestion;
            $cita->cedula_cliente=$cita_gestionada->cedula_cliente;
            $cita->telefono=$cita_gestionada->telefono;
            $cita->observacion=$gestion->observaciones;
            $cita->asesor=$cita_gestionada->asesor;
            $cita->estado=1;
            $cita->direccion_cita=$cita_gestionada->direccion_cita;
            $cita->fecha_cita=$fecha_pcita;
            $cita->hora_cita=$hora_pcita;
            $cita->estado_cita=$cita_gestionada->estado_cita;
            $cita->nombres=$cita_gestionada->nombres;
            $cita->usuario_gestion=$cita_gestionada->usuario_gestion;
            $cita->pais=$cita_gestionada->pais;
            $cita->save();
        }

        tbl_citas::where('id_cita',$gestion->id_cita)->first()->delete();
        return redirect()->action('HomeController@index');
    }

    public function gestionAp($id)
    {
        $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
        $productos=tbl_producto::where('estado',1)->pluck("descripcion","id_producto")->all();
        $user = Auth::user();
        $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();
        $id_cita=$id;
        $cita=tbl_citas_propias::where('id_cita',$id_cita)->where('estado',1)->first();
        $url='gestionRp';
        return view('bmi.gestion.gestionAsesorPropia', compact('tipo','productos','cita','asesor','id_cita','url'));
    }

    public function gestionRp(Request $request)
    {
        $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
        $productos=tbl_producto::pluck("descripcion","id_producto")->all();
        $user = Auth::user();
        $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();

        $gestion=new tbl_gestiones_propias();
        $gestion->cedula_asesor=$asesor->cedula_asesor;
        $gestion->fecha_visita=$request->fecha_visita;
        $gestion->fecha_cita_programada=$request->fecha_cita_programada;
        $gestion->id_tipo=$request->tipo;
        $gestion->id_accion=$request->accion;
        $gestion->subaccion=$request->subaccion;
        $gestion->suma_asegurada=$request->suma_asegurada;
        $gestion->valor_prima=$request->valor_prima;
        if ($request->producto==''){
            $gestion->id_producto=tbl_producto::where('descripcion','SIN PRODUCTO')->first()->id_producto;
        }else{
            $gestion->id_producto=$request->producto;
        }
        $gestion->observaciones=$request->observaciones;

        if($request->accion==1){
            $gestion->cita_efectiva=1;
        }else{
            $gestion->cita_efectiva=0;
        }

        $gestion->fecha_proxima_visita=$request->fecha_pvisita;
        $gestion->id_cita_propia=$request->id_cita;
        $gestion->id_cita_original=$request->id_cita_original;
        $gestion->estado=1;
        $gestion->save();

        tbl_citas_propias::where('id_cita',$request->id_cita)->update(['estado'=>2]);
        $cita_gestionada=tbl_citas_propias::where('id_cita',$gestion->id_cita_propia)->first();

        $cita_historial=new tbl_citas_propias_historial();
        //$cita_historial->cedula_cliente=$cita_gestionada->cedula_cliente;
        $cita_historial->id_gestion=$cita_gestionada->id_gestion;
        $cita_historial->telefono=$cita_gestionada->telefono;
        $cita_historial->observacion=$cita_gestionada->observacion;
        $cita_historial->asesor=$cita_gestionada->asesor;
        $cita_historial->estado=$cita_gestionada->estado;
        $cita_historial->direccion_cita=$cita_gestionada->direccion_cita;
        $cita_historial->fecha_cita=$cita_gestionada->fecha_cita;
        $cita_historial->hora_cita=$cita_gestionada->hora_cita;
        $cita_historial->estado_cita=$cita_gestionada->estado_cita;
        $cita_historial->cita_propia=$cita_gestionada->cita_propia;
        $cita_historial->nombres=$cita_gestionada->nombres;
        $cita_historial->id_cita_orig=$gestion->id_cita_propia;
        $cita_historial->id_cita_original=$gestion->id_cita_original;
        $cita_historial->cedula_cliente=$request->cedula_cliente;
        $cita_historial->save();

        $fecha_pvisita=tbl_accion::where('id_accion',$request->accion)->first()->necesita_calendario;
        if ($fecha_pvisita==1){
            $hora_pcita=substr($request->fecha_pvisita,-5);
            $hora_pcita=trim($hora_pcita);
            $fecha_pcita=substr($request->fecha_pvisita,0,-5);
            $fecha_pcita=trim($fecha_pcita);

            $cita=new tbl_citas_propias();
            //$cita->id_gestion_cobefec=$gestion->id_gestion_cobefec;
            $cita->id_gestion=$gestion->id_gestion;
            $cita->cedula_cliente=$cita_gestionada->cedula_cliente;
            $cita->telefono=$cita_gestionada->telefono;
            $cita->observacion=$gestion->observaciones;
            $cita->asesor=$cita_gestionada->asesor;
            $cita->estado=1;
            $cita->direccion_cita=$cita_gestionada->direccion_cita;
            $cita->fecha_cita=$fecha_pcita;
            $cita->hora_cita=$hora_pcita;
            $cita->estado_cita=$cita_gestionada->estado_cita;
            $cita->nombres=$cita_gestionada->nombres;
            $cita->cita_propia=1;
            $cita->estado_aprobado=1;
            $cita->id_cita_original=$gestion->id_cita_original;

            $cita->cedula_cliente=$cita_gestionada->cedula_cliente;
            $cita->save();
        }

        tbl_citas_propias::where('id_cita',$gestion->id_cita_propia)->first()->delete();
        return redirect()->action('HomeController@index');
    }

    public function gestionApm($id)
    {
        $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
        $productos=tbl_producto::where('estado',1)->pluck("descripcion","id_producto")->all();
        $user = Auth::user();
        $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();
        $id_cita=$id;
        $cita=tbl_citas_historial_manuales::where('id_cita',$id_cita)->where('estado',1)->first();
        $url='gestionRpm';
        return view('bmi.gestion.gestionAsesorHistorialManual', compact('tipo','productos','cita','asesor','id_cita','url'));
    }

    public function gestionRpm(Request $request)
    {
        $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
        $productos=tbl_producto::pluck("descripcion","id_producto")->all();
        $user = Auth::user();
        $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();
        $cita_gestionada=tbl_citas_historial_manuales::where('id_cita',$request->id_cita)->first();

        $cita_historial=new tbl_citas_historial_manuales();
        $cita_historial->cedula_cliente=$cita_gestionada->cedula_cliente;
        $cita_historial->telefono=$cita_gestionada->telefono;
        $cita_historial->observacion=$request->observaciones;
        $cita_historial->asesor=$cita_gestionada->asesor;
        $cita_historial->estado=1;
        $cita_historial->direccion_cita=$cita_gestionada->direccion_cita;
        $cita_historial->estado_cita=$cita_gestionada->estado_cita;
        $cita_historial->nombres=$cita_gestionada->nombres;
        $cita_historial->nombres_asesor=$cita_gestionada->nombres_asesor;
        $cita_historial->cita_propia=1;
        $cita_historial->estado_aprobado=1;
        $cita_historial->finalizado=0;
        $cita_historial->cedula_cliente=$cita_gestionada->cedula_cliente;
        $cita_historial->id_tipo=$request->tipo;
        $cita_historial->id_accion=$request->accion;
        $cita_historial->id_cita_anterior=$request->id_cita;
        $cita_historial->fecha_visitada=$request->fecha_visita;
        $cita_historial->id_cita_orig=$request->id_cita_orig;
        $cita_historial->subaccion=$request->subaccion;
        $cita_historial->suma_asegurada=$request->suma_asegurada;
        $cita_historial->valor_prima=$request->valor_prima;
        if ($request->producto==''){
            $cita_historial->id_producto=tbl_producto::where('descripcion','SIN PRODUCTO')->first()->id_producto;
        }else{
            $cita_historial->id_producto=$request->producto;
        }

        $fecha_pvisita=tbl_accion::where('id_accion',$request->accion)->first()->necesita_calendario;
        if ($fecha_pvisita==1){
            tbl_citas_historial_manuales::where('id_cita',$request->id_cita)->update(['estado'=>2,'fecha_proxima_visita'=>$request->fecha_pvisita]);

            $hora_pcita=substr($request->fecha_pvisita,-5);
            $hora_pcita=trim($hora_pcita);
            $fecha_pcita=substr($request->fecha_pvisita,0,-5);
            $fecha_pcita=trim($fecha_pcita);

            $cita_historial->fecha_cita=$fecha_pcita;
            $cita_historial->hora_cita=$hora_pcita;

        }else{
            tbl_citas_historial_manuales::where('id_cita',$request->id_cita)->update(['estado'=>2]);
        }
        $cita_historial->save();

        if($request->accion==1){
            tbl_citas_historial_manuales::where('id_cita',$cita_historial->id_cita)->update(['estado'=>2,'finalizado'=>1,'cita_efectiva'=>1]);
        }elseif($request->accion==4){
            tbl_citas_historial_manuales::where('id_cita',$cita_historial->id_cita)->update(['estado'=>2,'finalizado'=>1]);
        }elseif($request->accion==5 || $request->accion==6 || $request->accion==7){
            tbl_citas_historial_manuales::where('id_cita',$cita_historial->id_cita)->update(['estado'=>2,'finalizado'=>1]);
        }else{
            tbl_citas_historial_manuales::where('id_cita',$cita_historial->id_cita)->update(['estado'=>1,'finalizado'=>0]);
        }

        //tbl_citas_historial_manuales::where('id_cita',$gestion->id_cita_propia)->first()->delete();
        return redirect()->action('Bmi\BmiController@historialAsesorManuales');
    }

    public function gestionShow($id)
    {
        $gestion=tbl_gestiones::where('id_gestion',$id)->first();
        $gestiones=tbl_gestiones::where('id_gestion_cobefec',$gestion->id_gestion_cobefec)->orderBy('id_gestion','DESC')->get();
        return view('bmi.gestion.gestionShow',compact('gestion','gestiones'));
    }

    public function gestionesShow($ci)
    {
        if(strlen($ci)==9){$ci= (string) '0'.$ci;}

        $cliente=tbl_clientes::where('cedula_cliente',$ci)->first();
        $gestiones=tbl_gestiones::where('id_gestion_cobefec',$cliente->id_gestion)->orderBy('id_gestion','DESC')->get();
        $gestion=tbl_gestiones::where('id_gestion_cobefec',$cliente->id_gestion)->orderBy('id_gestion','DESC')->first();


        return view('bmi.gestion.gestionShow',compact('gestion','gestiones'));
    }

    public function gestionPShow($id)
    {
        $gestion=tbl_gestiones_propias::where('id_gestion',$id)->first();
        $gestiones=tbl_gestiones_propias::where('id_cita_original',$gestion->id_cita_original)->OrderBy('id_gestion','DESC')->get();


        return view('bmi.gestion.gestionShow',compact('gestion','gestiones'));
    }

    public function gestionPmShow($id)
    {
        $cita=tbl_citas_historial_manuales::where('id_cita',$id)->first();

        $gestiones=tbl_citas_historial_manuales::where('id_cita_orig',$cita->id_cita)->get();
        return view('bmi.gestion.gestionShowManual',compact('cita'));
    }

    public function usuarioGestionesShow($cedula_asesor)
    {
        //return view('bmi.gestion.gestionShow',compact('gestion','gestiones'));

        $user=Auth::user();
        $estilo2='';
        $asesor=tbl_asesores::where('cedula_asesor',$cedula_asesor)->first();
        //$citas_historial=tbl_citas_historial::where('asesor',$asesor->cedula_asesor)->where('estado','!=',0)->get();

        //AGENCIA
        $citas_seguimiento=DB::connection('bmi')->select("select id_gestion from gestiones where cedula_asesor='".$asesor->cedula_asesor."'
        and id_gestion in (select max(id_gestion) from gestiones where cedula_asesor='".$asesor->cedula_asesor."' GROUP BY id_gestion_cobefec) and id_accion in(2,3,5,6,7,8,9);");
        $citas_seguimiento= json_decode(json_encode($citas_seguimiento), true);
        $citas_seguimiento=tbl_gestiones::whereIn('id_gestion',$citas_seguimiento)->get();

        $citas_finalizadas=tbl_gestiones::where('cedula_asesor',$asesor->cedula_asesor)->whereIn('id_accion',[1, 4])->get();

        //PROPIAS
        $citas_propias_seguimiento=DB::connection('bmi')->select("select id_gestion from gestiones_propias where cedula_asesor='".$asesor->cedula_asesor."' 
and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$asesor->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (2,3,5,6,7,8,9) GROUP BY id_cita_original ORDER BY id_gestion;");
        $citas_propias_seguimiento= json_decode(json_encode($citas_propias_seguimiento), true);
        $citas_propias_seguimiento=tbl_gestiones_propias::whereIn('id_gestion',$citas_propias_seguimiento)->get();

        $citas_propias_finalizadas=DB::connection('bmi')->select("select id_gestion from gestiones_propias where cedula_asesor='".$asesor->cedula_asesor."' 
and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$asesor->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (1,4) GROUP BY id_cita_original ORDER BY id_gestion;");
        $citas_propias_finalizadas= json_decode(json_encode($citas_propias_finalizadas), true);
        $citas_propias_finalizadas=tbl_gestiones_propias::whereIn('id_gestion',$citas_propias_finalizadas)->get();

        //MANUALES
        $citas_manuales_seguimiento=DB::connection('bmi')->select("select id_cita from citas_historial_manuales where asesor='".$asesor->cedula_asesor."' 
and id_cita in(select max(id_cita) from citas_historial_manuales 
where asesor='".$asesor->cedula_asesor."' group by cedula_cliente) and id_accion in (2,3,5,6,7,8,9) ORDER BY id_cita;");
        $citas_manuales_seguimiento= json_decode(json_encode($citas_manuales_seguimiento), true);
        $citas_manuales_seguimiento=tbl_citas_historial_manuales::whereIn('id_cita',$citas_manuales_seguimiento)->get();

        $citas_manuales_finalizadas=DB::connection('bmi')->select("select id_cita from citas_historial_manuales where asesor='".$asesor->cedula_asesor."' 
and id_cita in(select max(id_cita) from citas_historial_manuales 
where asesor='".$asesor->cedula_asesor."' group by cedula_cliente) and id_accion in (1,4) ORDER BY id_cita;");
        $citas_manuales_finalizadas= json_decode(json_encode($citas_manuales_finalizadas), true);
        $citas_manuales_finalizadas=tbl_citas_historial_manuales::whereIn('id_cita',$citas_manuales_finalizadas)->get();

        $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
        $productos=tbl_producto::pluck("descripcion","id_producto")->all();

        return view('bmi.reportes.usuariosGestionesShow', compact('tipo','productos','estilo2','citas_seguimiento','citas_finalizadas','citas_propias_seguimiento','citas_propias_finalizadas','citas_manuales_seguimiento','citas_manuales_finalizadas'));
    }

    public function selectAccion(Request $request)
    {
        if($request->ajax()){
            $accion = tbl_accion::where('id_tipo',$request->id_tipo)->pluck("descripcion","id_accion")->all();
            $data = view('bmi/ajax/ajax-select',compact('accion'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    public function selectAcciones(Request $request)
    {
        $peso=tbl_accion::where('id_accion',$request->id_accion)->first()->peso;
        $calendario=tbl_accion::where('id_accion',$request->id_accion)->first()->necesita_calendario;
        return response()->json(['options'=>$peso,'calendario'=>$calendario]);
    }

//CLIENTES RK
    public function clienteRk($cedula)
    {
        $clientesrk=tbl_clientes::where('cedula_cliente',$cedula)->first();
        $parientes=tbl_pariente::where('cedula_cliente',$cedula)->get();
        return view('bmi.clientes.clientesrk',compact('clientesrk','parientes'));
    }

    public function rankingClientes()
    {
        $clientesrk=tbl_ranking_cliente::get();
        return view('bmi.parametros.rankingClientes',compact('clientesrk'));
    }

    public function rankingCliente($id)
    {
        $cliente=tbl_ranking_cliente::where('id_ranking_cliente', $id)->first();

        return view('bmi.parametros.rankingClientesEdit',compact('cliente'));
    }

    public function rankingClienteP(Request $request)
    {
        $cliente=tbl_ranking_cliente::find($request->id_ranking_cliente);
        $cliente->monto_ini=$request->monto_ini;
        $cliente->monto_fin=$request->monto_fin;
        $cliente->nivel=$request->nivel;
        $cliente->descripcion=$request->descripcion;
        $cliente->save();
        $clientesrk=tbl_ranking_cliente::get();
        return view('bmi.parametros.rankingClientes',compact('clientesrk'));
    }
    public function rankingClienteNu(Request $request)
    {
        $cliente=new tbl_ranking_cliente;
        $cliente->monto_ini=$request->monto_ini;
        $cliente->monto_fin=$request->monto_fin;
        $cliente->nivel=$request->nivel;
        $cliente->descripcion=$request->descripcion;
        $cliente->save();
        return redirect()->action('Bmi\BmiController@rankingClientes');
    }

    public function rankingD($id)
    {
        tbl_ranking_cliente::where('id_ranking_cliente', $id)->delete();
        return redirect()->action('Bmi\BmiController@rankingClientes');
    }

//ASESORES RK
    public function verRankingAsesores()
    {
        $asesores=tbl_asesores::get();
        $i=0;
        foreach ($asesores as $k) {
            $citas_historial=tbl_citas_historial::where('asesor',$k->cedula_asesor)->count();
            $ventas=tbl_gestiones::where('cedula_asesor',$k->cedula_asesor)->where('id_accion',1)->count();
            $porcentaje_ventas=0;
            if ($citas_historial>0){
                $porcentaje_ventas=($ventas*100)/$citas_historial;
            }
            $asesores[$i]->citas_historial=$citas_historial;
            $asesores[$i]->ventas=$ventas;
            $asesores[$i]->porcentaje_ventas=$porcentaje_ventas;
            $niveles=tbl_ranking_asesor::get();
            foreach ($niveles as $nivel){
                if ($porcentaje_ventas>=$nivel->nivel_ini && $porcentaje_ventas<$nivel->nivel_fin){
                    $asesores[$i]->nivel=$nivel->nivel;
                }
            }
            $i++;
        }

        return view('bmi.asesores.verRankingAsesores',compact('asesores'));
    }

    public function nuevaCarga(){
        $user = Auth::user();
        $rol= Role::where('id',$user->role_id)->first();
        $usuarios = User::where('role_id',$rol->id)->get();

        return view('bmi/nuevaCarga', compact('user','usuarios'));
    }

    public function rankingAsesores()
    {
        $asesoresrk=tbl_ranking_asesor::get();
        return view('bmi.parametros.rankingAsesores',compact('asesoresrk'));
    }

    public function rankingAsesor($id)
    {
        $asesor=tbl_ranking_asesor::where('id_ranking', $id)->first();
        return view('bmi.parametros.rankingAsesoresEdit',compact('asesor'));
    }

    public function rankingAsesorP(Request $request)
    {
        $asesor=tbl_ranking_asesor::find($request->id_ranking);
        $asesor->valor_inicial=$request->valor_inicial;
        $asesor->valor_final=$request->valor_final;
        $asesor->nivel=$request->nivel;
        $asesor->descripcion=$request->descripcion;
        $asesor->save();
        return redirect()->action('Bmi\BmiController@rankingAsesores');
    }
    public function rankingAsesorNu(Request $request)
    {
        $asesor=new tbl_ranking_asesor();
        $asesor->valor_inicial=$request->valor_inicial;
        $asesor->valor_final=$request->valor_final;
        $asesor->nivel=$request->nivel;
        $asesor->descripcion=$request->descripcion;
        $asesor->save();
        return redirect()->action('Bmi\BmiController@rankingAsesores');
    }

    public function rankingAsesorD($id)
    {
        tbl_ranking_asesor::where('id_ranking', $id)->delete();
        return redirect()->action('Bmi\BmiController@rankingAsesores');
    }

//TIPO - ACCIONES
    public function tipoAccion()
    {
        $tipos=tbl_tipo::get();
        return view('bmi/parametros/tipoAccion', compact('tipos'));
    }

    public function tipoAccionE($id)
    {
        $tipo=tbl_tipo::where('id_tipo',$id)->first();
        $acciones=tbl_accion::where('id_tipo',$id)->get();
        return view('bmi/parametros/tipoAccionE', compact('tipo','acciones'));
    }

    public function tipoAccionU(Request $request)
    {
        $tipos=tbl_tipo::get();
        tbl_accion::where('id_accion',$request->id_accion)->update(['peso' => $request->peso,'descripcion' => $request->descripcion]);
        return redirect()->action('Bmi\BmiController@tipoAccion');
    }

    public function tipoAccionN(Request $request)
    {
        $accion=new tbl_accion();
        $accion->id_tipo=$request->id_tipo;
        $accion->descripcion=$request->descripcion;
        $accion->peso=$request->peso;
        $accion->estado=1;
        $accion->save();
        return redirect()->action('Bmi\BmiController@tipoAccion');
    }

    public function tipoAccionD($id)
    {
        $accion=tbl_accion::where('id_accion',$id)->first();
        $accion->delete();
        return redirect()->action('Bmi\BmiController@tipoAccion');
    }

//CITAS
    public function citas()
    {
        $ranking_asesores=tbl_ranking_asesor::where('estado',1)->get();

        foreach ($ranking_asesores as $k){
            $citas=tbl_parametros_citas::where('id_ranking',$k->id_ranking)->first();
            if (is_null($citas)){
                $cita=new tbl_parametros_citas();
                $cita->citas_max=0;
                $cita->tiempo_citas=0;
                $cita->hora_inicio='0:00';
                $cita->id_ranking=$k->id_ranking;
                $cita->estado=1;
                $cita->save();
            }else{
                $tasesores=tbl_asesores::where('id_ranking',$k->id_ranking)->where('estado',1)->count();
                if ($tasesores==0){
                    $pc=tbl_parametros_citas::where('id_parametros_citas',$citas->id_parametros_citas)->first();
                    $pc->estado=0;
                    $pc->save();
                }
            }
        }



        $tclientes=tbl_clientes::where('estado',1)->count();
        if ($tclientes>0){
            distribuirAsesoresClientes();
        }



        $citas=tbl_parametros_citas::orderBy('estado','DESC')->get();

        foreach ($citas as $cita){
            if ($cita->estado==0){
                $cita->estilo='warning';
                $cita->mensaje='* Desactivado';
            }
        }
        $asesores=tbl_asesores::where('estado',1)->get();
        $clientes=tbl_clientes::where('estado',1)->get();

        $estilo='hidden';
        $mensaje='';

        $citas_con_clientes=tbl_citas_con_clientes::where('estado',1)->count();


        return view('bmi/parametros/citas', compact('citas','asesores','estilo','mensaje','citas_con_clientes'));
    }

    public function citasU(Request $request)
    {

        tbl_citas_con_clientes::where('id_parametros_citas',$request->id_parametros_citas)->update(array('estado' => 0));

        $citas=tbl_parametros_citas::where('id_parametros_citas',$request->id_parametros_citas)->first();
        $citas->citas_max=$request->citas_max;
        $citas->tiempo_citas=$request->tiempo_citas;
        $citas->hora_inicio=$request->hora_inicio;
        $citas->save();

        $ranking_asesores=tbl_ranking_asesor::where('estado',1)->get();
        foreach ($ranking_asesores as $k){
            $citas=tbl_parametros_citas::where('id_ranking',$k->id_ranking)->first();
            if (is_null($citas)){
                $cita=new tbl_parametros_citas();
                $cita->citas_max=0;
                $cita->tiempo_citas=0;
                $cita->hora_inicio='0:00';
                $cita->id_ranking=$k->id_ranking;
                $cita->estado=1;
                $cita->save();
            }
        }

        /*foreach ($request->id_ranking_cliente as $k) {
            $citas_con_clientes = new tbl_citas_con_clientes();
            $citas_con_clientes->id_parametros_citas = $request->id_parametros_citas;
            $citas_con_clientes->id_ranking_cliente = $k;
            $citas_con_clientes->fecha = date('Y-m-d');
            $citas_con_clientes->estado = 1;
            $citas_con_clientes->total = $request->total[$k];
            $citas_con_clientes->save();
        }*/

        $citas=tbl_parametros_citas::orderBy('estado','DESC')->get();
        foreach ($citas as $cita){
            if ($cita->estado==0){
                $cita->estilo='warning';
                $cita->mensaje='Debe tener mÃ­nimo un asesor para que pueda editar los parÃ¡metros';
            }else{
                $cita->estilo='success';
                $cita->mensaje='ActualizaciÃ³n exitosa';
            }
        }
        $asesores=tbl_asesores::where('estado',1)->get();
        $estilo='';
        $mensaje='ActualizaciÃ³n exitosa';

        $tclientes=tbl_clientes::where('estado',1)->count();
        if ($tclientes>0){
            distribuirAsesoresClientes();
        }
        $citas_con_clientes=tbl_citas_con_clientes::where('estado',1)->count();


        return view('bmi/parametros/citas', compact('citas','asesores','estilo','mensaje','citas_con_clientes'));
    }

    public function citasUp(Request $request)
    {
        $citas=tbl_parametros_citas::where('id_parametros_citas',$request->id_parametros_citas)->first();
        $citas->citas_max=$request->citas_max;
        $citas->tiempo_citas=$request->tiempo_citas;
        $citas->hora_inicio=$request->hora_inicio;
        $citas->save();
        return "Parametros actualizados correctamente";
        //return redirect()->action('Bmi\BmiController@citas');
    }

    public function habdesc($id,$estado)
    {
        $citas=tbl_parametros_citas::where('id_parametros_citas',$id)->first();
        if($estado==1){
            $citas->estado=1;
        }elseif($estado==0){
            $citas->estado=0;
        }
        $citas->save();

        tbl_citas_con_clientes::where('estado',1)->update(['estado'=>0]);
        return redirect()->action('Bmi\BmiController@citas');
    }

    public function eliminarCita($id_ap,$id_asesor,$id_cita)
    {
        $user = Auth::user();
        if($user->role_id==9){
            if ($id_ap==1){
                $cliente=tbl_citas::where('asesor',$id_asesor)->where('id_cita',$id_cita)->first();
                if ($cliente){
                    $cliente->estado=1;
                    $cliente->estado_aprobado=1;
                    $cliente->save();
                }
            }elseif ($id_ap==0){
                $cita=tbl_citas::where('asesor',$id_asesor)->where('id_cita',$id_cita)->first();
                $cliente=tbl_clientes::where('cedula_cliente',$cita->cedula_cliente)->first();
                $cliente->estado=1;
                $cliente->save();
                $cita->delete();
            }
        }
        return redirect()->back();
    }

    public function dcitaAlert($id_ap,$id_asesor,$id_cita)
    {
        $user = Auth::user();
        if($user->role_id==9){
            if ($id_ap==0){
                $cliente_propio=tbl_citas_propias::where('asesor',$id_asesor)->where('id_cita',$id_cita)->where('cita_propia',1)->first();
                if ($cliente_propio){
                    $estilo='hidden';
                    $mensaje='hidden';
                    return view('bmi/asesor/eliminarCitasPropias', compact('estilo','mensaje','cliente_propio'));
                }
            }
        }
        return redirect()->back();
    }

    public function eliminarCitaPropia(Request $request)
    {
        $user = Auth::user();
        if($user->role_id==9){
            if ($request->id_ap==0){
                $cliente=tbl_citas_propias::where('asesor',$request->id_asesor)->where('id_cita',$request->id_cita)->where('cita_propia',1)->first();
                if ($cliente){
                    $clientHistorial=new tbl_citas_propias_historial();
                    $clientHistorial->nombres=$cliente->nombres;
                    $clientHistorial->telefono=$cliente->telefono;
                    $clientHistorial->observacion=$cliente->observacion;
                    $clientHistorial->asesor=$cliente->asesor;
                    $clientHistorial->direccion_cita=$cliente->direccion_cita;
                    $clientHistorial->fecha_cita=$cliente->fecha_cita;
                    $clientHistorial->hora_cita=$cliente->hora_cita;
                    $clientHistorial->estado_cita=$cliente->estado_cita;
                    $clientHistorial->cita_propia=$cliente->cita_propia;
                    $clientHistorial->estado=2;
                    $clientHistorial->estado_aprobado=2;

                    $clientHistorial->id_cita_orig=$cliente->id_cita;
                    $clientHistorial->observacion_anulacion=$request->observacion_anulacion;
                    $clientHistorial->save();
                    $cliente->delete();
                }
            }
        }
        return redirect()->action('HomeController@index');
    }

    public function dcita($id_ap,$id_asesor,$id_cita)
    {
        $user = Auth::user();
        if($user->role_id==9){
            if ($id_ap==1){
                $cliente=tbl_citas_propias::where('asesor',$id_asesor)->where('id_cita',$id_cita)->where('cita_propia',1)->first();
                if ($cliente){
                    $cliente->estado=1;
                    $cliente->estado_aprobado=1;
                    $cliente->save();
                }
            }elseif ($id_ap==0){
                $cliente=tbl_citas_propias::where('asesor',$id_asesor)->where('id_cita',$id_cita)->where('cita_propia',1)->first();
                if ($cliente){
                    $cliente->estado=2;
                    $cliente->estado_aprobado=2;
                    $cliente->save();

                    $clientHistorial=new tbl_citas_propias_historial();
                    $clientHistorial->nombres=$cliente->nombres;
                    $clientHistorial->telefono=$cliente->telefono;
                    $clientHistorial->observacion=$cliente->observacion;
                    $clientHistorial->asesor=$cliente->asesor;
                    $clientHistorial->estado=$cliente->estado;
                    $clientHistorial->direccion_cita=$cliente->direccion_cita;
                    $clientHistorial->fecha_cita=$cliente->fecha_cita;
                    $clientHistorial->hora_cita=$cliente->hora_cita;
                    $clientHistorial->estado_cita=$cliente->estado_cita;
                    $clientHistorial->cita_propia=$cliente->cita_propia;
                    $clientHistorial->estado_aprobado=$cliente->estado_aprobado;
                    $clientHistorial->id_cita_orig=$cliente->id_cita;
                    $clientHistorial->save();
                    $cliente->delete();
                }
            }
        }
        return redirect()->back();
    }

//PRODUCTOS
    public function productos()
    {
        $productos=tbl_producto::where('estado',1)->get();
        return view('bmi/parametros/productos', compact('productos'));
    }

    public function productosS($id)
    {
        $producto=tbl_producto::where('id_producto',$id)->first();
        return view('bmi/parametros/productosS', compact('producto'));
    }

    public function productoU(Request $request)
    {
        tbl_producto::where('id_producto',$request->id_producto)->update(['descripcion'=>$request->descripcion]);
        return redirect()->action('Bmi\BmiController@productos');
    }

    public function productosNu(Request $request)
    {
        $producto=new tbl_producto();
        $producto->descripcion=$request->descripcion;
        $producto->estado=1;
        $producto->save();

        return redirect()->action('Bmi\BmiController@productos');
    }

    public function productoD($id)
    {
        tbl_producto::where('id_producto',$id)->update(['estado'=>0]);
        return redirect()->action('Bmi\BmiController@productos');
    }

    public function cambiarPs(Request $request){
        $usuario = Auth::user();

        $mensaje='';
        $error=0;
        if ($request->contrasena_n!=$request->contrasena_n2) {
            $mensaje.='<li>ContraseÃ±as no coinciden</li>';
            $estilo='alert-danger show';
            $error++;
        }
        if (strlen($request->contrasena_n)<7) {
            $mensaje.='<li>La contraseÃ±a debe tener mÃ¡s de 6 caracteres</li>';
            $estilo='alert-danger show';
            $error++;
        }
        if ($error==0){
            $usuario->password=Hash::make($request->contrasena_n);
            $usuario->save();

            $mensaje.='<li>ContraseÃ±a actualizada exitosamente</li>';
            $estilo='alert-success show';
        }
        return view('bmi.contrasena.cambiarP',compact('mensaje','estilo','asesor'));
    }

    public function rcontrasena(Request $request){
        $usuario = Auth::user();
        $estilo2='';
        $asesor=tbl_asesores::where('email_corporativo',$usuario->email)->first();
        $mensaje='';
        $error=0;
        if ($request->contrasena_n!=$request->contrasena_n2) {
            $mensaje.='<li>ContraseÃ±as no coinciden</li>';
            $estilo='alert-danger show';
            $error++;
        }
        if (strlen($request->contrasena_n)<7) {
            $mensaje.='<li>La contraseÃ±a debe tener mÃ¡s de 6 caracteres</li>';
            $estilo='alert-danger show';
            $error++;
        }
        if ($error==0){
            $usuario->password=Hash::make($request->contrasena_n);
            $usuario->save();

            $asesor->r_contrasena=1;
            $asesor->save();

            $mensaje.='<li>ContraseÃ±a actualizada exitosamente <a href="/home" class="btn btn-primary">Ingresar</a> </li>';
            $estilo='alert-success show';
            $estilo2='hidden';
        }
        return view('bmi.contrasena.rcontrasena',compact('mensaje','estilo','asesor','estilo2'));
    }

    public function agendarCitasPropiasN(Request $request)
    {
        $fecha_cita=Carbon::parse($request->fecha_cita);
        $usuario = Auth::user();
        $asesor=tbl_asesores::where('email_corporativo',$usuario->email)->first();
        $mensaje='';
        $error=0;
        $cita=new  tbl_citas_propias();
        $cita->asesor=$asesor->cedula_asesor;
        $cita->fecha_cita=$fecha_cita->format('Y-m-d');
        $cita->hora_cita=$fecha_cita->format('H:i');
        $cita->nombres=$request->nombres;
        $cita->telefono=$request->telefono;
        $cita->direccion_cita=$request->direccion_cita;
        $cita->observacion=$request->observacion;
        $cita->cita_propia=1;
        $cita->estado=0;
        $cita->save();

        /*tbl_notificaciones::create([
            'envia_id'=>$asesor->cedula_asesor,
            'recibe_id'=>'1717171818',
            'mensaje'=>'El asesor '.$asesor->nombres.' ha ingresado una nueva cita propia',
            ]
        );*/

        $cita->id_cita_original=$cita->id_cita;
        $cita->save();

        //return back()->with('flash','Tu cita propia ha sido ingresada correctamente');
        return redirect()->action('HomeController@index');
    }

    public function historialAsesor()
    {
        $user=Auth::user();
        $estilo2='';
        $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();
        //$citas_historial=tbl_citas_historial::where('asesor',$asesor->cedula_asesor)->where('estado','!=',0)->get();

        $citas_historial = tbl_citas::where('asesor',$asesor->cedula_asesor)->whereNotNull('id_gestion')->get();

        $citas_historial_finalizadas=DB::connection('bmi')->select("select id_cita from gestiones where cedula_asesor='".$asesor->cedula_asesor."' and id_accion in(1,4);");
        $citas_historial_finalizadas = json_decode(json_encode($citas_historial_finalizadas), true);
        $citas_historial_finalizadas=tbl_citas_historial::whereIn('id_cita_orig',$citas_historial_finalizadas)->get();

        $citasPropiasHistorial=DB::connection('bmi')->select("SELECT max(id_cita) id_cita FROM citas_propias_historial where asesor='".$asesor->cedula_asesor."' and estado=2 GROUP BY id_cita_original;");
        $citasPropiasHistorial= json_decode(json_encode($citasPropiasHistorial), true);
        $citasPropiasHistorial=tbl_citas_propias_historial::whereIn('id_cita',$citasPropiasHistorial)->get();

        $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
        $productos=tbl_producto::pluck("descripcion","id_producto")->all();
        $historialCitasManuales=tbl_citas_historial_manuales::where('estado',1)->where('asesor',$asesor->cedula_asesor)->whereIn('id_accion',[1,4])->get();
        return view('bmi.bmiHistorial', compact('tipo','productos','estilo2','citas_historial','citasPropiasHistorial','historialCitasManuales','citas_historial_finalizadas'));
    }

    public function historialAsesorManuales()
    {
        $user=Auth::user();
        $estilo2='';
        $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();
        $tipo=tbl_tipo::pluck("descripcion","id_tipo")->all();
        $productos=tbl_producto::pluck("descripcion","id_producto")->all();

        $citas_pendientes=DB::connection('bmi')->select("select id_cita from citas_historial_manuales where asesor='".$asesor->cedula_asesor."' 
and id_cita in(select max(id_cita) from citas_historial_manuales 
where asesor='".$asesor->cedula_asesor."' group by cedula_cliente) and id_accion in (2,3,5,6,7,8,9) ORDER BY id_cita;
");
        $citas_pendientes= json_decode(json_encode($citas_pendientes), true);
        //me quedo solo con las citas que estan pendientes
        $historialCitasManuales=tbl_citas_historial_manuales::whereIn('id_cita',$citas_pendientes)->get();


        $citas_finalizadas=DB::connection('bmi')->select("select id_cita from citas_historial_manuales where asesor='".$asesor->cedula_asesor."' 
and id_cita in(select max(id_cita) from citas_historial_manuales 
where asesor='".$asesor->cedula_asesor."' group by cedula_cliente) and id_accion in (1,4) ORDER BY id_cita;
");
        $citas_finalizadas= json_decode(json_encode($citas_finalizadas), true);
        //me quedo solo con las citas finalizadas
        $historialCitasManualesFinalizadas=tbl_citas_historial_manuales::whereIn('id_cita',$citas_finalizadas)->get();
        return view('bmi.bmiHistorialManuales', compact('tipo','productos','estilo2','citas_historial','citasPropiasHistorial','historialCitasManuales','historialCitasManualesFinalizadas'));
    }

    public function historialCitasA()
    {
        $estilo2='';
        $citas_seguimiento=DB::connection('bmi')->select("select id_gestion from gestiones where 
        id_gestion in (select max(id_gestion) from gestiones GROUP BY id_gestion_cobefec) and id_accion in(2,3,5,6,7,8,9) and id_gestion <> 2994;");
        $citas_seguimiento= json_decode(json_encode($citas_seguimiento), true);
        $citas_seguimiento=tbl_gestiones::whereIn('id_gestion',$citas_seguimiento)->get();


        $citas_finalizadas=tbl_gestiones::whereIn('id_accion',[1, 4])->get();

        return view('bmi.bmiHistorialSupervisor', compact('estilo2','citas_seguimiento','citas_finalizadas'));
    }

    public function historialCitasP()
    {
        $estilo2='';

        //PROPIAS
        $citas_propias_seguimiento=DB::connection('bmi')->select("select id_gestion from gestiones_propias where  
id_cita_original in(select MAX(id_cita_original) from gestiones_propias
GROUP BY id_cita_original) and id_accion in (2,3,5,6,7,8,9) GROUP BY id_cita_original ORDER BY id_gestion;");
        $citas_propias_seguimiento= json_decode(json_encode($citas_propias_seguimiento), true);
        $citas_propias_seguimiento=tbl_gestiones_propias::whereIn('id_gestion',$citas_propias_seguimiento)->get();

        $citas_propias_finalizadas=DB::connection('bmi')->select("select id_gestion from gestiones_propias where  
id_cita_original in(select MAX(id_cita_original) from gestiones_propias GROUP BY id_cita_original) and id_accion in (1,4) GROUP BY id_cita_original ORDER BY id_gestion;");
        $citas_propias_finalizadas= json_decode(json_encode($citas_propias_finalizadas), true);
        $citas_propias_finalizadas=tbl_gestiones_propias::whereIn('id_gestion',$citas_propias_finalizadas)->get();


        return view('bmi.bmiHistorialSupervisorCPropias', compact('estilo2','citas_propias_seguimiento','citas_propias_finalizadas'));
    }

    public function historialCitasM()
    {
        $estilo2='';
        //MANUALES
        $citas_manuales_seguimiento=DB::connection('bmi')->select("select id_cita from citas_historial_manuales where  
id_cita in(select max(id_cita) from citas_historial_manuales 
group by cedula_cliente) and id_accion in (2,3,5,6,7,8,9) ORDER BY id_cita;");
        $citas_manuales_seguimiento= json_decode(json_encode($citas_manuales_seguimiento), true);
        $citas_manuales_seguimiento=tbl_citas_historial_manuales::whereIn('id_cita',$citas_manuales_seguimiento)->get();

        $citas_manuales_finalizadas=DB::connection('bmi')->select("select id_cita from citas_historial_manuales where  
id_cita in(select max(id_cita) from citas_historial_manuales 
group by cedula_cliente) and id_accion in (1,4) ORDER BY id_cita;");
        $citas_manuales_finalizadas= json_decode(json_encode($citas_manuales_finalizadas), true);
        $citas_manuales_finalizadas=tbl_citas_historial_manuales::whereIn('id_cita',$citas_manuales_finalizadas)->get();

        return view('bmi.bmiHistorialSupervisorCManuales', compact('estilo2','citas_manuales_seguimiento','citas_manuales_finalizadas'));
    }

    public function clienteP($cliente)
    {
        $user=Auth::user();
        $asesor=tbl_asesores::where('email_corporativo',$user->email)->first();
        $cliente_propio=tbl_citas_propias::where('id_cita',$cliente)->where('asesor',$asesor->cedula_asesor)->first();
        $estilo='hidden';
        $mensaje='hidden';
        return view('bmi/asesor/editarCitasPropias', compact('estilo','mensaje','cliente_propio'));
    }
    public function editarCitasPropias(Request $request){
        $fecha_cita=Carbon::parse($request->fecha_cita);
        $usuario = Auth::user();
        $asesor=tbl_asesores::where('email_corporativo',$usuario->email)->first();
        $mensaje='';
        $error=0;
        $cita=tbl_citas_propias::where('id_cita',$request->id_cita)->where('asesor',$asesor->cedula_asesor)->first();

        $cita->fecha_cita=$fecha_cita->format('Y-m-d');
        $cita->hora_cita=$fecha_cita->format('H:i');
        $cita->nombres=$request->nombres;
        $cita->telefono=$request->telefono;
        $cita->direccion_cita=$request->direccion_cita;
        $cita->observacion=$request->observacion;
        $cita->save();
        return redirect()->action('HomeController@index');
    }


    public function actualizarClientes(Request $request)
    {
        $fecha = date('Y-m-d');

        $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );

        tbl_clientes::where('fecha_cita','<',$fecha)->update(['estado'=>0]);

        $fecha = date('d-m-Y');

        $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'd-m-Y' , $nuevafecha );

        try{
            //CONSULTAMOS CUENTAS DEL SERVIDOR DE GESTION ANTERIOR DE ECUADOR
            /*
            $cuentashoy = DB::connection('gestionec')->select("SELECT a.fecha_pp,(select s.nombre from gestion g, sub_motivo s where s.id=g.sub_motivo_id and g.id=b.mejor_gestion_id) hora_cita,
b.id as id_cuenta,b.mejor_gestion_id as id_gestion, c.nombre as descripcion_gestion, d.username as usuario_gestion,
b.ci as cedula ,b.nombre,b.direccion , a.telefono as telefonos, b.zona, b.principales_serializados
,  a.observacion
FROM copiaprod.gestion a, copiaprod.cuenta b, copiaprod.accion c, copiaprod.usuario d, copiaprod.capana e, copiaprod.producto f, copiaprod.marca g
WHERE b.id=a.cuenta_id and c.id=a.accion_id and d.id=a.usuario_id and e.id=b.capana_id
and g.id=f.marca_id and f.id=e.producto_id and g.id='36' and a.telefono <> 'Cobranza Externa'
 and a.accion_id in ('4062','4175')
  -- and c.nombre='Cita Confirmada'
and date(a.fecha_pp)='".date('Y-m-d')."'
;
");*/
            //CONSULTAMOS CUENTAS DEL SERVIDOR DE PERU
            /*
            $cuentaspehoy = DB::connection('gestionpe')->select("SELECT a.fecha_pp,(select s.nombre from gestion g, sub_motivo s where s.id=g.sub_motivo_id and g.id=b.mejor_gestion_id) hora_cita,
b.id as id_cuenta,b.mejor_gestion_id as id_gestion, c.nombre as descripcion_gestion, d.username as usuario_gestion,
b.ci as cedula ,b.nombre,b.direccion , a.telefono as telefonos, b.zona, b.principales_serializados
,  a.observacion
FROM cobefec_pruebas.gestion a, cobefec_pruebas.cuenta b, cobefec_pruebas.accion c, cobefec_pruebas.usuario d, cobefec_pruebas.capana e, cobefec_pruebas.producto f, cobefec_pruebas.marca g
WHERE b.id=a.cuenta_id and c.id=a.accion_id and d.id=a.usuario_id and e.id=b.capana_id
and g.id=f.marca_id and f.id=e.producto_id and g.id='41'
and a.accion_id='4186'
and date(a.fecha_pp)='".$fecha."'
;
");
            */
            //CONSULTAMOS CUENTAS DEL SERVIDOR DE GESTION ANTERIOR DE ECUADOR
            /*
            $cuentas = DB::connection('gestionec')->select("SELECT a.fecha_pp,(select s.nombre from gestion g, sub_motivo s where s.id=g.sub_motivo_id and g.id=b.mejor_gestion_id) hora_cita,
b.id as id_cuenta,b.mejor_gestion_id as id_gestion, c.nombre as descripcion_gestion, d.username as usuario_gestion,
b.ci as cedula ,b.nombre,b.direccion , a.telefono as telefonos, b.zona, b.principales_serializados
,  a.observacion
FROM copiaprod.gestion a, copiaprod.cuenta b, copiaprod.accion c, copiaprod.usuario d, copiaprod.capana e, copiaprod.producto f, copiaprod.marca g
WHERE b.id=a.cuenta_id and c.id=a.accion_id and d.id=a.usuario_id and e.id=b.capana_id
and g.id=f.marca_id and f.id=e.producto_id and g.id='36' and a.telefono <> 'Cobranza Externa'
 and a.accion_id in ('4062','4175')
and date(a.fecha_pp)='".$nuevafecha."'
;
");
            */
            //CONSULTAMOS CUENTAS DEL SERVIDOR DE PERU
            /*$cuentaspe = DB::connection('gestionpe')->select("SELECT a.fecha_pp,(select s.nombre from gestion g, sub_motivo s where s.id=g.sub_motivo_id and g.id=b.mejor_gestion_id) hora_cita,
   b.id as id_cuenta,b.mejor_gestion_id as id_gestion, c.nombre as descripcion_gestion, d.username as usuario_gestion,
   b.ci as cedula ,b.nombre,b.direccion , a.telefono as telefonos, b.zona, b.principales_serializados
   ,  a.observacion
   FROM cobefec_pruebas.gestion a, cobefec_pruebas.cuenta b, cobefec_pruebas.accion c, cobefec_pruebas.usuario d, cobefec_pruebas.capana e, cobefec_pruebas.producto f, cobefec_pruebas.marca g
   WHERE b.id=a.cuenta_id and c.id=a.accion_id and d.id=a.usuario_id and e.id=b.capana_id
   and g.id=f.marca_id and f.id=e.producto_id and g.id='41'
    and a.accion_id='4186'
   and date(a.fecha_pp)='".$nuevafecha."'
   ;
   ");*/
            //dd($cuentaspe);

            $cuentashoy = DB::connection('cobefec3')->select("select d.id as id_gestion, a.data, d.account_id as id_cuenta, d.action as descripcion_gestion,
d.agent as usuario_gestion, a.target_document as cedula, a.data ->> '$.nombre' as nombre, 
a.data ->> '$.nombres' as nombres, a.data ->> '$.direccion_empresa.original_address' as direccion_empresa, a.data ->> '$.medios1' as telefonos1,
a.data ->> '$.direccion.original_address' as direccion,
 a.data ->> '$.telf1' as telefonos, '' zona, d.description as observacion, d.extra ->> '$.pp_date' fecha_cita, LEFT(RIGHT(d.extra ->> '$.call_again',5),6) hora_cita
from cobefec3.demarches d, cobefec3.accounts a 
where d.extra ->> '$.pp_date'='".$fecha."' and d.weight=184 and d.action='Cita Confirmada' and d.account_id=a.id
;
");
            $cuentas = DB::connection('cobefec3')->select("select d.id as id_gestion, a.data, d.account_id as id_cuenta, d.action as descripcion_gestion, 
d.agent as usuario_gestion, a.target_document as cedula, a.data ->> '$.nombre' as nombre, 
a.data ->> '$.nombres' as nombres, a.data ->> '$.direccion_empresa.original_address' as direccion_empresa, a.data ->> '$.medios1' as telefonos1,
a.data ->> '$.direccion.original_address' as direccion,
 a.data ->> '$.telf1' as telefonos, '' zona, d.description as observacion, d.extra ->> '$.pp_date' fecha_cita, LEFT(RIGHT(d.extra ->> '$.call_again',5),6) hora_cita
from cobefec3.demarches d, cobefec3.accounts a 
where d.extra ->> '$.pp_date'='".$nuevafecha."' and d.weight=184 and d.action='Cita Confirmada' and d.account_id=a.id
;
");
            $cuentas= array_merge($cuentas,$cuentashoy);
            ///dd($cuentas);

            for ($i=0;$i<count($cuentas);$i++){
                $principales=json_decode($cuentas[$i]->data, true);
                $cuentas[$i]->fecha_nacimiento=$principales['fecha_nacimiento'];
                $cuentas[$i]->edad=$principales['edad'];
                $cuentas[$i]->estado_civil=$principales['estado_civil'];
                //$cuentas[$i]->cod_profesion=$principales[6];
                //$cuentas[$i]->cod_profesion='';
                //$cuentas[$i]->salario=$principales[7];
                //$cuentas[$i]->salario=$principales[9];
                $cuentas[$i]->cupo=isset($principales['cupo']) ? $principales['cupo'] : 'SIN CUPO';
                //$cuentas[$i]->empresa=$principales[8];
                //$cuentas[$i]->empresa=$principales[6];
                if(isset($principales['provincia'])){
                    $cuentas[$i]->provincia=$principales['provincia'];
                }else{
                    $cuentas[$i]->provincia='SIN PROVINCIA';
                }
                //$cuentas[$i]->canton=$principales[15];
                //$cuentas[$i]->canton=$principales[13];
                //$cuentas[$i]->cargo=$principales[17];
                //$cuentas[$i]->cargo=$principales[8];
                //$cuentas[$i]->ingreso=$principales[18];
                //$cuentas[$i]->ingreso=$principales[10];
                //$cuentas[$i]->tiempo_de_trabajo=$principales[20];
                //$cuentas[$i]->tiempo_de_trabajo='';
                //$cuentas[$i]->email=$principales[33];
                //$cuentas[$i]->email=$principales[11];

                //$cuentas[$i]->cedula_conyuge=$principales[35];
                //$cuentas[$i]->cedula_conyuge=$principales[16];
                //$cuentas[$i]->nombres_conyuge=$principales[36];
                //$cuentas[$i]->nombres_conyuge=$principales[15];
                //$cuentas[$i]->fecha_nacimiento_conyuge=$principales[37];
                //$cuentas[$i]->fecha_nacimiento_conyuge=$principales[17];
                //$cuentas[$i]->edad_conyuge=$principales[38];
                //$cuentas[$i]->edad_conyuge='';
                //$cuentas[$i]->salario_conyuge=$principales[39];
                //$cuentas[$i]->salario_conyuge=$principales[19];
                //$cuentas[$i]->empresa_conyuge=$principales[40];
                //$cuentas[$i]->empresa_conyuge=$principales[18];
                $cuentas[$i]->pais='Ecuador';
            }

            //UNIFICAMOS ECUADOR Y PERU
            //$cuentaspe= array_merge($cuentaspe,$cuentaspehoy);
            //dd($cuentaspehoy);
            /*for ($i=0;$i<count($cuentaspe);$i++){
                $principales=json_decode($cuentaspe[$i]->principales_serializados, true);
                $cuentaspe[$i]->fecha_nacimiento=$principales[3];
                $cuentaspe[$i]->edad=$principales[4];
                $cuentaspe[$i]->estado_civil=$principales[5];
                $cuentaspe[$i]->cod_profesion='';
                $cuentaspe[$i]->salario=$principales[9];
                $cuentaspe[$i]->empresa=$principales[6];
                if(isset($principales[14])){
                    $cuentaspe[$i]->provincia=$principales[14];
                }else{
                    $cuentaspe[$i]->provincia='SIN PROVINCIA';
                }
                $cuentaspe[$i]->canton=$principales[7];
                $cuentaspe[$i]->cargo=$principales[8];
                $cuentaspe[$i]->ingreso=$principales[10];
                $cuentaspe[$i]->tiempo_de_trabajo='';
                $cuentaspe[$i]->email=$principales[11];

                $cuentaspe[$i]->cedula_conyuge=$principales[16];
                $cuentaspe[$i]->nombres_conyuge=$principales[15];
                $cuentaspe[$i]->fecha_nacimiento_conyuge=$principales[17];
                $cuentaspe[$i]->edad_conyuge='';
                $cuentaspe[$i]->salario_conyuge=$principales[19];
                $cuentaspe[$i]->empresa_conyuge=$principales[18];
                $cuentaspe[$i]->pais='Peru';
            }*/


            //UNIFICAMOS ECUADOR Y PERU
            //$cuentas= array_merge($cuentas,$cuentaspe);

            $ingresos=0;
            DB::connection('bmi')->table('clientes2')->truncate();
            foreach ($cuentas as $carga) {
                $cliente = new tbl_clientes2();
                $cedula_cliente=trim($carga->cedula);
                if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cedula_cliente;}
                if(strlen($cedula_cliente)==11){$cedula_cliente=substr($cedula_cliente,1);}

                $cliente->cedula = $cedula_cliente;
                $cliente->nombres = isset($carga->nombre) ? $carga->nombre : $carga->nombres;
                $cliente->fecha_nacimiento =$carga->fecha_nacimiento;
                $cliente->edad = $carga->edad;
                $cliente->estado_civil = $carga->estado_civil;
                $cliente->cod_profesion = isset($carga->cod_profesion ) ? $carga->cod_profesion : '';
                $cliente->salario = isset($carga->salario ) ? doubleval($carga->salario) : '';
                $cliente->empresa = isset($carga->empresa ) ? $carga->empresa : '';

                $cliente->telefono = isset($carga->telefonos ) ? $carga->telefonos : $carga->telefonos1;
                $cliente->provincia = isset($carga->provincia ) ? $carga->provincia : '';
                $cliente->ciudad = isset($carga->canton ) ? $carga->canton : '';
                $cliente->cargo = isset($carga->cargo ) ? $carga->cargo : '';
                $cliente->ingreso = isset($carga->ingreso ) ? $carga->ingreso : '';
                $cliente->tiempo_de_trabajo = isset($carga->tiempo_de_trabajo ) ? $carga->tiempo_de_trabajo : '';
                $cliente->email = isset($carga->email ) ? $carga->email : '';


                $cliente->cedula_conyugue=isset($carga->cedula_conyuge ) ? $carga->cedula_conyuge : '';
                $cliente->nombres_conyugue=isset($carga->nombres_conyuge ) ? $carga->nombres_conyuge : '';
                $cliente->fecha_nacimiento_conyugue=isset($carga->fecha_nacimiento_conyuge ) ? $carga->fecha_nacimiento_conyuge : '';
                $cliente->edad_conyugue=isset($carga->edad_conyuge ) ? $carga->edad_conyuge : '';
                $cliente->salario_conyugue=isset($carga->salario_conyuge ) ? doubleval($carga->salario_conyuge) : '';
                $cliente->empresa_conyugue=isset($carga->empresa_conyuge ) ? $carga->empresa_conyuge : '';

                $cliente->id_cuenta=isset($carga->id_cuenta ) ? $carga->id_cuenta : '';
                $cliente->id_gestion=isset($carga->id_gestion ) ? $carga->id_gestion : '';
                $cliente->usuario_gestion=isset($carga->usuario_gestion ) ? $carga->usuario_gestion : '';
                $cliente->descripcion_gestion=isset($carga->descripcion_gestion ) ? $carga->descripcion_gestion : '';

                //cambio nuevo
                $chora_cita=$carga->hora_cita;
                //$chora_cita=trim ($chora_cita);
                //$chora_cita=substr($chora_cita, 0, -3);

                $cliente->fecha_cita=Carbon::parse($carga->fecha_cita)->format('Y-m-d');
                $cliente->hora_cita=$chora_cita;

                //cambio nuevo
                $cliente->observacion=$carga->observacion;
                $cliente->pais=$carga->pais;


                //Datos clientes
                $cnombres='';
                $cobservacion=$carga->observacion;
                //echo $cobservacion."\n\n";
                $contador=1;
                $cnombres=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "Nombres: ".$cnombres."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                //
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $ccedula=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "CI: ".$ccedula."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                //
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $cfechanacieminto=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "Fecha de nacimiento: ".$cfechanacieminto."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                //
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $cedad=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "Edad: ".$cedad."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                //
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $cestado_civil=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "Estado civil: ".$cestado_civil."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                //
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $cfecha_hora_cita=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "Fecha y hora de cita: ".$cfecha_hora_cita."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                //
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $ctelefono_contacto=strstr($carga->observacion, 'TELEFONO', false);
                $ctelefono_contacto=strstr($ctelefono_contacto, '..',true);
                $cobservacion=strstr($cobservacion, '..', false);

                //echo "TelÃ©fono de contacto: ".$ctelefono_contacto."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                //
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $cdireccion_cita=strstr($carga->observacion, 'DIRECCION', false);
                $cdireccion_cita=strstr($cdireccion_cita, '..', true);
                //$cdireccion_cita=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, 'DIRECCION', false);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "Direccion de la Cita: ".$cdireccion_cita."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                //
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $ctrabajo=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $ccargo=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "Cargo: ".$ccargo."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $csueldo=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "Sueldo: ".$csueldo."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                $ccupo=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                /*
                $cfehca_ingreso_empresa=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "Fecha ingreso a la empresa: ".$cfehca_ingreso_empresa."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);
                */


                //$cempresa=strstr($cobservacion, '..', true);
                //$cobservacion=strstr($cobservacion, '..', false);
                //echo "Empresa: ".$cempresa."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                //
                //$cobservacion=ltrim($cobservacion);
                //$cobservacion=substr($cobservacion, 2);
                //$cobservacion=ltrim($cobservacion);


                $fingreso=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);
                //$fingreso=str_replace('.','',$fingreso);
                //$fingreso=trim($fingreso);
                //$fingreso=strstr($fingreso, '..', false);
                //$fingreso=trim($fingreso);

                $cemail=str_replace('CORREO ELECTRONICO','',$cobservacion);
                $cemail=trim($cemail);
                $cemail=str_replace(':','',$cemail);
                $cemail=trim($cemail);
                //$cemail=str_replace('.','',$cemail);
                //$cemail=trim($cemail);
                $cemail=strstr($cemail, '..', false);
                $cemail=trim($cemail);

                //$cemail=strstr($cobservacion, '..', true);
                $cobservacion=strstr($cobservacion, '..', false);
                $cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);

                //$cemail=strstr($cobservacion, '..', true);
                //$cobservacion=strstr($cobservacion, '..', false);
                //echo "Correo electrÃ³nico: ".$cemail."\n";
                //echo "Observacion: ".$cobservacion."\n\n";
                //
                /*$cobservacion=ltrim($cobservacion);
                $cobservacion=substr($cobservacion, 2);
                $cobservacion=ltrim($cobservacion);*/

                $cdatos_acicionales=strstr($carga->observacion, 'DATOS ADICI', false);
                $cobservacion=strstr($cobservacion, '..', false);
                //echo "Datos adicionales: ".$cdatos_acicionales."\n";
                //echo "Observacion: ".$cobservacion."\n\n";

                $cliente->salario = $csueldo;
                $cliente->cupo = $ccupo;
                $cliente->empresa = $ctrabajo;
                $cliente->ingreso = $fingreso;
                $cliente->email= $cemail;


                $cliente->celular = $ctelefono_contacto;
                $cliente->direccion_visita = $cdireccion_cita;
                $cliente->datos_adicionales = $cdatos_acicionales;

                try{
                    $cliente->save();
                }catch(\Exception $e){

                    return \Response::json('Ocurrio un error: '.$e->getMessage(), 500);
                }



                if (is_null($cliente->ingreso)){$cliente->ingreso=null;}else{$cliente->ingreso=str_replace('/','-',$cliente->ingreso);}
                if (is_null($cliente->salida)){$cliente->salida=null;}else{$cliente->salida=str_replace('/','-',$cliente->salida);}


                $empresa=tbl_empresa::where('nombre',$cliente->empresa)->first();
                if(is_null($empresa)){
                    $empresa = new tbl_empresa();
                    $empresa->nombre = $cliente->empresa;
                    $empresa->direccion = $cliente->direccion_empresa;
                    $empresa->descripcion = $cliente->descripcion;
                    $empresa->save();
                }else{
                    if ($cliente->empresa == $empresa->nombre) {
                        $empresa->direccion = $cliente->direccion_empresa;
                        $empresa->descripcion = $cliente->descripcion;
                        $empresa->save();
                    } else {
                        $empresa = new tbl_empresa();
                        $empresa->nombre = $cliente->empresa;
                        $empresa->direccion = $cliente->direccion_empresa;
                        $empresa->descripcion = $cliente->descripcion;

                        try{
                            $empresa->save();
                        }catch(\Exception $e){

                            return \Response::json('Ocurrio un error: '.$e->getMessage(), 500);
                        }
                    }
                }

                if(tbl_clientes::where('cedula_cliente', $cliente->cedula)->first()){
                    $clientes=tbl_clientes::where('cedula_cliente', $cliente->cedula)->first();
                    $clientes->contador_carga=$clientes->contador_carga+1;
                }else{
                    $clientes=new tbl_clientes();
                    $clientes->cedula_cliente=$cliente->cedula;
                    $clientes->contador_carga=1;
                }


                $clientes->id_cuenta=$cliente->id_cuenta;
                $clientes->id_gestion=$cliente->id_gestion;
                $clientes->usuario_gestion=$cliente->usuario_gestion;
                $clientes->descripcion_gestion=$cliente->descripcion_gestion;

                $clientes->id_tipo_cliente=tbl_tipo_cliente::where('nombre','Cliente')->first()->id_tipo_cliente;
                $clientes->nombres=$cliente->nombres;
                $clientes->fecha_nacimiento=$cliente->fecha_nacimiento;
                $clientes->edad=$cliente->edad;
                $clientes->estado_civil=$cliente->estado_civil;
                $clientes->cod_profesion=$cliente->cod_profesion;

                //$clientes->salario=substr($cliente->salario, 7,0);
                $asalario=str_replace('SUELDO','',$cliente->salario);
                $asalario=trim($asalario);
                $asalario=str_replace(':','',$asalario);
                $asalario=trim($asalario);
                $asalario=str_replace('$','',$asalario);
                $asalario=trim($asalario);

                $clientes->salario=trim($asalario);


                $clientes->cupo=$cliente->cupo;
                $clientes->direccion_visita = $cliente->direccion_visita;
                $clientes->datos_adicionales = $cdatos_acicionales;
                $clientes->tiempo_trabajo=$cliente->tiempo_de_trabajo;

                $fingreso=str_replace('FECHA DE INGRESO A LA EMPRESA','',$cliente->ingreso);
                $fingreso=trim($fingreso);
                $fingreso=str_replace(':','',$fingreso);
                $fingreso=trim($fingreso);
                $fingreso=str_replace('.','',$fingreso);
                $fingreso=trim($fingreso);

                $clientes->fecha_ingreso=trim($fingreso);

                $clientes->fecha_ingreso=is_null($fingreso) ? null : $fingreso;
                //$clientes->fecha_ingreso=is_null($fingreso) ? null : Carbon::parse($fingreso)->format('Y-m-d');
                //$clientes->fecha_salida=is_null($cliente->salida) ? null : Carbon::parse($cliente->salida)->format('Y-m-d');

                $ranking_cliente=tbl_ranking_cliente::get();
                foreach ($ranking_cliente as $k){
                    if ($cliente->salario>=$k->monto_ini && $cliente->salario<=$k->monto_fin){
                        $clientes->id_ranking=$k->id_ranking_cliente;
                    }
                }

                $clientes->telefono=$cliente->telefono;
                $clientes->celular=$cliente->celular;
                $clientes->cargo=$cliente->cargo;
                $clientes->email=$cliente->email;
                /*$clientes->deuda=$cliente->deuda;
                $clientes->producto=$cliente->producto;
                $clientes->datos=$cliente->datos;*/
                $clientes->id_empresa=$empresa->id_empresa;


                if(tbl_clientes::where('id_gestion',$cliente->id_gestion)->count()==0){
                    $clientes->estado=1;
                }

                $clientes->observacion=$cliente->observacion;

                $clientes->fecha_cita=$cliente->fecha_cita;
                $clientes->hora_cita=$cliente->hora_cita;
                $clientes->pais=$cliente->pais;
                try{
                    $clientes->save();
                }catch(\Exception $e){

                    return \Response::json('Ocurrio un error: '.$e->getMessage(), 500);
                }


                //inactivamos al cliente
                //tbl_telefono::where('cedula_cliente',$cedula_cliente)->update(['estado' => 0]);


                /*for ($i=2;$i<=4;$i++){
                    if (!is_null($carga['telefono_'.$i])) {
                        $telefono=tbl_telefono::where('cedula_cliente',$cedula_cliente)->where('telefono',$carga['telefono_'.$i])->first();
                        if (is_null($telefono)){}else{
                        $telefono = new tbl_telefono();
                        $telefono->cedula_cliente = $cedula_cliente;
                        $telefono->telefono = $carga['telefono_.' . $i];
                        $telefono->estado = 1;
                        $telefono->save();
                        }
                    }
                }*/


                if(tbl_provincia::where('nombre', $cliente->provincia)->first()){
                    $provincia=tbl_provincia::where('nombre', $cliente->provincia)->first();
                }else{
                    $provincia=new tbl_provincia();
                    $provincia->nombre=$cliente->provincia;
                    try{
                        $provincia->save();
                    }catch(\Exception $e){

                        return \Response::json('Ocurrio un error: '.$e->getMessage(), 500);
                    }

                }


                if(tbl_ciudad::where('nombre', $cliente->ciudad)->first()){
                    $ciudad=tbl_ciudad::where('nombre', $cliente->ciudad)->first();
                }else{
                    $ciudad=new tbl_ciudad();
                    $ciudad->nombre=$cliente->ciudad;
                    $ciudad->id_provincia=$provincia->id_provincia;
                    try{
                        $ciudad->save();
                    }catch(\Exception $e){

                        return \Response::json('Ocurrio un error: '.$e->getMessage(), 500);
                    }

                }

                if(tbl_direccion::where('cedula_cliente', $cedula_cliente)->first()){
                    $direccion=tbl_direccion::where('cedula_cliente', $cedula_cliente)->first();
                }else{
                    $direccion=new tbl_direccion();
                    $direccion->cedula_cliente=$cedula_cliente;
                }
                $direccion->id_ciudad=$ciudad->id_ciudad;
                $direccion->direccion=$cliente->direccion;
                $direccion->parroquia=$cliente->parroquia;
                try{
                    $direccion->save();
                }catch(\Exception $e){

                    return \Response::json('Ocurrio un error: '.$e->getMessage(), 500);
                }


                if (!is_null($cliente->cedula_conyugue)) {
                    if (tbl_pariente::where('cedula_cliente', $cedula_cliente)->first()) {
                        $pariente = tbl_pariente::where('cedula_cliente', $cedula_cliente)->first();
                    } else {
                        $pariente = new tbl_pariente();
                        $pariente->cedula_cliente = $cedula_cliente;
                    }
                    $pariente->id_parentesco = tbl_parentesco::where('descripcion','CONYUGUE')->first()->id_parentesco;
                    $pariente->cedula = $cliente->cedula_conyugue;
                    $pariente->nombres = $cliente->nombres_conyugue;
                    $pariente->fecha_nacimiento = $cliente->fecha_nacimiento_conyugue;
                    $pariente->edad = $cliente->edad_conyugue;
                    $pariente->salario = $cliente->salario_conyugue;
                    //$pariente->descripcion = $cliente->descripcion_conyugue;
                    $pariente->actividad = $cliente->actividad_conyugue;
                    $pariente->cargo = $cliente->cargo_conyugue;
                    $pariente->empresa = $cliente->empresa_conyugue;
                    $pariente->fecha_ingreso = $cliente->ingreso_conyugue;
                    $pariente->telefono = $cliente->telefono_conyugue;
                    $pariente->telefono2 = $cliente->telefono_conyugue_2;
                    /*$pariente->celular_claro = $cliente->celular_claro_1_conyugue;
                    $pariente->celular_claro2 = $cliente->celular_claro_2_conyugue;
                    $pariente->celular_movistar = $cliente->celular_movistar_conyugue;
                    $pariente->celular_movistar2 = $cliente->id_provincia;*/
                    $pariente->direccion = $cliente->direccion;
                    try{
                        $pariente->save();
                    }catch(\Exception $e){

                        return \Response::json('Ocurrio un error: '.$e->getMessage(), 500);
                    }

                }
                $cedula_cliente='';

                $ingresos++;
            }
            return redirect()->action('Bmi\BmiController@busquedaBmi');
        }
        catch(PDOException $e){echo(sql_error($e));}
        return 'ok';
    }

    public function reportCitasConfirmadas()
    {
        $reportes=null;
        return view('bmi.reportes.reporteCitasConfirmadas', compact('reportes'));
    }

    public function reportCitasConfirmadasPost(Request $request)
    {
        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        $reportes = tbl_citas_historial::whereBetween('fecha_cita', [$fecha_inicio, $fecha_fin])->whereNull('id_gestion')->get();

        foreach ($reportes as $reporte) {
            $gestion=tbl_gestiones::where('id_gestion_cobefec',$reporte->id_gestion_cobefec)->first();
            $reporte->nombres_cliente=$gestion->nombres;
            $reporte->nombres_asesor=$gestion->asesor->nombres;
            $reporte->tipo_descripcion=$gestion->tipo->descripcion;
            $reporte->accion_descripcion=$gestion->accion->descripcion;
            $reporte->fecha_visitat=substr($gestion->fecha_cita_programada,0,-9);
            $reporte->hora_visita=substr($gestion->fecha_cita_programada,10);
            $reporte->observaciones=$gestion->observaciones;
        }

        return compact('reportes');
    }

    public function reportCitasConfirmadasExcel(Request $request)
    {

        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        $reportes = tbl_citas_historial::whereBetween('fecha_cita', [$fecha_inicio, $fecha_fin])->whereNull('id_gestion')->get();

        foreach ($reportes as $reporte) {
            $gestion=tbl_gestiones::where('id_gestion_cobefec',$reporte->id_gestion_cobefec)->first();
            $reporte->nombres_cliente=$reporte->nombres;
            $reporte->nombres_asesor=$gestion->asesor->nombres;
            $reporte->tipo_descripcion=$gestion->tipo->descripcion;
            $reporte->accion_descripcion=$gestion->accion->descripcion;
            $reporte->fecha_visitat=substr($gestion->fecha_cita_programada,0,-9);
            $reporte->hora_visita=substr($gestion->fecha_cita_programada,10);
            $reporte->observaciones=$gestion->observaciones;
        }

        $reportes = json_decode(json_encode($reportes), true);
        \Excel::create('REPORTE-CITAS-CONFIRMADAS-'.date('Y-m-d'), function($excel) use (&$reportes){
            $excel->sheet('Efectividad', function($sheet) use($reportes) {
                $i=1;
                $efectividad=Array();
                $reporte_gestiones=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;
                foreach ($reportes as $k=>$v){
                    $efectividad[$i]['nro']=$i;
                    $efectividad[$i]['usuario_gestion']=$v['usuario_gestion'];
                    $efectividad[$i]['cedula_cliente']=$v['cedula_cliente'];
                    $efectividad[$i]['nombres_cliente']=$v['nombres_cliente'];
                    $efectividad[$i]['nombres_asesor']=$v['nombres_asesor'];
                    $efectividad[$i]['fecha_visitat']=$v['fecha_visitat'];
                    $efectividad[$i]['hora_visita']=$v['hora_visita'];
                    $efectividad[$i]['tipo_descripcion']=$v['tipo_descripcion'];
                    $efectividad[$i]['accion_descripcion']=$v['accion_descripcion'];
                    $efectividad[$i]['observaciones']=$v['observaciones'];
                    $i++;
                };

                $sheet->fromArray($efectividad,null,'A1',true);
                $sheet->row(1, [
                    'N.', 'ASESOR TELEFONICO', 'C.I', 'NOMBRES CLIENTE', 'NOMBRES ASESOR', 'FECHA DE VISITA','HORA DE VISITA','TIPO','ACCION','OBSERVACIONES'
                ]);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#b0e887');
                });
                $sheet->setBorder('A1:I1', 'thin', "000");
            });




        })->export('xlsx');

        return view('bmi.reportes.reporteCitasConfirmadas', compact('reportes'));
    }

    public function reportEfectividad()
    {
        $fecha_inicio ='2018-03-01';
        $fecha_fin =date('Y-m-d');

        $reportes = DB::connection('bmi')->select("SELECT ch.fecha_cita,ch.asesor as cedula_asesor FROM citas_historial as ch 
WHERE date(ch.fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and ch.id_gestion is null
GROUP BY ch.asesor;
");
        $citas_programadas_t=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;
        $citas_programadas_p_t=0;$visitas_realizadas_p_t=0;$porcentaje_cumplimiento_p_t=0;$seguimiento_p_t=0;$cierre_ventas_p_t=0;$porcentaje_eficiencia_p_t=0;$no_efectivas_p_t=0;
        $citas_programadas_m_t=0;$visitas_realizadas_m_t=0;$porcentaje_cumplimiento_m_t=0;$seguimiento_m_t=0;$cierre_ventas_m_t=0;$porcentaje_eficiencia_m_t=0;$no_efectivas_m_t=0;
        $total_citas_agencia=0;$total_citas_propias=0;$total_citas_manuales=0;
        if ($reportes!=null){
            foreach ($reportes as $reporte){
                $reporte->nombre_asesor=tbl_asesores::where('cedula_asesor',$reporte->cedula_asesor)->first()->nombres;
                $reporte->visitas_realizadas=0;
                $reporte->citas_programadas=0;
                $reporte->seguimiento=0;
                $reporte->cierre_de_ventas=0;
                $reporte->no_efectivas=0;
                $reporte->total_citas_agencia=0;
                $reporte->total_citas_propias=0;
                $reporte->total_citas_manuales=0;
                $reporte->total_citas_programadas=0;
                $reporte->total_visitas_realizadas=0;
                $reporte->total_cumplimiento=0;
                $reporte->total_seguimiento=0;
                $reporte->total_cierre_de_ventas=0;
                $reporte->total_eficiencia=0;
                $reporte->total_no_efectivas=0;

                $citas_programadas=DB::connection('bmi')->select("SELECT * from citas where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "'  and id_gestion is null;");
                $citas_programadas=count($citas_programadas);
                $citas_programadas2=DB::connection('bmi')->select("select count(*) as total from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' GROUP BY cedula_cliente ORDER BY cedula_cliente;");
                $citas_programadas=$citas_programadas+count($citas_programadas2);

                if ($citas_programadas>0){
                    $reporte->citas_programadas=$citas_programadas;
                }

                $visita_realizada=DB::connection('bmi')->select("select count(*) as total from gestiones where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_visita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' GROUP BY id_gestion_cobefec;");
                if (count($visita_realizada)>0){
                    $reporte->visitas_realizadas=count($visita_realizada);
                }

                $seguimiento=DB::connection('bmi')->select("select * from gestiones where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion in (select max(id_gestion) from gestiones where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_gestion_cobefec) and id_accion in(2,3,5,6,7,8,9);");

                if (count($seguimiento)>0){
                    $reporte->seguimiento=count($seguimiento);
                }

                $cierre_de_ventas=DB::connection('bmi')->select("select * from gestiones where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion in (select max(id_gestion) from gestiones where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_gestion_cobefec) and id_accion in(1);");
                if (count($cierre_de_ventas)>0){
                    $reporte->cierre_de_ventas=count($cierre_de_ventas);
                }

                $no_efectivas=DB::connection('bmi')->select("select * from gestiones where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion in (select max(id_gestion) from gestiones where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_gestion_cobefec) and id_accion in(4);");
                if (count($no_efectivas)>0){
                    $reporte->no_efectivas=count($no_efectivas);
                }


                if ($reporte->citas_programadas==0){
                    $reporte->porcentaje_cumplimiento=0;
                    $reporte->porcentaje_eficiencia=0;
                }else{
                    $reporte->porcentaje_cumplimiento=round(($reporte->visitas_realizadas*100)/$reporte->citas_programadas,2);
                    $reporte->porcentaje_eficiencia=round(($reporte->cierre_de_ventas*100)/$reporte->citas_programadas,2);
                }
                if ($reporte->seguimiento<0){$reporte->seguimiento=0;}

                $citas_programadas_t=$citas_programadas_t+$reporte->citas_programadas;
                $visitas_realizadas_t=$visitas_realizadas_t+$reporte->visitas_realizadas;
                $seguimiento_t=$seguimiento_t+$reporte->seguimiento;
                $cierre_ventas_t=$cierre_ventas_t+$reporte->cierre_de_ventas;
                $no_efectivas_t=$no_efectivas_t+$reporte->no_efectivas;

                $reporte->total_citas_agencia=$reporte->total_citas_agencia+$reporte->citas_programadas;
                $total_citas_agencia=$total_citas_agencia+$reporte->total_citas_agencia;

                //CITAS PROPIAS
                $reporte->visitas_realizadas_p=0;
                $reporte->citas_programadas_p=0;
                $reporte->seguimiento_p=0;
                $reporte->cierre_de_ventas_p=0;
                $reporte->no_efectivas_p=0;

                $citas_programadas=DB::connection('bmi')->select("SELECT * from citas_propias_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' GROUP BY nombres;");
                $citas_programadas=count($citas_programadas);

                if ($citas_programadas>0){
                    $reporte->citas_programadas_p=$citas_programadas;
                }

                $visita_realizada=DB::connection('bmi')->select("SELECT * from citas_propias_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' GROUP BY nombres;");
                if (count($visita_realizada)>0){
                    $reporte->visitas_realizadas_p=count($visita_realizada);
                }

                $seguimiento=DB::connection('bmi')->select("select * from gestiones_propias where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (2,3,5,6,7,8,9) GROUP BY id_cita_original ORDER BY id_gestion;");
                if (count($seguimiento)>0){
                    $reporte->seguimiento_p=count($seguimiento);
                }

                $cierre_de_ventas=DB::connection('bmi')->select("select * from gestiones_propias where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (1) GROUP BY id_cita_original ORDER BY id_gestion;");
                if (count($cierre_de_ventas)>0){
                    $reporte->cierre_de_ventas_p=count($cierre_de_ventas);
                }

                $no_efectivas=DB::connection('bmi')->select("select * from gestiones_propias where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (4) GROUP BY id_cita_original ORDER BY id_gestion;");
                if (count($no_efectivas)>0){
                    $reporte->no_efectivas_p=count($no_efectivas);
                }


                if ($reporte->citas_programadas_p==0){
                    $reporte->porcentaje_cumplimiento_p=0;
                    $reporte->porcentaje_eficiencia_p=0;
                }else{
                    $reporte->porcentaje_cumplimiento_p=round(($reporte->visitas_realizadas_p*100)/$reporte->citas_programadas_p,2);
                    $reporte->porcentaje_eficiencia_p=round(($reporte->cierre_de_ventas_p*100)/$reporte->citas_programadas_p,2);
                }
                if ($reporte->seguimiento_p<0){$reporte->seguimiento_p=0;}

                $citas_programadas_p_t=$citas_programadas_p_t+$reporte->citas_programadas_p;
                $visitas_realizadas_p_t=$visitas_realizadas_p_t+$reporte->visitas_realizadas_p;
                $seguimiento_p_t=$seguimiento_p_t+$reporte->seguimiento_p;
                $cierre_ventas_p_t=$cierre_ventas_p_t+$reporte->cierre_de_ventas_p;
                $no_efectivas_p_t=$no_efectivas_p_t+$reporte->no_efectivas_p;

                $reporte->total_citas_propias=$reporte->total_citas_propias+$reporte->citas_programadas_p;
                $total_citas_propias=$total_citas_propias+$reporte->total_citas_propias;

                //CITAS MANUALES

                $reporte->visitas_realizadas_m=0;
                $reporte->citas_programadas_m=0;
                $reporte->seguimiento_m=0;
                $reporte->cierre_de_ventas_m=0;
                $reporte->no_efectivas_m=0;

                $citas_programadas=DB::connection('bmi')->select("SELECT * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita_orig is null;");
                $citas_programadas=count($citas_programadas);

                if ($citas_programadas>0){
                    $reporte->citas_programadas_m=$citas_programadas;
                }

                $visita_realizada=DB::connection('bmi')->select("SELECT * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita_orig is null;");
                if (count($visita_realizada)>0){
                    $reporte->visitas_realizadas_m=count($visita_realizada);
                }

                $seguimiento=DB::connection('bmi')->select("select * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita in(select MAX(id_cita) from citas_historial_manuales
where asesor='".$reporte->cedula_asesor."' GROUP BY cedula_cliente) and id_accion in (2,3,5,6,7,8,9) ORDER BY id_cita;");
                if (count($seguimiento)>0){
                    $reporte->seguimiento_m=count($seguimiento);
                }

                $cierre_de_ventas=DB::connection('bmi')->select("select * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita in(select MAX(id_cita) from citas_historial_manuales
where asesor='".$reporte->cedula_asesor."' GROUP BY cedula_cliente) and id_accion in (1) ORDER BY id_cita;");
                if (count($cierre_de_ventas)>0){
                    $reporte->cierre_de_ventas_m=count($cierre_de_ventas);
                }

                $no_efectivas=DB::connection('bmi')->select("select * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita in(select MAX(id_cita) from citas_historial_manuales
where asesor='".$reporte->cedula_asesor."' GROUP BY cedula_cliente) and id_accion in (4) ORDER BY id_cita;");

                if (count($no_efectivas)>0){
                    $reporte->no_efectivas_m=count($no_efectivas);
                }


                if ($reporte->citas_programadas_m==0){
                    $reporte->porcentaje_cumplimiento_m=0;
                    $reporte->porcentaje_eficiencia_m=0;
                }else{
                    $reporte->porcentaje_cumplimiento_m=round(($reporte->visitas_realizadas_m*100)/$reporte->citas_programadas_m,2);
                    $reporte->porcentaje_eficiencia_m=round(($reporte->cierre_de_ventas_m*100)/$reporte->citas_programadas_m,2);
                }
                if ($reporte->seguimiento_m<0){$reporte->seguimiento_m=0;}

                $citas_programadas_m_t=$citas_programadas_m_t+$reporte->citas_programadas_m;
                $visitas_realizadas_m_t=$visitas_realizadas_m_t+$reporte->visitas_realizadas_m;
                $seguimiento_m_t=$seguimiento_m_t+$reporte->seguimiento_m;
                $cierre_ventas_m_t=$cierre_ventas_m_t+$reporte->cierre_de_ventas_m;
                $no_efectivas_m_t=$no_efectivas_m_t+$reporte->no_efectivas_m;

                $reporte->total_citas_manuales=$reporte->total_citas_manuales+$reporte->citas_programadas_m;
                $total_citas_manuales=$total_citas_manuales+$reporte->total_citas_manuales;

                $reporte->total_citas_programadas=$reporte->citas_programadas+$reporte->citas_programadas_p+$reporte->citas_programadas_m;
                $reporte->total_visitas_realizadas=$reporte->visitas_realizadas+$reporte->visitas_realizadas_p+$reporte->visitas_realizadas_m;
                $reporte->total_seguimiento=$reporte->seguimiento+$reporte->seguimiento_p+$reporte->seguimiento_m;
                $reporte->total_cierre_de_ventas=$reporte->cierre_de_ventas+$reporte->cierre_de_ventas_p+$reporte->cierre_de_ventas_p;
                $reporte->total_no_efectivas=$reporte->no_efectivas+$reporte->no_efectivas_p+$reporte->no_efectivas_m;

                if ($reporte->total_citas_programadas==0){
                    $reporte->total_cumplimiento=0;
                    $reporte->total_eficiencia=0;
                }else{
                    $reporte->total_cumplimiento=round(($reporte->total_visitas_realizadas*100)/$reporte->total_citas_programadas,2);
                    $reporte->total_eficiencia=round(($reporte->total_cierre_de_ventas*100)/$reporte->total_citas_programadas,2);
                }
            }

            if ($citas_programadas_t==0){$citas_programadas_t=0;}else{$porcentaje_cumplimiento_t=round(($visitas_realizadas_t*100)/$citas_programadas_t,2);}
            if ($visitas_realizadas_t==0){$porcentaje_eficiencia_t=0;}else{$porcentaje_eficiencia_t=round(($cierre_ventas_t*100)/$visitas_realizadas_t,2);}

            if ($citas_programadas_p_t==0){$citas_programadas_p_t=0;}else{$porcentaje_cumplimiento_p_t=round(($visitas_realizadas_p_t*100)/$citas_programadas_p_t,2);}
            if ($visitas_realizadas_p_t==0){$porcentaje_eficiencia_p_t=0;}else{$porcentaje_eficiencia_p_t=round(($cierre_ventas_p_t*100)/$visitas_realizadas_p_t,2);}

            if ($citas_programadas_m_t==0){$citas_programadas_m_t=0;}else{$porcentaje_cumplimiento_m_t=round(($visitas_realizadas_m_t*100)/$citas_programadas_m_t,2);}
            if ($visitas_realizadas_m_t==0){$porcentaje_eficiencia_m_t=0;}else{$porcentaje_eficiencia_m_t=round(($cierre_ventas_m_t*100)/$visitas_realizadas_m_t,2);}

            //TOTALES
            $totales_citas=$citas_programadas_t+$citas_programadas_p_t+$citas_programadas_m_t;
            $totales_visitas_realizadas=$visitas_realizadas_t+$visitas_realizadas_p_t+$visitas_realizadas_m_t;
            $totales_seguimiento=$seguimiento_t+$seguimiento_p_t+$seguimiento_m_t;
            $totales_cierre_ventas=$cierre_ventas_t+$cierre_ventas_p_t+$cierre_ventas_m_t;
            $totales_no_efectivas=$no_efectivas_t+$no_efectivas_p_t+$no_efectivas_m_t;

            if ($totales_citas==0){$totales_procentaje_cumplimiento=0;}else{$totales_procentaje_cumplimiento=round(($totales_visitas_realizadas*100)/$totales_citas,2);}
            if ($visitas_realizadas_t==0){$totales_procentaje_eficiencia=0;}else{$totales_procentaje_eficiencia=round(($totales_cierre_ventas*100)/$totales_visitas_realizadas,2);}

        }
        else{
            $reportes=Array();
            $reportes[0]['nombre_asesor']='';
            $reportes[0]['citas_programadas']=0;
            $reportes[0]['visitas_realizadas']=0;
            $reportes[0]['porcentaje_cumplimiento']=0;
            $reportes[0]['seguimiento']=0;
            $reportes[0]['seguimiento']=0;
            $reportes[0]['cierre_de_ventas']=0;
            $reportes[0]['porcentaje_eficiencia']=0;
            $reportes[0]['no_efectivas']=0;

            //CITAS PROPIAS
            $reportes[0]['citas_programadas_p']=0;
            $reportes[0]['visitas_realizadas_p']=0;
            $reportes[0]['porcentaje_cumplimiento_p']=0;
            $reportes[0]['seguimiento_p']=0;
            $reportes[0]['seguimiento_p']=0;
            $reportes[0]['cierre_de_ventas_p']=0;
            $reportes[0]['porcentaje_eficiencia_p']=0;
            $reportes[0]['no_efectivas_p']=0;

            //CITAS PROPIAS
            $reportes[0]['citas_programadas_m']=0;
            $reportes[0]['visitas_realizadas_m']=0;
            $reportes[0]['porcentaje_cumplimiento_m']=0;
            $reportes[0]['seguimiento_m']=0;
            $reportes[0]['seguimiento_m']=0;
            $reportes[0]['cierre_de_ventas_m']=0;
            $reportes[0]['porcentaje_eficiencia_m']=0;
            $reportes[0]['no_efectivas_m']=0;

            $reportes[0]['total_citas_agencia']=0;
            $reportes[0]['total_citas_propias']=0;
            $reportes[0]['total_citas_manuales']=0;

            $citas_programadas_t=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;
            $citas_programadas_p_t=0;$visitas_realizadas_p_t=0;$porcentaje_cumplimiento_p_t=0;$seguimiento_p_t=0;$cierre_ventas_p_t=0;$porcentaje_eficiencia_p_t=0;$no_efectivas_p_t=0;
            $citas_programadas_m_t=0;$visitas_realizadas_m_t=0;$porcentaje_cumplimiento_m_t=0;$seguimiento_m_t=0;$cierre_ventas_m_t=0;$porcentaje_eficiencia_m_t=0;$no_efectivas_m_t=0;
        }

        return view('bmi.reportes.reporteEfectividad', compact('reportes','citas_programadas_t','visitas_realizadas_t','seguimiento_t','cierre_ventas_t','no_efectivas_t','porcentaje_cumplimiento_t','porcentaje_eficiencia_t','citas_programadas_p_t','visitas_realizadas_p_t','seguimiento_p_t','cierre_ventas_p_t','no_efectivas_p_t','porcentaje_cumplimiento_p_t','porcentaje_eficiencia_p_t','citas_programadas_m_t','visitas_realizadas_m_t','seguimiento_m_t','cierre_ventas_m_t','no_efectivas_m_t','porcentaje_cumplimiento_m_t','porcentaje_eficiencia_m_t','totales_citas','totales_visitas_realizadas','totales_seguimiento','totales_cierre_ventas','totales_no_efectivas','totales_procentaje_cumplimiento','totales_procentaje_eficiencia'));

    }

    public function reportEfectividadPost(Request $request)
    {
        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        $reportes = DB::connection('bmi')->select("SELECT ch.fecha_cita,ch.asesor as cedula_asesor FROM citas_historial as ch 
WHERE date(ch.fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and ch.id_gestion is null
GROUP BY ch.asesor;
");
        $citas_programadas_t=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;
        $citas_programadas_p_t=0;$visitas_realizadas_p_t=0;$porcentaje_cumplimiento_p_t=0;$seguimiento_p_t=0;$cierre_ventas_p_t=0;$porcentaje_eficiencia_p_t=0;$no_efectivas_p_t=0;
        $citas_programadas_m_t=0;$visitas_realizadas_m_t=0;$porcentaje_cumplimiento_m_t=0;$seguimiento_m_t=0;$cierre_ventas_m_t=0;$porcentaje_eficiencia_m_t=0;$no_efectivas_m_t=0;
        $total_citas_agencia=0;$total_citas_propias=0;$total_citas_manuales=0;
        if ($reportes!=null){
            foreach ($reportes as $reporte){
                $reporte->nombre_asesor=tbl_asesores::where('cedula_asesor',$reporte->cedula_asesor)->first()->nombres;

                $reporte->visitas_realizadas=0;
                $reporte->citas_programadas=0;
                $reporte->seguimiento=0;
                $reporte->cierre_de_ventas=0;
                $reporte->no_efectivas=0;
                $reporte->total_citas_agencia=0;
                $reporte->total_citas_propias=0;
                $reporte->total_citas_manuales=0;

                $reporte->total_citas_programadas=0;
                $reporte->total_visitas_realizadas=0;
                $reporte->total_cumplimiento=0;
                $reporte->total_seguimiento=0;
                $reporte->total_cierre_de_ventas=0;
                $reporte->total_eficiencia=0;
                $reporte->total_no_efectivas=0;

                //SE TOMA EN CUENTA LAS FECHA ORIGINALES DE LAS CITAS QUE LA AGENCIA ASIGNO
                $citas_programadas=DB::connection('bmi')->select("SELECT * from citas where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "'  and id_gestion is null;");
                $citas_programadas=count($citas_programadas);
                $citas_programadas2=DB::connection('bmi')->select("SELECT * from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "'  and id_gestion is null;");
                $citas_programadas=$citas_programadas+count($citas_programadas2);

                if ($citas_programadas>0){
                    $reporte->citas_programadas=$citas_programadas;
                }

                $visita_realizada=DB::connection('bmi')->select("SELECT * from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion is null;");
                if (count($visita_realizada)>0){
                    $reporte->visitas_realizadas=count($visita_realizada);
                }

                $seguimiento=DB::connection('bmi')->select("select max(id_gestion) from gestiones where cedula_asesor='".$reporte->cedula_asesor."' 
and id_cita in (SELECT id_cita_orig from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion is null ORDER BY id_cita_orig) 
and id_accion in(2,3,5,6,7,8,9)
GROUP BY id_gestion_cobefec ORDER BY id_cita;");

                if (count($seguimiento)>0){
                    $reporte->seguimiento=count($seguimiento);
                }

                $cierre_de_ventas=DB::connection('bmi')->select("select max(id_gestion) from gestiones where cedula_asesor='".$reporte->cedula_asesor."' 
and id_cita in (SELECT id_cita_orig from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion is null ORDER BY id_cita_orig) 
and id_accion =1
GROUP BY id_gestion_cobefec ORDER BY id_cita;");
                if (count($cierre_de_ventas)>0){
                    $reporte->cierre_de_ventas=count($cierre_de_ventas);
                }

                $no_efectivas=DB::connection('bmi')->select("select max(id_gestion) from gestiones where cedula_asesor='".$reporte->cedula_asesor."' 
and id_cita in (SELECT id_cita_orig from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion is null ORDER BY id_cita_orig) 
and id_accion =4
GROUP BY id_gestion_cobefec ORDER BY id_cita;");
                if (count($no_efectivas)>0){
                    $reporte->no_efectivas=count($no_efectivas);
                }



                if ($reporte->citas_programadas==0){
                    $reporte->porcentaje_cumplimiento=0;
                    $reporte->porcentaje_eficiencia=0;
                }else{
                    $reporte->porcentaje_cumplimiento=round(($reporte->visitas_realizadas*100)/$reporte->citas_programadas,2);
                    $reporte->porcentaje_eficiencia=round(($reporte->cierre_de_ventas*100)/$reporte->citas_programadas,2);
                }
                if ($reporte->seguimiento<0){$reporte->seguimiento=0;}

                $citas_programadas_t=$citas_programadas_t+$reporte->citas_programadas;
                $visitas_realizadas_t=$visitas_realizadas_t+$reporte->visitas_realizadas;
                $seguimiento_t=$seguimiento_t+$reporte->seguimiento;
                $cierre_ventas_t=$cierre_ventas_t+$reporte->cierre_de_ventas;
                $no_efectivas_t=$no_efectivas_t+$reporte->no_efectivas;

                $reporte->total_citas_agencia=$reporte->total_citas_agencia+$reporte->citas_programadas;
                $total_citas_agencia=$total_citas_agencia+$reporte->total_citas_agencia;

                //CITAS PROPIAS

                $reporte->visitas_realizadas_p=0;
                $reporte->citas_programadas_p=0;
                $reporte->seguimiento_p=0;
                $reporte->cierre_de_ventas_p=0;
                $reporte->no_efectivas_p=0;

                $citas_programadas=DB::connection('bmi')->select("SELECT * from citas_propias_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' GROUP BY nombres;");
                $citas_programadas=count($citas_programadas);

                if ($citas_programadas>0){
                    $reporte->citas_programadas_p=$citas_programadas;
                }

                $visita_realizada=DB::connection('bmi')->select("SELECT * from citas_propias_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' GROUP BY nombres;");
                if (count($visita_realizada)>0){
                    $reporte->visitas_realizadas_p=count($visita_realizada);
                }

                $seguimiento=DB::connection('bmi')->select("select * from gestiones_propias where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (2,3,5,6,7,8,9) GROUP BY id_cita_original ORDER BY id_gestion;");
                if (count($seguimiento)>0){
                    $reporte->seguimiento_p=count($seguimiento);
                }

                $cierre_de_ventas=DB::connection('bmi')->select("select * from gestiones_propias where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (1) GROUP BY id_cita_original ORDER BY id_gestion;");
                if (count($cierre_de_ventas)>0){
                    $reporte->cierre_de_ventas_p=count($cierre_de_ventas);
                }

                $no_efectivas=DB::connection('bmi')->select("select * from gestiones_propias where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (4) GROUP BY id_cita_original ORDER BY id_gestion;");
                if (count($no_efectivas)>0){
                    $reporte->no_efectivas_p=count($no_efectivas);
                }


                if ($reporte->citas_programadas_p==0){
                    $reporte->porcentaje_cumplimiento_p=0;
                    $reporte->porcentaje_eficiencia_p=0;
                }else{
                    $reporte->porcentaje_cumplimiento_p=round(($reporte->visitas_realizadas_p*100)/$reporte->citas_programadas_p,2);
                    $reporte->porcentaje_eficiencia_p=round(($reporte->cierre_de_ventas_p*100)/$reporte->citas_programadas_p,2);
                }
                if ($reporte->seguimiento_p<0){$reporte->seguimiento_p=0;}

                $citas_programadas_p_t=$citas_programadas_p_t+$reporte->citas_programadas_p;
                $visitas_realizadas_p_t=$visitas_realizadas_p_t+$reporte->visitas_realizadas_p;
                $seguimiento_p_t=$seguimiento_p_t+$reporte->seguimiento_p;
                $cierre_ventas_p_t=$cierre_ventas_p_t+$reporte->cierre_de_ventas_p;
                $no_efectivas_p_t=$no_efectivas_p_t+$reporte->no_efectivas_p;

                $reporte->total_citas_propias=$reporte->total_citas_propias+$reporte->citas_programadas_p;
                $total_citas_propias=$total_citas_propias+$reporte->total_citas_propias;

                //CITAS MANUALES

                $reporte->visitas_realizadas_m=0;
                $reporte->citas_programadas_m=0;
                $reporte->seguimiento_m=0;
                $reporte->cierre_de_ventas_m=0;
                $reporte->no_efectivas_m=0;

                $citas_programadas=DB::connection('bmi')->select("SELECT * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita_orig is null;");
                $citas_programadas=count($citas_programadas);

                if ($citas_programadas>0){
                    $reporte->citas_programadas_m=$citas_programadas;
                }

                $visita_realizada=DB::connection('bmi')->select("SELECT * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita_orig is null;");
                if (count($visita_realizada)>0){
                    $reporte->visitas_realizadas_m=count($visita_realizada);
                }

                $seguimiento=DB::connection('bmi')->select("select * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita in(select MAX(id_cita) from citas_historial_manuales
where asesor='".$reporte->cedula_asesor."' GROUP BY cedula_cliente) and id_accion in (2,3,5,6,7,8,9) ORDER BY id_cita;");
                if (count($seguimiento)>0){
                    $reporte->seguimiento_m=count($seguimiento);
                }

                $cierre_de_ventas=DB::connection('bmi')->select("select * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita in(select MAX(id_cita) from citas_historial_manuales
where asesor='".$reporte->cedula_asesor."' GROUP BY cedula_cliente) and id_accion in (1) ORDER BY id_cita;");
                if (count($cierre_de_ventas)>0){
                    $reporte->cierre_de_ventas_m=count($cierre_de_ventas);
                }

                $no_efectivas=DB::connection('bmi')->select("select * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita in(select MAX(id_cita) from citas_historial_manuales
where asesor='".$reporte->cedula_asesor."' GROUP BY cedula_cliente) and id_accion in (4) ORDER BY id_cita;");

                if (count($no_efectivas)>0){
                    $reporte->no_efectivas_m=count($no_efectivas);
                }


                if ($reporte->citas_programadas_m==0){
                    $reporte->porcentaje_cumplimiento_m=0;
                    $reporte->porcentaje_eficiencia_m=0;
                }else{
                    $reporte->porcentaje_cumplimiento_m=round(($reporte->visitas_realizadas_m*100)/$reporte->citas_programadas_m,2);
                    $reporte->porcentaje_eficiencia_m=round(($reporte->cierre_de_ventas_m*100)/$reporte->citas_programadas_m,2);
                }
                if ($reporte->seguimiento_m<0){$reporte->seguimiento_m=0;}

                $citas_programadas_m_t=$citas_programadas_m_t+$reporte->citas_programadas_m;
                $visitas_realizadas_m_t=$visitas_realizadas_m_t+$reporte->visitas_realizadas_m;
                $seguimiento_m_t=$seguimiento_m_t+$reporte->seguimiento_m;
                $cierre_ventas_m_t=$cierre_ventas_m_t+$reporte->cierre_de_ventas_m;
                $no_efectivas_m_t=$no_efectivas_m_t+$reporte->no_efectivas_m;

                $reporte->total_citas_manuales=$reporte->total_citas_manuales+$reporte->citas_programadas_m;
                $total_citas_manuales=$total_citas_manuales+$reporte->total_citas_manuales;

                $reporte->total_citas_programadas=$reporte->citas_programadas+$reporte->citas_programadas_p+$reporte->citas_programadas_m;
                $reporte->total_visitas_realizadas=$reporte->visitas_realizadas+$reporte->visitas_realizadas_p+$reporte->visitas_realizadas_m;
                $reporte->total_seguimiento=$reporte->seguimiento+$reporte->seguimiento_p+$reporte->seguimiento_m;
                $reporte->total_cierre_de_ventas=$reporte->cierre_de_ventas+$reporte->cierre_de_ventas_p+$reporte->cierre_de_ventas_p;
                $reporte->total_no_efectivas=$reporte->no_efectivas+$reporte->no_efectivas_p+$reporte->no_efectivas_m;
                if ($reporte->total_citas_programadas==0){
                    $reporte->total_cumplimiento=0;
                    $reporte->total_eficiencia=0;
                }else{
                    $reporte->total_cumplimiento=round(($reporte->total_visitas_realizadas*100)/$reporte->total_citas_programadas,2);
                    $reporte->total_eficiencia=round(($reporte->total_cierre_de_ventas*100)/$reporte->total_citas_programadas,2);
                }
            }

            if ($citas_programadas_t==0){$citas_programadas_t=0;}else{$porcentaje_cumplimiento_t=round(($visitas_realizadas_t*100)/$citas_programadas_t,2);}
            if ($visitas_realizadas_t==0){$porcentaje_eficiencia_t=0;}else{$porcentaje_eficiencia_t=round(($cierre_ventas_t*100)/$visitas_realizadas_t,2);}

            if ($citas_programadas_p_t==0){$citas_programadas_p_t=0;}else{$porcentaje_cumplimiento_p_t=round(($visitas_realizadas_p_t*100)/$citas_programadas_p_t,2);}
            if ($visitas_realizadas_p_t==0){$porcentaje_eficiencia_p_t=0;}else{$porcentaje_eficiencia_p_t=round(($cierre_ventas_p_t*100)/$visitas_realizadas_p_t,2);}

            if ($citas_programadas_m_t==0){$citas_programadas_m_t=0;}else{$porcentaje_cumplimiento_m_t=round(($visitas_realizadas_m_t*100)/$citas_programadas_m_t,2);}
            if ($visitas_realizadas_m_t==0){$porcentaje_eficiencia_m_t=0;}else{$porcentaje_eficiencia_m_t=round(($cierre_ventas_m_t*100)/$visitas_realizadas_m_t,2);}

            //TOTALES
            $totales_citas=$citas_programadas_t+$citas_programadas_p_t+$citas_programadas_m_t;
            $totales_visitas_realizadas=$visitas_realizadas_t+$visitas_realizadas_p_t+$visitas_realizadas_m_t;
            $totales_seguimiento=$seguimiento_t+$seguimiento_p_t+$seguimiento_m_t;
            $totales_cierre_ventas=$cierre_ventas_t+$cierre_ventas_p_t+$cierre_ventas_m_t;
            $totales_no_efectivas=$no_efectivas_t+$no_efectivas_p_t+$no_efectivas_m_t;

            if ($totales_citas==0){$totales_procentaje_cumplimiento=0;}else{$totales_procentaje_cumplimiento=round(($totales_visitas_realizadas*100)/$totales_citas,2);}
            if ($visitas_realizadas_t==0){$totales_procentaje_eficiencia=0;}else{$totales_procentaje_eficiencia=round(($totales_cierre_ventas*100)/$totales_visitas_realizadas,2);}
        }
        else{
            $reportes=Array();
            $reportes[0]['nombre_asesor']='';
            $reportes[0]['citas_programadas']=0;
            $reportes[0]['visitas_realizadas']=0;
            $reportes[0]['porcentaje_cumplimiento']=0;
            $reportes[0]['seguimiento']=0;
            $reportes[0]['seguimiento']=0;
            $reportes[0]['cierre_de_ventas']=0;
            $reportes[0]['porcentaje_eficiencia']=0;
            $reportes[0]['no_efectivas']=0;

            //CITAS PROPIAS
            $reportes[0]['citas_programadas_p']=0;
            $reportes[0]['visitas_realizadas_p']=0;
            $reportes[0]['porcentaje_cumplimiento_p']=0;
            $reportes[0]['seguimiento_p']=0;
            $reportes[0]['seguimiento_p']=0;
            $reportes[0]['cierre_de_ventas_p']=0;
            $reportes[0]['porcentaje_eficiencia_p']=0;
            $reportes[0]['no_efectivas_p']=0;

            //CITAS PROPIAS
            $reportes[0]['citas_programadas_m']=0;
            $reportes[0]['visitas_realizadas_m']=0;
            $reportes[0]['porcentaje_cumplimiento_m']=0;
            $reportes[0]['seguimiento_m']=0;
            $reportes[0]['seguimiento_m']=0;
            $reportes[0]['cierre_de_ventas_m']=0;
            $reportes[0]['porcentaje_eficiencia_m']=0;
            $reportes[0]['no_efectivas_m']=0;

            $reportes[0]['total_citas_agencia']=0;
            $reportes[0]['total_citas_propias']=0;
            $reportes[0]['total_citas_manuales']=0;

            $citas_programadas_t=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;
            $citas_programadas_p_t=0;$visitas_realizadas_p_t=0;$porcentaje_cumplimiento_p_t=0;$seguimiento_p_t=0;$cierre_ventas_p_t=0;$porcentaje_eficiencia_p_t=0;$no_efectivas_p_t=0;
            $citas_programadas_m_t=0;$visitas_realizadas_m_t=0;$porcentaje_cumplimiento_m_t=0;$seguimiento_m_t=0;$cierre_ventas_m_t=0;$porcentaje_eficiencia_m_t=0;$no_efectivas_m_t=0;
        }

        return compact('reportes','citas_programadas_t','visitas_realizadas_t','seguimiento_t','cierre_ventas_t','no_efectivas_t','porcentaje_cumplimiento_t','porcentaje_eficiencia_t','citas_programadas_p_t','visitas_realizadas_p_t','seguimiento_p_t','cierre_ventas_p_t','no_efectivas_p_t','porcentaje_cumplimiento_p_t','porcentaje_eficiencia_p_t','citas_programadas_m_t','visitas_realizadas_m_t','seguimiento_m_t','cierre_ventas_m_t','no_efectivas_m_t','porcentaje_cumplimiento_m_t','porcentaje_eficiencia_m_t','totales_citas','totales_visitas_realizadas','totales_seguimiento','totales_cierre_ventas','totales_no_efectivas','totales_procentaje_cumplimiento','totales_procentaje_eficiencia');



    }

    public function reportEfectividadExcel(Request $request)
    {
        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        if ($request->hist==1){
            $fecha_inicio ='2018-03-01';
            $fecha_fin =date('Y-m-d');
        }

        $reportes = DB::connection('bmi')->select("SELECT ch.fecha_cita,ch.asesor as cedula_asesor FROM citas_historial as ch 
WHERE date(ch.fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and ch.id_gestion is null
GROUP BY ch.asesor;
");
        $citas_programadas_t=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;
        $citas_programadas_p_t=0;$visitas_realizadas_p_t=0;$porcentaje_cumplimiento_p_t=0;$seguimiento_p_t=0;$cierre_ventas_p_t=0;$porcentaje_eficiencia_p_t=0;$no_efectivas_p_t=0;
        $citas_programadas_m_t=0;$visitas_realizadas_m_t=0;$porcentaje_cumplimiento_m_t=0;$seguimiento_m_t=0;$cierre_ventas_m_t=0;$porcentaje_eficiencia_m_t=0;$no_efectivas_m_t=0;
        $total_citas_agencia=0;$total_citas_propias=0;$total_citas_manuales=0;
        if ($reportes!=null){
            foreach ($reportes as $reporte){
                $reporte->nombre_asesor=tbl_asesores::where('cedula_asesor',$reporte->cedula_asesor)->first()->nombres;

                $reporte->visitas_realizadas=0;
                $reporte->citas_programadas=0;
                $reporte->seguimiento=0;
                $reporte->cierre_de_ventas=0;
                $reporte->no_efectivas=0;
                $reporte->total_citas_agencia=0;
                $reporte->total_citas_propias=0;
                $reporte->total_citas_manuales=0;

                $reporte->total_citas_programadas=0;
                $reporte->total_visitas_realizadas=0;
                $reporte->total_cumplimiento=0;
                $reporte->total_seguimiento=0;
                $reporte->total_cierre_de_ventas=0;
                $reporte->total_eficiencia=0;
                $reporte->total_no_efectivas=0;

                //SE TOMA EN CUENTA LAS FECHA ORIGINALES DE LAS CITAS QUE LA AGENCIA ASIGNO
                $citas_programadas=DB::connection('bmi')->select("SELECT * from citas where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "'  and id_gestion is null;");
                $citas_programadas=count($citas_programadas);
                $citas_programadas2=DB::connection('bmi')->select("SELECT * from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "'  and id_gestion is null;");
                $citas_programadas=$citas_programadas+count($citas_programadas2);

                if ($citas_programadas>0){
                    $reporte->citas_programadas=$citas_programadas;
                }

                $visita_realizada=DB::connection('bmi')->select("SELECT * from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion is null;");
                if (count($visita_realizada)>0){
                    $reporte->visitas_realizadas=count($visita_realizada);
                }

                $seguimiento=DB::connection('bmi')->select("select max(id_gestion) from gestiones where cedula_asesor='".$reporte->cedula_asesor."' 
and id_cita in (SELECT id_cita_orig from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion is null ORDER BY id_cita_orig) 
and id_accion in(2,3,5,6,7,8,9)
GROUP BY id_gestion_cobefec ORDER BY id_cita;");

                if (count($seguimiento)>0){
                    $reporte->seguimiento=count($seguimiento);
                }

                $cierre_de_ventas=DB::connection('bmi')->select("select max(id_gestion) from gestiones where cedula_asesor='".$reporte->cedula_asesor."' 
and id_cita in (SELECT id_cita_orig from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion is null ORDER BY id_cita_orig) 
and id_accion =1
GROUP BY id_gestion_cobefec ORDER BY id_cita;");
                if (count($cierre_de_ventas)>0){
                    $reporte->cierre_de_ventas=count($cierre_de_ventas);
                }

                $no_efectivas=DB::connection('bmi')->select("select max(id_gestion) from gestiones where cedula_asesor='".$reporte->cedula_asesor."' 
and id_cita in (SELECT id_cita_orig from citas_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_gestion is null ORDER BY id_cita_orig) 
and id_accion =4
GROUP BY id_gestion_cobefec ORDER BY id_cita;");
                if (count($no_efectivas)>0){
                    $reporte->no_efectivas=count($no_efectivas);
                }


                if ($reporte->citas_programadas==0){
                    $reporte->porcentaje_cumplimiento=0;
                    $reporte->porcentaje_eficiencia=0;
                }else{
                    $reporte->porcentaje_cumplimiento=round(($reporte->visitas_realizadas*100)/$reporte->citas_programadas,2);
                    $reporte->porcentaje_eficiencia=round(($reporte->cierre_de_ventas*100)/$reporte->citas_programadas,2);
                }
                if ($reporte->seguimiento<0){$reporte->seguimiento=0;}

                $citas_programadas_t=$citas_programadas_t+$reporte->citas_programadas;
                $visitas_realizadas_t=$visitas_realizadas_t+$reporte->visitas_realizadas;
                $seguimiento_t=$seguimiento_t+$reporte->seguimiento;
                $cierre_ventas_t=$cierre_ventas_t+$reporte->cierre_de_ventas;
                $no_efectivas_t=$no_efectivas_t+$reporte->no_efectivas;

                $reporte->total_citas_agencia=$reporte->total_citas_agencia+$reporte->citas_programadas;
                $total_citas_agencia=$total_citas_agencia+$reporte->total_citas_agencia;

                //CITAS PROPIAS

                $reporte->visitas_realizadas_p=0;
                $reporte->citas_programadas_p=0;
                $reporte->seguimiento_p=0;
                $reporte->cierre_de_ventas_p=0;
                $reporte->no_efectivas_p=0;

                $citas_programadas=DB::connection('bmi')->select("SELECT * from citas_propias_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' GROUP BY nombres;");
                $citas_programadas=count($citas_programadas);

                if ($citas_programadas>0){
                    $reporte->citas_programadas_p=$citas_programadas;
                }

                $visita_realizada=DB::connection('bmi')->select("SELECT * from citas_propias_historial where asesor='".$reporte->cedula_asesor."' and date(fecha_cita) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' GROUP BY nombres;");
                if (count($visita_realizada)>0){
                    $reporte->visitas_realizadas_p=count($visita_realizada);
                }

                $seguimiento=DB::connection('bmi')->select("select * from gestiones_propias where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (2,3,5,6,7,8,9) GROUP BY id_cita_original ORDER BY id_gestion;");
                if (count($seguimiento)>0){
                    $reporte->seguimiento_p=count($seguimiento);
                }

                $cierre_de_ventas=DB::connection('bmi')->select("select * from gestiones_propias where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (1) GROUP BY id_cita_original ORDER BY id_gestion;");
                if (count($cierre_de_ventas)>0){
                    $reporte->cierre_de_ventas_p=count($cierre_de_ventas);
                }

                $no_efectivas=DB::connection('bmi')->select("select * from gestiones_propias where cedula_asesor='".$reporte->cedula_asesor."' and date(fecha_cita_programada) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and id_cita_original in(select MAX(id_cita_original) from gestiones_propias
where cedula_asesor='".$reporte->cedula_asesor."' GROUP BY id_cita_original) and id_accion in (4) GROUP BY id_cita_original ORDER BY id_gestion;");
                if (count($no_efectivas)>0){
                    $reporte->no_efectivas_p=count($no_efectivas);
                }


                if ($reporte->citas_programadas_p==0){
                    $reporte->porcentaje_cumplimiento_p=0;
                    $reporte->porcentaje_eficiencia_p=0;
                }else{
                    $reporte->porcentaje_cumplimiento_p=round(($reporte->visitas_realizadas_p*100)/$reporte->citas_programadas_p,2);
                    $reporte->porcentaje_eficiencia_p=round(($reporte->cierre_de_ventas_p*100)/$reporte->citas_programadas_p,2);
                }
                if ($reporte->seguimiento_p<0){$reporte->seguimiento_p=0;}

                $citas_programadas_p_t=$citas_programadas_p_t+$reporte->citas_programadas_p;
                $visitas_realizadas_p_t=$visitas_realizadas_p_t+$reporte->visitas_realizadas_p;
                $seguimiento_p_t=$seguimiento_p_t+$reporte->seguimiento_p;
                $cierre_ventas_p_t=$cierre_ventas_p_t+$reporte->cierre_de_ventas_p;
                $no_efectivas_p_t=$no_efectivas_p_t+$reporte->no_efectivas_p;

                $reporte->total_citas_propias=$reporte->total_citas_propias+$reporte->citas_programadas_p;
                $total_citas_propias=$total_citas_propias+$reporte->total_citas_propias;

                //CITAS MANUALES

                $reporte->visitas_realizadas_m=0;
                $reporte->citas_programadas_m=0;
                $reporte->seguimiento_m=0;
                $reporte->cierre_de_ventas_m=0;
                $reporte->no_efectivas_m=0;

                $citas_programadas=DB::connection('bmi')->select("SELECT * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita_orig is null;");
                $citas_programadas=count($citas_programadas);

                if ($citas_programadas>0){
                    $reporte->citas_programadas_m=$citas_programadas;
                }

                $visita_realizada=DB::connection('bmi')->select("SELECT * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita_orig is null;");
                if (count($visita_realizada)>0){
                    $reporte->visitas_realizadas_m=count($visita_realizada);
                }

                $seguimiento=DB::connection('bmi')->select("select * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita in(select MAX(id_cita) from citas_historial_manuales
where asesor='".$reporte->cedula_asesor."' GROUP BY cedula_cliente) and id_accion in (2,3,5,6,7,8,9) ORDER BY id_cita;");
                if (count($seguimiento)>0){
                    $reporte->seguimiento_m=count($seguimiento);
                }

                $cierre_de_ventas=DB::connection('bmi')->select("select * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita in(select MAX(id_cita) from citas_historial_manuales
where asesor='".$reporte->cedula_asesor."' GROUP BY cedula_cliente) and id_accion in (1) ORDER BY id_cita;");
                if (count($cierre_de_ventas)>0){
                    $reporte->cierre_de_ventas_m=count($cierre_de_ventas);
                }

                $no_efectivas=DB::connection('bmi')->select("select * from citas_historial_manuales where asesor='".$reporte->cedula_asesor."' and id_cita in(select MAX(id_cita) from citas_historial_manuales
where asesor='".$reporte->cedula_asesor."' GROUP BY cedula_cliente) and id_accion in (4) ORDER BY id_cita;");

                if (count($no_efectivas)>0){
                    $reporte->no_efectivas_m=count($no_efectivas);
                }


                if ($reporte->citas_programadas_m==0){
                    $reporte->porcentaje_cumplimiento_m=0;
                    $reporte->porcentaje_eficiencia_m=0;
                }else{
                    $reporte->porcentaje_cumplimiento_m=round(($reporte->visitas_realizadas_m*100)/$reporte->citas_programadas_m,2);
                    $reporte->porcentaje_eficiencia_m=round(($reporte->cierre_de_ventas_m*100)/$reporte->citas_programadas_m,2);
                }
                if ($reporte->seguimiento_m<0){$reporte->seguimiento_m=0;}

                $citas_programadas_m_t=$citas_programadas_m_t+$reporte->citas_programadas_m;
                $visitas_realizadas_m_t=$visitas_realizadas_m_t+$reporte->visitas_realizadas_m;
                $seguimiento_m_t=$seguimiento_m_t+$reporte->seguimiento_m;
                $cierre_ventas_m_t=$cierre_ventas_m_t+$reporte->cierre_de_ventas_m;
                $no_efectivas_m_t=$no_efectivas_m_t+$reporte->no_efectivas_m;

                $reporte->total_citas_manuales=$reporte->total_citas_manuales+$reporte->citas_programadas_m;
                $total_citas_manuales=$total_citas_manuales+$reporte->total_citas_manuales;

                $reporte->total_citas_programadas=$reporte->citas_programadas+$reporte->citas_programadas_p+$reporte->citas_programadas_m;
                $reporte->total_visitas_realizadas=$reporte->visitas_realizadas+$reporte->visitas_realizadas_p+$reporte->visitas_realizadas_m;
                $reporte->total_seguimiento=$reporte->seguimiento+$reporte->seguimiento_p+$reporte->seguimiento_m;
                $reporte->total_cierre_de_ventas=$reporte->cierre_de_ventas+$reporte->cierre_de_ventas_p+$reporte->cierre_de_ventas_p;
                $reporte->total_no_efectivas=$reporte->no_efectivas+$reporte->no_efectivas_p+$reporte->no_efectivas_m;
                if ($reporte->total_citas_programadas==0){
                    $reporte->total_cumplimiento=0;
                    $reporte->total_eficiencia=0;
                }else{
                    $reporte->total_cumplimiento=round(($reporte->total_visitas_realizadas*100)/$reporte->total_citas_programadas,2);
                    $reporte->total_eficiencia=round(($reporte->total_cierre_de_ventas*100)/$reporte->total_citas_programadas,2);
                }
            }

            if ($citas_programadas_t==0){$citas_programadas_t=0;}else{$porcentaje_cumplimiento_t=round(($visitas_realizadas_t*100)/$citas_programadas_t,2);}
            if ($visitas_realizadas_t==0){$porcentaje_eficiencia_t=0;}else{$porcentaje_eficiencia_t=round(($cierre_ventas_t*100)/$visitas_realizadas_t,2);}

            if ($citas_programadas_p_t==0){$citas_programadas_p_t=0;}else{$porcentaje_cumplimiento_p_t=round(($visitas_realizadas_p_t*100)/$citas_programadas_p_t,2);}
            if ($visitas_realizadas_p_t==0){$porcentaje_eficiencia_p_t=0;}else{$porcentaje_eficiencia_p_t=round(($cierre_ventas_p_t*100)/$visitas_realizadas_p_t,2);}

            if ($citas_programadas_m_t==0){$citas_programadas_m_t=0;}else{$porcentaje_cumplimiento_m_t=round(($visitas_realizadas_m_t*100)/$citas_programadas_m_t,2);}
            if ($visitas_realizadas_m_t==0){$porcentaje_eficiencia_m_t=0;}else{$porcentaje_eficiencia_m_t=round(($cierre_ventas_m_t*100)/$visitas_realizadas_m_t,2);}

            //TOTALES
            $totales_citas=$citas_programadas_t+$citas_programadas_p_t+$citas_programadas_m_t;
            $totales_visitas_realizadas=$visitas_realizadas_t+$visitas_realizadas_p_t+$visitas_realizadas_m_t;
            $totales_seguimiento=$seguimiento_t+$seguimiento_p_t+$seguimiento_m_t;
            $totales_cierre_ventas=$cierre_ventas_t+$cierre_ventas_p_t+$cierre_ventas_m_t;
            $totales_no_efectivas=$no_efectivas_t+$no_efectivas_p_t+$no_efectivas_m_t;

            if ($totales_citas==0){$totales_procentaje_cumplimiento=0;}else{$totales_procentaje_cumplimiento=round(($totales_visitas_realizadas*100)/$totales_citas,2);}
            if ($visitas_realizadas_t==0){$totales_procentaje_eficiencia=0;}else{$totales_procentaje_eficiencia=round(($totales_cierre_ventas*100)/$totales_visitas_realizadas,2);}
        }
        else{
            $reportes=Array();
            $reportes[0]['nombre_asesor']='';
            $reportes[0]['citas_programadas']=0;
            $reportes[0]['visitas_realizadas']=0;
            $reportes[0]['porcentaje_cumplimiento']=0;
            $reportes[0]['seguimiento']=0;
            $reportes[0]['seguimiento']=0;
            $reportes[0]['cierre_de_ventas']=0;
            $reportes[0]['porcentaje_eficiencia']=0;
            $reportes[0]['no_efectivas']=0;

            //CITAS PROPIAS
            $reportes[0]['citas_programadas_p']=0;
            $reportes[0]['visitas_realizadas_p']=0;
            $reportes[0]['porcentaje_cumplimiento_p']=0;
            $reportes[0]['seguimiento_p']=0;
            $reportes[0]['seguimiento_p']=0;
            $reportes[0]['cierre_de_ventas_p']=0;
            $reportes[0]['porcentaje_eficiencia_p']=0;
            $reportes[0]['no_efectivas_p']=0;

            //CITAS PROPIAS
            $reportes[0]['citas_programadas_m']=0;
            $reportes[0]['visitas_realizadas_m']=0;
            $reportes[0]['porcentaje_cumplimiento_m']=0;
            $reportes[0]['seguimiento_m']=0;
            $reportes[0]['seguimiento_m']=0;
            $reportes[0]['cierre_de_ventas_m']=0;
            $reportes[0]['porcentaje_eficiencia_m']=0;
            $reportes[0]['no_efectivas_m']=0;

            $reportes[0]['total_citas_agencia']=0;
            $reportes[0]['total_citas_propias']=0;
            $reportes[0]['total_citas_manuales']=0;

            $citas_programadas_t=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;
            $citas_programadas_p_t=0;$visitas_realizadas_p_t=0;$porcentaje_cumplimiento_p_t=0;$seguimiento_p_t=0;$cierre_ventas_p_t=0;$porcentaje_eficiencia_p_t=0;$no_efectivas_p_t=0;
            $citas_programadas_m_t=0;$visitas_realizadas_m_t=0;$porcentaje_cumplimiento_m_t=0;$seguimiento_m_t=0;$cierre_ventas_m_t=0;$porcentaje_eficiencia_m_t=0;$no_efectivas_m_t=0;
        }


        $reportes = json_decode(json_encode($reportes), true);
        \Excel::create('REPORTE-EFECTIVIDAD-'.date('Y-m-d'), function($excel) use (&$reportes){
            $excel->sheet('Efectividad', function($sheet) use($reportes) {
                $i=0;
                $efectividad=Array();
                $citas_programadas_t=0;
                $porcentaje_eficiencia_t=0;
                $citas_programadas_t=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;
                $citas_programadas_p_t=0;$visitas_realizadas_p_t=0;$porcentaje_cumplimiento_p_t=0;$seguimiento_p_t=0;$cierre_ventas_p_t=0;$porcentaje_eficiencia_p_t=0;$no_efectivas_p_t=0;
                $citas_programadas_m_t=0;$visitas_realizadas_m_t=0;$porcentaje_cumplimiento_m_t=0;$seguimiento_m_t=0;$cierre_ventas_m_t=0;$porcentaje_eficiencia_m_t=0;$no_efectivas_m_t=0;
                foreach ($reportes as $k=>$v){
                    $efectividad[$i]['nombre_asesor']=$v['nombre_asesor'];
                    $efectividad[$i]['citas_programadas']=$v['citas_programadas'];
                    $efectividad[$i]['visitas_realizadas']=$v['visitas_realizadas'];
                    $efectividad[$i]['porcentaje_cumplimiento']=$v['porcentaje_cumplimiento'];
                    $efectividad[$i]['seguimiento']=$v['seguimiento'];
                    $efectividad[$i]['cierre_de_ventas']=$v['cierre_de_ventas'];
                    $efectividad[$i]['porcentaje_eficiencia']=$v['porcentaje_eficiencia'];
                    $efectividad[$i]['no_efectivas']=$v['no_efectivas'];

                    $efectividad[$i]['citas_programadas_p']=$v['citas_programadas_p'];
                    $efectividad[$i]['visitas_realizadas_p']=$v['visitas_realizadas_p'];
                    $efectividad[$i]['porcentaje_cumplimiento_p']=$v['porcentaje_cumplimiento_p'];
                    $efectividad[$i]['seguimiento_p']=$v['seguimiento_p'];
                    $efectividad[$i]['cierre_de_ventas_p']=$v['cierre_de_ventas_p'];
                    $efectividad[$i]['porcentaje_eficiencia_p']=$v['porcentaje_eficiencia_p'];
                    $efectividad[$i]['no_efectivas_p']=$v['no_efectivas_p'];

                    $efectividad[$i]['citas_programadas_m']=$v['citas_programadas_m'];
                    $efectividad[$i]['visitas_realizadas_m']=$v['visitas_realizadas_m'];
                    $efectividad[$i]['porcentaje_cumplimiento_m']=$v['porcentaje_cumplimiento_m'];
                    $efectividad[$i]['seguimiento_m']=$v['seguimiento_m'];
                    $efectividad[$i]['cierre_de_ventas_m']=$v['total_cierre_de_ventas'];
                    $efectividad[$i]['porcentaje_eficiencia_m']=$v['porcentaje_eficiencia_m'];
                    $efectividad[$i]['no_efectivas_m']=$v['no_efectivas_m'];

                    $efectividad[$i]['total_citas_programadas']=$v['total_citas_programadas'];
                    $efectividad[$i]['total_visitas_realizadas']=$v['total_visitas_realizadas'];
                    $efectividad[$i]['total_cumplimiento']=$v['total_cumplimiento'];
                    $efectividad[$i]['total_seguimiento']=$v['total_seguimiento'];
                    $efectividad[$i]['total_cierre_de_ventas']=$v['total_citas_agencia'];
                    $efectividad[$i]['total_eficiencia']=$v['total_eficiencia'];
                    $efectividad[$i]['total_no_efectivas']=$v['total_no_efectivas'];


                    $i++;

                    //TOTALES
                    $totales_citas=$citas_programadas_t+$citas_programadas_p_t+$citas_programadas_m_t;
                    $totales_visitas_realizadas=$visitas_realizadas_t+$visitas_realizadas_p_t+$visitas_realizadas_m_t;
                    $totales_seguimiento=$seguimiento_t+$seguimiento_p_t+$seguimiento_m_t;
                    $totales_cierre_ventas=$cierre_ventas_t+$cierre_ventas_p_t+$cierre_ventas_m_t;
                    $totales_no_efectivas=$no_efectivas_t+$no_efectivas_p_t+$no_efectivas_m_t;

                    if ($totales_citas==0){$totales_procentaje_cumplimiento=0;}else{$totales_procentaje_cumplimiento=round(($totales_visitas_realizadas*100)/$totales_citas,2);}
                    if ($visitas_realizadas_t==0){$totales_procentaje_eficiencia=0;}else{$totales_procentaje_eficiencia=round(($totales_cierre_ventas*100)/$totales_visitas_realizadas,2);}

                    $citas_programadas_t=$citas_programadas_t+$v['citas_programadas'];
                    $visitas_realizadas_t=$visitas_realizadas_t+$v['visitas_realizadas'];
                    $seguimiento_t=$seguimiento_t+$v['seguimiento'];
                    $cierre_ventas_t=$cierre_ventas_t+$v['cierre_de_ventas'];
                    $no_efectivas_t=$no_efectivas_t+$v['no_efectivas'];

                    $citas_programadas_p_t=$citas_programadas_p_t+$v['citas_programadas_p'];
                    $visitas_realizadas_p_t=$visitas_realizadas_p_t+$v['visitas_realizadas_p'];
                    $seguimiento_p_t=$seguimiento_p_t+$v['seguimiento_p'];
                    $cierre_ventas_p_t=$cierre_ventas_p_t+$v['cierre_de_ventas_p'];
                    $no_efectivas_p_t=$no_efectivas_p_t+$v['no_efectivas_p'];

                    $citas_programadas_m_t=$citas_programadas_m_t+$v['citas_programadas_m'];
                    $visitas_realizadas_m_t=$visitas_realizadas_m_t+$v['visitas_realizadas_m'];
                    $seguimiento_m_t=$seguimiento_m_t+$v['seguimiento_m'];
                    $cierre_ventas_m_t=$cierre_ventas_m_t+$v['cierre_de_ventas_m'];
                    $no_efectivas_m_t=$no_efectivas_m_t+$v['no_efectivas_m'];


                    //TOTALES
                    $totales_citas=$citas_programadas_t+$citas_programadas_p_t+$citas_programadas_m_t;
                    $totales_visitas_realizadas=$visitas_realizadas_t+$visitas_realizadas_p_t+$visitas_realizadas_m_t;
                    $totales_seguimiento=$seguimiento_t+$seguimiento_p_t+$seguimiento_m_t;
                    $totales_cierre_ventas=$cierre_ventas_t+$cierre_ventas_p_t+$cierre_ventas_m_t;
                    $totales_no_efectivas=$no_efectivas_t+$no_efectivas_p_t+$no_efectivas_m_t;

                    if ($totales_citas==0){$totales_procentaje_cumplimiento=0;}else{$totales_procentaje_cumplimiento=round(($totales_visitas_realizadas*100)/$totales_citas,2);}
                    if ($visitas_realizadas_t==0){$totales_procentaje_eficiencia=0;}else{$totales_procentaje_eficiencia=round(($totales_cierre_ventas*100)/$totales_visitas_realizadas,2);}

                };

                /*
                if ($citas_programadas_t==0){$porcentaje_cumplimiento_t=0;}else{$porcentaje_cumplimiento_t=round(($visitas_realizadas_t*100)/$citas_programadas_t,2);}
                if ($porcentaje_eficiencia_t==0){$porcentaje_eficiencia_t=0;}else{$porcentaje_eficiencia_t=round(($cierre_ventas_t*100)/$visitas_realizadas_t,2);}
                */

                $porcentaje_cumplimiento_t=round(($visitas_realizadas_t*100)/$citas_programadas_t,2);
                $porcentaje_eficiencia_t=round(($cierre_ventas_t*100)/$visitas_realizadas_t,2);

                $porcentaje_cumplimiento_p_t=round(($visitas_realizadas_p_t*100)/$citas_programadas_p_t,2);
                $porcentaje_eficiencia_p_t=round(($cierre_ventas_p_t*100)/$visitas_realizadas_p_t,2);

                $porcentaje_cumplimiento_m_t=round(($visitas_realizadas_m_t*100)/$citas_programadas_m_t,2);
                $porcentaje_eficiencia_m_t=round(($cierre_ventas_m_t*100)/$visitas_realizadas_m_t,2);

                $efectividad[$i]['nombre_asesor']='';
                $efectividad[$i]['citas_programadas']=$citas_programadas_t;
                $efectividad[$i]['visitas_realizadas']=$visitas_realizadas_t;
                $efectividad[$i]['porcentaje_cumplimiento']=$porcentaje_cumplimiento_t;
                $efectividad[$i]['seguimiento']=$seguimiento_t;
                $efectividad[$i]['cierre_de_ventas']=$cierre_ventas_t;
                $efectividad[$i]['porcentaje_eficiencia']=$porcentaje_eficiencia_t;
                $efectividad[$i]['no_efectivas']=$no_efectivas_t;

                $efectividad[$i]['citas_programadas_p']=$citas_programadas_p_t;
                $efectividad[$i]['visitas_realizadas_p']=$visitas_realizadas_p_t;
                $efectividad[$i]['porcentaje_cumplimiento_p']=$porcentaje_cumplimiento_p_t;
                $efectividad[$i]['seguimiento_p']=$seguimiento_p_t;
                $efectividad[$i]['cierre_de_ventas_p']=$cierre_ventas_p_t;
                $efectividad[$i]['porcentaje_eficiencia_p']=$porcentaje_eficiencia_p_t;
                $efectividad[$i]['no_efectivas_p']=$no_efectivas_p_t;

                $efectividad[$i]['citas_programadas_m']=$citas_programadas_m_t;
                $efectividad[$i]['visitas_realizadas_m']=$visitas_realizadas_m_t;
                $efectividad[$i]['porcentaje_cumplimiento_m']=$porcentaje_cumplimiento_m_t;
                $efectividad[$i]['seguimiento_m']=$seguimiento_m_t;
                $efectividad[$i]['cierre_de_ventas_m']=$cierre_ventas_m_t;
                $efectividad[$i]['porcentaje_eficiencia_m']=$porcentaje_eficiencia_m_t;
                $efectividad[$i]['no_efectivas_m']=$no_efectivas_m_t;

                $efectividad[$i]['total_citas_programadas']=$totales_citas;
                $efectividad[$i]['total_visitas_realizadas']=$totales_visitas_realizadas;
                $efectividad[$i]['total_cumplimiento']=$totales_procentaje_cumplimiento;
                $efectividad[$i]['total_seguimiento']=$totales_seguimiento;
                $efectividad[$i]['total_cierre_de_ventas']=$totales_cierre_ventas;
                $efectividad[$i]['total_eficiencia']=$totales_procentaje_eficiencia;
                $efectividad[$i]['total_no_efectivas']=$totales_no_efectivas;

                $i=$i+2;


                $sheet->fromArray($efectividad,null,'A2',true);
                $sheet->row(1, [
                    '', '', '', 'CITAS DE LA AGENCIA', '','','',''
                    ,'', '', 'CITAS PROPIAS', '', '','','',''
                    ,'', '', 'CITAS MANUALES', '', '',''
                    ,'','', '', 'TOTALES', '', '', ''
                ]);
                $sheet->cells('B1:H1', function ($cells) {
                    $cells->setBackground('#71B8FF');
                    $cells->setAlignment('center');
                });
                $sheet->cells('I1:O1', function ($cells) {
                    $cells->setBackground('#F7FE72');
                    $cells->setAlignment('center');
                });
                $sheet->cells('P1:V1', function ($cells) {
                    $cells->setBackground('#70d44f');
                    $cells->setAlignment('center');
                });
                $sheet->cells('W1:AC1', function ($cells) {
                    $cells->setBackground('#f0f0ee');
                    $cells->setAlignment('center');
                });
                $sheet->row(2, [
                    'ASESOR', 'CITAS PROGRAMADAS', 'VISITAS REALIZADAS', '%CUMPLIMIENTO', 'SEGUIMIENTO','CIERRE DE VENTAS','% EFICIENCIA','NO EFECTIVAS'
                    , 'CITAS PROGRAMADAS', 'VISITAS REALIZADAS', '%CUMPLIMIENTO', 'SEGUIMIENTO','CIERRE DE VENTAS','% EFICIENCIA','NO EFECTIVAS'
                    , 'CITAS PROGRAMADAS', 'VISITAS REALIZADAS', '%CUMPLIMIENTO', 'SEGUIMIENTO','CIERRE DE VENTAS','% EFICIENCIA','NO EFECTIVAS'
                    , 'TOTAL CITAS PROGRAMADAS', 'TOTAL VISITAS REALIZADAS', 'TOTAL % CUMPLIMIENTO','TOTAL SEGUIMIENTO','TOTAL CIERRE DE VENTAS','TOTAL % EFICIENCIA','TOTAL NO EFECTIVAS'
                ]);
                $sheet->cells('B2:H2', function ($cells) {
                    $cells->setBackground('#71B8FF');
                    $cells->setAlignment('center');
                });
                $sheet->cells('I2:O2', function ($cells) {
                    $cells->setBackground('#F7FE72');
                    $cells->setAlignment('center');
                });
                $sheet->cells('P2:V2', function ($cells) {
                    $cells->setBackground('#70d44f');
                    $cells->setAlignment('center');
                });
                $sheet->cells('W2:AC2', function ($cells) {
                    $cells->setBackground('#f0f0ee');
                    $cells->setAlignment('center');
                });
                $sheet->setBorder('A2:AC2', 'thin', "000");
                $sheet->setBorder('B'.($i+1).':AC'.($i+1), 'thin', "000");
            });


        })->export('xlsx');
    }


    public function reportGeneralCuentas()
    {
        //$reportes = tbl_clientes::get();
        $query = "SELECT max(id_gestion) id_gestion FROM gestiones GROUP BY id_gestion_cobefec order by id_gestion DESC;";
        $reportes=DB::connection('bmi')->select($query);

        //$reportes = json_decode(json_encode($reportes), true);

        $i=0;
        $efectividad=Array();
        $reporte_gestiones=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;

        foreach ($reportes as $k){
            /*
                cedula
                nombre
                asesor_actual
                ultima_gestion
                ultima_gestion_tipo
                ultima_gestion_accion
                ultima_gestion_observacion
                ultima_gestion_fecha
                mejor_gestion
                mejor_gestion_tipo
                mejor_gestion_accion
                mejor_gestion_observacion
                mejor_gestion_fecha
            */
            $gestion=tbl_gestiones::find($k->id_gestion);
            $cliente=tbl_clientes::where('id_gestion',$gestion->id_gestion_cobefec)->first();
            if (isset($cliente)){

                $efectividad[$i]['ci']=isset($cliente->cedula_cliente) ? $cliente->cedula_cliente : '';
                $efectividad[$i]['nombres_cliente']=isset($cliente->nombres) ? $cliente->nombres : '';
                $efectividad[$i]['asesor_actual']=isset($gestion->asesor->nombres) ? $gestion->asesor->nombres : 'No asignado';
                //$efectividad[$i]['nombres_asesor']=isset($cliente->nombres) ? $cliente->nombres : '';
                //$efectividad[$i]['cedula_asesor']=isset($gestion->cedula_asesor) ? $gestion->cedula_asesor : '';

                $primera_gestion=tbl_gestiones::where('id_gestion_cobefec',$gestion->id_gestion_cobefec)->first();

                $efectividad[$i]['fecha_cita_tlc']=isset($primera_gestion->gestionCobefec->fecha_cita) ? $primera_gestion->gestionCobefec->fecha_cita.' '.substr($primera_gestion->gestionCobefec->hora_cita,0,-3) : '';
                $efectividad[$i]['primera_gestion_fecha']=isset($primera_gestion->fecha_visita) ? $primera_gestion->fecha_visita : '';
                $efectividad[$i]['primera_gestion_accion']=isset($primera_gestion->accion->descripcion) ? $primera_gestion->accion->descripcion : '';
                $efectividad[$i]['primera_gestion_observacion']=isset($primera_gestion->observaciones) ? $primera_gestion->observaciones : '';

                $efectividad[$i]['ultima_gestion_fecha']=isset($cliente->fecha_ultimo_peso) ? $cliente->fecha_ultimo_peso : '';
                $efectividad[$i]['ultima_gestion_accion']=isset($cliente->ultimaGestion->accion->descripcion) ? $cliente->ultimaGestion->accion->descripcion : '';
                $efectividad[$i]['ultima_gestion_observacion']=isset($cliente->ultimaGestion->observaciones) ? $cliente->ultimaGestion->observaciones : '';
                $efectividad[$i]['ultima_gestion_fecha_confirmacion_cita']=isset($cliente->fecha_ultimo_peso_confirmacion_cita) ? $cliente->fecha_ultimo_peso_confirmacion_cita : '';

                $efectividad[$i]['mejor_gestion_fecha']=isset($cliente->fecha_mayor_peso) ? $cliente->fecha_mayor_peso : '';
                $efectividad[$i]['mejor_gestion_accion']=isset($cliente->mejorGestion->accion->descripcion) ? $cliente->mejorGestion->accion->descripcion : '';
                $efectividad[$i]['mejor_gestion_observacion']=isset($cliente->mejorGestion->observaciones) ? $cliente->mejorGestion->observaciones : '';
                $efectividad[$i]['mejor_gestion_fecha_confirmacion_cita']=isset($cliente->fecha_mayor_peso_confirmacion_cita) ? $cliente->fecha_mayor_peso_confirmacion_cita : '';

                $i++;
            }
        }
        $asesores=tbl_asesores::where('estado',1)->pluck("nombres","cedula_asesor")->all();

        return view('bmi.reportes.reporteGeneralCuentas', compact('efectividad','asesores'));
    }

    public function reportGeneralCuentasFecha(Request $request)
    {

        $asesores=implode(',',$request->asesores);

        $fecha_inicio=Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin=Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');
        //$reportes = tbl_clientes::get();
        $query = "SELECT max(id_gestion) id_gestion FROM gestiones where date(fecha_cita_programada) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' and cedula_asesor in (".$asesores.") GROUP BY id_gestion_cobefec order by id_gestion DESC";
        $reportes=DB::connection('bmi')->select($query);

        //$reportes = json_decode(json_encode($reportes), true);

        $i=0;
        $efectividad=Array();
        $reporte_gestiones=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;

        foreach ($reportes as $k){
            /*
                cedula
                nombre
                asesor_actual
                ultima_gestion
                ultima_gestion_tipo
                ultima_gestion_accion
                ultima_gestion_observacion
                ultima_gestion_fecha
                mejor_gestion
                mejor_gestion_tipo
                mejor_gestion_accion
                mejor_gestion_observacion
                mejor_gestion_fecha
            */
            $gestion=tbl_gestiones::find($k->id_gestion);
            $cliente=tbl_clientes::where('id_gestion',$gestion->id_gestion_cobefec)->first();
            if (isset($cliente)){

                $efectividad[$i]['ci']=isset($cliente->cedula_cliente) ? $cliente->cedula_cliente : '';
                $efectividad[$i]['nombres_cliente']=isset($cliente->nombres) ? $cliente->nombres : '';
                $efectividad[$i]['asesor_actual']=isset($gestion->asesor->nombres) ? $gestion->asesor->nombres : 'No asignado';
                //$efectividad[$i]['nombres_asesor']=isset($cliente->nombres) ? $cliente->nombres : '';
                //$efectividad[$i]['cedula_asesor']=isset($gestion->cedula_asesor) ? $gestion->cedula_asesor : '';

                $primera_gestion=tbl_gestiones::where('id_gestion_cobefec',$gestion->id_gestion_cobefec)->first();

                $efectividad[$i]['fecha_cita_tlc']=isset($primera_gestion->gestionCobefec->fecha_cita) ? $primera_gestion->gestionCobefec->fecha_cita.' '.substr($primera_gestion->gestionCobefec->hora_cita,0,-3) : '';
                $efectividad[$i]['primera_gestion_fecha']=isset($primera_gestion->fecha_visita) ? $primera_gestion->fecha_visita : '';
                $efectividad[$i]['primera_gestion_accion']=isset($primera_gestion->accion->descripcion) ? $primera_gestion->accion->descripcion : '';
                $efectividad[$i]['primera_gestion_observacion']=isset($primera_gestion->observaciones) ? $primera_gestion->observaciones : '';

                $efectividad[$i]['ultima_gestion_fecha']=isset($cliente->fecha_ultimo_peso) ? $cliente->fecha_ultimo_peso : '';
                $efectividad[$i]['ultima_gestion_accion']=isset($cliente->ultimaGestion->accion->descripcion) ? $cliente->ultimaGestion->accion->descripcion : '';
                $efectividad[$i]['ultima_gestion_observacion']=isset($cliente->ultimaGestion->observaciones) ? $cliente->ultimaGestion->observaciones : '';
                $efectividad[$i]['ultima_gestion_fecha_confirmacion_cita']=isset($cliente->fecha_ultimo_peso_confirmacion_cita) ? $cliente->fecha_ultimo_peso_confirmacion_cita : '';

                $efectividad[$i]['mejor_gestion_fecha']=isset($cliente->fecha_mayor_peso) ? $cliente->fecha_mayor_peso : '';
                $efectividad[$i]['mejor_gestion_accion']=isset($cliente->mejorGestion->accion->descripcion) ? $cliente->mejorGestion->accion->descripcion : '';
                $efectividad[$i]['mejor_gestion_observacion']=isset($cliente->mejorGestion->observaciones) ? $cliente->mejorGestion->observaciones : '';
                $efectividad[$i]['mejor_gestion_fecha_confirmacion_cita']=isset($cliente->fecha_mayor_peso_confirmacion_cita) ? $cliente->fecha_mayor_peso_confirmacion_cita : '';

                $i++;
            }
        }
        $asesores=tbl_asesores::where('estado',1)->pluck("nombres","cedula_asesor")->all();

        $fecha_inicio=$request->fecha_inicio;
        $fecha_fin=$request->fecha_fin;
        $asesores2=implode(',',$request->asesores);
        return view('bmi.reportes.reporteGeneralCuentas', compact('efectividad','asesores','fecha_inicio','fecha_fin','asesores2'));
    }

    public function generalCuentasBmi(Request $request)
    {
        $asesores=$request->asesores;

        $fecha_inicio=Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin=Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');
        //$reportes = tbl_clientes::get();
        $query = "SELECT max(id_gestion) id_gestion FROM gestiones where date(fecha_cita_programada) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' and cedula_asesor in (".$asesores.") GROUP BY id_gestion_cobefec order by id_gestion DESC";

        $reportes=DB::connection('bmi')->select($query);

        //$reportes = json_decode(json_encode($reportes), true);
        \Excel::create('REPORTE-GENERAL-CUENTAS-BMI-GENERADO-EL-'.date('Y-m-d').'DESDE-'.$fecha_inicio.'-HASTA-'.$fecha_fin, function($excel) use (&$reportes){
            $excel->sheet('Efectividad', function($sheet) use($reportes) {
                $i=0;
                $efectividad=Array();
                $reporte_gestiones=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_ventas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;

                foreach ($reportes as $k){
                    /*
                        cedula
                        nombre
                        asesor_actual
                        ultima_gestion
                        ultima_gestion_tipo
                        ultima_gestion_accion
                        ultima_gestion_observacion
                        ultima_gestion_fecha
                        mejor_gestion
                        mejor_gestion_tipo
                        mejor_gestion_accion
                        mejor_gestion_observacion
                        mejor_gestion_fecha
                    */
                    $gestion=tbl_gestiones::find($k->id_gestion);
                    $cliente=tbl_clientes::where('id_gestion',$gestion->id_gestion_cobefec)->first();
                    if (isset($cliente)){

                        $efectividad[$i]['ci']=isset($cliente->cedula_cliente) ? $cliente->cedula_cliente : '';
                        $efectividad[$i]['nombres_cliente']=isset($cliente->nombres) ? $cliente->nombres : '';
                        $efectividad[$i]['asesor_actual']=isset($gestion->asesor->nombres) ? $gestion->asesor->nombres : 'No asignado';
                        //$efectividad[$i]['nombres_asesor']=isset($cliente->nombres) ? $cliente->nombres : '';
                        //$efectividad[$i]['cedula_asesor']=isset($gestion->cedula_asesor) ? $gestion->cedula_asesor : '';

                        $primera_gestion=tbl_gestiones::where('id_gestion_cobefec',$gestion->id_gestion_cobefec)->first();

                        $efectividad[$i]['fecha_cita_tlc']=isset($primera_gestion->gestionCobefec->fecha_cita) ? $primera_gestion->gestionCobefec->fecha_cita.' '.substr($primera_gestion->gestionCobefec->hora_cita,0,-3) : '';
                        $efectividad[$i]['primera_gestion_fecha']=isset($primera_gestion->fecha_visita) ? $primera_gestion->fecha_visita : '';
                        $efectividad[$i]['primera_gestion_accion']=isset($primera_gestion->accion->descripcion) ? $primera_gestion->accion->descripcion : '';
                        $efectividad[$i]['primera_gestion_observacion']=isset($primera_gestion->observaciones) ? $primera_gestion->observaciones : '';

                        $efectividad[$i]['ultima_gestion_fecha']=isset($cliente->fecha_ultimo_peso) ? $cliente->fecha_ultimo_peso : '';
                        $efectividad[$i]['ultima_gestion_accion']=isset($cliente->ultimaGestion->accion->descripcion) ? $cliente->ultimaGestion->accion->descripcion : '';
                        $efectividad[$i]['ultima_gestion_observacion']=isset($cliente->ultimaGestion->observaciones) ? $cliente->ultimaGestion->observaciones : '';
                        $efectividad[$i]['ultima_gestion_fecha_confirmacion_cita']=isset($cliente->fecha_ultimo_peso_confirmacion_cita) ? $cliente->fecha_ultimo_peso_confirmacion_cita : '';

                        $efectividad[$i]['mejor_gestion_fecha']=isset($cliente->fecha_mayor_peso) ? $cliente->fecha_mayor_peso : '';
                        $efectividad[$i]['mejor_gestion_accion']=isset($cliente->mejorGestion->accion->descripcion) ? $cliente->mejorGestion->accion->descripcion : '';
                        $efectividad[$i]['mejor_gestion_observacion']=isset($cliente->mejorGestion->observaciones) ? $cliente->mejorGestion->observaciones : '';
                        $efectividad[$i]['mejor_gestion_fecha_confirmacion_cita']=isset($cliente->fecha_mayor_peso_confirmacion_cita) ? $cliente->fecha_mayor_peso_confirmacion_cita : '';

                        $i++;
                    }
                };

                $sheet->fromArray($efectividad,null,'A1',true);
                $sheet->row(1, ['CEDULA CLIENTE',	'NOMBRES', 'ASESOR ACTUAL', 'FECHA CITA TLC', 'PRIMERA GESTION FECHA', 'PRIMERA GESTION ACCION', 'PRIMERA GESTION (OBSERVACION)', 'ULTIMA GESTION FECHA', 'ULTIMA GESTION ACCION', 'ULTIMA GESTION (OBSERVACION)',	'ULTIMA GESTION (FECHA DE CONFIRMACION DE CITA)', 'MEJOR GESTION FECHA', 'MEJOR GESTION ACCION', 'MEJOR GESTION (OBSERVACION)',	'MEJOR GESTION (FECHA DE CONFIRMACION DE CITA)'
                ]);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#63b6fd');
                });
                $sheet->setBorder('A1:K1', 'thin', "000");
            });
        })->export('xlsx');
    }

    public function actualizaUltimaGestion()
    {
        //$reportes = tbl_clientes::get();
        $query = "SELECT  id_gestion FROM clientes;";
        $clientes = DB::connection('bmi')->select($query);

        foreach ($clientes as $cliente){
            $query = "SELECT max(id_gestion) id_gestion FROM gestiones where id_gestion_cobefec=".$cliente->id_gestion.";";
            $ultima_gestion = DB::connection('bmi')->select($query);


            if (isset($ultima_gestion[0]->id_gestion)){
                $query = "SELECT id_gestion_cobefec FROM gestiones where id_gestion=".$ultima_gestion[0]->id_gestion.";";
                $ultima_gestion2 = DB::connection('bmi')->select($query);

                $gestion=tbl_gestiones::where('id_gestion',$ultima_gestion[0]->id_gestion)->first();

                $cliente = tbl_clientes::where('id_gestion',$ultima_gestion2[0]->id_gestion_cobefec)->first();
                $cliente->id_ultima_gestion=$gestion->id_gestion;
                $cliente->ultimo_peso=$gestion->accion->peso;
                $cliente->id_accion_ultimo_peso=$gestion->id_accion;
                $cliente->fecha_ultimo_peso=$gestion->updated_at;
                if($gestion->accion->necesita_calendario==1){
                    $cliente->fecha_ultimo_peso_confirmacion_cita=$gestion->fecha_cita_programada;
                }else{
                    $cliente->fecha_ultimo_peso_confirmacion_cita=null;
                }
                $cliente->save();
            }
        }
        return 'procesado correctamente utima gestion';
    }

    public function actualizaMejorGestion()
    {
        //$reportes = tbl_clientes::get();
        $query = "SELECT  id_gestion FROM clientes;";
        $clientes = DB::connection('bmi')->select($query);

        foreach ($clientes as $cliente){
            $query = "SELECT a.peso peso
FROM gestiones g, tbl_accion a 
where g.id_gestion_cobefec=".$cliente->id_gestion."  
and a.id_accion=g.id_accion order by  a.peso DESC
limit 1;";
            $peso = DB::connection('bmi')->select($query);

            if (isset($peso[0]->id_gestion)) {
                $query = "SELECT g.id_gestion, g.id_gestion_cobefec, g.id_cita, g.id_accion, a.peso, g.created_at, g.observaciones
                    FROM gestiones g, tbl_accion a 
                    where g.id_gestion_cobefec=" . $cliente->id_gestion . " and a.peso=" . $peso[0]->peso . "
                    and a.id_accion=g.id_accion order by g.id_gestion DESC limit 1
                    ;";
                $mejor_gestion = DB::connection('bmi')->select($query);


                if (isset($mejor_gestion [0]->id_gestion)) {
                    $query = "SELECT id_gestion_cobefec FROM gestiones where id_gestion=" . $mejor_gestion [0]->id_gestion . ";";
                    $mejor_gestion2 = DB::connection('bmi')->select($query);

                    $gestion = tbl_gestiones::where('id_gestion', $mejor_gestion[0]->id_gestion)->first();

                    $cliente = tbl_clientes::where('id_gestion', $mejor_gestion2[0]->id_gestion_cobefec)->first();

                    $cliente->id_mayor_gestion = $gestion->id_gestion;
                    $cliente->mayor_peso = $gestion->accion->peso;
                    $cliente->id_accion_mayor_peso = $gestion->id_accion;
                    $cliente->fecha_mayor_peso = $gestion->updated_at;

                    if ($gestion->accion->necesita_calendario == 1) {
                        $cliente->fecha_mayor_peso_confirmacion_cita = $gestion->fecha_cita_programada;
                    } else {
                        $cliente->fecha_mayor_peso_confirmacion_cita = null;
                    }
                    $cliente->save();
                }
            }
        }

        return 'procesado correctamente mejor gestion';
    }
}

function reemplazar_ultimo($buscar, $remplazar, $texto){
    $pos = strrpos($texto, $buscar);
    if($pos !== false){
        $texto = substr_replace($texto, $remplazar, $pos, strlen($buscar));
    }
    return $texto;
}
function distribuirAsesoresClientes(){
    //ASIGNACION #CLIENTES CON #ASESORES
    tbl_citas_con_clientes::query()->truncate();
    //tbl_fecha_hora_cita::query()->truncate();

    //tbl_clientes::where('estado',0)->update(['estado'=>1]);

    if(tbl_citas_con_clientes::where('estado',1)->count()==0){
        $parametros_citas=tbl_parametros_citas::where('estado',1)->get();
        $total_acumulado= Array();
        $disponibles=Array();
        foreach ($parametros_citas as $cita){
            $hora=new Carbon();
            /*for ($i=1;$i<=5;$i++){
                $fecha_hora=new tbl_fecha_hora_cita();
                $fecha_hora->id_parametros_citas=$cita->id_parametros_citas;

                $fecha_hora->fecha_cita=Carbon::now()->addDay(1)->format('Y-m-d');

                if ($i==1){

                    $hora=Carbon::createFromFormat('H:i', $cita->hora_inicio)->addDay(1);
                    $hora_fin=Carbon::createFromFormat('H:i', $cita->hora_inicio)->addDay(1)->addMinutes($cita->tiempo_citas);
                    $fecha_hora->hora_cita_ini=$hora;
                    $fecha_hora->hora_cita_fin=$hora_fin;
                }else{
                    $fecha_hora->hora_cita_ini=$hora->addMinutes($cita->tiempo_citas);
                    $fecha_hora->hora_cita_fin=$hora_fin->addMinutes($cita->tiempo_citas);
                }
                $fecha_hora->save();
            }*/

            $totalf=0;
            $total=0;

            $total_clientes_a_asignar=0;
            $rc=tbl_ranking_cliente::where('estado',1)->orderBy('nivel','ASC')->get();
            //echo "<br>--------------";

            foreach ($rc as $key) {
                if (($total_clientes =tbl_clientes::where('estado',1)->where('id_ranking',$key->id_ranking_cliente)->count())>0) {


                    //echo "<br><br>nivel_clientes:" . $key->nivel. " -> total:" . $total_clientes;
                    if (!isset($disponibles[$key->id_ranking_cliente])) {
                        $disponibles[$key->id_ranking_cliente]=$total_clientes;
                        //echo "<br>disponibles id_ranking"."$key->id_ranking_cliente: ".$disponibles[$key->id_ranking_cliente];
                    }

                    if (!isset($total_acumulado[$key->id_ranking_cliente])) {
                        $total_acumulado[$key->id_ranking_cliente] = 0;
                    }

                    if ($total_acumulado[$key->id_ranking_cliente]<$total_clientes){
                        $citas_clientes = new tbl_citas_con_clientes();
                        $citas_clientes->fecha = date('Y-m-d');
                        $citas_clientes->id_parametros_citas = $cita->id_parametros_citas;
                        $citas_clientes->id_ranking_cliente = $key->id_ranking_cliente;
                        $citas_clientes->estado = 1;

                        $total_asesores = tbl_asesores::where('estado', 1)->where('id_ranking', $cita->id_ranking)->count();
                        if ($total_asesores > 0) {
                            if ($total_clientes_a_asignar == 0) {
                                $total_clientes_a_asignar = $cita->citas_max * $total_asesores;
                            } else {
                                $total_clientes_a_asignar = $total_clientes_a_asignar - $totalf;
                            }
                            //echo "<br><br>nivel asesores:" . $cita->id_ranking . " -> total_asesores:" . $total_asesores;
                            //echo "<br>total_clientes_a_asignar:" . $total_clientes_a_asignar;

                            $total = $total_clientes_a_asignar - $total_clientes;
                            if ($total < 0) {
                                $total = $total_clientes_a_asignar;
                            } else {
                                $total = $total_clientes;
                            }
                            //echo "<br>total:" . $total;
                            if ($total <= $total_clientes_a_asignar) {
                                $totalf = $total;


                                if ($total_acumulado[$key->id_ranking_cliente] == 0) {
                                    $total_acumulado[$key->id_ranking_cliente] = $totalf;
                                } else {
                                    $total_acumulado[$key->id_ranking_cliente] = $total_acumulado[$key->id_ranking_cliente] + $totalf;
                                }

                                $disponibles[$key->id_ranking_cliente]=$disponibles[$key->id_ranking_cliente]-$totalf;
                                if ($disponibles[$key->id_ranking_cliente]<0){
                                    $totalf=$totalf+$disponibles[$key->id_ranking_cliente];
                                    $disponibles[$key->id_ranking_cliente]=0;
                                }

                                /*echo "<br>--total_clientes_a_asignar: " . $totalf;
                                echo "<br>--total_acumulado: " . $total_acumulado[$key->id_ranking_cliente];
                                echo "<br>--disponibles: " . $disponibles[$key->id_ranking_cliente];*/
                                $citas_clientes->total = $totalf;
                                $citas_clientes->disponibles = $disponibles[$key->id_ranking_cliente];
                            }
                        }
                    }else{
                        $citas_clientes = new tbl_citas_con_clientes();
                        $citas_clientes->fecha=date('Y-m-d');
                        $citas_clientes->id_parametros_citas = $cita->id_parametros_citas;
                        $citas_clientes->id_ranking_cliente = $key->id_ranking_cliente;
                        $citas_clientes->estado = 1;
                        $citas_clientes->disponibles =0;
                        $citas_clientes->total=0;
                    }
                }else{
                    $citas_clientes = new tbl_citas_con_clientes();
                    $citas_clientes->fecha=date('Y-m-d');
                    $citas_clientes->id_parametros_citas = $cita->id_parametros_citas;
                    $citas_clientes->id_ranking_cliente = $key->id_ranking_cliente;
                    $citas_clientes->estado = 1;
                    $citas_clientes->disponibles =0;
                    $citas_clientes->total=0;
                }
                $citas_clientes->save();
            }
        }
    }
    //FIN ASIGNACION #CLIENTES CON #ASESORES
}

/*
 SELECT c.usuario_gestion,c.cedula_cliente,c.nombres as nombres_cliente,asesor.nombres as nombre_asesor,c.fecha_cita,c.hora_cita,
t.descripcion as tipo,a.descripcion as accion,
g.observaciones,
c.id_gestion_cobefec
from citas_historial as c,gestiones as g
INNER JOIN tbl_tipo as t ON g.id_tipo= t.id_tipo
INNER JOIN tbl_accion as a ON g.id_accion= a.id_accion
INNER JOIN asesores as asesor ON g.cedula_asesor=asesor.cedula_asesor
where c.pais='Peru'
and g.cedula_asesor=c.asesor
and fecha_cita='2018-03-30'
GROUP BY c.cedula_cliente,asesor.nombres

UNION ALL

SELECT c.usuario_gestion,c.cedula_cliente,c.nombres as nombres_cliente,asesor.nombres as nombre_asesor,c.fecha_cita,c.hora_cita,
if (c.id_gestion is NULL,'NO CONTACTADO',c.id_gestion) as tipo
, c.id_gestion as accion
, c.id_gestion as  observaciones
, c.id_gestion_cobefec
from citas as c, asesores as asesor
where c.id_gestion is NULL
and pais='Peru'
and fecha_cita='2018-03-30'
and asesor.cedula_asesor=c.asesor
;
 * */                                      