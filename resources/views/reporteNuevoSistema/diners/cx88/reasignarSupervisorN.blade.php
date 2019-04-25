<?php
use App\reportesNuevoSistema\cuentasX88\tbl_cuentas as tbl_cuentas;
?>@extends('layouts.appsupervisor')
@section('scripts')

<style>

    .panel.with-nav-tabs .panel-heading{
        padding: 5px 5px 0 5px;
    }
    .panel.with-nav-tabs .nav-tabs{
        border-bottom: none;
    }
    .panel.with-nav-tabs .nav-justified{
        margin-bottom: -1px;
    }
    /********************************************************************/
    /*** PANEL DEFAULT ***/
    .with-nav-tabs.panel-default .nav-tabs > li > a,
    .with-nav-tabs.panel-default .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > li > a:focus {
        color: #777;
    }
    .with-nav-tabs.panel-default .nav-tabs > .open > a,
    .with-nav-tabs.panel-default .nav-tabs > .open > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > .open > a:focus,
    .with-nav-tabs.panel-default .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > li > a:focus {
        color: #777;
        background-color: #ddd;
        border-color: transparent;
    }
    .with-nav-tabs.panel-default .nav-tabs > li.active > a,
    .with-nav-tabs.panel-default .nav-tabs > li.active > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > li.active > a:focus {
        color: #555;
        background-color: #fff;
        border-color: #ddd;
        border-bottom-color: transparent;
    }
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu {
        background-color: #f5f5f5;
        border-color: #ddd;
    }
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a {
        color: #777;
    }
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
        background-color: #ddd;
    }
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a,
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
    .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
        color: #fff;
        background-color: #555;
    }
    /********************************************************************/
    /*** PANEL PRIMARY ***/
    .with-nav-tabs.panel-primary .nav-tabs > li > a,
    .with-nav-tabs.panel-primary .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-primary .nav-tabs > li > a:focus {
        color: #fff;
    }
    .with-nav-tabs.panel-primary .nav-tabs > .open > a,
    .with-nav-tabs.panel-primary .nav-tabs > .open > a:hover,
    .with-nav-tabs.panel-primary .nav-tabs > .open > a:focus,
    .with-nav-tabs.panel-primary .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-primary .nav-tabs > li > a:focus {
        color: #fff;
        background-color: #3071a9;
        border-color: transparent;
    }
    .with-nav-tabs.panel-primary .nav-tabs > li.active > a,
    .with-nav-tabs.panel-primary .nav-tabs > li.active > a:hover,
    .with-nav-tabs.panel-primary .nav-tabs > li.active > a:focus {
        color: #428bca;
        background-color: #fff;
        border-color: #428bca;
        border-bottom-color: transparent;
    }
    .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu {
        background-color: #428bca;
        border-color: #3071a9;
    }
    .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > li > a {
        color: #fff;
    }
    .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
    .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
        background-color: #3071a9;
    }
    .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > .active > a,
    .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
    .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
        background-color: #4a9fe9;
    }
    /********************************************************************/
    /*** PANEL SUCCESS ***/
    .with-nav-tabs.panel-success .nav-tabs > li > a,
    .with-nav-tabs.panel-success .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > li > a:focus {
        color: #3c763d;
    }
    .with-nav-tabs.panel-success .nav-tabs > .open > a,
    .with-nav-tabs.panel-success .nav-tabs > .open > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > .open > a:focus,
    .with-nav-tabs.panel-success .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > li > a:focus {
        color: #3c763d;
        background-color: #d6e9c6;
        border-color: transparent;
    }
    .with-nav-tabs.panel-success .nav-tabs > li.active > a,
    .with-nav-tabs.panel-success .nav-tabs > li.active > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > li.active > a:focus {
        color: #3c763d;
        background-color: #fff;
        border-color: #d6e9c6;
        border-bottom-color: transparent;
    }
    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu {
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }
    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > li > a {
        color: #3c763d;
    }
    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
        background-color: #d6e9c6;
    }
    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > .active > a,
    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
    .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
        color: #fff;
        background-color: #3c763d;
    }
    /********************************************************************/
    /*** PANEL INFO ***/
    .with-nav-tabs.panel-info .nav-tabs > li > a,
    .with-nav-tabs.panel-info .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-info .nav-tabs > li > a:focus {
        color: #31708f;
    }
    .with-nav-tabs.panel-info .nav-tabs > .open > a,
    .with-nav-tabs.panel-info .nav-tabs > .open > a:hover,
    .with-nav-tabs.panel-info .nav-tabs > .open > a:focus,
    .with-nav-tabs.panel-info .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-info .nav-tabs > li > a:focus {
        color: #31708f;
        background-color: #bce8f1;
        border-color: transparent;
    }
    .with-nav-tabs.panel-info .nav-tabs > li.active > a,
    .with-nav-tabs.panel-info .nav-tabs > li.active > a:hover,
    .with-nav-tabs.panel-info .nav-tabs > li.active > a:focus {
        color: #31708f;
        background-color: #fff;
        border-color: #bce8f1;
        border-bottom-color: transparent;
    }
    .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu {
        background-color: #d9edf7;
        border-color: #bce8f1;
    }
    .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > li > a {
        color: #31708f;
    }
    .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
    .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
        background-color: #bce8f1;
    }
    .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > .active > a,
    .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
    .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
        color: #fff;
        background-color: #31708f;
    }
    /********************************************************************/
    /*** PANEL WARNING ***/
    .with-nav-tabs.panel-warning .nav-tabs > li > a,
    .with-nav-tabs.panel-warning .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-warning .nav-tabs > li > a:focus {
        color: #8a6d3b;
    }
    .with-nav-tabs.panel-warning .nav-tabs > .open > a,
    .with-nav-tabs.panel-warning .nav-tabs > .open > a:hover,
    .with-nav-tabs.panel-warning .nav-tabs > .open > a:focus,
    .with-nav-tabs.panel-warning .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-warning .nav-tabs > li > a:focus {
        color: #8a6d3b;
        background-color: #faebcc;
        border-color: transparent;
    }
    .with-nav-tabs.panel-warning .nav-tabs > li.active > a,
    .with-nav-tabs.panel-warning .nav-tabs > li.active > a:hover,
    .with-nav-tabs.panel-warning .nav-tabs > li.active > a:focus {
        color: #8a6d3b;
        background-color: #fff;
        border-color: #faebcc;
        border-bottom-color: transparent;
    }
    .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu {
        background-color: #fcf8e3;
        border-color: #faebcc;
    }
    .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > li > a {
        color: #8a6d3b;
    }
    .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
    .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
        background-color: #faebcc;
    }
    .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > .active > a,
    .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
    .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
        color: #fff;
        background-color: #8a6d3b;
    }
    /********************************************************************/
    /*** PANEL DANGER ***/
    .with-nav-tabs.panel-danger .nav-tabs > li > a,
    .with-nav-tabs.panel-danger .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-danger .nav-tabs > li > a:focus {
        color: #a94442;
    }
    .with-nav-tabs.panel-danger .nav-tabs > .open > a,
    .with-nav-tabs.panel-danger .nav-tabs > .open > a:hover,
    .with-nav-tabs.panel-danger .nav-tabs > .open > a:focus,
    .with-nav-tabs.panel-danger .nav-tabs > li > a:hover,
    .with-nav-tabs.panel-danger .nav-tabs > li > a:focus {
        color: #a94442;
        background-color: #ebccd1;
        border-color: transparent;
    }
    .with-nav-tabs.panel-danger .nav-tabs > li.active > a,
    .with-nav-tabs.panel-danger .nav-tabs > li.active > a:hover,
    .with-nav-tabs.panel-danger .nav-tabs > li.active > a:focus {
        color: #a94442;
        background-color: #fff;
        border-color: #ebccd1;
        border-bottom-color: transparent;
    }
    .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu {
        background-color: #f2dede; /* bg color */
        border-color: #ebccd1; /* border color */
    }
    .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > li > a {
        color: #a94442; /* normal text color */
    }
    .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
    .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
        background-color: #ebccd1; /* hover bg color */
    }
    .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > .active > a,
    .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
    .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
        color: #fff; /* active text color */
        background-color: #a94442; /* active bg color */
    }
</style>

<style>
    input[type="text"]:read-only {
        background: #f0f0f0;
    }

    textarea[readonly="readonly"], textarea[readonly] {
        background: #f0f0f0;
    }

    .col-lg-12 {
        width: 100%;
        margin-bottom: 10px;
    }
</style>

<style>
    .form-group input[type="checkbox"] {
        display: none;
    }

    .form-group input[type="checkbox"] + .btn-group > label span {
        width: 20px;
    }

    .form-group input[type="checkbox"] + .btn-group > label span:first-child {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:last-child {
        display: inline-block;
    }

    .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
        display: inline-block;
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
        display: none;
    }
</style>
<script>
    function confirmSubmit()
    {
        var agree=confirm("Está seguro que desea continuar?");
        if (agree)
            return true ;
        else
            return false ;
    }
</script>
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
    <div class="panel with-nav-tabs panel-primary" >
        <div class="panel-body" style="font-size: 11px">
            <div  class="panel panel-warning">
                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>CORREO</th>
                        <th>NOMBRES</th>
                        <th>CAMPAÑA</th>
                        <th>CICLO</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td><a href="#" class="modalButton btn btn-primary btn-xs">{{$asesor->correo_agente}}</a></td>
                            <td>{{$asesor->agente_actual}}</td>
                            <td>{{$asesor->carga->nombre_campana}}</td>
                            <td>{{$cicloEnv!='' ? $cicloEnv : 'Todos los ciclos'}}</td>
                            <td>
                                <?php $cuentasAprobadas=tbl_cuentas::where('correo_agente',$asesor->correo_agente)->where('estado_cuenta',1)->where('estado_aprobado',1)->where('id_campana',$asesor->id_campana)->where('ciclo',$cicloEnv)->count();?>
                                @if($cuentasAprobadas>0)
                                        <a href="#" class="btn btn-xs btn-default">No se puede eliminar este agente porque ya tiene cuentas aprobadas</a>
                                @else
                                <form id="logout-form" action="{{ url('/eliminarAgenteX88') }}" method="POST" onsubmit="selecciona('destino')">
                                    <input type="hidden" name="asesor" value="{{$asesor->correo_agente}}">
                                    <input type="hidden" name="id_campana" value="{{$asesor->id_campana}}">
                                    <input type="hidden" name="ciclo" value="{{$cicloEnv!='' ? $cicloEnv : ''}}">

                                    {{ csrf_field() }}<button class="btn btn-xs btn-danger" id="getSelected" onclick="return confirmSubmit()"><span class="glyphicon glyphicon-trash"></span> Eliminar</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Cuentas asignadas sin gestión</strong></div>
                    <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="lista">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>CEDULA</th>
                            <th>NOMBRES</th>
                            <th>CAMPAÑA</th>
                            <th>MARCA</th>
                            <th>CICLO</th>
                            <th>PRODUCTO</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($cuentasAsignadas) && count($cuentasAsignadas)>0)
                        @foreach($cuentasAsignadas as $k)
                            <tr class="alert ">
                                <td>{{$k->id}}</td>
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
                                <td>
                                    <form id="logout-form" action="{{ url('/eliminarAsignacionCx88') }}" method="POST" onsubmit="selecciona('destino')">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="asesor" value="{{$asesor->correo_agente}}">
                                        <input type="hidden" name="id_campana" value="{{$k->id_campana}}">
                                        <input type="hidden" name="cuenta" value="{{$k->id}}">
                                        <button class="btn btn-xs btn-danger" id="getSelected" onclick="return confirmSubmit()"><span class="glyphicon glyphicon-trash"></span> Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                <form id="logout-form" action="{{ url('/cuentasX88Eliminar') }}" method="POST" onsubmit="selecciona('destino')">
                    {{ csrf_field() }}
                    <input type="hidden" name="asesor" value="{{$asesor->correo_agente}}">
                    <input type="hidden" name="id_campana" value="{{$asesor->id_campana}}">
                    <a href="{{url('cuentasX88Reasignar')}}" class="btn btn-success" id="getSelected"><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
                    <button class="btn btn-danger" id="getSelected" onclick="return confirmSubmit()"><span class="glyphicon glyphicon-trash"></span> Eliminar todos</button>
                </form>
            </div>

            <div class="panel panel-success">
                <div class="panel-heading"><strong>Cuentas disponibles</strong></div>
                    <input type="hidden" name="asesor" value="{{$asesor->correo_agente}}">
                    <input type="hidden" name="id_campana" value="{{$asesor->id_campana}}">
                    <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="lista_cuentas">
                        <thead>
                        <tr>
                            <th>CEDULA</th>
                            <th>NOMBRES</th>
                            <th>CAMPAÑA</th>
                            <th>MARCA</th>
                            <th>CICLO</th>
                            <th>PRODUCTO</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php $i=0;?>
                        @if(isset($cuentas) && count($cuentas)>0)
                        @foreach($cuentas as $k)
                            <?php $i++;?>
                            <tr><form id="logout-form" action="{{ url('/cuentasX88ReasignarN2') }}" method="POST" onsubmit="selecciona('destino')">
                                <td>
                                    {{ csrf_field() }}
                                    <input type="hidden" name="asesor" value="{{$asesor->correo_agente}}">
                                    <input type="hidden" name="id_campana" value="{{$k->id_campana}}">
                                    <input type="hidden" name="cuenta" value="{{$k->id}}">
                                    <input type="hidden" name="ciclo" value="{{$k->ciclo}}">
                                    <?php
                                    $cedula_cliente=trim($k->cedula);
                                    if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cedula_cliente;}
                                    ?>
                                    <label for="cuentas{{$i}}" class="[ btn btn-default active ]">
                                        {{$cedula_cliente}}
                                    </label>
                                </td>
                                <td>{{$k->nombre}}</td>
                                <td>{{$k->carga->nombre_campana}}</td>
                                <td>{{$k->marca}}</td>
                                <td>{{$k->ciclo}}</td>
                                <td>{{$k->producto}}</td>
                                <td><button class="btn btn-xs  btn-primary" id="getSelected" onclick="return confirmSubmit()"><span class="glyphicon glyphicon-ok"></span> Asignar</button>
                                </td>
                                </form>
                            </tr>
                        @endforeach
                        @endif
                        <input type="hidden" name="total_cuentas" value="{{$i}}">
                        </tbody>
                    </table>
                <form id="logout-form" action="{{ url('/cuentasX88ReasignarTodos') }}" method="POST" onsubmit="selecciona('destino')">
                        {{ csrf_field() }}
                    <input type="hidden" name="asesor" value="{{$asesor->correo_agente}}">
                    <input type="hidden" name="id_campana" value="{{$asesor->id_campana}}">
                    <a href="{{url('cuentasX88Reasignar')}}" class="btn btn-success" id="getSelected"><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
                    <button class="btn btn-primary" id="getSelected" onclick="return confirmSubmit()"><span class="glyphicon glyphicon-list"></span> Asignar todos</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection