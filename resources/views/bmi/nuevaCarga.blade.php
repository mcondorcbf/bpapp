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
            <div class="panel-heading"><h3>NUEVA CARGA</h3></div>
            <div class="panel panel-default">
                <br>
                {!! Form::open(['action' => 'Ivr\IvrController@nuevoIvr2','method' => 'POST', 'id' => 'Form', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) !!}
                <div class="col-lg-11 col-md-11" style="margin-bottom: 10px">
                    <h4>CARGAR BASE</h4>
                    <br>
                    <span style="font-size: 14px">
                    <strong style="color:#c73535">Nota: </strong>.
                    </span>
                </div>
                <div class="col-lg-12 col-md-12">
                <div class="col-lg-8 col-md-8">
                <input id="file" class="form-control" required="required" name="file" type="file" onclick="limpiar()">
                <div id="cargando" style="display:none; color: green;"><img src="images/ajax-loader.gif" width="460" height="20"/><br> Cargando archivo... </div>
                <div id="resultado" style="display:none; color: green;"></div><br>
                </div>
                <div class="col-lg-2 col-md-2">
                    <a id="validar"  class="btn btn-success btn-sm" onclick="validacion()">Cargar <span class="glyphicon glyphicon-ok"></span></a>
                </div>
                </div>
                <br>
                <div class="col-lg-12 col-md-12">
                    <a href="{{url('/busquedabmi')}}" id="salir" class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Salir</a>
                </div>
                {!! Form::close() !!}
                <br><br>&nbsp;
                <br>&nbsp;
                <div>&nbsp;</div>
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