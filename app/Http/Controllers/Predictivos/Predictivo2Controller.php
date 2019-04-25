<?php

namespace App\Http\Controllers\Predictivos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\predictivos\prd2_call_center as predictivo2;
use App\predictivos\prd2_campaign as campaign;
use App\tbl_predictivos as predictivos;
use App\tbl_control_llamadas as control;
use Carbon\Carbon;

class Predictivo2Controller extends Controller
{
    public function index()
    {
        $campanias=campaign::where('estatus','A')->get();
        return view('predictivos.predictivo',compact('campanias'));
    }

    public function procesarPredictivo(Request $request)
    {

        try {
            $user = Auth::user();
            $campania=campaign::where('id',$request->campania)->first();
            $hora1 = strtotime($campania->daytime_end);
            $hora2 = strtotime(date('H:i:s'));
            if( $hora1 > $hora2 and $campania->estatus=='A') {
                predictivos::where('estado', 1)->update(['estado' => 0]);
                $predictivo = new predictivos();
                $predictivo->campania = $request->campania;
                $predictivo->agendamiento = $request->agendamiento;
                $predictivo->tiempo = $request->tiempo;
                $predictivo->tiempo_insistencia = $request->tiempo_insistencia;
                $predictivo->id_usuario = $user->id;
                $predictivo->usuario = $user->name;
                $predictivo->estado = 1;
                $predictivo->save();

                $mensaje='Configuración exitosa.';
            } else {
                $mensaje='Al parecer la campaña '.$request->campania.' esta desactivada o se encuentra fuera del horario: '.$campania->daytime_end;
            }
        }catch (Exception $e) {
            Log::info("Exception bdd predictivo: " . $e->getMessage());
        }




        return view('predictivos.predictivo2', compact('mensaje'));



        //dd($predictivo);
        /*$result=predictivo2::where('id_campaign',$request->campania)->whereNotNull('fecha_llamada')->where('status','<>','Success');
        $result2=predictivo2::where('id_campaign',$request->campania)->whereNotNull('fecha_llamada')->whereNull('status')->unionAll($result)->get();

        foreach ($result2 as $k){
            $llamada= predictivo2::where('id',$k->id)->first();
            $llamada->date_init=date('Y-m-d');
            $llamada->date_end=date('Y-m-d');

            $fecha = date($k->end_time);
            $hora = date("H", strtotime($fecha));
            $min = date("i", strtotime($fecha));
            $seg = date("s", strtotime($fecha));

            $fecha = new Carbon();
            $fecha= $fecha->setTime($hora,$min,$seg);

            if($request->agendamiento=='minutos'){
                $llamada->time_init=$fecha->addMinute($request->tiempo)->format('h:i:s');
                $llamada->time_end=$fecha->addMinute($request->tiempo_insistencia)->format('h:i:s');
                $llamada->save();
            }
            if($request->agendamiento=='horas'){
                $llamada->time_init=$fecha->addHours($request->tiempo)->format('h:i:s');
                $llamada->time_end=$fecha->addMinute($request->tiempo_insistencia)->format('h:i:s');
                $llamada->save();
            }
        }*/
        return $predictivo->count();
    }
}