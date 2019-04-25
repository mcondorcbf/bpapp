<?php
namespace App\Http\Controllers\Movistar;
use App\movistar\tbl_carga_movistar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Role;
use App\Http\Controllers\Controller;
use App\User;
use App\User as users;
use App\movistar\tbl_movistar_chk_nt as movistar;

use Illuminate\Support\Facades\Input;
use DB;
use Carbon\Carbon;
class MovistarController extends Controller
{
    protected $fecha_act;
    public function __construct()
    {
        $this->middleware('auth');
        $this->fecha_act=Carbon::now(-5);
    }

    public function index()
    {
        $user = Auth::user();
        return view('movistar.movistar');
    }


    public function depurarMovistar(Request $request)
    {
        if(Input::hasFile('file')){
            $outa=array();

            $out = array (
                0 =>'FILA ',     // resultado (numero corregido)
                1 =>' ERROR',     // error en texto
            );
            array_push($outa, $out);
            $result='';

            set_time_limit(0);

            $archivo=date("Y-m-d")."-".Input::file('file')->getClientOriginalName();
            $dir = public_path() . '/storage/cargaMovistar/';
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            try {

                $carga = new tbl_carga_movistar();
                $carga->truncate();

                Input::file('file')->move($dir,$archivo);
                $csv = $dir.$archivo;

                try {
                    $query = sprintf("
                        LOAD DATA INFILE '%s'
                        INTO TABLE bddmovistar.tbl_carga_movistar
                        FIELDS TERMINATED BY ';' ENCLOSED BY ''
                        LINES TERMINATED BY '\n'
                        IGNORE 1 ROWS
                        (b_number,destino)", addslashes($csv));
                    DB::connection()->getpdo()->exec($query);

                    $a='%';
                    $query = sprintf("update bddmovistar.tbl_carga_movistar set destino='OFF NET MÓVIL' where destino like 'OFF NET MÓVIL%s'", htmlentities($a));
                    DB::connection()->getpdo()->exec($query);
                    $query = sprintf("update bddmovistar.tbl_carga_movistar set destino='ONNET' where destino like 'ONNET%s'", htmlentities($a));
                    DB::connection()->getpdo()->exec($query);
                    $outa=  array (
                        0 => 'Archivo analizado: ',
                        1 => 'Carga exitosa'
                    );
                    return \Response::json($outa, 200);

                } catch(Exception $e) {
                    $outa=  array (
                        0 => 'Error: ',
                        1 => $e
                    );
                    return \Response::json($outa, 500);
                }
            } catch(Exception $e) {
                $outa=  array (
                    0 => 'Error: ',
                    1 => $e
                );
                return \Response::json($outa, 500);
            }


        }
    }

    public function procesarMovistar(Request $request)
    {


        try{
            $count = movistar::count();
            if ($count==0){
                try {
                    $query = sprintf("INSERT INTO bddmovistar.tbl_movistar_chk_nt (varchar_number,int_net_check,status,created_at,updated_at)(select b_number,1,1,NOW(),NOW() from bddmovistar.tbl_carga_movistar where destino = 'OFF NET MÓVIL')");
                    DB::connection()->getpdo()->exec($query);
                    $mensaje = 'Base actualizada correctamente';
                    return view('movistar.movistar2',compact('mensaje'));
                } catch(Exception $e) {
                    $mensaje = 'Ocurrió un error en la base de datos: '.$e;
                    return view('movistar.movistar2',compact('mensaje'));
                }
            }
        else{
            try {
                $query = sprintf("INSERT INTO bddmovistar.tbl_movistar_chk_nt (varchar_number,int_net_check,status,created_at,updated_at)
                              (SELECT b.b_number,1,1,NOW(),NOW()
                              FROM bddmovistar.tbl_movistar_chk_nt a, bddmovistar.tbl_carga_movistar b
                              where b.b_number<>a.varchar_number and b.destino = 'OFF NET MÓVIL' GROUP BY b.b_number, b.destino)");
                DB::connection()->getpdo()->exec($query);
                $query = sprintf("update bddmovistar.tbl_movistar_chk_nt a, bddmovistar.tbl_carga_movistar b
                              set a.int_net_check='0', a.updated_at=NOW()
                              where b.b_number=a.varchar_number and b.destino like 'ONNET'");
                DB::connection()->getpdo()->exec($query);

                $mensaje = 'Base actualizada correctamente';
                return view('movistar.movistar2',compact('mensaje'));
            } catch(Exception $e) {
                $mensaje = 'Ocurrió un error en la base de datos: '.$e;
                return view('movistar.movistar2',compact('mensaje'));
            }
        }
        }
        catch (Exception $e){
            $mensaje= $e->errorInfo[1];
            return view('movistar.movistar2',compact('mensaje'));
        }

    }

    public function tipoScript()
    {
        $tipoScript=tipoScript::all();
        return $tipoScript;
    }

}
