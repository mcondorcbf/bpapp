<?php

namespace App\Http\Controllers\Centrales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CentralesController extends Controller
{
    public function index()
    {
        $centrales = array();
        return view('centrales/index', compact('centrales'));
    }

    public function procesar(Request $request)
    {

        ini_set('max_execution_time',0);
        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        $centrales = array();
        if (in_array('0', $request->operadores)) {
            try{
            $query = DB::connection('claro')->select("SELECT 'CLARO' OPERADORA, sum(billsec)/60 MINUTOS FROM cdr c where dstchannel like '%-claro-%' and date(calldate) between '" . $fecha_inicio . "' and '" . $fecha_fin . "' and disposition='ANSWERED'");
            $array = json_decode(json_encode($query), true);
            $centrales = array_merge($centrales, $array);
            }
        catch(PDOException $e){echo(sql_error($e));}

            try{
            $query = DB::connection('claro2')->select("SELECT 'CLARO' OPERADORA, sum(billsec)/60 MINUTOS FROM cdr c where dstchannel like '%-claro-%' and date(calldate) between '" . $fecha_inicio . "' and '" . $fecha_fin . "' and disposition='ANSWERED'");
            $array = json_decode(json_encode($query), true);
            $centrales = array_merge($centrales, $array);
            }
        catch(PDOException $e){echo(sql_error($e));}

            try{
            $query = DB::connection('claro3')->select("SELECT 'CLARO' OPERADORA, sum(billsec)/60 MINUTOS FROM cdr c where dstchannel like '%-claro-%' and date(calldate) between '" . $fecha_inicio . "' and '" . $fecha_fin . "' and disposition='ANSWERED'");
            $array = json_decode(json_encode($query), true);
            $centrales = array_merge($centrales, $array);
            }
        catch(PDOException $e){echo(sql_error($e));}

            try{
            $query = DB::connection('claro4')->select("select 'CLARO' OPERADORA, sum(duration)/60 MINUTOS FROM calls where date(fecha_llamada) between '" . $fecha_inicio . "' and '" . $fecha_fin . "' and trunk like '%-claro-%' and status='Success'");
            $array = json_decode(json_encode($query), true);
            $centrales = array_merge($centrales, $array);
            }
        catch(PDOException $e){echo(sql_error($e));}

            try{
            $query = DB::connection('claro5')->select("SELECT 'CLARO' OPERADORA, sum(billsec)/60 MINUTOS FROM `cdr` where date(calldate) BETWEEN '" . $fecha_inicio . "' and '" . $fecha_fin . "' and dstchannel like '%-claro-%' and disposition='ANSWERED'");
            $array = json_decode(json_encode($query), true);
            $centrales = array_merge($centrales, $array);
            }
        catch(PDOException $e){echo(sql_error($e));}

            $minutos = 0;
            foreach ($centrales as $k) {
                $minutos = $k['MINUTOS'] + $minutos;
            }
            $centrales = array();
            $centrales [0] = [
                'OPERADORA' => 'CLARO',
                'MINUTOS' => $minutos
            ];
        }
        try
        {
        if (in_array('1', $request->operadores)) {
            $query = DB::connection('movistar')->select("SELECT 'MOVISTAR' OPERADORA, sum(billsec)/60 As MINUTOS FROM asteriskcdrdb.cdr where date(calldate) between '".$fecha_inicio."' and '".$fecha_fin."' and disposition ='ANSWERED' and billsec <> 0 and dstchannel like '%@roundrobin%'");
            $array = json_decode(json_encode($query), true);
            $centrales = array_merge($centrales, $array);
        }

        }
        catch(PDOException $e){echo(sql_error($e));}

        try{
        if (in_array('2', $request->operadores)) {
            $query = DB::connection('cnt')->select("SELECT 'CNT' OPERADORA, sum(billsec)/60 MINUTOS FROM cdr c where dstchannel like '%CNT%' and date(calldate) between '".$fecha_inicio."' and '".$fecha_fin."' and disposition='ANSWERED'");

            $array = json_decode(json_encode($query), true);
            $centrales = array_merge($centrales, $array);
        }
        }
        catch(PDOException $e){echo(sql_error($e));}

        try{
        if (in_array('3', $request->operadores)) {
            $query = DB::connection('cnt')->select("SELECT 'SETEL' OPERADORA, sum(billsec)/60 MINUTOS FROM cdr c where dstchannel like '%SETEL%' and date(calldate) between '".$fecha_inicio."' and '".$fecha_fin."' and disposition='ANSWERED'");

            $array = json_decode(json_encode($query), true);
            $centrales = array_merge($centrales, $array);
        }
        }
        catch(PDOException $e){echo(sql_error($e));}

        try{
        if (in_array('4', $request->operadores)) {
            $query = DB::connection('movistarPeru')->select("SELECT 'MOVISTAR PERU' OPERADORA, sum(billsec)/60 MINUTOS FROM asteriskcdrdb.cdr where date(calldate) between '".$fecha_inicio."' and '".$fecha_fin."' and (dstchannel like ('%_MOVISTAR-%') or dstchannel like ('%@roundrobin%') ) and disposition='ANSWERED'");
            $array = json_decode(json_encode($query), true);

            $centrales = array_merge($centrales, $array);
        }
        }
        catch(PDOException $e){echo(sql_error($e));}

        //SELECT * FROM `cdr` where date(calldate) BETWEEN '2017-07-01' and '2017-07-08' and dstchannel like '%-claro-%' and disposition='ANSWERED';

        //SELECT 'MOVISTAR' OPERADORA, sum(billsec)/60 MINUTOS FROM cdr c where dstchannel like '%_MOVISTAR-%' and date(calldate) between '.$fecha_inicio.' and '.$fecha_fin.' and disposition='ANSWERED';

        $tabla='<table class="table"><tr><th>PERIODO</th><th>OPERADORA</th><th>MINUTOS</th></>
';
        foreach ($centrales as $k){
            $tabla.='<tr><td>'.$fecha_inicio.' - '.$fecha_fin.'</td><td>'.$k['OPERADORA'].'</td><td>'.round($k['MINUTOS'],2).'</td></tr>
';
        }
        $tabla.='</table>';

        return $tabla;
    }

}
