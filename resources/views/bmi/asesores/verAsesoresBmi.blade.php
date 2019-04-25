@extends('layouts.appBmi')
@section('scripts')
<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="/js/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#lista').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[-1,10,20,50], ["All",10,20,50]]
        } );

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
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
<div class="row">
    <div class="form-group panel-default">
            <div class="tab-content tab-content-border" style="font-size: 12px">
                <div class="tab-pane fade active in" id="dinersconsolidado">
                    <div class="col-md-12 col-lg-12">
                        <div class="panel-heading " style="background-color: #3a77bf; border-color: #000; color: #fff;">LISTA DE ASESORES</div>

                        <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/consolidarTarjetas') }}" id="consolidar" style="font-size: 11px">
                        {{ csrf_field() }}
                        <!-- Nav tabs -->
                            <div>
                                <table class="table table-hover table-striped display" id="lista" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NOMBRES</th>
                                        <th>CEDULA</th>
                                        <th>CELULAR</th>
                                        <th>EMAIL PERSONAL</th>
                                        <th>EMAIL CORPORATIVO</th>
                                        <th>FECHA DE CONTRATO</th>
                                        <th>FECHA DE NACIMIENTO</th>
                                        <th>NIVEL</th>
                                        <th>ESTADO</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1;$result='';?>@foreach($asesores as $k=>$v)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$v->nombres}}</td>
                                        <td>{{$v->cedula_asesor}}</td>
                                        <td>{{$v->celular}}</td>
                                        <td>{{$v->email_personal}}</td>
                                        <td>{{$v->email_corporativo}}</td>
                                        <td>{{$v->fecha_contrato}}</td>
                                        <td>{{$v->fecha_nacimiento}}</td>
                                        <td>{{$v->ranking_asesor->nivel}}</td>
                                        <td>@if($v->estado==1)
                                            <a href="{{url('desactivarAsesorBmi/'.$v->cedula_asesor)}}" class="btn btn-success btn-xs" id="getSelected">Activo</a></td>
                                        @else
                                            <a href="{{url('activarAsesorBmi/'.$v->cedula_asesor)}}" class="btn btn-warning btn-xs" id="getSelected">Inactivo</a></td>
                                        @endif
                                    </tr><?php $i++;?>
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