<?php

namespace App\Http\Controllers\ReportesNuevoSistema;

use App\reportesNuevoSistema\tbl_accounts;
use App\reportesNuevoSistema\tbl_brands;
use App\reportesNuevoSistema\tbl_campaigns;
use App\reportesNuevoSistema\tbl_demarches;
use App\reportesNuevoSistema\tbl_products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use PhpParser\Node\Stmt\DeclareDeclare;


class ReportesNsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reportesDiners()
    {
        try{
            //$marcas=tbl_brands::get();
            $marcas=DB::connection('cobefec3')->select("SELECT id,name FROM cobefec3.brands where id in(2,10) and deleted_at is null and enabled=1;");
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
        $campanas_campo=tbl_campaigns::where('product_id',1)->where('enabled',1)->whereNull('deleted_at')->orderBy('id','DESC')->pluck("name","id")->all();
        $campanas_legal=tbl_campaigns::where('product_id',19)->where('enabled',1)->whereNull('deleted_at')->orderBy('id','DESC')->pluck("name","id")->all();
        return view('reporteNuevoSistema/diners/index', compact('marcas','campanas_campo','campanas_legal'));
    }

    public function getProducto(Request $request){
        try{
            $productos=tbl_products::where('brand_id',$request->id_marca)->where('enabled',1)->whereNull('deleted_at')->pluck("name","id")->all();
            $data= view('reporteNuevoSistema/diners/marcas/ajax-select-producto',compact('productos'))->render();
            return response()->json(['options'=>$data]);
        }catch (\Exception $e) {
            return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
        }
    }

    public function getCampana(Request $request){
        try{
            $campanas=tbl_campaigns::where('product_id',$request->id_producto)->where('enabled',1)->whereNull('deleted_at')->orderBy('id','DESC')->pluck("name","id")->all();

            $data= view('reporteNuevoSistema/diners/marcas/ajax-select-campana',compact('campanas'))->render();
            return response()->json(['options'=>$data]);

        }catch (\Exception $e) {
            return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
        }
    }

    public function getCuentas(Request $request){
        set_time_limit(0);
        $query = "select product_id from cobefec3.campaigns where id=" . $request->id_campana . " limit 1;";
        try {
            $sql = DB::connection('cobefec3')->select($query);
            $id_producto=$sql[0]->product_id;
        } catch (\Exception $e) {
            return response()->json('Ocurrio un error: ' . $e->getMessage(), 500);
        }

        if ($id_producto==1 || $id_producto==2 || $id_producto==19) {
            $query = "select count(DISTINCT(target_document)) as count
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and d.sent_status=0
            and c.id=" . $request->id_campana . "
            ;";
            $sql = DB::connection('cobefec3')->select($query);
            $gestiones=$sql[0]->count;
            try {
                $cuentas = tbl_accounts::where('campaign_id', $request->id_campana)->count();
                return response()->json(['cuentas' => $cuentas, 'gestiones' => $gestiones]);
            } catch (\Exception $e) {
                return response()->json('Ocurrio un error: ' . $e->getMessage(), 500);
            }
        }elseif ($id_producto==8 || $id_producto=21){
            $query = "select count(*) as count
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and d.sent_status=0
            and c.id=" . $request->id_campana . "
            ;";
            $sql = DB::connection('cobefec3')->select($query);
            $gestiones=$sql[0]->count;
            try {
                $cuentas = tbl_accounts::where('campaign_id', $request->id_campana)->count();
                return response()->json(['cuentas' => $cuentas, 'gestiones' => $gestiones]);
            }catch (\Exception $e){
                return response()->json('Ocurrio un error: ' . $e->getMessage(), 500);
            }

        }
    }

    public function sftpDiners(Request $request)
    {
        //diners tlc/campo
        if($request->id_marca==2){
            //telefonia
            if($request->id_producto==2){
                set_time_limit(0);

                try{
                    DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.sftptelefonia;");
                }catch(\Exception $e){
                    return $e->getMessage();
                }



                $dbh = DB::connection('cobefec3')->getPdo();

                $sth = $dbh->query("create table cobefec_reportes.sftptelefonia
select substr(a.identifier,1,locate(' ',a.identifier)-1) MARCA, a.data ->> '$.ciclof' CICLOF, a.data ->> '$.nomsoc' NOMSOC, a.data ->> '$.cedsoc' CEDSOC, a.data ->> '$.valor_pago_minimo_facturado' VAPAMI, a.data ->> '$.total_riesgo' TRIESGO_ORIG, a.data ->> '$.edcart_fact' EDAD, a.data ->> '$.producto' PRODUCTO, a.data ->> '$.direccion_donde_recibe_estado_de_cuenta.original_address' DIRECCION, if(length(a.data ->> '$.p1')=1,concat('0',a.data ->> '$.p1'),a.data ->> '$.p1') P1
, a.data ->> '$.t1' T1, if(length(a.data ->> '$.p2')=1,concat('0',a.data ->> '$.p2'),a.data ->> '$.p2') P2, a.data ->> '$.t2' T2, if(length(a.data ->> '$.p3')=1,concat('0',a.data ->> '$.p3'),a.data ->> '$.p3') P3, a.data ->> '$.t3' T3, a.data ->> '$.ciudad' NOMBRE_CIUDAD, a.data ->> '$.zona' ZONA, a.data ->> '$.resultado_anterior' MOTIVO_ANTERIOR, a.data ->> '$.descripcion_anterior' RESULTADO_ANTERIOR, a.data ->> '$.gestion_anterior' OBSERVACION_ANTERIOR,
if(((((a.data ->> '$.resultado_anterior')='Aún No Contactado Mañana') or ((a.data ->> '$.resultado_anterior')='Aún No Contactado Tarde') or ((a.data ->> '$.resultado_anterior')='Aún No Contactado Noche') or ((a.data ->> '$.resultado_anterior')='Ilocalizable') or ((a.data ->> '$.resultado_anterior')='Envio de Mail') or ((a.data ->> '$.resultado_anterior')='Redes Sociales') or ((a.data ->> '$.resultado_anterior')='Sin Gestión') or ((a.data ->> '$.resultado_anterior')='SIN GESTION') or ((a.data ->> '$.resultado_anterior')='')) and (d.action = 'Sin Arreglo Cliente' or d.action = 'Sin Arreglo Tercero')),'Aún No Contactado Tarde', if(((((a.data ->> '$.resultado_anterior')='Contacto sin Arreglo Mediato') or ((a.data ->> '$.resultado_anterior')='Acuerdo de Pago Pagare') or ((a.data ->> '$.resultado_anterior')='Llamada Inbound') or ((a.data ->> '$.resultado_anterior')='Notificado') or ((a.data ->> '$.resultado_anterior')='Ofrecimiento al Corte') or ((a.data ->> '$.resultado_anterior')='Refinancia') or ((a.data ->> '$.resultado_anterior')='Sin Arreglo Cliente') or ((a.data ->> '$.resultado_anterior')='OFRECIMIENTO INCUMPLIDO')) and (d.action = 'Aún No Contactado Mañana' or d.action = 'Aún No Contactado Tarde' or d.action = 'Aún No Contactado Noche' or d.action = 'Sin Arreglo Tercero')),'Sin Arreglo Cliente',if(((((a.data ->> '$.resultado_anterior')='Mensaje a Tercero') or ((a.data ->> '$.resultado_anterior')='Sin Arreglo Tercero')) and (d.action = 'Aún No Contactado Mañana' or d.action = 'Aún No Contactado Tarde' or d.action = 'Aún No Contactado Noche' or d.action = 'Sin Arreglo Cliente')),'Sin Arreglo Tercero',d.action))) RESULTADO,
if(((((a.data ->> '$.resultado_anterior')='Aún No Contactado Mañana') or ((a.data ->> '$.resultado_anterior')='Aún No Contactado Tarde') or ((a.data ->> '$.resultado_anterior')='Aún No Contactado Noche') or ((a.data ->> '$.resultado_anterior')='Ilocalizable') or ((a.data ->> '$.resultado_anterior')='Envio de Mail') or ((a.data ->> '$.resultado_anterior')='Redes Sociales') or ((a.data ->> '$.resultado_anterior')='Sin Gestión') or ((a.data ->> '$.resultado_anterior')='SIN GESTION') or ((a.data ->> '$.resultado_anterior')='')) and (d.action = 'Sin Arreglo Cliente' or d.action = 'Sin Arreglo Tercero')),'No Contesta T', if(((((a.data ->> '$.resultado_anterior')='Contacto sin Arreglo Mediato') or ((a.data ->> '$.resultado_anterior')='Acuerdo de Pago Pagare') or ((a.data ->> '$.resultado_anterior')='Llamada Inbound') or ((a.data ->> '$.resultado_anterior')='Notificado') or ((a.data ->> '$.resultado_anterior')='Ofrecimiento al Corte') or ((a.data ->> '$.resultado_anterior')='Refinancia') or ((a.data ->> '$.resultado_anterior')='Sin Arreglo Cliente') or ((a.data ->> '$.resultado_anterior')='OFRECIMIENTO INCUMPLIDO')) and (d.action = 'Aún No Contactado Mañana' or d.action = 'Aún No Contactado Tarde' or d.action = 'Aún No Contactado Noche' or d.action = 'Sin Arreglo Tercero')),'Sin Arreglo Cliente',if(((((a.data ->> '$.resultado_anterior')='Mensaje a Tercero') or ((a.data ->> '$.resultado_anterior')='Sin Arreglo Tercero')) and (d.action = 'Aún No Contactado Mañana' or d.action = 'Aún No Contactado Tarde' or d.action = 'Aún No Contactado Noche' or d.action = 'Sin Arreglo Cliente')),'Sin Arreglo Tercero',d.sub_action))) DESCRIPCION,
if(trim(substr(a.data ->> '$.producto1', locate(' ', a.data ->> '$.producto1')+1, length(a.data ->> '$.producto1')-locate(' ', a.data ->> '$.producto1')))='PRECOBRO',
concat('PRECOBRO COBEFEC ',concat(year(d.created_at), if(month(d.created_at)<10,concat('0',month(d.created_at)),month(d.created_at)), if(day(d.created_at)<10,concat('0',day(d.created_at)),day(d.created_at))),' ',if(a.current_agent is null,'',concat((select concat(substr(u.first_name,1,1),substr(u.last_name,1,1)) from cobefec3.accounts ac, cobefec3.agents ag, cobefec3.users u where ag.id=ac.current_agent and ag.user_id=u.id and ac.current_agent=a.current_agent group by 1),' ')), d.description),
concat('COBEFEC ',concat(year(d.created_at), if(month(d.created_at)<10,concat('0',month(d.created_at)),month(d.created_at)), if(day(d.created_at)<10,concat('0',day(d.created_at)),day(d.created_at))),' ',if(a.current_agent is null,'',concat((select concat(substr(u.first_name,1,1),substr(u.last_name,1,1)) from cobefec3.accounts ac, cobefec3.agents ag, cobefec3.users u where ag.id=ac.current_agent and ag.user_id=u.id and ac.current_agent=a.current_agent group by 1),' ')), d.description)) OBSERVACION,
concat(substr(d.extra ->> '$.pp_date',7,4),substr(d.extra ->> '$.pp_date',4,2),substr(d.extra ->> '$.pp_date',1,2)) FECHACOMPROMISO, d.phone ULTIMO_TLF_CONTACTO, d.type TIPOLLAMADA,
if((a.data ->> '$.producto1'='VISA PRECOBRO') or (a.data ->> '$.producto1'='DINERS PRECOBRO') or (a.data ->> '$.producto1'='DISCOVER PRECOBRO'),'',d.reason) MOTIVO,
if((a.data ->> '$.producto1'='VISA PRECOBRO') or (a.data ->> '$.producto1'='DINERS PRECOBRO') or (a.data ->> '$.producto1'='DISCOVER PRECOBRO'),'',d.sub_reason) SUB_MOTIVO_NO_PAGO,
d.agent GESTOR, 'COBEFEC' EMPRESA, a.data ->> '$.producto1' CAMPAÑA, concat(if(hour(d.tlc_time)<10,concat('0',hour(d.tlc_time)),hour(d.tlc_time)),if(minute(d.tlc_time)<10,concat('0',minute(d.tlc_time)),minute(d.tlc_time)), if(second(d.tlc_time)<10,concat('0',second(d.tlc_time)),second(d.tlc_time))) HORA_DE_CONTACTO,
(SELECT if(type=0,'Teléfono', if(type=1,'SMS','Correo')) FROM cobefec3.contact_types where account_id=a.id and demarche_id=d.id) TIPO_CONTACTO,
(SELECT contact_value FROM cobefec3.contact_types where account_id=a.id and demarche_id=d.id) CONTACTO,
(SELECT concat(time_from,' - ',time_to) FROM cobefec3.contact_types where account_id=a.id and demarche_id=d.id) HORARIO, d.weight peso, b.id idmarca, p.id idproducto, c.id idcampana, d.created_at fecha, d.type, d.sent_status estado_envio, d.id idgestion, a.id idcuenta,
substr(a.identifier,1,locate('-',a.identifier)-1) identificador,
ifnull((select d1.phone from cobefec3.demarches d1 where d1.type='MN' and d1.action='Equivocado' and d1.id=d.id),'') Borrar_Telefono1,
'' Borrar_Telefono2, '' Borrar_Telefono3, '' Borrar_Direccion, '' Borrar_Correo, '' Anadir_Telefono1, '' Anadir_Telefono2, '' Anadir_Telefono3,
(SELECT original_address FROM cobefec3_mining.target_addresses where target_document=d.document order by id desc limit 1) Anadir_Direccion, '' Anadir_Correo, a.major_weight_id,a.last_weight_id
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id
and b.id=".$request->id_marca." and p.id=".$request->id_producto."  and c.id=".$request->id_campana."
;
");

                $query="select s.MARCA, s.CICLOF, s.NOMSOC, s.CEDSOC, s.VAPAMI, s.TRIESGO_ORIG, s.EDAD, s.PRODUCTO, s.DIRECCION, s.P1, s.T1, s.P2, s.T2, s.P3, s.T3, s.NOMBRE_CIUDAD, s.ZONA, s.MOTIVO_ANTERIOR, s.RESULTADO_ANTERIOR, s.OBSERVACION_ANTERIOR, s.RESULTADO, s.DESCRIPCION, s.OBSERVACION, s.FECHACOMPROMISO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.ULTIMO_TLF_CONTACTO) ULTIMO_TLF_CONTACTO,
s.TIPOLLAMADA,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'', if(((s.RESULTADO='Contacto sin Arreglo Mediato' or s.RESULTADO='Ofrecimiento al Corte') and s.MOTIVO=''),'No quiere informar el motivo',s.MOTIVO)) MOTIVO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'', if(((s.RESULTADO='Contacto sin Arreglo Mediato' or s.RESULTADO='Ofrecimiento al Corte') and s.MOTIVO=''),'No quiere informar el motivo',s.SUB_MOTIVO_NO_PAGO)) SUB_MOTIVO_NO_PAGO,
s.GESTOR, s.EMPRESA, substr(s.CAMPAÑA, locate(' ', s.CAMPAÑA)+1, length(s.CAMPAÑA)-locate(' ', s.CAMPAÑA)) CAMPAÑA, s.HORA_DE_CONTACTO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.TIPO_CONTACTO) CANAL_DE_COMUNICACION,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.CONTACTO) CONTACTO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.HORARIO) HORARIO_DE_CONTACTO_FUTURO, s.peso, s.idgestion, s.identificador,
s.Borrar_Telefono1, s.Borrar_Telefono2, s.Borrar_Telefono3, s.Borrar_Direccion, s.Borrar_Correo, s.Anadir_Telefono1, s.Anadir_Telefono2, s.Anadir_Telefono3, s.Anadir_Direccion, s.Anadir_Correo
from cobefec_reportes.sftptelefonia s
where s.estado_envio='0'
and s.idgestion=s.major_weight_id and s.idgestion=s.last_weight_id
union all
select s.MARCA, s.CICLOF, s.NOMSOC, s.CEDSOC, s.VAPAMI, s.TRIESGO_ORIG, s.EDAD, s.PRODUCTO, s.DIRECCION, s.P1, s.T1, s.P2, s.T2, s.P3, s.T3, s.NOMBRE_CIUDAD, s.ZONA, s.MOTIVO_ANTERIOR, s.RESULTADO_ANTERIOR, s.OBSERVACION_ANTERIOR, s.RESULTADO, s.DESCRIPCION, s.OBSERVACION, s.FECHACOMPROMISO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.ULTIMO_TLF_CONTACTO) ULTIMO_TLF_CONTACTO,
s.TIPOLLAMADA,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'', if(((s.RESULTADO='Contacto sin Arreglo Mediato' or s.RESULTADO='Ofrecimiento al Corte') and s.MOTIVO=''),'No quiere informar el motivo',s.MOTIVO)) MOTIVO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'', if(((s.RESULTADO='Contacto sin Arreglo Mediato' or s.RESULTADO='Ofrecimiento al Corte') and s.MOTIVO=''),'No quiere informar el motivo',s.SUB_MOTIVO_NO_PAGO)) SUB_MOTIVO_NO_PAGO,
s.GESTOR, s.EMPRESA, substr(s.CAMPAÑA, locate(' ', s.CAMPAÑA)+1, length(s.CAMPAÑA)-locate(' ', s.CAMPAÑA)) CAMPAÑA, s.HORA_DE_CONTACTO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.TIPO_CONTACTO) CANAL_DE_COMUNICACION,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.CONTACTO) CONTACTO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.HORARIO) HORARIO_DE_CONTACTO_FUTURO, s.peso, s.idgestion, s.identificador,
s.Borrar_Telefono1, s.Borrar_Telefono2, s.Borrar_Telefono3, s.Borrar_Direccion, s.Borrar_Correo, s.Anadir_Telefono1, s.Anadir_Telefono2, s.Anadir_Telefono3, s.Anadir_Direccion, s.Anadir_Correo
from cobefec_reportes.sftptelefonia s
where s.estado_envio='0'
and s.idgestion=s.major_weight_id and s.idgestion<>s.last_weight_id
order by 1,3
;
";

                $sql=DB::connection('cobefec3')->select($query);

                $reportes = json_decode(json_encode($sql), true);

                //solo extraigo la informacion de cedsoc en un solo array
                $unicos = array_column($reportes, 'CEDSOC');

                //elimino los valores duplicados de array unicos
                //$unicos = array_unique($unicos);

                $reportes_f_diners=Array();
                $reportes_f_visa=Array();
                $reportes_f_discover=Array();
                $reportes_f_base=Array();

                $i=0;
                foreach($unicos as $k)
                {
                    $key = array_search($k, array_column($reportes, 'CEDSOC'));
                    // validar info socio
                    // PRODUCTO UNICO
                    // MANTENGO MEJOR GESTION

                    //agrego mÃ¡ximo 3 telefonos
                    /*$query="SELECT if(p.prefix is null, p.number, concat(p.prefix,p.number)) telefono
FROM cobefec3_mining.target_phones p, cobefec3.demarches d1
where d1.document=p.target_document and date(d1.created_at)=date(p.created_at) and d1.contact_type='CD' and d1.id='".$reportes[$key]['idgestion']."'
;";
                    try {
                        $sql = DB::connection('cobefec3')->select($query);
                    }catch(\Exception $e) {
                        return $reportes[$key]['idgestion'].' - '.$e->getMessage();
                        //return "No se encontro ninguna gestion asociada a DINERS, VISA Y DISCOVER.";
                    }
                    $numeros = json_decode(json_encode($sql), true);
                    $countTelefono=1;
                    foreach ($numeros as $numero) {
                        if ($countTelefono<=3){
                            $reportes[$key]['Anadir_Telefono'.$countTelefono]=$numero['telefono'];
                            $countTelefono++;
                        }
                    }*/
                    //$identificador=$reportes[$key]['identificador'];

                    //$reportes[$key]['VAPAMI']=(string)$reportes[$key]['VAPAMI'];
                    //$reportes[$key]['VAPAMI']=str_replace('.',',',$reportes[$key]['VAPAMI']);


                    //$reportes[$key]['VAPAMI']=floatValue($reportes[$key]['VAPAMI']);
                    //$reportes[$key]['TRIESGO_ORIG']=1.5;

                    //creo un nuevo arreglo sin valores duplicados
                    if ($reportes[$i]['MARCA']=='DINERS'){
                        $reportes[$i]['VAPAMI']=round($reportes[$i]['VAPAMI'], 2);
                        $reportes[$i]['TRIESGO_ORIG']=round($reportes[$i]['TRIESGO_ORIG'], 2);
                        array_push($reportes_f_diners, $reportes[$i]);
                    }
                    elseif($reportes[$i]['MARCA']=='VISA'){
                        $reportes[$i]['VAPAMI']=round($reportes[$i]['VAPAMI'], 2);
                        $reportes[$i]['TRIESGO_ORIG']=round($reportes[$i]['TRIESGO_ORIG'], 2);
                        array_push($reportes_f_visa, $reportes[$i]);
                    }
                    elseif($reportes[$i]['MARCA']=='DISCOVER'){
                        $reportes[$i]['VAPAMI']=round($reportes[$i]['VAPAMI'], 2);
                        $reportes[$i]['TRIESGO_ORIG']=round($reportes[$i]['TRIESGO_ORIG'], 2);
                        array_push($reportes_f_discover, $reportes[$i]);
                    }
                    elseif(count($reportes[$i]['MARCA'])>0){
                        array_push($reportes_f_base, $reportes[$i]);
                    }


                    //UNSET


                    //unset($reportes[$key]['identificador']);
                    //unset($reportes[$key]['idgestion']);
                    //unset($reportes[$key]['peso']);

                    $i++;
                }

                try {
                    \Excel::create('BASE A CARGAR TELEFONIA DISCOVER VISA DINERS COBEFEC '.date('d-m-Y'), function($excel) use (&$reportes_f_diners,$reportes_f_visa,$reportes_f_discover,$reportes_f_base){
                        if (count($reportes_f_diners)>0){
                            $excel->sheet('DINERS', function($sheet) use($reportes_f_diners) {
                                $sheet->fromArray($reportes_f_diners,null,'A1',true);
                                //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                            });
                        }
                        if (count($reportes_f_visa)>0){
                            $excel->sheet('VISA', function($sheet) use($reportes_f_visa) {
                                $sheet->fromArray($reportes_f_visa,null,'A1',true);
                                //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                            });
                        }
                        if (count($reportes_f_discover)>0){
                            $excel->sheet('DISCOVER', function($sheet) use($reportes_f_discover) {
                                $sheet->fromArray($reportes_f_discover,null,'A1',true);
                                //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                            });
                        }
                        if (count($reportes_f_base)>0){
                            $excel->sheet('REGISTROS CON ERROR', function($sheet) use($reportes_f_base) {
                                $sheet->fromArray($reportes_f_base,null,'A1',true);
                                //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                            });
                        }
                    })->export('xlsx');
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                    //return "No se encontro ninguna gestion asociada a DINERS, VISA Y DISCOVER.";
                }

            }
            elseif($request->id_producto==1){
                $descargar=$request->descargar;

                $sent_status="";
                $fecha="";
                if ($request->descargar==0){
                    $sent_status=" and d.sent_status=0 ";
                    $fecha="";
                    $envio=$request->envio;
                }elseif($request->descargar==1){
                        $sent_status=" ";
                        $fecha = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio_sftp)->format('Y-m-d');
                        $fecha=" and date(d.created_at)='".$fecha."' ";
                        $envio=0;
                }
                //Campo
                set_time_limit(0);
                ini_set ( 'memory_limit' , '2048M' );

                try{
                    DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.sftpcampo_dm;");
                }catch(\Exception $e){
                    return $e->getMessage();
                }

                $query="create table cobefec_reportes.sftpcampo_dm
select substr(a.identifier,1,locate('-',a.identifier)-1) MARCA, a.data ->> '$.ciclof' CICLOF, a.data ->> '$.nomsoc' NOMSOC, a.data ->> '$.cedsoc' CEDSOC, a.data ->> '$.deuda_inicial' VAPAMI, a.data ->> '$.triesgo' TRIESGO_ORIG, a.data ->> '$.edcart' EDAD, a.data ->> '$.producto' PRODUCTO, a.data ->> '$.direccion.original_address' DIRECCION, if(length(a.data ->> '$.p1')=1,concat('0',a.data ->> '$.p1'),a.data ->> '$.p1') P1
, a.data ->> '$.t1' T1, if(length(a.data ->> '$.p2')=1,concat('0',a.data ->> '$.p2'),a.data ->> '$.p2') P2, a.data ->> '$.t2' T2, if(length(a.data ->> '$.p3')=1,concat('0',a.data ->> '$.p3'),a.data ->> '$.p3') P3, a.data ->> '$.t3' T3, a.data ->> '$.nombre_ciudad' NOMBRE_CIUDAD, a.data ->> '$.zona' ZONA, a.data ->> '$.motivo' MOTIVO_ANTERIOR, a.data ->> '$.descripcion' RESULTADO_ANTERIOR, a.data ->> '$.observacion' OBSERVACION_ANTERIOR,
if(d.contact_type='NC',if(d.sub_action='Dirección no Corresponde' or d.sub_action='Trabajo Cerrado' or d.sub_action='Fallecimiento' or d.sub_action='Empleada' or d.sub_action='Socio de Viaje' or d.sub_action='Compañero de Trabajo' or d.sub_action='Domicilio Cerrado','Aún No Contactado Tarde',d.action),
if(d.contact_type='CI',if(d.sub_action='Esposa (o)' or d.sub_action='Familiares' or d.sub_action='Hijos / Padres' or d.sub_action='Secretaria / Asistente','Mensaje a Tercero',d.action),
if(d.contact_type='CD',if(d.sub_action='No Concreta','Contacto sin Arreglo Mediato',if(d.sub_action='Ofrecimiento de Arreglo' or d.sub_action='Ofrecimiento de Pago Total','Ofrecimiento al Corte',d.action)),d.action))) Resultado,
if(d.contact_type='NC',if(d.sub_action='Dirección no Corresponde','Direccion no Corresponde T', if(d.sub_action='Trabajo Cerrado','No Contesta T',if(d.sub_action='Fallecimiento','Fallecimiento T',if(d.sub_action='Empleada','Empleada T',if(d.sub_action='Socio de Viaje','Viaje T',if(d.sub_action='Compañero de Trabajo','Compañero de Trabajo T',if(d.sub_action='Domicilio Cerrado','No Contesta T',d.sub_action))))))),
if(d.contact_type='CI',if(d.sub_action='Esposa (o)','Esposa (o)',if(d.sub_action='Familiares','Familiares',if(d.sub_action='Hijos / Padres','Hijos / Padres',if(d.sub_action='Secretaria / Asistente','Secretaria / Asistente',d.sub_action)))),
if(d.contact_type='CD',if(d.sub_action='No Concreta','No Concreta',if(d.sub_action='Ofrecimiento de Arreglo','Ofrecimiento de Arreglo',if(d.sub_action='Ofrecimiento de Pago Total','Ofrecimiento de Pago Total',d.sub_action))),d.sub_action))) Descripcion,
upper(d.description) OBSERVACION,
if( (d.extra ->> '$.pp_date' is null) or (d.extra ->> '$.pp_date' = '') ,'',concat(substr(if(locate('-',substr(d.extra ->> '$.pp_date',1,10))=5, str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%Y-%m-%d'),if((length(substr(d.extra ->> '$.pp_date',1,10)) - locate('-',substr(d.extra ->> '$.pp_date',1,10),locate('-',substr(d.extra ->> '$.pp_date',1,10))+1))=4, str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%d-%m-%Y'), str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%m-%d-%y'))),1,4),substr(if(locate('-',substr(d.extra ->> '$.pp_date',1,10))=5, str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%Y-%m-%d'),if((length(substr(d.extra ->> '$.pp_date',1,10)) - locate('-',substr(d.extra ->> '$.pp_date',1,10),locate('-',substr(d.extra ->> '$.pp_date',1,10))+1))=4, str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%d-%m-%Y'), str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%m-%d-%y'))),6,2),substr(if(locate('-',substr(d.extra ->> '$.pp_date',1,10))=5, str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%Y-%m-%d'),if((length(substr(d.extra ->> '$.pp_date',1,10)) - locate('-',substr(d.extra ->> '$.pp_date',1,10),locate('-',substr(d.extra ->> '$.pp_date',1,10))+1))=4, str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%d-%m-%Y'), str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%m-%d-%y'))),9,2))) FECHACOMPROMISO,
d.phone ULTIMO_TLF_CONTACTO,
d.type TIPOLLAMADA, d.reason MOTIVO, d.sub_reason SUB_MOTIVO_NO_PAGO, d.agent GESTOR, 'COBEFEC' EMPRESA, 'CAMPO' CAMPAÑA,
if(d.type='DM',concat(if(hour(d.cex_time)<10,concat('0',hour(d.cex_time)),hour(d.cex_time)),if(minute(d.cex_time)<10,concat('0',minute(d.cex_time)),minute(d.cex_time)), if(second(d.cex_time)<10,concat('0',second(d.cex_time)),second(d.cex_time))),concat(if(hour(d.tlc_time)<10,concat('0',hour(d.tlc_time)),hour(d.tlc_time)),if(minute(d.tlc_time)<10,concat('0',minute(d.tlc_time)),minute(d.tlc_time)), if(second(d.tlc_time)<10,concat('0',second(d.tlc_time)),second(d.tlc_time)))) HORA_DE_CONTACTO,
if(d.action='Domicilio','SI','') VISITA_DOMICILIO,
if(d.action='Trabajo','SI','') VISITA_OFICINA,
(SELECT if(type=0,'Teléfono', if(type=1,'SMS','Correo')) FROM cobefec3.contact_types where account_id=a.id and demarche_id=d.id) TIPO_CONTACTO,
(SELECT contact_value FROM cobefec3.contact_types where account_id=a.id and demarche_id=d.id) CONTACTO,
(SELECT concat(time_from,' - ',time_to) FROM cobefec3.contact_types where account_id=a.id and demarche_id=d.id) HORARIO, d.weight peso, c.id idcampana, d.created_at fecha, d.type, d.sent_status estado_envio, d.id idgestion, a.id idcuenta,
'' Borrar_Telefono1, '' Borrar_Telefono2, '' Borrar_Telefono3,
ifnull((select ifnull(a1.data ->> '$.direccion.original_address', '') from cobefec3.demarches d1, cobefec3.accounts a1 where d1.type='DM' and d1.action='Direccion no corresponde' and a1.id=d1.account_id and d1.id=d.id),'') Borrar_Direccion,
'' Borrar_Correo, '' Anadir_Telefono1, '' Anadir_Telefono2, '' Anadir_Telefono3,
if(a.data ->> '$.direccion.original_address'<>ifnull((select d1.address from cobefec3.demarches d1 where d1.type='DM' and d1.id=d.id),''),ifnull((select d1.address from cobefec3.demarches d1 where d1.type='DM' and d1.id=d.id),''),'') Anadir_Direccion,
'' Anadir_Correo 
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and (d.original_demarche='' or d.original_demarche is null) 
and b.id=2 and p.id=1  and c.id=".$request->id_campana." and d.type='DM' and a.data ->> '$.saldo_actual' > 0 
and a.data ->> '$.codret'<> '88' ".$sent_status.$fecha."
and d.discarded=0 and d.validated=1
-- and d.document in ('1724550270','1715619092','1723406649','1003410113','1722732805','1726336603','1721306015','1726113028')
;
";
                try {
                    DB::connection('cobefec3')->statement($query);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }

                try {
                    DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.tmp_sftpcampodm;");
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }

                $query="create temporary table cobefec_reportes.tmp_sftpcampodm
select s.MARCA, s.CICLOF, s.NOMSOC, s.CEDSOC, s.VAPAMI, s.TRIESGO_ORIG, s.EDAD, s.PRODUCTO, s.DIRECCION, s.P1, s.T1, s.P2, s.T2, s.P3, s.T3, s.NOMBRE_CIUDAD, s.ZONA, s.MOTIVO_ANTERIOR, s.RESULTADO_ANTERIOR, s.OBSERVACION_ANTERIOR, 
if(((((s.MOTIVO_ANTERIOR)='Aún No Contactado Mañana') or ((s.MOTIVO_ANTERIOR)='Aún No Contactado Tarde') or ((s.MOTIVO_ANTERIOR)='Aún No Contactado Noche') or ((s.MOTIVO_ANTERIOR)='Ilocalizable') or ((s.MOTIVO_ANTERIOR)='Envio de Mail') or ((s.MOTIVO_ANTERIOR)='Redes Sociales') or ((s.MOTIVO_ANTERIOR)='Sin Gestión') or ((s.MOTIVO_ANTERIOR)='')) and (s.RESULTADO = 'Sin Arreglo Cliente' or s.RESULTADO = 'Sin Arreglo Tercero')),'Aún No Contactado Tarde', if(((((s.MOTIVO_ANTERIOR)='Contacto sin Arreglo Mediato') or ((s.MOTIVO_ANTERIOR)='Acuerdo de Pago Pagare') or ((s.MOTIVO_ANTERIOR)='Llamada Inbound') or ((s.MOTIVO_ANTERIOR)='Notificado') or ((s.MOTIVO_ANTERIOR)='Ofrecimiento al Corte') or ((s.MOTIVO_ANTERIOR)='Refinancia') or ((s.MOTIVO_ANTERIOR)='Sin Arreglo Cliente') or ((s.MOTIVO_ANTERIOR)='Cont. Sin Arreglo Definitivo')) and (s.RESULTADO = 'Aún No Contactado Mañana' or s.RESULTADO = 'Aún No Contactado Tarde' or s.RESULTADO = 'Aún No Contactado Noche' or s.RESULTADO = 'Sin Arreglo Tercero')),'Sin Arreglo Cliente',if(((((s.MOTIVO_ANTERIOR)='Mensaje a Tercero') or ((s.MOTIVO_ANTERIOR)='Sin Arreglo Tercero')) and (s.RESULTADO = 'Aún No Contactado Mañana' or s.RESULTADO = 'Aún No Contactado Tarde' or s.RESULTADO = 'Aún No Contactado Noche' or s.RESULTADO = 'Sin Arreglo Cliente')),'Sin Arreglo Tercero',s.RESULTADO))) RESULTADO, 
if(((((s.MOTIVO_ANTERIOR)='Aún No Contactado Mañana') or ((s.MOTIVO_ANTERIOR)='Aún No Contactado Tarde') or ((s.MOTIVO_ANTERIOR)='Aún No Contactado Noche') or ((s.MOTIVO_ANTERIOR)='Ilocalizable') or ((s.MOTIVO_ANTERIOR)='Envio de Mail') or ((s.MOTIVO_ANTERIOR)='Redes Sociales') or ((s.MOTIVO_ANTERIOR)='Sin Gestión') or ((s.MOTIVO_ANTERIOR)='')) and (s.RESULTADO = 'Sin Arreglo Cliente' or s.RESULTADO = 'Sin Arreglo Tercero')),'No Contesta T', if(((((s.MOTIVO_ANTERIOR)='Contacto sin Arreglo Mediato') or ((s.MOTIVO_ANTERIOR)='Acuerdo de Pago Pagare') or ((s.MOTIVO_ANTERIOR)='Llamada Inbound') or ((s.MOTIVO_ANTERIOR)='Notificado') or ((s.MOTIVO_ANTERIOR)='Ofrecimiento al Corte') or ((s.MOTIVO_ANTERIOR)='Refinancia') or ((s.MOTIVO_ANTERIOR)='Sin Arreglo Cliente') or ((s.MOTIVO_ANTERIOR)='Cont. Sin Arreglo Definitivo')) and (s.RESULTADO = 'Aún No Contactado Mañana' or s.RESULTADO = 'Aún No Contactado Tarde' or s.RESULTADO = 'Aún No Contactado Noche' or s.RESULTADO = 'Sin Arreglo Tercero')),'Sin Arreglo Cliente',if(((((s.MOTIVO_ANTERIOR)='Mensaje a Tercero') or ((s.MOTIVO_ANTERIOR)='Sin Arreglo Tercero')) and (s.RESULTADO = 'Aún No Contactado Mañana' or s.RESULTADO = 'Aún No Contactado Tarde' or s.RESULTADO = 'Aún No Contactado Noche' or s.RESULTADO = 'Sin Arreglo Cliente')),'Sin Arreglo Tercero',s.DESCRIPCION))) DESCRIPCION, 
s.OBSERVACION, s.FECHACOMPROMISO, s.ULTIMO_TLF_CONTACTO, s.TIPOLLAMADA, s.MOTIVO, s.SUB_MOTIVO_NO_PAGO, s.GESTOR, s.EMPRESA, s.CAMPAÑA, s.HORA_DE_CONTACTO,VISITA_DOMICILIO,VISITA_OFICINA, s.TIPO_CONTACTO, s.CONTACTO, s.HORARIO, s.peso, s.idgestion,
s.Borrar_Telefono1, s.Borrar_Telefono2, s.Borrar_Telefono3, s.Borrar_Direccion, s.Borrar_Correo, s.Anadir_Telefono1, s.Anadir_Telefono2, s.Anadir_Telefono3, s.Anadir_Direccion, s.Anadir_Correo
from cobefec_reportes.sftpcampo_dm s 
 having s.peso=(select max(peso) from cobefec_reportes.sftpcampo_dm where CEDSOC=s.CEDSOC and idcampana=s.idcampana and date(fecha)=date(s.fecha))
order by 1,4
;";

                try {
                    DB::connection('cobefec3')->statement($query);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }

                // nuevo

                try {
                    DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.vista_sftpcampo;");
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }


                $query="create table cobefec_reportes.vista_sftpcampo 
select substr(a.identifier,1,locate('-',a.identifier)-1) MARCA, a.data ->> '$.ciclof' CICLOF, a.data ->> '$.nomsoc' NOMSOC, a.data ->> '$.cedsoc' CEDSOC, a.data ->> '$.deuda_inicial' VAPAMI, a.data ->> '$.triesgo' TRIESGO_ORIG, a.data ->> '$.edcart' EDAD, a.data ->> '$.producto' PRODUCTO, a.data ->> '$.direccion.original_address' DIRECCION, if(length(a.data ->> '$.p1')=1,concat('0',a.data ->> '$.p1'),a.data ->> '$.p1') P1
, a.data ->> '$.t1' T1, if(length(a.data ->> '$.p2')=1,concat('0',a.data ->> '$.p2'),a.data ->> '$.p2') P2, a.data ->> '$.t2' T2, if(length(a.data ->> '$.p3')=1,concat('0',a.data ->> '$.p3'),a.data ->> '$.p3') P3, a.data ->> '$.t3' T3, a.data ->> '$.nombre_ciudad' NOMBRE_CIUDAD, a.data ->> '$.zona' ZONA, a.data ->> '$.motivo' MOTIVO_ANTERIOR, a.data ->> '$.descripcion' RESULTADO_ANTERIOR, a.data ->> '$.observacion' OBSERVACION_ANTERIOR,
if(((((a.data ->> '$.motivo')='Aún No Contactado Mañana') or ((a.data ->> '$.motivo')='Aún No Contactado Tarde') or ((a.data ->> '$.motivo')='Aún No Contactado Noche') or ((a.data ->> '$.motivo')='Ilocalizable') or ((a.data ->> '$.motivo')='Envio de Mail') or ((a.data ->> '$.motivo')='Redes Sociales') or ((a.data ->> '$.motivo')='Sin Gestión') or ((a.data ->> '$.motivo')='')) and (d.action = 'Sin Arreglo Cliente' or d.action = 'Sin Arreglo Tercero')),'Aún No Contactado Tarde', if(((((a.data ->> '$.motivo')='Contacto sin Arreglo Mediato') or ((a.data ->> '$.motivo')='Acuerdo de Pago Pagare') or ((a.data ->> '$.motivo')='Llamada Inbound') or ((a.data ->> '$.motivo')='Notificado') or ((a.data ->> '$.motivo')='Ofrecimiento al Corte') or ((a.data ->> '$.motivo')='Refinancia') or ((a.data ->> '$.motivo')='Sin Arreglo Cliente') or ((a.data ->> '$.motivo')='Cont. Sin Arreglo Definitivo')) and (d.action = 'Aún No Contactado Mañana' or d.action = 'Aún No Contactado Tarde' or d.action = 'Aún No Contactado Noche' or d.action = 'Sin Arreglo Tercero')),'Sin Arreglo Cliente',if(((((a.data ->> '$.motivo')='Mensaje a Tercero') or ((a.data ->> '$.motivo')='Sin Arreglo Tercero')) and (d.action = 'Aún No Contactado Mañana' or d.action = 'Aún No Contactado Tarde' or d.action = 'Aún No Contactado Noche' or d.action = 'Sin Arreglo Cliente')),'Sin Arreglo Tercero',d.action))) RESULTADO,
if(((((a.data ->> '$.motivo')='Aún No Contactado Mañana') or ((a.data ->> '$.motivo')='Aún No Contactado Tarde') or ((a.data ->> '$.motivo')='Aún No Contactado Noche') or ((a.data ->> '$.motivo')='Ilocalizable') or ((a.data ->> '$.motivo')='Envio de Mail') or ((a.data ->> '$.motivo')='Redes Sociales') or ((a.data ->> '$.motivo')='Sin Gestión') or ((a.data ->> '$.motivo')='')) and (d.action = 'Sin Arreglo Cliente' or d.action = 'Sin Arreglo Tercero')),'No Contesta T', if(((((a.data ->> '$.motivo')='Contacto sin Arreglo Mediato') or ((a.data ->> '$.motivo')='Acuerdo de Pago Pagare') or ((a.data ->> '$.motivo')='Llamada Inbound') or ((a.data ->> '$.motivo')='Notificado') or ((a.data ->> '$.motivo')='Ofrecimiento al Corte') or ((a.data ->> '$.motivo')='Refinancia') or ((a.data ->> '$.motivo')='Sin Arreglo Cliente') or ((a.data ->> '$.motivo')='Cont. Sin Arreglo Definitivo')) and (d.action = 'Aún No Contactado Mañana' or d.action = 'Aún No Contactado Tarde' or d.action = 'Aún No Contactado Noche' or d.action = 'Sin Arreglo Tercero')),'Sin Arreglo Cliente',if(((((a.data ->> '$.motivo')='Mensaje a Tercero') or ((a.data ->> '$.motivo')='Sin Arreglo Tercero')) and (d.action = 'Aún No Contactado Mañana' or d.action = 'Aún No Contactado Tarde' or d.action = 'Aún No Contactado Noche' or d.action = 'Sin Arreglo Cliente')),'Sin Arreglo Tercero',d.sub_action))) DESCRIPCION,
d.description OBSERVACION,
if( (d.extra ->> '$.pp_date' is null) or (d.extra ->> '$.pp_date' = '') ,'',concat(substr(if(locate('-',d.extra ->> '$.pp_date')=5, str_to_date(d.extra ->> '$.pp_date','%Y-%m-%d'),if((length(d.extra ->> '$.pp_date') - locate('-',d.extra ->> '$.pp_date',locate('-',d.extra ->> '$.pp_date')+1))=4, str_to_date(d.extra ->> '$.pp_date','%d-%m-%Y'), str_to_date(d.extra ->> '$.pp_date','%m-%d-%y'))),1,4),substr(if(locate('-',d.extra ->> '$.pp_date')=5, str_to_date(d.extra ->> '$.pp_date','%Y-%m-%d'),if((length(d.extra ->> '$.pp_date') - locate('-',d.extra ->> '$.pp_date',locate('-',d.extra ->> '$.pp_date')+1))=4, str_to_date(d.extra ->> '$.pp_date','%d-%m-%Y'), str_to_date(d.extra ->> '$.pp_date','%m-%d-%y'))),6,2),substr(if(locate('-',d.extra ->> '$.pp_date')=5, str_to_date(d.extra ->> '$.pp_date','%Y-%m-%d'),if((length(d.extra ->> '$.pp_date') - locate('-',d.extra ->> '$.pp_date',locate('-',d.extra ->> '$.pp_date')+1))=4, str_to_date(d.extra ->> '$.pp_date','%d-%m-%Y'), str_to_date(d.extra ->> '$.pp_date','%m-%d-%y'))),9,2))) FECHACOMPROMISO,
d.phone ULTIMO_TLF_CONTACTO,
d.type TIPOLLAMADA, d.reason MOTIVO, d.sub_reason SUB_MOTIVO_NO_PAGO, d.agent GESTOR, 'COBEFEC' EMPRESA, 'CAMPO' CAMPAÑA,
if(d.type='DM',concat(if(hour(d.cex_time)<10,concat('0',hour(d.cex_time)),hour(d.cex_time)),if(minute(d.cex_time)<10,concat('0',minute(d.cex_time)),minute(d.cex_time)), if(second(d.cex_time)<10,concat('0',second(d.cex_time)),second(d.cex_time))),concat(if(hour(d.tlc_time)<10,concat('0',hour(d.tlc_time)),hour(d.tlc_time)),if(minute(d.tlc_time)<10,concat('0',minute(d.tlc_time)),minute(d.tlc_time)), if(second(d.tlc_time)<10,concat('0',second(d.tlc_time)),second(d.tlc_time)))) HORA_DE_CONTACTO,
if(locate('DOMICILIO',d.description)>0 or locate('Domicilio',d.description)>0,'SI','') VISITA_DOMICILIO,
if(locate('TRABAJO',d.description)>0 or locate('Trabajo',d.description)>0,'SI',if(locate('DOMICILIO',d.description)=0 and locate('Domicilio',d.description)=0,'SI','')) VISITA_OFICINA,
(SELECT if(type=0,'Teléfono', if(type=1,'SMS','Correo')) FROM cobefec3.contact_types where account_id=a.id and demarche_id=d.id) TIPO_CONTACTO,
(SELECT contact_value FROM cobefec3.contact_types where account_id=a.id and demarche_id=d.id) CONTACTO,
(SELECT concat(time_from,' - ',time_to) FROM cobefec3.contact_types where account_id=a.id and demarche_id=d.id) HORARIO, d.weight peso, b.id idmarca, p.id idproducto, c.id idcampana, d.created_at fecha, d.type, d.sent_status estado_envio, d.id idgestion, a.id idcuenta,
ifnull((select d1.phone from cobefec3.demarches d1 where d1.type='MN' and d1.sub_action like 'Equivocado-Desconocen Núm %' and d1.id=d.id),'') Borrar_Telefono1,
'' Borrar_Telefono2, '' Borrar_Telefono3, '' Borrar_Direccion, '' Borrar_Correo, '' Anadir_Telefono1, '' Anadir_Telefono2, '' Anadir_Telefono3,
if(a.data ->> '$.direccion.original_address'<>(SELECT original_address FROM cobefec3_mining.target_addresses where target_document=d.document order by id desc limit 1),(SELECT original_address FROM cobefec3_mining.target_addresses where target_document=d.document order by id desc limit 1),'') Anadir_Direccion,
'' Anadir_Correo
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id 
and b.id=2 and p.id=1  and c.id=".$request->id_campana."  and d.type='MN' and (d.original_demarche='' or d.original_demarche is null) and a.data ->> '$.saldo_actual' > 0 
and a.data ->> '$.codret'<> '88'
".$sent_status.$fecha."
-- and d.document in ('1724550270','1715619092','1723406649','1003410113','1722732805','1726336603','1721306015','1726113028')
;
";

                try {
                    DB::connection('cobefec3')->statement($query);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }

                try {
                    DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.sftpcampo_mn;");
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }

                $query="create table cobefec_reportes.sftpcampo_mn
select s.MARCA, s.CICLOF, s.NOMSOC, s.CEDSOC, s.VAPAMI, s.TRIESGO_ORIG, s.EDAD, s.PRODUCTO, s.DIRECCION, s.P1, s.T1, s.P2, s.T2, s.P3, s.T3, s.NOMBRE_CIUDAD, s.ZONA, s.MOTIVO_ANTERIOR, s.RESULTADO_ANTERIOR, s.OBSERVACION_ANTERIOR, s.RESULTADO, s.DESCRIPCION, s.OBSERVACION, s.FECHACOMPROMISO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.ULTIMO_TLF_CONTACTO) ULTIMO_TLF_CONTACTO,
s.TIPOLLAMADA,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'', if(((s.RESULTADO='Contacto sin Arreglo Mediato' or s.RESULTADO='Ofrecimiento al Corte' or s.RESULTADO='Refinancia' or s.RESULTADO='Notificado') and (s.MOTIVO='' or s.MOTIVO is null)),'No quiere informar el motivo',s.MOTIVO)) MOTIVO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'', if(((s.RESULTADO='Contacto sin Arreglo Mediato' or s.RESULTADO='Ofrecimiento al Corte' or s.RESULTADO='Refinancia' or s.RESULTADO='Notificado') and (s.MOTIVO='' or s.MOTIVO is null)),'No quiere informar el motivo',s.SUB_MOTIVO_NO_PAGO)) SUB_MOTIVO_NO_PAGO,
s.GESTOR, s.EMPRESA, s.CAMPAÑA, s.HORA_DE_CONTACTO, '' VISITA_DOMICILIO, '' VISITA_OFICINA,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.TIPO_CONTACTO) CANAL_DE_COMUNICACION,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.CONTACTO) CONTACTO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.HORARIO) HORARIO_DE_CONTACTO_FUTURO, s.peso, s.fecha, s.idgestion,
s.Borrar_Telefono1, s.Borrar_Telefono2, s.Borrar_Telefono3, s.Borrar_Direccion, s.Borrar_Correo, s.Anadir_Telefono1, s.Anadir_Telefono2, s.Anadir_Telefono3, s.Anadir_Direccion, s.Anadir_Correo
from cobefec_reportes.vista_sftpcampo s 
order by 1,4
;
";

                try {
                    DB::connection('cobefec3')->statement($query);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }


                /*
                //antes de la union

                $query="select s.MARCA, s.CICLOF, s.NOMSOC, s.CEDSOC, s.VAPAMI, s.TRIESGO_ORIG, s.EDAD, s.PRODUCTO, s.DIRECCION, s.P1, s.T1, s.P2, s.T2, s.P3, s.T3, s.NOMBRE_CIUDAD, s.ZONA, s.MOTIVO_ANTERIOR, s.RESULTADO_ANTERIOR, s.OBSERVACION_ANTERIOR, s.RESULTADO, s.DESCRIPCION, s.OBSERVACION, s.FECHACOMPROMISO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.ULTIMO_TLF_CONTACTO) ULTIMO_TLF_CONTACTO,
s.TIPOLLAMADA,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'', if(((s.RESULTADO='Contacto sin Arreglo Mediato' or s.RESULTADO='Ofrecimiento al Corte' or s.RESULTADO='Refinancia' or s.RESULTADO='Notificado') and (s.MOTIVO='' or s.MOTIVO is null)),'No quiere informar el motivo',s.MOTIVO)) MOTIVO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'', if(((s.RESULTADO='Contacto sin Arreglo Mediato' or s.RESULTADO='Ofrecimiento al Corte' or s.RESULTADO='Refinancia' or s.RESULTADO='Notificado') and (s.MOTIVO='' or s.MOTIVO is null)),'No quiere informar el motivo',s.SUB_MOTIVO_NO_PAGO)) SUB_MOTIVO_NO_PAGO,
s.GESTOR, s.EMPRESA, s.CAMPAÑA, s.HORA_DE_CONTACTO,VISITA_DOMICILIO,VISITA_OFICINA,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.TIPO_CONTACTO) CANAL_DE_COMUNICACION,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.CONTACTO) CONTACTO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.HORARIO) HORARIO_DE_CONTACTO_FUTURO,0, s.idgestion,
s.Borrar_Telefono1, s.Borrar_Telefono2, s.Borrar_Telefono3, s.Borrar_Direccion, s.Borrar_Correo, s.Anadir_Telefono1, s.Anadir_Telefono2, s.Anadir_Telefono3, s.Anadir_Direccion, s.Anadir_Correo
from cobefec_reportes.tmp_sftpcampodm s
;
";
                $sql=DB::connection('cobefec3')->select($query);
                $reportes = json_decode(json_encode($sql), true);


                //solo extraigo la informacion de cedusc en un solo array
                $unicos = array_column($reportes, 'CEDSOC');



                $res = array_diff($unicos, array_diff(array_unique($unicos), array_diff_assoc($unicos, array_unique($unicos))));

                foreach(array_unique($res) as $v) {
                    //echo "Duplicado1 $v en la posicion: " .  implode(', ', array_keys($res, $v)) . '<br />';
                    $posicion=array_keys($res, $v);
                    $count=count($posicion);
                    //echo $count."<br>";
                    $observacion="";
                    for ($i=0;$i<$count;$i++){
                        $observacion=$observacion." ".$reportes[$posicion[$i]]['OBSERVACION'];
                    }
                    $reportes[$posicion[0]]['OBSERVACION']=$observacion;
                }

                $reportes_1=Array();
                foreach($unicos as $k)
                {
                    $key = array_search($k, array_column($reportes, 'CEDSOC'));
                    unset($reportes[$key]['peso']);
                    unset($reportes[$key]['idgestion']);
                    //creo un nuevo arreglo sin valores duplicados
                    array_push($reportes_1, $reportes[$key]);
                }

                //nuevo
                $query="select * from cobefec_reportes.sftpcampo_mn s
where s.peso=(select max(peso) from cobefec_reportes.sftpcampo_mn where CEDSOC=s.CEDSOC and date(fecha)=date(s.fecha))
;";
                $sql=DB::connection('cobefec3')->select($query);
                $reportes2 = json_decode(json_encode($sql), true);

                try {
                    if($request->envio==1) {
                        DB::connection('cobefec3')->statement("update demarches set sent_status=1 where account_id in (select id from accounts where campaign_id=".$request->id_campana.") and sent_status=0;");
                    }
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }

                //solo extraigo la informacion de cedusc en un solo array
                $unicos = array_column($reportes2, 'CEDSOC');

                $res = array_diff($unicos, array_diff(array_unique($unicos), array_diff_assoc($unicos, array_unique($unicos))));
                foreach(array_unique($res) as $v) {
                    //echo "Duplicado2 $v en la posicion: " .  implode(', ', array_keys($res, $v)) . '<br />';
                    $posicion=array_keys($res, $v);
                    $count=count($posicion);
                    //echo $count."<br>";
                    $observacion="";
                    for ($i=0;$i<$count;$i++){
                        $observacion=$observacion." ".$reportes2[$posicion[$i]]['OBSERVACION'];
                    }
                    $reportes2[$posicion[0]]['OBSERVACION']=$observacion;
                }

                $reportes_2=Array();
                foreach($unicos as $k)
                {
                    $key = array_search($k, array_column($reportes2, 'CEDSOC'));
                    unset($reportes2[$key]['0']);
                    //unset($reportes2[$key]['idgestion']);
                    //creo un nuevo arreglo sin valores duplicados
                    array_push($reportes_2, $reportes2[$key]);
                }

                $reportes_f=Array();
                //$reportes_f=array_merge($reportes_1,$reportes_2);

                //solo extraigo la informacion de cedusc en un solo array
                $unicos = array_column($reportes_1, 'CEDSOC');
                //elimino los valores duplicados de array unicos
                $unicos = array_unique($unicos);

                foreach($unicos as $k)
                {
                    $key = array_search($k, array_column($reportes_1, 'CEDSOC'));
                    unset($reportes_1[$key]['0']);
                    //unset($reportes_1[$key]['idgestion']);
                    $iniciales=explode(" ",trim($reportes_1[$key]['GESTOR']));
                    if (count($iniciales)==2){
                        $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[1][0]);
                    }elseif(count($iniciales)==3){
                        $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[1][0]);
                    }
                    elseif(count($iniciales)==4){
                        $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[2][0]);
                    }

                    try{
                        $reportes_1[$key]['OBSERVACION']="COBEFEC ".date('Ymd')." ".$iniciales." ".$reportes_1[$key]['OBSERVACION'];
                    }
                    catch(\Exception $e) {
                        return $e->getMessage();
                    }


                    //creo un nuevo arreglo sin valores duplicados
                    array_push($reportes_f, $reportes_1[$key]);
                }

                //solo extraigo la informacion de cedusc en un solo array
                $unicos = array_column($reportes2, 'CEDSOC');
                //elimino los valores duplicados de array unicos
                $unicos = array_unique($unicos);
                foreach($unicos as $k)
                {
                    $key = array_search($k, array_column($reportes2, 'CEDSOC'));
                    unset($reportes2[$key]['peso']);
                    //agrego mÃ¡ximo 3 telefonos
                    $query="SELECT if(p.prefix is null, p.number, concat(p.prefix,p.number)) telefono
FROM cobefec3_mining.target_phones p, cobefec3.demarches d1
where d1.document=p.target_document and date(d1.created_at)=date(p.created_at) and d1.contact_type='CD' and d1.id='".$reportes2[$key]['idgestion']."'
;";
                    $sql=DB::connection('cobefec3')->select($query);
                    $numeros = json_decode(json_encode($sql), true);
                    $countTelefono=1;
                    foreach ($numeros as $numero) {
                        if ($countTelefono<=3){
                            $reportes2[$key]['Anadir_Telefono'.$countTelefono]=$numero['telefono'];
                            $countTelefono++;
                        }
                    }
                    unset($reportes2[$key]['idgestion']);
                    unset($reportes2[$key]['fecha']);
                    $iniciales=explode(" ",trim($reportes2[$key]['GESTOR']));
                    if (count($iniciales)==2){
                        $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[1][0]);
                    }elseif(count($iniciales)==3){
                        $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[1][0]);
                    }elseif(count($iniciales)==4){
                        $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[2][0]);
                    }
                    $reportes2[$key]['OBSERVACION']="ECE COBEFEC ".date('Ymd')." ".$iniciales." ".$reportes2[$key]['OBSERVACION'];
                    //creo un nuevo arreglo sin valores duplicados
                    array_push($reportes_f, $reportes2[$key]);
                }


                $reportes_f_diners=Array();
                $reportes_f_visa=Array();
                $reportes_f_discover=Array();
                $reportes_f_base=Array();
                foreach($reportes_f as $k)
                {
                    if ($k['MARCA']=='DINERS'){
                        array_push($reportes_f_diners, $k);
                    }
                    elseif($k['MARCA']=='VISA'){
                        array_push($reportes_f_visa, $k);
                    }
                    elseif($k['MARCA']=='DISCOVER'){
                        array_push($reportes_f_discover, $k);
                    }
                    elseif(count($k['MARCA'])>0){
                        array_push($reportes_f_base, $k);
                    }
                }
                */


                $fecha_reporte = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio_sftp)->format('Ymd');
                $reportes_f_diners=Array();
                $reportes_f_diners=reporteCampoSftp($envio,$fecha_reporte,$request->id_campana,'DINERS');
                $reportes_f_visa=Array();
                $reportes_f_visa=reporteCampoSftp($envio,$fecha_reporte,$request->id_campana,'VISA');
                $reportes_f_discover=Array();
                $reportes_f_discover=reporteCampoSftp($envio,$fecha_reporte,$request->id_campana,'DISCOVER');
                $reportes_f_discover_ud=Array();
                $reportes_f_discover_ud=reporteCampoSftp($envio,$fecha_reporte,$request->id_campana,'DISCOVER UDLA');
                $k=0;
                foreach ($reportes_f_discover_ud as $item) {
                    $reportes_f_discover_ud[$k]['MARCA']='DISCOVER';
                    $k++;
                }

                $reportes_f_discover=array_merge($reportes_f_discover,$reportes_f_discover_ud);
                //dd($reportes_f_discover);

                $reportes_f_base=Array();
                $total_gestiones=count($reportes_f_diners)+count($reportes_f_visa)+count($reportes_f_discover)+count($reportes_f_discover_ud);

                if ($total_gestiones==0){
                    return 'No existen cuentas gestionadas o las cuentas ya han sido enviadas.';
                }else{
                    $fecha_reporte = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio_sftp)->format('Y-m-d');
                    try {
                        \Excel::create('BASE A CARGAR CAMPO DISCOVER VISA DINERS COBEFEC '.$fecha_reporte, function($excel) use (&$reportes_f_diners,$reportes_f_visa,$reportes_f_discover,$reportes_f_base){

                            if (count($reportes_f_diners)>0){
                                $excel->sheet('DINERS', function($sheet) use($reportes_f_diners) {
                                    $sheet->fromArray($reportes_f_diners,null,'A1',true);
                                    //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                                });
                            }
                            if (count($reportes_f_visa)>0){
                                $excel->sheet('VISA', function($sheet) use($reportes_f_visa) {
                                    $sheet->fromArray($reportes_f_visa,null,'A1',true);
                                    //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                                });
                            }
                            if (count($reportes_f_discover)>0){
                                $excel->sheet('DISCOVER', function($sheet) use($reportes_f_discover) {
                                    $sheet->fromArray($reportes_f_discover,null,'A1',true);
                                    //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                                });
                            }
                            /*if (count($reportes_f_base)>0){
                                $excel->sheet('REGISTROS CON ERROR', function($sheet) use($reportes_f_discover) {
                                    $sheet->fromArray($reportes_f_discover,null,'A1',true);
                                    //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                                });
                            }*/
                        })->export('xlsx');
                    }
                    catch(\Exception $e) {
                        return $e->getMessage();
                    }
                }
            }
            elseif($request->id_producto==8){
                //Legal
                set_time_limit(0);
                $query="select concat(substr(date(d.created_at),1,4),substr(date(d.created_at),9,2),substr(date(d.created_at),6,2)) FECHA_GESTION, a.data ->> '$.cuenta' CUENTA, a.data ->> '$.nombre' NOMSOC, a.data ->> '$.cedula' CEDSOC, 
d.action RESULTADO, d.sub_action DESCRIPCION,
if(d.extra ->> '$.pp_date' is null,'',concat(substr(d.extra ->> '$.pp_date',1,4),substr(d.extra ->> '$.pp_date',6,2),substr(d.extra ->> '$.pp_date',9,2))) FECHACOMPROMISO,
d.phone TLF_CONTACTO,
concat(if(hour(d.tlc_time)<10,concat('0',hour(d.tlc_time)),hour(d.tlc_time)),if(minute(d.tlc_time)<10,concat('0',minute(d.tlc_time)),minute(d.tlc_time)), if(second(d.tlc_time)<10,concat('0',second(d.tlc_time)),second(d.tlc_time))) HORA_DE_CONTACTO,
concat('ECE COBEFEC AR ',concat(year(d.created_at), if(month(d.created_at)<10,concat('0',month(d.created_at)),month(d.created_at)), if(day(d.created_at)<10,concat('0',day(d.created_at)),day(d.created_at))),' ',if(a.current_agent is null,'',(select concat(substr(u.first_name,1,1),substr(u.last_name,1,1)) from cobefec3.accounts ac, cobefec3.agents ag, cobefec3.users u where ag.id=ac.current_agent and ag.user_id=u.id and ac.current_agent=a.current_agent group by 1)), d.description) OBSERVACIONES,
'ECE COBEFEC AR' EJECUTIVO, d.type 'TIPO_DE_GESTION'
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and d.sent_status=0
 and b.id=".$request->id_marca." and p.id=".$request->id_producto." and c.id=".$request->id_campana."
;
";
                try {
                    $sql=DB::connection('cobefec3')->select($query);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }

                $reportes = json_decode(json_encode($sql), true);


                $query="select name from cobefec3.campaigns where id=".$request->id_campana.";";
                try {
                    $sql=DB::connection('cobefec3')->select($query);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }
                $campana=$sql[0]->name;

                try {
                    \Excel::create('SFTP CUENTAS '.$campana.' COBEFEC '.date('m-d-Y'), function($excel) use (&$reportes){
                        $excel->sheet('DISCOVER', function($sheet) use($reportes) {
                            $sheet->fromArray($reportes,null,'A1',true);
                            //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                        });
                    })->export('xlsx');
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }
            }
        }
        //diners legal
        elseif($request->id_marca==10){
            if($request->id_producto==19){
                //Legal
                set_time_limit(0);
                ini_set ( 'memory_limit' , '2048M' );
                ini_set('max_execution_time', 800);
                $fecha='';
                if ($request->descargar==0){
                    $fecha='';
                }elseif($request->descargar==1){
                    $fecha = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio_sftp)->format('Y-m-d');
                    $fecha=" and date(d.created_at)='".$fecha."' ";
                }
                if ($request->descargar==1){$dfecha='';}elseif ($request->descargar==1){$dfecha='';}
                $query="select concat(substr(date(d.created_at),1,4),substr(date(d.created_at),6,2),substr(date(d.created_at),9,2)) FECHA_GESTION, a.data ->> '$.cuenta' CUENTA, a.data ->> '$.nombre' NOMSOC, a.data ->> '$.cedula' CEDSOC,
if(d.contact_type='NC',if(d.sub_action='Dirección no Corresponde' or d.sub_action='Trabajo Cerrado' or d.sub_action='Fallecimiento' or d.sub_action='Empleada' or d.sub_action='Socio de Viaje' or d.sub_action='Compañero de Trabajo' or d.sub_action='Domicilio Cerrado','Aún No Contactado Mañana',d.action),
if(d.contact_type='CI',if(d.sub_action='Esposa (o)' or d.sub_action='Familiares' or d.sub_action='Hijos / Padres' or d.sub_action='Secretaria / Asistente','Mensaje a Tercero',d.action),
if(d.contact_type='CD',if(d.sub_action='No Concreta','Contacto sin Arreglo Mediato',if(d.sub_action='Ofrecimiento de Arreglo' or d.sub_action='Ofrecimiento de Pago Total','Ofrecimiento al Corte',d.action)),d.action))) Resultado,
if(d.contact_type='NC',if(d.sub_action='Dirección no Corresponde','Ilocalizable Domicilio', if(d.sub_action='Trabajo Cerrado','Compañero de trabajo M',if(d.sub_action='Fallecimiento','Fallecimiento M',if(d.sub_action='Empleada','Empleada M',if(d.sub_action='Socio de Viaje','Viaje M',if(d.sub_action='Compañero de Trabajo','Compañero de Trabajo M',if(d.sub_action='Domicilio Cerrado','Empleada M',d.sub_action))))))),
if(d.contact_type='CI',if(d.sub_action='Esposa (o)','Esposa (o)',if(d.sub_action='Familiares','Familiares',if(d.sub_action='Hijos / Padres','Hijos / Padres',if(d.sub_action='Secretaria / Asistente','Secretaria / Asistente',d.sub_action)))),
if(d.contact_type='CD',if(d.sub_action='No Concreta','No Concreta',if(d.sub_action='Ofrecimiento de Arreglo','Ofrecimiento de Arreglo',if(d.sub_action='Ofrecimiento de Pago Total','Ofrecimiento de Pago Total',d.sub_action))),d.sub_action))) Descripcion,
if( (d.extra ->> '$.pp_date' is null) or (d.extra ->> '$.pp_date' = '') ,'',concat(substr(if(locate('-',substr(d.extra ->> '$.pp_date',1,10))=5, str_to_date(substr(extra ->> '$.pp_date',1,10),'%Y-%m-%d'),if((length(substr(d.extra ->> '$.pp_date',1,10)) - locate('-',substr(d.extra ->> '$.pp_date',1,10),locate('-',substr(d.extra ->> '$.pp_date',1,10))+1))=4, str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%d-%m-%Y'), str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%m-%d-%y'))),1,4),substr(if(locate('-',substr(d.extra ->> '$.pp_date',1,10))=5, str_to_date(substr(extra ->> '$.pp_date',1,10),'%Y-%m-%d'),if((length(substr(d.extra ->> '$.pp_date',1,10)) - locate('-',substr(d.extra ->> '$.pp_date',1,10),locate('-',substr(d.extra ->> '$.pp_date',1,10))+1))=4, str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%d-%m-%Y'), str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%m-%d-%y'))),6,2),substr(if(locate('-',substr(d.extra ->> '$.pp_date',1,10))=5, str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%Y-%m-%d'),if((length(substr(d.extra ->> '$.pp_date',1,10)) - locate('-',substr(d.extra ->> '$.pp_date',1,10),locate('-',substr(d.extra ->> '$.pp_date',1,10))+1))=4, str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%d-%m-%Y'), str_to_date(substr(d.extra ->> '$.pp_date',1,10),'%m-%d-%y'))),9,2))) FECHACOMPROMISO,
d.phone TLF_CONTACTO,
concat(if(hour(d.cex_time)<10,concat('0',hour(d.cex_time)),hour(d.cex_time)),if(minute(d.cex_time)<10,concat('0',minute(d.cex_time)),minute(d.cex_time)), if(second(d.cex_time)<10,concat('0',second(d.cex_time)),second(d.cex_time))) HORA_DE_CONTACTO,
concat('ECE COBEFEC AR ',concat(year(d.created_at), if(month(d.created_at)<10,concat('0',month(d.created_at)),month(d.created_at)), if(day(d.created_at)<10,concat('0',day(d.created_at)),day(d.created_at))),' ',if(a.current_agent is null,'',(select concat(substr(u.first_name,1,1),substr(u.last_name,1,1)) from cobefec3.accounts ac, cobefec3.agents ag, cobefec3.users u where ag.id=ac.current_agent and ag.user_id=u.id and ac.current_agent=a.current_agent group by 1)), d.description) OBSERVACIONES,
'ECE COBEFEC AR' EJECUTIVO, d.type 'TIPO_DE_GESTION' -- , a.id idcuenta
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and
c.id=".$request->id_campana." and d.sent_status=0 and d.type='DM' ".$fecha."
UNION ALL
select concat(substr(date(d.created_at),1,4),substr(date(d.created_at),6,2),substr(date(d.created_at),9,2)) FECHA_GESTION, a.data ->> '$.cuenta' CUENTA, a.data ->> '$.nombre' NOMSOC, a.data ->> '$.cedula' CEDSOC, 
d.action RESULTADO, d.sub_action DESCRIPCION,
if( (d.extra ->> '$.pp_date' is null) or (d.extra ->> '$.pp_date' = '') ,'',concat(substr(if(locate('-',d.extra ->> '$.pp_date')=5, str_to_date(d.extra ->> '$.pp_date','%Y-%m-%d'),if((length(d.extra ->> '$.pp_date') - locate('-',d.extra ->> '$.pp_date',locate('-',d.extra ->> '$.pp_date')+1))=4, str_to_date(d.extra ->> '$.pp_date','%d-%m-%Y'), str_to_date(d.extra ->> '$.pp_date','%m-%d-%y'))),1,4),substr(if(locate('-',d.extra ->> '$.pp_date')=5, str_to_date(d.extra ->> '$.pp_date','%Y-%m-%d'),if((length(d.extra ->> '$.pp_date') - locate('-',d.extra ->> '$.pp_date',locate('-',d.extra ->> '$.pp_date')+1))=4, str_to_date(d.extra ->> '$.pp_date','%d-%m-%Y'), str_to_date(d.extra ->> '$.pp_date','%m-%d-%y'))),6,2),substr(if(locate('-',d.extra ->> '$.pp_date')=5, str_to_date(d.extra ->> '$.pp_date','%Y-%m-%d'),if((length(d.extra ->> '$.pp_date') - locate('-',d.extra ->> '$.pp_date',locate('-',d.extra ->> '$.pp_date')+1))=4, str_to_date(d.extra ->> '$.pp_date','%d-%m-%Y'), str_to_date(d.extra ->> '$.pp_date','%m-%d-%y'))),9,2))) FECHACOMPROMISO,
d.phone TLF_CONTACTO,
concat(if(hour(d.tlc_time)<10,concat('0',hour(d.tlc_time)),hour(d.tlc_time)),if(minute(d.tlc_time)<10,concat('0',minute(d.tlc_time)),minute(d.tlc_time)), if(second(d.tlc_time)<10,concat('0',second(d.tlc_time)),second(d.tlc_time))) HORA_DE_CONTACTO,
concat('ECE COBEFEC AR ',concat(year(d.created_at), if(month(d.created_at)<10,concat('0',month(d.created_at)),month(d.created_at)), if(day(d.created_at)<10,concat('0',day(d.created_at)),day(d.created_at))),' ',if(a.current_agent is null,'',(select concat(substr(u.first_name,1,1),substr(u.last_name,1,1)) from cobefec3.accounts ac, cobefec3.agents ag, cobefec3.users u where ag.id=ac.current_agent and ag.user_id=u.id and ac.current_agent=a.current_agent group by 1)), d.description) OBSERVACIONES,
'ECE COBEFEC AR' EJECUTIVO, d.type 'TIPO_DE_GESTION' -- , a.id idcuenta
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and a.id=d.account_id and 
c.id=".$request->id_campana." and d.sent_status=0 and d.type='MN' ".$fecha."
;";
                try {
                    $sql=DB::connection('cobefec3')->select($query);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }

                $reportes = json_decode(json_encode($sql), true);


                $query="select name from cobefec3.campaigns where id=".$request->id_campana.";";
                try {
                    $sql=DB::connection('cobefec3')->select($query);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }
                $campana=$sql[0]->name;

                try {
                    ini_set ( 'memory_limit' , '2048M' );
                    ini_set('max_execution_time', 800);
                    \Excel::create('SFTP CUENTAS '.$campana.' COBEFEC '.date('m-d-Y'), function($excel) use (&$reportes){
                        $excel->sheet('SFTP CUENTAS', function($sheet) use($reportes) {
                            $sheet->fromArray($reportes,null,'A1',true);
                            //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                        });
                    })->export('xlsx');
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }
            }
        }
    }

    public function recupMetaDiners(){
        try{
            //$marcas=tbl_brands::get();
            $marcas=DB::connection('cobefec3')->select("SELECT id,name FROM cobefec3.brands where id=2;");
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
        return view('reporteNuevoSistema/diners/recuperacionMeta', compact('marcas'));
    }

    public function recupMetaDinersR(Request $request)
    {
        //campo
        if ($request->id_producto==1 || $request->id_producto==2){
            set_time_limit(0);
            $query="select concat(a.data ->> '$.cedsoc', a.data ->> '$.codpri', a.data ->> '$.ciclof') CONCATENAR,upper(p.name) CARTERA, a.data ->> '$.ciclof' CICLO, a.data ->> '$.nomsoc' NOMBRE, a.data ->> '$.cedsoc' CEDULA, a.data ->> '$.edcart' EDCART, a.data ->> '$.deuda_inicial' SALDO_INICIAL, a.to_recover VALOR_ASIGNADO, a.recovered RECUPERACION_A_LA_FECHA, (a.to_recover - a.recovered) SALDO_ACTUAL, 
if(a.current_agent is null,'', (select substr(u.email,1,locate('@',u.email)-1) GESTOR from cobefec3.users u, cobefec3.agents ag where ag.user_id=u.id and ag.id=a.current_agent)) GESTOR_DEFINITIVO, a.data ->> '$.codpri' PRODUCTO,  a.data ->> '$.triesgo' RIESGO_INICIAL, a.data ->> '$.nombre_ciudad' NOMBRE_CIUDAD, a.data ->> '$.zona' ZONA, a.data ->> '$.ejecutivo' ECE, a.data ->> '$.nomestab' INFORMATIVA_REF_O_RESTRUCT, a.data ->> '$.focalizacion' FOCALIZACION, a.data ->> '$.codret' COD_RET, a.data ->> '$.actuales' ACTUALES_ORIG, a.data ->> '$.d30' D30_ORIG, a.data ->> '$.d60' D60_ORIG, a.data ->> '$.d90' D90_ORIG, a.data ->> '$.dmas90' DMAS90_ORIG, a.data ->> '$.interes_total' INTERES_TOTAL , '' FECHA_DE_ASIGNACION, '' FECHA_DE_CIERRE, a.data ->> '$.meta_cobefec' META_COBEFEC, a.data ->> '$.meta_diners' META_DINERS, a.data ->> '$.grupo' GRUPO
from cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p
where  c.id=a.campaign_id and p.id=c.product_id and p.brand_id=".$request->id_marca." and c.product_id=".$request->id_producto." and a.campaign_id=".$request->id_campana."
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
                $reportes[$i]['VALOR_ASIGNADO']=($reporte['VALOR_ASIGNADO']);
                $reportes[$i]['META_COBEFEC']=(['META_COBEFEC']);
                $reportes[$i]['META_DINERS']=($reporte['META_DINERS']);
                $reportes[$i]['META_COBEFEC1']=($reporte['VALOR_ASIGNADO']*doubleval($reporte['META_COBEFEC']));
                $reportes[$i]['META_DINERS1']=($reporte['VALOR_ASIGNADO']*doubleval($reporte['META_DINERS']));

                $reportes[$i]['SALDO_INICIAL']=round($reporte['SALDO_INICIAL'], 2);
                $reportes[$i]['VALOR_ASIGNADO']=round($reporte['VALOR_ASIGNADO'], 2);
                $reportes[$i]['RECUPERACION_A_LA_FECHA']=round($reporte['RECUPERACION_A_LA_FECHA'], 2);

                $reportes[$i]['REMANENTE_COBEFEC']=round(($reporte['VALOR_ASIGNADO']*$reporte['META_COBEFEC'])-$reporte['RECUPERACION_A_LA_FECHA'],2);
                $reportes[$i]['REMANENTE_DINERS']=round(($reporte['VALOR_ASIGNADO']*$reporte['META_DINERS'])-$reporte['RECUPERACION_A_LA_FECHA'],2);

                $reportes[$i]['SALDO_ACTUAL']=round($reporte['SALDO_ACTUAL'], 2);
                $reportes[$i]['RIESGO_INICIAL']=round($reporte['RIESGO_INICIAL'], 2);
                $reportes[$i]['ACTUALES_ORIG']=round($reporte['ACTUALES_ORIG'], 2);
                $reportes[$i]['D30_ORIG']=round($reporte['D30_ORIG'], 2);
                $reportes[$i]['D60_ORIG']=round($reporte['D60_ORIG'], 2);
                $reportes[$i]['D90_ORIG']=round($reporte['D90_ORIG'], 2);
                $reportes[$i]['DMAS90_ORIG']=round($reporte['DMAS90_ORIG'], 2);
                $reportes[$i]['INTERES_TOTAL']=round($reporte['INTERES_TOTAL'], 2);
                $reportes[$i]['META_COBEFEC']=round($reporte['META_COBEFEC'], 2);
                $reportes[$i]['META_DINERS']=round($reporte['META_DINERS'], 2);
                $i++;
            }

            $query="select name from cobefec3.campaigns where id=".$request->id_campana.";";
            try{
                $sql=DB::connection('cobefec3')->select($query);
            }
            catch(\Exception $e){
                return $e->getMessage();
            }

            $campana=$sql[0]->name;
            try{
                \Excel::create('RECUPERACION VS META '.$campana.' COBEFEC '.date('m-d-Y'), function($excel) use (&$reportes){
                    $excel->sheet('DISCOVER', function($sheet) use($reportes) {
                        $sheet->fromArray($reportes,null,'A1',true);
                        //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                    });
                })->export('xlsx');
            }catch(\Exception $e){
                return $e->getMessage();
            }
        }elseif($request->id_producto==19){
            set_time_limit(0);
            $query="select concat(a.data ->> '$.cedula', a.data ->> '$.marca', a.data ->> '$.ciclo') CONCATENAR, 'LEGAL' CARTERA, a.data ->> '$.ciclo' CICLO, a.data ->> '$.nombre' NOMBRE, a.data ->> '$.cedula' CEDULA, a.data ->> '$.edad_facturada' EDCART, a.data ->> '$.saldo_actual' SALDO_INICIAL, a.data ->> '$.saldo_actual' VALOR_ASIGNADO, a.data ->> '$.abonos' RECUPERACION_A_LA_FECHA, (a.data ->> '$.saldo_actual' - a.data ->> '$.abonos') SALDO_ACTUAL, 
if(a.current_agent is null,'', (select substr(u.email,1,locate('@',u.email)-1) GESTOR from cobefec3.users u, cobefec3.agents ag where ag.user_id=u.id and ag.id=a.current_agent)) GESTOR_DEFINITIVO, upper(p.name) PRODUCTO,  a.data ->> '$.interes' RIESGO_INICIAL, a.data ->> '$.ciudad' NOMBRE_CIUDAD, a.data ->> '$.zona' ZONA, a.data ->> '$.ejecutivo' ECE, a.data ->> '$.nomestab' INFORMATIVA_REF_O_RESTRUCT, a.data ->> '$.focalizacion' FOCALIZACION, a.data ->> '$.cod_cancelacion' COD_RET, a.data ->> '$.actuales' ACTUALES_ORIG, a.data ->> '$.d30' D30_ORIG, a.data ->> '$.d60' D60_ORIG, a.data ->> '$.d90' D90_ORIG, a.data ->> '$.dmas90' DMAS90_ORIG, a.data ->> '$.interes_total' INTERES_TOTAL , '' FECHA_DE_ASIGNACION, '' FECHA_DE_CIERRE, a.data ->> '$.meta_cobefec' META_COBEFEC, a.data ->> '$.meta_diners' META_DINERS, a.data ->> '$.grupo' GRUPO
from cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p
where  c.id=a.campaign_id and p.id=c.product_id and a.campaign_id=".$request->id_campana."
;";
            try{
                $sql=DB::connection('cobefec3')->select($query);
            }
            catch(\Exception $e) {
                return $e->getMessage();
            }

            $reportes = json_decode(json_encode($sql), true);
            $i=0;
            foreach($reportes as $reporte) {
                $reportes[$i]['VALOR_ASIGNADO']=($reporte['VALOR_ASIGNADO']);
                $reportes[$i]['META_COBEFEC']=($reporte['META_COBEFEC']);
                $reportes[$i]['META_DINERS']=($reporte['META_DINERS']);
                $reportes[$i]['META_COBEFEC1']=($reporte['VALOR_ASIGNADO']*$reporte['META_COBEFEC']);
                $reportes[$i]['META_DINERS1']=($reporte['VALOR_ASIGNADO']*$reporte['META_DINERS']);

                $reportes[$i]['SALDO_INICIAL']=round($reporte['SALDO_INICIAL'], 2);
                $reportes[$i]['VALOR_ASIGNADO']=round($reporte['VALOR_ASIGNADO'], 2);
                $reportes[$i]['RECUPERACION_A_LA_FECHA']=round($reporte['RECUPERACION_A_LA_FECHA'], 2);

                $reportes[$i]['REMANENTE_COBEFEC']=round(($reporte['VALOR_ASIGNADO']*$reporte['META_COBEFEC'])-$reporte['RECUPERACION_A_LA_FECHA'], 2);
                $reportes[$i]['REMANENTE_DINERS']=round(($reporte['VALOR_ASIGNADO']*$reporte['META_DINERS'])-$reporte['RECUPERACION_A_LA_FECHA'], 2);


                $reportes[$i]['SALDO_ACTUAL']=round($reporte['SALDO_ACTUAL'], 2);
                $reportes[$i]['RIESGO_INICIAL']=round($reporte['RIESGO_INICIAL'], 2);
                $reportes[$i]['ACTUALES_ORIG']=round($reporte['ACTUALES_ORIG'], 2);
                $reportes[$i]['D30_ORIG']=round($reporte['D30_ORIG'], 2);
                $reportes[$i]['D60_ORIG']=round($reporte['D60_ORIG'], 2);
                $reportes[$i]['D90_ORIG']=round($reporte['D90_ORIG'], 2);
                $reportes[$i]['DMAS90_ORIG']=round($reporte['DMAS90_ORIG'], 2);
                $reportes[$i]['INTERES_TOTAL']=round($reporte['INTERES_TOTAL'], 2);
                $reportes[$i]['META_COBEFEC']=round($reporte['META_COBEFEC'], 2);
                $reportes[$i]['META_DINERS']=round($reporte['META_DINERS'], 2);

                $i++;
            }

            $query="select name from cobefec3.campaigns where id=".$request->id_campana.";";

            try{
                $sql=DB::connection('cobefec3')->select($query);
            }catch(\Exception $e) {
                return $e->getMessage();
            }

            $campana=$sql[0]->name;
            try{
                \Excel::create('RECUPERACION VS META '.$campana.' COBEFEC '.date('m-d-Y'), function($excel) use (&$reportes){
                    $excel->sheet('DISCOVER', function($sheet) use($reportes){
                        $sheet->fromArray($reportes,null,'A1',true);
                        //PESTAÑAS ORDEN->DINERS DISCOVER VISA
                    });
                })->export('xlsx');
            }catch(\Exception $e){
                return $e->getMessage();
            }
        }
    }

    public function focalizacionCartera(Request $request)
    {
        //campo
        //variables add abono,gestor,
        set_time_limit(0);
        $focalizados=Array();
        $porcentajeDiners=[array($request->diners_mas_90,'dmas90'),array($request->diners_90,'d90'),array($request->diners_60,'d60'),array($request->diners_30,'d30')];
        $porcentajeVisa=[array($request->visa_mas_90,'dmas90'),array($request->visa_90,'d90'),array($request->visa_60,'d60'),array($request->visa_30,'d30')];
        $porcentajeDiscover=[array($request->discover_mas_90,'dmas90'),array($request->discover_90,'d90'),array($request->discover_60,'d60'),array($request->discover_30,'d30')];

        $marca_ciclo_zona="select a.data ->> '$.codpri' MARCA, a.data ->> '$.ciclof' CICLO, a.data ->> '$.zona' ZONA
from cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p
where  c.id=a.campaign_id and p.id=c.product_id and p.brand_id=".$request->id_marca." and c.product_id=".$request->id_producto." and a.campaign_id=".$request->id_campana."  
and a.data ->> '$.saldo_actual'>20 and a.data ->> '$.codret'<>'88'
GROUP BY 1,2,3
;
";
        try {
            $sql=DB::connection('cobefec3')->select($marca_ciclo_zona);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $marca_ciclo_zonas = json_decode(json_encode($sql), true);
        foreach ($marca_ciclo_zonas as $marca_ciclo_zona)
        {
            $porcentajes=Array();
            if ($marca_ciclo_zona['MARCA']=='DINERS'){
                $porcentajes=[array($request->diners_mas_90,'dmas90'),array($request->diners_90,'d90'),array($request->diners_60,'d60'),array($request->diners_30,'d30')];
            }
            elseif($marca_ciclo_zona['MARCA']=='VISA'){
                $porcentajes=[array($request->visa_mas_90,'dmas90'),array($request->visa_90,'d90'),array($request->visa_60,'d60'),array($request->visa_30,'d30')];
            }
            elseif($marca_ciclo_zona['MARCA']=='DISCOVER'){
                $porcentajes=[array($request->discover_mas_90,'dmas90'),array($request->discover_90,'d90'),array($request->discover_60,'d60'),array($request->discover_30,'d30')];
            }


            foreach ($porcentajes as $porcentaje)
            {
                $query="select a.id id_cuenta, CAST(a.data ->> '$.".$porcentaje[1]."'as DECIMAL(9,2)) D_ORIG
from cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p
where  c.id=a.campaign_id and p.id=c.product_id and p.brand_id=".$request->id_marca." and c.product_id=".$request->id_producto." and a.campaign_id=".$request->id_campana."
and data ->> '$.codpri'='".$marca_ciclo_zona['MARCA']."' and data ->> '$.ciclof'='".$marca_ciclo_zona['CICLO']."' and data ->> '$.zona'='".$marca_ciclo_zona['ZONA']."' and a.data ->> '$.".$porcentaje[1]."'>0
order by 2 DESC
;
";
                try {
                    $sql=DB::connection('cobefec3')->select($query);
                }catch(\Exception $e) {
                    return $e->getMessage();
                }
                $dias=json_decode(json_encode($sql), true);

                $suma=0;
                foreach ($dias as $dia){
                    $suma=$suma+$dia['D_ORIG'];
                }
                $valor_focalizado=$suma*($porcentaje[0]/100);
                //var_dump($dias);
                //var_dump($porcentaje);
                //echo $suma."<br>".$valor_focalizado."<br>";

                $suma=0.1;
                foreach ($dias as $dia){
                    if($suma<=$valor_focalizado){
                        array_push($focalizados,$dia['id_cuenta']);
                        $suma=$suma+$dia['D_ORIG'];
                    }
                }
            }
        }

        $query="select a.id id_cuenta,a.data ->> '$.codpri' MARCA, a.data ->> '$.ciclof' CICLO, a.data ->> '$.zona' ZONA, a.data ->> '$.nombre_ciudad' CIUDAD, a.data ->> '$.codret' COD_RET, a.data ->> '$.cedsoc' CEDULA, a.data ->> '$.asignacion' VALOR_FACTURADO,
if((select d.action from cobefec3.demarches d where d.document=a.target_document and d.account_id=a.id and date(d.created_at)=a.major_weight_date order by d.id desc limit 1) is null,'',(select d.action from cobefec3.demarches d where d.document=a.target_document and d.account_id=a.id and date(d.created_at)=a.major_weight_date order by d.id desc limit 1)) MOTIVO_CIERRE,
a.data ->> '$.actuales' ACTUALES_ORIG, a.data ->> '$.d30' D30_ORIG, a.data ->> '$.d60' D60_ORIG, a.data ->> '$.d90' D90_ORIG, a.data ->> '$.dmas90' DMAS90_ORIG, a.data ->> '$.abono' ABONO, a.data ->> '$.saldo_actual' SALDO_ACTUAL,  CONCAT(u.first_name,' ',u.last_name) GESTOR, '' FOCALIZACION
from cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p, cobefec3.agents ag, cobefec3.users u
where  c.id=a.campaign_id and p.id=c.product_id and p.brand_id=".$request->id_marca." and c.product_id=".$request->id_producto." and a.campaign_id=".$request->id_campana." and ag.id=a.current_agent and u.id=ag.user_id and a.data ->> '$.saldo_actual'>20 and a.data ->> '$.codret'<>'88'
order by 3
;
";
        try
        {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $reportes = json_decode(json_encode($sql), true);
        $i=0;
        foreach($reportes as $reporte) {
            $reportes[$i]['VALOR_FACTURADO']=round($reporte['VALOR_FACTURADO'],2);
            $reportes[$i]['ACTUALES_ORIG']=round($reporte['ACTUALES_ORIG'],2);
            $reportes[$i]['D30_ORIG']=round($reporte['D30_ORIG'],2);
            $reportes[$i]['D60_ORIG']=round($reporte['D60_ORIG'],2);
            $reportes[$i]['D60_ORIG']=round($reporte['D60_ORIG'],2);
            $reportes[$i]['D90_ORIG']=round($reporte['D90_ORIG'],2);
            $reportes[$i]['DMAS90_ORIG']=round($reporte['DMAS90_ORIG'],2);
            $reportes[$i]['ABONO']=round($reporte['ABONO'],2);
            $reportes[$i]['SALDO_ACTUAL']=round($reporte['SALDO_ACTUAL'],2);
            //$reportes[$i]['SALDO']=($reportes[$i]['VALOR_FACTURADO']-$reportes[$i]['ABONO']);
            $i++;
        }

        $focalizados=array_unique($focalizados);
        foreach ($focalizados as $focalizado){
            $key = array_search($focalizado, array_column($reportes, 'id_cuenta'));
            //$reportes[$key]['FOCALIZACION']=$reportes[$key]['FOCALIZACION'].'X';
            $reportes[$key]['FOCALIZACION']='X';
        }

        $query="select name from cobefec3.campaigns where id=".$request->id_campana.";";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $campana=$sql[0]->name;
        try {
            \Excel::create('REPORTE FOCALIZACIONES DE CARTERA '.$campana.' COBEFEC '.date('m-d-Y'), function($excel) use (&$reportes){
                $excel->sheet('DINERS', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                    //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                });
            })->export('xlsx');
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

    }

    public function focalizacionCarteraBackup(Request $request)
    {
        //campo
        set_time_limit(0);
        $query="select a.data ->> '$.ciclof' CICLO, a.data ->> '$.cedsoc' CEDULA, a.data ->> '$.asignacion' VALOR_FACTURADO, a.data ->> '$.zona' ZONA,
if((select d.action from cobefec3.demarches d where d.document=a.target_document and d.account_id=a.id and date(d.created_at)=a.major_weight_date order by d.id desc limit 1) is null,'',(select d.action from cobefec3.demarches d where d.document=a.target_document and d.account_id=a.id and date(d.created_at)=a.major_weight_date order by d.id desc limit 1)) MOTIVO_CIERRE,
a.data ->> '$.actuales' ACTUALES_ORIG, a.data ->> '$.d30' D30_ORIG, a.data ->> '$.d60' D60_ORIG, a.data ->> '$.d90' D90_ORIG, a.data ->> '$.dmas90' DMAS90_ORIG, '' FOCALIZACION
from cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p
where  c.id=a.campaign_id and p.id=c.product_id and p.brand_id=".$request->id_marca." and c.product_id=".$request->id_producto." and a.campaign_id=".$request->id_campana."
order by 4
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
            $reportes[$i]['VALOR_FACTURADO']=round($reporte['VALOR_FACTURADO'],2);
            $reportes[$i]['ACTUALES_ORIG']=round($reporte['ACTUALES_ORIG'],2);
            $reportes[$i]['D30_ORIG']=round($reporte['D30_ORIG'],2);
            $reportes[$i]['D60_ORIG']=round($reporte['D60_ORIG'],2);
            $reportes[$i]['D60_ORIG']=round($reporte['D60_ORIG'],2);
            $reportes[$i]['D90_ORIG']=round($reporte['D90_ORIG'],2);
            $reportes[$i]['DMAS90_ORIG']=round($reporte['DMAS90_ORIG'],2);

            $i++;
        }

        $query="select name from cobefec3.campaigns where id=".$request->id_campana.";";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $campana=$sql[0]->name;
        try {
            \Excel::create('REPORTE FOCALIZACIONES DE CARTERA '.$campana.' COBEFEC '.date('m-d-Y'), function($excel) use (&$reportes){
                $excel->sheet('DISCOVER', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                    //PESTAÑAS ORDEN-> DINERS DISCOVER VISA
                });
            })->export('xlsx');
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function reporteMarcaciones(Request $request)
    {
        set_time_limit(0);
        $fecha = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');

        try {
            $query="select id, name from cobefec3.campaigns where product_id=2 and date(created_at)='".$fecha."';";
            $campanas=DB::connection('cobefec3')->select($query);
            $campanas=json_decode(json_encode($campanas),true);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $reportes=Array();
        $contadorReporte=0;
        foreach ($campanas as $campana){
            try {
                $cuentas=DB::connection('cobefec3')->select("SELECT id, target_document, data ->> '$.t1' t1,data ->> '$.t2' t2,data ->> '$.t3' t3,data ->> '$.t4' t4,data ->> '$.t5' t5,data ->> '$.t6' t6, data ->> '$.nomsoc' nombres FROM cobefec3.accounts where campaign_id=".$campana['id'].";");
                $cuentas=json_decode(json_encode($cuentas),true);
                foreach ($cuentas as $cuenta){
                    try {
                        $gestiones=DB::connection('cobefec3')->select("
                                SELECT d.id id_gestion, DATE(d.created_at) fecha, 
                                TIME_FORMAT(d.created_at, '%H:%i') hora,
                                d.document ci, a.data ->> '$.nomsoc' nombre,
                                d.phone telefono,
                                d.action gestion,
                                d.description observacion,
                                ifnull(d.extra ->> '$.pp_date','') fecha_pp,
                                ifnull(d.extra ->> '$.pp_amount','') valor_pp,
                                c.name campana,
                                d.uniqueid
                                from cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
                                where c.id=a.campaign_id
                                and d.account_id=a.id and a.id=".$cuenta['id']." and date(a.created_at)='".$fecha."';");
                        $gestiones=json_decode(json_encode($gestiones),true);
                    }
                    catch(\Exception $e) {
                        return $e->getMessage();
                    }
                    $contadorGestion=1;
                    if (count($gestiones)>0){
                        foreach ($gestiones as $gestion){
                            $reportes[$contadorReporte]['status']='NO ANSWER';
                            switch ($gestion['gestion']) {
                                case 'Abono': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Aún No Contactado Mañana': $reportes[$contadorReporte]['status']='NO ANSWER';break;
                                case 'Aún No Contactado Noche': $reportes[$contadorReporte]['status']='NO ANSWER';break;
                                case 'Aún No Contactado Tarde': $reportes[$contadorReporte]['status']='NO ANSWER';break;
                                case 'Caso Problema / Reclamo': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Cliente Cancelo': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Compañero de Trabajo': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Compromiso de pago': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'CONFIRMACION DE PAGO': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Cont. Sin Arreglo Definitivo': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Contacto sin Arreglo Mediato': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Contestadora': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Convenio de pago': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Dificultad de Pago Deudor': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Equivocado': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Esposa (o)': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Familiares': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Hijos / Padres': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Ilocalizable': $reportes[$contadorReporte]['status']='NO ANSWER';break;
                                case 'Ilocalizable Domicilio': $reportes[$contadorReporte]['status']='NO ANSWER';break;
                                case 'Ilocalizable telefÃ³nicamente': $reportes[$contadorReporte]['status']='NO ANSWER';break;
                                case 'Incumplimiento Promesa De Pago': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Liquidacion en proceso': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Mensaje a Tercero': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Mensaje Con Tercero': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Mensaje Conyuge': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Mensaje Tercero': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'No Concreta': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'No Contacto Mañana':
                                    try{
                                        $estatus=DB::connection('predictivo2')->select("SELECT status estatus FROM call_center.calls where uniqueid='".$gestion['uniqueid']."';");
                                        $estatus = json_decode(json_encode($estatus), true);
                                    }
                                    catch(\Exception $e) {
                                        return $e->getMessage();
                                    }
                                    if (count($estatus)>0)
                                    {
                                        if($estatus[0]['estatus']='Success'){
                                            $reportes[$contadorReporte]['status']='ANSWERED';
                                        }else{
                                            $reportes[$contadorReporte]['status']='NO ANSWER';
                                        }

                                    }else{
                                        $reportes[$contadorReporte]['estatus']='NO ANSWER';
                                    }

                                    break;
                                case 'No Contacto Tarde':
                                    try{
                                        $estatus=DB::connection('predictivo2')->select("SELECT status estatus FROM call_center.calls where uniqueid='".$gestion['uniqueid']."';");
                                        $estatus = json_decode(json_encode($estatus), true);
                                    }
                                    catch(\Exception $e) {
                                        return $e->getMessage();
                                    }
                                    if (count($estatus)>0)
                                    {
                                        if($estatus[0]['estatus']='Success'){
                                            $reportes[$contadorReporte]['status']='ANSWERED';
                                        }else{
                                            $reportes[$contadorReporte]['status']='NO ANSWER';
                                        }

                                    }else{
                                        $reportes[$contadorReporte]['estatus']='NO ANSWER';
                                    }

                                    break;
                                case 'No Contesta': $reportes[$contadorReporte]['status']='NO ANSWER';break;
                                case 'Notificado': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'NOVEDAD': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Ofrecimiento al Corte': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Ofrecimiento de Pago': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Ofrecimiento Incumplido': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Pago Realizado': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'PAGO REALIZADOS (sin respaldos)': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'PAGOS COBEFEC (para supervisor)': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Promesa De Pago': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Promesa de pago Conyuge': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Promesa de Pago Deudor': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Refinancia': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Refinanciamiento en proceso': $reportes[$contadorReporte]['status']='ANSWERED';break;
                                case 'Sin Arreglo Cliente':
                                    try{
                                        $estatus=DB::connection('predictivo2')->select("SELECT status estatus FROM call_center.calls where uniqueid='".$gestion['uniqueid']."';");
                                        $estatus = json_decode(json_encode($estatus), true);
                                    }
                                    catch(\Exception $e) {
                                        return $e->getMessage();
                                    }
                                    if (count($estatus)>0)
                                    {
                                        if($estatus[0]['estatus']='Success'){
                                            $reportes[$contadorReporte]['status']='ANSWERED';
                                        }else{
                                            $reportes[$contadorReporte]['status']='NO ANSWER';
                                        }

                                    }else{
                                        $reportes[$contadorReporte]['estatus']='NO ANSWER';
                                    }
                                    break;
                                case 'Sin Arreglo Tercero':
                                    try{
                                        $estatus=DB::connection('predictivo2')->select("SELECT status estatus FROM call_center.calls where uniqueid='".$gestion['uniqueid']."';");
                                        $estatus = json_decode(json_encode($estatus), true);
                                    }
                                    catch(\Exception $e) {
                                        return $e->getMessage();
                                    }
                                    if (count($estatus)>0)
                                    {
                                        if($estatus[0]['estatus']='Success'){
                                            $reportes[$contadorReporte]['status']='ANSWERED';
                                        }else{
                                            $reportes[$contadorReporte]['status']='NO ANSWER';
                                        }

                                    }else{
                                        $reportes[$contadorReporte]['estatus']='NO ANSWER';
                                    }
                                    break;
                                case 'VISITA ENTREGA DE PAGO Y RETENCIONES': $reportes[$contadorReporte]['status']='ANSWERED';break;
                            }

                            if (count($gestion['fecha'])>0){
                                $reportes[$contadorReporte]['contadorGestion']=$contadorGestion;
                                $reportes[$contadorReporte]['id_gestion']=$gestion['id_gestion'];
                                $reportes[$contadorReporte]['fecha']=$gestion['fecha'];
                                $reportes[$contadorReporte]['hora']=$gestion['hora'];
                                $reportes[$contadorReporte]['ci']=$gestion['ci'];
                                $reportes[$contadorReporte]['nombre']=$gestion['nombre'];
                                $reportes[$contadorReporte]['telefono']=$gestion['telefono'];
                                $reportes[$contadorReporte]['gestion']=$gestion['gestion'];
                                $reportes[$contadorReporte]['observacion']=$gestion['observacion'];
                                $reportes[$contadorReporte]['fecha_pp']=$gestion['fecha_pp'];
                                $reportes[$contadorReporte]['valor_pp']=$gestion['valor_pp'];
                                $reportes[$contadorReporte]['campana']=$gestion['campana'];
                                $reportes[$contadorReporte]['servidor']='gestion';
                                $contadorReporte++;
                                $contadorGestion++;
                            }
                        }
                    }

                    //LISTO LOS TELEFONOS A INVESTIGAR EN EL SERVER MANUAL Y PREDICTIVO
                    $listaTelefonos='';

                    for ($i=1; $i<=6; $i++){
                        if ($cuenta['t'.$i]!=null && strlen($cuenta['t'.$i])>0){
                            $listaTelefonos=$listaTelefonos."'".$cuenta['t'.$i]."',";
                        }
                    }

                    if ($listaTelefonos!=''){
                        $listaTelefonos=substr($listaTelefonos,0,-1);
                        //SERVIDOR PREDICTIVO

                        try{
                            //validador mayor a 6 dias busca en el historico
                            $cDate =  new Carbon($fecha);

                                $query="select disposition, dst, TIME_FORMAT(calldate, '%H:%i') hora from asteriskcdrdb.cdr where dst in(".$listaTelefonos.") and date(calldate)='".$fecha."';";

                            /*if ($cDate->diffInDays()>6){
                                $query="select disposition, dst, TIME_FORMAT(calldate, '%H:%i') hora from asteriskcdrdb.cdr where dst in(".$listaTelefonos.") and date(calldate)='".$fecha."';";
                            }else{
                                $query="select disposition, dst, TIME_FORMAT(calldate, '%H:%i') hora from call_center.cdr where dst in(".$listaTelefonos.") and date(calldate)='".$fecha."';";
                            }*/

                            $telefonos=DB::connection('predictivo2')->select($query);
                            $telefonos=json_decode(json_encode($telefonos),true);
                        }
                        catch(\Exception $e) {
                            return $e->getMessage()." lista telefonos: ".$listaTelefonos;
                        }
                        if (count($telefonos)>0){
                            foreach ($telefonos as $telefono){
                                if ($telefono['disposition']!='ANSWERED'){
                                    $reportes[$contadorReporte]['status']='NO ANSWER';
                                }else{
                                    $reportes[$contadorReporte]['status']=$telefono['disposition'];
                                }
                                $reportes[$contadorReporte]['contadorGestion']=$contadorGestion;
                                $reportes[$contadorReporte]['id_gestion']='';
                                $reportes[$contadorReporte]['fecha']=$fecha;
                                $reportes[$contadorReporte]['hora']=$telefono['hora'];
                                $reportes[$contadorReporte]['ci'] = $cuenta['target_document'];
                                $reportes[$contadorReporte]['nombre'] = $cuenta['nombres'];
                                $reportes[$contadorReporte]['telefono']=$telefono['dst'];
                                $reportes[$contadorReporte]['gestion']='';
                                $reportes[$contadorReporte]['observacion']='';
                                $reportes[$contadorReporte]['fecha_pp']='';
                                $reportes[$contadorReporte]['valor_pp']='';
                                $reportes[$contadorReporte]['campana'] = $campana['name'];
                                $reportes[$contadorReporte]['servidor']='predictivo';
                                $contadorReporte++;
                                $contadorGestion++;
                            }
                        }

                        //SERVIDOR MANUAL
                        try{
                            //validador mayor a 6 días busca en el historico
                            if ($cDate->diffInDays()>6){
                                $query="select disposition, dst, TIME_FORMAT(calldate, '%H:%i') hora from asteriskcdrdb.cdr where dst in(".$listaTelefonos.") and date(calldate)='".$fecha."';";
                            }else{
                                $query="select disposition, dst, TIME_FORMAT(calldate, '%H:%i') hora from ccl_ligero.cdr where dst in(".$listaTelefonos.") and date(calldate)='".$fecha."';";
                            }

                            $telefonos=DB::connection('manual')->select($query);
                            $telefonos=json_decode(json_encode($telefonos),true);
                        }
                        catch(\Exception $e) {
                            return $e->getMessage();
                        }
                        if (count($telefonos)>0) {

                            foreach ($telefonos as $telefono) {
                                if ($telefono['disposition'] != 'ANSWERED') {
                                    $reportes[$contadorReporte]['status'] = 'NO ANSWER';
                                } else {
                                    $reportes[$contadorReporte]['status'] = $telefono['disposition'];
                                }
                                $reportes[$contadorReporte]['contadorGestion'] = $contadorGestion;
                                $reportes[$contadorReporte]['id_gestion'] = '';
                                $reportes[$contadorReporte]['fecha'] = $fecha;
                                $reportes[$contadorReporte]['hora'] = $telefono['hora'];
                                $reportes[$contadorReporte]['ci'] = $cuenta['target_document'];
                                $reportes[$contadorReporte]['nombre'] = $cuenta['nombres'];
                                $reportes[$contadorReporte]['telefono'] = $telefono['dst'];
                                $reportes[$contadorReporte]['gestion'] = '';
                                $reportes[$contadorReporte]['observacion'] = '';
                                $reportes[$contadorReporte]['fecha_pp'] = '';
                                $reportes[$contadorReporte]['valor_pp'] = '';
                                $reportes[$contadorReporte]['campana'] = $campana['name'];
                                $reportes[$contadorReporte]['servidor'] = 'manual';
                                $contadorReporte++;
                                $contadorGestion++;
                            }
                        }
                    }
                }
            }
            catch(\Exception $e) {
                return $e->getMessage();
            }
        }

        try {
            ini_set ( 'memory_limit' , '7000M' );
            ini_set('max_execution_time', 1200);
            \Excel::create('REPORTE MARCACIONES COBEFEC DEL '.$fecha, function($excel) use (&$reportes){
                $excel->sheet('MARCACIONES', function($sheet) use($reportes) {
                    $sheet->loadView('reporteNuevoSistema.diners.tableMarcaciones')->with('reportes',$reportes);
                });
            })->export('xlsx');
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        try {
            $llamadas=DB::connection('predictivo2')->select("SELECT * FROM calls where date(fecha_llamada)='".$fecha."'  order by id ASC");
            $llamadas = json_decode(json_encode($llamadas), true);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $contadorReporte=0;
        foreach ($llamadas as $llamada){

            $num_concatenados='';
            $numeros=explode('-', $llamada["phone"]);

            foreach ($numeros as $key) {
                $num_concatenados.="'".$key."',";
            }

            try {
                set_time_limit(0);
                $gestion=DB::connection('cobefec3')->select("SELECT d.id id_gestion, DATE(d.created_at) fecha, 
    CONCAT(HOUR(d.created_at),':',MINUTE(d.created_at)) hora,
    d.document ci, a.data ->> '$.nomsoc' nombre,
    d.phone telefono,
    d.action gestion,
    d.description observacion,
    ifnull(d.extra ->> '$.pp_date','') fecha_pp,
    ifnull(d.extra ->> '$.pp_amount','') valor_pp,
    c.name campana,
    d.uniqueid
    from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
    where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id
    and d.account_id=a.id and b.id=2 and p.id=2 and a.id=".$llamada['id_account_cobefec']." and date(a.created_at)='".$fecha."' and d.uniqueid is not null;");
                $gestion = json_decode(json_encode($gestion), true);
            }
            catch(\Exception $e) {
                return $e->getMessage();
            }

            $contadorGestion=1;
            if (isset($gestion[0]['fecha'])){
                $reportes[$contadorReporte]['id_gestion']=$gestion[0]['id_gestion'];
                $reportes[$contadorReporte]['contadorGestion']=$contadorGestion;

                $reportes[$contadorReporte]['fecha']=$gestion[0]['fecha'];
                $hora=$gestion[0]['hora'];
                if (strlen($hora)==4){$hora='0'.$hora;}
                $reportes[$contadorReporte]['hora']=$hora;
                $reportes[$contadorReporte]['ci']=$gestion[0]['ci'];
                $reportes[$contadorReporte]['nombre']=$gestion[0]['nombre'];
                $reportes[$contadorReporte]['telefono']=$gestion[0]['telefono'];
                $reportes[$contadorReporte]['estatus']='';
                try{
                    $estatus=DB::connection('predictivo2')->select("SELECT status estatus FROM call_center.calls where uniqueid='".$gestion[0]['uniqueid']."';");
                    $estatus = json_decode(json_encode($estatus), true);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }
                if (count($estatus)>0)
                {
                    if($estatus[0]['estatus']='Success'){
                        $reportes[$contadorReporte]['estatus']='ANSWERED';
                    }else{
                        $reportes[$contadorReporte]['estatus']='NO ANSWER';
                    }

                }else{
                    $reportes[$contadorReporte]['estatus']='NO ANSWER';
                }

                $reportes[$contadorReporte]['gestion']=$gestion[0]['gestion'];
                $reportes[$contadorReporte]['observacion']=$gestion[0]['observacion'];
                $reportes[$contadorReporte]['fecha_pp']=$gestion[0]['fecha_pp'];
                $reportes[$contadorReporte]['valor_pp']=$gestion[0]['valor_pp'];
                $reportes[$contadorReporte]['campana']=$gestion[0]['campana'];
                $contadorReporte++;
            }
        }

        try{
            set_time_limit(0);
            \Excel::create('REPORTE MARCACIONES COBEFEC '.date('Y-m-d'), function($excel) use (&$reportes){
                $excel->sheet('DISCOVER', function($sheet) use($reportes) {
                    $sheet->loadView('reporteNuevoSistema.diners.tableMarcaciones')->with('reportes',$reportes);
                });
            })->export('xlsx');
        }catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function generalCuentasDinersCampo(Request $request)
    {
        //campo
        set_time_limit(0);

        $query="call cobefec_reportes.sp_diners_gral_cuentas(".$request->id_campana.");";
        try {
            $reportes=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes=json_decode(json_encode($reportes),true);

        $campana=tbl_campaigns::find($request->id_campana);
        set_time_limit(0);
        ini_set('memory_limit','-1');
        ini_set('max_execution_time',900);
        try{
            \Excel::create('REPORTE GENERAL DE CUENTAS DINERS CAMPO '.$campana->name.' '.date('d-m-Y'), function($excel) use (&$reportes){
                $excel->sheet('REPORTE', function($sheet) use($reportes) {
                    $sheet->loadView('reporteNuevoSistema/diners/tableGeneralCuentasCampo')->with('reportes',$reportes);
                    //$sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
    }

    public function generalCuentasDinersLegal(Request $request)
    {
        //campo
        set_time_limit(0);

        $query="call cobefec_reportes.sp_legal_gral_cuentas(".$request->id_campana.");";
        try {
            $reportes=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes=json_decode(json_encode($reportes),true);

        $campana=tbl_campaigns::find($request->id_campana);
        set_time_limit(0);
        ini_set('memory_limit','-1');
        ini_set('max_execution_time',900);
        try{
            \Excel::create('REPORTE GENERAL DE CUENTAS DINERS LEGAL '.$campana->name.' '.date('d-m-Y'), function($excel) use (&$reportes){
                $excel->sheet('REPORTE', function($sheet) use($reportes) {
                    //$sheet->loadView('reporteNuevoSistema/diners/tableGeneralCuentasLegal')->with('reportes',$reportes);
                    $sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
    }

    public function actualizarZonificacion()
    {
        $cont=1;
        $campana_orig=tbl_accounts::where('campaign_id',210)->where('enabled',1)->where('located',1)->get();
        foreach ($campana_orig as $k) {
            $direccion_orig=json_decode($k->data,true);
            $lng=$direccion_orig['direccion']['lng'];
            $lat=$direccion_orig['direccion']['lat'];
            $full_address=$direccion_orig['direccion']['full_address'];

            //$direccion_orig['direccion']['full_address']='"Av Manabi, Portoviejo, Ecuador';

            //var_dump($direccion_orig);
            //echo "<br><br>";
            //$k->data=json_encode($direccion_orig);
            //$k->save();
            //dd($k);
            if ($lng!='' && $lng!=null && $lat!='' && $lat!=null){
                $campana_destinos=tbl_accounts::where('target_document',$k->target_document)->where('campaign_id',1054)->where('enabled',1)->where('located',0)->get();
                foreach ($campana_destinos as $campana_dest) {
                    if (count($campana_dest) > 0) {
                        //dd($campana_dest);
                        $direccion_dest = json_decode($campana_dest->data, true);
                        $direccion_dest['direccion']['lat'] = $lat;
                        $direccion_dest['direccion']['lng'] = $lng;
                        $direccion_dest['direccion']['full_address'] = $full_address;
                        $campana_dest->data = json_encode($direccion_dest);
                        $campana_dest->located = 1;
                        $campana_dest->location_verified = 1;
                        $campana_dest->zone = $k->zone;
                        $campana_dest->zone_id = $k->zone_id;
                        $campana_dest->save();
                        //if ($cont==100){dd('fin 100');}
                        echo $cont . '<br>';
                        $cont++;
                    }
                }
                //$direccion_dest=json_decode($campana_dest[0]->data,true);
                //dd($direccion_orig['direccion']['original_address']);
            }
        }

    }
    public function xdelete()
    {
        $campanas=tbl_campaigns::where('enabled',0)->get();



        foreach ($campanas as $campana){
            // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://172.16.5.52:9200/campaign-'.$campana->id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo '<br>Error:' . curl_error($ch);
            }else{
                echo '<br><strong>Exito:</strong> '.$result;
            }
            curl_close ($ch);
        }
        dd($campanas);

    }
}

function reporteCampoSftp($envio,$fecha,$campana,$marca){
    //antes de la union

    $query="select s.MARCA, s.CICLOF, s.NOMSOC, s.CEDSOC, s.VAPAMI, s.TRIESGO_ORIG, s.EDAD, s.PRODUCTO, s.DIRECCION, s.P1, s.T1, s.P2, s.T2, s.P3, s.T3, s.NOMBRE_CIUDAD, s.ZONA, s.MOTIVO_ANTERIOR, s.RESULTADO_ANTERIOR, s.OBSERVACION_ANTERIOR, s.RESULTADO, s.DESCRIPCION, s.OBSERVACION, s.FECHACOMPROMISO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.ULTIMO_TLF_CONTACTO) ULTIMO_TLF_CONTACTO,
s.TIPOLLAMADA,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'', if(((s.RESULTADO='Contacto sin Arreglo Mediato' or s.RESULTADO='Ofrecimiento al Corte' or s.RESULTADO='Refinancia' or s.RESULTADO='Notificado') and (s.MOTIVO='' or s.MOTIVO is null)),'No quiere informar el motivo',s.MOTIVO)) MOTIVO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'', if(((s.RESULTADO='Contacto sin Arreglo Mediato' or s.RESULTADO='Ofrecimiento al Corte' or s.RESULTADO='Refinancia' or s.RESULTADO='Notificado') and (s.MOTIVO='' or s.MOTIVO is null)),'No quiere informar el motivo',s.SUB_MOTIVO_NO_PAGO)) SUB_MOTIVO_NO_PAGO,
s.GESTOR, s.EMPRESA, s.CAMPAÑA, s.HORA_DE_CONTACTO,VISITA_DOMICILIO,VISITA_OFICINA,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.TIPO_CONTACTO) CANAL_DE_COMUNICACION,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.CONTACTO) CONTACTO,
if((s.RESULTADO='Aún No Contactado Mañana' or s.RESULTADO='Aún No Contactado Tarde' or s.RESULTADO='Aún No Contactado Noche' or s.RESULTADO='Ilocalizable' or s.RESULTADO='Envio de Mail' or s.RESULTADO='Redes Sociales' or s.RESULTADO='Sin Gestión' or s.RESULTADO='' or s.RESULTADO='Sin Arreglo Cliente' or s.RESULTADO='Mensaje a Tercero' or s.RESULTADO='Sin Arreglo Tercero'),'',s.HORARIO) HORARIO_DE_CONTACTO_FUTURO,0, s.idgestion,
s.Borrar_Telefono1, s.Borrar_Telefono2, s.Borrar_Telefono3, s.Borrar_Direccion, s.Borrar_Correo, s.Anadir_Telefono1, s.Anadir_Telefono2, s.Anadir_Telefono3, s.Anadir_Direccion, s.Anadir_Correo
from cobefec_reportes.tmp_sftpcampodm s where s.MARCA='".$marca."'
;
";
    $sql=DB::connection('cobefec3')->select($query);
    $reportes = json_decode(json_encode($sql), true);

    /*foreach($reportes as $v) {
        if ($request->envio==1){
            $dm=tbl_demarches::find($v['idgestion']);
            $dm->sent_status=1;
            $dm->save();
        }
    }*/

    //solo extraigo la informacion de cedusc en un solo array
    $unicos = array_column($reportes, 'CEDSOC');
    $res = array_diff($unicos, array_diff(array_unique($unicos), array_diff_assoc($unicos, array_unique($unicos))));

    foreach(array_unique($res) as $v) {
        //echo "Duplicado1 $v en la posicion: " .  implode(', ', array_keys($res, $v)) . '<br />';
        $posicion=array_keys($res, $v);
        $count=count($posicion);
        //echo $count."<br>";
        $observacion="";
        for ($i=0;$i<$count;$i++){
            $observacion=$observacion." ".$reportes[$posicion[$i]]['OBSERVACION'];
        }
        $reportes[$posicion[0]]['OBSERVACION']=$observacion;
    }

    $reportes_1=Array();
    foreach($unicos as $k)
    {
        $key = array_search($k, array_column($reportes, 'CEDSOC'));
        unset($reportes[$key]['peso']);
        unset($reportes[$key]['idgestion']);
        //creo un nuevo arreglo sin valores duplicados
        array_push($reportes_1, $reportes[$key]);
    }

    //nuevo
    $query="select * from cobefec_reportes.sftpcampo_mn s
where s.peso=(select max(peso) from cobefec_reportes.sftpcampo_mn where CEDSOC=s.CEDSOC and date(fecha)=date(s.fecha)) and s.MARCA='".$marca."';";
    $sql=DB::connection('cobefec3')->select($query);
    $reportes2 = json_decode(json_encode($sql), true);

    try {
        if($envio==1) {
            DB::connection('cobefec3')->statement("update demarches set sent_status=1 where account_id in (select id from accounts where campaign_id=".$campana.") and sent_status=0;");
        }
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }

    //solo extraigo la informacion de cedusc en un solo array
    $unicos = array_column($reportes2, 'CEDSOC');

    $res = array_diff($unicos, array_diff(array_unique($unicos), array_diff_assoc($unicos, array_unique($unicos))));
    foreach(array_unique($res) as $v) {
        //echo "Duplicado2 $v en la posicion: " .  implode(', ', array_keys($res, $v)) . '<br />';
        $posicion=array_keys($res, $v);
        $count=count($posicion);
        //echo $count."<br>";
        $observacion="";
        for ($i=0;$i<$count;$i++){
            $observacion=$observacion." ".$reportes2[$posicion[$i]]['OBSERVACION'];
        }
        $reportes2[$posicion[0]]['OBSERVACION']=$observacion;
    }

    $reportes_2=Array();
    foreach($unicos as $k)
    {
        $key = array_search($k, array_column($reportes2, 'CEDSOC'));
        unset($reportes2[$key]['0']);
        //unset($reportes2[$key]['idgestion']);
        //creo un nuevo arreglo sin valores duplicados
        array_push($reportes_2, $reportes2[$key]);
    }

    $reportes_f=Array();
    //$reportes_f=array_merge($reportes_1,$reportes_2);

    //solo extraigo la informacion de cedusc en un solo array
    $unicos = array_column($reportes_1, 'CEDSOC');
    //elimino los valores duplicados de array unicos
    $unicos = array_unique($unicos);

    foreach($unicos as $k)
    {
        $key = array_search($k, array_column($reportes_1, 'CEDSOC'));
        unset($reportes_1[$key]['0']);
        //unset($reportes_1[$key]['idgestion']);
        $iniciales=explode(" ",trim($reportes_1[$key]['GESTOR']));
        if (count($iniciales)==2){
            $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[1][0]);
        }elseif(count($iniciales)==3){
            $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[1][0]);
        }
        elseif(count($iniciales)>=4){
            $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[2][0]);
        }

        try{
            $reportes_1[$key]['OBSERVACION']="COBEFEC ".$fecha." ".$iniciales." ".$reportes_1[$key]['OBSERVACION'];
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }


        //creo un nuevo arreglo sin valores duplicados
        array_push($reportes_f, $reportes_1[$key]);
    }

    //solo extraigo la informacion de cedusc en un solo array
    $unicos = array_column($reportes2, 'CEDSOC');
    //elimino los valores duplicados de array unicos
    $unicos = array_unique($unicos);
    foreach($unicos as $k)
    {
        $key = array_search($k, array_column($reportes2, 'CEDSOC'));
        unset($reportes2[$key]['peso']);
        //agrego mÃ¡ximo 3 telefonos
        $query="SELECT if(p.prefix is null, p.number, concat(p.prefix,p.number)) telefono
FROM cobefec3_mining.target_phones p, cobefec3.demarches d1
where d1.document=p.target_document and date(d1.created_at)=date(p.created_at) and d1.contact_type='CD' and d1.id='".$reportes2[$key]['idgestion']."'
;";
        $sql=DB::connection('cobefec3')->select($query);
        $numeros = json_decode(json_encode($sql), true);
        $countTelefono=1;
        foreach ($numeros as $numero) {
            if ($countTelefono<=3){
                $reportes2[$key]['Anadir_Telefono'.$countTelefono]=$numero['telefono'];
                $countTelefono++;
            }
        }
        unset($reportes2[$key]['idgestion']);
        unset($reportes2[$key]['fecha']);
        $iniciales=explode(" ",trim($reportes2[$key]['GESTOR']));
        if (count($iniciales)==2){
            $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[1][0]);
        }elseif(count($iniciales)==3){
            $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[1][0]);
        }elseif(count($iniciales)>=4){
            $iniciales=strtoupper($iniciales[0][0]).strtoupper($iniciales[2][0]);
        }
        $obs="ECE COBEFEC ".$fecha." ".$iniciales." ".$reportes2[$key]['OBSERVACION'];
        $reportes2[$key]['OBSERVACION']="ECE COBEFEC ".$fecha." ".$iniciales." ".$reportes2[$key]['OBSERVACION'];

        //creo un nuevo arreglo sin valores duplicados
        array_push($reportes_f, $reportes2[$key]);
    }

    return $reportes_f;

    /*
    $reportes_f_diners=Array();
    $reportes_f_visa=Array();
    $reportes_f_discover=Array();
    $reportes_f_base=Array();
    foreach($reportes_f as $k)
    {
        if ($k['MARCA']=='DINERS'){
            array_push($reportes_f_diners, $k);
        }
        elseif($k['MARCA']=='VISA'){
            array_push($reportes_f_visa, $k);
        }
        elseif($k['MARCA']=='DISCOVER'){
            array_push($reportes_f_discover, $k);
        }
        elseif(count($k['MARCA'])>0){
            array_push($reportes_f_base, $k);
        }
    }
    */

}