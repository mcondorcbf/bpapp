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

class ReportesBelcorpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function reportes()
    {
        try{
            //$marcas=tbl_brands::get();
            $marcas=DB::connection('cobefec3')->select("SELECT id,name FROM cobefec3.brands where id=3 and deleted_at is null and enabled=1;");
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
        return view('reporteNuevoSistema/belcorp/index', compact('marcas'));
    }

    public function gCuentasBelcorp(Request $request)
    {
        if(is_array($request->id_campana)){$campana=implode(',',$request->id_campana);}else{$campana=$request->id_campana;}
        $cuentash='';
        if ($request->cuentasInhabilitadas==1){$cuentash='';}else{$cuentash=' and a.enabled=1 ';}

        set_time_limit(0);

        try {
            if ($request->cuentasInhabilitadas==1){
                $query = "select count(*) total from accounts where campaign_id in (".$campana.");";
                $sql = DB::connection('cobefec3')->select($query);
                $cuentas=$sql[0]->total;

                $query = "select count(*) as count
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and d.sent_status=0
        and c.id in (".$campana.")
        ;";
                $sql = DB::connection('cobefec3')->select($query);
                $gestiones=$sql[0]->count;
            }else{
                $query = "select count(*) total from accounts where campaign_id in (".$campana.") and enabled=1;";
                $sql = DB::connection('cobefec3')->select($query);
                $cuentas=$sql[0]->total;

                $query = "select count(*) as count
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and d.sent_status=0 and c.enabled=1
        and c.id in (".$campana.") 
        ;";
                $sql = DB::connection('cobefec3')->select($query);
                $gestiones=$sql[0]->count;
            }
            return response()->json(['cuentas' => $cuentas, 'gestiones' => $gestiones]);
        } catch (\Exception $e) {
            return response()->json('Ocurrio un error: ' . $e->getMessage(), 500);
        }
    }

    //rConsolidadoPagoSemanalMensual
    public function generalCuentas(Request $request)
    {
        set_time_limit(0);
        ini_set ( 'memory_limit' , '2048M' );
        ini_set('max_execution_time', 800);

        $campana=implode(',',$request->id_campana1);
        $cuentash='';
        if ($request->cuentasInhabilitadas1==1){}else{$cuentash=' and a.enabled=1';}

        $query="select b.name MARCA, a.stage ETAPA, a.data ->> '$.campana' CAMPANA, a.data ->> '$.region' REGION, a.data ->> '$.zona' ZONA, a.data ->> '$.seccion' SECCION, a.target_document CEDULA, a.data ->> '$.nombres' NOMBRES, a.to_recover DEUDA_ASIGNADA, a.recovered DEUDA_PENDIENTE, (a.to_recover-a.recovered) DEUDA_RECUPERADA,
ifnull((SELECT concat(u1.first_name,' ',u1.last_name) FROM cobefec3.accounts a1, cobefec3.agents ag1, cobefec3.users u1 where ag1.id=a1.current_agent and u1.id=ag1.user_id and a1.id=a.id),'SIN ASIGNAR') AGENTE_ACTUAL,
ifnull(a.last_weight_date,'') UG_FECHA_TLC,
ifnull((select tlc_time from cobefec3.demarches where id=a.last_weight_id),'') UG_HORA_TLC,
if(a.last_weight_type is null,'',if(a.last_weight_type='CD','CONTACTO DIRECTO',if(a.last_weight_type='CI','CONTACTO INDIRECTO', 'NO CONTACTADO'))) UG_TIPO,
ifnull((select action from cobefec3.demarches where id=a.last_weight_id),'') UG_ACCION,
ifnull((select sub_action from cobefec3.demarches where id=a.last_weight_id),'') UG_SUBACCION,
ifnull((select description from cobefec3.demarches where id=a.last_weight_id),'') UG_DESCRIPCION,
ifnull((select agent from cobefec3.demarches where id=a.last_weight_id),'') UG_AGENTE,
ifnull((select phone from cobefec3.demarches where id=a.last_weight_id),'') UG_TELEFONO,
ifnull(a.demarche_count,0) '#Gestiones_TLC', 
ifnull((select reason from cobefec3.demarches where id=a.last_weight_id),'') UG_MOTIVO,
ifnull((select sub_reason from cobefec3.demarches where id=a.last_weight_id),'') UG_SUBMOTIVO,
ifnull(a.major_weight_date,'') MG_FECHA_TLC,
ifnull((select tlc_time from cobefec3.demarches where id=a.major_weight_id),'') MG_HORA_TLC,
if(a.major_weight_type is null,'',if(a.major_weight_type='CD','CONTACTO DIRECTO',if(a.major_weight_type='CI','CONTACTO INDIRECTO', 'NO CONTACTADO'))) MG_TIPO,
ifnull((select action from cobefec3.demarches where id=a.major_weight_id),'') MG_ACCION,
ifnull((select sub_action from cobefec3.demarches where id=a.major_weight_id),'') MG_SUBACCION,
ifnull((select description from cobefec3.demarches where id=a.major_weight_id),'') MG_DESCRIPCION,
ifnull((select phone from cobefec3.demarches where id=a.major_weight_id),'') MG_TELEFONO,
ifnull((select agent from cobefec3.demarches where id=a.major_weight_id),'') MG_AGENTE,
a.data ->> '$.movil' MOVIL,
ifnull((select reason from cobefec3.demarches where id=a.major_weight_id),'') MG_MOTIVO,
ifnull((select sub_reason from cobefec3.demarches where id=a.major_weight_id),'') MG_SUBMOTIVO,
a.data ->> '$.n0_de_pedido' N0_PEDIDO, ifnull(a.pp_amount,'') PP_MONTO, ifnull(a.pp_date,'') PP_FECHA, a.data ->> '$.codigo' CODIGO,
a.data ->> '$.correo_electronico' CORREO_ELECTRONICO, a.data ->> '$.dias_de_atraso' DIAS_ATRASO, a.data ->> '$.provincia' PROVINCIA, a.data ->> '$.departamento' DEPARTAMENTO, 
a.data ->> '$.distrito' DISTRITO, a.data ->> '$.direccion.original_address' DIRECCION, if(a.enabled=1,'HABILITADO','DESHABILITADO') ESTADO,
(
select if((select count(distinct contact_type) from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)=1,
if((select distinct contact_type from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)='NC',
(select action from cobefec3.demarches where account_id=a.id and type='DM' and contact_type='NC' and discarded=0 order by id desc limit 1),
(select action from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1)),
(select action from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1))
) CEX_MEJOR_GESTION_ACCION,
(
select if((select count(distinct contact_type) from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)=1,
if((select distinct contact_type from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)='NC',
(select address from cobefec3.demarches where account_id=a.id and type='DM' and contact_type='NC' and discarded=0 order by id desc limit 1),
(select address from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1)),
(select address from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1))
) CEX_MEJOR_GESTION_DIRECCION,
(
select if((select count(distinct contact_type) from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)=1,
if((select distinct contact_type from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)='NC',
(select agent from cobefec3.demarches where account_id=a.id and type='DM' and contact_type='NC' and discarded=0 order by id desc limit 1),
(select agent from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1)),
(select agent from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1))
) CEX_MEJOR_GESTION_AGENTE,
(
select if((select count(distinct contact_type) from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)=1,
if((select distinct contact_type from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)='NC',
(select date(created_at) from cobefec3.demarches where account_id=a.id and type='DM' and contact_type='NC' and discarded=0 order by id desc limit 1),
(select date(created_at) from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1)),
(select date(created_at) from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1))
) CEX_MEJOR_GESTION_FECHA,
(
select if((select count(distinct contact_type) from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)=1,
if((select distinct contact_type from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)='NC',
(select if(contact_type='CD', 'CONTACTO DIRECTO',if(contact_type='CI','CONTACTO INDIRECTO', 'NO CONTACTADO')) from cobefec3.demarches where account_id=a.id and type='DM' and contact_type='NC' and discarded=0 order by id desc limit 1),
(select if(contact_type='CD', 'CONTACTO DIRECTO',if(contact_type='CI','CONTACTO INDIRECTO', 'NO CONTACTADO')) from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1)),
(select if(contact_type='CD', 'CONTACTO DIRECTO',if(contact_type='CI','CONTACTO INDIRECTO', 'NO CONTACTADO')) from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1))
) CEX_MEJOR_GESTION_TIPO,
(
select if((select count(distinct contact_type) from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)=1,
if((select distinct contact_type from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)='NC',
(select description from cobefec3.demarches where account_id=a.id and type='DM' and contact_type='NC' and discarded=0 order by id desc limit 1),
(select description from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1)),
(select description from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1))
) CEX_MEJOR_GESTION_DESCRIPCION,
(
select if((select count(distinct contact_type) from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)=1,
if((select distinct contact_type from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)='NC',
(select reason from cobefec3.demarches where account_id=a.id and type='DM' and contact_type='NC' and discarded=0 order by id desc limit 1),
(select reason from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1)),
(select reason from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1))
) CEX_MEJOR_GESTION_MOTIVO,
(
select if((select count(distinct contact_type) from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)=1,
if((select distinct contact_type from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)='NC',
(select sub_reason from cobefec3.demarches where account_id=a.id and type='DM' and contact_type='NC' and discarded=0 order by id desc limit 1),
(select sub_reason from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1)),
(select sub_reason from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1))
) CEX_MEJOR_GESTION_SUBMOTIVO,
(
select if((select count(distinct contact_type) from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)=1,
if((select distinct contact_type from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)='NC',
(select sub_action from cobefec3.demarches where account_id=a.id and type='DM' and contact_type='NC' and discarded=0 order by id desc limit 1),
(select sub_action from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1)),
(select sub_action from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1))
) CEX_MEJOR_GESTION_SUBACCION,
(
select if((select count(distinct contact_type) from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)=1,
if((select distinct contact_type from cobefec3.demarches where type='DM' and account_id=a.id and discarded=0)='NC',
(select weight from cobefec3.demarches where account_id=a.id and type='DM' and contact_type='NC' and discarded=0 order by id desc limit 1),
(select weight from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1)),
(select weight from cobefec3.demarches where account_id=a.id and type='DM' and discarded=0 order by contact_type asc, weight desc, id desc limit 1))
) CEX_MEJOR_GESTION_PESO, demarche_cex_count GESTIONES_CEX, 
if(a.zone is null and a.zone_id=0,'SIN COBERTURA',if(a.zone is null and a.zone_id is null,'POR ZONIFICAR',a.zone)) COBERTURA
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id
and b.id=3 and c.id in(".$campana.")".$cuentash.";
;
";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes = json_decode(json_encode($sql), true);

        $i=0;
        foreach($reportes as $reporte){
            /*if ($reporte['ESTADO']==0){
                $reportes[$i]['ESTADO']='DESHABILITADO';
            }else{
                $reportes[$i]['ESTADO']='HABILITADO';
            }*/
            $reportes[$i]['DEUDA_ASIGNADA']=round($reporte['DEUDA_ASIGNADA'], 2);
            $reportes[$i]['DEUDA_PENDIENTE']=round($reporte['DEUDA_PENDIENTE'], 2);
            $reportes[$i]['DEUDA_RECUPERADA']=round($reporte['DEUDA_RECUPERADA'], 2);
            $i++;
        }

        if (count($request->id_campana1)==1){
            $query="select name from cobefec3.campaigns where id=".$request->id_campana1[0].";";
            try{
                $sql=DB::connection('cobefec3')->select($query);
            }catch(\Exception $e){
                return $e->getMessage();
            }
            $campana=$sql[0]->name;
        }else{
            $campana='VARIAS CAMPAÃ‘AS';
        }


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

    public function historialGestiones(Request $request)
    {
        set_time_limit(0);
        ini_set ( 'memory_limit' , '2048M' );
        ini_set('max_execution_time', 800);

        $campana=implode(',',$request->id_campana2);
        $cuentash='';
        if ($request->cuentasInhabilitadas2==1){}else{$cuentash=' and a.enabled=1 ';}

        $query="select b.name MARCA, c.name CAMPAÃ‘A, a.data ->> '$.region' REGION, a.data ->> '$.zona' ZONA, a.target_document DOCUMENTO, a.data ->> '$.nombres' NOMBRE_CLIENTE, a.to_recover DEUDA_INICIAL, a.recovered DEUDA_ACTUAL, (a.to_recover - a.recovered) RECUPERACION,
if(d.contact_type='CD','CONTACTO DIRECTO',if(d.contact_type='CI','CONTACTO INDIRECTO','NO CONTACTADO')) CONTACTO_TIPO, d.action TIPO, d.sub_action SUB_TIPO, d.description DESCRIPCION, d.agent AGENTE, d.original_demarche ORIGINAL_GESTION, d.type TIPO_GESTION,
d.tlc_time TLC_HORA, d.contact_type CONTACTO_TIPO_GESTION ,   d.phone TELEFONO, date(d.created_at) CREADO, c.date_init CAMPAÃ‘A_FECHA_INICIO, c.date_end CAMPAÃ‘A_FECHA_FIN, ifnull(d.extra ->> '$.relationship','') RELATIONSHIP, ifnull(d.extra ->> '$.call_again','')  CALL_AGAIN, ifnull(a.pp_date,'') PP_FECHA, ifnull(a.pp_amount,'')  PP_AMOUNT 
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id
and c.id in(".$campana.")".$cuentash.";
";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes = json_decode(json_encode($sql), true);

        $i=0;
        foreach($reportes as $reporte){
            $reportes[$i]['DEUDA_INICIAL']=round($reporte['DEUDA_INICIAL'], 2);
            $reportes[$i]['DEUDA_ACTUAL']=round($reporte['DEUDA_ACTUAL'], 2);
            $reportes[$i]['RECUPERACION']=round($reporte['RECUPERACION'], 2);
            $i++;
        }

        if (count($request->id_campana1)==1){
            $query="select name from cobefec3.campaigns where id=".$request->id_campana2[0].";";
            try{
                $sql=DB::connection('cobefec3')->select($query);
            }catch(\Exception $e){
                return $e->getMessage();
            }
            $campana=$sql[0]->name;
        }else{
            $campana='VARIAS CAMPAÃ‘AS';
        }

        try{
            \Excel::create('HISTORIAL DE GESTIONES '.$campana.' COBEFEC '.date('d-m-Y'), function($excel) use (&$reportes){
                $excel->sheet('HISTORIAL DE GESTIONES', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function recuperacion(Request $request)
    {

        //$campana=implode(',',$request->id_campana3);
        $cuentash='';
        $campana=tbl_campaigns::find($request->id_campana3[0]);
        if ($request->cuentasInhabilitadas3==1){}else{$cuentash=' and a.enabled=1 ';}
        try{DB::connection('cobefec3')->statement("drop table cobefec_reportes.bc_tmp_recuperacion;");

        }catch (\Exception $exception){
            return $exception->getMessage();
        }
        try{DB::connection('cobefec3')->statement("create table cobefec_reportes.bc_tmp_recuperacion
select ifnull((SELECT concat(u1.first_name,' ',u1.last_name) FROM cobefec3.accounts a1, cobefec3.agents ag1, cobefec3.users u1 where ag1.id=a1.current_agent and u1.id=ag1.user_id and a1.id=a.id),'SIN ASIGNAR') gestores,
a.data ->> '$.region' region, count(b.name) no_cuentas, round(sum(a.to_recover),2) asignacion, round(sum(a.recovered),2) pendiente, round(sum(a.to_recover-a.recovered),2) recuperacion,
round((round(sum(a.to_recover-a.recovered),2)*100)/(round(sum(a.to_recover),2)),2) porcentaje_recuperacion
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id
and b.id=3 and p.id=6 and c.id = (".$campana->id.") ".$cuentash."
group by 1,2
;
");

        }catch (\Exception $exception){
            return $exception->getMessage();
        }

        try{
            $reportes = DB::connection('cobefec3')->select("select r.*, (r.porcentaje_recuperacion-(select max(porcentaje_recuperacion) from cobefec_reportes.bc_tmp_recuperacion)) brecha
from cobefec_reportes.bc_tmp_recuperacion r;
");
            $reportes = json_decode(json_encode($reportes),true);
        }catch (\Exception $exception){
            return $exception->getMessage();
        }


        return view('reporteNuevoSistema/belcorp/table/tableRecuperacion', compact('reportes'));

        try{
            \Excel::create('BELCORP REPORTE DE RECUPERACION CAMPANA '.$campana->name." DESCARGADO EL ".date('d-m-Y His'), function($excel) use (&$reportes){
                $excel->sheet('RECUPERACION', function($sheet) use($reportes) {
                    $sheet->loadView('reporteNuevoSistema/belcorp/table/tableRecuperacion')->with('reportes',$reportes);
                });
            })->export('xlsx');

        }catch(\Exception $e){
            return $e->getMessage();
        }

    }
}