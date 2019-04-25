<?php
namespace App\Http\Controllers\Ivr;
use App\PermissionRole;
use App\reportesNuevoSistema\tbl_agents;
use App\reportesNuevoSistema\tbl_campaigns;
use App\reportesNuevoSistema\tbl_demarches;
use App\reportesNuevoSistema\tbl_users;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ivrs\tbl_carga_historico as carga_historico;
use App\ivrs\tbl_usuarios_clientes;
use App\ivrs\usuarios_varios_clientes;
use App\Role;
use App\Http\Controllers\Controller;
use App\ivrs\tbl_carga as carga;
use App\User;
use App\ivrs\tbl_campania as campanias;
use App\ivrs\tbl_cliente as clientes;
use App\ivrs\tbl_script as scripts;
use App\ivrs\tbl_id_carga as id_carga;
use App\ivrs\tbl_tipo_script as tipoScript;
use App\ivrs\tbl_canales as canales;
use App\User as users;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PHPExcel_IOFactory;
use PhpParser\Node\Expr\Cast\Array_;
use TCG\Voyager\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Clue\React\Ami\Protocol\Response;
use Clue\React\Ami\Protocol\Event;
use DB;

//Solo Asterisk con PAMI
use PAMI\Message\Action\CommandAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\SIPShowPeerAction;
use PAMI\Message\Action\SIPPeersAction;
use PAMI\Client\Impl\ClientImpl as PamiClient;

use PAMI\Listener\IEventListener;
use PAMI\Message\Event\EventMessage;
use PAMI\Message\Action\ListCommandsAction;
use PAMI\Message\Action\ListCategoriesAction;
use PAMI\Message\Action\CoreShowChannelsAction;
use PAMI\Message\Action\CoreSettingsAction;
use PAMI\Message\Action\CoreStatusAction;
use PAMI\Message\Action\StatusAction;
use PAMI\Message\Action\ReloadAction;
use PAMI\Message\Action\HangupAction;
use PAMI\Message\Action\LogoffAction;
use PAMI\Message\Action\AbsoluteTimeoutAction;
use PAMI\Message\Action\BridgeAction;
use PAMI\Message\Action\CreateConfigAction;
use PAMI\Message\Action\GetConfigAction;
use PAMI\Message\Action\GetConfigJSONAction;
use PAMI\Message\Action\AttendedTransferAction;
use PAMI\Message\Action\RedirectAction;
use PAMI\Message\Action\DAHDIShowChannelsAction;
use PAMI\Message\Action\DAHDIHangupAction;
use PAMI\Message\Action\DAHDIRestartAction;
use PAMI\Message\Action\DAHDIDialOffHookAction;
use PAMI\Message\Action\DAHDIDNDOnAction;
use PAMI\Message\Action\DAHDIDNDOffAction;
use PAMI\Message\Action\AgentsAction;
use PAMI\Message\Action\AgentLogoffAction;
use PAMI\Message\Action\MailboxStatusAction;
use PAMI\Message\Action\MailboxCountAction;
use PAMI\Message\Action\VoicemailUsersListAction;
use PAMI\Message\Action\PlayDTMFAction;
use PAMI\Message\Action\DBGetAction;
use PAMI\Message\Action\DBPutAction;
use PAMI\Message\Action\DBDelAction;
use PAMI\Message\Action\DBDelTreeAction;
use PAMI\Message\Action\GetVarAction;
use PAMI\Message\Action\SetVarAction;
use PAMI\Message\Action\PingAction;
use PAMI\Message\Action\ParkedCallsAction;
use PAMI\Message\Action\SIPQualifyPeerAction;
use PAMI\Message\Action\SIPShowRegistryAction;
use PAMI\Message\Action\SIPNotifyAction;
use PAMI\Message\Action\QueuesAction;
use PAMI\Message\Action\QueueStatusAction;
use PAMI\Message\Action\QueueSummaryAction;
use PAMI\Message\Action\QueuePauseAction;
use PAMI\Message\Action\QueueRemoveAction;
use PAMI\Message\Action\QueueUnpauseAction;
use PAMI\Message\Action\QueueLogAction;
use PAMI\Message\Action\QueuePenaltyAction;
use PAMI\Message\Action\QueueReloadAction;
use PAMI\Message\Action\QueueResetAction;
use PAMI\Message\Action\QueueRuleAction;
use PAMI\Message\Action\MonitorAction;
use PAMI\Message\Action\PauseMonitorAction;
use PAMI\Message\Action\UnpauseMonitorAction;
use PAMI\Message\Action\StopMonitorAction;
use PAMI\Message\Action\ExtensionStateAction;
use PAMI\Message\Action\JabberSendAction;
use PAMI\Message\Action\LocalOptimizeAwayAction;
use PAMI\Message\Action\ModuleCheckAction;
use PAMI\Message\Action\ModuleLoadAction;
use PAMI\Message\Action\ModuleUnloadAction;
use PAMI\Message\Action\ModuleReloadAction;
use PAMI\Message\Action\ShowDialPlanAction;
use PAMI\Message\Action\ParkAction;
use PAMI\Message\Action\MeetmeListAction;
use PAMI\Message\Action\MeetmeMuteAction;
use PAMI\Message\Action\MeetmeUnmuteAction;
use PAMI\Message\Action\EventsAction;
use PAMI\Message\Action\VGMSMSTxAction;
use PAMI\Message\Action\DongleSendSMSAction;
use PAMI\Message\Action\DongleShowDevicesAction;
use PAMI\Message\Action\DongleReloadAction;
use PAMI\Message\Action\DongleStartAction;
use PAMI\Message\Action\DongleRestartAction;
use PAMI\Message\Action\DongleStopAction;
use PAMI\Message\Action\DongleResetAction;
use PAMI\Message\Action\DongleSendUSSDAction;
use PAMI\Message\Action\DongleSendPDUAction;

use App\Jobs\SendIvrs;
use Carbon\Carbon;
//use Elasticsearch\ClientBuilder;

class IvrController extends Controller
{
    protected $fecha_act;
    public function __construct()
    {
        $this->middleware('auth');

        $this->fecha_act=Carbon::now();
    }

    private function _start(array $write, \PAMI\Message\Action\ActionMessage $action)
    {

        global $mock_stream_socket_client;
        global $mock_stream_set_blocking;
        global $mockTime;
        global $standardAMIStart;
        $mock_stream_socket_client = true;
        $mock_stream_set_blocking = true;
        $options = array(
            'host' => '172.16.21.242',
            'scheme' => 'tcp://',
            'port' => 5038,
            'username' => 'admin',
            'secret' => 'amp111',
            'connect_timeout' => 10,
            'read_timeout' => 10
        );
        $writeLogin = array(
            "action: Login\r\nactionid: 1432.123\r\nusername: admin\r\nsecret: amp111\r\n"
        );

        //setFgetsMock($standardAMIStart, $writeLogin);
        $client = new \PAMI\Client\Impl\ClientImpl($options);
        $client->open();
        if ($action instanceof \PAMI\Message\Action\DBGetAction) {
            $event = array(
                'Response: Success',
                'EventList: start',
                'ActionID: 1432.123',
                '',
                'Event: DBGetResponse',
                'ActionID: 1432.123',
                ''
            );
        } else {
            $event = array(
                'Response: Success',
                'ActionID: 1432.123',
                ''
            );
        }
        setFgetsMock($event, $write);
        //$result = $client->send($action);
        //$this->assertTrue($result instanceof \PAMI\Message\Response\ResponseMessage);
        //return $client;
    }

    public function index()
    {
        $user = Auth::user();
        //if(!(\Voyager::can('accede_ivrs_supervisor')))return redirect()->action('HomeController@index');

        $carga = array();
        $ivrprocesos = array();
        $ivrfinalizados = array();
        /*$carga = carga::where('estado','=',1)->get();
        $rol= Role::where('id',$user->role_id)->first();
        $ivrprocesos= id_carga::where('estado',1)->limit(10)->get();
        $ivrprocesos=$ivrprocesos->toArray();
        $i=0;

        foreach ($ivrprocesos as $k){

            $ivrprocesos[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
            $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->count();
            $sinprocesar=intval($sinprocesar);

            $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->count();
            $procesados=intval($procesados);

            $total=carga::where('id_carga',$k['id_carga'])->count();
            $total=intval($total);
            $porcentajeavance=round((($procesados/$total)*100),2);
            $ivrprocesos[$i]['porcentaje'] = $porcentajeavance;
            $ivrprocesos[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;

            $ivrprocesos[$i]['procesados'] = $procesados;
            $ivrprocesos[$i]['sinprocesar'] = $sinprocesar;
            if ($k['estado']==1){
                $ivrprocesos[$i]['estado'] = 'Activo';
            }
            $i++;
        }


        $ivrfinalizados= id_carga::where('estado',0)->limit(10)->get();
        $i=0;
        foreach ($ivrfinalizados as $k){
            $ivrfinalizados[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
            $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->count();
            $sinprocesar=intval($sinprocesar);
            $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->count();
            $procesados=intval($procesados);
            $total=carga::where('id_carga',$k['id_carga'])->count();
            $total=intval($total);
            if ($total==0){$porcentajeavance=100;}else{$porcentajeavance=round((($procesados/$total)*100),2);}
            $ivrfinalizados[$i]['porcentaje'] = $porcentajeavance;
            $ivrfinalizados[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;
            $ivrfinalizados[$i]['procesados'] = $procesados;
            $ivrfinalizados[$i]['sinprocesar'] = $sinprocesar;
            if ($k['estado']==0){
                $ivrfinalizados[$i]['estado'] = 'Finalizado';
            }
            $i++;
        }
*/

        return view('ivr/ivr', compact('user','carga','rol','ivrprocesos','ivrfinalizados'));
    }

    public function supervisorIvr()
    {
        $user = Auth::user();
        $carga = array();
        $ivrprocesos = array();
        $ivrfinalizados = array();
        /*$carga = carga::where('estado','=',1)->get();
        $rol= Role::where('id',$user->role_id)->first();
        $ivrprocesos= id_carga::where('estado',1)->limit(10)->get();
        $ivrprocesos=$ivrprocesos->toArray();
        $i=0;

        foreach ($ivrprocesos as $k){

            $ivrprocesos[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
            $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->count();
            $sinprocesar=intval($sinprocesar);

            $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->count();
            $procesados=intval($procesados);

            $total=carga::where('id_carga',$k['id_carga'])->count();
            $total=intval($total);
            $porcentajeavance=round((($procesados/$total)*100),2);
            $ivrprocesos[$i]['porcentaje'] = $porcentajeavance;
            $ivrprocesos[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;

            $ivrprocesos[$i]['procesados'] = $procesados;
            $ivrprocesos[$i]['sinprocesar'] = $sinprocesar;
            if ($k['estado']==1){
                $ivrprocesos[$i]['estado'] = 'Activo';
            }
            $i++;
        }


        $ivrfinalizados= id_carga::where('estado',0)->limit(10)->get();
        $i=0;
        foreach ($ivrfinalizados as $k){
            $ivrfinalizados[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
            $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->count();
            $sinprocesar=intval($sinprocesar);
            $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->count();
            $procesados=intval($procesados);
            $total=carga::where('id_carga',$k['id_carga'])->count();
            $total=intval($total);
            if ($total==0){$porcentajeavance=100;}else{$porcentajeavance=round((($procesados/$total)*100),2);}
            $ivrfinalizados[$i]['porcentaje'] = $porcentajeavance;
            $ivrfinalizados[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;
            $ivrfinalizados[$i]['procesados'] = $procesados;
            $ivrfinalizados[$i]['sinprocesar'] = $sinprocesar;
            if ($k['estado']==0){
                $ivrfinalizados[$i]['estado'] = 'Finalizado';
            }
            $i++;
        }
*/

        return view('ivr/ivr', compact('user','carga','rol','ivrprocesos','ivrfinalizados'));
    }

    public function administrarIvr()
    {
        $permisosPorRoles=PermissionRole::select('role_id')->where('permission_id',Permission::where('key','accede_ivrs_supervisor')->first()->id)->get(1);

        $roles=Array();
        $i=0;
        foreach ($permisosPorRoles as $permisosPorRole){
            $roles[$i]=$permisosPorRole->role_id;
            $i++;
        }

        $usuarios=users::whereIn('role_id',$roles)->get();

        foreach ($usuarios as $k){
            if(count(tbl_usuarios_clientes::where('id_usuarios_clientes',$k->id)->first())==0){
                $usuarios_clientes=new tbl_usuarios_clientes();
                $usuarios_clientes->id_usuarios_clientes=$k->id;
                $usuarios_clientes->save();
            }
        }

        $user = Auth::user();
        $campanias = campanias::where('estado',1)->get();
        $clientes = clientes::all();
        $scripts = scripts::all();
        $usuarios = User::whereIn('role_id',$roles)->get();

        $idCliente=null;
        $autorizados=array();
        $noautorizados=$usuarios->toArray();

        return view('ivr/cargaBase/administrarIvr', compact('user','campanias','clientes','scripts','usuarios','idCliente','autorizados','noautorizados'));
    }

    public function scriptsEstaticosIvr()
    {
        $user = Auth::user();
        $campanias = null;
        $clientes = clientes::all();
        $scripts = scripts::all();

        return view('ivr/cargaBase/scriptsEstaticosIvr', compact('user','campanias','clientes','scripts','idCliente'));
    }

    public function getScriptCliente($idCliente)
    {
        $user = Auth::user();

        $clientes = clientes::all();
        $scripts = scripts::all();

        return view('ivr/cargaBase/scriptsEstaticosIvr', compact('user','campanias','clientes','scripts','idCliente'));
    }

    public function scriptsClientes(Request $request)
    {
        $user = Auth::user();

        $clientes = clientes::all();
        $scripts = scripts::all();
        $rol= Role::where('name','ivrsupervisor')->first();


        return view('ivr/ivr', compact('user','carga','rol','ivrprocesos','ivrfinalizados'));
    }

    public function clienteIvr($idCliente)
    {

        $user = Auth::user();
        $campanias = campanias::where('estado',1)->get();
        $clientes = clientes::all();



        $permisosPorRoles=PermissionRole::select('role_id')->where('permission_id',Permission::where('key','accede_ivrs_supervisor')->first()->id)->get(1);

        $roles=Array();
        $i=0;
        foreach ($permisosPorRoles as $permisosPorRole){
            $roles[$i]=$permisosPorRole->role_id;
            $i++;
        }

        $usuarios=users::whereIn('role_id',$roles)->get();


        $usuarios_con_clientes = usuarios_varios_clientes::where('id_cliente',$idCliente)->get();

        $autorizados = array();
        $noautorizados = array();

        //dd($usuarios->toArray());

        $array=$usuarios->toArray();

        $ucc=$usuarios_con_clientes->toArray();


        foreach ($array as $key=>$value) {
            $cont=0;
            foreach ($ucc as $k=>$v){
                $indice = array_search($value['id'], $v);
                if ($indice && $idCliente==$v['id_cliente']){
                    $cont=1;
                    array_push($autorizados,array('id'=>$v['id_usuarios_clientes'],'name'=>User::where('id',$v['id_usuarios_clientes'])->first()->name));
                }
            }
            if ($cont==0){
                array_push($noautorizados,array('id'=>$value['id'],'name'=>User::where('id',$value['id'])->first()->name));
            }
        }

        $idCliente = clientes::where('id_cliente',$idCliente)->first();
        return view('ivr/cargaBase/administrarIvr', compact('idCliente','user','campanias','clientes','scripts','usuarios','autorizados','noautorizados'));
    }

    public function campanias(Request $request,$idCliente)
    {
        $campanias = campanias::where('estado',1)->where('id_cliente',$idCliente)->get();
        return $campanias;
    }

    public function usuariosClientes(Request $request)
    {
        $user = Auth::user();
        $campanias = campanias::where('estado',1)->get();
        $clientes = clientes::all();
        $scripts = scripts::all();

        $permisosPorRoles=PermissionRole::select('role_id')->where('permission_id',Permission::where('key','accede_ivrs_supervisor')->first()->id)->get(1);

        $roles=Array();
        $i=0;
        foreach ($permisosPorRoles as $permisosPorRole){
            $roles[$i]=$permisosPorRole->role_id;
            $i++;
        }

        $usuarios=users::whereIn('role_id',$roles)->get();



        if(count($request->destino)>0){
            foreach ($request->destino as $k){
                if(tbl_usuarios_clientes::where('id_usuarios_clientes',$k)->first()){}else{
                    $nuevo_usuario = new tbl_usuarios_clientes();
                    $nuevo_usuario->id_usuarios_clientes=$k;
                    $nuevo_usuario->save();
                }
                usuarios_varios_clientes::where('id_usuarios_clientes',$k)->where('id_cliente',$request->clienteselect)->delete();
                $usuarios_varios_clientes= new usuarios_varios_clientes();
                $usuarios_varios_clientes->id_cliente=$request->clienteselect;
                $usuarios_varios_clientes->id_usuarios_clientes=$k;
                $usuarios_varios_clientes->save();
            }
        }

        if(count($request->origen)>0){
            foreach ($request->origen as $k){
                usuarios_varios_clientes::where('id_usuarios_clientes',$k)->where('id_cliente',$request->clienteselect)->delete();
            }
        }

        $user = Auth::user();
        $carga = carga::where('estado','=',1)->get();
        $rol= Role::where('id',$user->role_id)->first();

        $ivrprocesos= id_carga::where('estado',1)->where('estado_aprobado',1)->get();
        $ivrprocesos=$ivrprocesos->toArray();


        $i=0;
        foreach ($ivrprocesos as $k){
            $ivrprocesos[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
            $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->get();
            $sinprocesar=count($sinprocesar);
            $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->get();
            $procesados=count($procesados);
            $total=carga::where('id_carga',$k['id_carga'])->get();
            $total=count($total);
            $porcentajeavance=($procesados/$total)*100;
            $ivrprocesos[$i]['porcentaje'] = $porcentajeavance;
            $ivrprocesos[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;
            if ($k['estado']==1){
                $ivrprocesos[$i]['estado'] = 'Activo';
            }
            $i++;
        }
        $ivrfinalizados= id_carga::where('estado',0)->get();

        $i=0;
        foreach ($ivrfinalizados as $k){
            $ivrfinalizados[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
            $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->get();
            $sinprocesar=count($sinprocesar);
            $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->get();
            $procesados=count($procesados);
            $total=carga::where('id_carga',$k['id_carga'])->get();
            $total=count($total);
            if ($total==0){$porcentajeavance=100;}else{$porcentajeavance=($procesados/$total)*100;}

            $ivrfinalizados[$i]['porcentaje'] = $porcentajeavance;
            $ivrfinalizados[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;

            if ($k['estado']==0){
                $ivrfinalizados[$i]['estado'] = 'Finalizado';
            }
            $i++;
        }

        return view('ivr/ivr', compact('user','carga','rol','ivrprocesos','ivrfinalizados'));
    }

    public function nuevoIvr()
    {
        $user = Auth::user();
        $campanias = campanias::where('estado',1)->get();
        $clientes = clientes::all();
        $scripts = scripts::all();
        $rol= Role::where('id',$user->role_id)->first();
        $usuarios = User::where('role_id',$rol->id)->get();

        $dir = public_path() . '/storage/temporalivr/';
        /*
                while ($file = readdir($dir))  {
                    if (is_file($dir.$file)) { unlink($dir.$file); }
                }
        */
        //dispatch(new SendIvrs());
        //$job = (new SendIvrs())->onQueue('processing');
        /*Carbon::setLocale('de');

        echo Carbon::now();
        $job = (new SendIvrs())
            ->delay(Carbon::now()->addSeconds(10));

        dispatch($job);*/
        return view('ivr/cargaBase/nuevoIvr', compact('user','campanias','clientes','scripts','usuarios','dir'));
    }

    public function nuevoIvr2(Request $request)
    {
        $user = Auth::user();
        if(\Voyager::can('accede_ivrs_administrador')){
            $usuarios_clientes=usuarios_varios_clientes::get();
        }else{
            $usuarios_clientes=usuarios_varios_clientes::where('id_usuarios_clientes',$user->id)->get();
        }


        $clientes= array();
        if(isset($usuarios_clientes)){
            foreach ($usuarios_clientes as $k){
                array_push($clientes,array('id_cliente'=>$k->id_cliente,'nombres'=>clientes::where('id_cliente',$k->id_cliente)->first()->nombres));
            }
        }
        $campanias = array();
        $scripts = scripts::all();
        $archivo=Input::file('file')->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        $dir = public_path() . '/storage/temporalivr/';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $nombrearchivo = $archivo;
        Input::file('file')->move($dir, $nombrearchivo);
        $cliente=array();
        $idCliente='';
        $reader='';
        $cabeceraExcel='';

        Excel::load($dir.$nombrearchivo, function($file) use (&$cabeceraExcel)
        {
            //$sheet = $file->setActiveSheetIndex(0);
            $cabeceraExcel=(array_keys($file->first()->toArray()));
        });
        $columns=DB::connection('ivrs')->select("SHOW COLUMNS FROM tbl_carga");
        $i=0;
        foreach ($columns as $value) {
            if ($i>7){
                $columnas[$i]=$value->Field;
            }
            //echo "'" . $value->Field . "' => '" . $value->Type . "|" . ( $value->Null == "NO" ? 'required' : '' ) ."', <br/>" ;
            $i++;
        }

        $sisGestionCampanas=tbl_campaigns::where('product_id',24)->where('enabled',1)->get();
        return view('ivr/cargaBase/nuevoIvr2', compact('user','campanias','clientes','cliente','scripts','archivo','dir','idCliente','cabeceraExcel','columnas','sisGestionCampanas'));
    }

    public function depurarIvrs(Request $request)
    {
//        return Input::hasFile('file');
        if(Input::hasFile('import_file')){
            $path = Input::file('import_file')->getRealPath();

            Excel::load($path, function($file)
            {
                $sheet = $file->setActiveSheetIndex(0);
                //$sheet->setCellValue('D28', 'Test sdsadsa d sad sa');
                foreach ($file->get() as $carga) {
                    $cargas = new carga;
                    $cargas->nombres=$carga->str_nombres_crg;
                    $cargas->telefono=$carga->str_telefono_crg;
                    $cargas->id_campania=$carga->int_id_camp;
                    $cargas->estado=$carga->bool_status;
                    $cargas->save();
                }
            });return carga::all();
            //-> download('xls');
            $data =Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $insert[] = ['title' => $value->title, 'description' => $value->description];
                }
                /*if(!empty($insert)){
                    DB::table('items')->insert($insert);
                    dd('Insert Record successfully.');
                }*/
            }
            dd($data->count());
        }
        return back();
        $user = Auth::user();
        $permisos= Permission::where('');
        return view('ivr/cargaBase/nuevoIvr', compact('user'));
    }

    public function depurarIvr(Request $request)
    {
        $lib_val_tel_array_errores = array (
            '0' => 'numero invalido',
            '1' => 'numero incompleto',
            '2' => 'numero sin codigo de area',
            '3' => 'sin numero',
            '4' => 'numero invalido',
            '5' => 'numero no comienza con 2-7',
            '6' => 'contiene grupo de numeros repetidos',
            '7' => 'el numero contien series',
            '8' => 'contiene repetidos',
            '9' => 'numero en tabla de equivocados'
        );

        if(Input::hasFile('file')){
            $path = Input::file('file')->getRealPath();
            $outa=array();

            $out = array (
                0 =>'FILA ',     // resultado (numero corregido)
                1 =>' ERROR',     // error en texto
            );
            array_push($outa, $out);
            $result='';



            Excel::load($path, function($file) use (&$result, &$outa)
            {
                $sheet = $file->setActiveSheetIndex(0);
                //$sheet->setCellValue('D28', 'Test sdsadsa d sad sa');
                $i=1;
                $o=0;
                $result='';
                foreach ($file->get() as $carga) {

                    $dat = $carga->telefono;//asignacion de la variable de telefono en la variable dat
                    $dat = trim($dat);//eliminacion de los espacios en blancos
                    $dat = preg_replace('#^[^\d]*#','',$dat);//eliminando los caracteres alfanumericos del inicio de la cadena
                    $dat = preg_replace('#o|O#','0',$dat);//remplaza la letra o y O por cero
                    $dat = preg_replace('#[^\d]#','',$dat);//eliminando los caracteres alfanumericos
                    if (preg_match('#^(593)#',$dat)&&((strlen ($dat) == 11)||(strlen ($dat) == 12)))//el control de numeros que contengan 593 para solo enviar el numero
                    {
                        $dat = substr($dat, 3);
                    }// fin del if

                    $i++;

                    if($dat=='' || strlen($dat)<=8)//control de campos vacios o incompletos
                    {
                        $o++;
                        if($o==1){$result='SE ENCONTRARON ERRORES!!';}

                        if(9==substr($dat, 1, -7)){
                            array_push($outa, $out = array (
                                0 => 'Fila '.$i.': ',
                                1 => 'Número celular incompleto')
                            );
                        }elseif(strlen($dat)<9){
                            array_push($outa, $out = array (
                                0 => 'Fila '.$i.': ',
                                1 => 'Número incompleto o vacío')
                            );
                        }


                        $result.='Fila: '.$i.' || mensaje: número incompleto ||';
                        $result=500;

                    }

                    /*$cargas = new carga;
                    $cargas->nombres=$carga->str_nombres_crg;
                    $cargas->telefono=$carga->str_telefono_crg;
                    $cargas->id_campania=$carga->int_id_camp;
                    $cargas->estado=$carga->bool_status;
                    $cargas->save();
                    */
                }


                if($result=='' || count($outa)==1){
                    $result=200;
                    $outa=  array (
                        0 => 'Archivo analizado: ',
                        1 => 'Validado exitosamente'
                    );
                }


            });//carga::all();
            //-> download('xls');
            //return \Response::json(['resultado' => $result], 200);
            //$outa = json_encode($outa);
            return \Response::json($outa, 200);
            $data =Excel::load($path, function($reader){})->get();

            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $insert[] = ['title' => $value->title, 'description' => $value->description];
                }
                /*if(!empty($insert)){
                    DB::table('items')->insert($insert);
                    dd('Insert Record successfully.');
                }*/
            }
            dd($data->count());
        }
        return back();
        $user = Auth::user();
        return view('ivr/cargaBase/nuevoIvr', compact('user'));
    }

    public function enviarIvrPrueba(Request $request)
    {

        $hosts = [
            '172.16.5.88:9200'         // IP + Port
        ];

        //$gestione=ClientBuilder::create()->setHosts($hosts)->build();
        //$gestion->addToIndex();
        //$gestion->index($gestione);


        /*$data = [
            'body' => [
                'testField' => 'abc'
            ],
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => 'my_id',
        ];

        $return = Elasticsearch::connection('elasticsearch')->index($data);
        dd($return);
        */

        $script = scripts::where('id_script', $request->scriptsselect)->first();
        $pamiClientOptions = array(
            'host' => '172.16.21.242',
            'scheme' => 'tcp://',
            'port' => 5038,
            'username' => 'admin',
            'secret' => 'amp111',
            'connect_timeout' => 10,
            'read_timeout' => 10
        );
        $respuesta = '';

        /*
        if ($i% 6==0  ){
            sleep(30);
        }*/


        $pamiClient = new PamiClient($pamiClientOptions);
        $pamiClient->open();
        $pami='';
        if ($request->tiposcriptsselect == 1) {
            $channel = 'Local/' . $request->demo . '@from-internal';
            $id_var=scripts::where('id_script',$request->scriptsselect)->first()->id_var;
            $originateMsg = new OriginateAction($channel);
            $originateMsg->setExtension('6001');
            $originateMsg->setContext('app-ivrplay2');
            $originateMsg->setTimeout('20000');
            $originateMsg->setCallerId('1000');
            $originateMsg->setVariable('VAR', $id_var);
            $originateMsg->setVariable('ID_CARGA', 'prueba-' . $this->fecha_act);
            $originateMsg->setAsync(true);
            $originateMsg->setActionID($request->demo . '-' .$id_var);
            $response = $pamiClient->send($originateMsg);
            if ($response->isSuccess()) {
                $respuesta = "Enviado Correctamente, espere unos segundos.!\n";
            } else {
                $respuesta = "Envío Fallido!\n";
            }
        }

        if ($request->tiposcriptsselect == 2) {
            //Seleccionamos el script dinamico creado por el usuario
            $script=mb_strtolower(scripts::where('id_script',$request->scriptsselect)->first()->script);
            //Buscamos las variables dentro de este formato {nombre_variable} y las almacenamos en la variable coincidencias
            if (preg_match_all('/{([a-z_a-z_0-9])*}/',$script,$coincidencias,PREG_OFFSET_CAPTURE, 0))
            {
                //echo "HAY ".count($coincidencias[0])." COINCIDENCIAS";
            }else
            {
                //echo "NO HAY COINCIDENCIA";
            }
            //creo una array var para almacenar los campos de la base de datos
            $var=array();
            for ($i=0;$i<count($coincidencias[0]);$i++){
                $var[$i]=$coincidencias[0][$i][0];
                $var[$i]=substr($var[$i],1);
                $var[$i]=substr($var[$i],0,-1);
                //reemplazo el nombre de las variables con la palabra estatica variable nombre_variable al ser un demo
                $script=str_replace($coincidencias[0][$i][0],' variable '.$var[$i], $script);
            }

            $channel = 'Local/' . $request->demo . '@from-internal';
            $originateMsg = new OriginateAction($channel);
            $originateMsg->setExtension('1001');
            $originateMsg->setContext('@from-internal');
            $originateMsg->setPriority('');
            $originateMsg->setApplication('PicoTTS');
            $originateMsg->setData('"'.$script.'",any,es-ES');


            //$originateMsg->setApplication('agi');
            //$originateMsg->setData('googletts.agi,"'.$script.'",es');
            $originateMsg->setTimeout('20000');
            $originateMsg->setCallerId('1000');
            $originateMsg->setVariable('ID_CARGA', 'prueba-' . $this->fecha_act);
            $originateMsg->setAsync(true);
            $originateMsg->setActionID($request->demo);
            $response = $pamiClient->send($originateMsg);
            if ($response->isSuccess()) {
                $respuesta = "Enviado Correctamente!\n";
            } else {
                $respuesta = "Envío Fallido!\n";
            }
        }
        $pamiClient->close();
        return $respuesta;

    }

    public function procesarIvr(Request $request)
    {
        //echo $request->fecha_agenda;
        //dd(Carbon::parse($request->fecha_agenda)->format('Y-m-d H:i:s'));
        $user = Auth::user();
        $fp = fopen($request->dir, "r");

        if($fp){
            $path = $request->dir;
            $result='';
            Excel::load($path, function($file) use (&$result, &$request,$user)
            {
                $sheet = $file->setActiveSheetIndex(0);
                //$sheet->setCellValue('D28', 'Test sdsadsa d sad sa');

                $id_carga_canales=id_carga::where('estado',1)->where('estado_aprobado',1)->get();
                $canales=canales::first();


                $id_carga=new id_carga();
                $id_carga->id_campania=$request->campaniaselect;
                $id_carga->fecha= $this->fecha_act;
                $id_carga->motivo='';
                $id_carga->id_usuario=$user->id;
                $id_carga->nombre_usuario=$user->email;
                $id_carga->id_campania_sis_gest=isset($request->sisGestCamapana) ? $request->sisGestCamapana : null;


                $id_carga->estado=1;
                if (count($id_carga_canales)==0){
                    $id_carga->canales=$canales->canales;
                    $id_carga->ejecucion=1;
                }else{
                    $total_canales=0;
                    foreach ($id_carga_canales as $k){
                        $total_canales=$total_canales+$k->canales;
                    }
                    if ($total_canales<=$canales->canales){
                        $id_carga->canales=$canales->canales-$total_canales;
                        $id_carga->ejecucion=1;
                    }
                }


                //comprobacion calendarizacion
                if ($request->habilitado==1){
                    $id_carga->calendarizado=1;
                    $id_carga->fecha_inicio_envio=Carbon::parse($request->fecha_agenda)->format('Y-m-d H:i:s');
                    $id_carga->ejecucion=0;
                    $id_carga->canales=0;
                }else{
                    $id_carga->calendarizado=0;
                }
                if(\Voyager::can('accede_ivrs_supervisor')){$id_carga->estado_aprobado=1;}
                if(\Voyager::can('accede_ivrs_administrador')){$id_carga->estado_aprobado=1;}

                $id_carga->save();
                foreach ($file->get() as $carga) {
                    $cargas = new carga_historico;
                    for($i=0;$i<$request->total;$i++){
                        $base=$request->input('basep'.$i);
                        $excel=$request->input('excelp'.$i);
                        $cargas->$base=$carga->$excel;
                    }

                    $cargas->id_carga=$id_carga->id_carga;
                    $cargas->tiposcript=$request->tiposcriptsselect;
                    $cargas->estado=1;
                    $cargas->id_script=$request->scriptsselect;
                    $cargas->save();

                    $cargas = new carga;
                    for($i=0;$i<$request->total;$i++){
                        $base=$request->input('basep'.$i);
                        $excel=$request->input('excelp'.$i);
                        $cargas->$base=$carga->$excel;
                    }
                    $cargas->id_carga=$id_carga->id_carga;
                    $cargas->tiposcript=$request->tiposcriptsselect;
                    $cargas->estado=1;
                    $cargas->id_script=$request->scriptsselect;
                    $cargas->save();
                    $request->id_carga=$id_carga->id_carga;
                }
            })->get();
        }

        $cliente=clientes::where('id_cliente',$request->clienteselect)->first();
        $campania=campanias::where('id_campania',$request->campaniaselect)->first();
        $script=scripts::where('id_script',$request->scriptsselect)->first();
        //$this->enviarIvr($request);


        return redirect()->action('Ivr\IvrController@index');

        //return view('ivr/cargaBase/procesar', compact('request','cliente','campania','script'));
    }

    public function mapeoExcel(Request $request)
    {
        $excel=array();
        $total=0;
        for ($i=0; $i<$request->total; $i++){
            if ($request->input('excel'.$i)){
                $excel[$i]=array('excel'=>$request->input('excel'.$i),'base'=>$request->input('columna'.$i));
                $total++;
            }
        }
        return [$excel,$total];
    }

    public function tipoScript()
    {
        $tipoScript=tipoScript::all();
        return $tipoScript;
    }

    public function reporteIvr(Request $request)
    {
        $fecha=id_carga::where('id_carga',$request->id_carga)->first()->fecha;
        $fecha=substr($fecha,0,-9);
        //if ($request->nm=='ATM-IVR INFORMATIVO' || $request->nm=='ATM-IVR APERTURA SABADOS'){
           //ESTA QUERY SI FUNCIONA PERO LO REEMPLAZAMOS POR UN PROCEDIMIENTO ALMACENADO PARA OPTIMIZAR EL TIEMPO
            /*$query='SELECT a.id AS id, a.cedula, a.telefono, a.cuenta,
                if((b.disposition=\'ANSWERED\' and b.duration=36 and b.billsec=27) or (b.disposition=\'ANSWERED\' and b.duration=36 and b.billsec=28) or (b.disposition=\'ANSWERED\' and b.duration=37 and b.billsec=27) or (b.disposition=\'ANSWERED\' and b.duration=37 and b.billsec=28),\'NO ANSWER\',b.disposition) as ESTADO, 
                b.calldate AS Fecha, 
                if((b.disposition=\'ANSWERED\' and b.duration=36 and b.billsec=27) or (b.disposition=\'ANSWERED\' and b.duration=36 and b.billsec=28) or (b.disposition=\'ANSWERED\' and b.duration=37 and b.billsec=27) or (b.disposition=\'ANSWERED\' and b.duration=37 and b.billsec=28),\'0\',b.billsec) as Duracion,
                 count(*) as Eventos
                FROM asteriskcdrdb.tbl_carga_ivr_federada AS a
                INNER JOIN asteriskcdrdb.cdr AS b ON a.telefono = b.dst and a.id_carga=b.id_carga
                WHERE
                b.dst <> \'0996408081\'
                AND b.dst <> \'0987777986\'
                AND b.dst <> \'0995059605\'
                AND dst!=\'6001\' 
                AND dst!=\'1001\' 
                AND dst!=\'s\'
                AND b.id_carga='.$request->id_carga.'
                GROUP BY a.id,a.cedula,a.telefono,a.cuenta,b.disposition,b.calldate,b.billsec
                ;';
            */
        //FIN ESTA QUERY SI FUNCIONA PERO LO REEMPLAZAMOS POR UN PROCEDIMIENTO ALMACENADO PARA OPTIMIZAR EL TIEMPO

        /*}else{
        $query='SELECT a.id AS id, a.cedula, a.telefono, a.cuenta, b.disposition as ESTADO, b.calldate AS Fecha, b.billsec as Duracion, count(*) as Eventos
                FROM asteriskcdrdb.tbl_carga_ivr_federada AS a
                INNER JOIN asteriskcdrdb.cdr AS b ON a.telefono = b.dst and a.id_carga=b.id_carga
                WHERE
                b.dst <> \'0996408081\'
                AND b.dst <> \'0987777986\'
                AND b.dst <> \'0995059605\'
                AND dst!=\'6001\' 
                AND dst!=\'1001\' 
                AND dst!=\'s\'
                AND b.id_carga='.$request->id_carga.'
                GROUP BY  a.id,a.cedula,a.telefono,a.cuenta,b.disposition,b.calldate,b.billsec
                ORDER BY id;';
        }
        */

        $query="call cobefec_reportes.sp_ivr(".$request->id_carga.");";
        $reporte = DB::connection('cdr')->select($query);
        $reporte = json_decode(json_encode($reporte), true);

        \Excel::create('REPORTE-'.$request->nm, function($excel) use (&$reporte){
            $excel->sheet('hoja1', function($sheet) use($reporte) {
                $i=1;
                foreach ($reporte as $k=>$v){
                    $reporte[$k]['id']=$i;
                    $i++;
                };
                $sheet->fromArray($reporte);
            });
        })->export('xlsx');
    }

    public function getIvrs()
    {
        $user = Auth::user();

        if(\Voyager::can('accede_ivrs_administrador')){

            $ivrprocesos= id_carga::where('estado',1)->orderBy('id_carga', 'desc')->get();
            $ivrprocesos=$ivrprocesos->toArray();
            $i=0;

            foreach ($ivrprocesos as $k){

                $ivrprocesos[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
                $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->count();
                $sinprocesar=intval($sinprocesar);
                $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->count();
                $procesados=intval($procesados);
                $total=carga::where('id_carga',$k['id_carga'])->count();
                $total=intval($total);
                if ($total==0){
                    $porcentajeavance=0;

                }else{
                    $porcentajeavance=round((($procesados/$total)*100),2);
                }



                $ivrprocesos[$i]['porcentaje'] = $porcentajeavance;
                $ivrprocesos[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;
                $ivrprocesos[$i]['procesados'] = $procesados;
                if ($k['fecha_inicio_envio']==null){
                    $ivrprocesos[$i]['fecha_inicio_envio']='Automático';
                }else{
                    $ivrprocesos[$i]['fecha_inicio_envio'] = $k['fecha_inicio_envio'];
                }
                $ivrprocesos[$i]['sinprocesar'] = $sinprocesar;
                $ivrprocesos[$i]['total'] = $total;
                if ($porcentajeavance==100){
                    $id_carga=id_carga::where('id_carga',$k['id_carga'])->first();
                    if (count($id_carga)>0){
                        $id_carga->estado=0;
                        $id_carga->save();
                    }
                }
                if ($k['estado']==1){
                    $ivrprocesos[$i]['estado'] = 'Activo';
                }

                $i++;
            }

            $ivrfinalizados= id_carga::where('estado',0)->orderBy('id_carga', 'desc')->limit(10)->get();
            $i=0;

            foreach ($ivrfinalizados as $k){
                $ivrfinalizados[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
                $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->count();
                $sinprocesar=intval($sinprocesar);
                $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->count();
                $procesados=intval($procesados);
                $total=carga::where('id_carga',$k['id_carga'])->count();
                $total=intval($total);

                $totalivrs = intval($k->total_carga);
                if($totalivrs==0){$totalivrs=1;}
                $totalllamados = intval($k->total_llamadas);
                $contactabilidad = round((($totalllamados * 100) / $totalivrs), 2);

                if ($total==0){$porcentajeavance=100;}else{$porcentajeavance=round((($procesados/$total)*100),2);}
                $ivrfinalizados[$i]['porcentaje'] = $porcentajeavance;
                $ivrfinalizados[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;
                $ivrfinalizados[$i]['procesados'] = $procesados;
                $ivrfinalizados[$i]['sinprocesar'] = $sinprocesar;
                $ivrfinalizados[$i]['total'] = $total;
                $ivrfinalizados[$i]['totalllamados']=$totalllamados;
                $ivrfinalizados[$i]['contactabilidad'] = $contactabilidad;
                if ($k->calendarizado==1){$calendarizado='';}else{$calendarizado='NO';}
                $ivrfinalizados[$i]['calendarizado'] = $calendarizado;
                $ivrfinalizados[$i]['fecha_inicio_envio'] = ($k->fecha_inicio_envio) ? $k->fecha_inicio_envio : "";
                if ($k['estado']==0){
                    $ivrfinalizados[$i]['estado'] = 'Finalizado';
                }
                if ($k['estado']==0){
                    $ivrfinalizados[$i]['estado'] = 'Finalizado';
                }
                if ($k['estado_aprobado']==0 && $k['motivo']!=''){
                    $ivrfinalizados[$i]['estado'] = '<strong style="color:#b0280d" >Denegado</strong>';
                }

                $cargaIndexada=id_carga::find($k['id_carga']);
                if ($cargaIndexada->porcentaje_indexado<100){
                    if ($cargaIndexada->campaniaIvr->clienteIvr->id_cliente == 11) {
                        //SELECT count(*) from asteriskcdrdb.tbl_carga_ivr_federada where id_carga=5175 and estado_indexado=1;
                        $ivrfinalizados[$i]['indexados'] = round(((carga::where('id_carga',$k['id_carga'])->where('estado_indexado',1)->count())/(carga::where('id_carga',$k['id_carga'])->count()))*100,2);
                        $cargaId=id_carga::find($k['id_carga']);
                        if ($ivrfinalizados[$i]['indexados']>=98){
                            $cargaId->porcentaje_indexado=100;
                            $cargaId->estado_indexado=1;
                            $cargaId->save();
                        }else{
                            $cargaId->porcentaje_indexado=$ivrfinalizados[$i]['indexados'];
                            $cargaId->estado_indexado=0;
                            $cargaId->save();
                        }
                        $ivrfinalizados[$i]['indexados'] = $ivrfinalizados[$i]['indexados']."%";
                    }else{
                        $ivrfinalizados[$i]['indexados'] = 'No aplica';
                    }
                }else{
                    $ivrfinalizados[$i]['indexados'] = '100%';
                }

                $i++;


            }


            return compact('ivrprocesos','ivrfinalizados');
        }
        elseif(\Voyager::can('accede_ivrs_supervisor')){

            $user_clients=usuarios_varios_clientes::where('id_usuarios_clientes',$user->id)->get();
            $campanias=array();
            $ci=0;
            foreach ($user_clients as $uk){
                $campania=campanias::where('id_cliente',$uk->id_cliente)->get();
                foreach ($campania as $key){
                    $campanias[$ci]=$key->id_campania;
                    $ci=$ci+1;
                }
            }
            //var_dump($campanias);

            $user_campania=campanias::WhereIn('id_campania',$campanias)->get();


            $ivrprocesos= id_carga::where('estado',1)->WhereIn('id_campania',$campanias)->orderBy('id_carga', 'desc')->get();

            $ivrprocesos=$ivrprocesos->toArray();
            $i=0;

            foreach ($ivrprocesos as $k){



                $ivrprocesos[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
                $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->count();
                $sinprocesar=intval($sinprocesar);
                $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->count();
                $procesados=intval($procesados);
                $total=carga::where('id_carga',$k['id_carga'])->count();
                $total=intval($total);
                if ($total==0){
                    $porcentajeavance=0;

                }else{
                    $porcentajeavance=round((($procesados/$total)*100),2);
                }



                $ivrprocesos[$i]['porcentaje'] = $porcentajeavance;
                $ivrprocesos[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;
                $ivrprocesos[$i]['procesados'] = $procesados;
                if ($k['fecha_inicio_envio']==null){
                    $ivrprocesos[$i]['fecha_inicio_envio']='Automático';
                }else{
                    $ivrprocesos[$i]['fecha_inicio_envio'] = $k['fecha_inicio_envio'];
                }
                $ivrprocesos[$i]['sinprocesar'] = $sinprocesar;
                $ivrprocesos[$i]['total'] = $total;
                if ($porcentajeavance==100){
                    $id_carga=id_carga::where('id_carga',$k['id_carga'])->first();
                    if (count($id_carga)>0){
                        $id_carga->estado=0;
                        $id_carga->save();
                    }
                }
                if ($k['estado']==1){
                    $ivrprocesos[$i]['estado'] = 'Activo';
                }

                $i++;
            }

            $ivrfinalizados= id_carga::where('estado',0)->WhereIn('id_campania',$campanias)->orderBy('id_carga', 'desc')->limit(10)->get();

            $i=0;

            foreach ($ivrfinalizados as $k){
                $ivrfinalizados[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
                $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->count();
                $sinprocesar=intval($sinprocesar);
                $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->count();
                $procesados=intval($procesados);
                $total=carga::where('id_carga',$k['id_carga'])->count();
                $total=intval($total);

                $totalivrs = intval($k->total_carga);
                if($totalivrs==0){$totalivrs=1;}
                $totalllamados = intval($k->total_llamadas);
                $contactabilidad = round((($totalllamados * 100) / $totalivrs), 2);

                if ($total==0){$porcentajeavance=100;}else{$porcentajeavance=round((($procesados/$total)*100),2);}
                $ivrfinalizados[$i]['porcentaje'] = $porcentajeavance;
                $ivrfinalizados[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;
                $ivrfinalizados[$i]['procesados'] = $procesados;
                $ivrfinalizados[$i]['sinprocesar'] = $sinprocesar;
                $ivrfinalizados[$i]['total'] = $total;
                $ivrfinalizados[$i]['totalllamados']=$totalllamados;
                $ivrfinalizados[$i]['contactabilidad'] = $contactabilidad;
                if ($k->calendarizado==1){$calendarizado='';}else{$calendarizado='NO';}
                $ivrfinalizados[$i]['calendarizado'] = $calendarizado;
                $ivrfinalizados[$i]['fecha_inicio_envio'] = ($k->fecha_inicio_envio) ? $k->fecha_inicio_envio : "";
                if ($k['estado']==0){
                    $ivrfinalizados[$i]['estado'] = 'Finalizado';
                }
                if ($k['estado']==0){
                    $ivrfinalizados[$i]['estado'] = 'Finalizado';
                }
                if ($k['estado_aprobado']==0 && $k['motivo']!=''){
                    $ivrfinalizados[$i]['estado'] = '<strong style="color:#b0280d" >Denegado</strong>';
                }

                $cargaIndexada=id_carga::find($k['id_carga']);
                if ($cargaIndexada->porcentaje_indexado<100){
                    if ($cargaIndexada->campaniaIvr->clienteIvr->id_cliente == 11) {
                        //SELECT count(*) from asteriskcdrdb.tbl_carga_ivr_federada where id_carga=5175 and estado_indexado=1;
                        $ivrfinalizados[$i]['indexados'] = round(((carga::where('id_carga',$k['id_carga'])->where('estado_indexado',1)->count())/(carga::where('id_carga',$k['id_carga'])->count()))*100,2);
                        $cargaId=id_carga::find($k['id_carga']);
                        if ($ivrfinalizados[$i]['indexados']>=99){
                            $cargaId->porcentaje_indexado=100;
                            //$cargaId->estado_indexado=1;
                            $cargaId->save();
                        }else{
                            $cargaId->porcentaje_indexado=$ivrfinalizados[$i]['indexados'];
                            //$cargaId->estado_indexado=0;
                            $cargaId->save();
                        }
                        $ivrfinalizados[$i]['indexados'] = $ivrfinalizados[$i]['indexados']."%";
                    }else{
                        $ivrfinalizados[$i]['indexados'] = 'No aplica';
                    }
                }else{
                    $ivrfinalizados[$i]['indexados'] = '100%';
                }
                $i++;
            }
            return compact('ivrprocesos','ivrfinalizados');
        }


    }

    public function reportesIvr()
    {
        $user = Auth::user();
        $campanias = campanias::where('estado',1)->get();
        $clientes = clientes::where('estado',1)->get();
        $scripts = scripts::all();
        $rol= Role::where('id',$user->role_id)->first();
        $usuarios = User::where('role_id',$rol->id)->get();

        $ivrfinalizados= id_carga::where('estado',0)->orderBy('id_carga', 'desc')->limit(10)->get();
        $i=0;

        foreach ($ivrfinalizados as $k){
            $ivrfinalizados[$i]['cliente'] = clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
            $sinprocesar=carga::where('id_carga',$k['id_carga'])->where('estado',1)->count();
            $sinprocesar=intval($sinprocesar);
            $procesados=carga::where('id_carga',$k['id_carga'])->where('estado',0)->count();
            $procesados=intval($procesados);
            $total=carga::where('id_carga',$k['id_carga'])->count();
            $total=intval($total);
            $totalllamados = intval($k->total_llamadas);

            $totalivrs = intval($k->total_carga);
            if($totalivrs==0){$totalivrs=1;}
            $totalllamados = intval($k->total_llamadas);
            $contactabilidad = round((($totalllamados * 100) / $totalivrs), 2);

            if ($total==0){$porcentajeavance=100;}else{$porcentajeavance=round((($procesados/$total)*100),2);}
            $ivrfinalizados[$i]['porcentaje'] = $porcentajeavance;
            $ivrfinalizados[$i]['id_campania'] = campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;
            $ivrfinalizados[$i]['procesados'] = $procesados;
            $ivrfinalizados[$i]['sinprocesar'] = $sinprocesar;
            $ivrfinalizados[$i]['totalllamados']=$totalllamados;
            $ivrfinalizados[$i]['contactabilidad'] = $contactabilidad;
            if ($k->calendarizado==1){$calendarizado='';}else{$calendarizado='NO';}
            $ivrfinalizados[$i]['calendarizado'] = $calendarizado;
            $ivrfinalizados[$i]['fecha_inicio_envio'] = ($k->fecha_inicio_envio) ? $k->fecha_inicio_envio : "";
            if ($k['estado']==0){
                $ivrfinalizados[$i]['estado'] = 'Finalizado';
            }
            $i++;
        }

        return view('ivr/reportes/index', compact('user','campanias','clientes','scripts','usuarios','dir','ivrfinalizados'));
    }

    public function procesarReporteIvr(Request $request)
    {


        ini_set('max_execution_time',0);
        $user = Auth::user();

        $clientes = clientes::where('estado',1)->get();
        $scripts = scripts::all();
        $rol= Role::where('id',$user->role_id)->first();
        $usuarios = User::where('role_id',$rol->id)->get();


        $fecha_inicio = Carbon::createFromFormat('d/m/Y', $request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d/m/Y', $request->fecha_fin)->format('Y-m-d');

        $i=0;

        foreach ($request->clientes as $cliente) {
            $campanias = campanias::where('estado',1)->where('id_cliente',$cliente)->get();
            foreach ($campanias as $campania) {
                $id_carga = id_carga::where('estado', 0)->where('id_campania', $campania->id_campania)->whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
                foreach ($id_carga as $k) {
                    $ivrfinalizados[$i]['id_carga'] = $k->id_carga;
                    $ivrfinalizados[$i]['fecha'] = $k->fecha;
                    $ivrfinalizados[$i]['cliente'] = clientes::where('id_cliente', $cliente)->first()->nombres;
                    $procesados = carga::where('id_carga', $k['id_carga'])->where('estado', 0)->count();
                    $procesados = intval($procesados);
                    $total = carga::where('id_carga', $k['id_carga'])->count();
                    $total = intval($total);

                    //$query = 'SELECT COUNT(*) as count FROM cdr where id_carga=' . $k['id_carga'] . ' and disposition=\'ANSWERED\'';
                    //$reporte = DB::connection('cdr')->select($query);

                    $totalivrs = intval($k->total_carga);
                    if($totalivrs==0){$totalivrs=1;}
                    $totalllamados = intval($k->total_llamadas);
                    $contactabilidad = round((($totalllamados * 100) / $totalivrs), 2);

                    if ($total == 0) {
                        $porcentajeavance = 100;
                    } else {
                        $porcentajeavance = round((($procesados / $total) * 100), 2);
                    }
                    $ivrfinalizados[$i]['porcentaje'] = $porcentajeavance;
                    $ivrfinalizados[$i]['id_campania'] = campanias::where('id_campania', $k['id_campania'])->first()->nombre_campania;
                    $ivrfinalizados[$i]['totalivrs'] = $totalivrs;
                    $ivrfinalizados[$i]['totalllamados']=$totalllamados;
                    $ivrfinalizados[$i]['contactabilidad'] = $contactabilidad;
                    if ($k->calendarizado==1){$calendarizado='';}else{$calendarizado='NO';}
                    $ivrfinalizados[$i]['calendarizado'] = $calendarizado;
                    $ivrfinalizados[$i]['fecha_inicio_envio'] = ($k->fecha_inicio_envio) ? $k->fecha_inicio_envio : "";
                    if ($k['estado']==0){
                        $ivrfinalizados[$i]['estado'] = 'Finalizado';
                    }
                    $i++;
                }
            }
        }
        return compact('ivrfinalizados');
    }

    public function canalesIvr()
    {
        $canales=canales::first();
        $campanias=id_carga::where('estado',1)->where('estado_aprobado',1)->get();
        foreach ($campanias as $k){
            $k->cliente=clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
            $k->campania=campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;
        }
        $mensaje='';
        return view('ivr/canales/index', compact('canales','campanias','mensaje'));
    }

    public function procesarCanalesIvr(Request $request)
    {
        $canales=canales::first();
        $canales->canales=$request->canales;
        $canales->save();
        return redirect()->action('Ivr\IvrController@index');
    }

    public function procesarCampanaIvr($id,Request $request)
    {

        $mensaje='';


        $canales=canales::first()->canales;
        $i_carga=id_carga::where('id_carga',$request->id_carga)->first();
        $i_carga->canales=$request->canales;

        $id_carga=id_carga::where('estado',1)->where('estado_aprobado',1)->where('id_carga','!=',$request->id_carga)->get();
        $suma_canales_ivrs=0;
        foreach ($id_carga as $k){
            $suma_canales_ivrs=$suma_canales_ivrs+$k->canales;
        }

        $suma_canales_ivrs=$suma_canales_ivrs+$request->canales;

        if ($suma_canales_ivrs<=$canales){
            $mensaje='<div class="alert alert-success"><h4>Canales actualizados</h4></div>';
            $i_carga->save();
        }else{
            $mensaje='<div class="alert alert-danger"><h4>Todos los canales estan siendo utilizados, recuerde que solo tiene '.$canales.' canales disponibles.</h4></div>';
        }

        $canales=canales::first();
        $campanias=id_carga::where('estado',1)->where('estado_aprobado',1)->get();
        foreach ($campanias as $k){
            $k->cliente=clientes::where('id_cliente',campanias::where('id_campania',$k['id_campania'])->first()->id_cliente)->first()->nombres;
            $k->campania=campanias::where('id_campania',$k['id_campania'])->first()->nombre_campania;
        }
        return view('ivr/canales/index', compact('canales','campanias','mensaje'));
    }

    function enviarIvr(Request $request)
    {
        ini_set('max_execution_time',0);
        $script=scripts::where('id_script',$request->scriptsselect)->first();
        $pamiClientOptions = array(
            'host' => '172.16.21.242',
            'scheme' => 'tcp://',
            'port' => 5038,
            'username' => 'admin',
            'secret' => 'amp111',
            'connect_timeout' => 10,
            'read_timeout' => 10
        );


        try{
            $carga=carga::where('estado',1)->limit(10)->get();

            $i=1;
            foreach ($carga as $k) {

                //$actualizar=carga::where('estado',0)->first();
                $k->estado=0;
                $k->save();

                sleep(1);

                $pamiClient = new PamiClient($pamiClientOptions);
                $pamiClient->open();
                if ($request->tiposcriptsselect == 1) {
                    $channel = 'Local/'.$k['telefono'].'@from-internal';
                    $id_var=scripts::where('id_script',$request->scriptsselect)->first()->id_var;
                    $originateMsg = new OriginateAction($channel);
                    $originateMsg->setExtension('6001');
                    $originateMsg->setContext('app-ivrplay2');
                    $originateMsg->setTimeout('20000');
                    $originateMsg->setCallerId('1000');
                    $originateMsg->setVariable('VAR', $id_var);
                    $originateMsg->setVariable('ID_CARGA',$k->id_carga);
                    $originateMsg->setAsync(true);
                    $originateMsg->setActionID($k['telefono'] . '-' .$id_var);
                    $response = $pamiClient->send($originateMsg);
                    if ($response->isSuccess()) {
                        $respuesta = "Enviado Correctamente, espere unos segundos.!\n";
                    } else {
                        $respuesta = "Envío Fallido!\n";
                    }
                }
                if ($request->tiposcriptsselect == 2) {
                    //Seleccionamos el script dinamico creado por el usuario
                    $script=mb_strtolower(scripts::where('id_script',$request->scriptsselect)->first()->script);
                    //Buscamos las variables dentro de este formato {nombre_variable} y las almacenamos en la variable coincidencias
                    if (preg_match_all('/{([a-z_a-z_0-9])*}/',$script,$coincidencias,PREG_OFFSET_CAPTURE, 0))
                    {
                        //echo "HAY ".count($coincidencias[0])." COINCIDENCIAS";
                    }else
                    {
                        //echo "NO HAY COINCIDENCIA";
                    }
                    //creo una array var para almacenar los campos de la base de datos
                    $var=array();
                    for ($i=0;$i<count($coincidencias[0]);$i++){
                        $var[$i]=$coincidencias[0][$i][0];
                        $var[$i]=substr($var[$i],1);
                        $var[$i]=substr($var[$i],0,-1);
                        //reemplazo el nombre de las variables con la palabra estatica variable nombre_variable al ser un demo
                        $script=str_replace($coincidencias[0][$i][0],$k[$var[$i]], $script);
                    }
                    $channel = 'Local/' . $k['telefono'] . '@from-internal';
                    $originateMsg = new OriginateAction($channel);
                    $originateMsg->setExtension('1001');
                    $originateMsg->setContext('@from-internal');
                    $originateMsg->setPriority('');
                    $originateMsg->setApplication('PicoTTS');
                    $originateMsg->setData('"'.$script.'",any,es-ES');
                    $originateMsg->setTimeout('20000');
                    $originateMsg->setCallerId('1000');
                    $originateMsg->setVariable('ID_CARGA',$k->id_carga);
                    $originateMsg->setAsync(true);
                    $originateMsg->setActionID($k['telefono']);
                    $response = $pamiClient->send($originateMsg);
                    if ($response->isSuccess()) {
                        $respuesta = "Enviado Correctamente!\n";
                    } else {
                        $respuesta = "Envío Fallido!\n";
                    }
                }
                //cambio de estado a cada número enviado
                //$idcarga=carga::find($k['id']);
                //$idcarga->estado=0;
                //$idcarga->save();
                $pamiClient->close();
                $i++;
            }
            return "Lista enviada correctamente";
        } catch (Exception $e) {
            print "Exception: " . $e->getMessage();
        }
    }

    public function play($id)
    {
        $id_carga=id_carga::where('id_carga',$id)->first();
        $id_carga->ejecucion=1;
        $id_carga->save();
        return redirect()->action('Ivr\IvrController@index');
    }
    public function pause($id)
    {
        $id_carga=id_carga::where('id_carga',$id)->first();
        $id_carga->ejecucion=0;
        $id_carga->canales=0;
        $id_carga->save();
        return redirect()->action('Ivr\IvrController@index');
    }

    public function aprobarIvr(Request $request)
    {
        $id_carga=id_carga::where('id_carga',$request->id_carga)->first();
        $id_carga->estado_aprobado=1;
        $id_carga->save();
        return redirect()->action('Ivr\IvrController@index');
    }

    public function denegarIvr(Request $request)
    {
        $id_carga=id_carga::where('id_carga',$request->id_carga)->first();
        $id_carga->estado_aprobado=0;
        $id_carga->estado=0;
        $id_carga->ejecucion=0;
        $motivo='motivo_'.$request->id_carga;
        $id_carga->motivo=$request->$motivo;
        if ($request->$motivo!='')$id_carga->save();

        return redirect()->action('Ivr\IvrController@index');
    }


    public function comandos(){
        try {

            /*
             'host' => '172.16.5.150', // pbx troncal de movistar de toda la empresa
                'scheme' => 'tcp://',
                'port' => 5038,
                'username' => 'admin',
                'secret' => 'ngZCFepGhLnh',
             * */

            $options = array(
                'host' => '172.16.21.242',
                'scheme' => 'tcp://',
                'port' => 5038,
                'username' => 'admin',
                'secret' => 'amp111',
                'connect_timeout' => 10,
                'read_timeout' => 10,
                'scheme' => 'tcp://' // try tls://
            );
            $a = new PamiClient($options);
            $a->open();
            // Registering a closure
            //$client->registerEventListener(function ($event) {
            //});

            // Register a specific method of an object for event listening
            //$client->registerEventListener(array($listener, 'handle'));

            // Register an IEventListener:

            var_dump($a->send(new CommandAction('core show calls')));
            //return $item;
            //var_dump($a->send(new ListCategoriesAction('sip.conf')));
            /*
                var_dump($a->send(new DongleSendUSSDAction('dongle01', '*101#')));
                var_dump($a->send(new DongleSendPDUAction('dongle01', 'AT+CSMS=0 ')));
                var_dump($a->send(new DongleRestartAction('now', 'dongle01')));
                var_dump($a->send(new DongleResetAction('dongle01')));
                var_dump($a->send(new DongleReloadAction('now')));
                var_dump($a->send(new DongleStopAction('now', 'dongle01')));
                var_dump($a->send(new DongleStartAction('dongle01')));
                var_dump($a->send(new DongleSendSMSAction('dongle01', '+666666666', 'a message')));
                var_dump($a->send(new ListCommandsAction()));
                var_dump($a->send(new QueueStatusAction()));
                var_dump($a->send(new QueueStatusAction()));
                var_dump($a->send(new QueueStatusAction()));
                var_dump($a->send(new CoreShowChannelsAction()));
                var_dump($a->send(new SIPPeersAction()));
                var_dump($a->send(new StatusAction()));
                var_dump($a->send(new CommandAction('sip show peers')));
                var_dump($a->send(new SIPShowRegistryAction()));
                var_dump($a->send(new CoreSettingsAction()));
                var_dump($a->send(new ListCategoriesAction('sip.conf')));
                var_dump($a->send(new CoreStatusAction()));
                var_dump($a->send(new GetConfigAction('extensions.conf')));
                var_dump($a->send(new GetConfigAction('sip.conf', 'general')));
                var_dump($a->send(new GetConfigJSONAction('extensions.conf')));
                var_dump($a->send(new DAHDIShowChannelsAction()));
                var_dump($a->send(new AgentsAction()));
                var_dump($a->send(new MailboxStatusAction('marcelog@gmail')));
                var_dump($a->send(new MailboxCountAction('marcelog@gmail')));
                var_dump($a->send(new VoicemailUsersListAction()));
                var_dump($a->send(new DBPutAction('something', 'a', 'a')));
                var_dump($a->send(new DBGetAction('something', 'a')));
                var_dump($a->send(new DBDelAction('something', 'a')));
                var_dump($a->send(new DBDelTreeAction('something', 'a')));
                var_dump($a->send(new SetVarAction('foo', 'asd')));
                var_dump($a->send(new SetVarAction('foo', 'asd', 'SIP/a-1')));
                var_dump($a->send(new GetVarAction('foo')));
                var_dump($a->send(new ParkedCallsAction()));
                var_dump($a->send(new GetVarAction('foo', 'SIP/a-1')));
                var_dump($a->send(new PingAction()));
                var_dump($a->send(new ExtensionStateAction('1', 'default')));
                var_dump($a->send(new ModuleCheckAction('chan_sip')));
                var_dump($a->send(new SIPShowPeerAction('marcelog')));
                var_dump($a->send(new QueuePauseAction('Agent/123')));
                var_dump($a->send(new QueueUnpauseAction('Agent/123')));
                var_dump($a->send(new QueueStatusAction()));
                $notify = new SIPNotifyAction('marcelog');
                $notify->setVariable('a', 'b');
                var_dump($a->send($notify));
                var_dump($a->send(new ShowDialPlanAction()));
                var_dump($a->send(new QueueSummaryAction()));
                var_dump($a->send(new QueueLogAction('a', 'asdasd')));
                var_dump($a->send(new QueuePenaltyAction('Agent/123', '123')));
                var_dump($a->send(new QueueResetAction('a')));
                var_dump($a->send(new QueueRuleAction('a')));
            */
dd();

            // Since we declare(ticks=1) at the top, the following line is not necessary



            $time = time();
            while(true)//(time() - $time) < 60) // Wait for events.
            {
                usleep(1000); // 1ms delay
                // Since we declare(ticks=1) at the top, the following line is not necessary
                $a->process();
            }
            $a->close(); // send logoff and close the connection.
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}

function setFgetsMock(array $readValues, $writeValues)
{
    global $mockFgets;
    global $mockFopen;
    global $mockFgetsCount;
    global $mockFgetsReturn;
    global $mockFwrite;
    global $mockFwriteCount;
    global $mockFwriteReturn;
    $mockFgets = true;
    $mockFopen = true;
    $mockFwrite = true;
    $mockFgetsCount = 0;
    $mockFgetsReturn = $readValues;
    $mockFwriteCount = 0;
    $mockFwriteReturn = $writeValues;

}
