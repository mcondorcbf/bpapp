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
            "order": [[ 5, "desc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        } );
        $('#lista_a').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        } );
        $('#listaf').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        } );
        $('#listafm').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
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
            'width': width,
            'allowfullscreen':''
        });



        $('#myModal').on('hidden.bs.modal', function(){
            $(this).find('iframe').html("");
            $(this).find('iframe').attr("src", "");
        });

    }
</script>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:830px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="body">
                <iframe class="embed-responsive-item" src="" id="frame" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="font-size: 11px">
        <div class="panel with-nav-tabs panel-warning">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a href="{{url('/historialCitas')}}">HISTORIAL CITAS AGENCIA</a></li>
                    <li class="active"><a href="{{url('/historialCitasP')}}">HISTORIAL CITAS PROPIAS</a></li>
                    <li class="nav-item"><a href="{{url('/historialCitasM')}}">HISTORIAL CITAS MANUALES AGENCIA</a></li>
                    <input type="hidden" id="reporte_nro" value="1">
                </ul>
            </div>
            <div class="panel-body">
                <div class="content">
                    <div class="panel with-nav-tabs panel-default">
                        <div class="panel-heading">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#primer_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(1);">SEGUIMIENTO <span class="badge">{{count($citas_propias_seguimiento)}}</span></a></li>
                                <li class="nav-item"><a href="#segundo_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(2);">FINALIZADAS <span class="badge">{{count($citas_propias_finalizadas)}}</span></a></li>
                                <input type="hidden" id="reporte_nro" value="1">
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="tab-content tab-content-border" >
                                    <div class="tab-pane fade active in" id="primer_reporte">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-hover table-striped display" id="listaf" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Asesor</th>
                                                <th>Cliente</th>
                                                <th>Teléfono</th>
                                                <th>Dirección</th>
                                                <th>Fecha y hora de cita agendada</th>
                                                <th>Fecha de gestión</th>
                                                <th>Fecha próxima gestión</th>
                                                <th>Observación</th>
                                                <th>Estado</th>
                                                </thead>
                                                <tbody id="data2">
                                                <?php $i=1;?>
                                                @foreach($citas_propias_seguimiento as $k)
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->asesores->nombres}}
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->nombres}}
                                                        </td>
                                                        <td>
                                                            {{isset($k->citaHistorial->telefono)? $k->citaHistorial->telefono: ''}}
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->direccion_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->fecha_cita}} {{$k->citaHistorial->hora_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->fecha_visita}}
                                                        </td>
                                                        <td>
                                                            {{$k->fecha_proxima_visita}}
                                                        </td>
                                                        <td>
                                                            {{$k->observaciones}}
                                                        </td>
                                                        <td>
                                                            @if($k->accion->peso==100)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @elseif($k->accion->peso==50)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @elseif($k->accion->peso<50)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <?php $i++?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade active " id="segundo_reporte">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-hover table-striped display" id="listafm" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Asesor</th>
                                                <th>Cliente</th>
                                                <th>Teléfono</th>
                                                <th>Dirección</th>
                                                <th>Fecha y hora de cita agendada</th>
                                                <th>Fecha de gestión</th>
                                                <th>Observación</th>
                                                <th>Estado</th>
                                                </thead>
                                                <tbody id="data2">
                                                <?php $i=1;?>
                                                @foreach($citas_propias_finalizadas as $k)
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->asesores->nombres}}
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->nombres}}
                                                        </td>
                                                        <td>
                                                            {{isset($k->citaHistorial->telefono)? $k->citaHistorial->telefono: ''}}
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->direccion_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->fecha_cita}} {{$k->citaHistorial->hora_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->fecha_visita}}
                                                        </td>
                                                        <td>
                                                            {{$k->observaciones}}
                                                        </td>
                                                        <td>
                                                            @if($k->accion->peso==100)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @elseif($k->accion->peso==50)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @elseif($k->accion->peso<50)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
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