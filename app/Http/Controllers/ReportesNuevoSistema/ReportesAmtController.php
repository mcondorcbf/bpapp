<?php
namespace App\Http\Controllers\reportesNuevoSistema;

use App\reportesNuevoSistema\tbl_campaigns;
use App\reportesNuevoSistema\tbl_products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ReportesAmtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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