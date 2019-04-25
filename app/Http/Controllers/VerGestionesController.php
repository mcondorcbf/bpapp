<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\tbl_gestiones as gestiones;
use Illuminate\Support\Facades\Auth;


class VerGestionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

            $gestiones_d = gestiones::where('marca','DINERS CLUB')->
            where('id_formato',1)->
            where('gestor',$user->name)->orderBy('id', 'desc')->get();

            $gestiones_vr = gestiones::where('marca','VISA INTERDIN')->
            where('id_formato',2)->
            where('gestor',$user->name)->orderBy('id', 'desc')->get();

            $gestiones_v = gestiones::where('marca','VISA INTERDIN')->
            where('id_formato',1)->
            where('gestor',$user->name)->orderBy('id', 'desc')->get();

            $gestiones_disr = gestiones::where('marca','DISCOVER')->
            where('id_formato',2)->
            where('gestor',$user->name)->orderBy('id', 'desc')->get();

            $gestiones_dis = gestiones::where('marca','DISCOVER')->
            where('id_formato',1)->
            where('gestor',$user->name)->orderBy('id', 'desc')->get();

            return view('verGestiones.agente' ,compact('gestiones_d','gestiones_v','gestiones_dis','gestiones_dr','gestiones_vr','gestiones_disr','archivos'));
    }

    public function gestion(Request $request)
    {
        //dd($request->id);
        $gestion=gestiones::where('id',$request->id)->first();

        return view('verGestiones/gestion' ,compact('gestion'));
    }

    public function observacion(Request $request)
    {
        //dd($request->id);
        $gestion=gestiones::where('id',$request->id)->first();
        return view('verGestiones/observacion' ,compact('gestion'));
    }

    public function enviarObservacion(Request $request)
    {
        try{
            $gestion=gestiones::where('id',$request->id)->first();
            $gestion->observacion=$gestion->observacion." // ".$request->observacion;
            $gestion->save();
            $mensaje='Observación enviada.';
        } catch (Exception $e) {
            $mensaje='Ocurrió un error.';
            Log::info("Exception: " . $e->getMessage());

        }

        return view('verGestiones/observacionEnviada' ,compact('gestion','mensaje'));
    }

    public function verRefinanciamiento($id)
    {

        $gestion=gestiones::where('id',$id)->first();

        return view('verGestiones/verRefinanciamiento' ,compact('gestion'));
    }
}