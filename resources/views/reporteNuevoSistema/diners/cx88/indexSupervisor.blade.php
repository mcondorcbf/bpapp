<?php
use App\reportesNuevoSistema\cuentasX88\tbl_observaciones as observaciones;
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
@endsection
@section('content')
<style type="text/css">
    a {
        color: #FFF;
        text-decoration: none;
    }
</style>
<script>
    $(document).ready(function() {
        $('#diners').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        } );

        $('#visa').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        });
        $('#discover').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        });
    } );
</script>
<div class="content">
    <h2 align="center">CUENTAS X88 SIN GESTIONAR</h2>
    <div class="col-lg-12">
        <form class="content" id="logout-form" action="{{ url('/cuentasX88SP') }}" method="POST" onsubmit="selecciona('destino')">
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
</div>
<br>
<br>
<div class="content">
    <div class="panel with-nav-tabs panel-primary" >
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#dinersconsolidado" data-toggle="tab"><strong>BASE CUENTAS X88 DINERS <span class="badge">{{$totalDiners}}</span></strong>
                    </a>
                </li>
                <li class="">
                    <a href="#visaconsolidado" data-toggle="tab">BASE CUENTAS X88 VISA <span class="badge">{{$totalVisa}}</span>
                    </a>
                </li>
                <li class="">
                    <a href="#discoverconsolidado" data-toggle="tab">BASE CUENTAS X88 DISCOVER <span class="badge">{{$totalDiscover}}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content tab-content-border" style="font-size: 11px">
            <div class="row tab-pane fade active in" id="dinersconsolidado">
                <div class="col-md-12 col-lg-12">
                    <!-- Nav tabs -->
                    <table id="diners" class="display" cellspacing="0" style="width: 100%" width="100%">
                        <thead>
                        <tr>
                            <th>N°</th>
                            <th>NOMBRES CLIENTE</th>
                            <th>CEDULA CLIENTE</th>
                            <th>NOMBRE AGENTE</th>
                            <th>CORREO AGENTE</th>
                            <th>CAMPAÑA</th>
                            <th>CICLO</th>
                            <th>PRODUCTO</th>
                            <th>OBSERVACIONES</th>
                            <th>ESTADO</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1; $color='';?>
                        @if(count($cuentasDiners)>0)
                            <?php $i=1;$estado='';?>
                            @foreach($cuentasDiners as $k=>$v)
                                <tr {{$color}}>
                                    <td>{{$v->id}}</td>
                                    <td>{{$v->nombre}}</td>
                                    <td>{{$v->cedula}}</td>
                                    <td>{{$v->agente_actual}}</td>
                                    <td>{{$v->correo_agente}}</td>
                                    <td>{{$v->carga->nombre_campana}}</td>
                                    <td>{{$v->ciclo}}</td>
                                    <td>{{$v->producto}}</td>
                                    <td><?php $observaciones=observaciones::where('id_cuenta',$v->id)->get();?>
                                        @foreach($observaciones as $observacion)
                                            <?php $fecha=new DateTime($observacion->fecha);?>
                                            - {{$fecha->format('Y-m-d')}} || {{$observacion->observacion}}<br>
                                        @endforeach
                                    </td>
                                    <td>@if($v->estado_aprobado==0 && $v->estado_gestionado==0)
                                            <a href="#" class="btn btn-danger btn-xs">
                                                <span class="glyphicon glyphicon-remove"></span> Sin Gestionar
                                            </a>
                                        @elseif($v->estado_aprobado==0 && $v->estado_gestionado==1)
                                            <a href="{{url('/gestionAgenteX88S/'.$v->id)}}" class="btn btn-warning btn-xs">
                                                <span class="glyphicon glyphicon-record"></span> Pendiente de aprobación
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row tab-pane fade" id="visaconsolidado">
                <div class="col-md-12 col-lg-12">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="tab1_v">
                            <table id="visa" class="display" cellspacing="0" style="width: 100%" width="100%">
                                <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>NOMBRES CLIENTE</th>
                                    <th>CEDULA CLIENTE</th>
                                    <th>NOMBRE AGENTE</th>
                                    <th>CORREO AGENTE</th>
                                    <th>CAMPAÑA</th>
                                    <th>CICLO</th>
                                    <th>PRODUCTO</th>
                                    <th>OBSERVACIONES</th>
                                    <th>ESTADO</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;?>
                                @if(count($cuentasVisa)>0)
                                    @foreach($cuentasVisa as $k=>$v)
                                        <tr {{$color}}>
                                            <td>{{$v->id}}</td>
                                            <td>{{$v->nombre}}</td>
                                            <td>{{$v->cedula}}</td>
                                            <td>{{$v->agente_actual}}</td>
                                            <td>{{$v->correo_agente}}</td>
                                            <td>{{$v->carga->nombre_campana}}</td>
                                            <td>{{$v->ciclo}}</td>
                                            <td>{{$v->producto}}</td>
                                            <td><?php $observaciones=observaciones::where('id_cuenta',$v->id)->get();?>
                                                @foreach($observaciones as $observacion)
                                                    <?php $fecha=new DateTime($observacion->fecha);?>
                                                    - {{$fecha->format('Y-m-d')}} || {{$observacion->observacion}}<br>
                                                @endforeach
                                            </td>
                                            <td>@if($v->estado_aprobado==0 && $v->estado_gestionado==0)
                                                    <a href="#" class="btn btn-danger btn-xs">
                                                        <span class="glyphicon glyphicon-remove"></span> Sin Gestionar
                                                    </a>
                                                @elseif($v->estado_aprobado==0 && $v->estado_gestionado==1)
                                                    <a href="{{url('/gestionAgenteX88S/'.$v->id)}}" class="btn btn-warning btn-xs">
                                                        <span class="glyphicon glyphicon-record"></span> Pendiente de aprobación
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row tab-pane fade" id="discoverconsolidado">
                <div class="col-md-12 col-lg-12">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="tab1_dis">
                            <table id="discover" class="display" cellspacing="0" style="width: 100%" width="100%">
                                <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>NOMBRES CLIENTE</th>
                                    <th>CEDULA CLIENTE</th>
                                    <th>NOMBRE AGENTE</th>
                                    <th>CORREO AGENTE</th>
                                    <th>CAMPAÑA</th>
                                    <th>CICLO</th>
                                    <th>PRODUCTO</th>
                                    <th>OBSERVACIONES</th>
                                    <th>ESTADO</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;?>
                                @if(count($cuentasDiscover)>0)
                                    @foreach($cuentasDiscover as $k=>$v)
                                        <tr {{$color}}>
                                            <td>{{$v->id}}</td>
                                            <td>{{$v->nombre}}</td>
                                            <td>{{$v->cedula}}</td>
                                            <td>{{$v->agente_actual}}</td>
                                            <td>{{$v->correo_agente}}</td>
                                            <td>{{$v->carga->nombre_campana}}</td>
                                            <td>{{$v->ciclo}}</td>
                                            <td>{{$v->producto}}</td>
                                            <td><?php $observaciones=observaciones::where('id_cuenta',$v->id)->get();?>
                                                @foreach($observaciones as $observacion)
                                                    <?php $fecha=new DateTime($observacion->fecha);?>
                                                    - {{$fecha->format('Y-m-d')}} || {{$observacion->observacion}}<br>
                                                @endforeach
                                            </td>
                                            <td>@if($v->estado_aprobado==0 && $v->estado_gestionado==0)
                                                    <a href="#" class="btn btn-danger btn-xs">
                                                        <span class="glyphicon glyphicon-remove"></span> Sin Gestionar
                                                    </a>
                                                @elseif($v->estado_aprobado==0 && $v->estado_gestionado==1)
                                                    <a href="{{url('/gestionAgenteX88S/'.$v->id)}}" class="btn btn-warning btn-xs">
                                                        <span class="glyphicon glyphicon-record"></span> Pendiente de aprobación
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection