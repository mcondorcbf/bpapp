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
            $('#listafm').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
            } );
            $('#listafmn').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[-1,10,20,50], ["Todo",10,20,50]]
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


<div id="resultados"></div>
<div class="row">
        <div class="col-md-12" style="font-size: 11px">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <div class="panel-group">

                        <div class="panel panel-warning">
                            <div class="panel-heading"><strong>Historial citas manuales seguimiento</strong></div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped display" id="listafm" cellspacing="0" width="100%">
                                    <thead>
                                    <th>#</th>
                                    <th>Asesor</th>
                                    <th>Cliente</th>
                                    <th>Fecha de visita</th>
                                    <th>Hora de visita</th>
                                    <th>Observación</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody id="data2">
                                    <?php $i=1;?>
                                    @foreach($historialCitasManuales as $k)
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
                                            {{$k->fecha_cita}}
                                        </td>
                                        <td>
                                            {{$k->hora_cita}}
                                        </td>
                                        <td>
                                            {{$k->observacion}}
                                        </td>
                                        <td>
                                            @if($k->accion->peso==100)
                                                <button type="button" class="modalButton1{{$k->id_cita}} btn btn-success btn-xs" data-toggle="modal" data-target="#myModal" data-src="{{url('/gestionPmShow/'.$k->id_cita)}}" data-video-fullscreen="" onclick="alerta(1{{$k->id_cita}})">{{$k->accion->descripcion}}</button>
                                            @elseif($k->accion->necesita_calendario==1 && $k->accion->peso==50)
                                                <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                <button type="button" class="modalButton1{{$k->id_cita}} btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal" data-src="{{url('/gestionPmShow/'.$k->id_cita)}}" data-video-fullscreen="" onclick="alerta(1{{$k->id_cita}})">{{$k->accion->descripcion}}</button>
                                                <a href="{{url('/gestionPm/'.$k->id_cita)}}" class="btn btn-primary btn-xs">Gestionar</a>
                                            @elseif($k->accion->necesita_calendario==1 && $k->accion->peso>0 && $k->accion->peso<50)
                                                <div class="modalButton btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span> Seguimiento</div>
                                                <button type="button" class="modalButton1{{$k->id_cita}} btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal" data-src="{{url('/gestionPmShow/'.$k->id_cita)}}" data-video-fullscreen="" onclick="alerta(1{{$k->id_cita}})">{{$k->accion->descripcion}}</button>
                                                <a href="{{url('/gestionPm/'.$k->id_cita)}}" class="btn btn-primary btn-xs">Gestionar</a>
                                            @elseif($k->accion->peso==0)
                                                <button type="button" class="modalButton1{{$k->id_cita}} btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal" data-src="{{url('/gestionPmShow/'.$k->id_cita)}}" data-video-fullscreen="" onclick="alerta(1{{$k->id_cita}})">{{$k->accion->descripcion}}</button>
                                                <a href="{{url('/gestionPm/'.$k->id_cita)}}" class="btn btn-primary btn-xs">Gestionar</a>
                                            @endif
                                        </td>
                                    </tr>
                                        <?php $i++?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-success">
                            <div class="panel-heading"><strong>Historial citas manuales finalizadas</strong></div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped display" id="listafmn" cellspacing="0" width="100%">
                                    <thead>
                                    <th>#</th>
                                    <th>Asesor</th>
                                    <th>Cliente</th>
                                    <th>Fecha visitada</th>
                                    <th>Observación</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody id="data2">
                                    <?php $i=1;?>
                                    @foreach($historialCitasManualesFinalizadas as $k)
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
                                                {{$k->fecha_visitada}}
                                            </td>
                                            <td>
                                                {{$k->observacion}}
                                            </td>
                                            <td>
                                                @if($k->accion->peso==100)
                                                    <button type="button" class="modalButton1{{$k->id_cita}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionPmShow/'.$k->id_cita)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_cita}})">{{$k->accion->descripcion}}</button>

                                                @elseif($k->accion->peso==50)
                                                    <button type="button" class="modalButton1{{$k->id_cita}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionPmShow/'.$k->id_cita)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_cita}})">{{$k->accion->descripcion}}</button>

                                                @elseif($k->accion->peso<50)
                                                    <button type="button" class="modalButton1{{$k->id_cita}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionPmShow/'.$k->id_cita)}}" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_cita}})">{{$k->accion->descripcion}}</button>
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