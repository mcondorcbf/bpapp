<?php

namespace App\Http\Controllers\Ivr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ivrs\tbl_cliente as cliente;

class ClienteController extends Controller
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
        $item = new cliente();
        $item->nombres=$request->nombres;
        $item->estado=1;
        $item->save();

        return $item->nombres;
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
