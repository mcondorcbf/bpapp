<?php

namespace App\Http\Controllers\ReportesNuevoSistema;

use App\apiRest\tbl_api_cobefec;
use App\reportesNuevoSistema\cex\cex_horas_trabajadas;
use App\reportesNuevoSistema\cex\cex_observacion_horast;
use App\reportesNuevoSistema\cex\cex_tiempos_muertos;
use App\reportesNuevoSistema\cex\cex_tipo_observacion;
use App\reportesNuevoSistema\cex\tbl_auditoria_dispositivos;
use App\reportesNuevoSistema\cex\tbl_dispositivos;
use App\reportesNuevoSistema\cex\tbl_paradas_configuracion;
use App\reportesNuevoSistema\cex\tbl_paradas_recorrido;
use App\reportesNuevoSistema\encuestasCex\tbl_categorias;
use App\reportesNuevoSistema\encuestasCex\tbl_preguntas;
use App\reportesNuevoSistema\encuestasCex\tbl_rol;
use App\reportesNuevoSistema\encuestasCex\tbl_usuarios;
use App\reportesNuevoSistema\tbl_accounts;
use App\reportesNuevoSistema\tbl_brands;
use App\reportesNuevoSistema\tbl_campaigns;
use App\reportesNuevoSistema\tbl_demarches;
use App\reportesNuevoSistema\tbl_executives;
use App\reportesNuevoSistema\tbl_products;
use App\reportesNuevoSistema\tbl_routes;
use App\reportesNuevoSistema\tbl_users;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\Yaml\Tests\B;

class ReportesCexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reportes()
    {
        if (\Auth::user()->email=='esalinas@cobefec.com' || \Auth::user()->email=='lmotoche@cobefec.com' || \Auth::user()->email=='lnavas@cobefec.com' || \Auth::user()->email=='vorquera@cobefec.com' || \Auth::user()->email=='lgonzalez@cobefec.com' || \Auth::user()->email=='xpalacios@cobefec.com' || \Auth::user()->email=='rduarte@cobefec.com' || \Auth::user()->email=='lpinto@cobefec.com' || \Auth::user()->email=='lchavez@cobefec.com'){
            return redirect()->action('ReportesNuevoSistema\ReportesCexController@rCexAsignaciones');
        }else{
            try{
                //$marcas=tbl_brands::get();
                $marcas=DB::connection('cobefec3')->select("SELECT id,name FROM cobefec3.brands where deleted_at is null and enabled=1;");

            }catch (\Exception $exception){
                echo $exception->getMessage();
            }
            $menu='primer_reporte';

            /*$query="select a.executive_id ID_GESTOR_CEX, concat(u.first_name, ' ', u.last_name) GESTOR_CEX, a.assigned_date FECHA,
    ifnull((select min(cex_time) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') HORA_PRIMERA_GESTION,
    ifnull((select max(cex_time) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') HORA_ULTIMA_GESTION, '01:00:00' HORA_ALMUERZO,
    ifnull((select if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') HORAS_TRABAJADAS,
    '09:00:00' JORNADA_COMPLETA,
    ifnull((select if(timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')>'08:00:00','00:00:00',time_format(timediff('08:00:00',if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00'))),\"%H:%i:%s\")) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') TIEMPOS_MUERTOS,
    '08:00:00' TIEMPO_MEDICION,
    ifnull((select concat(round((time_to_sec(if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')))/time_to_sec('08:00:00'))*100,1),' %') from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') PORCENTAJE_OCUPACION, '' OBSERVACION
    from cobefec3.assignment_cex a, cobefec3.executives e, cobefec3.users u
    where e.id=a.executive_id and u.id=e.user_id and a.executive_id<>1 and date(a.assigned_date) between '".date('Y-m-d')."' and '".date('Y-m-d')."'
    group by 1,2,3
    union all
    select e.id, concat(u.first_name, ' ', u.last_name) GESTOR_CEX, '' FECHA, '' HORA_PRIMERA_GESTION, '' HORA_ULTIMA_GESTION, '' HORA_ALMUERZO, '' HORAS_TRABAJADAS, '' JORNADA_COMPLETA, '' TIEMPOS_MUERTOS, '' TIEMPO_MEDICION, '' PORCENTAJE_OCUPACION, '' OBSERVACION
    from cobefec3.executives e, cobefec3.users u
    where e.deleted_at is null and e.id<>1 and u.id=e.user_id and e.id not in
    (select distinct executive_id from cobefec3.assignment_cex where executive_id<>1 and date(assigned_date) between '".date('Y-m-d')."' and '".date('Y-m-d')."')
    order by 2
    ;";*/
            //14-08-2018 Cambio por SP que mejora la velociad de despliegue de la consulta
            $query=("call cobefec_reportes.control_asistencia_ini('".date('Y-m-d')."','".date('Y-m-d')."');");
            try {
                set_time_limit(0);
                $sql=DB::connection('cobefec3')->select($query);
            }catch(\Exception $e) {
                return $e->getMessage();
            }
            $reportes = json_decode(json_encode($sql),true);


            foreach ($reportes as $k){
                if (cex_horas_trabajadas::where('id_gestor_cex',$k['ID_GESTOR_CEX'])->where('fecha',$k['FECHA'])->count()>0){
                    $horasTrabajadas=cex_horas_trabajadas::where('id_gestor_cex',$k['ID_GESTOR_CEX'])->where('fecha',$k['FECHA'])->first();
                    $horasTrabajadas->gestor_cex=$k['GESTOR_CEX'];
                    $horasTrabajadas->id_gestor_cex=$k['ID_GESTOR_CEX'];
                    $horasTrabajadas->fecha=$k['FECHA'];
                    $horasTrabajadas->hora_primera_gestion=$k['HORA_PRIMERA_GESTION'];
                    $horasTrabajadas->hora_ultima_gestion=$k['HORA_ULTIMA_GESTION'];
                    $horasTrabajadas->hora_almuerzo=$k['HORA_ALMUERZO'];
                    $horasTrabajadas->horas_trabajadas=$k['HORAS_TRABAJADAS'];
                    $horasTrabajadas->jornada_completa=$k['JORNADA_COMPLETA'];
                    $horasTrabajadas->tiempos_muertos=$k['TIEMPOS_MUERTOS'];
                    $horasTrabajadas->tiempo_medicion=$k['TIEMPO_MEDICION'];
                    $horasTrabajadas->porcentaje_ocupacion=$k['PORCENTAJE_OCUPACION'];
                    $horasTrabajadas->save();
                }else{
                    $horasTrabajadas=new cex_horas_trabajadas();
                    $horasTrabajadas->gestor_cex=$k['GESTOR_CEX'];
                    $horasTrabajadas->id_gestor_cex=$k['ID_GESTOR_CEX'];
                    $horasTrabajadas->fecha=$k['FECHA'];
                    $horasTrabajadas->hora_primera_gestion=$k['HORA_PRIMERA_GESTION'];
                    $horasTrabajadas->hora_ultima_gestion=$k['HORA_ULTIMA_GESTION'];
                    $horasTrabajadas->hora_almuerzo=$k['HORA_ALMUERZO'];
                    $horasTrabajadas->horas_trabajadas=$k['HORAS_TRABAJADAS'];
                    $horasTrabajadas->jornada_completa=$k['JORNADA_COMPLETA'];
                    $horasTrabajadas->tiempos_muertos=$k['TIEMPOS_MUERTOS'];
                    $horasTrabajadas->tiempo_medicion=$k['TIEMPO_MEDICION'];
                    $horasTrabajadas->porcentaje_ocupacion=$k['PORCENTAJE_OCUPACION'];
                    $horasTrabajadas->save();
                }
            }

            $tiemposMuertos=cex_tiempos_muertos::first()->tiempo;

            $reportes=cex_horas_trabajadas::whereBetween('fecha',[date('Y-m-d'), date('Y-m-d')])->get();
            $gestores=cex_horas_trabajadas::groupBy('id_gestor_cex')->orderBy('gestor_cex','ASC')->pluck("gestor_cex","id_gestor_cex")->all();
            $gestoresa=cex_horas_trabajadas::groupBy('id_gestor_cex')->orderBy('gestor_cex','ASC')->pluck('id_gestor_cex');


            return view('reporteNuevoSistema/cex/index', compact('marcas','menu','reportes','tiemposMuertos','gestores','gestoresa'));
        }

    }

    public function rAsistencia(Request $request)
    {
        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        try{
            //$marcas=tbl_brands::get();
            $marcas=DB::connection('cobefec3')->select("SELECT id,name FROM cobefec3.brands where deleted_at is null and enabled=1;");
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }

        $menu='primer_reporte';
        /*$query="select a.executive_id ID_GESTOR_CEX, concat(u.first_name, ' ', u.last_name) GESTOR_CEX, a.assigned_date FECHA,
ifnull((select min(cex_time) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') HORA_PRIMERA_GESTION,
ifnull((select max(cex_time) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') HORA_ULTIMA_GESTION, '01:00:00' HORA_ALMUERZO,
ifnull((select if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') HORAS_TRABAJADAS,
'09:00:00' JORNADA_COMPLETA,
ifnull((select if(timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')>'08:00:00','00:00:00',time_format(timediff('08:00:00',if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00'))),\"%H:%i:%s\")) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') TIEMPOS_MUERTOS,
'08:00:00' TIEMPO_MEDICION,
ifnull((select concat(round((time_to_sec(if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')))/time_to_sec('08:00:00'))*100,1),' %') from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') PORCENTAJE_OCUPACION, '' OBSERVACION
from cobefec3.assignment_cex a, cobefec3.executives e, cobefec3.users u
where e.id=a.executive_id and u.id=e.user_id and a.executive_id<>1 and date(a.assigned_date) between '".$fecha_inicio."' and '".$fecha_fin."'
group by 1,2,3
;
";*/
        //14-08-2018 Cambio por SP que mejora la velociad de despliegue de la consulta

        /*
        $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'd-m-Y' , $nuevafecha );
        */
        $query=("call cobefec_reportes.control_asistencia_ini('".$fecha_inicio."','".$fecha_fin."');");
        try {
            set_time_limit(0);
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $reportes = json_decode(json_encode($sql),true);


        foreach ($reportes as $k){
            if (cex_horas_trabajadas::where('id_gestor_cex',$k['ID_GESTOR_CEX'])->where('fecha',$k['FECHA'])->count()>0){
                $horasTrabajadas=cex_horas_trabajadas::where('id_gestor_cex',$k['ID_GESTOR_CEX'])->where('fecha',$k['FECHA'])->first();
                $horasTrabajadas->gestor_cex=$k['GESTOR_CEX'];
                $horasTrabajadas->id_gestor_cex=$k['ID_GESTOR_CEX'];
                $horasTrabajadas->fecha=$k['FECHA'];
                $horasTrabajadas->hora_primera_gestion=$k['HORA_PRIMERA_GESTION'];
                $horasTrabajadas->hora_ultima_gestion=$k['HORA_ULTIMA_GESTION'];
                $horasTrabajadas->hora_almuerzo=$k['HORA_ALMUERZO'];
                $horasTrabajadas->horas_trabajadas=$k['HORAS_TRABAJADAS'];
                $horasTrabajadas->jornada_completa=$k['JORNADA_COMPLETA'];
                $horasTrabajadas->tiempos_muertos=$k['TIEMPOS_MUERTOS'];
                $horasTrabajadas->tiempo_medicion=$k['TIEMPO_MEDICION'];
                $horasTrabajadas->porcentaje_ocupacion=$k['PORCENTAJE_OCUPACION'];
                $horasTrabajadas->save();
            }else{
                $horasTrabajadas=new cex_horas_trabajadas();
                $horasTrabajadas->gestor_cex=$k['GESTOR_CEX'];
                $horasTrabajadas->id_gestor_cex=$k['ID_GESTOR_CEX'];
                $horasTrabajadas->fecha=$k['FECHA'];
                $horasTrabajadas->hora_primera_gestion=$k['HORA_PRIMERA_GESTION'];
                $horasTrabajadas->hora_ultima_gestion=$k['HORA_ULTIMA_GESTION'];
                $horasTrabajadas->hora_almuerzo=$k['HORA_ALMUERZO'];
                $horasTrabajadas->horas_trabajadas=$k['HORAS_TRABAJADAS'];
                $horasTrabajadas->jornada_completa=$k['JORNADA_COMPLETA'];
                $horasTrabajadas->tiempos_muertos=$k['TIEMPOS_MUERTOS'];
                $horasTrabajadas->tiempo_medicion=$k['TIEMPO_MEDICION'];
                $horasTrabajadas->porcentaje_ocupacion=$k['PORCENTAJE_OCUPACION'];
                $horasTrabajadas->save();
            }
        }
        $tiemposMuertos=cex_tiempos_muertos::first()->tiempo;

        if (is_null($request->gestores[0])){
            $reportes=cex_horas_trabajadas::whereBetween('fecha',[$fecha_inicio, $fecha_fin])->get();
            $gestoresa=cex_horas_trabajadas::groupBy('id_gestor_cex')->orderBy('gestor_cex','ASC')->pluck('id_gestor_cex');
        }else{
            $reportes=cex_horas_trabajadas::whereIn('id_gestor_cex',$request->gestores)->whereBetween('fecha',[$fecha_inicio, $fecha_fin])->get();
            $gestoresa=cex_horas_trabajadas::whereIn('id_gestor_cex',$request->gestores)->orderBy('gestor_cex','ASC')->groupBy('gestor_cex')->pluck('id_gestor_cex');
        }

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $gestores=cex_horas_trabajadas::groupBy('id_gestor_cex')->orderBy('gestor_cex','ASC')->pluck("gestor_cex","id_gestor_cex")->all();

        return view('reporteNuevoSistema/cex/index', compact('marcas','menu','reportes','fecha_inicio','fecha_fin','tiemposMuertos','gestores','gestoresa'));
    }

    public function rAsistenciaExcel(Request $request)
    {
        $request->gestores=substr($request->gestores,1);
        $request->gestores=substr($request->gestores,0,-1);
        $request->gestores=explode(',',$request->gestores);

        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        if (is_null($request->gestores[0])){
            $reportes=cex_horas_trabajadas::whereBetween('fecha',[$fecha_inicio, $fecha_fin])->get();
        }else{
            $gestores=$request->gestores;
            //$reportes=cex_horas_trabajadas::whereIn('id_gestor_cex',$gestores)->whereBetween('fecha',[$fecha_inicio, $fecha_fin])->get();
            $reportes=cex_horas_trabajadas::whereBetween('fecha',[$fecha_inicio, $fecha_fin])->get();
        }


        $reportesf=Array();
        $i=0;
        $j=2;
        $tiemposMuertos=cex_tiempos_muertos::first()->tiempo;
        $marcas=Array();

        foreach ($reportes as $k){
            $observaciones=cex_observacion_horast::where('horas_trabajadas_id',$k->id)->get();
            if (count($observaciones)>0){
                foreach ($observaciones as $key){
                    $reportesf[$i]['GESTOR CEX']=$k->gestor_cex;
                    $reportesf[$i]['FECHA']=$k->fecha;
                    $reportesf[$i]['HORA PRIMERA GETION']=$k->hora_primera_gestion;
                    $reportesf[$i]['HORA ULTIMA GESTION']=$k->hora_ultima_gestion;
                    $reportesf[$i]['HORA ALMUERZO']=$k->hora_almuerzo;
                    $reportesf[$i]['HORAS TRABAJADAS']=$k->horas_trabajadas;
                    $reportesf[$i]['JORNADA COMPLETA']=$k->jornada_completa;
                    $reportesf[$i]['TIEMPOS MUERTOS']=$k->tiempos_muertos;
                    $reportesf[$i]['TIEMPO MEDICION']=$k->tiempo_medicion;
                    $reportesf[$i]['PORCENTAJE OCUPACION']=$k->porcentaje_ocupacion;
                    $reportesf[$i]['TIPO']=$key->tipoCex->nombre;
                    $reportesf[$i]['NOMBRES']=$key->nombres;
                    $reportesf[$i]['CEDULA']=$key->cedula;
                    $reportesf[$i]['ALMUERZO|TRANSPORTE HORA INICIO']=$key->hora_inicio;
                    $reportesf[$i]['ALMUERZO|TRANSPORTE HORA FIN']=$key->hora_fin;
                    $reportesf[$i]['OBSERVACIONES']='';
                    $reportesf[$i]['OBSERVACIONES'].=$key->observacion.' '.$key->hora_inicio .' '.$key->hora_fin;
                    if(strtotime($k->tiempos_muertos)>strtotime($tiemposMuertos)){
                        $marcas[$i]=$j++;
                    }else{
                        $j++;
                    }
                    $i++;
                }
            }else{
                $reportesf[$i]['GESTOR CEX']=$k->gestor_cex;
                $reportesf[$i]['FECHA']=$k->fecha;
                $reportesf[$i]['HORA PRIMERA GETION']=$k->hora_primera_gestion;
                $reportesf[$i]['HORA ULTIMA GESTION']=$k->hora_ultima_gestion;
                $reportesf[$i]['HORA ALMUERZO']=$k->hora_almuerzo;
                $reportesf[$i]['HORAS TRABAJADAS']=$k->horas_trabajadas;
                $reportesf[$i]['JORNADA COMPLETA']=$k->jornada_completa;
                $reportesf[$i]['TIEMPOS MUERTOS']=$k->tiempos_muertos;
                $reportesf[$i]['TIEMPO MEDICION']=$k->tiempo_medicion;
                $reportesf[$i]['PORCENTAJE OCUPACION']=$k->porcentaje_ocupacion;
                $reportesf[$i]['TIPO']='';
                $reportesf[$i]['NOMBRES']='';
                $reportesf[$i]['CEDULA']='';
                $reportesf[$i]['ALMUERZO|TRANSPORTE HORA INICIO']='';
                $reportesf[$i]['ALMUERZO|TRANSPORTE HORA FIN']='';
                $reportesf[$i]['OBSERVACIONES']='';
                if(strtotime($k->tiempos_muertos)>strtotime($tiemposMuertos)){
                    $marcas[$i]=$j++;
                }else{
                    $j++;
                }
                $i++;
            }
        }
        try{
            \Excel::create('CONTROL DE ASISTENCIA (DISPOSITIVOS) '.date('d-m-Y His'), function($excel) use (&$reportesf,$marcas){
                $excel->sheet('CONTROL DE ASISTENCIA', function($sheet) use($reportesf,$marcas) {
                    $sheet->fromArray($reportesf,null,'A1',true);
                    $sheet->row(1, function ($row) {
                        $row->setBackground('#63b6fd');
                    });
                    foreach ($marcas as $marca){
                        $sheet->row($marca, function ($row) {
                            $row->setBackground('#ffafad');
                        });
                    }
                    $sheet->setBorder('A1:Q1', 'thin', "000");
                });
            })->export('xlsx');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /*public function rControlAsistenciaAjax()
    {
        $query="select d.agent GESTOR_CEX, date(d.cex_date) FECHA, min(cex_time) HORA_PRIMERA_GESTION, max(cex_time) HORA_ULTIMA_GESTION,
'01:00:00' HORA_ALMUERZO, if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')) HORAS_TRABAJADAS,
'09:00:00' JORNADA_COMPLETA, time_format(timediff('08:00:00',if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00'))),\"%H:%i:%s\") TIEMPOS_MUERTOS, '08:00:00' TIEMPO_MEDICION,
concat(round((time_to_sec(if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')))/time_to_sec('08:00:00'))*100,1),' %') PORCENTAJE_OCUPACION, '' OBSERVACION
from cobefec3.demarches d
where d.executive_id is not null and date(d.cex_date) between '".date('Y-m-d')."' and '".date('Y-m-d')."'
group by 1,2
;
";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }


        $reportes = json_decode(json_encode($sql),true);

        $json_data = array(
            "draw"            => '10',
            "recordsTotal"    => '100',
            "recordsFiltered" => '10',
            "data"            => $reportes
        );

        //echo json_encode($json_data);

        return json_encode($json_data);

    }
*/
    /*public function rControlAsistencia(Request $request)
    {
        set_time_limit(0);

        $query="select d.agent GESTOR_CEX, date(d.cex_date) FECHA, min(cex_time) HORA_PRIMERA_GESTION, max(cex_time) HORA_ULTIMA_GESTION, 
'01:00:00' HORA_ALMUERZO, if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')) HORAS_TRABAJADAS,
'09:00:00' JORNADA_COMPLETA, time_format(timediff('08:00:00',if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00'))),\"%H:%i:%s\") TIEMPOS_MUERTOS, '08:00:00' TIEMPO_MEDICION, 
concat(round((time_to_sec(if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')))/time_to_sec('08:00:00'))*100,1),' %') PORCENTAJE_OCUPACION, '' OBSERVACION
from cobefec3.demarches d
where d.executive_id is not null and date(d.cex_date) between '2018-06-01' and '2018-06-01'
group by 1,2
;
";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        $reportes = json_decode(json_encode($sql), true);

        try{
            \Excel::create('CONTROL DE ASISTENCIA (DISPOSITIVOS) '.date('d-m-Y'), function($excel) use (&$reportes){
                $excel->sheet('CONTROL DE ASISTENCIA', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                });
            })->export('xlsx');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
*/
    public function obCexAsistencia(Request $request)
    {
        $reporte=cex_horas_trabajadas::find($request->id);
        $tipoObservacion=cex_tipo_observacion::pluck("nombre","id")->all();
        $fecha_inicio=$request->fecha_inicio;
        $fecha_fin=$request->fecha_fin;
        $id=$request->id;
        return view('reporteNuevoSistema/cex/observacionesCex', compact('reporte','tipoObservacion','fecha_inicio','fecha_fin','id'));
    }

    public function rControlAsistenciaObs(Request $request)
    {
        $reporte=cex_horas_trabajadas::find($request->id);
        $tipoObservacion=cex_tipo_observacion::pluck("nombre","id")->all();
        $observacion=new cex_observacion_horast();
        $observacion->observacion=$request->observacion;
        $observacion->fecha=date('Y-m-d H:i:s');
        $observacion->cedula=$request->cedula;
        $observacion->nombres=$request->nombres;
        $observacion->tipo_id=$request->tipoObservacion;
        if(isset($request->inicio)){
            $observacion->hora_inicio=$request->inicio;
        }
        if(isset($request->fin)){
            $observacion->hora_fin=$request->fin;
        }
        $observacion->horas_trabajadas_id=cex_horas_trabajadas::find($request->id)->id;
        $observacion->usuario_supervisor=\Auth::user()->email;
        $observacion->save();
        $fecha_inicio=$request->fecha_inicio;
        $fecha_fin=$request->fecha_fin;
        $id=$request->id;
        return view('reporteNuevoSistema/cex/observacionesCex', compact('reporte','tipoObservacion','fecha_inicio','fecha_fin','id'));
    }

    public function rRegresarCex(Request $request)
    {
        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        try{
            //$marcas=tbl_brands::get();
            $marcas=DB::connection('cobefec3')->select("SELECT id,name FROM cobefec3.brands where deleted_at is null and enabled=1;");
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }

        $menu='primer_reporte';
        /*$query="select a.executive_id ID_GESTOR_CEX, concat(u.first_name, ' ', u.last_name) GESTOR_CEX, a.assigned_date FECHA,
ifnull((select min(cex_time) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') HORA_PRIMERA_GESTION,
ifnull((select max(cex_time) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') HORA_ULTIMA_GESTION, '01:00:00' HORA_ALMUERZO,
ifnull((select if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') HORAS_TRABAJADAS,
'09:00:00' JORNADA_COMPLETA,
ifnull((select if(timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')>'08:00:00','00:00:00',time_format(timediff('08:00:00',if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00'))),\"%H:%i:%s\")) from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') TIEMPOS_MUERTOS,
'08:00:00' TIEMPO_MEDICION,
ifnull((select concat(round((time_to_sec(if(timediff(max(cex_time),min(cex_time))<'01:00:00','00:00:00',timediff(timediff(max(cex_time),min(cex_time)),'01:00:00')))/time_to_sec('08:00:00'))*100,1),' %') from cobefec3.demarches where executive_id=a.executive_id and date(created_at)=a.assigned_date),'') PORCENTAJE_OCUPACION, '' OBSERVACION
from cobefec3.assignment_cex a, cobefec3.executives e, cobefec3.users u
where e.id=a.executive_id and u.id=e.user_id and a.executive_id<>1 and date(a.assigned_date) between '".$fecha_inicio."' and '".$fecha_fin."'
group by 1,2,3
;
";*/
        //14-08-2018 Cambio por SP que mejora la velociad de despliegue de la consulta
        $query=("call cobefec_reportes.control_asistencia_ini('".date('Y-m-d')."','".date('Y-m-d')."');");
        try {
            set_time_limit(0);
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $reportes = json_decode(json_encode($sql),true);

        foreach ($reportes as $k){
            if (cex_horas_trabajadas::where('id_gestor_cex',$k['ID_GESTOR_CEX'])->where('fecha',$k['FECHA'])->count()>0){
                $horasTrabajadas=cex_horas_trabajadas::where('id_gestor_cex',$k['ID_GESTOR_CEX'])->where('fecha',$k['FECHA'])->first();
                $horasTrabajadas->gestor_cex=$k['GESTOR_CEX'];
                $horasTrabajadas->id_gestor_cex=$k['ID_GESTOR_CEX'];
                $horasTrabajadas->fecha=$k['FECHA'];
                $horasTrabajadas->hora_primera_gestion=$k['HORA_PRIMERA_GESTION'];
                $horasTrabajadas->hora_ultima_gestion=$k['HORA_ULTIMA_GESTION'];
                $horasTrabajadas->hora_almuerzo=$k['HORA_ALMUERZO'];
                $horasTrabajadas->horas_trabajadas=$k['HORAS_TRABAJADAS'];
                $horasTrabajadas->jornada_completa=$k['JORNADA_COMPLETA'];
                $horasTrabajadas->tiempos_muertos=$k['TIEMPOS_MUERTOS'];
                $horasTrabajadas->tiempo_medicion=$k['TIEMPO_MEDICION'];
                $horasTrabajadas->porcentaje_ocupacion=$k['PORCENTAJE_OCUPACION'];
                $horasTrabajadas->save();
            }else{
                $horasTrabajadas=new cex_horas_trabajadas();
                $horasTrabajadas->gestor_cex=$k['GESTOR_CEX'];
                $horasTrabajadas->id_gestor_cex=$k['ID_GESTOR_CEX'];
                $horasTrabajadas->fecha=$k['FECHA'];
                $horasTrabajadas->hora_primera_gestion=$k['HORA_PRIMERA_GESTION'];
                $horasTrabajadas->hora_ultima_gestion=$k['HORA_ULTIMA_GESTION'];
                $horasTrabajadas->hora_almuerzo=$k['HORA_ALMUERZO'];
                $horasTrabajadas->horas_trabajadas=$k['HORAS_TRABAJADAS'];
                $horasTrabajadas->jornada_completa=$k['JORNADA_COMPLETA'];
                $horasTrabajadas->tiempos_muertos=$k['TIEMPOS_MUERTOS'];
                $horasTrabajadas->tiempo_medicion=$k['TIEMPO_MEDICION'];
                $horasTrabajadas->porcentaje_ocupacion=$k['PORCENTAJE_OCUPACION'];
                $horasTrabajadas->save();
            }
        }
        $tiemposMuertos=cex_tiempos_muertos::first()->tiempo;

        if (is_null($request->gestores[0])){
            $reportes=cex_horas_trabajadas::whereBetween('fecha',[$fecha_inicio, $fecha_fin])->get();
            $gestoresa=cex_horas_trabajadas::groupBy('id_gestor_cex')->orderBy('gestor_cex','ASC')->pluck('id_gestor_cex');
        }else{
            $reportes=cex_horas_trabajadas::whereIn('id_gestor_cex',$request->gestores)->whereBetween('fecha',[$fecha_inicio, $fecha_fin])->get();
            $gestoresa=cex_horas_trabajadas::whereIn('id_gestor_cex',$request->gestores)->orderBy('gestor_cex','ASC')->groupBy('gestor_cex')->pluck('id_gestor_cex');
        }

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $gestores=cex_horas_trabajadas::groupBy('id_gestor_cex')->orderBy('gestor_cex','ASC')->pluck("gestor_cex","id_gestor_cex")->all();

        return view('reporteNuevoSistema/cex/index', compact('marcas','menu','reportes','fecha_inicio','fecha_fin','tiemposMuertos','gestores','gestoresa'));
    }

    public function rCexCumplimiento(){
        if (\Auth::user()->email=='esalinas@cobefec.com' || \Auth::user()->email=='lmotoche@cobefec.com' || \Auth::user()->email=='lnavas@cobefec.com' || \Auth::user()->email=='vorquera@cobefec.com' || \Auth::user()->email=='lgonzalez@cobefec.com' || \Auth::user()->email=='xpalacios@cobefec.com'){
            $supervisores=1;
        }else{$supervisores=0;}
        return view('reporteNuevoSistema/cex/cumplimiento', compact('supervisores'));
    }

    public function rCexCumplimientoP(Request $request)
    {
        set_time_limit(0);
        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        //REVISAR XQ LOS QUERYS 1-2-3 ESTAN DEMORANDO MUCHO
        $reportes1=cumplimientoQuery1($fecha_inicio,$fecha_fin);
        $reportes2=cumplimientoQuery2($fecha_inicio,$fecha_fin,'');
        $reportes3=cumplimientoQuery3('');
        $reportes4=cumplimientoQuery4();
        $reportes5=cumplimientoQuery5();
        $reportes6=cumplimientoQuery6();

        $reportesBelcorp2=cumplimientoQuery2($fecha_inicio,$fecha_fin,'Belcorp');
        $reportesBelcorp3=cumplimientoQuery3('Belcorp');
        $reportesBelcorp7=cumplimientoQuery7('Belcorp');
        $reportesBelcorp8=cumplimientoQuery8('Belcorp');
        $reportesBelcorp9=cumplimientoQuery9('Belcorp');
        $reportesBelcorp10=cumplimientoQuery10('Belcorp');
        $reportesBelcorp11=cumplimientoQuery11('Belcorp');

        $reportesDiners2=cumplimientoQuery2($fecha_inicio,$fecha_fin,'Diners');
        $reportesDiners3=cumplimientoQuery3('Diners');
        $reportesDiners7=cumplimientoQuery7('Diners');
        $reportesDiners8=cumplimientoQuery8('Diners');
        $reportesDiners9=cumplimientoQuery9('Diners');
        $reportesDiners10=cumplimientoQuery10('Diners');
        $reportesDiners11=cumplimientoQuery11('Diners');

        $reportesCoop2=cumplimientoQuery2($fecha_inicio,$fecha_fin,'Cooperativa 29 de Octubre');
        $reportesCoop3=cumplimientoQuery3('Cooperativa 29 de Octubre');
        $reportesCoop7=cumplimientoQuery7('Cooperativa 29 de Octubre');
        $reportesCoop8=cumplimientoQuery8('Cooperativa 29 de Octubre');
        $reportesCoop9=cumplimientoQuery9('Cooperativa 29 de Octubre');
        $reportesCoop10=cumplimientoQuery10('Cooperativa 29 de Octubre');
        $reportesCoop11=cumplimientoQuery11('Cooperativa 29 de Octubre');

        $reportesGye2=cumplimientoQuery2($fecha_inicio,$fecha_fin,'Banco de Guayaquil');
        $reportesGye3=cumplimientoQuery3('Banco de Guayaquil');
        $reportesGye7=cumplimientoQuery7('Banco de Guayaquil');
        $reportesGye8=cumplimientoQuery8('Banco de Guayaquil');
        $reportesGye9=cumplimientoQuery9('Banco de Guayaquil');
        $reportesGye10=cumplimientoQuery10('Banco de Guayaquil');
        $reportesGye11=cumplimientoQuery11('Banco de Guayaquil');

        $reportesDLegal2=cumplimientoQuery2($fecha_inicio,$fecha_fin,'Diners Legal');
        $reportesDLegal3=cumplimientoQuery3('Diners Legal');
        $reportesDLegal7=cumplimientoQuery7('Diners Legal');
        $reportesDLegal8=cumplimientoQuery8('Diners Legal');
        $reportesDLegal9=cumplimientoQuery9('Diners Legal');
        $reportesDLegal10=cumplimientoQuery10('Diners Legal');
        $reportesDLegal11=cumplimientoQuery11('Diners Legal');


        \Excel::create('REPORTE DE CUMPLIMIENTO '.$fecha_inicio.' al '.$fecha_fin, function($excel) use (&$reportes1,$reportes2,$reportes3,$reportes4,$reportes5,$reportes6,
            $reportesBelcorp2,$reportesBelcorp3,$reportesBelcorp7,$reportesBelcorp8,$reportesBelcorp9,$reportesBelcorp10,$reportesBelcorp11,
            $reportesDiners2,$reportesDiners3,$reportesDiners7,$reportesDiners8,$reportesDiners9,$reportesDiners10,$reportesDiners11,
            $reportesCoop2,$reportesCoop3,$reportesCoop7,$reportesCoop8,$reportesCoop9,$reportesCoop10,$reportesCoop11,
            $reportesGye2,$reportesGye3,$reportesGye7,$reportesGye8,$reportesGye9,$reportesGye10,$reportesGye11,
            $reportesDLegal2,$reportesDLegal3,$reportesDLegal7,$reportesDLegal8,$reportesDLegal9,$reportesDLegal10,$reportesDLegal11,
            $fecha_inicio,$fecha_fin){
            $excel->sheet('RESUMEN', function($sheet) use($reportes1,$reportes2,$reportes3,$reportes4,$reportes5,$reportes6,$fecha_inicio,$fecha_fin) {
                $sheet->loadView('reporteNuevoSistema/cex/table/tableCumplimientoResumen')->with('reportes1',$reportes1)
                    ->with('reportes2',$reportes2)
                    ->with('reportes3',$reportes3)
                    ->with('reportes4',$reportes4)
                    ->with('reportes5',$reportes5)
                    ->with('reportes6',$reportes6)
                    ->with('fecha_inicio',$fecha_inicio)
                    ->with('fecha_fin',$fecha_fin);
            });
            if (count($reportesBelcorp2)>0){
                $excel->sheet('BELCORP', function($sheet) use($reportes1,$reportesBelcorp2,$reportesBelcorp3,$reportesBelcorp7,$reportesBelcorp8,$reportesBelcorp9,$reportesBelcorp10,$reportesBelcorp11,$fecha_inicio,$fecha_fin) {
                    $sheet->loadView('reporteNuevoSistema/cex/table/tableCumplimientoMarca')->with('reportes1',$reportes1)
                        ->with('reportes2',$reportesBelcorp2)
                        ->with('reportes3',$reportesBelcorp3)
                        ->with('reportes7',$reportesBelcorp7)
                        ->with('reportes8',$reportesBelcorp8)
                        ->with('reportes9',$reportesBelcorp9)
                        ->with('reportes10',$reportesBelcorp10)
                        ->with('reportes11',$reportesBelcorp11)
                        ->with('fecha_inicio',$fecha_inicio)
                        ->with('fecha_fin',$fecha_fin);
                });
            }
            if (count($reportesDiners2)>0) {
                $excel->sheet('DINERS', function ($sheet) use ($reportes1, $reportesDiners2, $reportesDiners3, $reportesDiners7, $reportesDiners8, $reportesDiners9, $reportesDiners10, $reportesDiners11, $fecha_inicio, $fecha_fin) {
                    $sheet->loadView('reporteNuevoSistema/cex/table/tableCumplimientoMarca')->with('reportes1', $reportes1)
                        ->with('reportes2', $reportesDiners2)
                        ->with('reportes3', $reportesDiners3)
                        ->with('reportes7', $reportesDiners7)
                        ->with('reportes8', $reportesDiners8)
                        ->with('reportes9', $reportesDiners9)
                        ->with('reportes10', $reportesDiners10)
                        ->with('reportes11', $reportesDiners11)
                        ->with('fecha_inicio', $fecha_inicio)
                        ->with('fecha_fin', $fecha_fin);
                });
            }
            if (count($reportesCoop2)>0) {
                $excel->sheet('COOPERATIVA 29 DE OCTUBRE', function ($sheet) use ($reportes1, $reportesCoop2, $reportesCoop3, $reportesCoop7, $reportesCoop8, $reportesCoop9, $reportesCoop10, $reportesCoop11, $fecha_inicio, $fecha_fin) {
                    $sheet->loadView('reporteNuevoSistema/cex/table/tableCumplimientoMarca')->with('reportes1', $reportes1)
                        ->with('reportes2', $reportesCoop2)
                        ->with('reportes3', $reportesCoop3)
                        ->with('reportes7', $reportesCoop7)
                        ->with('reportes8', $reportesCoop8)
                        ->with('reportes9', $reportesCoop9)
                        ->with('reportes10', $reportesCoop10)
                        ->with('reportes11', $reportesCoop11)
                        ->with('fecha_inicio', $fecha_inicio)
                        ->with('fecha_fin', $fecha_fin);
                });
            }
            if (count($reportesGye2)>0) {
                $excel->sheet('BANCO DE GUAYAQUIL', function ($sheet) use ($reportes1, $reportesGye2, $reportesGye3, $reportesGye7, $reportesGye8, $reportesGye9, $reportesGye10, $reportesGye11, $fecha_inicio, $fecha_fin) {
                    $sheet->loadView('reporteNuevoSistema/cex/table/tableCumplimientoMarca')->with('reportes1', $reportes1)
                        ->with('reportes2', $reportesGye2)
                        ->with('reportes3', $reportesGye3)
                        ->with('reportes7', $reportesGye7)
                        ->with('reportes8', $reportesGye8)
                        ->with('reportes9', $reportesGye9)
                        ->with('reportes10', $reportesGye10)
                        ->with('reportes11', $reportesGye11)
                        ->with('fecha_inicio', $fecha_inicio)
                        ->with('fecha_fin', $fecha_fin);
                });
            }
            if (count($reportesDLegal2)>0) {
                $excel->sheet('DINERS LEGAL', function ($sheet) use ($reportes1, $reportesDLegal2, $reportesDLegal3, $reportesDLegal7, $reportesDLegal8, $reportesDLegal9, $reportesDLegal10, $reportesDLegal11, $fecha_inicio, $fecha_fin) {
                    $sheet->loadView('reporteNuevoSistema/cex/table/tableCumplimientoMarca')->with('reportes1', $reportes1)
                        ->with('reportes2', $reportesDLegal2)
                        ->with('reportes3', $reportesDLegal3)
                        ->with('reportes7', $reportesDLegal7)
                        ->with('reportes8', $reportesDLegal8)
                        ->with('reportes9', $reportesDLegal9)
                        ->with('reportes10', $reportesDLegal10)
                        ->with('reportes11', $reportesDLegal11)
                        ->with('fecha_inicio', $fecha_inicio)
                        ->with('fecha_fin', $fecha_fin);
                });
            }
        })->export('xlsx');
        //return view('reporteNuevoSistema/cex/table/tableCumplimientoMarca', compact('reportes1','reportesBelcorp2','reportesBelcorp3','reportes4','reportes5','reportes6','fecha_inicio','fecha_fin'));
    }

    public function encuestas()
    {
        try{
            $usuarios=tbl_usuarios::where('estado_usuario',1)->get();
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
        $menu='primer_reporte';
        $roles=tbl_rol::pluck("nombre","id_rol")->all();
        $preguntas=tbl_preguntas::where('estado_pregunta',1)->get();
        $categorias=tbl_categorias::pluck("nombre","id_categoria")->all();
        $categorias_all=tbl_categorias::where('estado_categoria',1)->get();
        return view('reporteNuevoSistema/cex/indexEncuesta', compact('roles','usuarios','menu','categorias','preguntas','categorias_all'));
    }

    public function ingresarAsesorCex(Request $request)
    {
        $error='';
        $mensaje='';
        $rol=tbl_rol::find($request->rol);

            try{
                //no ingresa el nuevo usuario a la tabla users verificar
                $user=User::where('email',$request->email)->first();
                if (!$user){
                    $user=new User();
                    $user->name=$request->nombres;
                    $user->email=$request->email;
                    $user->avatar='users/default.png';
                    $user->password=Hash::make($request->pass);
                    $user->role_id=Role::where('name',$rol->descripcion)->first()->id;
                    $user->save();
                }
            }catch (\Exception $e){
                if ($e->errorInfo[1] == 1062) {
                    return 'Error este asesor ya existe.';
                }else{
                    return $e->getMessage();
                }
            }
            if ($user->save()){
                $asesor=new tbl_usuarios();
                $asesor->nombres=$request->nombres;
                $asesor->cedula=$request->cedula;
                $asesor->email=$request->email;
                $asesor->id_rol=$rol->id_rol;
                try {
                    $asesor->save();
                }catch (\Exception $e) {
                    if ($e->errorInfo[1] == 1062) {
                        return 'Error este asesor ya existe.';
                    }else{
                        return $e->getMessage();
                    }
                }
            }
            $mensaje=$asesor->nombres.', correctamente ingresado.';
        $usuarios=tbl_usuarios::where('estado_usuario',1)->get();
        $menu=$request->menu;
        $roles=tbl_rol::pluck("nombre","id_rol")->all();
        $preguntas=tbl_preguntas::where('estado_pregunta',1)->get();
        $categorias=tbl_categorias::pluck("nombre","id_categoria")->all();
        return view('reporteNuevoSistema/cex/indexEncuesta', compact('roles','usuarios','menu','categorias','preguntas'));
    }

    public function ingresarPreguntaCex(Request $request)
    {
        $error='';
        $mensaje='';
        $categoria=tbl_categorias::find($request->categoria);
        try{
            //no ingresa el nuevo usuario a la tabla users verificar
            $pregunta=new tbl_preguntas();
            $pregunta->descripcion=$request->pregunta;
            $pregunta->puntos=$request->puntaje;
            $pregunta->id_categoria=$categoria->id_categoria;
            $pregunta->save();
        }catch(\Exception $e){
            if($e->errorInfo[1]== 1062){
                return 'Error ya existe.';
            }else{
                return $e->getMessage();
            }
        }
        $usuarios=tbl_usuarios::where('estado_usuario',1)->get();
        $menu=$request->menu;
        $roles=tbl_rol::pluck("nombre","id_rol")->all();
        $preguntas=tbl_preguntas::where('estado_pregunta',1)->get();
        $categorias=tbl_categorias::pluck("nombre","id_categoria")->all();
        return view('reporteNuevoSistema/cex/indexEncuesta', compact('roles','usuarios','menu','categorias','preguntas'));
    }

    public function usuariosGrupos($idAsesor)
    {
        $asesor=tbl_usuarios::where('id_usuario',$idAsesor)->first();
        return view('reporteNuevoSistema/cex/usuariosGrupos', compact('asesor'));
    }

    public function rCexAsignaciones()
    {
        if (\Auth::user()->email=='esalinas@cobefec.com' || \Auth::user()->email=='lmotoche@cobefec.com' || \Auth::user()->email=='lnavas@cobefec.com' || \Auth::user()->email=='vorquera@cobefec.com' || \Auth::user()->email=='lgonzalez@cobefec.com' || \Auth::user()->email=='xpalacios@cobefec.com'){
         $supervisores=1;
        }else{$supervisores=0;}
        $fecha_inicio = date('d/m/Y');
        $fecha_fin = date('d/m/Y');

        return view('reporteNuevoSistema/cex/asignaciones', compact('fecha_inicio','fecha_fin','supervisores'));
    }

    public function rAsignaciones(Request $request)
    {

        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        //$query="select * from cobefec_reportes.vista_asignacion_cex where Fecha between '".$fecha_inicio."' and '".$fecha_fin."';";
        $query="call cobefec_reportes.sp_asignacion_cex('".$fecha_inicio."' , '".$fecha_fin."');";
        try {
            set_time_limit(0);
            ini_set ( 'memory_limit' , '7048M' );
            ini_set('max_execution_time', 800);
            $sql =DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $reportes = json_decode(json_encode($sql),true);

        try{
            /*
            \Excel::create('REPORTE ASIGNACIONES CEX '.date('d-m-Y His'), function($excel) use (&$reportes){
                $excel->sheet('REPORTE ASIGNACIONES CEX', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                    $sheet->row(1, function ($row) {
                        $row->setBackground('#63b6fd');
                    });
                    $sheet->setBorder('A1:K1', 'thin', "000");
                });
            })->export('xlsx');
*/
            $i=count($reportes);

            \Excel::create('REPORTE ASIGNACIONES CEX del '.$fecha_inicio.' al '.$fecha_fin.' GENERADO EL '.date('d-m-Y His'), function($excel) use (&$reportes,
                $fecha_inicio,$fecha_fin){
                $excel->sheet('REPORTE ASIGNACIONES CEX', function($sheet) use($reportes) {
                    $sheet->loadView('reporteNuevoSistema/cex/table/tableAsignacionesCex')->with('reportes',$reportes);
                });
            })->export('xlsx');

        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function rCexAsignaciones2()
    {
        if (\Auth::user()->email=='esalinas@cobefec.com' || \Auth::user()->email=='lmotoche@cobefec.com' || \Auth::user()->email=='lnavas@cobefec.com' || \Auth::user()->email=='vorquera@cobefec.com' || \Auth::user()->email=='lgonzalez@cobefec.com' || \Auth::user()->email=='xpalacios@cobefec.com'){
            $supervisores=1;
        }else{$supervisores=0;}
        $fecha_inicio = date('d/m/Y');
        $fecha_fin = date('d/m/Y');

        return view('reporteNuevoSistema/cex/asignaciones2', compact('fecha_inicio','fecha_fin','supervisores'));
    }

    public function rAsignaciones2(Request $request)
    {

        set_time_limit(0);
        ini_set ( 'memory_limit' , '7048M' );
        ini_set('max_execution_time', 800);
        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');
        try{DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.tmp_cuentas;");

        }catch (\Exception $exception){
            return $exception->getMessage();
        }

        try{
            $dbh = DB::connection('cobefec3')->getPdo();
            set_time_limit(0);
            ini_set ( 'memory_limit' , '7048M' );
            ini_set('max_execution_time', 800);
$dbh->query("create temporary table cobefec_reportes.tmp_cuentas
select b1.id idmarca, z.id idzona, c.id idcampana, count(distinct a.id) cuentas  
from cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p, cobefec3.brands b1, cobefec3.zones z, cobefec3.executive_zone ez
where c.id=a.campaign_id and p.id=c.product_id and b1.id=p.brand_id and c.deleted_at is null
and z.id=a.zone_id and z.id=ez.zone_id and z.deleted_at is null  and z.enabled=1 and ez.executive_id<>1 and a.enabled=1
and ez.assigned_date between '".$fecha_inicio."' and '".$fecha_fin."'
group by 1,2,3
;
");

        }catch (\Exception $exception){
            return $exception->getMessage();
        }


        $query="select b.name Marca, c.name Campana, concat(u2.first_name, ' ', u2.last_name) Coordinador, concat(u.first_name, ' ', u.last_name) Gestor_CEX, m.map_name Mapa, -- z.id idzona, 
z.name Ruta, substr(e.label,locate('-',e.label)+1,10) Celular, u.username Cedula_Gestor, ez.quantity Cupo, 
ifnull((select cuentas from cobefec_reportes.tmp_cuentas where idmarca=b.id and idzona=z.id and idcampana=c.id),0) Cuentas_en_ruta
from cobefec3.executive_zone ez, cobefec3.zones z, cobefec3.brands b, cobefec3.executives e, cobefec3.users u, cobefec3.coordinators co, cobefec3.users u2, cobefec3.maps m, cobefec3.products p, cobefec3.campaigns c
where z.id=ez.zone_id and z.deleted_at is null  and z.enabled=1 and ez.executive_id<>1
and b.id=ez.brand_id and e.id=ez.executive_id and e.deleted_at is null and u.id=e.user_id
and co.id=e.coordinator_id and u2.id=co.user_id and m.id=z.map_id
and p.brand_id=b.id and c.product_id=p.id and p.deleted_at is null and p.enabled=1 and p.id<>2 and c.deleted_at is null and c.enabled=1
and ez.assigned_date between '".$fecha_inicio."' and '".$fecha_fin."'
order by 1,2,3,4,5,6
;
";
        try {
            set_time_limit(0);
            ini_set ( 'memory_limit' , '7048M' );
            ini_set('max_execution_time', 800);
            $sql =DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $reportes = json_decode(json_encode($sql),true);

        $i=0;
        foreach($reportes as $reporte) {
            $reportes[$i]['Cupo'] = intval($reporte['Cupo']);
            $reportes[$i]['Cuentas_en_ruta'] = intval($reporte['Cuentas_en_ruta']);
            $i++;
        }
        try{
            set_time_limit(0);
            ini_set ( 'memory_limit' , '7048M' );
            ini_set('max_execution_time', 800);
            dd(count($reportes));
            \Excel::create('INVENTARIO '.date('d-m-Y His'), function($excel) use (&$reportes){
                set_time_limit(0);
                ini_set ( 'memory_limit' , '7048M' );
                ini_set('max_execution_time', 800);
                $excel->sheet('INVENTARIO', function($sheet) use($reportes) {
                    set_time_limit(0);
                    ini_set ( 'memory_limit' , '7048M' );
                    ini_set('max_execution_time', 800);
                    $sheet->fromArray($reportes,null,'A1',true);
                    $sheet->row(1, function ($row) {
                        $row->setBackground('#63b6fd');
                    });
                    $sheet->setBorder('A1:J1', 'thin', "000");
                });
            })->export('xlsx');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function rCexInventario()
    {
        if (\Auth::user()->email=='esalinas@cobefec.com' || \Auth::user()->email=='lmotoche@cobefec.com' || \Auth::user()->email=='lnavas@cobefec.com' || \Auth::user()->email=='vorquera@cobefec.com' || \Auth::user()->email=='lgonzalez@cobefec.com' || \Auth::user()->email=='xpalacios@cobefec.com'){
            $supervisores=1;
        }else{$supervisores=0;}
        $fecha_inicio = date('d/m/Y');
        $fecha_fin = date('d/m/Y');

        return view('reporteNuevoSistema/cex/inventario', compact('fecha_inicio','fecha_fin','supervisores'));
    }

    public function rInventario(Request $request)
    {
        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        $query="select b.name Marca, concat(u2.first_name, ' ', u2.last_name) Coordinador,
concat(u.first_name, ' ', u.last_name) Gestor_CEX,  
ifnull(a.zone,'') Ruta, substr(e.label,locate('-',e.label)+1,10) Celular, u.username Cedula_Gestor, ez.quantity Cupo_asignacion
from cobefec3.assignment_cex a1, cobefec3.executives e, cobefec3.users u, cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p, cobefec3.brands b, cobefec3.coordinators co, cobefec3.users u2, cobefec3.executive_zone ez
where e.id=a1.executive_id and e.deleted_at is null and e.id<>1 and u.id=e.user_id
and a.id=a1.account_id and c.id=a.campaign_id and p.id=c.product_id and b.id=p.brand_id and co.id=e.coordinator_id and u2.id=co.user_id
and ez.executive_id=a1.executive_id and ez.zone_id=a1.zone_id and ez.assigned_date=a1.assigned_date and ez.brand_id=b.id
and a1.assigned_date between '".$fecha_inicio."' and '".$fecha_fin."'  
group by 1,2,3,4,5,6,7
order by 1,2,3,4
;";
        try{
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e){
            return $e->getMessage();
        }

        $reportes = json_decode(json_encode($sql),true);
        $i=0;
        foreach($reportes as $reporte) {
            $reportes[$i]['Cupo_asignacion'] = intval($reporte['Cupo_asignacion']);
            $i++;
        }

        try{
            \Excel::create('RESUMEN ASIGNACION '.date('d-m-Y His'), function($excel) use (&$reportes){
                $excel->sheet('RESUMEN ASIGNACION', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                    $sheet->row(1, function ($row) {
                        $row->setBackground('#63b6fd');
                    });
                    $sheet->setBorder('A1:G1', 'thin', "000");
                });
            })->export('xlsx');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function rCexZonificacion()
    {
        if (\Auth::user()->email=='esalinas@cobefec.com' || \Auth::user()->email=='lmotoche@cobefec.com' || \Auth::user()->email=='lnavas@cobefec.com' || \Auth::user()->email=='vorquera@cobefec.com' || \Auth::user()->email=='lgonzalez@cobefec.com' || \Auth::user()->email=='xpalacios@cobefec.com'){
            $supervisores=1;
        }else{$supervisores=0;}
        $fecha_inicio = date('d/m/Y');
        $fecha_fin = date('d/m/Y');

        try{
            //$marcas=tbl_brands::get();
            $marcas=DB::connection('cobefec3')->select("SELECT id,name FROM cobefec3.brands where deleted_at is null and enabled=1;");
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }

        return view('reporteNuevoSistema/cex/zonificacion', compact('fecha_inicio','fecha_fin','supervisores','marcas'));
    }

    public function rZonificacion(Request $request)
    {
        $campanas= implode(",", $request->id_campana);
        try{
            DB::connection('cobefec3')->statement("call cobefec_reportes.coordinador(".$request->id_marca.");");
        }catch (\Exception $exception){
            return $exception->getMessage();
        }

                try{
                    DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.zonificacion_cex;");
                }catch (\Exception $exception){
                    return $exception->getMessage();
                }

                try{
                    set_time_limit(0);
                    ini_set ( 'memory_limit' , '2048M' );
                    ini_set('max_execution_time', 800);
                    DB::connection('cobefec3')->statement("
                    create temporary table cobefec_reportes.zonificacion_cex
select b.name Marca, concat(u2.first_name, ' ', u2.last_name) Coordinador,
(select concat(u1.first_name, ' ', u1.last_name) from cobefec3.assignment_cex a2, cobefec3.executives e2, cobefec3.users u1 where a2.executive_id=e2.id and e2.user_id=u1.id and a2.account_id=a.id order by a2.assigned_date desc limit 1) Gestor_CEX,
a.target_document Cedula,
if(b.id=2,a.data ->> '$.nomsoc',if(b.id=3,a.data ->> '$.nombres',if(b.id=8,a.data ->> '$.nombre',if(b.id=10,a.data ->> '$.nombre','')))) Nombre,
c.name Campana,
if(b.id=2,a.data ->> '$.codpri',if(b.id=3,ifnull(a.stage,''),if(b.id=10,a.data ->> '$.castigo',''))) Producto,
if(b.id=2,a.data ->> '$.nombre_ciudad',if(b.id=3,ifnull(a.data ->> '$.provincia',''),if(b.id=8,a.data ->> '$.ciudad',if(b.id=10,a.data ->> '$.ciudad','')))) Ciudad,
z.name Ruta, if(b.id=8,a.data ->> '$.agencia',if(a.data ->> '$.zona'='#REF!','',ifnull(a.data ->> '$.zona',''))) Zona, if(b.id=8,a.data ->> '$.sucursal',ifnull(a.data ->> '$.region','')) Region, ifnull(a.data ->> '$.seccion','') Seccion,
if(b.id=2,a.data ->> '$.ciclof','') Ciclo,
if(b.id=2,a.data ->> '$.saldo_actual',if(b.id=3,a.recovered,if(b.id=8,a.data ->> '$.total_a_pagar',if(b.id=10,a.data ->> '$.saldo_actual',a.recovered)))) Valor_pendiente,
if(a.location_incomplete=0,'NO','SI') loc_direccion_incorrecta, z.name Cobertura, if(a.enabled=1,'HABILITADO','DESHABILITADO') Estado_cuenta,
(select concat(us.first_name, ' ', us.last_name) from cobefec3.agents ag, cobefec3.users us where ag.user_id=us.id and ag.id=a.current_agent) Agente_actual, a.data ->> '$.direccion.original_address' Direccion, a.data ->> '$.focalizacion' Focalizacion, a.data ->> '$.carta' Carta
from cobefec3.assignment_cex a1, cobefec3.executives e, cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p, cobefec3.brands b, cobefec3.coordinators co, cobefec3.users u2, cobefec3.zones z
where e.id=a1.executive_id and e.deleted_at is null and e.id<>1
and a.id=a1.account_id and c.id=a.campaign_id and p.id=c.product_id and b.id=p.brand_id and co.id=e.coordinator_id and u2.id=co.user_id
and c.enabled=1 and c.deleted_at is null and p.enabled=1 and p.deleted_at is null and b.enabled=1 and b.deleted_at is null and e.deleted_at is null
and a.zone_id is not null and a.zone_id<>0 and c.id in (".$campanas.") and z.id=a.zone_id
group by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,17,18,19,20,21
UNION ALL
select b.name Marca, 
ifnull((
select coordinador from cobefec_reportes.coordinadorxciudad where marca=b.id and ciudad=(if(b.id=2,a.data ->> '$.nombre_ciudad',if(b.id=3,ifnull(a.data ->> '$.provincia',''),if(b.id=8,a.data ->> '$.ciudad',if(b.id=10,a.data ->> '$.ciudad',if(b.id=5,a.data ->> '$.ciudad_2','')))))) limit 1
), ifnull((select coordinador from cobefec_reportes.coordinadorxzona where marca=b.id and zona=(if(b.id=8,a.data ->> '$.agencia',if(a.data ->> '$.zona'='#REF!','',ifnull(a.data ->> '$.zona','')))) limit 1),'')
) Coordinador, 
'POR ZONIFICAR' Gestor_CEX, a.target_document Cedula,
if(b.id=2,a.data ->> '$.nomsoc',if(b.id=3,a.data ->> '$.nombres',if(b.id=8,a.data ->> '$.nombre',if(b.id=10,a.data ->> '$.nombre','')))) Nombre, 
c.name Campana, if(b.id=2,a.data ->> '$.codpri',if(b.id=3,ifnull(a.stage,''),if(b.id=10,a.data ->> '$.castigo',''))) Producto,
if(b.id=2,a.data ->> '$.nombre_ciudad',if(b.id=3,ifnull(a.data ->> '$.provincia',''),if(b.id=8,a.data ->> '$.ciudad',if(b.id=10,a.data ->> '$.ciudad','')))) Ciudad,
'POR ZONIFICAR' Ruta, if(b.id=8,a.data ->> '$.agencia',if(a.data ->> '$.zona'='#REF!','',ifnull(a.data ->> '$.zona',''))) Zona, if(b.id=8,a.data ->> '$.sucursal',ifnull(a.data ->> '$.region','')) Region, ifnull(a.data ->> '$.seccion','') Seccion,
if(b.id=2,a.data ->> '$.ciclof','') Ciclo,
if(b.id=2,a.data ->> '$.saldo_actual',if(b.id=3,a.recovered,if(b.id=8,a.data ->> '$.total_a_pagar',if(b.id=10,a.data ->> '$.saldo_actual',a.recovered)))) Valor_pendiente,
if(a.location_incomplete=0,'NO','SI') loc_direccion_incorrecta, 'POR ZONIFICAR' Cobertura, if(a.enabled=1,'HABILITADO','DESHABILITADO') Estado_cuenta,
(select concat(us.first_name, ' ', us.last_name) from cobefec3.agents ag, cobefec3.users us where ag.user_id=us.id and ag.id=a.current_agent) Agente_actual, a.data ->> '$.direccion.original_address' Direccion, a.data ->> '$.focalizacion' Focalizacion, a.data ->> '$.carta'
from cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p, cobefec3.brands b 
where c.id=a.campaign_id and p.id=c.product_id and b.id=p.brand_id  
and c.enabled=1 and c.deleted_at is null and p.enabled=1 and p.deleted_at is null and b.enabled=1 and b.deleted_at is null
and a.zone_id is null and c.id in (".$campanas.")
UNION ALL
select b.name Marca, 'N/A' Coordinador, 'SIN COBERTURA' Gestor_CEX, a.target_document Cedula,
if(b.id=2,a.data ->> '$.nomsoc',if(b.id=3,a.data ->> '$.nombres',if(b.id=8,a.data ->> '$.nombre',if(b.id=10,a.data ->> '$.nombre','')))) Nombre, 
c.name Campana, if(b.id=2,a.data ->> '$.codpri',if(b.id=3,ifnull(a.stage,''),if(b.id=10,a.data ->> '$.castigo',''))) Producto,
if(b.id=2,a.data ->> '$.nombre_ciudad',if(b.id=3,ifnull(a.data ->> '$.provincia',''),if(b.id=8,a.data ->> '$.ciudad',if(b.id=10,a.data ->> '$.ciudad','')))) Ciudad,
'SIN COBERTURA' Ruta, if(b.id=8,a.data ->> '$.agencia',if(a.data ->> '$.zona'='#REF!','',ifnull(a.data ->> '$.zona',''))) Zona, if(b.id=8,a.data ->> '$.sucursal',ifnull(a.data ->> '$.region','')) Region, ifnull(a.data ->> '$.seccion','') Seccion,
if(b.id=2,a.data ->> '$.ciclof','') Ciclo,
if(b.id=2,a.data ->> '$.saldo_actual',if(b.id=3,a.recovered,if(b.id=8,a.data ->> '$.total_a_pagar',if(b.id=10,a.data ->> '$.saldo_actual',a.recovered)))) Valor_pendiente, 
if(a.location_incomplete=0,'NO','SI') loc_direccion_incorrecta, 'SIN COBERTURA' Cobertura, if(a.enabled=1,'HABILITADO','DESHABILITADO') Estado_cuenta,
(select concat(us.first_name, ' ', us.last_name) from cobefec3.agents ag, cobefec3.users us where ag.user_id=us.id and ag.id=a.current_agent) Agente_actual, a.data ->> '$.direccion.original_address' Direccion, a.data ->> '$.focalizacion' Focalizacion, a.data ->> '$.carta'
from cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p, cobefec3.brands b 
where c.id=a.campaign_id and p.id=c.product_id and b.id=p.brand_id  
and c.enabled=1 and c.deleted_at is null and p.enabled=1 and p.deleted_at is null and b.enabled=1 and b.deleted_at is null
and a.zone_id=0 and c.id in (".$campanas.")
UNION ALL
select b.name Marca,
(select concat(u.first_name, ' ', u.last_name) from cobefec3.executive_zone ez, cobefec3.executives e, cobefec3.coordinators co, cobefec3.users u
where ez.id=(select max(id) from cobefec3.executive_zone where brand_id=ez.brand_id and zone_id=ez.zone_id) and e.id=ez.executive_id and co.id=e.coordinator_id and u.id=co.user_id and ez.brand_id=b.id and ez.zone_id=a.zone_id) Coordinador,
(select concat(u.first_name, ' ', u.last_name) from cobefec3.executive_zone ez, cobefec3.executives e, cobefec3.users u
where ez.id=(select max(id) from cobefec3.executive_zone where brand_id=ez.brand_id and zone_id=ez.zone_id) and e.id=ez.executive_id and u.id=e.user_id and ez.brand_id=b.id and ez.zone_id=a.zone_id) Gestor_CEX,
a.target_document Cedula,
if(b.id=2,a.data ->> '$.nomsoc',if(b.id=3,a.data ->> '$.nombres',if(b.id=8,a.data ->> '$.nombre',if(b.id=10,a.data ->> '$.nombre','')))) Nombre,
c.name Campana, if(b.id=2,a.data ->> '$.codpri',if(b.id=3,ifnull(a.stage,''),if(b.id=10,a.data ->> '$.castigo',''))) Producto,
if(b.id=2,a.data ->> '$.nombre_ciudad',if(b.id=3,ifnull(a.data ->> '$.provincia',''),if(b.id=8,a.data ->> '$.ciudad',if(b.id=10,a.data ->> '$.ciudad','')))) Ciudad,
z.name Ruta, if(b.id=8,a.data ->> '$.agencia',if(a.data ->> '$.zona'='#REF!','',ifnull(a.data ->> '$.zona',''))) Zona, if(b.id=8,a.data ->> '$.sucursal',ifnull(a.data ->> '$.region','')) Region, ifnull(a.data ->> '$.seccion','') Seccion,
if(b.id=2,a.data ->> '$.ciclof','') Ciclo,
if(b.id=2,a.data ->> '$.saldo_actual',if(b.id=3,a.recovered,if(b.id=8,a.data ->> '$.total_a_pagar',if(b.id=10,a.data ->> '$.saldo_actual',a.recovered)))) Valor_pendiente,
if(a.location_incomplete=0,'NO','SI') loc_direccion_incorrecta, z.name Cobertura, if(a.enabled=1,'HABILITADO','DESHABILITADO') Estado_cuenta,
(select concat(us.first_name, ' ', us.last_name) from cobefec3.agents ag, cobefec3.users us where ag.user_id=us.id and ag.id=a.current_agent) Agente_actual, a.data ->> '$.direccion.original_address' Direccion, a.data ->> '$.focalizacion' Focalizacion, a.data ->> '$.carta'
from cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p, cobefec3.brands b, cobefec3.zones z
where c.id=a.campaign_id and p.id=c.product_id and b.id=p.brand_id
and c.enabled=1 and c.deleted_at is null and p.enabled=1 and p.deleted_at is null and b.enabled=1 and b.deleted_at is null
and a.zone_id is not null and a.zone_id<>0 and z.id=a.zone_id
and (select count(*) from cobefec3.assignment_cex where account_id=a.id)=0 and c.id in (".$campanas.")
order by 1,2,3,5
;
");
                }catch (\Exception $exception){
                    return $exception->getMessage();
                }

                $query="select z.*, 
if(z.loc_direccion_incorrecta='SI', if(z.Cobertura='SIN COBERTURA' or z.Cobertura='POR ZONIFICAR','DIRECCION INCORRECTA','ZONIFICADO'),
if(z.Cobertura='SIN COBERTURA','SIN COBERTURA',if(Cobertura='POR ZONIFICAR','POR ZONIFICAR','ZONIFICADO')) ) Validador
from cobefec_reportes.zonificacion_cex z
;
        ";
                try {
                    set_time_limit(0);
                    ini_set ( 'memory_limit' , '2048M' );
                    ini_set('max_execution_time', 800);
                    $sql =DB::connection('cobefec3')->select($query);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }
                $reportes = json_decode(json_encode($sql),true);

        try{
            \Excel::create('REPORTE ZONIFICACION '.date('d-m-Y His'), function($excel) use (&$reportes){
                $excel->sheet('ZONIFICACION', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                    $sheet->row(1, function ($row) {
                        $row->setBackground('#63b6fd');
                    });
                    $sheet->setBorder('A1:Q1', 'thin', "000");
                });
            })->export('xlsx');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function gTipos(Request $request)
    {
        $tipo=cex_tipo_observacion::find($request->id);
        return response()->json(['tipo' => $tipo]);
    }


    public function monitoreoCex()
    {
        $gestores=tbl_users::whereIn('id',tbl_executives::whereNull('deleted_at')->get(['user_id']))->where('enabled',1)->whereNull('deleted_at')->pluck("email","id")->all();
        $gestors=tbl_dispositivos::where('imei','<>','')->whereNotNull('imei')->get();

        return view('reporteNuevoSistema/cex/monitoreo/index', compact('gestores','gestors'));
    }

    public function monitoreoCexRes()
    {
        $fecha=date('Y-m-d');
        $dispositivos=tbl_dispositivos::where('imei','<>','')->whereNotNull('imei')->get(['id','imei','cedula','telefono','nombre'])->toArray();
        //$gestores=$api_cobefec->toArray();
        $configuracion=tbl_paradas_configuracion::where('estado',1)->first();
        $idispositivos=0;
        foreach ($dispositivos as $dispositivo){

            $imeis=tbl_api_cobefec::whereDate('created_at',$fecha)->where('asyncStatus',0)->where('referencia',0)->where('calculado',0)->where('imei',$dispositivo['imei'])->get(['id','bateria_porcentaje','latitud','longitud','secuencia','imei','created_at','update_time']);

            if(count($imeis)==0){
                $imei = tbl_api_cobefec::whereDate('created_at', $fecha)->where('asyncStatus', 0)->where('imei', $dispositivo['imei'])->orderBy('id', 'DESC')->first();
                if ($imei != null){
                $fecha1 = new \DateTime($imei->hora_inicio);//fecha inicial
                $fecha2 = new \DateTime();//fecha de cierre
                $intervalo = $fecha2->diff($fecha1);


                $imei->tiempo_parado = $intervalo->format('%H:%I:%S');
                $imei->save();
            }


            }else {
                $ultima_referencia = tbl_api_cobefec::whereDate('created_at', $fecha)->where('asyncStatus', 0)->where('calculado', 1)->where('referencia', 1)->where('ultima_referencia', 1)->where('imei', $dispositivo['imei'])->get(['id', 'ultima_referencia', 'latitud', 'longitud', 'update_time'])->first();

                if ($ultima_referencia == null) {
                    $ultima_referencia = tbl_api_cobefec::find($imeis[0]['id']);
                    $ultima_referencia->referencia = 1;
                    $ultima_referencia->ultima_referencia = 1;
                    $ultima_referencia->save();
                }
                if (count($imeis) > 0) {
                    foreach ($imeis as $imei) {
                        $distancia = distanceCalculation($ultima_referencia->latitud, $ultima_referencia->longitud, $imei['latitud'], $imei['longitud']);

                        $api_cbc = tbl_api_cobefec::find($imei['id']);
                        $api_cbc->distancia = $distancia;
                        $api_cbc->save();

                        $fecha1 = new \DateTime($ultima_referencia->update_time);//fecha inicial
                        $fecha2 = new \DateTime($imei['update_time']);//fecha de cierre
                        $intervalo = $fecha2->diff($fecha1);

                        if ($distancia <= $configuracion->distancia) {
                            $api = tbl_api_cobefec::find($imei['id']);
                            $api->calculado = 1;
                            $api->save();

                            if ($intervalo->format('%H%i') > $configuracion->tiempo_parada) {
                                //echo 'Lleva '.$created->diff($now)->i.' minutos parados. Distancia recorrida: '.$distancia.'m. Id de referencia: '.$ultima_referencia->id;
                                $dispositivos[$idispositivos]['minutos_parado'] = $intervalo->format('%i');
                                $dispositivos[$idispositivos]['alerta'] = 'alert alert-danger';

                                $paradas_recorrido = new tbl_paradas_recorrido();
                                $paradas_recorrido->id_api = $imei['id'];
                                $paradas_recorrido->id_dispositivo = $dispositivo['id'];
                                $paradas_recorrido->id_parada_configuracion = $configuracion->id;
                                $paradas_recorrido->distancia = $distancia;
                                $paradas_recorrido->hora = $imei['update_time'];
                                $paradas_recorrido->tiempo_detenido = $intervalo->format('%i');
                                $paradas_recorrido->hora_salida = '';
                                $paradas_recorrido->tiempo_recorrido = '';
                                $paradas_recorrido->kms = '';
                                $paradas_recorrido->ciudad = '';
                                $paradas_recorrido->direccion = '';
                                $paradas_recorrido->save();

                            } else {
                                $dispositivos[$idispositivos]['minutos_parado'] = $intervalo->format('%i');
                                $dispositivos[$idispositivos]['alerta'] = '';
                                //echo 'Distancia recorrida: '.$distancia.'m. Id de referencia: '.$ultima_referencia->id;
                            }
                            $api_cbc = tbl_api_cobefec::find($imei['id']);
                            $api_cbc->hora_fin = $fecha2;
                            $api_cbc->hora_inicio = $fecha1;
                            $api_cbc->tiempo_parado = $intervalo->format('%H:%I:%S');
                            $api_cbc->distancia = $distancia;
                            $api_cbc->save();
                            $idispositivos++;
                        } else {
                            $ultima_referencia->ultima_referencia = 0;
                            $ultima_referencia->save();

                            $api = tbl_api_cobefec::find($imei['id']);
                            $api->calculado = 1;
                            $api->referencia = 1;
                            $api->hora_fin = $fecha2;
                            $api->hora_inicio = $fecha1;
                            $api->tiempo_parado = $intervalo->format('%H:%I:%S');
                            $api->ultima_referencia = 1;
                            $api->save();
                            $ultima_referencia = tbl_api_cobefec::find($api->id);
                        }
                        /*$dispositivos[$idispositivos]['id_api']=$imei['id'];
                        $dispositivos[$idispositivos]['id_dispositivo']=$dispositivo['id'];
                        $dispositivos[$idispositivos]['id_parada_configuracion']=$configuracion->id;
                        $dispositivos[$idispositivos]['hora']='';
                        $dispositivos[$idispositivos]['tiempo_detenido']=$intervalo->format('%i');
                        $dispositivos[$idispositivos]['hora_salida']='';
                        $dispositivos[$idispositivos]['tiempo_recorrido']='';
                        $dispositivos[$idispositivos]['kms']='';
                        $dispositivos[$idispositivos]['ciudad']='';
                        $dispositivos[$idispositivos]['direccion']='';*/
                    }
                }
            }
        }


        $gestors=tbl_users::whereIn('id',tbl_executives::whereNull('deleted_at')->get(['user_id']))->where('enabled',1)->whereNull('deleted_at')->pluck("email","id")->all();
        $dispositivos=tbl_dispositivos::where('imei','<>','')->whereNotNull('imei')->get(['id','imei','cedula','telefono','nombre'])->toArray();
        $dispositivosfs=Array();
        $idsp=0;
        foreach ($dispositivos as $dispositivo){
            $api_cobefec=tbl_api_cobefec::where('imei',$dispositivo['imei'])->whereDate('created_at',$fecha)->where('asyncStatus',0)->orderBy('id','DESC')->first();
            if ($api_cobefec != null){

                $fecha1 = new \DateTime($api_cobefec->tiempo_parado);//fecha inicial
                $fecha2 = new \DateTime(date('Y:m:d ').$configuracion->time_out);//fecha cierre
                $intervalo = $fecha2->diff($fecha1);

                if ($fecha1>$fecha2){
                    $dispositivos[$idsp]['alerta']='danger';
                    $dispositivos[$idsp]['alerta_mensaje']='SUPERO EL TIEMPO DE PARADA';
                }else{
                    $dispositivos[$idsp]['alerta']='primary';
                    $dispositivos[$idsp]['alerta_mensaje']='';
                }


                $dispositivos[$idsp]['tiempo_parado']=$api_cobefec->tiempo_parado;
                $dispositivos[$idsp]['distancia']=$api_cobefec->distancia;
                $dispositivos[$idsp]['bateria_porcentaje']=$api_cobefec->bateria_porcentaje."%";

                $gpsStatus=tbl_auditoria_dispositivos::where('imei',$dispositivo['imei'])->whereDate('created_at',$fecha)->whereNotNull('status_gps')->orderBy('id','DESC')->first();
                $dispositivos[$idsp]['status_gps']="ACTIVO";
                if(isset($gpsStatus->status_gps)){
                    if($gpsStatus->status_gps==1){
                        $dispositivos[$idsp]['status_gps']="ACTIVO";
                    }elseif($gpsStatus->status_gps==0){
                        $dispositivos[$idsp]['status_gps']="INACTIVO";
                        $dispositivos[$idsp]['alerta_mensaje'].=' // SIN SEÑAL POSIBLEMENTE DESACTIVO EL GPS';
                    }
                }
                $appStatus=tbl_auditoria_dispositivos::where('imei',$dispositivo['imei'])->whereDate('created_at',$fecha)->whereNotNull('appStatus')->orderBy('id','DESC')->first();
                $dispositivos[$idsp]['appStatus']="ACTIVO";
                if(isset($appStatus->appStatus)){
                    if($appStatus->appStatus==1){
                        $dispositivos[$idsp]['appStatus']="ACTIVO";
                    }elseif($appStatus->appStatus==0){
                        $dispositivos[$idsp]['appStatus']="INACTIVO";
                        $dispositivos[$idsp]['alerta_mensaje'].=' // APAGO EL DISPOSITIVO';
                    }
                }
                $dispositivos[$idsp]['status_hora']="-";
            }else{

                $dispositivos[$idsp]['tiempo_parado']='';
                $dispositivos[$idsp]['distancia']='';
                $dispositivos[$idsp]['bateria_porcentaje']='';
                $dispositivos[$idsp]['alerta']='primary';
                $dispositivos[$idsp]['alerta_mensaje']='Sin Novedad';
                $dispositivos[$idsp]['appStatus']="INACTIVO";
                $dispositivos[$idsp]['status_gps']="INACTIVO";
                $dispositivos[$idsp]['status_hora']="-";

                array_push($dispositivosfs,$dispositivos[$idsp]);
                unset($dispositivos[$idsp]);

            }
            $idsp++;
        }
        foreach ($dispositivosfs as $dispositivosf){
            array_push($dispositivos,$dispositivosf);
        }

        //dd($dispositivos);

        return view('reporteNuevoSistema/cex/monitoreo/index2', compact('gestores','gestors','dispositivos','api_cobefec'));
    }

    public function monitoreoCexResPost(Request $request)
    {

        $fecha=$request->fecha;
        $dispositivos=tbl_dispositivos::where('imei','<>','')->whereNotNull('imei')->get(['id','imei','cedula','telefono','nombre'])->toArray();
        //$gestores=$api_cobefec->toArray();
        $configuracion=tbl_paradas_configuracion::where('estado',1)->first();
        $idispositivos=0;
        foreach ($dispositivos as $dispositivo){

            $imeis=tbl_api_cobefec::whereDate('created_at',$fecha)->where('asyncStatus',0)->where('referencia',0)->where('calculado',0)->where('imei',$dispositivo['imei'])->get(['id','bateria_porcentaje','latitud','longitud','secuencia','imei','created_at','update_time']);

            if(count($imeis)==0){
                $imei = tbl_api_cobefec::whereDate('created_at', $fecha)->where('asyncStatus', 0)->where('imei', $dispositivo['imei'])->orderBy('id', 'DESC')->first();
                if ($imei != null){
                    $fecha1 = new \DateTime($imei->hora_inicio);//fecha inicial
                    $fecha2 = new \DateTime();//fecha de cierre
                    $intervalo = $fecha2->diff($fecha1);


                    $imei->tiempo_parado = $intervalo->format('%H:%I:%S');
                    $imei->save();
                }


            }else {
                $ultima_referencia = tbl_api_cobefec::whereDate('created_at', $fecha)->where('asyncStatus', 0)->where('calculado', 1)->where('referencia', 1)->where('ultima_referencia', 1)->where('imei', $dispositivo['imei'])->get(['id', 'ultima_referencia', 'latitud', 'longitud', 'update_time'])->first();

                if ($ultima_referencia == null) {
                    $ultima_referencia = tbl_api_cobefec::find($imeis[0]['id']);
                    $ultima_referencia->referencia = 1;
                    $ultima_referencia->ultima_referencia = 1;
                    $ultima_referencia->save();
                }
                if (count($imeis) > 0) {
                    foreach ($imeis as $imei) {
                        $distancia = distanceCalculation($ultima_referencia->latitud, $ultima_referencia->longitud, $imei['latitud'], $imei['longitud']);

                        $api_cbc = tbl_api_cobefec::find($imei['id']);
                        $api_cbc->distancia = $distancia;
                        $api_cbc->save();

                        $fecha1 = new \DateTime($ultima_referencia->update_time);//fecha inicial
                        $fecha2 = new \DateTime($imei['update_time']);//fecha de cierre
                        $intervalo = $fecha2->diff($fecha1);

                        if ($distancia <= $configuracion->distancia) {
                            $api = tbl_api_cobefec::find($imei['id']);
                            $api->calculado = 1;
                            $api->save();

                            if ($intervalo->format('%H%i') > $configuracion->tiempo_parada) {
                                //echo 'Lleva '.$created->diff($now)->i.' minutos parados. Distancia recorrida: '.$distancia.'m. Id de referencia: '.$ultima_referencia->id;
                                $dispositivos[$idispositivos]['minutos_parado'] = $intervalo->format('%i');
                                $dispositivos[$idispositivos]['alerta'] = 'alert alert-danger';

                                $paradas_recorrido = new tbl_paradas_recorrido();
                                $paradas_recorrido->id_api = $imei['id'];
                                $paradas_recorrido->id_dispositivo = $dispositivo['id'];
                                $paradas_recorrido->id_parada_configuracion = $configuracion->id;
                                $paradas_recorrido->distancia = $distancia;
                                $paradas_recorrido->hora = $imei['update_time'];
                                $paradas_recorrido->tiempo_detenido = $intervalo->format('%i');
                                $paradas_recorrido->hora_salida = '';
                                $paradas_recorrido->tiempo_recorrido = '';
                                $paradas_recorrido->kms = '';
                                $paradas_recorrido->ciudad = '';
                                $paradas_recorrido->direccion = '';
                                $paradas_recorrido->save();

                            } else {
                                $dispositivos[$idispositivos]['minutos_parado'] = $intervalo->format('%i');
                                $dispositivos[$idispositivos]['alerta'] = '';
                                //echo 'Distancia recorrida: '.$distancia.'m. Id de referencia: '.$ultima_referencia->id;
                            }
                            $api_cbc = tbl_api_cobefec::find($imei['id']);
                            $api_cbc->hora_fin = $fecha2;
                            $api_cbc->hora_inicio = $fecha1;
                            $api_cbc->tiempo_parado = $intervalo->format('%H:%I:%S');
                            $api_cbc->distancia = $distancia;
                            $api_cbc->save();
                            $idispositivos++;
                        } else {
                            $ultima_referencia->ultima_referencia = 0;
                            $ultima_referencia->save();

                            $api = tbl_api_cobefec::find($imei['id']);
                            $api->calculado = 1;
                            $api->referencia = 1;
                            $api->hora_fin = $fecha2;
                            $api->hora_inicio = $fecha1;
                            $api->tiempo_parado = $intervalo->format('%H:%I:%S');
                            $api->ultima_referencia = 1;
                            $api->save();
                            $ultima_referencia = tbl_api_cobefec::find($api->id);
                        }
                        /*$dispositivos[$idispositivos]['id_api']=$imei['id'];
                        $dispositivos[$idispositivos]['id_dispositivo']=$dispositivo['id'];
                        $dispositivos[$idispositivos]['id_parada_configuracion']=$configuracion->id;
                        $dispositivos[$idispositivos]['hora']='';
                        $dispositivos[$idispositivos]['tiempo_detenido']=$intervalo->format('%i');
                        $dispositivos[$idispositivos]['hora_salida']='';
                        $dispositivos[$idispositivos]['tiempo_recorrido']='';
                        $dispositivos[$idispositivos]['kms']='';
                        $dispositivos[$idispositivos]['ciudad']='';
                        $dispositivos[$idispositivos]['direccion']='';*/
                    }
                }
            }
        }


        $gestors=tbl_users::whereIn('id',tbl_executives::whereNull('deleted_at')->get(['user_id']))->where('enabled',1)->whereNull('deleted_at')->pluck("email","id")->all();
        $dispositivos=tbl_dispositivos::where('imei','<>','')->whereNotNull('imei')->get(['id','imei','cedula','telefono','nombre'])->toArray();
        $dispositivosfs=Array();
        $idsp=0;
        foreach ($dispositivos as $dispositivo){
            $api_cobefec=tbl_api_cobefec::where('imei',$dispositivo['imei'])->whereDate('created_at',$fecha)->where('asyncStatus',0)->orderBy('id','DESC')->first();
            if ($api_cobefec != null){
                $fecha1 = new \DateTime($api_cobefec->tiempo_parado);//fecha inicial
                $fecha2 = new \DateTime(date('Y:m:d ').$configuracion->time_out);//fecha cierre
                $intervalo = $fecha2->diff($fecha1);

                if ($fecha1>$fecha2){
                    $dispositivos[$idsp]['alerta']='alert alert-danger';
                    $dispositivos[$idsp]['alerta_mensaje']='SUPERO EL TIEMPO DE PARADA';
                }else{
                    $dispositivos[$idsp]['alerta']='primary';
                    $dispositivos[$idsp]['alerta_mensaje']='';
                }


                $dispositivos[$idsp]['tiempo_parado']=$api_cobefec->tiempo_parado;
                $dispositivos[$idsp]['distancia']=$api_cobefec->distancia;
                $dispositivos[$idsp]['bateria_porcentaje']=$api_cobefec->bateria_porcentaje."%";

                $gpsStatus=tbl_auditoria_dispositivos::where('imei',$dispositivo['imei'])->whereDate('created_at',$fecha)->whereNotNull('status_gps')->orderBy('id','DESC')->first();
                $dispositivos[$idsp]['status_gps']="ACTIVO";
                if(isset($gpsStatus->status_gps)){
                    if($gpsStatus->status_gps==1){
                        $dispositivos[$idsp]['status_gps']="ACTIVO";
                    }elseif($gpsStatus->status_gps==0){
                        $dispositivos[$idsp]['status_gps']="INACTIVO";
                        $dispositivos[$idsp]['alerta_mensaje'].=' // SIN SEÑAL POSIBLEMENTE DESACTIVO EL GPS';
                    }
                }
                $appStatus=tbl_auditoria_dispositivos::where('imei',$dispositivo['imei'])->whereDate('created_at',$fecha)->whereNotNull('appStatus')->orderBy('id','DESC')->first();
                $dispositivos[$idsp]['appStatus']="ACTIVO";
                if(isset($appStatus->appStatus)){
                    if($appStatus->appStatus==1){
                        $dispositivos[$idsp]['appStatus']="ACTIVO";
                    }elseif($appStatus->appStatus==0){
                        $dispositivos[$idsp]['appStatus']="INACTIVO";
                        $dispositivos[$idsp]['alerta_mensaje'].=' // APAGO EL DISPOSITIVO';
                    }
                }
                $dispositivos[$idsp]['status_hora']="-";
            }else{

                $dispositivos[$idsp]['tiempo_parado']='';
                $dispositivos[$idsp]['distancia']='';
                $dispositivos[$idsp]['bateria_porcentaje']='';
                $dispositivos[$idsp]['alerta']='danger';
                $dispositivos[$idsp]['alerta_mensaje']='Sin Novedad';
                $dispositivos[$idsp]['appStatus']="INACTIVO";
                $dispositivos[$idsp]['status_gps']="INACTIVO";
                $dispositivos[$idsp]['status_hora']="-";

                array_push($dispositivosfs,$dispositivos[$idsp]);
                unset($dispositivos[$idsp]);

            }
            $idsp++;
        }
        foreach ($dispositivosfs as $dispositivosf){
            array_push($dispositivos,$dispositivosf);
        }

        //dd($dispositivos);

        return $dispositivos;
    }

    public function monitoreoCexMapa($cedula,$fecha)
    {
        //dd($fecha);
        $gestor=tbl_dispositivos::where('cedula',$cedula)->first();
        $coords=tbl_api_cobefec::where('imei',$gestor->imei)->whereDate('created_at',$fecha)->whereDate('update_time',$fecha)->where('latitud','<>','')->orderBy('secuencia')->get(['id','imei','secuencia','secid','latitud','longitud','tiempo_parado','distancia','bateria_porcentaje','update_time'])->toArray();
        $configuracion=tbl_paradas_configuracion::where('estado',1)->first();
        //dd($coords);


        $coordenadas=Array();
        foreach($coords as $coord){
            $fecha1 = new \DateTime($fecha.$configuracion->tiempo_tolerancia);//fecha inicial
            $fecha2 = new \DateTime($fecha.$coord['tiempo_parado']);//fecha de cierre

            //$intervalo = $fecha2->diff($fecha1);
            //echo $configuracion->tiempo_tolerancia.' - '.$coord['tiempo_parado'];

            //dd($fecha1);
            //if ($fecha2<$fecha1 && $coord['distancia']<$configuracion->distancia_tolerancia){
                array_push($coordenadas,$coord);
            //}
        }

        if(count($coordenadas)==0){
            return 'Sin Datos';
        }

        $user=tbl_users::where('username',$cedula)->first();
        $executive=tbl_executives::where('user_id',$user->id)->first();
        if(isset($executive))$rutas=tbl_routes::where('executive_id',$executive->id)->whereDate('date',$fecha)->first();

        $gestiones=Array();
        if (isset($rutas)){
            $rutas=json_decode($rutas->data);
            $ir=0;
            foreach ($rutas as $ruta){
                $ruta->latitude=str_replace(',','.',$ruta->latitude);
                $ruta->longitude=str_replace(',','.',$ruta->longitude);
                if($ruta->type=='DEMARCHE'){
                    //extraigo la observacion del json campo description
                    $texto = $ruta->description;
                    $palabra = "OBSERVACIÓN:";
                    $description= substr($texto, (strpos($texto, $palabra) + strlen($palabra)));
                    //busco en el sistema de gestión
                    $demarche=tbl_demarches::where('description',$description)->where('executive_id',$executive->id)->first();
                    if(isset($demarche)){
                        $rutas[$ir]->agente=$demarche->agent;
                        $rutas[$ir]->cedula_cuenta=$demarche->document;
                        $datos_cuenta=json_decode($demarche->cuenta->data);
                        if(isset($datos_cuenta->nombres)){
                            $rutas[$ir]->nombre_cuenta=$datos_cuenta->nombres;
                        }elseif(isset($datos_cuenta->nomsoc)){
                            $rutas[$ir]->nombre_cuenta = $datos_cuenta->nomsoc;
                        }else{
                            $rutas[$ir]->nombre_cuenta='REVISAR LA CONFIGURACION DEL NOMBRE DE LA CUENTA EN LA CAMPAÑA';
                        }
                        $campana_cuenta=$demarche->cuenta->campana;
                        $rutas[$ir]->campana=$campana_cuenta->name;
                        $producto_cuenta=$campana_cuenta->producto;
                        $rutas[$ir]->producto=$producto_cuenta->name;
                        $rutas[$ir]->accion=$demarche->action;
                        $rutas[$ir]->sub_accion=$demarche->sub_action;
                        $rutas[$ir]->description=$description;
                        array_push($gestiones,$ruta);
                    }
                }
                $ir++;
            }
        }

        return view('reporteNuevoSistema/cex/monitoreo/mapa', compact('coordenadas','gestor','gestiones'));
    }

    public function guardaDireccion(Request $request)
    {

        //dd($request->all());
        sleep(2);
        $gestor=tbl_api_cobefec::find($request->id);
        if ($gestor){
            $gestor->direccion=$request->direccion;
            $gestor->save();
            return $request->id;
        }else{
            return 'no se guardo la direccion';
        }


    }

    public function dashboardCexMapa($fecha,$imei)
    {

        $status_gps_On=tbl_auditoria_dispositivos::where('imei',$imei)->where('status_gps',1)->whereDate('created_at',$fecha)->count();
        $status_gps_Off=tbl_auditoria_dispositivos::where('imei',$imei)->where('status_gps',0)->whereDate('created_at',$fecha)->count();
        $appCloseStatus_On=tbl_auditoria_dispositivos::where('imei',$imei)->where('appStatus',1)->whereDate('created_at',$fecha)->count();
        $appCloseStatus_Off=tbl_auditoria_dispositivos::where('imei',$imei)->where('appStatus',0)->whereDate('created_at',$fecha)->count();
        $cambio_hora_On=tbl_auditoria_dispositivos::where('imei',$imei)->where('cambio_hora',1)->whereDate('created_at',$fecha)->count();
        $cambio_hora_Off=tbl_auditoria_dispositivos::where('imei',$imei)->where('cambio_hora',0)->whereDate('created_at',$fecha)->count();
        $zona_horaria_On=tbl_auditoria_dispositivos::where('imei',$imei)->where('zona_horaria',1)->whereDate('created_at',$fecha)->count();
        $zona_horaria_Off=tbl_auditoria_dispositivos::where('imei',$imei)->where('zona_horaria',0)->whereDate('created_at',$fecha)->count();

        //$point1 = array("lat" => "48.8666667", "long" => "2.3333333"); // París (Francia)
        //$point2 = array("lat" => "19.4341667", "long" => "-99.1386111"); // Ciudad de México (México)

        $paradas=tbl_api_cobefec::whereDate('created_at',$fecha)->where('referencia',1)->where('imei',$imei)->orderBy('secuencia')->get()->toArray();
        if(!isset($paradas)){
            $paradas=Array();
        }
        $paradasDirecciones=tbl_api_cobefec::whereDate('created_at',$fecha)->where('referencia',1)->where('imei',$imei)->where('direccion',null)->orderBy('secuencia')->get()->toArray();

        if(!isset($paradas)){
            $paradasDirecciones=Array();
        }
        $datosObjs=tbl_auditoria_dispositivos::whereDate('created_at',$fecha)->whereDate('dttimeupdate',$fecha)->where('imei',$imei)->get()->toArray();
        $datos=Array();
        if (isset($datosObjs)){
            foreach ($datosObjs as $datosObj){
                    array_push($datos,$datosObj);
            }
        }



        $apps=tbl_api_cobefec::whereDate('created_at',$fecha)->where('imei',$imei)->whereNotNull('extras')->orderBy('secuencia')->first(['extras']);
        if (isset($apps)){
            $apps=json_decode($apps['extras'],true);
        }


        return view('reporteNuevoSistema/cex/monitoreo/dashboard',compact('status_gps_On','status_gps_Off','appCloseStatus_Off','appCloseStatus_On','cambio_hora_Off','cambio_hora_On','zona_horaria_Off','zona_horaria_On','paradas','datos','apps','paradasDirecciones'));
    }

    public function  calculoDistanciaCex(Request $request)
    {
        $coordenadas=tbl_api_cobefec::where('imei',$request->imei)->whereDate('update_time',$request->update_time)->where('asyncStatus',0)->orderBy('id','DESC')->orderBy('secid','DESC')->limit(2)->get();

        $point1=Array();
        $point2=Array();
        $km = distanceCalculation($coordenadas[0]['latitud'], $coordenadas[0]['longitud'], $coordenadas[1]['latitud'], $coordenadas[1]['longitud']); // Calcular la distancia en kilómetros (por defecto)
        //$km = distanceCalculation($point1['lat'], $point1['long'], $point2['lat'], $point2['long']); // Calcular la distancia en kilómetros (por defecto)
        return $coordenadas;
    }

    public function rMonitoreo(Request $request)
    {
        $gestores=tbl_users::whereIn('id',tbl_executives::whereNull('deleted_at')->get(['user_id']))->where('enabled',1)->whereNull('deleted_at')->pluck("email","id")->all();

        $fecha_inicio=$request->fecha_inicio;
        $fecha=Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');

        $gestors=tbl_users::whereIn('id',tbl_executives::whereNull('deleted_at')->get(['user_id']))->where('enabled',1)->whereNull('deleted_at')->pluck("email","id")->all();
        $dispositivos=tbl_dispositivos::where('imei','<>','')->whereNotNull('imei')->get(['id','imei','cedula','telefono','nombre'])->toArray();
        $idsp=0;
        foreach ($dispositivos as $dispositivo) {
            $api_cobefec = tbl_api_cobefec::where('imei', $dispositivo['imei'])->whereDate('created_at', $fecha)->where('asyncStatus', 0)->orderBy('id', 'DESC')->first();

            $dispositivos[$idsp]['tiempo_parado'] = '';
            $dispositivos[$idsp]['distancia'] = '';
            $dispositivos[$idsp]['bateria_porcentaje'] = '';
            $dispositivos[$idsp]['alerta'] = '';
            $dispositivos[$idsp]['alerta_mensaje'] = '';
            $dispositivos[$idsp]['status_gps'] = '';
            $dispositivos[$idsp]['status_hora'] = '';

            $idsp++;
        }

        return view('reporteNuevoSistema/cex/monitoreo/index2', compact('gestores','gestors','dispositivos','api_cobefec','fecha_inicio','fecha'));
    }

    public function rParametrosCex()
    {
        $parametros=tbl_paradas_configuracion::where('estado',1)->first();
        return view('reporteNuevoSistema/cex/monitoreo/parametros', compact('parametros'));
    }

    public function procesarParametrosCex(Request $request)
    {
        $user=Auth::user();
        tbl_paradas_configuracion::where('estado',1)->update(['estado'=>0]);
        $parametros=new tbl_paradas_configuracion();
        $parametros->id_usuario=$user->id;
        $parametros->nombre_usuario=$user->name;
        $parametros->tiempo_parada=$request->tiempo_parada;
        $parametros->distancia=$request->distancia;
        $parametros->hora_inicio=$request->hora_inicio;
        $parametros->hora_fin=$request->hora_fin;
        $parametros->time_out=$request->time_out;
        $parametros->save();

        return redirect()->action('ReportesNuevoSistema\ReportesCexController@monitoreoCexRes');
        //return view('reporteNuevoSistema/cex/monitoreo/parametros', compact('parametros'));
    }
}
function cumplimientoQuery1($fecha_inicio,$fecha_fin){
    $query="call cobefec_reportes.cex_cumplimiento1('".$fecha_inicio."','".$fecha_fin."');";
    try{
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
    /*
    try{
        DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.cex_cumplimiento;");

    }catch (\Exception $exception){
        return $exception->getMessage();
    }

    try{
        DB::connection('cobefec3')->statement("create table cobefec_reportes.cex_cumplimiento
SELECT concat(u2.first_name,' ',u2.last_name) COORDINADOR, concat(u1.first_name,' ',u1.last_name) EJECUTIVO_CEX, 
sum((select count(*) from cobefec3.assignment_cex where executive_id=ass.executive_id and assigned_date=ass.assigned_date)) VISITAS_ASIGNADAS,
sum((select count(*) from cobefec3.assignment_cex where contact_type<>'NG'and executive_id=ass.executive_id and assigned_date=ass.assigned_date)) VISITAS_REALIZADAS,
sum((select count(*) from cobefec3.assignment_cex where contact_type='NG'and executive_id=ass.executive_id and assigned_date=ass.assigned_date)) VISITAS_NO_REALIZADAS,
sum((select count(*) from cobefec3.assignment_cex where contact_type='CD'and executive_id=ass.executive_id and assigned_date=ass.assigned_date)) CD,
sum((select count(*) from cobefec3.assignment_cex where contact_type='CI'and executive_id=ass.executive_id and assigned_date=ass.assigned_date)) CI, 
sum((select count(*) from cobefec3.assignment_cex where contact_type='NC'and executive_id=ass.executive_id and assigned_date=ass.assigned_date)) NC, ass.executive_id -- , ass.id
FROM cobefec3.assignment_cex ass, cobefec3.executives e, cobefec3.users u1, cobefec3.coordinators c, cobefec3.users u2
where e.id<>1 and e.id=ass.executive_id and e.deleted_at is null and u1.id=e.user_id and c.id=e.coordinator_id and c.deleted_at is null
and u2.id=c.user_id
and ass.assigned_date between '".$fecha_inicio."' and '".$fecha_fin."'
and ass.id=(select max(id) from cobefec3.assignment_cex where executive_id=ass.executive_id and assigned_date=ass.assigned_date)
group by 1,2,9
order by 1,2
;
");

    }catch (\Exception $exception){
        return $exception->getMessage();
    }

    $query="select COORDINADOR, EJECUTIVO_CEX, VISITAS_ASIGNADAS, VISITAS_REALIZADAS, VISITAS_NO_REALIZADAS, round((VISITAS_REALIZADAS/VISITAS_ASIGNADAS)*100,2) CUMPLIMIENTO, if(VISITAS_REALIZADAS=0,'0.00',round(((CD + CI)/VISITAS_REALIZADAS)*100,2)) EFECTIVIDAD
from cobefec_reportes.cex_cumplimiento
;
";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }*/
    $reportes = json_decode(json_encode($sql),true);
    return $reportes;
}
function cumplimientoQuery2($fecha_inicio,$fecha_fin,$marca){
    //SEGUNDO REPORTE
    $query="call cobefec_reportes.cex_cumplimiento2('".$fecha_inicio."','".$fecha_fin."')";
    try {
        DB::connection('cobefec3')->statement($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }

    if($marca!=''){$marca=" where MARCA='".$marca."' ";}
    $query="select MARCA, EJECUTIVO_CEX, VISITAS_ASIGNADAS, VISITAS_REALIZADAS, VISITAS_NO_REALIZADAS, round((VISITAS_REALIZADAS/VISITAS_ASIGNADAS)*100,2) CUMPLIMIENTO, CD CONTACTO_DIRECTO, CI CONTACTO_INDIRECTO, NC NO_CONTACTADO, if(VISITAS_REALIZADAS=0,'0.00',round(((CD + CI)/VISITAS_REALIZADAS)*100,2)) EFECTIVIDAD
from cobefec_reportes.cex_cumplimiento2
".$marca.";";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
    /*
    //El si el parametro marca es igual a vacío selecciona todas las marcas caso contrario se extrae de una marca en especifico
    if($marca!=''){$marca=" where MARCA='".$marca."' ";}
    try{
        DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.asignacion_cex;");

    }catch (\Exception $exception){
        return $exception->getMessage();
    }

    try{
        DB::connection('cobefec3')->statement("create table cobefec_reportes.asignacion_cex
select b.name marca, b.id idmarca, a1.executive_id, concat(u.first_name,' ',u.last_name) ejecutivo_cex, a1.zone_id, a1.assigned_date, a1.id asignacion_id, a1.account_id, a1.contact_type, a1.demarche_id -- , a1.* 
from cobefec3.assignment_cex a1, cobefec3.accounts a, cobefec3.campaigns c, cobefec3.products p, cobefec3.brands b, cobefec3.executives e, cobefec3.users u
where a.id=a1.account_id and a.enabled=1 and c.id=a.campaign_id and c.enabled=1 and c.deleted_at is null
and p.id=c.product_id and p.enabled=1 and p.deleted_at is null
and b.id=p.brand_id and b.enabled=1 and b.deleted_at is null
and e.id<>1 and e.id=a1.executive_id and e.deleted_at is null and u.id=e.user_id
-- and a1.executive_id=2 
and a1.assigned_date between '".$fecha_inicio."' and '".$fecha_fin."'
order by b.id, a1.zone_id, a1.id, a1.assigned_date;
");
    }catch (\Exception $exception){
        return $exception->getMessage();
    }

    try{
        DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.cex_cumplimiento2;");

    }catch (\Exception $exception){
        return $exception->getMessage();
    }

    try{
        DB::connection('cobefec3')->statement("create temporary table cobefec_reportes.cex_cumplimiento2
select a1.marca MARCA, a1.ejecutivo_cex EJECUTIVO_CEX,
sum((select count(*) from cobefec_reportes.asignacion_cex where executive_id=a1.executive_id and idmarca=a1.idmarca)) VISITAS_ASIGNADAS,
sum((select count(*) from cobefec_reportes.asignacion_cex where contact_type<>'NG'and executive_id=a1.executive_id and idmarca=a1.idmarca)) VISITAS_REALIZADAS,
sum((select count(*) from cobefec_reportes.asignacion_cex where contact_type='NG'and executive_id=a1.executive_id and idmarca=a1.idmarca)) VISITAS_NO_REALIZADAS,
sum((select count(*) from cobefec_reportes.asignacion_cex where contact_type='CD'and executive_id=a1.executive_id and idmarca=a1.idmarca)) CD,
sum((select count(*) from cobefec_reportes.asignacion_cex where contact_type='CI'and executive_id=a1.executive_id and idmarca=a1.idmarca)) CI, 
sum((select count(*) from cobefec_reportes.asignacion_cex where contact_type='NC'and executive_id=a1.executive_id and idmarca=a1.idmarca)) NC
from cobefec_reportes.asignacion_cex a1
where a1.asignacion_id=(select max(asignacion_id) from cobefec_reportes.asignacion_cex where executive_id=a1.executive_id and idmarca=a1.idmarca)
group by 1,2
;
");
    }catch (\Exception $exception){
        return $exception->getMessage();
    }

    $query="select MARCA, EJECUTIVO_CEX, VISITAS_ASIGNADAS, VISITAS_REALIZADAS, VISITAS_NO_REALIZADAS, round((VISITAS_REALIZADAS/VISITAS_ASIGNADAS)*100,2) CUMPLIMIENTO, CD CONTACTO_DIRECTO, CI CONTACTO_INDIRECTO, NC NO_CONTACTADO, if(VISITAS_REALIZADAS=0,'0.00',round(((CD + CI)/VISITAS_REALIZADAS)*100,2)) EFECTIVIDAD
from cobefec_reportes.cex_cumplimiento2
".$marca."
;
";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
*/
    $reportes = json_decode(json_encode($sql),true);

    return $reportes;
}
function cumplimientoQuery3($marca){
    //TERCER REPORTE
    $query="call cobefec_reportes.cex_cumplimiento3();";
    try {
        DB::connection('cobefec3')->statement($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }

    if($marca!=''){$marca=" where MARCA='".$marca."' ";}

    $query="select MARCA, VISITAS_ASIGNADAS, VISITAS_REALIZADAS, VISITAS_NO_REALIZADAS, round((VISITAS_REALIZADAS/VISITAS_ASIGNADAS)*100,2) CUMPLIMIENTO, CD CONTACTO_DIRECTO, CI CONTACTO_INDIRECTO, NC NO_CONTACTADO, if(VISITAS_REALIZADAS=0,'0.00',round(((CD + CI)/VISITAS_REALIZADAS)*100,2)) EFECTIVIDAD
from cobefec_reportes.cex_cumplimiento3
".$marca."
;
";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }

    /*
    //El si el parametro marca es igual a vacío selecciona todas las marcas caso contrario se extrae de una marca en especifico
    if($marca!=''){$marca=" where MARCA='".$marca."' ";}
    try{
        DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.cex_cumplimiento3;");

    }catch (\Exception $exception){
        return $exception->getMessage();
    }

    try{
        DB::connection('cobefec3')->statement("create temporary table cobefec_reportes.cex_cumplimiento3
select a1.marca MARCA,
sum((select count(*) from cobefec_reportes.asignacion_cex where idmarca=a1.idmarca)) VISITAS_ASIGNADAS,
sum((select count(*) from cobefec_reportes.asignacion_cex where contact_type<>'NG'and idmarca=a1.idmarca)) VISITAS_REALIZADAS,
sum((select count(*) from cobefec_reportes.asignacion_cex where contact_type='NG'and idmarca=a1.idmarca)) VISITAS_NO_REALIZADAS,
sum((select count(*) from cobefec_reportes.asignacion_cex where contact_type='CD'and idmarca=a1.idmarca)) CD,
sum((select count(*) from cobefec_reportes.asignacion_cex where contact_type='CI'and idmarca=a1.idmarca)) CI, 
sum((select count(*) from cobefec_reportes.asignacion_cex where contact_type='NC'and idmarca=a1.idmarca)) NC
from cobefec_reportes.asignacion_cex a1
where a1.asignacion_id=(select max(asignacion_id) from cobefec_reportes.asignacion_cex where idmarca=a1.idmarca)
group by 1
;
");
    }catch (\Exception $exception){
        return $exception->getMessage();
    }

    $query="select MARCA, VISITAS_ASIGNADAS, VISITAS_REALIZADAS, VISITAS_NO_REALIZADAS, round((VISITAS_REALIZADAS/VISITAS_ASIGNADAS)*100,2) CUMPLIMIENTO, CD CONTACTO_DIRECTO, CI CONTACTO_INDIRECTO, NC NO_CONTACTADO, if(VISITAS_REALIZADAS=0,'0.00',round(((CD + CI)/VISITAS_REALIZADAS)*100,2)) EFECTIVIDAD
from cobefec_reportes.cex_cumplimiento3
".$marca."
;
";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
    */
    $reportes = json_decode(json_encode($sql),true);
    return $reportes;
}
function cumplimientoQuery4(){
    //CUARTO REPORTE
    $query="select COORDINADOR, avg(round((VISITAS_REALIZADAS/VISITAS_ASIGNADAS)*100,2)) CUMPLIMIENTO, avg(if(VISITAS_REALIZADAS=0,'0.00',round(((CD + CI)/VISITAS_REALIZADAS)*100,2))) EFECTIVIDAD
from cobefec_reportes.cex_cumplimiento
group by 1
;
";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }

    $reportes = json_decode(json_encode($sql),true);
    return $reportes;
}
function cumplimientoQuery5(){
    //QUINTO REPORTE NOVEDADES
    $query="select EJECUTIVO_CEX, round((VISITAS_REALIZADAS/VISITAS_ASIGNADAS)*100,2) MOTIVO, COORDINADOR
from cobefec_reportes.cex_cumplimiento
UNION ALL
select concat(u.first_name,' ',u.last_name) ejecutivo_cex, 'S/ASIGNACION' MOTIVO, 
ifnull((select concat(u1.first_name,' ',u1.last_name) from cobefec3.coordinators c, cobefec3.users u1 where u1.id=c.user_id and u1.deleted_at is null and u1.enabled=1 and c.id=e.coordinator_id),'') COORDINADOR 
from cobefec3.executives e, cobefec3.users u
where e.deleted_at is null and e.id<>1 and u.id=e.user_id and u.deleted_at is null and u.enabled=1
and e.id not in
(select distinct executive_id from cobefec_reportes.cex_cumplimiento)
order by 3,1
;
";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }

    $reportes = json_decode(json_encode($sql),true);
    return $reportes;
}
function cumplimientoQuery6(){
    //SEXTO REPORTE
    try{
        DB::connection('cobefec3')->statement("DROP TABLE IF EXISTS cobefec_reportes.novedades_cex;");

    }catch (\Exception $exception){
        return $exception->getMessage();
    }

    try{
        DB::connection('cobefec3')->statement("create table cobefec_reportes.novedades_cex
select EJECUTIVO_CEX, round((VISITAS_REALIZADAS/VISITAS_ASIGNADAS)*100,2) MOTIVO, COORDINADOR
from cobefec_reportes.cex_cumplimiento
UNION ALL
select concat(u.first_name,' ',u.last_name) ejecutivo_cex, 'S/ASIGNACION' motivo, 
ifnull((select concat(u1.first_name,' ',u1.last_name) from cobefec3.coordinators c, cobefec3.users u1 where u1.id=c.user_id and u1.deleted_at is null and u1.enabled=1 and c.id=e.coordinator_id),'') Coordinador 
from cobefec3.executives e, cobefec3.users u
where e.deleted_at is null and e.id<>1 and u.id=e.user_id and u.deleted_at is null and u.enabled=1
and e.id not in
(select distinct executive_id from cobefec_reportes.cex_cumplimiento)
order by 3,1
;
");
    }catch (\Exception $exception){
        return $exception->getMessage();
    }

    $query="select 'ACTIVOS' Ejecutivos_cex, count(round((VISITAS_REALIZADAS/VISITAS_ASIGNADAS)*100,2)) Ejecutivos_cex
from cobefec_reportes.cex_cumplimiento
where round((VISITAS_REALIZADAS/VISITAS_ASIGNADAS)*100,2) >= 70
UNION ALL
select 'CAPACIDAD INSTALADA', count(*) Ejecutivos_cex from cobefec_reportes.novedades_cex
UNION ALL
select 'S/ASIGNACION', count(*) from cobefec_reportes.novedades_cex where MOTIVO='S/ASIGNACION'
UNION ALL
select 'CUMPLIMIENTO BAJO 70%', count(*) from cobefec_reportes.novedades_cex where MOTIVO < 70 and MOTIVO<>'S/ASIGNACION'
UNION ALL
select 'CUMPLIMIENTO 100%', count(*) from cobefec_reportes.novedades_cex where MOTIVO =100
;

";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
    $reportes = json_decode(json_encode($sql),true);
    return $reportes;
}
function cumplimientoQuery7($marca){
    //SEPTIMO REPORTE VISITAS POR ACCION
    switch ($marca) {
        case 'Belcorp':
            $marca = 3;
            break;
        case 'Diners':
            $marca = 2;
            break;
        case 'Cooperativa 29 de Octubre':
            $marca = 8;
            break;
        case 'Banco de Guayaquil':
            $marca = 5;
            break;
        case 'Diners Legal':
            $marca = 10;
            break;
    }

    $query="select d.action status, count(*) visitas, 
round((100*count(*)/(select count(*) from cobefec_reportes.asignacion_cex where demarche_id is not null and idmarca=a1.idmarca)),2) '%',
if(a1.contact_type='CD','CONTACTO DIRECTO',if(a1.contact_type='CI','CONTACTO INDIRECTO',if(a1.contact_type='NC','NO CONTACTADO',''))) tipo  
from cobefec_reportes.asignacion_cex a1, cobefec3.demarches d
where a1.demarche_id is not null and a1.idmarca=".$marca."
and d.id=a1.demarche_id
group by 1,4
order by 1,4
;

";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
    $reportes = json_decode(json_encode($sql),true);
    return $reportes;
}
function cumplimientoQuery8($marca){
    //OCTAVO REPORTE VISITAS POR TIPO
    switch ($marca) {
        case 'Belcorp':
            $marca = 3;
            break;
        case 'Diners':
            $marca = 2;
            break;
        case 'Cooperativa 29 de Octubre':
            $marca = 8;
            break;
        case 'Banco de Guayaquil':
            $marca = 5;
            break;
        case 'Diners Legal':
            $marca = 10;
            break;
    }

    $query="SELECT if(a1.contact_type='CD','CONTACTO DIRECTO',if(a1.contact_type='CI','CONTACTO INDIRECTO',if(a1.contact_type='NC','NO CONTACTADO',''))) tipo, count(*) visitas,
round((100*count(*)/(select count(*) from cobefec_reportes.asignacion_cex where demarche_id is not null and idmarca=a1.idmarca)),2) '%'
from cobefec_reportes.asignacion_cex a1
where a1.demarche_id is not null and a1.idmarca=".$marca."
group by 1 
order by 1;

";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
    $reportes = json_decode(json_encode($sql),true);
    return $reportes;
}
function cumplimientoQuery9($marca){
    //OCTAVO REPORTE VISITAS POR HORA
    switch ($marca) {
        case 'Belcorp':
            $marca = 3;
            break;
        case 'Diners':
            $marca = 2;
            break;
        case 'Cooperativa 29 de Octubre':
            $marca = 8;
            break;
        case 'Banco de Guayaquil':
            $marca = 5;
            break;
        case 'Diners Legal':
            $marca = 10;
            break;
    }

    $query="select a1.ejecutivo_cex, (select count(*) from cobefec_reportes.asignacion_cex where demarche_id is null and idmarca=a1.idmarca and executive_id=a1.executive_id) no_realizadas,
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)<9) as '<9',
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)=9) as '9',
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)=10) as '10',
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)=11) as '11',
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)=12) as '12',
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)=13) as '13',
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)=14) as '14',
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)=15) as '15',
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)=16) as '16',
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)=17) as '17',
(select count(*) from cobefec_reportes.asignacion_cex a2, cobefec3.demarches d2 where d2.id=a2.demarche_id and a2.demarche_id is not null and a2.idmarca=a1.idmarca and a2.executive_id=a1.executive_id and hour(d2.cex_time)>=18) as '>=18',
(select count(*) from cobefec_reportes.asignacion_cex where demarche_id is not null and idmarca=a1.idmarca and executive_id=a1.executive_id) total_visitas
from cobefec_reportes.asignacion_cex a1
where a1.demarche_id is not null and a1.idmarca=".$marca."
and a1.asignacion_id=(select max(asignacion_id) from cobefec_reportes.asignacion_cex where demarche_id is not null and idmarca=a1.idmarca and executive_id=a1.executive_id)
order by 1
;
";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
    $reportes = json_decode(json_encode($sql),true);
    return $reportes;
}
function cumplimientoQuery10($marca){
    //DECIMO REPORTE VISITAS REALIZADAS
    switch ($marca) {
        case 'Belcorp':
            $marca = 3;
            break;
        case 'Diners':
            $marca = 2;
            break;
        case 'Cooperativa 29 de Octubre':
            $marca = 8;
            break;
        case 'Banco de Guayaquil':
            $marca = 5;
            break;
        case 'Diners Legal':
            $marca = 10;
            break;
    }

    $query="select a1.ejecutivo_cex gestor, a.target_document codigo,
if(a1.idmarca=2,a.data ->> '$.nomsoc',if(a1.idmarca=3,a.data ->> '$.nombres',if(a1.idmarca=5 or a1.idmarca=8 or a1.idmarca=10,a.data ->> '$.nombre',''))) nombre, 
upper(d.action) resultado
from cobefec_reportes.asignacion_cex a1, cobefec3.accounts a, cobefec3.demarches d
where a1.demarche_id is not null and a1.idmarca=".$marca."
and a.id=a1.account_id and d.id=a1.demarche_id
order by 1,3
;
";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
    $reportes = json_decode(json_encode($sql),true);
    return $reportes;
}
function cumplimientoQuery11($marca){
    //DECIMO REPORTE VISITAS REALIZADAS
    switch ($marca) {
        case 'Belcorp':
            $marca = 3;
            break;
        case 'Diners':
            $marca = 2;
            break;
        case 'Cooperativa 29 de Octubre':
            $marca = 8;
            break;
        case 'Banco de Guayaquil':
            $marca = 5;
            break;
        case 'Diners Legal':
            $marca = 10;
            break;
    }

    $query="select a1.ejecutivo_cex gestor, a.target_document codigo,
if(a1.idmarca=2,a.data ->> '$.nomsoc',if(a1.idmarca=3,a.data ->> '$.nombres',if(a1.idmarca=5 or a1.idmarca=8 or a1.idmarca=10,a.data ->> '$.nombre',''))) nombre 
from cobefec_reportes.asignacion_cex a1, cobefec3.accounts a
where a1.demarche_id is null and a1.idmarca=".$marca."
and a.id=a1.account_id 
order by 1,3
;
";
    try {
        $sql=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
    $reportes = json_decode(json_encode($sql),true);
    return $reportes;
}

function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'm', $decimals = 2) {
    // Cálculo de la distancia en grados
    $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));

    // Conversión de la distancia en grados a la unidad escogida (kilómetros, millas o millas naúticas)
    switch($unit) {
        case 'km':
            $distance = $degrees * 111.13384; // 1 grado = 111.13384 km, basándose en el diametro promedio de la Tierra (12.735 km)
            break;
        case 'm':
            $distance = ($degrees * 111.13384)*1000; // 1 grado = 111.13384 m, basándose en el diametro promedio de la Tierra (12.735 km)
            break;
        case 'mi':
            $distance = $degrees * 69.05482; // 1 grado = 69.05482 millas, basándose en el diametro promedio de la Tierra (7.913,1 millas)
            break;
        case 'nmi':
            $distance =  $degrees * 59.97662; // 1 grado = 59.97662 millas naúticas, basándose en el diametro promedio de la Tierra (6,876.3 millas naúticas)
    }
    return round($distance, $decimals);
}


