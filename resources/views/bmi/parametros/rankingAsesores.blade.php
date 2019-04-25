@extends('layouts.appBmi')
@section('scripts')


    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#diners').DataTable( {
                "order": [[ 0, "asc" ]]
            } );

            $('#visa').DataTable( {
                "scrollY": 500,
                "scrollX": true
            } );
            $('#visar').DataTable( {
                "scrollY": 500,
                "scrollX": true
            } );
            $('#discoverr').DataTable( {
                "scrollY": 500,
                "scrollX": true
            } );
            $('#discover').DataTable( {
                "scrollY": 500,
                "scrollX": true
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
    <script>
        function confirmation(id) {
            var answer = confirm("Seguro que desea eliminar?")
            if (answer){
                location.href='/rankingAsesorD/'+id;
            }
            else{
                // do nothing
            }
        }
    </script>
@endsection
@section('content')

<div class="row">
    <div class="form-group panel panel-default">
        <div class="tab-content tab-content-border" style="font-size: 11px">
            <div class="tab-pane fade active in" id="dinersconsolidado">
                <div class="col-md-12 col-lg-12">
                    <div class="panel-heading " style="background-color: #3a77bf; border-color: #000; color: #fff;">PARAMETROS RANKING DE ASESORES</div>

                    <!-- Nav tabs -->
                        <div>

                            <table id="diners" class="display" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>NIVEL</th>
                                    <th>MONTO INICIAL (%)</th>
                                    <th>MONTO FINAL (%)</th>
                                    <th>DESCRIPCION</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;$result='';?>
                                @foreach($asesoresrk as $k)
                                    <tr>
                                        <td><a class="btn-xs btn-primary" href="{{url('rankingAsesor/'.$k->id_ranking)}}">{{$k->nivel}}</a></td>
                                        <td>{{$k->valor_inicial}}</td>
                                        <td>{{$k->valor_final}}</td>
                                        <td>{{$k->descripcion}}</td>
                                        <td><a class="btn btn-danger btn-xs" onclick="return confirmation({{$k->id_ranking}});">Eliminar</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <?php print_r($result);?>
                        </div>
                </div>
                <div class="col-md-12 col-lg-12">
                    <a href="{{ url('/rankingAsesorN') }}" class="btn btn-primary">+ Agregar Nuevo</a><br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection