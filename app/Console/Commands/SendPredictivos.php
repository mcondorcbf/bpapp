<?php

namespace App\Console\Commands;
use App\predictivos\prd2_campaign as campaign;
use Illuminate\Console\Command;
use App\predictivos\prd2_call_center as predictivo2;
use App\tbl_predictivos as predictivos;
use Carbon\Carbon;
use App\tbl_control_llamadas as control;

class SendPredictivos extends Command
{
    protected $signature = 'predictivo:insistir';

    protected $description = 'Insiste las llamadas tomando como parametros el tiempo de insistencia y el intervalo de agendamiento';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        /*-------------------Cron job----------------------*/
        $maximo=1;
        $predictivos= predictivos::where('estado',1)->orderBy('id','DESC')->first();

        while($maximo<=2){
            sleep(30);
            if (count($predictivos)>0) {
                $campania = campaign::where('id', $predictivos->campania)->orderBy('id', 'desc')->first();
                $hora1 = strtotime($campania->daytime_end);
                $hora2 = strtotime(date('H:i:s'));
                if ($hora1 > $hora2 and $campania->estatus == 'A') {
                    $result = predictivo2::where('id_campaign', $predictivos->campania)->whereNotNull('fecha_llamada')->where('status', '<>', 'Success');
                    $result2 = predictivo2::where('id_campaign', $predictivos->campania)->whereNotNull('fecha_llamada')->whereNull('status')->unionAll($result)->get();

                    foreach ($result2 as $k) {
                        $llamada = predictivo2::where('id', $k->id)->first();
                        $llamada->date_init = date('Y-m-d');
                        $llamada->date_end = date('Y-m-d');

                        $fecha = date($k->fecha_llamada);
                        $hora = date("H", strtotime($fecha));
                        $min = date("i", strtotime($fecha));
                        $seg = date("s", strtotime($fecha));

                        $fecha = new Carbon();
                        $fecha = $fecha->setTime($hora, $min, $seg);

                        $control = control::where('id_call', $llamada->id)->where('estado', 1)->first();
                        if (count($control) == 0) {
                            $control = new control();
                            $control->id_call = $llamada->id;
                            $control->fecha_llamada = $llamada->fecha_llamada;
                            $control->id_campaign = $llamada->id_campaign;
                            $control->estado = 1;
                            $control->nllamadas = 1;
                            $control->save();

                            if ($predictivos->agendamiento == 'minutos') {
                                $llamada->time_init = $fecha->addMinute($predictivos->tiempo)->format('H:i:s');
                                $llamada->time_end = $fecha->addMinute($predictivos->tiempo_insistencia)->format('H:i:s');
                                $llamada->save();
                            }
                            if ($predictivos->agendamiento == 'horas') {
                                $llamada->time_init = $fecha->addHours($predictivos->tiempo)->format('H:i:s');
                                $llamada->time_end = $fecha->addMinute($predictivos->tiempo_insistencia)->format('H:i:s');
                                $llamada->save();
                            }
                        }elseif(count($control)>0){
                            if ($llamada->id == $control->id_call && $llamada->fecha_llamada != $control->fecha_llamada) {
                                $control->fecha_llamada = $llamada->fecha_llamada;
                                $control->nllamadas = $control->nllamadas + 1;
                                $control->save();
                                if ($predictivos->agendamiento == 'minutos') {
                                    $llamada->time_init = $fecha->addMinute($predictivos->tiempo)->format('H:i:s');
                                    $llamada->time_end = $fecha->addMinute($predictivos->tiempo_insistencia)->format('H:i:s');
                                    $llamada->save();
                                }
                                if ($predictivos->agendamiento == 'horas') {
                                    $llamada->time_init = $fecha->addHours($predictivos->tiempo)->format('H:i:s');
                                    $llamada->time_end = $fecha->addMinute($predictivos->tiempo_insistencia)->format('H:i:s');
                                    $llamada->save();
                                }
                            }
                        }
                        //CAMBIAR A MODO PREDICTIVO LOS NUMEROS SIMILARES O MENORES A 3 MINUTOS

                    }
                } else {
                    predictivos::where('estado', 1)->update(['estado' => 0]);
                }
            }
            $maximo++;
        }
        /*----------------Fin Cron Job---------------*/
    }
}