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

class Reportes29Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reportes29()
    {
        try{
            //$marcas=tbl_brands::get();
            $marcas=DB::connection('cobefec3')->select("SELECT id,name FROM cobefec3.brands where id=8 and deleted_at is null and enabled=1;");
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
        return view('reporteNuevoSistema/coop29/index', compact('marcas'));
    }

    public function gCuentas29(Request $request)
    {
        $campana=$request->id_campana;
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

    public function getCuentasGenerico(Request $request){
        $cuentash='';
        if ($request->cuentasInhabilitadas==1){$cuentash='';}else{$cuentash=' and a.enabled=1 ';}

        set_time_limit(0);
        $query = "select product_id from cobefec3.campaigns where id=".$request->id_campana." limit 1;";

        try {
            $sql = DB::connection('cobefec3')->select($query);
            $id_producto=$sql[0]->product_id;
        } catch (\Exception $e) {
            return response()->json('Ocurrio un error: ' . $e->getMessage(), 500);
        }

        $query = "select count(*) as count
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and d.sent_status=0
        and c.id=" . $request->id_campana . $cuentash."
        ;";

        $sql = DB::connection('cobefec3')->select($query);
        $gestiones=$sql[0]->count;
        try {

            if ($request->cuentasInhabilitadas==1){$cuentas = tbl_accounts::where('campaign_id', $request->id_campana)->count();}else{$cuentas = tbl_accounts::where('campaign_id', $request->id_campana)->where('enabled', 1)->count();}
            return response()->json(['cuentas' => $cuentas, 'gestiones' => $gestiones]);
        } catch (\Exception $e) {
            return response()->json('Ocurrio un error: ' . $e->getMessage(), 500);
        }
    }

    public function rAcumuladoGestiones(Request $request)
    {
        set_time_limit(0);
        $query_fecha="SET lc_time_names = 'es_MX';";
        try {
            DB::connection('cobefec3')->statement($query_fecha);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $query="select 'COBEFEC' EMPRESA, upper(monthname(c.date_init)) MES, date_format(date(d.created_at), '%d/%m/%Y') FECHA_GESTION, a.data ->> '$.nombre' NOMBRES_DEUDOR, d.document CEDULA_DEUDOR , a.data ->> '$.agencia' AGENCIA, a.data ->> '$.codigo_credito' N_OPERACION, a.data ->> '$.tipo_de_credito' PRODUCTO, a.data ->> '$.saldo_de_operacion' SALDO_EN_RIESGO, a.data ->> '$.dias_de_mora' DIAS_MORA,
if(d.cex_time is null,'LLAMADA','VISITA')  ACCION, if(d.contact_type='CD','CONTACTO DIRECTO',if(d.contact_type='CI','CONTACTO INDIRECTO','SIN CONTACTO')) TIPO_CONTACTO, d.action RESPUESTA, if(d.sub_reason is null,'',d.sub_reason) MOTIVO_NO_PAGO, ifnull(date_format(str_to_date(d.extra ->> '$.pp_date','%d-%m-%Y'),'%d/%m/%Y'), '') FECHA_COMPROMISO_PAGO, d.description OBSERVACION
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id 
and a.campaign_id=".$request->id_campana1."
order by 5,3
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
        foreach($reportes as $reporte) {
            $reportes[$i]['SALDO_EN_RIESGO']=round($reporte['SALDO_EN_RIESGO'],2);
            $i++;
        }

        $query="call cobefec_reportes.gestion_diaria(".$request->id_campana1.");";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes2 = json_decode(json_encode($sql), true);


        $query="call cobefec_reportes.29oct_contactabilidad(".$request->id_campana1.");";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes3 = json_decode(json_encode($sql), true);

        $query="select TIPO_DE_CONTACTO, sum(GESTIONADAS) GESTIONADAS, sum(PORCENT) PORCENT
from cobefec_reportes.29oct_contact_agencia
group by 1
;";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes4 = json_decode(json_encode($sql), true);




        $query="call cobefec_reportes.29oct_asignacion(".$request->id_campana1.");";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes5 = json_decode(json_encode($sql), true);


        $query="select name from cobefec3.campaigns where id=".$request->id_campana1.";";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $campana=$sql[0]->name;

        try {
            \Excel::create('ACUMULADO DE GESTIONES '.$campana.' COBEFEC '.date('d-m-Y'), function($excel) use (&$reportes,$reportes2,$reportes3,$reportes4,$reportes5){
                $excel->sheet('ACUMULADO DE GESTIONES', function($sheet) use($reportes) {
                    $sheet->loadView('reporteNuevoSistema/coop29/tableAcumuladoGestiones')->with('reportes',$reportes);
                });
                $excel->sheet('REPORTE DE GESTION DIARIA', function($sheet) use($reportes2) {
                    $sheet->loadView('reporteNuevoSistema/coop29/tableGestionDiaria')->with('reportes2',$reportes2);
                });
                $excel->sheet('CONTACTABILIDAD', function($sheet) use($reportes3,$reportes4) {
                    $sheet->loadView('reporteNuevoSistema/coop29/tableContactabilidad')->with('reportes3',$reportes3)->with('reportes4',$reportes4);
                });
                $excel->sheet('ASIGNACION', function($sheet) use($reportes5) {
                    $sheet->loadView('reporteNuevoSistema/coop29/tableAsignacion')->with('reportes5',$reportes5);
                });
            })->export('xlsx');
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function rSeguimientoFacturacion(Request $request)
    {
        set_time_limit(0);
        $query="SET lc_time_names = 'es_MX';";
        try {
            //$sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $cuentash='';
        if ($request->cuentasInhabilitadas2==1){}else{$cuentash=' and a.enabled=1 ';}


        $query="select a.target_document Cedula, a.data ->> '$.codigo_credito' Cuenta, a.data ->> '$.tipo_de_credito' Producto, a.data ->> '$.nombre' Nombre, 'TELECOBRO' Etapa_actual, if(a.enabled=1,'Habilitado','Deshabilitado') Estado, a.to_recover Deuda_inicial, a.recovered Deuda_actual, (a.to_recover - a.recovered) Recuperacion, substr(u.email,1,locate('@',u.email)-1) Gestor_actual,
ifnull((SELECT substr(u1.email,1,locate('@',u1.email)-1) FROM cobefec3.demarches d1, cobefec3.agents ag1, cobefec3.users u1 where ag1.id=d1.agent_id and u1.id=ag1.user_id and d1.account_id=a.id and date(d1.created_at)=a.last_weight_date order by d1.id desc limit 1 ),'') Ug_Gestor,
ifnull((SELECT created_at FROM cobefec3.demarches where account_id=a.id and date(created_at)=a.last_weight_date order by id desc limit 1),'') Ug_Fecha_Hora,
ifnull((SELECT if(contact_type='CD','CONTACTO DIRECTO',if(contact_type='CI','CONTACTO INDIRECTO','SIN CONTACTO'))  FROM cobefec3.demarches where account_id=a.id and date(created_at)=a.last_weight_date order by id desc limit 1),'') Ug_Tipo,
ifnull((SELECT action FROM cobefec3.demarches where account_id=a.id and date(created_at)=a.last_weight_date order by id desc limit 1),'') Ug_Accion,
ifnull((SELECT reason FROM cobefec3.demarches where account_id=a.id and date(created_at)=a.last_weight_date order by id desc limit 1),'') Ug_Motivo,
ifnull((SELECT sub_reason FROM cobefec3.demarches where account_id=a.id and date(created_at)=a.last_weight_date order by id desc limit 1),'') Ug_SubMotivo,
ifnull((SELECT description FROM cobefec3.demarches where account_id=a.id and date(created_at)=a.last_weight_date order by id desc limit 1),'') Ug_Observacion,
ifnull(a.pp_date,'') Ug_Fecha_promesa_pago, ifnull(a.pp_amount,'') Ug_Monto_promesa_pago, a.data ->> '$.saldo_de_operacion' Saldo_de_Operacion, a.data ->> '$.total_capital' TOTAL_CAPITAL, a.data ->> '$.dias_de_mora' DIAS_MORA,a.data ->> '$.agencia' AGENCIA, a.data ->> '$.calificacion' CALIFICACION, a.data ->> '$.producto_a_negociar' PRODUCTO_A_NEGOCIAR,
if((
select count(*) from cobefec3.brands b1, cobefec3.products p1, cobefec3.campaigns c1, cobefec3.accounts a1
where b1.id=p1.brand_id and p1.id=c1.product_id and c1.id=a1.campaign_id and b1.id=b.id and p1.id=p.id and c1.id=c.id and date(a1.created_at)=(select date_init from cobefec3.campaigns where id=c1.id) and a1.id=a.id
)=0,'NO','SI') ASIGNACION_INICIAL, a.created_at FECHA_ASIGNACION, a.updated_at FECHA_ACTUALIZACION
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.agents ag, cobefec3.users u
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and ag.id=a.current_agent and u.id=ag.user_id
and c.id=".$request->id_campana2.$cuentash."
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
        foreach($reportes as $reporte) {
            $reportes[$i]['Deuda_actual']=round($reporte['Deuda_actual'],2);
            $reportes[$i]['Ug_Monto_promesa_pago']=round($reporte['Ug_Monto_promesa_pago'],2);
            $reportes[$i]['Saldo_de_Operacion']=round($reporte['Saldo_de_Operacion'],2);
            $i++;
        }
        $query="select name from cobefec3.campaigns where id=".$request->id_campana2.";";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $campana=$sql[0]->name;

        try {
            \Excel::create('SEGUIMIENTO DE FACTURACION '.$campana.' COBEFEC '.date('d-m-Y'), function($excel) use (&$reportes){
                $excel->sheet('SEGUIMIENTO DE FACTURACION', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
