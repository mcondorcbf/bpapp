@extends('layouts.appBmi')
@section('scripts')

    <link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#listaHoy').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 0, "desc" ]],
                "lengthMenu": [[-1,100,50,20,10], ["All",100,50,20,10]]
            } );
            $('#lista').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 0, "desc" ]],
                "lengthMenu": [[-1,100,50,20,10], ["All",100,50,20,10]]
            } );
        } );


        function marcar(obj,chk) {
            elem=obj.getElementsByTagName('input');
            for(i=0;i<elem.length;i++)
                elem[i].checked=chk.checked;
        }

        ( function($) {
            function iframeModalOpen(){

                $('.modalButton').on('click', function(e) {
                    var src = $(this).attr('data-src');
                    var width = $(this).attr('data-width') || 640;
                    var height = $(this).attr('data-height') || 360;

                    var allowfullscreen = $(this).attr('data-video-fullscreen');


                    $("#myModal iframe").attr({
                        'src': src,
                        'height': height,
                        'width': width,
                        'allowfullscreen':''
                    });
                });


                $('#myModal').on('hidden.bs.modal', function(){
                    $(this).find('iframe').html("");
                    $(this).find('iframe').attr("src", "");
                });
            }
            $(document).ready(function(){
                iframeModalOpen();
            });
        } ) ( jQuery );
    </script>

@endsection
@section('content')
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ACTUALIZAR</h5>
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

    <div align="center">

        <form role="form" method="post" action="{{url('/actualizarClientes')}}">
            {{ csrf_field() }}
            <button class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span> Actualizar clientes</button>
        </form>
    </div>
    <hr>

    <div class="row">
        <div class="form-group">
                <div class="tab-content tab-content-border" style="font-size: 11px">
                    <div class="tab-pane fade active in">
                        <div class="col-md-12 col-lg-12 panel panel-success">
                            <div class="panel-heading"><strong>CONSOLIDADO CLIENTES HOY {{$fecha}}</strong></div>
                            <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/') }}" id="consolidar" style="font-size: 11px">
                            {{ csrf_field() }}
                            <!-- Nav tabs -->
                                <div>
                                    <table class="table table-hover table-striped display" id="listaHoy" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>CEDULA</th>
                                            <th>NOMBRES</th>
                                            <th>DIRECCION CITA</th>
                                            <th>EDAD</th>
                                            <th>TELEFONO</th>
                                            <th>ESTADO CIVIL</th>
                                            <th>PROFESION</th>
                                            <th>SALARIO</th>
                                            <th>EMPRESA</th>
                                            <th>CARGO</th>
                                            <th>FECHA CITA</th>
                                            <th>HORA CITA</th>
                                            <th>RANKING</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1;$result='';?>
                                        @foreach($clientesHoy as $k=>$v)
                                            <tr>
                                                <td>
                                                    @if(strlen($v->cedula_cliente)==9)
                                                        <?php $cedula='0'.$v->cedula_cliente;?>
                                                    @else
                                                        <?php $cedula=$v->cedula_cliente;?>
                                                    @endif
                                                    <button type="button" class="modalButton btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$cedula)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="">{{$cedula}}</button>
                                                </td>
                                                <td>{{$v->nombres}}</td>
                                                <td>{{$v->direccion_visita}}</td>
                                                <td>{{$v->edad}}</td>
                                                <td>{{$v->celular}}</td>
                                                <td>{{$v->estado_civil}}</td>
                                                <td>{{$v->cod_profesion}}</td>
                                                <td>${{$v->salario}}</td>
                                                <td>{{$v->empresa->nombre}}</td>
                                                <td>{{$v->cargo}}</td>
                                                <td>{{$v->fecha_cita}}</td>
                                                <td>{{$v->hora_cita}}</td>
                                                <td>{{$v->ranking_cliente->descripcion}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <?php print_r($result);?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade active in">
                        <div class="col-md-12 col-lg-12 panel panel-primary">
                            <div class="panel-heading "><strong>CONSOLIDADO CLIENTES MAÑANA {{$nuevafecha}}</strong></div>
                            <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/') }}" id="consolidar" style="font-size: 11px">
                            {{ csrf_field() }}
                            <!-- Nav tabs -->
                                <div>
                                    <table class="table table-hover table-striped display" id="lista" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>CEDULA</th>
                                            <th>NOMBRES</th>
                                            <th>DIRECCION CITA</th>
                                            <th>EDAD</th>
                                            <th>TELEFONO</th>
                                            <th>ESTADO CIVIL</th>
                                            <th>PROFESION</th>
                                            <th>SALARIO</th>
                                            <th>EMPRESA</th>
                                            <th>CARGO</th>
                                            <th>FECHA CITA</th>
                                            <th>HORA CITA</th>
                                            <th>RANKING</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1;$result='';?>
                                        @foreach($clientesManana as $k=>$v)
                                            <tr>
                                                <td>
                                                    @if(strlen($v->cedula_cliente)==9)
                                                        <?php $cedula='0'.$v->cedula_cliente;?>
                                                    @else
                                                        <?php $cedula=$v->cedula_cliente;?>
                                                    @endif
                                                    <button type="button" class="modalButton btn btn-primary btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$cedula)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="">{{$cedula}}</button>
                                                </td>
                                                <td>{{$v->nombres}}</td>
                                                <td>{{$v->direccion_visita}}</td>
                                                <td>{{$v->edad}}</td>
                                                <td>{{$v->celular}}</td>
                                                <td>{{$v->estado_civil}}</td>
                                                <td>{{$v->cod_profesion}}</td>
                                                <td>${{$v->salario}}</td>
                                                <td>{{$v->empresa->nombre}}</td>
                                                <td>{{$v->cargo}}</td>
                                                <td>{{$v->fecha_cita}}</td>
                                                <td>{{$v->hora_cita}}</td>
                                                <td>{{$v->ranking_cliente->descripcion}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <?php print_r($result);?>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection