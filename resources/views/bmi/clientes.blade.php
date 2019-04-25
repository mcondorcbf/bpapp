@extends('layouts.appBmi')
@section('scripts')
    <script src="/js/app.js"></script>
    <link rel="stylesheet" href="vendor/bootstrap-select/dist/css/bootstrap-select.css">
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <form class="form-horizontal" role="form" method="post" action="{{ url('/busqueda') }}">
                        {{ csrf_field() }}
                        <div class="panel-heading">Dashboard</div>
                        <div class="panel-body">
                            Ingrese el numero de cédula que desea buscar
                            <br>
                            <br>
                            <label for="c.i" class="col-md-1 control-label">Cédula</label>
                            <div class="col-md-6" style="margin-bottom: 10px">
                                <input id="c.i" type="number" class="form-control" name="c.i" value="{{ old('c.i') }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <div class="col-md-1 col-md-offset-0 col-xs-5 col-xs-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="destino"></div>

    <script>
        function validacion() {
            if ($(document.getElementById('file')).val() == '') {
                alert('SELECCIONE UN ARCHIVO');
                return false;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#cargando").css("display", "inline");
            var form = $(this);
            var url = '{{URL::to('validarArchivo')}}';
            var formData = new FormData(document.getElementById("Form"));
            $.ajax({
                url: url,
                type: "post",

                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                datatype: 'json',
                success: function (data) {
                    var errors = 0;
                    $("#resultado").html('');
                    $.each(data, function (k, v) {

                        if ('ERROR' == data[k]) {
                            errors = 1;
                        }
                        $('#resultado').append(data[k] + '<br>');
                    });
                    if (errors == 1) {
                        $("#resultado").css("color", "red");
                        $("#siguiente").attr('disabled', true);
                    } else {
                        $("#resultado").css("color", "green");
                        $("#siguiente").attr('disabled', false);
                    }
                    $("#cargando").css("display", "none");
                    $("#resultado").addClass('msg_notice');
                    $("#resultado").fadeIn(1500);
                },
                error: function () {
                    $("#resultado").html('Hubo un error');
                    $("#resultado").addClass('msg_error');
                    $("#resultado").fadeIn(1500);
                }
            });
        };

        $("#Form2").on("submit", function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            $("#cargando").css("display", "inline");
            var form = $(this);
            var url = '{{URL::to('nuevoIvr2')}}';
            var formData = new FormData(document.getElementById("Form"));

            $.ajax({
                url: url,
                type: "post",

                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                datatype: 'json',
                success: function(data){
                    console.log(data);
                },
                error:function(){
                    console.log('Hubo un error');
                }
            });
        });

        $(document).ready(function(){
            $("#enlaceajax").click(function(evento){
                evento.preventDefault();
                $("#cargando").css("display", "inline");
                $("#destino").load("pagina-lenta.php", function(){
                    $("#cargando").css("display", "none");
                    $("#resultado").css("display", "none");
                    $("#siguiente").attr('disabled',true);
                });
            });
        })

        function limpiar(){
            $("#resultado").css("display", "none");
            $("#siguiente").attr('disabled',true);
        }
    </script>
@endsection