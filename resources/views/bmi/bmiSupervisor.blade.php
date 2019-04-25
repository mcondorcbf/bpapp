<?php use App\Role;?>
@extends('layouts.appBmi')
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="/js/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#lista_p').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 8, "desc" ]],
                "lengthMenu": [[10,20,50], [10,20,50]]
            } );
            $('#lista_a').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 6, "asc" ]],
                "lengthMenu": [[10,20,50], [10,20,50]]
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
                "lengthMenu": [[10,20,50], [10,20,50]]
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
    <div class="modal-dialog" role="document" style="width: 830px">
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
        <div class="col-md-12" style="font-size:11px">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <div class="panel-group">

                        <div class="panel panel-warning">
                            <div class="panel-heading"><strong>Citas propias</strong></div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped display" id="lista_p" cellspacing="0" width="100%">
                                    <thead>
                                    <th>#</th>
                                    <th>Asesor</th>
                                    <th>Cliente</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Fecha Visita</th>
                                    <th>Hora Visita</th>
                                    <th>Oservación</th>
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
                                                {{$k->asesores->nombres}}
                                            </td>
                                            <td>
                                                {{$k->nombres}}
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
                                                    <a href="{{url('/gestionP/'.$k->id_cita)}}" class="btn btn-primary btn-xs">Gestionar</a>
                                                @endif
                                                @if($k->estado_aprobado==2)
                                                    <a href="#" class="modalButton btn btn-danger btn-xs">Anulado</a>
                                                @endif
                                                @if($k->estado_aprobado==3)
                                                        <div class="btn btn-info btn-xs">Pendiente de aprobación</div>
                                                        <a href="{{url('dcita/1/'.$k->asesores->cedula_asesor.'/'.$k->id_cita)}}" class="modalButton btn btn-xs btn-primary">Aprobar</a>  <a href="{{url('dcitaAlert/0/'.$k->asesores->cedula_asesor.'/'.$k->id_cita)}}" class="modalButton btn btn-xs btn-danger">Anular</a>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $i++?>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Citas agendadas agencia</strong></div>
                            <table class="table table-hover table-striped display" id="lista_a" cellspacing="0" width="100%">
                                <thead>
                                <th>#</th>
                                <th>Asesor</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Fecha Visita</th>
                                <th>Hora Visita</th>
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
                                            {{$k->asesores['nombres']}}
                                        </td>
                                        <td>
                                            {{$k->clientes['nombres']}}
                                        </td>
                                        <td>
                                            {{isset($k->telefono)? $k->telefono: ''}}
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
                                            @if(\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->count()>0)
                                                <?php $gestion=\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->first(); ?>
                                                @if($gestion->accion->peso==50)
                                                    <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                    <br><button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})"> {{$gestion->accion->descripcion}}</button>
                                                @elseif($gestion->accion->peso<50)
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
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Citas seguimiento agencia</strong></div>
                            <table class="table table-hover table-striped display" id="lista_s" cellspacing="0" width="100%">
                                <thead>
                                <th>#</th>
                                <th>Asesor</th>
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
                                @foreach($citas as $k)
                                    @if($k->estado==1)
                                        @if(\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->count()>0)
                                        <tr>
                                            <td>
                                                <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                            </td>
                                            <td>
                                                {{isset($k->asesores->nombres)? $k->asesores->nombres : ''}}
                                            </td>
                                            <td>
                                                {{$k->clientes['nombres']}}
                                            </td>
                                            <td>
                                                {{isset($k->telefono)? $k->telefono: ''}}
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
                                                @if(\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->count()>0)
                                                    <?php $gestion=\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->first(); ?>
                                                {{$gestion->observaciones}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->count()>0)
                                                    <?php $gestion=\App\bmi\tbl_gestiones::where('id_gestion',$k->id_gestion)->first(); ?>
                                                    @if($gestion->accion->peso==50)
                                                        <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                        <br><button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})"> {{$gestion->accion->descripcion}}</button>
                                                    @elseif($gestion->accion->peso<50)
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
                            <table class="table table-hover table-striped display" id="listaf" cellspacing="0" width="100%">
                                <thead>
                                <th>#</th>
                                <th>Asesor</th>
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Fecha y hora de Cita</th>
                                <th>Fecha de gestión</th>
                                <th>Fecha próxima gestión</th>
                                <th>Observación</th>
                                <th>Estado</th>
                                </thead>
                                <tbody id="data2">
                                <?php $i=1;?>
                                @foreach($citas_historial as $k)
                                    @if($k->estado!=0)
                                        <tr>
                                            <td>
                                                <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                            </td>
                                            <td>
                                                {{isset($k->asesores->nombres)? $k->asesores->nombres : ''}}
                                            </td>
                                            <td>
                                                {{$k->nombres}}
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
                                                    <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                @elseif($gestion->accion->peso==50)
                                                    <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                @elseif($gestion->accion->peso<50)
                                                    <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif<?php $i++?>
                                @endforeach
                                @foreach($citasPropiasHistorial as $k)
                                    @if($k->estado==2)
                                        <tr>
                                            <td>
                                                <button type="button" class="modalButton btn btn-info btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-target="#myModal" data-video-fullscreen="">{{$i}}</button>
                                            </td>
                                            <td>
                                                {{isset($k->asesores->nombres)? $k->asesores->nombres : ''}}
                                            </td>
                                            <td>
                                                {{$k->nombres}}
                                            </td>

                                            <td>
                                                {{$k->direccion_cita}}
                                            </td>
                                            <td>
                                                {{$k->fecha_cita}}
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
                                                    <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                @elseif($gestion->accion->peso==50)
                                                    <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
                                                @elseif($gestion->accion->peso<50)
                                                    <button type="button" class="modalButton1{{$gestion->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionPShow/'.$gestion->id_gestion)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$gestion->id_gestion}})">{{$gestion->accion->descripcion}}</button>
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


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
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