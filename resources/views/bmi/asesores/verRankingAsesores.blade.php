@extends('layouts.appBmi')
@section('scripts')


    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#asesores').DataTable( {
                "order": [[ 2, "desc" ]]
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

    <div class="row">
        <div class="form-group panel panel-default">
            <div class="tab-content tab-content-border" style="font-size: 11px">
                <div class="tab-pane fade active in" id="dinersconsolidado">
                    <div class="col-md-12 col-lg-12">
                        <div class="panel-heading " style="background-color: #3a77bf; border-color: #000; color: #fff;">LISTA DE RANKING DE ASESORES</div>
                        <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/consolidarTarjetas') }}" id="consolidar" style="font-size: 11px">
                        {{ csrf_field() }}
                        <!-- Nav tabs -->
                            <div>
                                <table id="asesores" class="display" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NOMBRES</th>
                                        <th>#CITAS</th>
                                        <th>#VENTAS</th>
                                        <th>%VENTAS</th>
                                        <th>NIVEL</th>
                                    </tr>
                                    </thead>
                                    <tbody><?php $i=1;?>
                                    @foreach($asesores as $k)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td><strong>{{$k->nombres}}</strong></td>
                                        <td>{{$k->citas_historial}}</td>
                                        <td>{{$k->ventas}}</td>
                                        <td>{{$k->porcentaje_ventas}}</td>
                                        <td>{{$k->ranking_asesor->nivel}}</td>
                                    </tr>
                                    <?php $i++;?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection