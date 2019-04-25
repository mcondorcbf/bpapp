<?php use App\Role;?>
@extends('layouts.appBmi')
@section('scripts')
    <link rel="stylesheet" href="vendor/bootstrap-select/dist/css/bootstrap-select.css">
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.js"></script>
    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#lista_p').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 4, "asc" ]],
                "lengthMenu": [[10,20,50], [10,20,50]]
            } );
            $('#lista_a').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[15,20,50], [15,20,50]]
            } );
            $('#lista_s').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 5, "asc" ]],
                "lengthMenu": [[10,20,50], [10,20,50]]
            } );
            $('#listaf').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[-1,100,50,20,10], ["All",100,50,20,10]]
            } );
        } );

        function  alerta(id) {


            var src = $('.modalButton'+id).attr('data-src');
            var width = $('.modalButton'+id).attr('data-width') || 780;
            var height = $('.modalButton'+id).attr('data-height') || 500;

            var allowfullscreen = $(this).attr('data-video-fullscreen');

            $("#myModal iframe").attr({
                'src': src,
                'height': height,
                'width': '100%',
                'allowfullscreen':''
            });


            $('#myModal').on('hidden.bs.modal', function(){
                $(this).find('iframe').html("");
                $(this).find('iframe').attr("src", "");
            });
        }
    </script>
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
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
@endsection
@section('content')
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="body">
                <iframe class="embed-responsive-item" src="" id="frame" frameborder="0" scrolling="yes"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<div id="resultados"></div>
<div class="row">
<div class="col-md-12" style="font-size: 11px">
    <div class="panel panel-default">
        <div class="panel-heading">Dashboard</div>
        <div class="panel-body">
            <div class="panel-group">
                <div class="panel with-nav-tabs panel-warning">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#primero" data-toggle="tab" onclick="$('#reporte_nro').val(1);">CITAS PROPIAS SEGUIMIENTO <span class="badge"></span></a></li>
                            <!-- <li class="nav-item"><a href="#segundo" data-toggle="tab" onclick="$('#reporte_nro').val(2);">CITAS PROPIAS FINALIZADAS <span class="badge"></span></a></li> -->
                            <input type="hidden" id="reporte_nro" value="1">
                        </ul>
                    </div>

                    <div class="panel-body">
                        <div class="content">
                            <div class="form-group">
                                <div class="tab-content tab-content-border" >
                                    <div class="tab-pane fade active in" id="primero">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-hover table-striped display" id="lista_p" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Cliente</th>
                                                <th>Teléfono</th>
                                                <th>Dirección</th>
                                                <th>Fecha Visita</th>
                                                <th>Hora Visita</th>
                                                <th>Observación</th>
                                                <th>Estado</th>
                                                </thead>
                                                <tbody id="data">
                                                <?php $i=1;?>
                                                @foreach($citasPropias as $k)
                                                    <tr>
                                                        <td>
                                                            {{$i}}
                                                        </td>
                                                        <td>
                                                            <a href="{{url('/clienteP/'.$k->id_cita)}}" class="modalButton btn btn-success btn-xs"> <span class="glyphicon glyphicon-edit"></span> {{$k->nombres}}</a>
                                                        </td>
                                                        <td>
                                                            {{$k->telefono}}
                                                        </td>
                                                        <td>
                                                            {{$k->direccion_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->fecha_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->hora_cita}}
                                                        </td>
                                                        <td>
                                                            @if(\App\bmi\tbl_gestiones_propias::where('id_gestion',$k->id_gestion)->count()>0)
                                                                <?php $gestion=\App\bmi\tbl_gestiones_propias::where('id_gestion',$k->id_gestion)->first(); ?>
                                                                {{$gestion->observaciones}}
                                                            @else
                                                                {{$k->observacion}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($k->estado_aprobado==1)
                                                                @if(\App\bmi\tbl_gestiones_propias::where('id_gestion',$k->id_gestion)->count()>0)
                                                                    <?php $gestion=\App\bmi\tbl_gestiones_propias::where('id_gestion',$k->id_gestion)->first(); ?>
                                                                    @if($gestion->accion->necesita_calendario==1 && $gestion->accion->peso==50)
                                                                        <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                                        <br><button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                                    @elseif($gestion->accion->necesita_calendario==1 && $gestion->accion->peso<50)
                                                                        <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                                        <br><button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                                    @endif
                                                                @endif
                                                                <a href="{{url('/gestionP/'.$k->id_cita)}}" class="modalButton btn btn-primary btn-xs">Gestionar</a>
                                                            @endif
                                                            @if($k->estado_aprobado==2)
                                                                <a href="#" class="modalButton btn btn-danger btn-xs">Anulado</a>
                                                            @endif
                                                            @if($k->estado_aprobado==3)
                                                                <div class="btn btn-info btn-xs">Pendiente de aprobación</div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <?php $i++?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="segundo">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-hover table-striped display" id="lista_p" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Cliente</th>
                                                <th>Teléfono</th>
                                                <th>Dirección</th>
                                                <th>Fecha Visita</th>
                                                <th>Hora Visita</th>
                                                <th>Observación</th>
                                                <th>Estado</th>
                                                </thead>
                                                <tbody id="data">
                                                <?php $i=1;?>
                                                @foreach($citasPropias as $k)
                                                    <tr>
                                                        <td>
                                                            {{$i}}
                                                        </td>
                                                        <td>
                                                            <a href="{{url('/clienteP/'.$k->id_cita)}}" class="modalButton btn btn-success btn-xs"> <span class="glyphicon glyphicon-edit"></span> {{$k->nombres}}</a>
                                                        </td>
                                                        <td>
                                                            {{$k->telefono}}
                                                        </td>
                                                        <td>
                                                            {{$k->direccion_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->fecha_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->hora_cita}}
                                                        </td>
                                                        <td>
                                                            @if(\App\bmi\tbl_gestiones_propias::where('id_gestion',$k->id_gestion)->count()>0)
                                                                <?php $gestion=\App\bmi\tbl_gestiones_propias::where('id_gestion',$k->id_gestion)->first(); ?>
                                                                {{$gestion->observaciones}}
                                                            @else
                                                                {{$k->observacion}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($k->estado_aprobado==1)
                                                                @if(\App\bmi\tbl_gestiones_propias::where('id_gestion',$k->id_gestion)->count()>0)
                                                                    <?php $gestion=\App\bmi\tbl_gestiones_propias::where('id_gestion',$k->id_gestion)->first(); ?>
                                                                    @if($gestion->accion->necesita_calendario==1 && $gestion->accion->peso==50)
                                                                        <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                                        <br><button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                                    @elseif($gestion->accion->necesita_calendario==1 && $gestion->accion->peso<50)
                                                                        <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                                        <br><button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                                    @endif
                                                                @endif
                                                                <a href="{{url('/gestionP/'.$k->id_cita)}}" class="modalButton btn btn-primary btn-xs">Gestionar</a>
                                                            @endif
                                                            @if($k->estado_aprobado==2)
                                                                <a href="#" class="modalButton btn btn-danger btn-xs">Anulado</a>
                                                            @endif
                                                            @if($k->estado_aprobado==3)
                                                                <div class="btn btn-info btn-xs">Pendiente de aprobación</div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <?php $i++?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>Citas agendadas agencia</strong></div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped display" id="lista_a" cellspacing="0" width="100%">
                            <thead>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Teléfono</th>
                            <th>Empresa</th>
                            <th>Salario</th>
                            <th>Dirección</th>
                            <th>Observación</th>
                            <th>Fecha y hora cita</th>
                            <th>Estado</th>
                            </thead>
                            <tbody id="data">
                            <?php $i=1;?>
                            @foreach($citas as $k)
                                @if($k->estado==1)
                                @if(\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->count()==0)
                            <tr>
                                <td>
                                    <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                </td>
                                <td>
                                    {{$k->nombres}}
                                </td>
                                <td>
                                    {{isset($k->telefono)? $k->telefono: ''}}
                                </td>
                                <td>
                                    {{isset($k->clientes->empresa->nombre)? $k->clientes->empresa->nombre: ''}}
                                </td>
                                <td>
                                    ${{$k->clientes->salario}}
                                </td>
                                <td>
                                    {{$k->direccion_cita}}
                                </td>
                                <td>
                                    @if(\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->count()>0)
                                        <?php $gestion=\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->first(); ?>
                                        {{$gestion->observaciones}}
                                    @else
                                        {{$k->observacion}}
                                    @endif
                                </td>
                                <td>
                                    {{$k->fecha_cita}} {{$k->hora_cita}}
                                </td>
                                <td>@if(\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->count()>0)
                                        <?php $gestion=\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->first(); ?>
                                            @if($gestion->accion->necesita_calendario==1 && $gestion->accion->peso==50)
                                                <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                <br><button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                            @elseif($gestion->accion->necesita_calendario==1 && $gestion->accion->peso<50)
                                                <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                <br><button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                            @endif
                                    @endif
                                        <a href="{{url('/gestion/'.$k->id_cita)}}" class="modalButton btn btn-primary btn-xs">Gestionar</a>
                                </td>
                            </tr>
                                @endif
                                @endif<?php $i++?>
                            @endforeach

                            </tbody>
                        </table>
                        </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>Citas seguimiento agencia</strong></div>
                    <table class="table table-hover table-striped display" id="lista_s" cellspacing="0" width="100%">
                        <thead>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Empresa</th>
                        <th>Dirección</th>
                        <th>Fecha Hora Visita</th>
                        <th>Observación</th>
                        <th>Estado</th>
                        </thead>
                        <tbody id="data">
                        <?php $i=1;?>
                        @foreach($citas as $k)
                            @if($k->estado==1)
                                @if(\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->count()>0)
                                    <tr>
                                        <td>
                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                        </td>
                                        <td>
                                            {{$k->nombres}}
                                        </td>
                                        <td>
                                            {{isset($k->telefono)? $k->telefono: ''}}
                                        </td>
                                        <td>
                                            {{isset($k->clientes->empresa->nombre)? $k->clientes->empresa->nombre: ''}}
                                        </td>
                                        <td>
                                            {{$k->direccion_cita}}
                                        </td>
                                        <td>
                                            {{$k->fecha_cita}} {{$k->hora_cita}}
                                        </td>
                                        <td>
                                            @if(\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->count()>0)
                                                <?php $gestion=\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->first(); ?>
                                                {{$gestion->observaciones}}
                                            @else
                                                {{$k->observacion}}
                                            @endif
                                        </td>
                                        <td>@if(\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->count()>0)
                                                <?php $gestion=\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->first(); ?>
                                                @if($gestion->accion->necesita_calendario==1 && $gestion->accion->peso==50)
                                                    <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                    <br><button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                @elseif($gestion->accion->necesita_calendario==1 && $gestion->accion->peso<50)
                                                    <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                    <br><button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                @endif
                                            @endif
                                            <a href="{{url('/gestion/'.$k->id_cita)}}" class="modalButton btn btn-primary btn-xs">Gestionar</a>
                                        </td>
                                    </tr>

                                    <?php $i++?>
                                @endif
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="panel panel-success">
                    <div class="panel-heading"><strong>Citas gestionadas hoy {{date('d-m-Y')}}</strong></div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped display" id="listaf" cellspacing="0" width="100%">
                            <thead>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Fecha y hora cita</th>
                            <th>Fecha de gestión</th>
                            <th>Fecha próxima cita</th>
                            <th>Observación</th>
                            <th>Estado</th>
                            </thead>
                            <tbody id="data2">
                            <?php $i=1;?>
                            @foreach($citas_historial as $k)
                                @if($k->estado==2)
                                <tr>
                                    <td>
                                        <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                    </td>
                                    <td>
                                        {{$k->nombres}}
                                    </td>
                                    <td>
                                        {{isset($k->telefono)? $k->telefono: ''}}
                                    </td>
                                    <td>
                                        {{$k->direccion_cita}}
                                    </td>
                                    <td>
                                        {{$k->fecha_cita}} {{$k->hora_cita}}
                                    </td>
                                    <td><?php $gestion=\App\bmi\tbl_gestiones::where('id_cita',$k->id_cita_orig)->first(); ?>
                                        {{$gestion->fecha_visita}}
                                    </td>
                                    <td>
                                        {{$gestion->fecha_proxima_visita}}
                                    </td>
                                    <td>
                                        {{$gestion->observaciones}}
                                    </td>
                                    <td>
                                        @if($gestion->accion->peso==100)
                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$gestion->accion->descripcion}}</button>
                                        @elseif($gestion->accion->peso==50)
                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$gestion->accion->descripcion}}</button>
                                        @elseif($gestion->accion->peso<50)
                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$gestion->accion->descripcion}}</button>
                                        @endif
                                    </td>
                                </tr>
                                @endif<?php $i++?>
                            @endforeach
                            @foreach($citasPropiasHistorial as $k)
                                @if($k->estado==2)
                                    <tr>
                                        <td>
                                            {{$i}}
                                        </td>
                                        <td>
                                            {{$k->nombres}}
                                        </td>
                                        <td>
                                            {{isset($k->telefono)? $k->telefono: ''}}
                                        </td>
                                        <td>
                                            {{$k->direccion_cita}}
                                        </td>
                                        <td>
                                            {{$k->fecha_cita}} {{$k->hora_cita}}
                                        </td>
                                        <td><?php $gestion=\App\bmi\tbl_gestiones_propias::where('id_cita_propia',$k->id_cita_orig)->first(); ?>
                                            {{$gestion->fecha_visita}}
                                        </td>
                                        <td>
                                            {{$gestion->fecha_proxima_visita}}
                                        </td>
                                        <td>
                                            {{$gestion->observaciones}}
                                        </td>
                                        <td>
                                            <div class="btn btn-info btn-xs">Cita propia</div>
                                            @if($gestion->accion->peso==100)
                                                <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$gestion->accion->descripcion}}</button>
                                            @elseif($gestion->accion->peso>=40 && $gestion->accion->peso<=50)
                                                <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$gestion->accion->descripcion}}</button>
                                            @elseif($gestion->accion->peso<40)
                                                <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$gestion->accion->descripcion}}</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endif<?php $i++?>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> - </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
function reporte(id_carga) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        data: "id_carga="+id_carga ,
        url:   "{{url('/reporteIvr')}}"+id_carga,
        type:  'get',
        beforeSend: function () {
            $("#resultado").html("Procesando, espere por favor...");
        },
        success:  function (response) {
            document.location=response;
        }
    });
}
function realizaProceso(valorCaja1, valorCaja2){
    var parametros = {
        "valorCaja1" : valorCaja1,
        "valorCaja2" : valorCaja2
    };
    $.ajax({
        data:  parametros,
        url:   'ejemplo_ajax_proceso.php',
        type:  'post',
        beforeSend: function () {
            $("#resultado").html("Procesando, espere por favor...");
        },
        success:  function (response) {
            $("#resultado").html(response);
        }
    });
}
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.button').click(function(){
            //Añadimos la imagen de carga en el contenedor
            $('#content').html('<div><img src="images/loading.gif"/></div>');
            var page = $(this).attr('data');
            var dataString = 'page='+page;
            $.ajax({
                type: "GET",
                url: "{{url('/nuevoIvr')}}",
                data: dataString,
                success: function(data) {
                    //Cargamos finalmente el contenido deseado
                    $('#content').fadeIn(1000).html(data);
                }
            });
        });
    });
</script>
@endsection