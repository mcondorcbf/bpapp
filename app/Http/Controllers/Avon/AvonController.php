<?php

namespace App\Http\Controllers\Avon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\IFTTTHandler;

class AvonController extends Controller
{
    public function generarReporteAvon(Request $request)
    {
        ini_set('max_execution_time',0);
        try {

            if ($request->idReporte==1){
            stream_context_set_default([
                'ssl'=>[
                    'verify_peer'=>false,
                    'verify_peer_name' =>false,
                ]
            ]);

                $dir = public_path() . "/storage/reportesAvon/";
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

            $nombre1='elastix-ecuador-'.date('Y-m-d-H-i-s').'.csv';
            $query = "SELECT 'Fecha', 'Fuente', 'Destino', 'Canal_destino', 'Duracion', 'Marca_nombre', 'Campana_nombre', 'Agente_numero', 'Agente_nombre', 'Estado', 'Tipo'
UNION ALL select a.calldate Fecha, a.src Fuente, a.dst Destino, a.dstchannel Canal_destino, sec_to_time(a.billsec) Duracion, b.nombre Marca_nombre, c.nombre Campana_nombre, a.agente Agente_numero, d.nombre Agente_nombre,
    if(a.descanso is null,'Activo',(SELECT concat('En Descanso: ',nombre) FROM ccl_ligero.descansos where prefijo=a.descanso)) Estado,
    if(a.disposition='ANSWERED','CONTESTADO',if(a.disposition='NO ANSWER','NO CONTESTADO',if(a.disposition='BUSY','OCUPADO','FALLIDO'))) Tipo
    from asteriskcdrdb.cdr a, ccl_ligero.marcas b, ccl_ligero.campanas c, ccl_ligero.agentes d
    where a.marca='30' and b.prefijo=a.marca and c.prefijo=a.campana and c.marca=a.marca and d.numero=a.agente and d.campana=a.campana
    AND date(a.calldate) BETWEEN '".$request->finicio."' AND '".$request->ffin."'
    INTO OUTFILE '/var/www/html/reportes/".$nombre1."'
    FIELDS TERMINATED BY ';'
    ENCLOSED BY '\"'
    LINES TERMINATED BY '\n';";
                DB::connection('elastixec')->getpdo()->exec($query);
                copy('https://172.16.5.185/reportes/'.$nombre1,public_path('storage/reportesAvon/'.$nombre1));

                $nombre2='elastix-peru-'.date('Y-m-d-H-i-s').'.csv';
                $query = "select a.calldate Fecha, a.src Fuente, a.dst Destino, a.dstchannel Canal_destino, sec_to_time(a.billsec) Duracion, b.nombre Marca_nombre, c.nombre Campana_nombre, a.agente Agente_numero, d.nombre Agente_nombre,
    if(a.descanso is null,'Activo',(SELECT concat('En Descanso: ',nombre) FROM ccl_ligero.descansos where prefijo=a.descanso)) Estado,
    if(a.disposition='ANSWERED','CONTESTADO',if(a.disposition='NO ANSWER','NO CONTESTADO',if(a.disposition='BUSY','OCUPADO','FALLIDO'))) Tipo
    from asteriskcdrdb.cdr a, ccl_ligero.marcas b, ccl_ligero.campanas c, ccl_ligero.agentes d
    where a.marca='30' and b.prefijo=a.marca and c.prefijo=a.campana and c.marca=a.marca and d.numero=a.agente and d.campana=a.campana
    AND date(a.calldate) BETWEEN '".$request->finicio."' AND '".$request->ffin."'
    INTO OUTFILE '/var/www/html/reportes/".$nombre2."'
    FIELDS TERMINATED BY ';'
    ENCLOSED BY '\"'
    LINES TERMINATED BY '\n';";
                DB::connection('elastixpe')->getpdo()->exec($query);
                copy('https://192.168.99.251/reportes/'.$nombre2,public_path('storage/reportesAvon/'.$nombre2));
                chdir('storage/reportesAvon/');
                $nombre='consolidado-elastix-ecuador-peru-'.date('Y-m-d-H-i-s').'.csv';
                shell_exec('cat '.$nombre1.' '.$nombre2.' > '.$nombre);

                return response()->download(public_path('storage/reportesAvon/'.$nombre))->deleteFileAfterSend(true);

            }

            if ($request->idReporte==2){
                $fecha='elastix-ecuador-'.date('Y-m-d-H-i-s');
                $query = "SELECT 'Fecha', 'Fuente', 'Destino', 'Canal_destino', 'Duracion', 'Marca_nombre', 'Campana_nombre', 'Agente_numero', 'Agente_nombre', 'Estado', 'Tipo'
UNION ALL select a.calldate Fecha, a.src Fuente, a.dst Destino, a.dstchannel Canal_destino, sec_to_time(a.billsec) Duracion, b.nombre Marca_nombre, c.nombre Campana_nombre, a.agente Agente_numero, d.nombre Agente_nombre,
    if(a.descanso is null,'Activo',(SELECT concat('En Descanso: ',nombre) FROM ccl_ligero.descansos where prefijo=a.descanso)) Estado,
    if(a.disposition='ANSWERED','CONTESTADO',if(a.disposition='NO ANSWER','NO CONTESTADO',if(a.disposition='BUSY','OCUPADO','FALLIDO'))) Tipo
    from asteriskcdrdb.cdr a, ccl_ligero.marcas b, ccl_ligero.campanas c, ccl_ligero.agentes d
    where a.marca='30' and b.prefijo=a.marca and c.prefijo=a.campana and c.marca=a.marca and d.numero=a.agente and d.campana=a.campana
    AND date(a.calldate) BETWEEN '".$request->finicio."' AND '".$request->ffin."'
    INTO OUTFILE '/var/www/html/reportes/".$fecha.".csv'
    FIELDS TERMINATED BY ';'
    ENCLOSED BY '\"'
    LINES TERMINATED BY '\n';";

                DB::connection('elastixec')->getpdo()->exec($query);
                return \Redirect::to('https://172.16.5.185/reportes/'.$fecha.'.csv');
            }

            if ($request->idReporte==3){
                $fecha='elastix-peru-'.date('Y-m-d-H-i-s');
                $query = "SELECT 'Fecha', 'Fuente', 'Destino', 'Canal_destino', 'Duracion', 'Marca_nombre', 'Campana_nombre', 'Agente_numero', 'Agente_nombre', 'Estado', 'Tipo'
UNION ALL select a.calldate Fecha, a.src Fuente, a.dst Destino, a.dstchannel Canal_destino, sec_to_time(a.billsec) Duracion, b.nombre Marca_nombre, c.nombre Campana_nombre, a.agente Agente_numero, d.nombre Agente_nombre,
    if(a.descanso is null,'Activo',(SELECT concat('En Descanso: ',nombre) FROM ccl_ligero.descansos where prefijo=a.descanso)) Estado,
    if(a.disposition='ANSWERED','CONTESTADO',if(a.disposition='NO ANSWER','NO CONTESTADO',if(a.disposition='BUSY','OCUPADO','FALLIDO'))) Tipo
    from asteriskcdrdb.cdr a, ccl_ligero.marcas b, ccl_ligero.campanas c, ccl_ligero.agentes d
    where a.marca='30' and b.prefijo=a.marca and c.prefijo=a.campana and c.marca=a.marca and d.numero=a.agente and d.campana=a.campana
    AND date(a.calldate) BETWEEN '".$request->finicio."' AND '".$request->ffin."'
    INTO OUTFILE '/var/www/html/reportes/".$fecha.".csv'
    FIELDS TERMINATED BY ';'
    ENCLOSED BY '\"'
    LINES TERMINATED BY '\n';";

                DB::connection('elastixpe')->getpdo()->exec($query);
                return \Redirect::to('https://192.168.99.251/reportes/'.$fecha.'.csv');
            }

            if ($request->idReporte==5){
                $campanias='';
                foreach ($request->campanias as $k){
                    $campanias.="'".$k."',";
                }
                $campanias=substr($campanias, 0, -1);
                $estado='';
                if ($request->estado==1){
                    $estado="and a.estado='1' ";
                }elseif($request->estado==0){
                    $estado="and a.estado='0' ";
                }

                $fecha='general-de-cuentas-ecuador-'.date('Y-m-d-H-i-s');
                $query="select 'Cedula','Cuenta','Producto','Nombre','Etapa_actual','Estado','Telefonos','Telefono_principal','Celular','Direcciones','Referencias','Deuda_inicial','Deuda_actual','Recuperacion','Gestor_actual','Ultima_gestion_gestor','Ultima_gestion_fecha','Ultima_gestion_tipo','Ultima_gestion_accion','Ultima_gestion_motivo','Ultima_gestion_submotivo','Ultima_gestion_observacion','Ultima_gestion_Fecha_promesa_pago','Ultima_gestion_Monto_promesa_pago','Mejor_gestion_gestor','Mejor_gestion_Fecha_Hora','Mejor_gestion_tipo','Mejor_gestion_accion','Mejor_gestion_motivo','Mejor_gestion_submotivo','Mejor_gestion_Observacion','Mejor_gestion_Fecha_promesa_pago','Mejor_gestion_Monto_promesa_pago','Tipo_contacto','Contacto','Horario','A','CODIGO_DE_PROVINCIA','TIPO_DE_REPRESENTANTE','ZONA','SECCION','ANTIGUEDAD','CIUDAD','PAST_DUE','EQUIPO','EMAIL','DIVISION','REPRESENTANTE','SALDO_ACTUAL','CAMPANA_COB','idcampana','estado_i'
UNION ALL
select a.ci Cedula,a.no_cuenta Cuenta, a.producto Producto, a.nombre Nombre, e.nombre 'Etapa_actual', if(a.estado='1','Habilitado','Deshabilitado') Estado, 
trim(CONCAT(if(locate(',',telefonos_serializados,3)=0,substr(telefonos_serializados,3,(locate('\"',telefonos_serializados,3)-1)-2),substr(telefonos_serializados,3,(locate(',',telefonos_serializados,3)-2)-2)),' ',
if(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)=0,substr(telefonos_serializados,locate('\"',telefonos_serializados,3)+3,locate('\"',telefonos_serializados,locate('\"',telefonos_serializados,3)+3)-(locate('\"',telefonos_serializados,3)+3)),substr(telefonos_serializados,locate(',',telefonos_serializados,3)+2,(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)-2)-(locate(',',telefonos_serializados,3)+1))), ' ',
if(locate(',',telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2)=0,substr(telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2,(locate('\"',telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2)-1)-(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+1)),'')
)) Telefonos, a.telefono 'Telefono_prncipal'
,'' Celular, a.direccion Direcciones, if(locate(',',referencias_serializados)<>0,concat(substr(referencias_serializados,2+1,(locate(',',referencias_serializados))-2-2), ' ' ,substr(referencias_serializados,(locate(',',referencias_serializados))+2,(locate(',',referencias_serializados,(locate(',',referencias_serializados))+2))-((locate(',',referencias_serializados))+1)-2)),substr(referencias_serializados,3,(locate(']',referencias_serializados))-1-3)) Referencias
,a.deuda_inicial 'Deuda_inicial', a.deuda_actual 'Deuda_actual', (a.deuda_inicial - a.deuda_actual) Recuperacion, f.username 'Gestor_actual',
h.username 'Ultima_gestion_gestor', a.fecha_ultima_gestion 'Ultima_gestion_fecha', j.nombre 'Ultima_gestion_tipo', i.nombre 'Ultima_gestion_accion',
ifnull((select if(z.sub_motivo_id is not null,x.nombre,'') from copiaprod.motivo x, copiaprod.sub_motivo y, copiaprod.gestion z where x.id=y.motivo_id and y.id=z.sub_motivo_id and z.id=a.ultima_gestion_id), '--') 'Ultima_gestion_motivo',
ifnull((select if(y.sub_motivo_id is not null,x.nombre, '' ) from copiaprod.sub_motivo x, copiaprod.gestion y where x.id=y.sub_motivo_id and y.id=a.ultima_gestion_id), '--') 'Ultima_gestion_submotivo',
g.observacion 'Ultima_gestion_observacion', if(g.fecha_pp is not null,g.fecha_pp,'') 'Ultima_gestion_Fecha_promesa_pago',if(g.valor_pp is not null,g.valor_pp,'') 'Ultima_gestion_Monto_promesa_pago',
h_m.username 'Mejor_gestion_Gestor',g_m.fecha 'Mejor_gestion_Fecha_Hora', j_m.nombre 'Mejor_gestion_tipo', i_m.nombre 'Mejor_gestion_accion',
ifnull((select if(z.sub_motivo_id is not null,x.nombre,'') from copiaprod.motivo x, copiaprod.sub_motivo y, copiaprod.gestion z where x.id=y.motivo_id and y.id=z.sub_motivo_id and z.id=a.mejor_gestion_id), '--') 'Mejor_gestion_motivo',
ifnull((select if(y.sub_motivo_id is not null,x.nombre, '' ) from copiaprod.sub_motivo x, copiaprod.gestion y where x.id=y.sub_motivo_id and y.id=a.mejor_gestion_id), '--') 'Mejor_gestion_submotivo',
g_m.observacion 'Mejor_gestion_Observacion', if(g_m.fecha_pp is not null,g_m.fecha_pp,'') 'Mejor_gestion_Fecha_promesa_pago', if(g_m.valor_pp is not null,g_m.valor_pp,'') 'Mejor_gestion_Monto_promesa_pago',
'--' Tipo_Contacto, '--' Contacto, '--' Horario,
substr(a.principales_serializados,locate('\"12\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"12\":',a.principales_serializados)+6)-(locate('\"12\":',a.principales_serializados)+6)) A,
substr(a.principales_serializados,locate('\"17\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"17\":',a.principales_serializados)+6)-(locate('\"17\":',a.principales_serializados)+6)) 'CODIGO_DE_PROVINCIA',
substr(a.principales_serializados,locate('\"18\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"18\":',a.principales_serializados)+6)-(locate('\"18\":',a.principales_serializados)+6)) 'TIPO_DE_REPRESENTANTE',
substr(a.principales_serializados,locate('\"19\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"19\":',a.principales_serializados)+6)-(locate('\"19\":',a.principales_serializados)+6)) 'ZONA',
substr(a.principales_serializados,locate('\"20\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"20\":',a.principales_serializados)+6)-(locate('\"20\":',a.principales_serializados)+6)) 'SECCION',
substr(a.principales_serializados,locate('\"21\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"21\":',a.principales_serializados)+6)-(locate('\"21\":',a.principales_serializados)+6)) 'ANTIGUEDAD',
substr(a.principales_serializados,locate('\"25\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"25\":',a.principales_serializados)+6)-(locate('\"25\":',a.principales_serializados)+6)) 'CIUDAD',
substr(a.principales_serializados,locate('\"26\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"26\":',a.principales_serializados)+6)-(locate('\"26\":',a.principales_serializados)+6)) 'PAST_DUE',
substr(a.principales_serializados,locate('\"27\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"27\":',a.principales_serializados)+6)-(locate('\"27\":',a.principales_serializados)+6)) 'EQUIPO',
substr(a.principales_serializados,locate('\"30\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"30\":',a.principales_serializados)+6)-(locate('\"30\":',a.principales_serializados)+6)) 'EMAIL',
substr(a.principales_serializados,locate('\"31\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"31\":',a.principales_serializados)+6)-(locate('\"31\":',a.principales_serializados)+6)) 'DIVISION',
substr(a.principales_serializados,locate('\"32\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"32\":',a.principales_serializados)+6)-(locate('\"32\":',a.principales_serializados)+6)) 'REPRESENTANTE',
substr(a.principales_serializados,locate('\"33\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"33\":',a.principales_serializados)+6)-(locate('\"33\":',a.principales_serializados)+6)) 'SALDO_ACTUAL',
substr(a.principales_serializados,locate('\"34\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"34\":',a.principales_serializados)+6)-(locate('\"34\":',a.principales_serializados)+6)) 'CAMPANA_COB',
b.id idcampana, a.estado estado_i
from copiaprod.cuenta a, copiaprod.capana b, copiaprod.producto c, copiaprod.marca d, copiaprod.producto_etapa e, copiaprod.usuario f, copiaprod.gestion g, copiaprod.usuario h, copiaprod.accion i, copiaprod.tipo j
, copiaprod.gestion g_m, copiaprod.usuario h_m, copiaprod.accion i_m, copiaprod.tipo j_m
where b.id=a.capana_id and c.id=b.producto_id and d.id=c.marca_id and e.id=a.etapa_id and f.id=a.usuario_id and g.id=a.ultima_gestion_id
and g.usuario_id=h.id and g.accion_id=i.id and i.tipo_id=j.id
and g_m.id=a.mejor_gestion_id and g_m.usuario_id=h_m.id and g_m.accion_id=i_m.id and i_m.tipo_id=j_m.id
and d.id='25' and c.id='98' and b.id in (".$campanias.") and e.id='86' ".$estado."
UNION ALL
select a.ci Cedula,a.no_cuenta Cuenta, a.producto Producto, a.nombre Nombre, e.nombre 'Etapa actual', if(a.estado='1','Habilitado','Deshabilitado') Estado,
trim(CONCAT(if(locate(',',telefonos_serializados,3)=0,substr(telefonos_serializados,3,(locate('\"',telefonos_serializados,3)-1)-2),substr(telefonos_serializados,3,(locate(',',telefonos_serializados,3)-2)-2)),' ',
if(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)=0,substr(telefonos_serializados,locate('\"',telefonos_serializados,3)+3,locate('\"',telefonos_serializados,locate('\"',telefonos_serializados,3)+3)-(locate('\"',telefonos_serializados,3)+3)),substr(telefonos_serializados,locate(',',telefonos_serializados,3)+2,(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)-2)-(locate(',',telefonos_serializados,3)+1))), ' ',
if(locate(',',telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2)=0,substr(telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2,(locate('\"',telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2)-1)-(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+1)),'')
)) Telefonos, a.telefono 'Telefono prncipal'
,'' Celular, a.direccion Direcciones, if(locate(',',referencias_serializados)<>0,concat(substr(referencias_serializados,2+1,(locate(',',referencias_serializados))-2-2), ' ' ,substr(referencias_serializados,(locate(',',referencias_serializados))+2,(locate(',',referencias_serializados,(locate(',',referencias_serializados))+2))-((locate(',',referencias_serializados))+1)-2)),substr(referencias_serializados,3,(locate(']',referencias_serializados))-1-3)) Referencias 
,a.deuda_inicial 'Deuda inicial', a.deuda_actual 'Deuda actual', (a.deuda_inicial - a.deuda_actual) Recuperacion, 
if(a.usuario_id is null, '',(select username from copiaprod.usuario where id=a.usuario_id)) 'Gestor actual',
'--' Ultima_gestion_gestor, '--' Ultima_gestiÃ³n_Fecha_Hora, '--' Ultima_gestion_tipo, '--' Ultima_gestion_accion, '--' Ultima_gestion_motivo, '--' Ultima_gestion_submotivo,
'--' Ultima_gestion_observacion, '--' Ultima_gestion_Fecha_promesa_pago, '--' Ultima_gestion_Monto_promesa_pago, '--' Mejor_gestion_Gestor, '--' Mejor_gestion_Fecha_Hora,
'--' Mejor_gestion_tipo, '--' Mejor_gestion_accion, '--' Mejor_gestion_motivo, '--' Mejor_gestion_submotivo, '--' Mejor_gestion_Observacion, '--' Mejor_gestion_Fecha_promesa_pago,
'--' Mejor_gestion_Monto_promesa_pago, '--' Tipo_Contacto, '--' Contacto, '--' Horario,
substr(a.principales_serializados,locate('\"12\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"12\":',a.principales_serializados)+6)-(locate('\"12\":',a.principales_serializados)+6)) A,
substr(a.principales_serializados,locate('\"17\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"17\":',a.principales_serializados)+6)-(locate('\"17\":',a.principales_serializados)+6)) 'CODIGO DE PROVINCIA',
substr(a.principales_serializados,locate('\"18\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"18\":',a.principales_serializados)+6)-(locate('\"18\":',a.principales_serializados)+6)) 'TIPO DE REPRESENTANTE',
substr(a.principales_serializados,locate('\"19\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"19\":',a.principales_serializados)+6)-(locate('\"19\":',a.principales_serializados)+6)) 'ZONA',
substr(a.principales_serializados,locate('\"20\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"20\":',a.principales_serializados)+6)-(locate('\"20\":',a.principales_serializados)+6)) 'SECCION',
substr(a.principales_serializados,locate('\"21\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"21\":',a.principales_serializados)+6)-(locate('\"21\":',a.principales_serializados)+6)) 'ANTIGUEDAD',
substr(a.principales_serializados,locate('\"25\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"25\":',a.principales_serializados)+6)-(locate('\"25\":',a.principales_serializados)+6)) 'CIUDAD',
substr(a.principales_serializados,locate('\"26\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"26\":',a.principales_serializados)+6)-(locate('\"26\":',a.principales_serializados)+6)) 'PAST DUE',
substr(a.principales_serializados,locate('\"27\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"27\":',a.principales_serializados)+6)-(locate('\"27\":',a.principales_serializados)+6)) 'EQUIPO',
substr(a.principales_serializados,locate('\"30\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"30\":',a.principales_serializados)+6)-(locate('\"30\":',a.principales_serializados)+6)) 'EMAIL',
substr(a.principales_serializados,locate('\"31\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"31\":',a.principales_serializados)+6)-(locate('\"31\":',a.principales_serializados)+6)) 'DIVISION',
substr(a.principales_serializados,locate('\"32\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"32\":',a.principales_serializados)+6)-(locate('\"32\":',a.principales_serializados)+6)) 'REPRESENTANTE',
substr(a.principales_serializados,locate('\"33\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"33\":',a.principales_serializados)+6)-(locate('\"33\":',a.principales_serializados)+6)) 'SALDO ACTUAL',
substr(a.principales_serializados,locate('\"34\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"34\":',a.principales_serializados)+6)-(locate('\"34\":',a.principales_serializados)+6)) 'CAMPANA COB',
b.id idcampana, a.estado estado_i
from copiaprod.cuenta a, copiaprod.capana b, copiaprod.producto c, copiaprod.marca d, copiaprod.producto_etapa e
where b.id=a.capana_id and c.id=b.producto_id and d.id=c.marca_id and e.id=a.etapa_id  
and d.id='25' and c.id='98' and b.id in (".$campanias.") and e.id='86' ".$estado."
and a.ultima_gestion_id is null 
INTO OUTFILE '/var/www/html/reportes/".$fecha.".csv'
    FIELDS TERMINATED BY ';'
    ENCLOSED BY '\"'
    LINES TERMINATED BY '\n';";

                DB::connection('gestionec')->getpdo()->exec($query);
                return \Redirect::to('http://172.16.5.25:8088/'.$fecha.'.csv');

            }

            if ($request->idReporte==6){
                $campanias='';
                foreach ($request->campanias as $k){
                    $campanias.="'".$k."',";
                }
                $campanias=substr($campanias, 0, -1);
                $estado='';
                if ($request->estado==1){
                    $estado="and a.estado='1' ";
                }elseif($request->estado==0){
                    $estado="and a.estado='0' ";
                }

                $fecha='general-de-cuentas-peru-'.date('Y-m-d-H-i-s');
                $query="select 'Cedula','Cuenta','Producto','Nombre','Etapa_actual','Estado','Telefonos','Telefono_principal','Celular','Direcciones','Referencias','Deuda_inicial','Deuda_actual','Recuperacion','Gestor_actual','Ultima_gestion_gestor','Ultima_gestion_fecha','Ultima_gestion_tipo','Ultima_gestion_accion','Ultima_gestion_motivo','Ultima_gestion_submotivo','Ultima_gestion_observacion','Ultima_gestion_Fecha_promesa_pago','Ultima_gestion_Monto_promesa_pago','Mejor_gestion_gestor','Mejor_gestion_Fecha_Hora','Mejor_gestion_tipo','Mejor_gestion_accion','Mejor_gestion_motivo','Mejor_gestion_submotivo','Mejor_gestion_Observacion','Mejor_gestion_Fecha_promesa_pago','Mejor_gestion_Monto_promesa_pago','Tipo_contacto','Contacto','Horario','A','CODIGO_DE_PROVINCIA','TIPO_DE_REPRESENTANTE','ZONA','SECCION','ANTIGUEDAD','CIUDAD','PAST_DUE','EQUIPO','EMAIL','DIVISION','REPRESENTANTE','SALDO_ACTUAL','CAMPANA_COB','idcampana','estado_i'
UNION ALL
select a.ci Cedula,a.no_cuenta Cuenta, a.producto Producto, a.nombre Nombre, e.nombre 'Etapa_actual', if(a.estado='1','Habilitado','Deshabilitado') Estado, 
trim(CONCAT(if(locate(',',telefonos_serializados,3)=0,substr(telefonos_serializados,3,(locate('\"',telefonos_serializados,3)-1)-2),substr(telefonos_serializados,3,(locate(',',telefonos_serializados,3)-2)-2)),' ',
if(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)=0,substr(telefonos_serializados,locate('\"',telefonos_serializados,3)+3,locate('\"',telefonos_serializados,locate('\"',telefonos_serializados,3)+3)-(locate('\"',telefonos_serializados,3)+3)),substr(telefonos_serializados,locate(',',telefonos_serializados,3)+2,(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)-2)-(locate(',',telefonos_serializados,3)+1))), ' ',
if(locate(',',telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2)=0,substr(telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2,(locate('\"',telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2)-1)-(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+1)),'')
)) Telefonos, a.telefono 'Telefono_prncipal'
,'' Celular, a.direccion Direcciones, if(locate(',',referencias_serializados)<>0,concat(substr(referencias_serializados,2+1,(locate(',',referencias_serializados))-2-2), ' ' ,substr(referencias_serializados,(locate(',',referencias_serializados))+2,(locate(',',referencias_serializados,(locate(',',referencias_serializados))+2))-((locate(',',referencias_serializados))+1)-2)),substr(referencias_serializados,3,(locate(']',referencias_serializados))-1-3)) Referencias
,a.deuda_inicial 'Deuda_inicial', a.deuda_actual 'Deuda_actual', (a.deuda_inicial - a.deuda_actual) Recuperacion, f.username 'Gestor_actual',
h.username 'Ultima_gestion_gestor', a.fecha_ultima_gestion 'Ultima_gestion', j.nombre 'Ultima_gestion_tipo', i.nombre 'Ultima_gestion_accion',
ifnull((select if(z.sub_motivo_id is not null,x.nombre,'') from cobefec_pruebas.motivo x, cobefec_pruebas.sub_motivo y, cobefec_pruebas.gestion z where x.id=y.motivo_id and y.id=z.sub_motivo_id and z.id=a.ultima_gestion_id), '--') 'Ultima_gestion_motivo',
ifnull((select if(y.sub_motivo_id is not null,x.nombre, '' ) from cobefec_pruebas.sub_motivo x, cobefec_pruebas.gestion y where x.id=y.sub_motivo_id and y.id=a.ultima_gestion_id),'--') 'Ultima_gestion_submotivo',
g.observacion 'Ultima_gestion_observacion', if(g.fecha_pp is not null,g.fecha_pp,'') 'Ultima_gestion_Fecha_promesa_pago',if(g.valor_pp is not null,g.valor_pp,'') 'Ultima_gestion_Monto_promesa_pago',
h_m.username 'Mejor_gestion_Gestor',g_m.fecha 'Mejor_gestion_Fecha_Hora', j_m.nombre 'Mejor_gestion_tipo', i_m.nombre 'Mejor_gestion_accion',
ifnull((select if(z.sub_motivo_id is not null,x.nombre,'') from cobefec_pruebas.motivo x, cobefec_pruebas.sub_motivo y, cobefec_pruebas.gestion z where x.id=y.motivo_id and y.id=z.sub_motivo_id and z.id=a.mejor_gestion_id),'--') 'Mejor_gestion_motivo',
ifnull((select if(y.sub_motivo_id is not null,x.nombre, '' ) from cobefec_pruebas.sub_motivo x, cobefec_pruebas.gestion y where x.id=y.sub_motivo_id and y.id=a.mejor_gestion_id),'--') 'Mejor_gestion_submotivo',
g_m.observacion 'Mejor_gestion_Observacion', if(g_m.fecha_pp is not null,g_m.fecha_pp,'') 'Mejor_gestion_Fecha_promesa_pago', if(g_m.valor_pp is not null,g_m.valor_pp,'') 'Mejor_gestion_Monto_promesa_pago',
'--' Tipo_Contacto, '--' Contacto, '--' Horario,
'0' A,
substr(a.principales_serializados,locate('\"17\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"17\":',a.principales_serializados)+6)-(locate('\"17\":',a.principales_serializados)+6)) 'CODIGO_DE_PROVINCIA',
substr(a.principales_serializados,locate('\"18\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"18\":',a.principales_serializados)+6)-(locate('\"18\":',a.principales_serializados)+6)) 'TIPO_DE_REPRESENTANTE',
substr(a.principales_serializados,locate('\"19\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"19\":',a.principales_serializados)+6)-(locate('\"19\":',a.principales_serializados)+6)) 'ZONA',
substr(a.principales_serializados,locate('\"20\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"20\":',a.principales_serializados)+6)-(locate('\"20\":',a.principales_serializados)+6)) 'SECCION',
substr(a.principales_serializados,locate('\"21\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"21\":',a.principales_serializados)+6)-(locate('\"21\":',a.principales_serializados)+6)) 'ANTIGUEDAD',
substr(a.principales_serializados,locate('\"25\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"25\":',a.principales_serializados)+6)-(locate('\"25\":',a.principales_serializados)+6)) 'CIUDAD',
substr(a.principales_serializados,locate('\"26\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"26\":',a.principales_serializados)+6)-(locate('\"26\":',a.principales_serializados)+6)) 'PAST_DUE',
substr(a.principales_serializados,locate('\"27\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"27\":',a.principales_serializados)+6)-(locate('\"27\":',a.principales_serializados)+6)) 'EQUIPO',
substr(a.principales_serializados,locate('\"30\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"30\":',a.principales_serializados)+6)-(locate('\"30\":',a.principales_serializados)+6)) 'EMAIL',
substr(a.principales_serializados,locate('\"31\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"31\":',a.principales_serializados)+6)-(locate('\"31\":',a.principales_serializados)+6)) 'DIVISION',
substr(a.principales_serializados,locate('\"32\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"32\":',a.principales_serializados)+6)-(locate('\"32\":',a.principales_serializados)+6)) 'REPRESENTANTE',
substr(a.principales_serializados,locate('\"33\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"33\":',a.principales_serializados)+6)-(locate('\"33\":',a.principales_serializados)+6)) 'SALDO_ACTUAL',
substr(a.principales_serializados,locate('\"34\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"34\":',a.principales_serializados)+6)-(locate('\"34\":',a.principales_serializados)+6)) 'CAMPANA_COB', 
b.id idcampana, a.estado estado_i
from cobefec_pruebas.cuenta a, cobefec_pruebas.capana b, cobefec_pruebas.producto c, cobefec_pruebas.marca d, cobefec_pruebas.producto_etapa e, cobefec_pruebas.usuario f, cobefec_pruebas.gestion g, cobefec_pruebas.usuario h, cobefec_pruebas.accion i, cobefec_pruebas.tipo j
, cobefec_pruebas.gestion g_m, cobefec_pruebas.usuario h_m, cobefec_pruebas.accion i_m, cobefec_pruebas.tipo j_m
where b.id=a.capana_id and c.id=b.producto_id and d.id=c.marca_id and e.id=a.etapa_id and f.id=a.usuario_id and g.id=a.ultima_gestion_id
and g.usuario_id=h.id and g.accion_id=i.id and i.tipo_id=j.id
and g_m.id=a.mejor_gestion_id and g_m.usuario_id=h_m.id and g_m.accion_id=i_m.id and i_m.tipo_id=j_m.id
and d.id='37' and c.id='153' and b.id in (".$campanias.") and e.id='130' ".$estado." 
UNION ALL
select a.ci Cedula,a.no_cuenta Cuenta, a.producto Producto, a.nombre Nombre, e.nombre 'Etapa actual', if(a.estado='1','Habilitado','Deshabilitado') Estado,
trim(CONCAT(if(locate(',',telefonos_serializados,3)=0,substr(telefonos_serializados,3,(locate('\"',telefonos_serializados,3)-1)-2),substr(telefonos_serializados,3,(locate(',',telefonos_serializados,3)-2)-2)),' ',
if(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)=0,substr(telefonos_serializados,locate('\"',telefonos_serializados,3)+3,locate('\"',telefonos_serializados,locate('\"',telefonos_serializados,3)+3)-(locate('\"',telefonos_serializados,3)+3)),substr(telefonos_serializados,locate(',',telefonos_serializados,3)+2,(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)-2)-(locate(',',telefonos_serializados,3)+1))), ' ',
if(locate(',',telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2)=0,substr(telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2,(locate('\"',telefonos_serializados,locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+2)-1)-(locate(',',telefonos_serializados,locate(',',telefonos_serializados,3)+2)+1)),'')
)) Telefonos, a.telefono 'Telefono prncipal'
,'' Celular, a.direccion Direcciones, if(locate(',',referencias_serializados)<>0,concat(substr(referencias_serializados,2+1,(locate(',',referencias_serializados))-2-2), ' ' ,substr(referencias_serializados,(locate(',',referencias_serializados))+2,(locate(',',referencias_serializados,(locate(',',referencias_serializados))+2))-((locate(',',referencias_serializados))+1)-2)),substr(referencias_serializados,3,(locate(']',referencias_serializados))-1-3)) Referencias 
,a.deuda_inicial 'Deuda inicial', a.deuda_actual 'Deuda actual', (a.deuda_inicial - a.deuda_actual) Recuperacion, 
if(a.usuario_id is null, '',(select username from cobefec_pruebas.usuario where id=a.usuario_id)) 'Gestor actual',
'--' Ultima_gestion_gestor, '--' Ultima_gestiÃ³n_Fecha_Hora, '--' Ultima_gestion_tipo, '--' Ultima_gestion_accion, '--' Ultima_gestion_motivo, '--' Ultima_gestion_submotivo,
'--' Ultima_gestion_observacion, '--' Ultima_gestion_Fecha_promesa_pago, '--' Ultima_gestion_Monto_promesa_pago, '--' Mejor_gestion_Gestor, '--' Mejor_gestion_Fecha_Hora,
'--' Mejor_gestion_tipo, '--' Mejor_gestion_accion, '--' Mejor_gestion_motivo, '--' Mejor_gestion_submotivo, '--' Mejor_gestion_Observacion, '--' Mejor_gestion_Fecha_promesa_pago,
'--' Mejor_gestion_Monto_promesa_pago, '--' Tipo_Contacto, '--' Contacto, '--' Horario,
'0' A,
substr(a.principales_serializados,locate('\"17\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"17\":',a.principales_serializados)+6)-(locate('\"17\":',a.principales_serializados)+6)) 'CODIGO DE PROVINCIA',
substr(a.principales_serializados,locate('\"18\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"18\":',a.principales_serializados)+6)-(locate('\"18\":',a.principales_serializados)+6)) 'TIPO DE REPRESENTANTE',
substr(a.principales_serializados,locate('\"19\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"19\":',a.principales_serializados)+6)-(locate('\"19\":',a.principales_serializados)+6)) 'ZONA',
substr(a.principales_serializados,locate('\"20\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"20\":',a.principales_serializados)+6)-(locate('\"20\":',a.principales_serializados)+6)) 'SECCION',
substr(a.principales_serializados,locate('\"21\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"21\":',a.principales_serializados)+6)-(locate('\"21\":',a.principales_serializados)+6)) 'ANTIGUEDAD',
substr(a.principales_serializados,locate('\"25\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"25\":',a.principales_serializados)+6)-(locate('\"25\":',a.principales_serializados)+6)) 'CIUDAD',
substr(a.principales_serializados,locate('\"26\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"26\":',a.principales_serializados)+6)-(locate('\"26\":',a.principales_serializados)+6)) 'PAST DUE',
substr(a.principales_serializados,locate('\"27\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"27\":',a.principales_serializados)+6)-(locate('\"27\":',a.principales_serializados)+6)) 'EQUIPO',
substr(a.principales_serializados,locate('\"30\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"30\":',a.principales_serializados)+6)-(locate('\"30\":',a.principales_serializados)+6)) 'EMAIL',
substr(a.principales_serializados,locate('\"31\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"31\":',a.principales_serializados)+6)-(locate('\"31\":',a.principales_serializados)+6)) 'DIVISION',
substr(a.principales_serializados,locate('\"32\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"32\":',a.principales_serializados)+6)-(locate('\"32\":',a.principales_serializados)+6)) 'REPRESENTANTE',
substr(a.principales_serializados,locate('\"33\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"33\":',a.principales_serializados)+6)-(locate('\"33\":',a.principales_serializados)+6)) 'SALDO ACTUAL',
substr(a.principales_serializados,locate('\"34\":',a.principales_serializados)+6,locate('\"',a.principales_serializados, locate('\"34\":',a.principales_serializados)+6)-(locate('\"34\":',a.principales_serializados)+6)) 'CAMPANA COB', 
b.id idcampana, a.estado estado_i
from cobefec_pruebas.cuenta a, cobefec_pruebas.capana b, cobefec_pruebas.producto c, cobefec_pruebas.marca d, cobefec_pruebas.producto_etapa e
where b.id=a.capana_id and c.id=b.producto_id and d.id=c.marca_id and e.id=a.etapa_id  
and d.id='37' and c.id='153' and b.id in (".$campanias.") and e.id='130' ".$estado."
and a.ultima_gestion_id is null 
INTO OUTFILE '/var/www/html/reportes/".$fecha.".csv'
    FIELDS TERMINATED BY ';'
    ENCLOSED BY '\"'
    LINES TERMINATED BY '\n';";

                DB::connection('gestionpe')->getpdo()->exec($query);
                return \Redirect::to('http://local.cobefec-reportesperu/'.$fecha.'.csv');

            }

            if ($request->idReporte==8){
                $fecha='historial-de-cuentas-ecuador-'.date('Y-m-d-H-i-s');
                $query="SELECT 'gestor','identificacion_cliente','no_cuenta','zona','region','nombre','deuda_actual','telefono','direccion','deuda_inicial','fecha_gestion','estado','campania','etapa','tipo','accion','motivo','submotivo','observacion','fecha_pp','valor_pp','tipo_contacto','contacto','horario'
union all
SELECT f.username gestor, b.ci identificacion_cliente, b.no_cuenta no_cuenta,'--' zona,'--' region, b.nombre nombre, b.deuda_actual deuda_actual, a.telefono telefono, b.direccion direccion, b.deuda_inicial deuda_inicial,a.fecha fecha_gestion, if(b.estado='1', 'Habilitado','Deshabilitado') estado, c.nombre campania, g.nombre etapa, i.nombre tipo, h.nombre accion, k.nombre motivo
, j.nombre submotivo, a.observacion observacion, ifnull(a.fecha_pp,'--') fecha_pp, ifnull(a.valor_pp,'--') valor_pp,'--' tipo_contacto,'--' contacto,'--' horario
FROM copiaprod.gestion a, copiaprod.cuenta b, copiaprod.capana c, copiaprod.producto d, copiaprod.marca e, copiaprod.usuario f, copiaprod.producto_etapa g, copiaprod.accion h, copiaprod.tipo i , copiaprod.sub_motivo j, copiaprod.motivo k
WHERE b.id=a.cuenta_id and c.id=b.capana_id
and d.id=c.producto_id and e.id=d.marca_id 
and f.id=a.usuario_id and g.id=b.etapa_id and h.id=a.accion_id and i.id=h.tipo_id  and j.id=a.sub_motivo_id  and k.id=j.motivo_id
and e.id='25' and d.id='98'and g.id='86' -- and b.estado='1' 
and a.sub_motivo_id is not null
and c.id='4790'
union all
SELECT f.username gestor, b.ci identificacion_cliente, b.no_cuenta no_cuenta,'--' zona,'--' region, b.nombre nombre, b.deuda_actual, a.telefono, b.direccion, b.deuda_inicial,a.fecha, if(b.estado='1', 'Habilitado','Deshabilitado') estado, c.nombre campania, g.nombre etapa, i.nombre tipo, h.nombre accion , '--' motivo, '--' submotivo
, a.observacion, ifnull(a.fecha_pp,'--') fecha_pp, ifnull(a.valor_pp,'--') valor_pp,'--' tipo_contacto,'--' contacto,'--' horario
FROM copiaprod.gestion a, copiaprod.cuenta b, copiaprod.capana c, copiaprod.producto d, copiaprod.marca e, copiaprod.usuario f, copiaprod.producto_etapa g, copiaprod.accion h, copiaprod.tipo i 
WHERE b.id=a.cuenta_id and c.id=b.capana_id
and d.id=c.producto_id and e.id=d.marca_id 
and f.id=a.usuario_id and g.id=b.etapa_id and h.id=a.accion_id and i.id=h.tipo_id 
and e.id='25' and d.id='98'and g.id='86' -- and b.estado='1' 
and a.sub_motivo_id is null
and c.id='4790'

INTO OUTFILE '/var/www/html/reportes/".$fecha.".csv'
    FIELDS TERMINATED BY ';'
    ENCLOSED BY '\"'
    LINES TERMINATED BY '\n';";

                DB::connection('gestionec')->getpdo()->exec($query);
                return \Redirect::to('http://172.16.5.25:8088/'.$fecha.'.csv');

            }

            if ($request->idReporte==9){
                $fecha='historial-de-cuentas-ecuador-'.date('Y-m-d-H-i-s');
                $query="SELECT 'gestor','identificacion_cliente','no_cuenta','zona','region','nombre','deuda_actual','telefono','direccion','deuda_inicial','fecha_gestion','estado','campania','etapa','tipo','accion','motivo','submotivo','observacion','fecha_pp','valor_pp','tipo_contacto','contacto','horario'
union all
SELECT f.username gestor, b.ci identificacion_cliente, b.no_cuenta no_cuenta,'--' zona,'--' region, b.nombre nombre, b.deuda_actual deuda_actual, a.telefono telefono, b.direccion direccion, b.deuda_inicial deuda_inicial,a.fecha fecha_gestion, if(b.estado='1', 'Habilitado','Deshabilitado') estado, c.nombre campania, g.nombre etapa, i.nombre tipo, h.nombre accion, k.nombre motivo
, j.nombre submotivo, a.observacion observacion, ifnull(a.fecha_pp,'--') fecha_pp, ifnull(a.valor_pp,'--') valor_pp,'--' tipo_contacto,'--' contacto,'--' horario
FROM copiaprod.gestion a, copiaprod.cuenta b, copiaprod.capana c, copiaprod.producto d, copiaprod.marca e, copiaprod.usuario f, copiaprod.producto_etapa g, copiaprod.accion h, copiaprod.tipo i , copiaprod.sub_motivo j, copiaprod.motivo k
WHERE b.id=a.cuenta_id and c.id=b.capana_id
and d.id=c.producto_id and e.id=d.marca_id 
and f.id=a.usuario_id and g.id=b.etapa_id and h.id=a.accion_id and i.id=h.tipo_id  and j.id=a.sub_motivo_id  and k.id=j.motivo_id
and e.id='25' and d.id='98'and g.id='86' -- and b.estado='1' 
and a.sub_motivo_id is not null
and c.id='4790'
union all
SELECT f.username gestor, b.ci identificacion_cliente, b.no_cuenta no_cuenta,'--' zona,'--' region, b.nombre nombre, b.deuda_actual, a.telefono, b.direccion, b.deuda_inicial,a.fecha, if(b.estado='1', 'Habilitado','Deshabilitado') estado, c.nombre campania, g.nombre etapa, i.nombre tipo, h.nombre accion , '--' motivo, '--' submotivo
, a.observacion, ifnull(a.fecha_pp,'--') fecha_pp, ifnull(a.valor_pp,'--') valor_pp,'--' tipo_contacto,'--' contacto,'--' horario
FROM copiaprod.gestion a, copiaprod.cuenta b, copiaprod.capana c, copiaprod.producto d, copiaprod.marca e, copiaprod.usuario f, copiaprod.producto_etapa g, copiaprod.accion h, copiaprod.tipo i 
WHERE b.id=a.cuenta_id and c.id=b.capana_id
and d.id=c.producto_id and e.id=d.marca_id 
and f.id=a.usuario_id and g.id=b.etapa_id and h.id=a.accion_id and i.id=h.tipo_id 
and e.id='25' and d.id='98'and g.id='86' -- and b.estado='1' 
and a.sub_motivo_id is null
and c.id='4790'

INTO OUTFILE '/var/www/html/reportes/".$fecha.".csv'
    FIELDS TERMINATED BY ';'
    ENCLOSED BY '\"'
    LINES TERMINATED BY '\n';";

                DB::connection('gestionec')->getpdo()->exec($query);
                return \Redirect::to('http://172.16.5.25:8088/'.$fecha.'.csv');

            }

        }catch(\Exception $e){
            return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
        }
        //return \Redirect::to('https://192.168.99.251/reportes/limpiar.php');
    }
}
