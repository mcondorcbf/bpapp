<?php

namespace App\Http\Controllers\Ivr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ivrs\tbl_script as script;

class ScriptController extends Controller
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
        $item = new script();
        $item->script=$request->script;
        $item->id_campania=$request->idCampania;
        $item->tipo=$request->tipoScript;
        $item->estado=1;
        $item->save();
        return $item;
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
