<?php

namespace App\Http\Controllers\ReportesNuevoSistema;

use App\reportesNuevoSistema\cuentasX88\tbl_agentes;
use App\reportesNuevoSistema\cuentasX88\tbl_contactabilidad;
use App\reportesNuevoSistema\cuentasX88\tbl_cuentas;
use App\reportesNuevoSistema\cuentasX88\tbl_cuentas_historico;
use App\reportesNuevoSistema\cuentasX88\tbl_diners_x88;
use App\reportesNuevoSistema\cuentasX88\tbl_id_carga;
use App\reportesNuevoSistema\cuentasX88\tbl_motivo;
use App\reportesNuevoSistema\cuentasX88\tbl_observaciones;
use App\reportesNuevoSistema\cuentasX88\tbl_submotivo;
use App\reportesNuevoSistema\cuentasX88\tbl_sugerencia;
use App\reportesNuevoSistema\tbl_accounts;
use App\reportesNuevoSistema\tbl_campaigns;
use App\reportesNuevoSistema\tbl_demarches;
use App\tbl_gestiones as gestiones;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PHPExcel_IOFactory;
use Illuminate\Support\Facades\Input;
use PhpParser\Node\Expr\Cast\Array_;
use App\User;

class ReportesCx88Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $id_carga=tbl_id_carga::where('estado',1)->first()->id;
        try{
            $cuentasDiners = tbl_cuentas::where('marca','DINERS')->where('estado_gestionado','0')->where('correo_agente',$user->email)->get();
            $totalDiners=count($cuentasDiners);
            $totalGestionadosDiners=tbl_cuentas::where('marca','DINERS')->where('estado_aprobado','0')->where('estado_gestionado','0')->where('correo_agente',$user->email)->count();
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasVisa = tbl_cuentas::where('marca','VISA')->where('estado_gestionado','0')->where('correo_agente',$user->email)->get();
            $totalVisa=count($cuentasVisa);
            $totalGestionadosVisa= tbl_cuentas::where('marca','VISA')->where('estado_aprobado','0')->where('estado_gestionado','0')->where('correo_agente',$user->email)->count();
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasDiscover = tbl_cuentas::where('marca','DISCOVER')->where('estado_gestionado','0')->where('correo_agente',$user->email)->get();
            $totalDiscover=count($cuentasDiscover);
            $totalGestionadosDiscover= tbl_cuentas::where('marca','DISCOVER')->where('estado_aprobado','0')->where('estado_gestionado','0')->where('correo_agente',$user->email)->count();
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }

        return view('reporteNuevoSistema.diners.cx88.index' , compact('cuentasDiners','cuentasVisa','cuentasDiscover','totalDiners','totalVisa','totalDiscover','totalGestionadosDiners','totalGestionadosVisa','totalGestionadosDiscover'));
    }

    public function gestionadas()
    {
        $user = Auth::user();
        $id_carga=tbl_id_carga::where('estado',1)->first()->id;
        try{
            $cuentasDiners = tbl_cuentas::where('marca','DINERS')->where('estado_gestionado','1')->where('estado_aprobado','0')->where('correo_agente',$user->email)->get();
            $totalDiners=count($cuentasDiners);
            $totalPendientesDiners=tbl_cuentas::where('marca','DINERS')->where('estado_aprobado','0')->where('estado_gestionado','1')->where('correo_agente',$user->email)->count();
            $totalAprobadasDiners=tbl_cuentas::where('marca','DINERS')->where('estado_aprobado','1')->where('estado_gestionado','1')->where('correo_agente',$user->email)->count();

        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasVisa = tbl_cuentas::where('marca','VISA')->where('estado_gestionado','1')->where('estado_aprobado','0')->where('correo_agente',$user->email)->get();
            $totalVisa=count($cuentasVisa);
            $totalPendientesVisa= tbl_cuentas::where('marca','VISA')->where('estado_aprobado','0')->where('estado_gestionado','1')->where('correo_agente',$user->email)->count();
            $totalAprobadasVisa= tbl_cuentas::where('marca','VISA')->where('estado_aprobado','1')->where('estado_gestionado','1')->where('correo_agente',$user->email)->count();

        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasDiscover = tbl_cuentas::where('marca','DISCOVER')->where('estado_gestionado','1')->where('estado_aprobado','0')->where('correo_agente',$user->email)->get();
            $totalDiscover=count($cuentasDiscover);
            $totalPendientesDiscover= tbl_cuentas::where('marca','DISCOVER')->where('estado_aprobado','0')->where('estado_gestionado','1')->where('correo_agente',$user->email)->count();
            $totalAprobadasDiscover= tbl_cuentas::where('marca','DISCOVER')->where('estado_aprobado','1')->where('estado_gestionado','1')->where('correo_agente',$user->email)->count();

        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        return view('reporteNuevoSistema.diners.cx88.gestionadas' , compact('cuentasDiners','cuentasVisa','cuentasDiscover','totalDiners','totalVisa','totalDiscover','totalPendientesDiners','totalPendientesVisa','totalPendientesDiscover','totalAprobadasDiners','totalAprobadasVisa','totalAprobadasDiscover'));
    }

    public function indexSupervisor()
    {
        $user = Auth::user();
        $id_carga=tbl_id_carga::where('estado',1)->first()->id;
        try{
            $cuentasDiners = tbl_cuentas::where('marca','DINERS')->where('estado_gestionado','0')->get();
            $totalDiners=count($cuentasDiners);
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasVisa = tbl_cuentas::where('marca','VISA')->where('estado_gestionado','0')->get();
            $totalVisa=count($cuentasVisa);
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasDiscover = tbl_cuentas::where('marca','DISCOVER')->where('estado_gestionado','0')->get();
            $totalDiscover=count($cuentasDiscover);
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        $id_carga=tbl_id_carga::where('estado',1)->first();
        $campanas=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->get();
        $campana=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->first();
        $ciclos=tbl_cuentas::groupBy('ciclo','id_campana')->get();

        return view('reporteNuevoSistema.diners.cx88.indexSupervisor' , compact('cuentasDiners','cuentasVisa','cuentasDiscover','totalDiners','totalVisa','totalDiscover','id_carga','campanas','campana','ciclos'));
    }

    public function indexSupervisorP(Request $request)
    {
        $user = Auth::user();
        $id_carga=tbl_id_carga::where('estado',1)->first()->id;
        try{
            $cuentasDiners = tbl_cuentas::where('marca','DINERS')->where('estado_gestionado','0')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->get();
            $totalDiners=count($cuentasDiners);
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasVisa = tbl_cuentas::where('marca','VISA')->where('estado_gestionado','0')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->get();
            $totalVisa=count($cuentasVisa);
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasDiscover = tbl_cuentas::where('marca','DISCOVER')->where('estado_gestionado','0')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->get();
            $totalDiscover=count($cuentasDiscover);
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        $id_carga=tbl_id_carga::where('estado',1)->first();
        $campanas=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->get();
        $campana=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->first();
        $ciclos=tbl_cuentas::groupBy('ciclo','id_campana')->get();

        return view('reporteNuevoSistema.diners.cx88.indexSupervisor' , compact('cuentasDiners','cuentasVisa','cuentasDiscover','totalDiners','totalVisa','totalDiscover','id_carga','campanas','campana','ciclos'));
    }

    public function gestionadasSupervisor()
    {
        $user = Auth::user();
        $id_carga=tbl_id_carga::where('estado',1)->first()->id;
        try{
            $cuentasDiners = tbl_cuentas::where('marca','DINERS')->where('estado_gestionado','1')->get();
            $totalDiners=count($cuentasDiners);


            $totalPendientesDiners=tbl_cuentas::where('marca','DINERS')->where('estado_aprobado','0')->where('estado_gestionado','1')->count();
            $totalAprobadasDiners=tbl_cuentas::where('marca','DINERS')->where('estado_aprobado','1')->where('estado_gestionado','1')->count();
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasVisa = tbl_cuentas::where('marca','VISA')->where('estado_gestionado','1')->get();
            $totalVisa=count($cuentasVisa);

            $totalPendientesVisa= tbl_cuentas::where('marca','VISA')->where('estado_aprobado','0')->where('estado_gestionado','1')->count();
            $totalAprobadasVisa= tbl_cuentas::where('marca','VISA')->where('estado_aprobado','1')->where('estado_gestionado','1')->count();
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasDiscover = tbl_cuentas::where('marca','DISCOVER')->where('estado_gestionado','1')->get();
            $totalDiscover=count($cuentasDiscover);
            $totalPendientesDiscover= tbl_cuentas::where('marca','DISCOVER')->where('estado_aprobado','0')->where('estado_gestionado','1')->count();
            $totalAprobadasDiscover= tbl_cuentas::where('marca','DISCOVER')->where('estado_aprobado','1')->where('estado_gestionado','1')->count();
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        $id_carga=tbl_id_carga::where('estado',1)->first();
        $campanas=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->get();
        $campana=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->first();
        $ciclos=tbl_cuentas::groupBy('ciclo','id_campana')->get();
        return view('reporteNuevoSistema.diners.cx88.gestionadasSupervisor' , compact('cuentasDiners','cuentasVisa','cuentasDiscover','totalDiners','totalVisa','totalDiscover','totalPendientesDiners','totalPendientesVisa','totalPendientesDiscover','totalAprobadasDiners','totalAprobadasVisa','totalAprobadasDiscover','id_carga','campanas','campana','ciclos'));
    }

    public function gestionadasSupervisorP(Request $request)
    {
        $user = Auth::user();
        try{
            $cuentasDiners = tbl_cuentas::where('marca','DINERS')->where('estado_gestionado','1')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->get();
            $totalDiners=count($cuentasDiners);


            $totalPendientesDiners=tbl_cuentas::where('marca','DINERS')->where('estado_aprobado','0')->where('estado_gestionado','1')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->count();
            $totalAprobadasDiners=tbl_cuentas::where('marca','DINERS')->where('estado_aprobado','1')->where('estado_gestionado','1')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->count();
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasVisa = tbl_cuentas::where('marca','VISA')->where('estado_gestionado','1')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->get();
            $totalVisa=count($cuentasVisa);

            $totalPendientesVisa= tbl_cuentas::where('marca','VISA')->where('estado_aprobado','0')->where('estado_gestionado','1')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->count();
            $totalAprobadasVisa= tbl_cuentas::where('marca','VISA')->where('estado_aprobado','1')->where('estado_gestionado','1')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->count();
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        try{
            $cuentasDiscover = tbl_cuentas::where('marca','DISCOVER')->where('estado_gestionado','1')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->get();
            $totalDiscover=count($cuentasDiscover);
            $totalPendientesDiscover= tbl_cuentas::where('marca','DISCOVER')->where('estado_aprobado','0')->where('estado_gestionado','1')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->count();
            $totalAprobadasDiscover= tbl_cuentas::where('marca','DISCOVER')->where('estado_aprobado','1')->where('estado_gestionado','1')->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->count();
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }
        $campanas=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->get();
        $campana=tbl_id_carga::where('id_campana',$request->id_campana)->first();
        $ciclos=tbl_cuentas::groupBy('ciclo','id_campana')->get();
        $cicloEnv=$request->ciclo;
        return view('reporteNuevoSistema.diners.cx88.gestionadasSupervisor' , compact('cuentasDiners','cuentasVisa','cuentasDiscover','totalDiners','totalVisa','totalDiscover','totalPendientesDiners','totalPendientesVisa','totalPendientesDiscover','totalAprobadasDiners','totalAprobadasVisa','totalAprobadasDiscover','campanas','campana','ciclos','cicloEnv'));
    }

    public function indexSupervisorCarga()
    {
        $user = Auth::user();
        $campanas= tbl_campaigns::where('product_id',1)->where('enabled',1)->orderBy('id','DESC')->get();
        return view('reporteNuevoSistema.diners.cx88.cargaX88Supervisor', compact('campanas'));
    }

    public function indexSupervisorReasignar()
    {
        $user = Auth::user();
        $campanas=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->get();
        $ciclos=tbl_cuentas::where('id_campana',$campanas[0]->id_campana)->groupBy('ciclo','id_campana')->get();

        $asesores=tbl_cuentas::where('correo_agente','!=','sinAgente')->groupBy('correo_agente')->where('estado_cuenta',1)->where('id_campana',$campanas[0]->id_campana)->get();

        $asesores2=agregarListaAsesores($campanas[0]->id_campana);

        $cuentas=tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->where('id_campana',$campanas[0]->id_campana)->get();
        $agentes=User::where('role_id',2)->get();
        return view('reporteNuevoSistema.diners.cx88.reasignarSupervisor', compact('asesores','cuentas','campanas','ciclos','agentes','asesores2'));
    }

    public function indexSupervisorReasignarP(Request $request)
    {

        $campanas=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->get();
        $ciclos=tbl_cuentas::where('id_campana',$request->id_campana)->groupBy('ciclo','id_campana')->get();

        $asesores=tbl_cuentas::where('correo_agente','!=','sinAgente')->groupBy('correo_agente')->where('estado_cuenta',1)->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->get();
        $asesores2=agregarListaAsesores($request->id_campana);

        $cuentas=tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->get();
        $campana=tbl_id_carga::where('id_campana',$request->id_campana)->first();
        $cicloEnv=$request->ciclo;
        $agentes=User::where('role_id',2)->get();
        return view('reporteNuevoSistema.diners.cx88.reasignarSupervisor', compact('asesores','cuentas','campanas','ciclos','cicloEnv','agentes','campana','asesores2'));
    }

    public function cuentasX88ReasignarN(Request $request)
    {
        $asesor=tbl_cuentas::where('id_campana',$request->id_campana)->where('correo_agente',$request->asesor)->first();


        if (count($asesor)==0){
            $asesor=tbl_agentes::where('correo_agente',$request->asesor)->where('id_campana',$request->id_campana)->first();
        }

        if ($request->ciclo!='')
        {
            $cuentasAsignadas=tbl_cuentas::where('correo_agente',$asesor->correo_agente)->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->where('ciclo',$request->ciclo)->get();
            $cuentas=tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->where('ciclo',$request->ciclo)->get();
        }else{
            $cuentasAsignadas=tbl_cuentas::where('correo_agente',$asesor->correo_agente)->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->get();
            $cuentas=tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->get();
        }

        $cicloEnv=$request->ciclo;

        return view('reporteNuevoSistema.diners.cx88.reasignarSupervisorN', compact('asesor','cuentasAsignadas','cuentas','cicloEnv'));
    }

    public function cuentasX88ReasignarN2(Request $request)
    {
        $usuario=User::where('email',$request->asesor)->first();
        $cuenta=tbl_cuentas::find($request->cuenta);
        if (isset($cuenta)){
            $cuenta->correo_agente=$usuario->email;
            $cuenta->agente_actual=$usuario->name;
            $cuenta->save();
        }

        $cicloEnv=$request->ciclo;

        $asesor=tbl_cuentas::where('id_campana',$request->id_campana)->where('correo_agente',$request->asesor)->where('estado_cuenta',1)->first();
        $cuentasAsignadas=tbl_cuentas::where('correo_agente',$request->asesor)->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->get();
        $cuentas=tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->get();
        return view('reporteNuevoSistema.diners.cx88.reasignarSupervisorN', compact('asesor','cuentasAsignadas','cuentas','cicloEnv'));
    }

    public function cuentasX88EliminarTodas(Request $request)
    {
        tbl_cuentas::where('correo_agente',$request->asesor)->where('estado_aprobado',0)->where('estado_gestionado',0)->update(['agente_actual'=>null,'cedula_agente'=>null,'correo_agente'=>'sinAgente']);

        $campanas=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->get();
        $ciclos=tbl_cuentas::where('id_campana',$campanas[0]->id_campana)->groupBy('ciclo','id_campana')->get();
        $asesores=tbl_cuentas::where('correo_agente','!=','sinAgente')->groupBy('correo_agente')->where('estado_cuenta',1)->where('id_campana',$campanas[0]->id_campana)->get();
        $asesores2=agregarListaAsesores($request->id_campana);
        $cuentas=tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->where('id_campana',$campanas[0]->id_campana)->get();
        $agentes=User::where('role_id',2)->get();

        return view('reporteNuevoSistema.diners.cx88.reasignarSupervisor', compact('asesores','cuentas','campanas','ciclos','cicloEnv','agentes','asesores2'));
    }

    public function eliminarAsignacionCx88(Request $request)
    {
        $cuenta=tbl_cuentas::where('id',$request->cuenta)->where('estado_gestionado',0)->first();
        if($cuenta != null){
            $cuenta->agente_actual=null;
            $cuenta->cedula_agente=null;
            $cuenta->correo_agente='sinAgente';
            $cuenta->save();
        }else{
            return 'ESTA CUENTA YA ESTA GESTIONADA Y NO SE PUEDE ELMINAR DE LA ASIGNACION';
        }

        $asesor=tbl_cuentas::where('id_campana',$request->id_campana)->where('correo_agente',$request->asesor)->where('estado_cuenta',1)->first();
        if(count($asesor)==0){
            $asesor=tbl_agentes::where('correo_agente',$request->asesor)->first();
        }
        $cuentasAsignadas=tbl_cuentas::where('correo_agente',$request->asesor)->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->get();
        $cuentas=tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->get();
        $cicloEnv=$request->ciclo;

        return view('reporteNuevoSistema.diners.cx88.reasignarSupervisorN', compact('asesor','cuentasAsignadas','cuentas','cicloEnv'));
        //return redirect()->back();
    }

    public function cuentasX88ReasignarTodos(Request $request)
    {
        $asesor=tbl_cuentas::where('id_campana',$request->id_campana)->where('correo_agente',$request->asesor)->where('estado_cuenta',1)->first();
        if(count($asesor)==0){
            $asesor=tbl_agentes::where('correo_agente',$request->asesor)->first();
        }
        tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->update(['agente_actual'=>$asesor->agente_actual,'cedula_agente'=>$asesor->cedula_agente,'correo_agente'=>$asesor->correo_agente]);
        $cuentasAsignadas=tbl_cuentas::where('correo_agente',$request->asesor)->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->get();
        $cuentas=tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->get();
        $cicloEnv=$request->ciclo;
        return view('reporteNuevoSistema.diners.cx88.reasignarSupervisorN', compact('asesor','cuentasAsignadas','cuentas','cicloEnv'));
    }

    public function agregarAgenteX88(Request $request)
    {

        $agente=User::find($request->id_agente);
        $asesor=tbl_cuentas::where('correo_agente',$agente->email)->where('id_campana',$request->id_campana)->first();

        if (count($asesor)==0){
            $asesor=tbl_agentes::where('correo_agente',$agente->email)->where('id_campana',$request->id_campana)->first();
            if (count($asesor)==0){
                $asesor=new tbl_agentes();
                $asesor->id_campana=$request->id_campana;
                $asesor->agente_actual=$agente->name;
                $asesor->correo_agente=$agente->email;
                if (isset($request->ciclo))$asesor->ciclo=$agente->ciclo;
                $asesor->save();
            }
        }
        $asesores2=agregarListaAsesores($request->id_campana);
        $campanas=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->get();
        $ciclos=tbl_cuentas::where('id_campana',$campanas[0]->id_campana)->groupBy('ciclo','id_campana')->get();
        $asesores=tbl_cuentas::where('correo_agente','!=','sinAgente')->groupBy('correo_agente')->where('estado_cuenta',1)->where('id_campana',$campanas[0]->id_campana)->get();

        $cuentas=tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->where('id_campana',$campanas[0]->id_campana)->get();
        $agentes=User::where('role_id',2)->get();
        return view('reporteNuevoSistema.diners.cx88.reasignarSupervisor', compact('asesores','cuentas','campanas','ciclos','agentes','asesores2'));
    }

    public function eliminarAgenteX88(Request $request)
    {
        if (isset($ciclo))tbl_cuentas::where('correo_agente',$request->asesor)->where('id_campana',$request->id_campana)->where('ciclo',$request->ciclo)->where('estado_aprobado',0)->where('estado_gestionado',0)->update(['agente_actual'=>null,'cedula_agente'=>null,'correo_agente'=>'sinAgente']);

        if (!isset($ciclo))tbl_cuentas::where('correo_agente',$request->asesor)->where('id_campana',$request->id_campana)->where('estado_aprobado',0)->where('estado_gestionado',0)->update(['agente_actual'=>null,'cedula_agente'=>null,'correo_agente'=>'sinAgente']);

        $campanas=tbl_id_carga::groupBy('id_campana')->orderBy('id_campana','DESC')->get();
        $ciclos=tbl_cuentas::where('id_campana',$request->id_campana)->groupBy('ciclo','id_campana')->get();

        $asesores=tbl_cuentas::where('correo_agente','!=','sinAgente')->groupBy('correo_agente')->where('estado_cuenta',1)->where('id_campana',$request->id_campana)->get();

        tbl_agentes::where('correo_agente', $request->asesor)->where('id_campana', $request->id_campana)->delete();
        $asesores2=agregarListaAsesores($request->id_campana);

        $cuentas=tbl_cuentas::where('correo_agente','sinAgente')->where('estado_aprobado',0)->where('estado_gestionado',0)->where('estado_cuenta',1)->where('id_campana',$request->id_campana)->get();
        $agentes=User::where('role_id',2)->get();
        return view('reporteNuevoSistema.diners.cx88.reasignarSupervisor', compact('asesores','cuentas','campanas','ciclos','agentes','asesores2'));
    }

    public function getMotivo(Request $request)
    {
        try{
            $submotivos=tbl_submotivo::where('id_motivo',$request->id)->where('estado',1)->pluck("nombre","id")->all();
            $data = view('reporteNuevoSistema/diners/cx88/ajax-select-submotivos',compact('submotivos'))->render();

            //dd($data);
            return response()->json(['options'=>$data]);
        }catch (\Exception $e) {
            return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
        }
    }

    public function importExcelCx88(Request $request)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 5600);

        $file=$request->file('import_file');
        $path = $request->file('import_file')->getRealPath();
        $objReader=PHPExcel_IOFactory::createReaderForFile($path);
        $worksheetData=$objReader->listWorksheetInfo($path);
        echo '<h3>Worksheet Information</h3>';
        echo '<ol>';
        foreach ($worksheetData as $worksheet) {
            echo '<li>', $worksheet['worksheetName'], '<br />';
            echo 'Rows: ', $worksheet['totalRows'],
            ' Columns: ', $worksheet['totalColumns'], '<br />';
            echo 'Cell Range: A1: ',
            $worksheet['lastColumnLetter'], $worksheet['totalRows'];
            echo '</li>';
        }
        echo '</ol>';
        $dir = public_path() . "/storage/csvx88/";

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        //echo $dir;
        //$archivo=Input::file('import_file')->getClientOriginalName();
        $archivo="carga88.xlsx";
        try{
            Input::file('import_file')->move($dir, $archivo);
        }catch (Exception $e) {
            return $e->getMessage();

        }
        $csv = $dir.$archivo;
        //echo $csv;
        return $csv;
    }

    public function cargaDatosExcel(Request $request)
    {
        $total=tbl_cuentas::where('estado_gestionado',0)->where('estado_aprobado',0)->count();
        //if ($total==0){
            //tbl_id_carga::where('estado', 1)->update(array('estado' => 0));
            $user = Auth::user();
            $dir = public_path() . "/storage/csvx88/carga88.xlsx";

            Excel::load($dir, function($reader) use($request) {
                $excel = $reader->get();
                //iteracción
                tbl_diners_x88::truncate();
                $marca=$excel->getTitle();
                foreach ($excel as $row) {
                    $cuenta=new tbl_diners_x88();
                    $cuenta->id_campana=$request->campana;
                    $cuenta->marca=$marca;
                    $cuenta->cuenta=$row->cuenta;
                    $cuenta->nombre=$row->nombre;

                    if (strlen($row->cedula)==9){
                        $cuenta->cedula='0'.$row->cedula;
                    }else{
                        $cuenta->cedula=$row->cedula;
                    }

                    $cuenta->mes_ing=$row->mes_ing;
                    $cuenta->ano_ing=$row->ano_ing;
                    $cuenta->edad_total=$row->edad_total;
                    $cuenta->edad_recns=$row->edad_recns;
                    $cuenta->edad_final=$row->edad_final;
                    $cuenta->codret=$row->codret;
                    $cuenta->cargo=$row->cargo;
                    $cuenta->ingreso=$row->ingreso;
                    $cuenta->cr=$row->cr;
                    $cuenta->fin_vehicular=$row->fin_vehicular;
                    $cuenta->cash_advance=$row->cash_advance;
                    $cuenta->tipo_refinanciacion=$row->tipo_refinanciacion;
                    $cuenta->contactabilidad=$row->contactabilidad;
                    $cuenta->motivo=$row->motivo;
                    $cuenta->submotivo=$row->submotivo;
                    $cuenta->castigada_sis_financiero=$row->castigada_sis_financiero;
                    $cuenta->ciudad=$row->ciudad;
                    $cuenta->zona=$row->zona;
                    $cuenta->kapital_diners=$row->kapital_diners;
                    $cuenta->tkapital_diners=$row->tkapital_diners;
                    $cuenta->riesgo_diners=$row->riesgo_diners;
                    $cuenta->cta_visa=$row->cta_visa;
                    $cuenta->ciclo_visa=$row->ciclo_visa;

                    $cuenta->mora=$row->mora;
                    $cuenta->saldo=$row->saldo;

                    $cuenta->mora_diners=$row->mora_diners;
                    $cuenta->saldo_diners=$row->saldo_diners;

                    $cuenta->mora_visa=$row->mora_visa;
                    $cuenta->saldo_visa=$row->saldo_visa;
                    $cuenta->mora_discover=$row->mora_discover;
                    $cuenta->saldo_discover=$row->saldo_discover;

                    $cuenta->cta_diners=$row->cta_diners;
                    $cuenta->ciclo_diners=$row->ciclo_diners;
                    $cuenta->kapital_discover=$row->kapital_discover;

                    $cuenta->kapital_visa=$row->kapital_visa;
                    $cuenta->tkapital_visa=$row->tkapital_visa;
                    $cuenta->riesgo_visa=$row->riesgo_visa;
                    $cuenta->cta_discover=$row->cta_discover;
                    $cuenta->ciclo_discover=$row->ciclo_discover;
                    $cuenta->tkapital_discover=$row->tkapital_discover;
                    $cuenta->riesgo_discover=$row->riesgo_discover;
                    $cuenta->kapital=$row->kapital;
                    $cuenta->riesgo_total=$row->riesgo_total;
                    $cuenta->nivel=$row->nivel;
                    $cuenta->gestion_realizada=$row->gestion_realizada;
                    $cuenta->buro_de_credito=$row->buro_de_credito;
                    $cuenta->cpd=$row->cpd;
                    $cuenta->parametro=$row->parametro;
                    $cuenta->decision_final=$row->decision_final;
                    $cuenta->llamada=$row->llamada;
                    $cuenta->visita=$row->visita;
                    $cuenta->protocolo=$row->protocolo;

                    $cuenta->producto=$row->producto;

                    $cuenta->actu=$row->actu;

                    $cuenta->pendiente_actual=$row->pendiente_actual;
                    $cuenta->pendiente_mora=$row->pendiente_mora;
                    $cuenta->total_pendiente=$row->total_pendiente;

                    $cuenta->nombreejecutivo_domicilio=$row->nombreejecutivo_domicilio;
                    $cuenta->ejecutivovisita=$row->ejecutivovisita;

                    $cuenta->vinculadoprincipal=$row->vinculadoprincipal;

                    $cuenta->d30=$row->d30;
                    $cuenta->d60=$row->d60;
                    $cuenta->d90=$row->d90;
                    $cuenta->dmas90=$row->dmas90;

                    $cuenta->masd90=$row->masd90;

                    $cuenta->stotmo=$row->stotmo;
                    $cuenta->actuales=$row->actuales;
                    $cuenta->stotot=$row->stotot;
                    $cuenta->ciclo=$row->ciclo;
                    $cuenta->ciclo_f=$row->ciclo_f;
                    $cuenta->producto=$row->producto;

                    $cuenta->actu=$row->actu;

                    $cuenta->motivo_1=$row->motivo_1;
                    $cuenta->ejecutivo=$row->ejecutivo;
                    $cuenta->vinculado=$row->vinculado;
                    $cuenta->observacionesdevinculacion=$row->observacionesdevinculacion;
                    $cuenta->clientevip=$row->clientevip;
                    $cuenta->empleado=$row->empleado;
                    $cuenta->cedulacyg=$row->cedulacyg;
                    $cuenta->nomsoc_cyg=$row->nomsoc_cyg;
                    $cuenta->codpri_dc_cyg=$row->codpri_dc_cyg;
                    $cuenta->codpri_dis_cyg=$row->codpri_dis_cyg;
                    $cuenta->codpri_id_cyg=$row->codpri_id_cyg;
                    $cuenta->mes_3=$row->mes_3;
                    $cuenta->mes_2=$row->mes_2;
                    $cuenta->mes_1=$row->mes_1;
                    $cuenta->ajust_ult_mes=$row->ajust_ult_mes;
                    $cuenta->valor_total=$row->valor_total;
                    $cuenta->no_ajustes=$row->no_ajustes;
                    $cuenta->pago=$row->pago;
                    $cuenta->pago_marzo=$row->pago_marzo;
                    $cuenta->pagos_marzo=$row->pagos_marzo;
                    $cuenta->calle_principal=$row->calle_principal;
                    $cuenta->numeracion=$row->numeracion;
                    $cuenta->calle_secundaria=$row->calle_secundaria;
                    $cuenta->sector=$row->sector;
                    $cuenta->parroquia=$row->parroquia;
                    $cuenta->canton=$row->canton;
                    $cuenta->provincia=$row->provincia;
                    $cuenta->referencia=$row->referencia;
                    $cuenta->mail=$row->mail;
                    $cuenta->rango_riesgo=$row->rango_riesgo;
                    $cuenta->responsable=$row->responsable;
                    $cuenta->visita_domicilio=$row->visita_domicilo;
                    $cuenta->visita_oficina=$row->visita_oficina;
                    $cuenta->databook=$row->databok;
                    $cuenta->paginas_investigacion=$row->paginas_investigacion;
                    $cuenta->conyugue=$row->conyugue;
                    $cuenta->circulo_familiar=$row->circulo_familiar;
                    $cuenta->migracion=$row->migracion;
                    $cuenta->resultado_final=$row->resultado_final;

                    if (strlen($row->cedula_agente)==9){
                        $cuenta->cedula_agente='0'.$row->cedula_agente;
                    }else{
                        $cuenta->cedula_agente=$row->cedula_agente;
                    }

                    $cuenta->agente_actual = $row->agente_actual;
                    $cuenta->correo_agente = $row->correo_agente;
                    try{
                        $cuenta->save();
                    }catch (\Exception $e) {
                        return ('Ocurrio un error: '.$e->getMessage());
                    }
                }

                try{

                    $reader->each(function($sheet) use($request,$marca) {
                        // recorre las filas por hoja
                        if ($marca=='' || $marca===null)$marca=$sheet->getTitle();
                        /*$sheet->each(function($row) use($marca,&$request) {
                            $cuenta=new tbl_diners_x88();
                            $cuenta->id_campana=$request->campana;
                            $cuenta->marca=$marca;
                            $cuenta->cuenta=$row->cuenta;
                            $cuenta->nombre=$row->nombre;

                            if (strlen($row->cedula)==9){
                                $cuenta->cedula='0'.$row->cedula;
                            }else{
                                $cuenta->cedula=$row->cedula;
                            }

                            $cuenta->mes_ing=$row->mes_ing;
                            $cuenta->ano_ing=$row->ano_ing;
                            $cuenta->edad_total=$row->edad_total;
                            $cuenta->edad_recns=$row->edad_recns;
                            $cuenta->edad_final=$row->edad_final;
                            $cuenta->codret=$row->codret;
                            $cuenta->cargo=$row->cargo;
                            $cuenta->ingreso=$row->ingreso;
                            $cuenta->cr=$row->cr;
                            $cuenta->fin_vehicular=$row->fin_vehicular;
                            $cuenta->cash_advance=$row->cash_advance;
                            $cuenta->tipo_refinanciacion=$row->tipo_refinanciacion;
                            $cuenta->contactabilidad=$row->contactabilidad;
                            $cuenta->motivo=$row->motivo;
                            $cuenta->submotivo=$row->submotivo;
                            $cuenta->castigada_sis_financiero=$row->castigada_sis_financiero;
                            $cuenta->ciudad=$row->ciudad;
                            $cuenta->zona=$row->zona;
                            $cuenta->kapital_diners=$row->kapital_diners;
                            $cuenta->tkapital_diners=$row->tkapital_diners;
                            $cuenta->riesgo_diners=$row->riesgo_diners;
                            $cuenta->cta_visa=$row->cta_visa;
                            $cuenta->ciclo_visa=$row->ciclo_visa;

                            $cuenta->mora=$row->mora;
                            $cuenta->saldo=$row->saldo;

                            $cuenta->mora_diners=$row->mora_diners;
                            $cuenta->saldo_diners=$row->saldo_diners;

                            $cuenta->mora_visa=$row->mora_visa;
                            $cuenta->saldo_visa=$row->saldo_visa;
                            $cuenta->mora_discover=$row->mora_discover;
                            $cuenta->saldo_discover=$row->saldo_discover;

                            $cuenta->cta_diners=$row->cta_diners;
                            $cuenta->ciclo_diners=$row->ciclo_diners;
                            $cuenta->kapital_discover=$row->kapital_discover;

                            $cuenta->kapital_visa=$row->kapital_visa;
                            $cuenta->tkapital_visa=$row->tkapital_visa;
                            $cuenta->riesgo_visa=$row->riesgo_visa;
                            $cuenta->cta_discover=$row->cta_discover;
                            $cuenta->ciclo_discover=$row->ciclo_discover;
                            $cuenta->tkapital_discover=$row->tkapital_discover;
                            $cuenta->riesgo_discover=$row->riesgo_discover;
                            $cuenta->kapital=$row->kapital;
                            $cuenta->riesgo_total=$row->riesgo_total;
                            $cuenta->nivel=$row->nivel;
                            $cuenta->gestion_realizada=$row->gestion_realizada;
                            $cuenta->buro_de_credito=$row->buro_de_credito;
                            $cuenta->cpd=$row->cpd;
                            $cuenta->parametro=$row->parametro;
                            $cuenta->decision_final=$row->decision_final;
                            $cuenta->llamada=$row->llamada;
                            $cuenta->visita=$row->visita;
                            $cuenta->protocolo=$row->protocolo;

                            $cuenta->producto=$row->producto;

                            $cuenta->actu=$row->actu;

                            $cuenta->pendiente_actual=$row->pendiente_actual;
                            $cuenta->pendiente_mora=$row->pendiente_mora;
                            $cuenta->total_pendiente=$row->total_pendiente;

                            $cuenta->nombreejecutivo_domicilio=$row->nombreejecutivo_domicilio;
                            $cuenta->ejecutivovisita=$row->ejecutivovisita;

                            $cuenta->vinculadoprincipal=$row->vinculadoprincipal;

                            $cuenta->d30=$row->d30;
                            $cuenta->d60=$row->d60;
                            $cuenta->d90=$row->d90;
                            $cuenta->dmas90=$row->dmas90;

                            $cuenta->masd90=$row->masd90;

                            $cuenta->stotmo=$row->stotmo;
                            $cuenta->actuales=$row->actuales;
                            $cuenta->stotot=$row->stotot;
                            $cuenta->ciclo=$row->ciclo;
                            $cuenta->ciclo_f=$row->ciclo_f;
                            $cuenta->producto=$row->producto;

                            $cuenta->actu=$row->actu;

                            $cuenta->motivo_1=$row->motivo_1;
                            $cuenta->ejecutivo=$row->ejecutivo;
                            $cuenta->vinculado=$row->vinculado;
                            $cuenta->observacionesdevinculacion=$row->observacionesdevinculacion;
                            $cuenta->clientevip=$row->clientevip;
                            $cuenta->empleado=$row->empleado;
                            $cuenta->cedulacyg=$row->cedulacyg;
                            $cuenta->nomsoc_cyg=$row->nomsoc_cyg;
                            $cuenta->codpri_dc_cyg=$row->codpri_dc_cyg;
                            $cuenta->codpri_dis_cyg=$row->codpri_dis_cyg;
                            $cuenta->codpri_id_cyg=$row->codpri_id_cyg;
                            $cuenta->mes_3=$row->mes_3;
                            $cuenta->mes_2=$row->mes_2;
                            $cuenta->mes_1=$row->mes_1;
                            $cuenta->ajust_ult_mes=$row->ajust_ult_mes;
                            $cuenta->valor_total=$row->valor_total;
                            $cuenta->no_ajustes=$row->no_ajustes;
                            $cuenta->pago=$row->pago;
                            $cuenta->pago_marzo=$row->pago_marzo;
                            $cuenta->pagos_marzo=$row->pagos_marzo;
                            $cuenta->calle_principal=$row->calle_principal;
                            $cuenta->numeracion=$row->numeracion;
                            $cuenta->calle_secundaria=$row->calle_secundaria;
                            $cuenta->sector=$row->sector;
                            $cuenta->parroquia=$row->parroquia;
                            $cuenta->canton=$row->canton;
                            $cuenta->provincia=$row->provincia;
                            $cuenta->referencia=$row->referencia;
                            $cuenta->mail=$row->mail;
                            $cuenta->rango_riesgo=$row->rango_riesgo;
                            $cuenta->responsable=$row->responsable;
                            $cuenta->visita_domicilio=$row->visita_domicilo;
                            $cuenta->visita_oficina=$row->visita_oficina;
                            $cuenta->databook=$row->databok;
                            $cuenta->paginas_investigacion=$row->paginas_investigacion;
                            $cuenta->conyugue=$row->conyugue;
                            $cuenta->circulo_familiar=$row->circulo_familiar;
                            $cuenta->migracion=$row->migracion;
                            $cuenta->resultado_final=$row->resultado_final;

                            if (strlen($row->cedula_agente)==9){
                                $cuenta->cedula_agente='0'.$row->cedula_agente;
                            }else{
                                $cuenta->cedula_agente=$row->cedula_agente;
                            }

                            $cuenta->agente_actual = $row->agente_actual;
                            $cuenta->correo_agente = $row->correo_agente;
                            try{
                                $cuenta->save();
                            }catch (\Exception $e) {
                                return ('Ocurrio un error: '.$e->getMessage());
                            }

                        });*/
                    });

                }catch (\Exception $e) {
                    return ('Ocurrio un error: '.$e->getMessage());
                }
            });

            //VALIDAR CUENTAS REPETIDAS.
            $repetidos=DB::connection('cuentasx88')->select("SELECT id_campana,cedula,ciclo,producto, marca FROM cuentas where (id_campana,cedula,ciclo,producto) IN (SELECT id_campana,cedula,ciclo,producto FROM cuentas_match order by marca);");
            $totalRepetidos=count($repetidos);
            $cuentasRepetidas='';
            if ($totalRepetidos>0){
                $count=2;
                $marcas='';
                foreach ($repetidos as $repetido) {
                    if ($marcas!=$repetido->marca){$count=2;}
                        $cuentasRepetidas.='Hoja: '.$repetido->marca.': Fila '.$count.' : Cuenta ya existe<br>';
                    $marcas=$repetido->marca;
                    $count++;
                }
                return response()->json($cuentasRepetidas, 500);
            }



            $id_carga=new tbl_id_carga();
            $id_carga->nombre_archivo=$request->archivo;
            $id_carga->estado=1;
            $id_carga->num_cuentas=tbl_diners_x88::count();
            $id_carga->agente_supervisor=$user->email;
            $id_carga->id_campana=$request->campana;
            $id_carga->nombre_campana=tbl_campaigns::find($request->campana)->name;
            $id_carga->save();
            $cuentas_match=tbl_diners_x88::get();
            try{
                foreach ($cuentas_match as $cuentam)
                {
                    $cuenta = new tbl_cuentas();
                    $cuenta->id_carga = $id_carga->id;
                    $cuenta->marca = $cuentam->marca;
                    $cuenta->cuenta = $cuentam->cuenta;
                    $cuenta->nombre = $cuentam->nombre;
                    $cuenta->cedula = $cuentam->cedula;

                    $cuenta->mes_ing=$cuentam->mes_ing;
                    $cuenta->ano_ing=$cuentam->ano_ing;
                    $cuenta->edad_total=$cuentam->edad_total;
                    $cuenta->edad_recns=$cuentam->edad_recns;
                    $cuenta->edad_final=$cuentam->edad_final;
                    $cuenta->codret=$cuentam->codret;
                    $cuenta->cargo=$cuentam->cargo;
                    $cuenta->ingreso=$cuentam->ingreso;
                    $cuenta->cr=$cuentam->cr;
                    $cuenta->fin_vehicular=$cuentam->fin_vehicular;
                    $cuenta->cash_advance=$cuentam->cash_advance;
                    $cuenta->tipo_refinanciacion=$cuentam->tipo_refinanciacion;
                    $cuenta->contactabilidad=$cuentam->contactabilidad;
                    $cuenta->motivo=$cuentam->motivo;
                    $cuenta->submotivo=$cuentam->submotivo;
                    $cuenta->castigada_sis_financiero=$cuentam->castigada_sis_financiero;
                    $cuenta->ciudad=$cuentam->ciudad;
                    $cuenta->zona=$cuentam->zona;
                    $cuenta->kapital_diners=$cuentam->kapital_diners;
                    $cuenta->tkapital_diners=$cuentam->tkapital_diners;
                    $cuenta->riesgo_diners=$cuentam->riesgo_diners;
                    $cuenta->cta_visa=$cuentam->cta_visa;
                    $cuenta->ciclo_visa=$cuentam->ciclo_visa;

                    $cuenta->mora=$cuentam->mora;
                    $cuenta->saldo=$cuentam->saldo;

                    $cuenta->mora_diners=$cuentam->mora_diners;
                    $cuenta->saldo_diners=$cuentam->saldo_diners;

                    $cuenta->mora_visa=$cuentam->mora_visa;
                    $cuenta->saldo_visa=$cuentam->saldo_visa;
                    $cuenta->mora_discover=$cuentam->mora_discover;
                    $cuenta->saldo_discover=$cuentam->saldo_discover;

                    $cuenta->cta_diners=$cuentam->cta_diners;
                    $cuenta->ciclo_diners=$cuentam->ciclo_diners;
                    $cuenta->kapital_discover=$cuentam->kapital_discover;

                    $cuenta->kapital_visa=$cuentam->kapital_visa;
                    $cuenta->tkapital_visa=$cuentam->tkapital_visa;
                    $cuenta->riesgo_visa=$cuentam->riesgo_visa;
                    $cuenta->cta_discover=$cuentam->cta_discover;
                    $cuenta->ciclo_discover=$cuentam->ciclo_discover;
                    $cuenta->tkapital_discover=$cuentam->tkapital_discover;
                    $cuenta->riesgo_discover=$cuentam->riesgo_discover;
                    $cuenta->kapital=$cuentam->kapital;
                    $cuenta->riesgo_total=$cuentam->riesgo_total;
                    $cuenta->nivel=$cuentam->nivel;
                    $cuenta->gestion_realizada=$cuentam->gestion_realizada;
                    $cuenta->buro_de_credito=$cuentam->buro_de_credito;
                    $cuenta->cpd=$cuentam->cpd;
                    $cuenta->parametro=$cuentam->parametro;
                    $cuenta->decision_final=$cuentam->decision_final;
                    $cuenta->llamada=$cuentam->llamada;
                    $cuenta->visita=$cuentam->visita;
                    $cuenta->protocolo=$cuentam->protocolo;

                    $cuenta->producto=$cuentam->producto;

                    $cuenta->actu=$cuentam->actu;

                    $cuenta->pendiente_actual=$cuentam->pendiente_actual;
                    $cuenta->pendiente_mora=$cuentam->pendiente_mora;
                    $cuenta->total_pendiente=$cuentam->total_pendiente;

                    $cuenta->nombreejecutivo_domicilio=$cuentam->nombreejecutivo_domicilio;
                    $cuenta->ejecutivovisita=$cuentam->ejecutivovisita;

                    $cuenta->vinculadoprincipal=$cuentam->vinculadoprincipal;

                    $cuenta->d30=$cuentam->d30;
                    $cuenta->d60=$cuentam->d60;
                    $cuenta->d90=$cuentam->d90;
                    $cuenta->dmas90=$cuentam->dmas90;

                    $cuenta->masd90=$cuentam->masd90;

                    $cuenta->stotmo=$cuentam->stotmo;
                    $cuenta->actuales=$cuentam->actuales;
                    $cuenta->stotot=$cuentam->stotot;
                    $cuenta->ciclo=$cuentam->ciclo;
                    $cuenta->ciclo_f=$cuentam->ciclo_f;
                    $cuenta->producto=$cuentam->producto;

                    $cuenta->actu=$cuentam->actu;

                    $cuenta->motivo_1=$cuentam->motivo_1;
                    $cuenta->ejecutivo=$cuentam->ejecutivo;
                    $cuenta->vinculado=$cuentam->vinculado;
                    $cuenta->observacionesdevinculacion=$cuentam->observacionesdevinculacion;
                    $cuenta->clientevip=$cuentam->clientevip;
                    $cuenta->empleado=$cuentam->empleado;
                    $cuenta->cedulacyg=$cuentam->cedulacyg;
                    $cuenta->nomsoc_cyg=$cuentam->nomsoc_cyg;
                    $cuenta->codpri_dc_cyg=$cuentam->codpri_dc_cyg;
                    $cuenta->codpri_dis_cyg=$cuentam->codpri_dis_cyg;
                    $cuenta->codpri_id_cyg=$cuentam->codpri_id_cyg;
                    $cuenta->mes_3=$cuentam->mes_3;
                    $cuenta->mes_2=$cuentam->mes_2;
                    $cuenta->mes_1=$cuentam->mes_1;
                    $cuenta->ajust_ult_mes=$cuentam->ajust_ult_mes;
                    $cuenta->valor_total=$cuentam->valor_total;
                    $cuenta->no_ajustes=$cuentam->no_ajustes;
                    $cuenta->pago=$cuentam->pago;
                    $cuenta->pago_marzo=$cuentam->pago_marzo;
                    $cuenta->pagos_marzo=$cuentam->pagos_marzo;
                    $cuenta->calle_principal=$cuentam->calle_principal;
                    $cuenta->numeracion=$cuentam->numeracion;
                    $cuenta->calle_secundaria=$cuentam->calle_secundaria;
                    $cuenta->sector=$cuentam->sector;
                    $cuenta->parroquia=$cuentam->parroquia;
                    $cuenta->canton=$cuentam->canton;
                    $cuenta->provincia=$cuentam->provincia;
                    $cuenta->referencia=$cuentam->referencia;
                    $cuenta->mail=$cuentam->mail;
                    $cuenta->rango_riesgo=$cuentam->rango_riesgo;
                    $cuenta->responsable=$cuentam->responsable;
                    $cuenta->visita_domicilio=$cuentam->visita_domicilo;
                    $cuenta->visita_oficina=$cuentam->visita_oficina;
                    $cuenta->databook=$cuentam->databok;
                    $cuenta->paginas_investigacion=$cuentam->paginas_investigacion;
                    $cuenta->conyugue=$cuentam->conyugue;
                    $cuenta->circulo_familiar=$cuentam->circulo_familiar;
                    $cuenta->migracion=$cuentam->migracion;
                    $cuenta->resultado_final=$cuentam->resultado_final;
                    $cuenta->agente_actual=$cuentam->agente_actual;

                    $cuenta->cedula_agente = $cuentam->cedula_agente;
                    $cuenta->correo_agente = $cuentam->correo_agente;
                    //validacion para las cuentas en legal y cuentas que no pertenecen a cobefec
                    if ($cuentam->agente_actual==null && $cuentam->correo_agente==null && $cuentam->cedula_agente==null){
                        $cuenta->estado_aprobado = 1;
                        $cuenta->estado_gestionado = 1;
                        $cuenta->gestion_realizada = 'Cuenta no pertenece a Cobefec';
                    }
                    if($cuentam->agente_actual=='CUENTA EN LEGAL'){
                        $cuenta->estado_aprobado = 1;
                        $cuenta->estado_gestionado = 1;
                        $cuenta->gestion_realizada = 'Cuenta en legal';
                    }
                    $cuenta->id_campana = $id_carga->id_campana;
                    $cuenta->save();
                }
            }catch (\Exception $e) {
                return response()->json(['Ocurrio un error: '.$e->getMessage()], 500);
            }
            return "Terminado";
        /*}
        else{
            // envío el status 423 => recurso bloqueado
            return response()->json(['errores'=>'Actualmente existen cuentas sin gestionar '], 423);
        }*/
    }

    public function gestionAgenteX88($id){
        $user = Auth::user();
        //$cuenta=tbl_cuentas::where('id',$id)->where('correo_agente',$user->email)->first();
        $cuenta=tbl_cuentas::where('id',$id)->where('correo_agente',$user->email)->first();

        if (count($cuenta)==0){
            return 'Esta cuenta no la puede gestionar.';
        }

        $campana=tbl_campaigns::find($cuenta->carga->id_campana);
        if (strlen($cuenta->cedula)==9){
            $cuenta->cedula='0'.$cuenta->cedula;
        }

        $cuentaG=tbl_accounts::where('target_document',$cuenta->cedula)->where('campaign_id',$campana->id)->first();
        if (count($cuentaG)==0){
            return 'Esta cuenta no pertenece a la campaña '.$cuenta->carga->nombre_campana.'.';
        }

        $llamadas=gestionesNuevoSistema($campana->id,$cuentaG->target_document,'MN');
        $visitas=gestionesNuevoSistema($campana->id,$cuentaG->target_document,'DM');

        $i=0;
        foreach ($visitas as $visita){
            $visitas[$i]->imagenes=json_decode($visita->images,true);
            $i++;
        }
        $parientes=Array();
        $parientes=['Conyugue','Padre','Madre','Hijo'];
        //$riesgo='riesgo_'.strtolower($cuenta->marca);
        $riesgo='riesgo_total';
        $riesgo=round($cuenta->$riesgo, 2);
        $requerido='';
        $requeridoaf='';
        if ($riesgo>10000){
            $requerido='required';
            $requeridoaf='*';
        }

        return view('reporteNuevoSistema.diners.cx88.formularioAgente',compact('cuenta','llamadas','visitas','parientes','riesgo','requerido','requeridoaf'));
    }

    public function gestionAgenteX88S($id){
        $user = Auth::user();
        //$cuenta=tbl_cuentas::where('id',$id)->where('correo_agente',$user->email)->first();
        $cuenta=tbl_cuentas::where('id',$id)->first();

        if (count($cuenta)==0){
            return 'Esta cuenta no la puede gestionar, revise que este asignada correctamente.';
        }

        $campana=tbl_campaigns::find($cuenta->carga->id_campana);
        if (strlen($cuenta->cedula)==9){
            $cuenta->cedula='0'.$cuenta->cedula;
        }
        $cuentaG=tbl_accounts::where('target_document',$cuenta->cedula)->where('campaign_id',$campana->id)->first();
        if (count($cuentaG)==0){
            return 'Esta cuenta no pertenece a la campaña '.$cuenta->carga->nombre_campana.'.';
        }
        $llamadas=gestionesNuevoSistema($campana->id,$cuentaG->target_document,'MN');
        $visitas=gestionesNuevoSistema($campana->id,$cuentaG->target_document,'DM');
        $i=0;
        foreach ($visitas as $visita){
            $visitas[$i]->imagenes=json_decode($visita->images,true);
            $i++;
        }
        $parientes=Array();
        $parientes=['Conyugue','Padre','Madre','Hijo'];


        $riesgo='riesgo_total';
        $riesgo=round($cuenta->$riesgo, 2);

        refrescarGestionRealizada($id);

        return view('reporteNuevoSistema.diners.cx88.formularioSupervisor',compact('cuenta','llamadas','visitas','parientes','riesgo'));
    }

    public function guardarGestionCx88(Request $request)
    {
        $user = Auth::user();
        $cuenta=tbl_cuentas::find($request->id);
        $cuenta->gestion_telefonica=$request->gestionTelefonica;
        $cuenta->gestion_campo=$request->gestionCampo;
        $cuenta->gestion_anterior=$request->gestionAnterior;

        $cuenta->investigacion_tipo=$request->tipoInvestigacion;
        $cuenta->investigacion=$request->investigacion;
        $cuenta->profesion=$request->profesion;

        $cuenta->ruc_tipo=$request->ruc;
        $cuenta->ruc=$request->nro;
        $cuenta->actividad_ruc=$request->actividadRuc;

        $cuenta->estado_civil=$request->estado_civil;

        if ($request->validadorConyugue==0){
            $cuenta->nombre_conyugue='';
            $cuenta->cedula_conyugue='';
            $cuenta->tel_conyugue='';
            $cuenta->ges_tel_conyugue='';
            $cuenta->cel_conyugue='';

        }else{
            $cuenta->nombre_conyugue=$request->nombreConyugue;
            $cuenta->cedula_conyugue=$request->cedulaConyugue;
            $cuenta->tel_conyugue=$request->tlfConvencionalConyugue;
            $cuenta->ges_tel_conyugue=$request->gestionTlfConyugue;
            $cuenta->cel_conyugue=$request->celularConyugue;
        }

        if ($request->validadorPadre==0){
            $cuenta->nombre_padre='';
            $cuenta->cedula_padre='';
            $cuenta->tel_padre='';
            $cuenta->ges_tel_padre='';
            $cuenta->cel_padre='';

        }else{
            $cuenta->nombre_padre=$request->nombrePadre;
            $cuenta->cedula_padre=$request->cedulaPadre;
            $cuenta->tel_padre=$request->tlfConvencionalPadre;
            $cuenta->ges_tel_padre=$request->gestionTlfPadre;
            $cuenta->cel_padre=$request->celularPadre;
        }

        if ($request->validadorMadre==0){
            $cuenta->nombre_madre='';
            $cuenta->cedula_madre='';
            $cuenta->tel_madre='';
            $cuenta->ges_tel_madre='';
            $cuenta->cel_madre='';
        }else{
            $cuenta->nombre_madre=$request->nombreMadre;
            $cuenta->cedula_madre=$request->cedulaMadre;
            $cuenta->tel_madre=$request->tlfConvencionalMadre;
            $cuenta->ges_tel_madre=$request->gestionTlfMadre;
            $cuenta->cel_madre=$request->celularMadre;
        }

        if ($request->validadorHijo==0){
            $cuenta->nombre_hijo='';
            $cuenta->cedula_hijo='';
            $cuenta->tel_hijo='';
            $cuenta->ges_tel_hijo='';
            $cuenta->cel_hijo='';
        }else{
            $cuenta->nombre_hijo=$request->nombreHijo;
            $cuenta->cedula_hijo=$request->cedulaHijo;
            $cuenta->tel_hijo=$request->tlfConvencionalHijo;
            $cuenta->ges_tel_hijo=$request->gestionTlfHijo;
            $cuenta->cel_hijo=$request->celularHijo;
        }

        $cuenta->calle_principal=$request->callePrincipal;
        $cuenta->numeracion=$request->numeracion;
        $cuenta->calle_secundaria=$request->calleSecundaria;
        $cuenta->sector=$request->sector;
        $cuenta->parroquia=$request->parroquia;
        $cuenta->ciudad2=$request->ciudad;
        $cuenta->canton=$request->canton;
        $cuenta->provincia=$request->provincia;
        $cuenta->referencia=$request->referencia;
        $cuenta->llamada=$request->llamada;
        $cuenta->visita=$request->visita;

        $cuenta->databook=$request->databook;
        $cuenta->paginas_investigacion=$request->paginasInvestigacion;
        $cuenta->circulo_familiar=$request->circuloFamiliar;
        $cuenta->migracion=$request->migracion;
        $cuenta->resultado_final=$request->resultadoFinal;

        $cuenta->visita_domicilio=$request->visita_domicilio;
        $cuenta->visita_oficina=$request->visita_oficina;

        $id_motivo='';
        if ($request->motivoNoPago==0){$id_motivo=null;}else{$id_motivo=$request->motivoNoPago;}
        $cuenta->motivo_id= $id_motivo ;
        $cuenta->motivo=isset(tbl_motivo::where('id',$request->motivoNoPago)->first()->nombre) ? tbl_motivo::where('id',$request->motivoNoPago)->first()->nombre : null;
        $cuenta->submotivo_id=$request->submotivoNoPago;
        $cuenta->submotivo=isset(tbl_submotivo::where('id',$request->submotivoNoPago)->first()->nombre) ? tbl_submotivo::where('id',$request->submotivoNoPago)->first()->nombre : null;

        $cuenta->sugerencia=$request->sugerencia;
        $cuenta->decision_ejecutivo=isset(tbl_sugerencia::where('nombre',$request->sugerencia)->first()->validador) ? tbl_sugerencia::where('nombre',$request->sugerencia)->first()->validador : null;
        $cuenta->contactabilidad=$request->contactabilidad;

        $contactabilidad=tbl_contactabilidad::where('nombre',$request->contactabilidad)->first();
        if ($contactabilidad){
            $cuenta->contactabilidad_id=$contactabilidad->id;
        }else{
            return "Error en la opción contactabilidad";
        }

        $cuenta->estado_guardado=$request->guardado;
        if ($request->enviarOk=='ok'){
            $cuenta->estado_gestionado=1;

            $observacion=new tbl_observaciones();
            $observacion->id_cuenta=$cuenta->id;
            $observacion->usuario_correo=$user->email;
            $observacion->fecha=date('Y-m-d H:i:s');
            $observacion->observacion='Enviado para aprobación';
            $observacion->save();
        }else{
            $cuenta->estado_gestionado=0;
        }
        if ($cuenta->estado_devuelto==1){
            $cuenta->estado_devuelto=0;
        }
        //dd($request->all());

        try{
            $cuenta->save();
        }catch (\Exception $e) {
            return ('Ocurrio un error: '.$e->getMessage());
        }

        refrescarGestionRealizada($cuenta->id);

        return redirect()->action('ReportesNuevoSistema\ReportesCx88Controller@index');
    }

    public function guardarGestionCx88S(Request $request)
    {
        $user = Auth::user();
        $aprobado='';
        $cuenta=tbl_cuentas::find($request->id);
        if ($request->estado_aprobado==0){
            $cuenta->estado_devuelto=1;
        }
        if ($request->estado_aprobado==1){
            $aprobado='APROBADO ';
            $cuenta->estado_devuelto=0;
        }

        $cuenta->estado_aprobado=$request->estado_aprobado;
        if (isset($request->observaciones)){
            $observacion=new tbl_observaciones();
            $observacion->id_cuenta=$cuenta->id;
            $observacion->usuario_correo=$user->email;
            $observacion->fecha=date('Y-m-d H:i:s');
            $observacion->observacion=$aprobado.$request->observaciones;
            $observacion->save();
        }

        $cuenta->save();


        return redirect()->action('ReportesNuevoSistema\ReportesCx88Controller@gestionadasSupervisor');
    }

    public function descargaExcelCx88S(Request $request)
    {
        $fecha=date('Y-m-d');
        $reportesD=null;
        $reportesV=null;
        $reportesDis=null;

        if ($request->tarjeta=='DINERS'){$reportesD=cuentasGestionadas($request->tarjeta,$request->id_campana,$request->ciclo);
            if (count($reportesD)>0) {
                foreach ($reportesD as $cuenta) {
                    refrescarGestionRealizada($cuenta->id);
                }
            }
        }
        if ($request->tarjeta=='VISA'){$reportesV=cuentasGestionadas($request->tarjeta,$request->id_campana,$request->ciclo);
            if (count($reportesV)>0) {
                foreach ($reportesV as $cuenta) {
                    refrescarGestionRealizada($cuenta->id);
                }
            }
        }
        if ($request->tarjeta=='DISCOVER'){$reportesDis=cuentasGestionadas($request->tarjeta,$request->id_campana,$request->ciclo);
            if (count($reportesDis)>0) {
                foreach ($reportesDis as $cuenta) {
                    refrescarGestionRealizada($cuenta->id);
                }
            }
        }
        if ($request->tarjeta=='TODAS') {
            $reportesD = cuentasGestionadas('DINERS', $request->id_campana, $request->ciclo);
            if (count($reportesD) > 0) {
                foreach ($reportesD as $cuenta) {
                    refrescarGestionRealizada($cuenta->id);
                }
            }
            $reportesV = cuentasGestionadas('VISA', $request->id_campana, $request->ciclo);
            if (count($reportesV) > 0) {
                foreach ($reportesV as $cuenta) {
                    refrescarGestionRealizada($cuenta->id);
                }
            }
            $reportesDis = cuentasGestionadas('DISCOVER', $request->id_campana, $request->ciclo);
            if (count($reportesDis) > 0) {
                foreach ($reportesDis as $cuenta) {
                    refrescarGestionRealizada($cuenta->id);
                }
            }
        }
        //



        if ($request->tarjeta=='DINERS'){$reportesD=cuentasGestionadas($request->tarjeta,$request->id_campana,$request->ciclo);
            if (count($reportesD)==0)dd('Debe tener por lo menos una cuenta Diners aprobada para general el reporte');
        }
        if ($request->tarjeta=='VISA'){$reportesV=cuentasGestionadas($request->tarjeta,$request->id_campana,$request->ciclo);
            if (count($reportesV)==0)dd('Debe tener por lo menos una cuenta Visa aprobada para general el reporte');
        }
        if ($request->tarjeta=='DISCOVER'){$reportesDis=cuentasGestionadas($request->tarjeta,$request->id_campana,$request->ciclo);
            if (count($reportesDis)==0)dd('Debe tener por lo menos una cuenta Discover aprobada para general el reporte');
        }
        if ($request->tarjeta=='TODAS'){
            $reportesD=cuentasGestionadas('DINERS',$request->id_campana,$request->ciclo);
            $reportesV=cuentasGestionadas('VISA',$request->id_campana,$request->ciclo);
            $reportesDis=cuentasGestionadas('DISCOVER',$request->id_campana,$request->ciclo);
            if (count($reportesD)>0 || count($reportesV)>0 || count($reportesDis)>0){}else{dd('Debe tener por lo menos una cuenta Diners, Visa o Discover aprobada para general el reporte');}
        }
        try {
            ini_set ( 'memory_limit' , '7000M' );
            ini_set('max_execution_time', 1200);
            \Excel::create('REPORTE CUENTAS X88 '.$fecha, function($excel) use (&$reportesD,$reportesV,$reportesDis){
                if (count($reportesD)>0){
                    $excel->sheet('DINERS', function($sheet) use($reportesD) {
                        $sheet->loadView('reporteNuevoSistema.diners.cx88.tableConsolidado')->with('reportes',$reportesD)->with('marca','DINERS');
                    });
                }
                if (count($reportesV)>0){
                    $excel->sheet('VISA', function($sheet) use($reportesV) {
                        $sheet->loadView('reporteNuevoSistema.diners.cx88.tableConsolidado')->with('reportes',$reportesV)->with('marca','VISA');
                    });
                }
                if (count($reportesDis)>0){
                    $excel->sheet('DISCOVER', function($sheet) use($reportesDis) {
                        $sheet->loadView('reporteNuevoSistema.diners.cx88.tableConsolidado')->with('reportes',$reportesDis)->with('marca','DISCOVER');
                    });
                }
            })->export('xlsx');
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function gestionRealizadaCx88S(Request $request)
    {
        try {
            $cuentas=tbl_cuentas::where('id_campana',$request->id_campana)->get();
            foreach ($cuentas as $cuenta){
                refrescarGestionRealizada($cuenta->id);
            }
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
        return 'listo';
    }
}

function agregarListaAsesores($id_campana){
    $asesores2=tbl_agentes::join('cuentas as c','c.correo_agente','=','agentes.correo_agente')->where('agentes.id_campana',$id_campana)->where('agentes.id_campana','c.id_campana')->groupBy('agentes.correo_agente')->get(['agentes.correo_agente','agentes.id_campana','agentes.agente_actual']);
    if (count($asesores2)>0) {
        foreach ($asesores2 as $k) {
            tbl_agentes::where('correo_agente', $k->correo_agente)->where('id_campana', $id_campana)->delete();
        }
    }
    $asesores2 = tbl_agentes::where('correo_agente', '!=', 'sinAgente')->where('id_campana', $id_campana)->get();
    return $asesores2;
}

function refrescarGestionRealizada($id_cuenta){

    $cuenta=tbl_cuentas::find($id_cuenta);

    if ($cuenta->estado_gestionado==1){
        $edad='';$tipoRefinanciacion='';
        if ($cuenta->marca=='DINERS'){$edad=$cuenta->edad_recns;}
        if ($cuenta->marca=='VISA' || $cuenta->marca=='DISCOVER'){$edad=$cuenta->edad_final;}

        if ($cuenta->tipo_refinanciacion=='REFINANCIACION'){$tipoRefinanciacion='refinanciada';}
        if ($cuenta->tipo_refinanciacion=='NO REFINANCIA'){$tipoRefinanciacion='no refinanciada';}
        if ($cuenta->tipo_refinanciacion=='REESTRUCTURACION'){$tipoRefinanciacion='reestructurada';}
        if ($cuenta->tipo_refinanciacion=='NOVACION'){$tipoRefinanciacion='novada';}

        if ($cuenta->agente_actual==null && $cuenta->correo_agente==null && $cuenta->cedula_agente==null){

        }else {
            //INICIO REFRESCAR GESTION REALIZADA
            $cuenta->gestion_realizada='';
            $cuenta->save();



            //$cuenta_gestion=tbl_accounts::where('identifier',$cuenta->producto.'->'.$cuenta->ciclo.'->'.$cuenta->cedula)->where('campaign_id',$cuenta->id_campana)->first();
            $query="select * from cobefec3.accounts where target_document='".$cuenta->cedula."' and campaign_id=".$cuenta->id_campana." and data ->> '$.producto'='".$cuenta->producto."' and data ->> '$.ciclof'=".$cuenta->ciclo.";";
            try {
                $cuenta_gestion=DB::connection('cobefec3')->select($query);
            }
            catch(\Exception $e) {
                return $e->getMessage();
            }

            try{
                $cuenta_gestion_data=json_decode($cuenta_gestion[0]->data,true);
            }
            catch(\Exception $e) {
                return $e->getMessage();
            }

            try{
                $cuenta->gestion_realizada .= 'Antecedentes: Socio registra ' . $edad . ' días en mora $' . $cuenta_gestion_data['saldo_actual'] . ' cuenta ' . $tipoRefinanciacion . '<br>';
            }
            catch(\Exception $e) {
                return 'Esta cuenta no tiene saldo actual';
            }

            $motivo_mora = '';
            if ($cuenta->motivo == $cuenta->submotivo) {
                $motivo_mora = $cuenta->motivo;
            } else {
                $motivo_mora = $cuenta->motivo . ' ' . $cuenta->submotivo;
            }
            $cuenta->gestion_realizada .= 'Motivo de Mora: ' . $motivo_mora . '<br>';

            $query = "select d.type, a.data ->> '$.ciclof' ciclo, a.data ->> '$.codpri' tarjeta, count(*) gestiones
    from cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
    where c.id=a.campaign_id and a.id=d.account_id 
    and c.id=" . $cuenta->carga->id_campana . " and document='" . $cuenta->cedula . "' and a.data ->> '$.producto'='" . $cuenta->producto . "' and a.data ->> '$.ciclof'=" . $cuenta->ciclo . " and d.type='MN'
    group by 1,2,3
    ;";
            try {
                $tlc = DB::connection('cobefec3')->select($query);
            } catch (\Exception $e) {
                return $e->getMessage();
            }

            if (count($tlc) == 0) {
                $tlcf = 0;
            } else {
                $tlcf = $tlc[0]->gestiones;
            }

            $cuenta->gestion_realizada .= 'Nro.Gest. Telef: ' . $tlcf . '<br>';

            $query = "select d.type, a.data ->> '$.ciclof' ciclo, a.data ->> '$.codpri' tarjeta, count(*) gestiones
    from cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
    where c.id=a.campaign_id and a.id=d.account_id 
    and c.id=" . $cuenta->carga->id_campana . " and document='" . $cuenta->cedula . "' and a.data ->> '$.producto'='" . $cuenta->producto . "' and a.data ->> '$.ciclof'=" . $cuenta->ciclo . " and d.type='DM'
    group by 1,2,3
    ;";
            try {
                $cex = DB::connection('cobefec3')->select($query);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            if (count($cex) == 0) {
                $cexf = 0;
            } else {
                $cexf = $cex[0]->gestiones;
            }
            $cuenta->gestion_realizada .= 'Nro.Gest. Domicilio: ' . $cexf . '<br>';

            $cuenta->gestion_realizada .= 'Detalle de Gestión:';
            if ($cuenta->gestion_telefonica != '') $cuenta->gestion_realizada .= 'En gestión teléfonica ' . $cuenta->gestion_telefonica . '. ';
            if ($cuenta->gestion_campo != '') $cuenta->gestion_realizada .= 'En visita de campo ' . $cuenta->gestion_campo . '. ';
            if ($cuenta->gestion_anterior != '') $cuenta->gestion_realizada .= 'En gestión anterior ' . $cuenta->gestion_anterior . '. ';
            if ($cuenta->investigacion_tipo != '') $cuenta->gestion_realizada .= 'En investigación ' . $cuenta->investigacion_tipo . ' ' . $cuenta->investigacion . '. ';
            if ($cuenta->profesion != '') $cuenta->gestion_realizada .= 'De profesión ' . $cuenta->profesion . '. ';


            if ($cuenta->ruc_tipo != '') {
                if ($cuenta->ruc_tipo != 'Suspendido' && $cuenta->ruc_tipo != 'No Tiene') {
                    $cuenta->gestion_realizada .= 'RUC: ' . $cuenta->ruc_tipo . ' No ' . $cuenta->ruc . '. Actividad RUC: ' . $cuenta->actividad_ruc . '. ';
                }
                if ($cuenta->ruc_tipo == 'Suspendido' || $cuenta->ruc_tipo == 'No Tiene') {
                    $cuenta->gestion_realizada .= 'RUC: ' . $cuenta->ruc_tipo . '. ';
                }
            }
            if ($cuenta->estado_civil != '') $cuenta->gestion_realizada .= 'Estado Civil: ' . $cuenta->estado_civil . '. ';
            //VALIDACION FAMILIARES
            if ($cuenta->nombre_conyugue) {
                $cuenta->gestion_realizada .= 'Nombre cónyugue: ' . $cuenta->nombre_conyugue;
                if ($cuenta->cedula_conyugue != '') {
                    $cuenta->gestion_realizada .= ' Cédula ' . $cuenta->cedula_conyugue;
                }
                if ($cuenta->tel_conyugue != '') {
                    $cuenta->gestion_realizada .= ' No teléfono convencional ' . $cuenta->tel_conyugue;
                }
                if ($cuenta->cel_conyugue != '') {
                    $cuenta->gestion_realizada .= ' Celular' . $cuenta->cel_conyugue;
                }
                $cuenta->gestion_realizada .= ' ' . $cuenta->ges_tel_conyugue . '. ';
            }

            if ($cuenta->nombre_padre) {
                $cuenta->gestion_realizada .= 'Nombre Padre: ' . $cuenta->nombre_padre;
                if ($cuenta->cedula_padre != '') {
                    $cuenta->gestion_realizada .= ' Cédula ' . $cuenta->cedula_padre;
                }
                if ($cuenta->tel_padre != '') {
                    $cuenta->gestion_realizada .= ' No teléfono convencional ' . $cuenta->tel_padre;
                }
                if ($cuenta->cel_padre != '') {
                    $cuenta->gestion_realizada .= ' Celular' . $cuenta->cel_padre;
                }
                $cuenta->gestion_realizada .= ' ' . $cuenta->ges_tel_padre . '. ';
            }

            if ($cuenta->nombre_madre) {
                $cuenta->gestion_realizada .= 'Nombre Madre: ' . $cuenta->nombre_madre;
                if ($cuenta->nombre_madre != '') {
                    $cuenta->gestion_realizada .= ' Cédula ' . $cuenta->nombre_madre;
                }
                if ($cuenta->tel_madre != '') {
                    $cuenta->gestion_realizada .= ' No teléfono convencional ' . $cuenta->tel_madre;
                }
                if ($cuenta->cel_madre != '') {
                    $cuenta->gestion_realizada .= ' Celular' . $cuenta->cel_madre;
                }
                $cuenta->gestion_realizada .= ' ' . $cuenta->ges_tel_madre . '. ';
            }

            if ($cuenta->nombre_hijo) {
                $cuenta->gestion_realizada .= 'Nombre Hijo: ' . $cuenta->nombre_hijo;
                if ($cuenta->cedula_hijo != '') {
                    $cuenta->gestion_realizada .= ' Cédula ' . $cuenta->cedula_hijo;
                }
                if ($cuenta->tel_hijo != '') {
                    $cuenta->gestion_realizada .= ' No teléfono convencional ' . $cuenta->tel_hijo;
                }
                if ($cuenta->cel_hijo != '') {
                    $cuenta->gestion_realizada .= ' Celular' . $cuenta->cel_hijo;
                }
                $cuenta->gestion_realizada .= ' ' . $cuenta->ges_tel_hijo . '. ';
            }

            if ($cuenta->sugerencia != '') $cuenta->gestion_realizada .= '<br>Sugerencia: ECE.: ' . $cuenta->sugerencia . '.';

            try {
                $cuenta->save();
            } catch (\Exception $e) {
                return ('Ocurrio un error: ' . $e->getMessage());
            }
        }
        //FIN REFRESCAR GESTION REALIZADA
    }
}

function gestionesNuevoSistema($campana,$cedula,$tipo){
    $query="select d.*
from cobefec3.campaigns c, cobefec3.accounts a, cobefec3.demarches d
where c.id=a.campaign_id and a.id=d.account_id
        and c.id=".$campana." and document='".$cedula."' and d.type='".$tipo."'
        ;";
    try {
        $gestiones=DB::connection('cobefec3')->select($query);
    }
    catch(\Exception $e) {
        return $e->getMessage();
    }
    return $gestiones;
}

function cuentasGestionadas($tarjeta,$id_campana,$ciclo){
    if (isset($ciclo)){
        $reportes=tbl_cuentas::where('estado_gestionado',1)->where('estado_aprobado',1)->where('marca',$tarjeta)->where('id_campana',$id_campana)->where('ciclo',$ciclo)->get();
    }else{
        $reportes=tbl_cuentas::where('estado_gestionado',1)->where('estado_aprobado',1)->where('marca',$tarjeta)->where('id_campana',$id_campana)->get();
    }

    return $reportes;
}