<?php

namespace App\Http\Controllers\Ivr;

use App\ivrs\tbl_script as script;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ivrs\tbl_campania as campania;


class CampaniaController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        $item = new campania();
        $item->id_cliente=$request->idCliente;
        $item->nombre_campania=$request->nombre_campania;
        $h=date('h')+7;
        $item->fecha_campania=date('Y-m-d '.$h.':m:s');
        $item->estado=1;
        $item->save();

        return $item;
    }

    public function campaniascript(Request $request)
    {
        $item=script::where('id_campania',$request->id)->where('tipo',$request->tipo)->where('estado',1)->get();
        $idCampania=$request->id;
        if (count($item)==0){
            $item= array();
        }
        return [$item,$idCampania];
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
