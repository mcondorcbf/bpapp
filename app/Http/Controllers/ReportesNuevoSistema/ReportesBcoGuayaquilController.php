<?php

namespace App\Http\Controllers\ReportesNuevoSistema;

use App\reportesNuevoSistema\tbl_accounts;
use App\reportesNuevoSistema\tbl_brands;
use App\reportesNuevoSistema\tbl_campaigns;
use App\reportesNuevoSistema\tbl_products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ReportesBcoGuayaquilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reportes()
    {
        try{
            //$marcas=tbl_brands::get();
            $marcas=DB::connection('cobefec3')->select("SELECT id,name FROM cobefec3.brands where id=5 and deleted_at is null and enabled=1;");
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
        return view('reporteNuevoSistema/bcoGuayaquil/index', compact('marcas'));
    }
    //rConsolidadoPagoSemanalMensual
    public function rSemaforo(Request $request)
    {
        set_time_limit(0);

        $query="select a.target_document Cedula, a.identifier Cuenta, a.data ->> '$.nombre' Nombre, a.to_recover Deuda_inicial, a.recovered Deuda_actual,
ifnull((SELECT substr(u1.email,1,locate('@',u1.email)-1) FROM cobefec3.accounts a1, cobefec3.agents ag1, cobefec3.users u1 where ag1.id=a1.current_agent and u1.id=ag1.user_id and a1.id=a.id),'SIN ASIGNAR') Gestor, 
a.last_weight_date FG, 
 (select if(d.contact_type='NC','No Contactado',if(d.contact_type='CD','Contacto Directo',if(d.contact_type='CI','Contacto Indirecto',''))) Tipo
from cobefec3.demarches d where d.account_id=a.id and date(d.created_at)=a.last_weight_date order by d.id desc limit 1) Tipo,
(select d.action from cobefec3.demarches d where d.account_id=a.id and date(d.created_at)=a.last_weight_date order by d.id desc limit 1) Accion,
(select ifnull(d.reason,'') from cobefec3.demarches d where d.account_id=a.id and date(d.created_at)=a.last_weight_date order by d.id desc limit 1) Motiv,
(select ifnull(d.sub_reason,'') from cobefec3.demarches d where d.account_id=a.id and date(d.created_at)=a.last_weight_date order by d.id desc limit 1) Submotiv,
(select d.description from cobefec3.demarches d where d.account_id=a.id and date(d.created_at)=a.last_weight_date order by d.id desc limit 1) Observac,
ifnull(a.pp_date,'') FCompr, ifnull(a.pp_amount,'') Vproyec,
a.data ->> '$.minimopagar' PagoMinimo, a.data ->> '$.montovencido' MontoVencido, a.data ->> '$.prioridad_1' Prioridad, a.data ->> '$.pago' Abonos, a.data ->> '$.dctoautorizado' DctoAutorizado, a.enabled Estado
,a.last_weight,a.last_weight_type,a.last_weight_date
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id
and a.campaign_id=".$request->id_campana.";
";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes = json_decode(json_encode($sql), true);
        $i=0;
        foreach($reportes as $reporte) {
            $reportes[$i]['Deuda_actual']=round($reporte['Deuda_actual'],2);
            $reportes[$i]['Deuda_inicial']=round($reporte['Deuda_inicial'],2);
            $reportes[$i]['PagoMinimo']=round($reporte['PagoMinimo'],2);
            $reportes[$i]['Abonos']=round($reporte['Abonos'],2);
            $reportes[$i]['MontoVencido']=round($reporte['MontoVencido'],2);

            if($reportes[$i]['Estado']==1){
                $reportes[$i]['Estado']='Habilitado';
            }elseif($reportes[$i]['Estado']==0){
                $reportes[$i]['Deuda_actual']=0;
                $reportes[$i]['Estado']='Deshabilitado';
            }

            if($reportes[$i]['Tipo']==''){
                $query_t="select if(d1.contact_type='NC','No Contactado',if(d1.contact_type='CD','Contacto Directo',if(d1.contact_type='CI','Contacto Indirecto',''))) tipo, d1.action accion, d1.reason motivo,
d1.sub_reason submotivo,d1.description observacion
from cobefec3.brands b1, cobefec3.products p1, cobefec3.campaigns c1, cobefec3.accounts a1, cobefec3.demarches d1
where b1.id=p1.brand_id and p1.id=c1.product_id and c1.id=a1.campaign_id and a1.id=d1.account_id
and b1.id=5 and p1.id=12 and d1.document='".$reportes[$i]['Cedula']."' and d1.weight='".$reportes[$i]['last_weight']."' and d1.contact_type='".$reportes[$i]['last_weight_type']."' 
order by d1.id desc
limit 1
;";
                try {
                    $sql=DB::connection('cobefec3')->select($query_t);
                    $reportes_t = json_decode(json_encode($sql), true);
                    if (count($reportes_t)>0){
                    $reportes[$i]['Tipo']=$reportes_t[0]['tipo'];
                    $reportes[$i]['Accion']=$reportes_t[0]['accion'];
                    $reportes[$i]['Motiv']=$reportes_t[0]['motivo'];
                    $reportes[$i]['Submotiv']=$reportes_t[0]['submotivo'];
                    $reportes[$i]['Observac']=$reportes_t[0]['observacion'];
                    }
                }catch(\Exception $e) {
                    return $e->getMessage();
                }
            }


            $i++;
        }
        $query="select name from cobefec3.campaigns where id=".$request->id_campana.";";
        try{
            $sql=DB::connection('cobefec3')->select($query);
        }catch(\Exception $e){
            return $e->getMessage();
        }
        $campana=$sql[0]->name;
        try{
            \Excel::create('GENERAL DE CUENTAS '.$campana.' COBEFEC '.date('d-m-Y'), function($excel) use (&$reportes){
                $excel->sheet('GENERAL DE CUENTAS', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}