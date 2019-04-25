<?php
use App\reportesNuevoSistema\cuentasX88\tbl_cuentas as tbl_cuentas;
?>@extends('layouts.appsupervisor')
@section('scripts')
<style type="text/css">
    a {
        color: #FFF;
        text-decoration: none;
    }
</style>
<script>
    $(document).ready(function() {
        $('#lista').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[20,50,-1,], [20,50,"Todo"]]
        } );

        $('#lista_cuentas').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[20,50,-1,], [20,50,"Todo"]]
        });
    } );
</script>
@endsection
@section('content')
<div class="content">

    <h2 align="center">REASIGNAR CUENTAS X88</h2>
    <div class="col-lg-6">
    <form class="content" id="logout-form" action="{{ url('/cuentasX88ReasignarP') }}" method="POST" onsubmit="selecciona('destino')">
        {{ csrf_field() }}
        <div align="center" class="col-lg-10 col-lg-offset-1">
        <select class="form-control col-lg-6" title="SELECCIONE UNA CAMPAÑA" name="id_campana" id="id_campana" required>
            <option value="">Seleccione una campaña</option>
            @if(isset($campana))
                <option value="{{$campana->id_campana}}" selected>{{$campana->nombre_campana}}</option>
            @else
                <option value="{{$campanas[0]->id_campana}}" selected>{{$campanas[0]->nombre_campana}}</option>
            @endif
            @foreach($campanas as $campana)
                <option value="{{$campana->id_campana}}">{{$campana->nombre_campana}}</option>
            @endforeach
        </select>
        <select class="form-control col-lg-4" title="SELECCIONE UN CICLO" name="ciclo" id="cicli" required>
            <option value="">Seleccione un ciclo</option>
            @if(isset($cicloEnv))
                <option value="{{$cicloEnv}}" selected>{{$cicloEnv}}</option>
            @endif
            @foreach($ciclos as $ciclo)
                <option value="{{$ciclo->ciclo}}">{{$ciclo->ciclo}}</option>
            @endforeach
        </select>
        <button class="btn btn-xs btn-info" id="getSelected" onclick="return confirmSubmit()"><span class="glyphicon glyphicon-search"></span> Buscar</button>
        </div>
    </form>
    </div>
    <div class="col-lg-6">
        <form class="content" id="logout-form" action="{{ url('/agregarAgenteX88') }}" method="POST" onsubmit="selecciona('destino')">
            {{ csrf_field() }}
            <div align="center" class="col-lg-10 col-lg-offset-1">
                <select class="form-control col-lg-6" title="AGREGAR UN AGENTE" name="id_agente" id="id_agente" required>
                    <option value="">Agregar un agente</option>
                    @if(isset($agentes))
                    @foreach($agentes as $k)
                        <option value="{{$k->id}}">{{$k->email}}</option>
                    @endforeach
                        @endif
                </select>
                <input type="hidden" name="id_campana" value="{{$campanas[0]->id_campana}}">
                @if(isset($cicloEnv))
                    <input type="hidden" name="ciclo" value="{{$cicloEnv}}">
                @endif
            <button class="btn btn-xs btn-info" id="getSelected" onclick="return confirmSubmit()"><span class="glyphicon glyphicon-plus"></span> Agregar agente</button>
            </div>
        </form>
    </div>
</div>
<br>
<br>
<div class="content">
    <div class="panel with-nav-tabs panel-primary" >
        <div class="panel-body" style="font-size:11px">
            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Asesores disponibles</strong></div>
                <table class="table table-hover table-striped display table-bordered" id="lista" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>CORREO</th>
                        <th>NOMBRES</th>
                        <th>CAMPAÑA</th>
                        <th>CICLO</th>
                        <th><span class="badge badge-success" style="background-color: #294d7d">asignados</span> </th>
                        <th><span class="badge badge-success" style="background-color:#3a7337">aprobados</span></th>
                        <th><span class="badge badge-success" style="background-color: #6b6b6b">sin gestionar</span></th>
                        <th><span class="badge badge-success" style="background-color: #999200">pendientes de aprobación</span></th>
                        <th><span class="badge badge-success" style="background-color: #c1504d">devueltos</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1;$result='';?>
                    @if(isset($asesores) && count($asesores)>0)
                    @foreach($asesores as $k=>$v)
                        <tr>
                            <td>{{$i}}</td>
                            <form id="logout-form" action="{{ url('/cuentasX88ReasignarN') }}" method="POST" onsubmit="selecciona('destino')">
                                <td>
                                    {{ csrf_field() }}
                                    <input type="hidden" name="asesor" value="{{$v->correo_agente}}">
                                    <input type="hidden" name="id_campana" value="{{$v->id_campana}}">
                                    <input type="hidden" name="ciclo" value="{{isset($cicloEnv) ? $cicloEnv : ''}}">

                                    <button class="modalButton btn btn-primary btn-xs">{{$v->correo_agente}}</button>
                                </td>
                            </form>
                            <td>{{$v->agente_actual}}</td>
                            <td>{{$v->carga->nombre_campana}}</td>
                            <td>@if(isset($cicloEnv)) {{$cicloEnv}} @else Todos los ciclos @endif</td>
                            <?php
                            if(isset($cicloEnv))$asignados=tbl_cuentas::where('correo_agente',$v->correo_agente)->where('estado_cuenta',1)->where('ciclo',$cicloEnv)->count();
                            if(!isset($cicloEnv))$asignados=tbl_cuentas::where('correo_agente',$v->correo_agente)->where('estado_cuenta',1)->count();

                            if(isset($cicloEnv))$aprobados=tbl_cuentas::where('correo_agente',$v->correo_agente)->where('estado_cuenta',1)->where('estado_gestionado',1)->where('estado_aprobado',1)->where('ciclo',$cicloEnv)->count();
                            if(!isset($cicloEnv))$aprobados=tbl_cuentas::where('correo_agente',$v->correo_agente)->where('estado_cuenta',1)->where('estado_gestionado',1)->where('estado_aprobado',1)->count();

                            if(isset($cicloEnv))$singestionar=tbl_cuentas::where('correo_agente',$v->correo_agente)->where('estado_cuenta',1)->where('estado_gestionado',0)->where('estado_devuelto',0)->where('estado_aprobado',0)->where('estado_cuenta',1)->where('estado_cuenta',1)->where('ciclo',$cicloEnv)->count();
                            if(!isset($cicloEnv))$singestionar=tbl_cuentas::where('correo_agente',$v->correo_agente)->where('estado_cuenta',1)->where('estado_gestionado',0)->where('estado_devuelto',0)->where('estado_aprobado',0)->where('estado_cuenta',1)->where('estado_cuenta',1)->count();

                            if(isset($cicloEnv))$pendientes=tbl_cuentas::where('correo_agente',$v->correo_agente)->where('estado_cuenta',1)->where('estado_gestionado',1)->where('estado_devuelto',0)->where('estado_aprobado',0)->where('estado_cuenta',1)->where('ciclo',$cicloEnv)->count();
                            if(!isset($cicloEnv))$pendientes=tbl_cuentas::where('correo_agente',$v->correo_agente)->where('estado_cuenta',1)->where('estado_gestionado',1)->where('estado_devuelto',0)->where('estado_aprobado',0)->where('estado_cuenta',1)->count();

                            if(isset($cicloEnv))$devueltos=tbl_cuentas::where('correo_agente',$v->correo_agente)->where('estado_cuenta',1)->where('estado_gestionado',1)->where('estado_devuelto',1)->where('estado_aprobado',0)->where('estado_cuenta',1)->where('ciclo',$cicloEnv)->count();
                            if(!isset($cicloEnv))$devueltos=tbl_cuentas::where('correo_agente',$v->correo_agente)->where('estado_cuenta',1)->where('estado_gestionado',1)->where('estado_devuelto',1)->where('estado_aprobado',0)->where('estado_cuenta',1)->count();
?>
                            <td><span class="badge badge-success" style="background-color: #294d7d">{{$asignados}}</span></td>
                            <td><span class="badge badge-success" style="background-color:#3a7337">{{$aprobados}}</span></td>
                            <td><span class="badge badge-success" style="background-color: #6b6b6b">{{$singestionar}}</span></td>
                            <td><span class="badge badge-success" style="background-color: #999200">{{$pendientes}}</span></td>
                            <td><span class="badge badge-success" style="background-color: #c1504d">{{$devueltos}}</span></td>
                        </tr><?php $i++;?>
                    @endforeach
                    @endif

                    @if(isset($asesores2) && count($asesores2))
                        @foreach($asesores2 as $k=>$v)
                            <tr>
                                <td>{{$i}}</td>
                                <form id="logout-form" action="{{ url('/cuentasX88ReasignarN') }}" method="POST" onsubmit="selecciona('destino')">
                                    <td>
                                        {{ csrf_field() }}
                                        <input type="hidden" name="asesor" value="{{$v->correo_agente}}">
                                        <input type="hidden" name="id_campana" value="{{$v->id_campana}}">
                                        @if(isset($cicloEnv))
                                            <input type="hidden" name="ciclo" value="{{$cicloEnv}}">
                                        @endif
                                        <button class="modalButton btn btn-primary btn-xs">{{$v->correo_agente}}</button>
                                    </td>
                                </form>
                                <td>{{$v->agente_actual}}</td>
                                <td>{{$v->carga->nombre_campana}}</td>
                                <td>@if(isset($cicloEnv)) {{$cicloEnv}} @else Todos los ciclos @endif</td>
                                <td><span class="badge badge-success" style="background-color: #294d7d">0</span></td>
                                <td><span class="badge badge-success" style="background-color:#3a7337">0</span></td>
                                <td><span class="badge badge-success" style="background-color: #6b6b6b">0</span></td>
                                <td><span class="badge badge-success" style="background-color: #999200">0</span></td>
                                <td><span class="badge badge-success" style="background-color: #c1504d">0</span></td>
                            </tr><?php $i++;?>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <?php print_r($result);?></div>
            <div class="panel panel-danger">
                <div class="panel-heading"><strong>Cuentas disponibles</strong></div>
                <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="lista_cuentas">
                    <thead>
                    <tr>
                        <th>CEDULA</th>
                        <th>NOMBRES</th>
                        <th>CAMPAÑA</th>
                        <th>MARCA</th>
                        <th>CICLO</th>
                        <th>PRODUCTO</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($cuentas)>0)
                    @foreach($cuentas as $k)
                        <tr>
                            <td>
                                <?php
                                $cedula_cliente=trim($k->cedula);
                                if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cedula_cliente;}
                                ?>
                                {{$cedula_cliente}}
                            </td>
                            <td>{{$k->nombre}}</td>
                            <td>{{$k->carga->nombre_campana}}</td>
                            <td>{{$k->marca}}</td>
                            <td>{{$k->ciclo}}</td>
                            <td>{{$k->producto}}</td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
                <?php print_r($result);?></div>
        </div>
    </div>
</div>
@endsection