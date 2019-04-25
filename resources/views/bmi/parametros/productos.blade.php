@extends('layouts.appBmi')
@section('scripts')


    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#diners').DataTable( {
                "order": [[ 0, "asc" ]]
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
                location.href='/productoD/'+id;
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
                    <div class="panel-heading " style="background-color: #3a77bf; border-color: #000; color: #fff;">PARAMETROS PRODUCTOS</div>

                    <!-- Nav tabs -->
                        <div>

                            <table id="diners" class="display" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DESCRIPCION</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;$result='';?>
                                @foreach($productos as $k=>$v)
                                    <tr>
                                        <td><a class="btn-xs btn-primary" href="{{url('productosS/'.$v->id_producto)}}">{{$v->id_producto}}</a></td>
                                        <td>
                                            {{$v->descripcion}}
                                        </td>
                                        <td><a class="btn btn-danger btn-xs" onclick="return confirmation({{$v->id_producto}});">Eliminar</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <?php print_r($result);?>
                        </div>
                </div>
                <div class="col-md-12 col-lg-12">
                    <a href="{{ url('/productosN') }}" class="btn btn-primary">+ Agregar Nuevo</a><br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection