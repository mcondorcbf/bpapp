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
                "order": [[ 0, "asc" ]],
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
            $('#listaf_f').DataTable( {
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
            var width = 780;
            var height =500;

            var allowfullscreen = $(this).attr('data-video-fullscreen');

            $("#myModal iframe").attr({
                'src': src,
                'height': 500,
                'width': 780,
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


<div id="resultados"></div>
<div class="row">
        <div class="col-md-12" style="font-size: 11px">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <div class="panel-group">
                        <div class="panel panel-warning">
                            <div class="panel-heading"><strong>Historial citas propias</strong></div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped display" id="lista_p" cellspacing="0" width="100%">
                                    <thead>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Fecha y hora de Cita</th>
                                    <th>Fecha de gestión</th>
                                    <th>Fecha próxima gestión</th>
                                    <th>Observación</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody id="data">
                                    <?php $i=1;?>
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
                                                @if($k->estado_aprobado==3)
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
                                                        @if($gestion->accion->peso==100)
                                                            <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                        @elseif($gestion->accion->peso==50)
                                                            <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                            <br>
                                                            <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                        @elseif($gestion->accion->peso==40)
                                                            <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                            <br>
                                                            <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                        @elseif($gestion->accion->peso<40)
                                                            <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>
                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>
                                                        <a href="#" class="modalButton btn btn-danger btn-xs">Anulado</a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endif<?php $i++?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-success">
                            <div class="panel-heading"><strong>Historial citas agencia seguimiento</strong></div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped display" id="listaf" cellspacing="0" width="100%">
                                    <thead>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Teléfono</th>
                                    <th>Empresa</th>
                                    <th>Dirección</th>
                                    <th>Fecha de visita</th>
                                    <th>Fecha de gestión</th>
                                    <th>Fecha próxima gestión</th>
                                    <th>Observación</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody id="data2">
                                    <?php $i=1;?>
                                    @foreach($citas_historial as $k)
                                        <tr>
                                            <td>
                                                <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$k->id_gestion}}</button>
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
                                            <?php $gestion=\App\bmi\tbl_gestiones::find($k->id_gestion);?>
                                            @if($gestion)
                                                <td>
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
                                                        <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                    @elseif($gestion->accion->peso==50)
                                                        <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                    @elseif($gestion->accion->peso<50)
                                                        <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                    @endif
                                                </td>
                                            @else
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                                <td>

                                                </td>
                                            @endif
                                        </tr>
                                        <?php $i++?>
                                    @endforeach
                                    </tbody>
                                </table>
                                </div>
                        </div>

                        <div class="panel panel-danger">
                            <div class="panel-heading"><strong>Historial citas agencia finalizadas</strong></div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped display" id="listaf_f" cellspacing="0" width="100%">
                                    <thead>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Teléfono</th>
                                    <th>Empresa</th>
                                    <th>Dirección</th>
                                    <th>Fecha de visita</th>
                                    <th>Fecha de gestión</th>
                                    <th>Observación</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody id="data2">
                                    <?php $i=1;?>
                                    @foreach($citas_historial_finalizadas as $k)
                                        @if($k->estado==2)
                                            <tr>
                                                <td>
                                                    <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
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
                                                <td><?php $gestion=\App\bmi\tbl_gestiones::where('id_cita',$k->id_cita_orig)->first(); ?>
                                                    {{$gestion->fecha_visita}}
                                                </td>
                                                <td>
                                                    {{$gestion->observaciones}}
                                                </td>
                                                <td>
                                                    @if($gestion->accion->peso==100)
                                                        <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                    @elseif($gestion->accion->peso==50)
                                                        <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                    @elseif($gestion->accion->peso<50)
                                                        <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
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