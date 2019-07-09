<?php
namespace App\Console\Commands;
use App\ivrs\tbl_campania;
use App\ivrs\tbl_script;
use PAMI\Message\Action\QueueLogAction;
use App\ivrs\tbl_carga as carga;
use App\ivrs\tbl_id_carga as id_carga;
use App\ivrs\tbl_canales as canales;
use App\ivrs\tbl_script as scripts;
use App\ivrs\tbl_cdr_242 as cdr;
use App\reportesNuevoSistema\tbl_accounts;
use App\reportesNuevoSistema\tbl_demarches;
use Carbon\Carbon;
use PAMI\Message\Action\CommandAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Client\Impl\ClientImpl as PamiClient;
use DB;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use Elasticsearch\ClientBuilder;

class SendIvrs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ivr:enviar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar ivrs cuyo estado es igual a 1';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time',0);

        $pamiClientOptions = array(
            'host' => '172.16.21.242',
            'scheme' => 'tcp://',
            'port' => 5038,
            'username' => 'admin',
            'secret' => 'amp111',
            'connect_timeout' => 10,
            'read_timeout' => 10
        );

        $fecha_actual = Carbon::now();
        $atmCampanas=tbl_campania::where('id_cliente',11)->where('estado',1)->get(['id_campania']);
        $idCampanas=Array();
        foreach ($atmCampanas as $atmCampana){
            array_push($idCampanas,$atmCampana->id_campania);
        }
        //SOLO PARA ATM
        $id_carga = id_carga::where('estado', 1)->where('estado_aprobado', 1)->whereIn('id_campania',$idCampanas)->get();
        $id_carga_calendarizado = id_carga::where('estado', 1)->where('estado_aprobado', 1)->where('calendarizado', 1)->whereIn('id_campania',$idCampanas)->get();

        $sabado=date("w", strtotime(date('Y-m-d')));
        if ($sabado==6){
            $horaInicio='09:00:00';
            $horaFin='13:00:00';
        }else{
            $horaInicio='08:00:00';
            $horaFin='18:00:00';
        }
        if($sabado==0){
            dd('fin');
        }
        echo "\n Sabado es 6 : ".$sabado." - ".$horaInicio." - ".$horaFin."\n";

        $finalizar=1;
        echo "\nid_carga de ATM: ".count($id_carga)."\n";
        if (count($id_carga)>0){
            echo "\nFecha inicio: ".$fecha_actual." - ".date('Y-m-d ').$horaInicio;
            echo "\nFecha fin: ".$fecha_actual." - ".date('Y-m-d ').$horaFin."\n";
            if (strtotime($fecha_actual)>strtotime(date('Y-m-d ').$horaInicio) && strtotime($fecha_actual)<strtotime(date('Y-m-d ').$horaFin)) {
                echo "\n entro al if: ".$fecha_actual;
                $id_carga = id_carga::where('estado', 1)->where('estado_aprobado', 1)->where('ejecucion', 1)->whereIn('id_campania',$idCampanas)->get();
                enviarIvrs($id_carga,$id_carga_calendarizado,$finalizar,$pamiClientOptions);
            }
            if (count($id_carga_calendarizado)>0)

            if(strtotime($fecha_actual)>strtotime(date('Y-m-d').$horaFin)){
                echo "\n entro al if para finalizar la campaña: ".$fecha_actual."\n\n";
                $id_cargas = $id_carga;
                foreach ($id_cargas as $id_carga){
                    if ($id_carga->calendarizado==1){
                        echo "\n fecha calendarizado : ".$id_carga->fecha_inicio_envio." = ".strtotime($id_carga->fecha_inicio_envio)." :: fecha inicio ".date('Y-m-d ').$horaInicio." = ".strtotime(date('Y-m-d ').$horaInicio)." :: fecha fin ".date('Y-m-d ').$horaFin." = ".strtotime(date('Y-m-d ').$horaFin)."\n";
                        if(strtotime($id_carga->fecha_inicio_envio)>strtotime(date('Y-m-d ').$horaInicio) && strtotime($id_carga->fecha_inicio_envio)<strtotime(date('Y-m-d ').$horaFin)){
                            if(strtotime($fecha_actual)>strtotime(date('Y-m-d ').$horaInicio) && strtotime($fecha_actual)<strtotime(date('Y-m-d ').$horaFin)){
                                echo "\nIvr calendarizado: ".$id_carga->id_carga." no finalizada. Fecha de envio: ".$id_carga->fecha_inicio_envio." :: fecha actual: ".$fecha_actual;
                                $id_carga->estado=1;
                            }else{
                                echo "\nIvr calendarizado: ".$id_carga->id_carga." finalizada. Fecha de envio: ".$id_carga->fecha_inicio_envio." :: fecha actual: ".$fecha_actual;
                                $id_carga->estado=0;
                            }
                        }elseif(strtotime($id_carga->fecha_inicio_envio)>strtotime(date('Y-m-d ').$horaFin)){
                            echo "\nIvr calendarizado: ".$id_carga->id_carga." finalizada. Fecha de envio: ".$id_carga->fecha_inicio_envio." :: fecha actual: ".$fecha_actual;
                            $id_carga->estado=1;
                        }
                    }else{
                        echo "\nIvr no calendarizado: ".$id_carga->id_carga." no finalizada. Fecha de envio: ".$id_carga->fecha_inicio_envio." :: fecha actual: ".$fecha_actual;
                        $id_carga->estado=0;
                    }
                    $id_carga->save();
                    echo "\n".$id_carga;
                }
            }
        }

        //PARA CUALQUIER MARCA
        $id_carga = id_carga::where('estado', 1)->where('estado_aprobado', 1)->whereNotIn('id_campania',$idCampanas)->get();
        $id_carga_calendarizado = id_carga::where('estado', 1)->where('estado_aprobado', 1)->where('calendarizado', 1)->whereNotIn('id_campania',$idCampanas)->get();
        $horaInicio='08:00:00';
        $horaFin='20:00:00';
        $finalizar=0;
        echo "\nid_carga diferentes ATM: ".count($id_carga)."\n";
        if (count($id_carga)>0){
            echo "\nentro al if\n";
            if (strtotime($fecha_actual)>strtotime(date('Y-m-d ').$horaInicio) && strtotime($fecha_actual)<strtotime(date('Y-m-d ').$horaFin)) {
                echo "\nentro al 2do if\n";
                $id_carga = id_carga::where('estado', 1)->where('estado_aprobado', 1)->where('ejecucion', 1)->whereNotIn('id_campania',$idCampanas)->get();
                echo "\nid carga no atm: ".$id_carga."\n";
                enviarIvrs($id_carga,$id_carga_calendarizado,$finalizar,$pamiClientOptions);
            }
        }

        //SOLO PARA ATM INICIO
        //Actualizo e indexo las gestiones ATM
        $id_cargas=id_carga::where('estado',0)->whereNotNull('id_campania_sis_gest')->where('estado_indexado',0)->get();

        foreach ($id_cargas as $idc){
            if ($idc->campaniaIvr->clienteIvr->id_cliente==11){
                if (count($idc)>0){
                    //inicio actualizo como indexado las cuentas que nunca se llamaron para que no los ingrese en el sistema de gestión
                    carga::where('id_carga',$idc->id_carga)->whereNull('id_gestion_original')->where('estado_indexado',0)->update(['estado_indexado' => 1]);
                    //fin actualizo ...

                    echo "\nantes del foreach indexar gestiones ".date('Y-m-d H:i:s')."\n";
                    //$cdr = cdr::where('id_carga',$k->id_carga)->where('id_campania_sis_gest',$k->id_campania_sis_gest)->where('disposition','ANSWERED')->get();
                    $query="call cobefec_reportes.sp_ivr_indexar(".$idc->id_carga.");";
                    DB::connection('cdr')->select($query);
                    $query="select i.id, i.cedula, i.telefono, i.cuenta, i.ESTADO, i.Fecha, i.Duracion, i.Eventos, i.valor_estado, i.id_gestion_original, i.id_carga,i.estado_indexado
from cobefec_reportes.tbl_ivr_index i 
where 
i.Fecha=(select max(Fecha) from cobefec_reportes.tbl_ivr_index where if(i.cuenta is null,cuenta is null,cuenta=i.cuenta) and telefono=i.telefono)
and i.estado_indexado=0 and id_carga=".$idc->id_carga.";";
                    try{
                        $reporte = DB::connection('cdr')->select($query);
                    } catch (\Exception $e) {
                        return $e->getMessage() . "\n";
                    }
                    $reporte = json_decode(json_encode($reporte), true);
                    echo "\nempieza a actualizar la base ".date('Y-m-d H:i:s');
                    foreach ($reporte as $key)
                    {
                        subirGestion2($key['id_gestion_original'],$key['ESTADO']);
                        $idCargaG=carga::find($key['id']);
                        $idCargaG->estado_indexado=1;
                        $idCargaG->save();
                    }
                    echo "\nfinaliza la actualizacion de la base ".date('Y-m-d H:i:s');
                    echo "\nid_carga: ".$idc->id_carga." - total llamadas contestadas a indexar: ".count($reporte);
                }
            }
        }

        //INDEXO TODA LA CAMPAÑA
        echo "\nEmpiezo a indexar";
        $id_cargas=id_carga::where('estado',0)->whereNotNull('id_campania_sis_gest')->where('estado_indexado',0)->where('porcentaje_indexado',100)->get();
        echo "\nId carga ".$id_cargas;
        foreach ($id_cargas as $idc){

            if ($idc->campaniaIvr->clienteIvr->id_cliente==11){

                if (count($idc)>0){
                    echo "\n\ningresa al metodo de indexacion por campana :".$idc->id_campania_sis_gest.": ".date('Y-m-d H:i:s');
                    subirGestionesPorCampana($idc->id_campania_sis_gest,$idc->id_carga);
                    echo "\nsale del metodo de indexacion por campana ".date('Y-m-d H:i:s');
                }
            }
        }
        //FIN INDEXO TODA LA CAMPAÑA
        //FIN SOLO PARA ATM

        //INICIO CRON PARA REPORTES TOTAL DE LLAMADAS VS CONTESTADOS
        $id_carga = id_carga::where('estado', 0)->whereNull('total_carga')->get();
        echo "\nId carga: ".$id_carga;
        //Actualizo el total de teléfonos cargados
        foreach ($id_carga as $k) {
            $carga = carga::where('id_carga', $k->id_carga)->count();
            $k->total_carga = $carga;
            $k->save();
            echo "\nid_carga: " . $k->id_carga;
            echo "\ntotal carga: " . $carga;
        }
        //Actualizo el total de llamadas contestadas desde la tabla cdr (ANSWERED)
        $id_carga = id_carga::where('estado', 0)->whereNull('total_llamadas')->get();
        echo "\nantes del foreach total llamadas";
        foreach ($id_carga as $k) {
            $cdr = cdr::where('id_carga', $k->id_carga)->where('disposition', 'ANSWERED')->count();
            echo "\nid_carga: " . $k->id_carga;
            echo " - total llamadas: " . $cdr;
            $k->total_llamadas = $cdr;
            $k->save();
        }
        //FIN CRON PARA REPORTES TOTAL DE LLAMADAS VS CONTESTADOS
    }
}

function enviarIvrs($id_carga,$id_carga_calendarizado,$finalizar,$pamiClientOptions){
    $fecha_actual = Carbon::now();
    //VERIFICA CALENDARIZACION DE IVRS

    foreach ($id_carga_calendarizado as $k) {
        echo '\nCampana calendarizada: '.$k->id_carga;
        $fecha_calendarizada = Carbon::parse($k->fecha_inicio_envio);
        if (strtotime($fecha_actual) > strtotime($fecha_calendarizada)) {
            $k->ejecucion = 1;
            $k->save();

            echo "\n" . $k->id_carga . " - " . $fecha_actual . " - ";
            echo "es mayor";
            echo "\n" . $k->fecha_inicio_envio;
        } else {
            $k->ejecucion = 0;
            $k->save();

            echo "\n" . $k->id_carga . " - " . $fecha_actual . " - ";
            echo "es menor";
            echo "\n" . $k->fecha_inicio_envio;
        }
    }
    //FIN VERIFICA CALENDARIZACION DE IVRS

    sleep(1);

    $canales = canales::first();

    echo "\nid carga activos" . $id_carga->count();
    echo "\ncanales: " . $canales->canales . "\n";
    echo "\ncampañas play: " . $id_carga->count();

    //$id_carga = id_carga::where('estado', 1)->where('estado_aprobado', 1)->where('ejecucion', 1)->get();
    balanceoCanales($canales);

    if ($id_carga->count() > 0) {
        for ($j = 1; $j <= 1; $j++) {
            $maximo = 1;
            //
            echo "\nj:" . $j;
            foreach ($id_carga as $k) {
                echo "\nid_carga: " . $k->id_carga . "\n";
                $cantidad_s = (int)$k->canales;
                echo "\ncantidad de canales de la campaña : " . $cantidad_s;
                $carga = carga::where('id_carga', $k->id_carga)->where('estado', 1)->where('estado_seleccionado', 0)->limit($cantidad_s)->get();
                if (count($carga) == 0) {
                    $cantidad_s =
                    $carga = carga::where('id_carga', $k->id_carga)->where('estado', 1)->where('estado_seleccionado', 1)->limit($cantidad_s)->get();
                }
                foreach ($carga as $c){
                    $c->estado_seleccionado=1;
                    $c->save();
                }
                echo "\ncarga tiene un total de: ".count($carga);

                echo "\ntotal clientes: " . count($carga) . "\n";
                if (count($carga) > 0) {
                    $count = 0;
                    foreach ($carga as $key) {
                        $count++;
                        //$actualizar=carga::where('estado',0)->first();
                        if ($canales->canales > 30) {
                            //mayor a 30 canales envia cada segundo
                            sleep(1);
                        } else {
                            //menos a 30 canales envia cada 2 segundos
                            sleep(2);
                        }

                        //$script=scripts::where('id_script',$key['id_script'])->first();
                        //echo $maximo." - ".$key->id." - ".$script->script." -- \n";
                        //$maximo++;
                        $script = scripts::where('id_script', $key['id_script'])->first();
                        echo "\n" . $script->script;
                        echo "\nCount: " . $count."\n";


                        $pamiClient = new PamiClient($pamiClientOptions);
                        try {
                            $pamiClient->open();
                        } catch (\Exception $e) {
                            print "Error PAMI OPEN exception : " . $e->getMessage();
                        }


                        if ($key['tiposcript'] == 1) {

                            $channel = 'Local/' . trim($key['telefono']) . '@from-internal';
                            $id_var = scripts::where('id_script', $key['id_script'])->first()->id_var;
                            echo "\nInstancio el Originate.\n";
                            try {
                                $originateMsg = new OriginateAction($channel);
                            } catch (\Exception $e) {
                                print "Error PAMI exception: " . $e->getMessage();
                            }
                            $originateMsg->setExtension('6001');
                            $originateMsg->setContext('app-ivrplay2');
                            $originateMsg->setTimeout('30000');
                            $originateMsg->setCallerId('1000');
                            $originateMsg->setVariable('VAR', $id_var);
                            $originateMsg->setVariable('ID_CARGA', $key->id_carga);
                            $id_campania_sis_ges=$key->cargaIvr->id_campania_sis_gest ? $key->cargaIvr->id_campania_sis_gest : 0;
                            $originateMsg->setVariable('ID_CAMPANIA_SIS_GEST', $id_campania_sis_ges . ',' . $key->cedula);
                            $originateMsg->setVariable('CEDULA', $key->cedula);
                            $originateMsg->setAsync(true);
                            $originateMsg->setActionID(trim($key['telefono']) . '-' . $id_var);
                            echo "\nEnviar el Originate.\n";
                            try {
                                $response = $pamiClient->send($originateMsg);
                            } catch (\Exception $e) {
                                print "Error PAMI exception send: " . $e->getMessage();
                            }
                            if (!isset($response)){dd("\nOCURRIO UN ERROR EL SERVER PAMI NO DEVOLVIO EL 'RESPONSE'");}


                            if ($response->isSuccess()) {
                                $respuesta = "Ivr estatico Enviado Correctamente, espere unos segundos.!\n";
                                echo $respuesta;
                            } else {
                                $respuesta = "Envío Fallido!\n";
                            }
                            echo "\nRespuesta: " . $respuesta . " - " . $key['tiposcript'] . " - SCRIPT: " . $script;
                            //Log::info("\nCount: ".$count." - Respuesta: ".$respuesta." - ".$key['tiposcript']." - SCRIPT: ".$script);
                        }
                        if ($key['tiposcript'] == 2) {
                            //Seleccionamos el script dinamico creado por el usuario
                            $script = mb_strtolower(scripts::where('id_script', $key['id_script'])->first()->script);
                            //Buscamos las variables dentro de este formato {nombre_variable} y las almacenamos en la variable coincidencias
                            if (preg_match_all('/{([a-z_a-z_0-9])*}/', $script, $coincidencias, PREG_OFFSET_CAPTURE, 0)) {
                                //echo "HAY ".count($coincidencias[0])." COINCIDENCIAS";
                            } else {
                                //echo "NO HAY COINCIDENCIA";
                            }
                            //creo una array var para almacenar los campos de la base de datos
                            $var = array();
                            for ($i = 0; $i < count($coincidencias[0]); $i++) {
                                $var[$i] = $coincidencias[0][$i][0];
                                $var[$i] = substr($var[$i], 1);
                                $var[$i] = substr($var[$i], 0, -1);
                                //reemplazo el nombre de las variables con la palabra estatica variable nombre_variable al ser un demo
                                $script = str_replace($coincidencias[0][$i][0], $key[$var[$i]], $script);
                            }

                            $channel = 'Local/' . trim($key['telefono']) . '@from-internal';
                            try {
                                $originateMsg = new OriginateAction($channel);
                            } catch (\Exception $e) {
                                print "Error PAMI exception: " . $e->getMessage();
                            }
                            $originateMsg->setExtension('1001');
                            $originateMsg->setContext('@from-internal');
                            $originateMsg->setPriority('');
                            $originateMsg->setApplication('PicoTTS');
                            $originateMsg->setData('"' . $script . '",any,es-ES');
                            //$originateMsg->setApplication('agi');
                            //$originateMsg->setData('googletts.agi,"'.$script.'",es');
                            $originateMsg->setTimeout('30000');
                            $originateMsg->setCallerId('1000');
                            $originateMsg->setVariable('ID_CARGA', $key->id_carga);
                            $id_campania_sis_ges=$key->cargaIvr->id_campania_sis_gest ? $key->cargaIvr->id_campania_sis_gest : 0;
                            $originateMsg->setVariable('ID_CAMPANIA_SIS_GEST', $id_campania_sis_ges . ',' . $key->cedula);
                            $originateMsg->setVariable('CEDULA', $key->cedula);
                            $originateMsg->setAsync(true);
                            $originateMsg->setActionID(trim($key['telefono']));
                            echo "\nEnviar el Originate.\n";
                            try {
                                $response = $pamiClient->send($originateMsg);
                            } catch (\Exception $e) {
                                print "Error PAMI exception send: " . $e->getMessage();
                            }
                            if (!isset($response)){dd("\nOCURRIO UN ERROR EL SERVER PAMI NO DEVOLVIO EL 'RESPONSE'");}


                            if ($response->isSuccess()) {
                                $respuesta = "Ivr estatico Enviado Correctamente, espere unos segundos.!\n";
                                echo $respuesta;
                            } else {
                                $respuesta = "Envío Fallido!\n";
                            }
                            echo "\nRespuesta: " . $respuesta . " - " . $key['tiposcript'] . " - SCRIPT: " . $script;
                            //Log::info("\nCount: ".$count." - Respuesta: ".$respuesta." - ".$key['tiposcript']." - SCRIPT: ".$script);
                        }
                        try {
                            $pamiClient->close();
                        } catch (\Exception $e) {
                            print "Error PAMI CLOSE exception : " . $e->getMessage();
                        }

                        //SUBIR GESTION AL SISTEMA DE GESTION
                        if ($key->cargaIvr->campaniaIvr->clienteIvr->id_cliente==11){
                            $id_gestion=subirGestion($key,$key->cargaIvr->id_campania_sis_gest);
                            echo "\nIngreso una gestion ATM: ".$id_gestion."\n";
                        }
                        //FIN SUBIR GESTION AL SISTEMA DE GESTION
                        //INGRESO EL ID DE GESTION RESULTANTE
                        if ($key->cargaIvr->campaniaIvr->clienteIvr->id_cliente==11){
                            $key->id_gestion_original=$id_gestion;
                        }
                        //FIN INGRESO EL ID DE GESTION RESULTANTE
                        $maximo++;
                        $key->estado = 0;
                        $key->save();
                        $idcarga = $key->id_carga;

                    }
                    //Log::info("Envio un ivr de id_carga:".$idcarga);
                }
            }
            if ($canales->canales > 30) {
                //mayor a 30 canales espera 5 segundos
                echo "\nesperando 5 segundos\n";
                sleep(5);
            } else {
                //menos a 30 canales espera 10 segundos
                echo "\nesperando 10 segundos\n";
                sleep(10);
            }
        }
    }
    //FIN CRON LLAMADAS IVR


    /*
    $cantidad_s=0;
    $cantidad_m=$cantidad_s*2;
    $carga=carga::where('estado',1)->limit($cantidad_m)->get();
    $maximo=0;
    if (count($carga)>0){
        foreach ($carga as $k) {
        //$actualizar=carga::where('estado',0)->first();
        if ($maximo==$cantidad_s){
            $maximo=0;
            sleep(25);
        }


        sleep(1);
        $script=scripts::where('id_script',$k['id_script'])->first();

        $pamiClient = new PamiClient($pamiClientOptions);
        $pamiClient->open();
        if ($k['tiposcript']== 1) {
            $channel = 'Local/'.$k['telefono'].'@from-internal';
            $id_var=scripts::where('id_script',$k['id_script'])->first()->id_var;
            $originateMsg = new OriginateAction($channel);
            $originateMsg->setExtension('6001');
            $originateMsg->setContext('app-ivrplay2');
            $originateMsg->setTimeout('20000');
            $originateMsg->setCallerId('1000');
            $originateMsg->setVariable('VAR', $id_var);
            $originateMsg->setVariable('ID_CARGA',$k->id_carga);
            $originateMsg->setAsync(true);
            $originateMsg->setActionID($k['telefono'] . '-' .$id_var);
            $response = $pamiClient->send($originateMsg);
            if ($response->isSuccess()) {
                $respuesta = "Enviado Correctamente, espere unos segundos.!\n";
            } else {
                $respuesta = "Envío Fallido!\n";
            }
            Log::info($k['tiposcript']." - SCRIPT: ".$script);
        }
        if ($k['tiposcript']== 2) {
            //Seleccionamos el script dinamico creado por el usuario
            $script=mb_strtolower(scripts::where('id_script',$k['id_script'])->first()->script);
            //Buscamos las variables dentro de este formato {nombre_variable} y las almacenamos en la variable coincidencias
            if (preg_match_all('/{([a-z_a-z_0-9])*}/',$script,$coincidencias,PREG_OFFSET_CAPTURE, 0))
            {
                //echo "HAY ".count($coincidencias[0])." COINCIDENCIAS";
            }else
            {
                //echo "NO HAY COINCIDENCIA";
            }
            //creo una array var para almacenar los campos de la base de datos
            $var=array();
            for ($i=0;$i<count($coincidencias[0]);$i++){
                $var[$i]=$coincidencias[0][$i][0];
                $var[$i]=substr($var[$i],1);
                $var[$i]=substr($var[$i],0,-1);
                //reemplazo el nombre de las variables con la palabra estatica variable nombre_variable al ser un demo
                $script=str_replace($coincidencias[0][$i][0],$k[$var[$i]], $script);
            }
            $channel = 'Local/' . $k['telefono'] . '@from-internal';
            $originateMsg = new OriginateAction($channel);
            $originateMsg->setExtension('1001');
            $originateMsg->setContext('@from-internal');
            $originateMsg->setPriority('');
            $originateMsg->setApplication('PicoTTS');
            $originateMsg->setData('"'.$script.'",any,es-ES');
            //$originateMsg->setApplication('agi');
            //$originateMsg->setData('googletts.agi,"'.$script.'",es');
            //$originateMsg->setTimeout('20000');
            $originateMsg->setCallerId('1000');
            $originateMsg->setVariable('ID_CARGA',$k->id_carga);
            $originateMsg->setAsync(true);
            $originateMsg->setActionID($k['telefono']);
            $response = $pamiClient->send($originateMsg);
            if ($response->isSuccess()) {
                $respuesta = "Enviado Correctamente!\n";
            } else {
                $respuesta = "Envío Fallido!\n";
            }
            Log::info($k['tiposcript']." - SCRIPT: ".$script);
        }
        //cambio de estado a cada número enviado
        //$idcarga=carga::find($k['id']);
        //$idcarga->estado=0;
        //$idcarga->save();
        $pamiClient->close();

        $maximo++;
            $k->estado=0;
            $k->save();
        $id_carga=$k->id_carga;
    }
        Log::info("Envio un ivr de id_carga:".$id_carga);
    }
    //return "Lista enviada correctamente";
    */
}

function subirGestion($key,$campana){
    //$account=tbl_accounts::where('identifier',$key['cedula'].'->'.$key['cuenta'])->where('campaign_id',578)->first();

    $account=tbl_accounts::where('identifier',$key['cedula'])->where('campaign_id',$campana)->first();
    $script=tbl_script::where('id_script',$key['id_script'])->first();

    if (count($account)>0){
        $gestion=new tbl_demarches();
        $gestion->account_id=$account->id;
        $gestion->document=$account->target_document;
        $gestion->agent_id=null;
        $gestion->agent='DIEGO BLADIMIR ARMIJOS CAJAMARCA';
        $gestion->executive_id=9;
        $gestion->phone=$key['telefono'];
        $gestion->address=null;
        $gestion->weight='';
        $gestion->action='ENVIAR IVR';
        $gestion->reason=null;
        $gestion->description=$script->script_gestion;
        $gestion->original_demarche='';
        $gestion->validated=1;
        $gestion->contact_type='';
        $gestion->extra='{}';
        $gestion->images='{}';
        $gestion->cex_date=date('Y-m-d 00:00:00');
        $gestion->signature=null;
        $gestion->last_user=null;
        $gestion->major_user=null;
        $gestion->uniqueid=null;
        $gestion->sub_action='';
        $gestion->sub_reason=null;
        $gestion->tlc_time=null;
        $gestion->cex_time=date('H:i:s');
        $gestion->type='DM';
        $gestion->sent_status=0;
        $gestion->discarded=0;
        $gestion->weight='102';
        $gestion->contact_type='NC';
        $gestion->sub_action='NO CONTESTA IVR';
        try{
            $gestion->save();
        } catch (\Exception $e) {
            return $e->getMessage() . "\n";
        }

        $account->cex_weight='102';
        $account->cex_weight_type='NC';
        $account->cex_weight_date=date('Y-m-d');
        $account->demarche_cex_count=$account->demarche_cex_count+1;
        try{
            $account->save();
        } catch (\Exception $e) {
            return $e->getMessage() . "\n";
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://ncobefecapp.cobefec.com/apoyo/gestiones/gestion/".$gestion->id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        try{
            $response = curl_exec($curl);
        } catch (\Exception $e) {
            return "Error CURL: ".$e->getMessage() . "\n";
        }
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }
        return $gestion->id;
    }

}

function subirGestion2($id_gestion,$estado){
    //$account=tbl_accounts::where('identifier',$key['cedula'].'->'.$key['cuenta'])->where('campaign_id',578)->first();

    $gestion=tbl_demarches::find($id_gestion);
    $cuenta=tbl_accounts::find($gestion->account_id);

    if (count($gestion)>0)
    {

        if ($estado=='ANSWERED'){
            $gestion->weight='302';
            $gestion->contact_type='CD';
            $gestion->sub_action='CONTESTA IVR';

            $cuenta->cex_weight='302';
            $cuenta->cex_weight_type='CD';
            $cuenta->cex_weight_date=date('Y-m-d');
            $cuenta->demarche_cex_count=$cuenta->demarche_cex_count+1;
        }else{
            $gestion->weight='102';
            $gestion->contact_type='NC';
            $gestion->sub_action='NO CONTESTA IVR';

            $cuenta->cex_weight='102';
            $cuenta->cex_weight_type='NC';
            $cuenta->cex_weight_date=date('Y-m-d');
            $cuenta->demarche_cex_count=$cuenta->demarche_cex_count+1;
        }
        try{
            $gestion->save();
            $cuenta->save();
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }
        /*$curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://ncobefecapp.cobefec.com/apoyo/gestiones/gestion/".$gestion->id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }*/
    }
}

function subirGestionesPorCampana($id_campana,$id_carga){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://ncobefecapp.cobefec.com/apoyo/gestiones/gestion/".$id_campana,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 30000,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            // Set Here Your Requesred Headers
            'Content-Type: application/json',
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "\nExiste error  #: ".$err." ".date('Y-m-d H:i:s');
        return "cURL Error #:" . $err;
    } else {
        $idCarg=id_carga::find($id_carga);
        $idCarg->estado_indexado=1;
        $idCarg->save();
        return json_decode($response);
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://ncobefecapp.cobefec.com/apoyo/gestiones/gestiones/".$id_campana,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 30000,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            // Set Here Your Requesred Headers
            'Content-Type: application/json',
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "\nExiste error  #: ".$err." ".date('Y-m-d H:i:s');
        return "cURL Error #:" . $err;
    } else {
        $idCarg=id_carga::find($id_carga);
        $idCarg->estado_indexado=1;
        $idCarg->save();
        return json_decode($response);
    }
}

function reSubirGestionesPorCampanaIvr($idCarga){
    reSubirGestionesPorCampanaIvr(6232);
    dd('fin fix');
    //$account=tbl_accounts::where('identifier',$key['cedula'].'->'.$key['cuenta'])->where('campaign_id',578)->first();
    $campana=id_carga::find($idCarga);
    $script=tbl_script::where('id_campania',$campana->id_campania)->first();
    $cargas=carga::where('id_carga',$idCarga)->whereNull('id_gestion_original')->get();

    $script=tbl_script::where('id_script',$script->id_script)->first();
    $count=0;
    foreach ($cargas as $carga){
        $account=tbl_accounts::where('identifier',$carga->cedula)->where('campaign_id',$campana->id_campania_sis_gest)->first();

        if (count($account)>0){
        $count++;
        $gestion=new tbl_demarches();
        $gestion->account_id=$account->id;
        $gestion->document=$account->target_document;
        $gestion->agent_id=null;
        $gestion->agent='DIEGO BLADIMIR ARMIJOS CAJAMARCA';
        $gestion->executive_id=9;
        $gestion->phone=$carga->telefono;
        $gestion->address=null;
        $gestion->weight='';
        $gestion->action='ENVIAR IVR';
        $gestion->reason=null;
        $gestion->description=$script->script_gestion;
        $gestion->original_demarche='';
        $gestion->validated=1;
        $gestion->contact_type='';
        $gestion->extra='{}';
        $gestion->images='{}';
        $gestion->cex_date=date('Y-m-d 00:00:00');
        $gestion->signature=null;
        $gestion->last_user=null;
        $gestion->major_user=null;
        $gestion->uniqueid=null;
        $gestion->sub_action='';
        $gestion->sub_reason=null;
        $gestion->tlc_time=null;
        $gestion->cex_time='00:00:00';
        $gestion->type='DM';
        $gestion->sent_status=0;
        $gestion->discarded=0;
        $gestion->weight='102';
        $gestion->contact_type='NC';
        $gestion->sub_action='NO CONTESTA IVR';
        $gestion->created_at=date('Y-m-d H:i:s');
        try{
            $gestion->save();
        } catch (\Exception $e) {
            return $e->getMessage() . "\n";
        }

        $account->cex_weight='102';
        $account->cex_weight_type='NC';
        $account->cex_weight_date='2019-06-03';
        $account->demarche_cex_count=$account->demarche_cex_count+1;
        try{
            $account->save();
        } catch (\Exception $e) {
            return $e->getMessage() . "\n";
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://ncobefecapp.cobefec.com/apoyo/gestiones/gestion/".$gestion->id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }
            $carga->id_gestion_original=$gestion->id;
            $carga->save();

        //return $gestion->id;
    }
    }
    echo $count;

}

function balanceoCanales($canales){
    //Balanceo los canales sobrantes sobre los demas ivrs activos y ejecutandose
    $id_carga=id_carga::where('estado',1)->where('estado_aprobado',1)->where('ejecucion',1)->get();
    if ($id_carga->count() > 1) {
        $total_canales = 0;
        foreach ($id_carga as $k) {
            echo "\nid_carga " . $k->id_carga;
            $total_canales = $total_canales + $k->canales;
            if ($total_canales > $canales->canales) {
                $k->canales = 0;
                $k->save();
            }
        }

        echo "\ntotal canales de los id_carga: " . $total_canales;
        echo "\ntotal canales disponibles para ivrs: " . $canales->canales;
        echo "\ntotal id_carga: " . $id_carga->count();


        //comentar solo para peru desde ecuador
        foreach ($id_carga as $k) {
            if (intval($k->canales) > 0 && intval($k->canales) < 10) {
                $k->canales = 0;
                $k->save();
            }
        }
        //comentar solo para peru desde ecuador

        if ($total_canales == $canales->canales) {
            $total_canales = $total_canales - 1;
        }

        if ($total_canales < $canales->canales) {
            echo "\nentro al if total canales <= canales";
            $total_canales = intval($canales->canales);

            //sirve para dividir los canales para todas las campañas activas -> sirve para Perú desde ecuador
            //$acanales=intval(floor($canales->canales/$id_carga->count()));

            //comentar solo para peru desde ecuador
            //valida que el total de canales a distribuir sea igual o mayor a 10 canales por campaña
            $total_campanias = $id_carga->count();
            do {
                $acanales = intval(floor($canales->canales / $total_campanias));
                $total_campanias = $total_campanias - 1;
                echo "\nacanales: " . $acanales;
            } while ($acanales < 10);
            //comentar solo para peru desde ecuador

            foreach ($id_carga as $k) {
                if ($acanales <= $total_canales) {
                    echo "\nid carga por recibir canales: " . $k->id_carga;
                    echo " - canales a recibir : " . $acanales;
                    $k->canales = $acanales;
                    $k->save();
                }
                $total_canales = $total_canales - $acanales;
                echo "\ntotal_canales: " . $total_canales;
            }

            //Si sobran canales le sumo a la primera campaña de la lista
            if ($total_canales > 0) {
                $id_final = $id_carga->first();
                $id_final->canales = $id_final->canales + $total_canales;
                $id_final->save();
            }
        }

    } elseif ($id_carga->count() == 1) {
        echo "\nentro al else";
        $icarga = id_carga::where('estado', 1)->where('estado_aprobado', 1)->where('ejecucion', 1)->first();
        $icarga->canales = $canales->canales;
        $icarga->save();
    }
    //Fin balanceo de canales
}