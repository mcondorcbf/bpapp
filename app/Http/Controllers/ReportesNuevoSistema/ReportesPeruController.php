<?php

namespace App\Http\Controllers\ReportesNuevoSistema;

use App\reportesNuevoSistema\tbl_accounts;
use App\reportesNuevoSistema\tbl_brands;
use App\reportesNuevoSistema\tbl_campaigns;
use App\reportesNuevoSistema\tbl_campaigns_peru;
use App\reportesNuevoSistema\tbl_products;
use App\reportesNuevoSistema\tbl_products_peru;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ReportesPeruController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function reportes()
    {
        try{
            //$marcas=tbl_brands::get();
            $marcas=DB::connection('cobefec3Peru')->select("SELECT id,name FROM cobefec3.brands where deleted_at is null and enabled=1;");
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
        $productos_belcorp=tbl_products_peru::where('brand_id',3)->where('id','<>',3)->where('enabled',1)->whereNull('deleted_at')->orderBy('id','DESC')->get();
        $productos_mfinancieras=tbl_products_peru::where('brand_id',1)->where('enabled',1)->whereNull('deleted_at')->orderBy('id','DESC')->get();

        return view('reporteNuevoSistema/peru/index', compact('marcas','productos_belcorp','productos_mfinancieras'));
    }

    public function gCuentasBelcorp(Request $request)
    {
        $campana=$request->id_campana;
        $cuentash='';


        set_time_limit(0);

        try {
            if ($request->cuentasInhabilitadas==1){
                $query = "select count(*) total from accounts where campaign_id in (".$campana.");";
                $sql = DB::connection('cobefec3Peru')->select($query);
                $cuentas=$sql[0]->total;

                $query = "select count(*) as count
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and d.sent_status=0
        and c.id in (".$campana.")
        ;";
                $sql = DB::connection('cobefec3Peru')->select($query);
                $gestiones=$sql[0]->count;
            }else{
                $query = "select count(*) total from accounts where campaign_id in (".$campana.") and enabled=1;";
                $sql = DB::connection('cobefec3Peru')->select($query);
                $cuentas=$sql[0]->total;

                $query = "select count(*) as count
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and d.sent_status=0 and c.enabled=1
        and c.id in (".$campana.") 
        ;";
                $sql = DB::connection('cobefec3Peru')->select($query);
                $gestiones=$sql[0]->count;
            }
            return response()->json(['cuentas' => $cuentas, 'gestiones' => $gestiones]);
        } catch (\Exception $e) {
            return response()->json('Ocurrio un error: ' . $e->getMessage(), 500);
        }
    }

    //rConsolidadoPagoSemanalMensual
    public function generalCuentasBelcorp(Request $request)
    {

        set_time_limit(0);
        ini_set ( 'memory_limit' , '2048M' );
        ini_set('max_execution_time', 800);

        $producto=tbl_products_peru::find($request->id_producto);
        $campana=tbl_campaigns_peru::find($request->id_campana1);

        if ($producto->id==13 || $producto->id==14){
            $query="call cobefec_reportes.sp_bc_incons_cbvendido_gcuentas(".$request->id_producto.",".$request->id_campana1.");";
        }elseif($producto->id==12){
            $query="call cobefec_reportes.sp_bc_cobrando_juntos(".$request->id_campana1.");";
        }else{
            return "La campaña ".$campana->name." no dispone de General de cuentas.";
        }
        try {
            $sql=DB::connection('cobefec3Peru')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes = json_decode(json_encode($sql), true);

        try{
            \Excel::create('GENERAL DE CUENTAS '.$producto->name.' - '.$campana->name.' COBEFEC '.date('d-m-Y'), function($excel) use (&$reportes){
                $excel->sheet('GENERAL DE CUENTAS', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function generalCuentasFinancieras(Request $request)
    {
        set_time_limit(0);
        ini_set ( 'memory_limit' , '2048M' );
        ini_set('max_execution_time', 800);

        $producto=tbl_products_peru::find($request->id_producto);
        $campana=tbl_campaigns_peru::find($request->id_campana2);

        if ($producto->id==5){
            $query="call cobefec_reportes.sp_cuentas_diners_judicial(".$request->id_campana2.");";
        }elseif ($producto->id==6){
            $query="call cobefec_reportes.sp_bco_finan_sobregiro(".$request->id_campana2.");";
        }elseif ($producto->id==7){
            $query="call cobefec_reportes.sp_cuentas_diners_vigente(".$request->id_campana2.");";
        }else{
            return "La campaña ".$campana->name." no dispone de General de cuentas.";
        }
        try {
            $sql=DB::connection('cobefec3Peru')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes = json_decode(json_encode($sql), true);

        try{
            \Excel::create('GENERAL DE CUENTAS '.$producto->name.' - '.$campana->name.' COBEFEC '.date('d-m-Y'), function($excel) use (&$reportes){
                $excel->sheet('GENERAL DE CUENTAS', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }


    public function getProductoPe(Request $request){
        try{
            $productos=tbl_products_peru::where('brand_id',$request->id_marca)->where('enabled',1)->whereNull('deleted_at')->pluck("name","id")->all();
            $data= view('reporteNuevoSistema/diners/marcas/ajax-select-producto',compact('productos'))->render();
            return response()->json(['options'=>$data]);
        }catch (\Exception $e) {
            return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
        }
    }

    public function getCampanaPe(Request $request){
        try{
            $campanas=tbl_campaigns_peru::where('product_id',$request->id_producto)->where('enabled',1)->whereNull('deleted_at')->orderBy('id','DESC')->pluck("name","id")->all();

            $data= view('reporteNuevoSistema/diners/marcas/ajax-select-campana',compact('campanas'))->render();
            return response()->json(['options'=>$data]);

        }catch (\Exception $e) {
            return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
        }
    }
}