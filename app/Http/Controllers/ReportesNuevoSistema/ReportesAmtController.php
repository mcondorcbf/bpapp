<?php
namespace App\Http\Controllers\reportesNuevoSistema;

use App\reportesNuevoSistema\tbl_campaigns;
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