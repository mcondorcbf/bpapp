@extends('layouts.appIvr')
@section('scripts')
    <script src="/js/app.js"></script>
    <link rel="stylesheet" href="vendor/bootstrap-select/dist/css/bootstrap-select.css">
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.js"></script>
@endsection
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel-heading"><h3>CREAR NUEVO IVR</h3></div>
            <div class="panel panel-default">
                <br>
                <form class="form-horizontal" id="Form" role="form" method="post" action="{{ url('/enviarIvr') }}">
                    {{ csrf_field() }}
                <div class="panel-body">
                    <div class='alert alert-info col-lg-12 col-md-12'>
                        ARCHIVO BASE: <strong>{{$archivo}}</strong>
                        <input type="hidden" name="dir" id="dir" value="{{$dir.$archivo}}">
                    </div>
                    <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                        <label class="col-lg-12">Seleccionar Cliente</label>
                        <div class="col-lg-12">

                        </div>

                    </div>

                    <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                        <label class="col-lg-12">Seleccionar Campaña</label>
                        <div class="col-lg-12">

                        </div>

                    </div>

                    <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                        <label class="col-lg-12">Seleccionar Script</label>
                        <div class="col-lg-12">

                        </div>
                        <br>

                    </div>


                    <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                        <br>
                            <div class="col-lg-12 col-md-12">
                                <a href="{{url('nuevoIvr')}}" id="atras"  class="btn btn-primary "><span class="glyphicon glyphicon-backward"></span> Nuevo Ivr</a>
                                <button type="submit" id="siguiente"  class="btn btn-primary ">Procesar <span class="glyphicon glyphicon-forward"></span></button>
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

<!-- Modal -->
<div class="modal fade" id="campaniaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Añadir Campaña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                {!! Form::open(['method' => 'POST', 'id' => 'Campanias', 'class' => 'form-horizontal']) !!}
                <div id="campanias">
                    <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                        <label>Nombre de la campaña</label>
                        @if(count($cliente)>0)
                        <input type="hidden" name="idCliente" value="{{$idCliente}}">
                        @endif
                        <input type="text" class="form-control" name="nombre_campania" required>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <button type="submit" id="submit"  class="btn btn-success"> + Agregar</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="restcampanias()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="clienteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Añadir Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                {!! Form::open(['method' => 'POST', 'id' => 'Clientes', 'class' => 'form-horizontal']) !!}
                <div id="clientes">
                    <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                        <label>Nombre del cliente</label>
                        <input type="text" class="form-control" name="nombres" required>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <button type="submit" id="submit"  class="btn btn-success"> + Agregar</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="restcampanias()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="scriptModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Añadir Script</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                {!! Form::open(['method' => 'POST', 'id' => 'Scripts', 'class' => 'form-horizontal']) !!}
                <div id="scripts">
                    <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                        <label>Script</label>
                        <input type="hidden" name="idCampania" id="idCampania" value="">
                        <textarea id="" type="text" class="form-control" name="script" value="" required="" autofocus=""></textarea>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <button type="submit" id="submit"  class="btn btn-success"> + Agregar</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="restscript()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
function restcampanias(){

var form = '<div class="col-lg-12 col-md-12" style="margin-bottom: 10px">            <label>Nombre de la campaña</label>        <input type="text" class="form-control" name="nombre_campania" required="">            </div>            <div class="col-lg-12 col-md-12">            <button type="submit" id="submit" class="btn btn-success"> + Agregar</button>            </div>';
    document.getElementById("campanias").innerHTML = form;

}

$("#Campanias").on("submit", function(e){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    e.preventDefault();
    var form = $(this);
    var url = '{{URL::to('campania')}}';
    var formData = new FormData(document.getElementById("Campanias"));
    $.ajax({
        url: url,
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function(jqXHR, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        success: function(data) {
            $("#cargando").css("display", "none");
            document.getElementById("campanias").innerHTML = data+' agreado correctamente.';
            $("#campaniaselect").append('<option value='+data+'>'+data+'</option>');
        }
    })

});

function restcliente(){

    var form = '<div class="col-lg-12 col-md-12" style="margin-bottom: 10px">            <label>Nombre del cliente</label>        <input type="text" class="form-control" name="nombres" required="">            </div>            <div class="col-lg-12 col-md-12">            <button type="submit" id="submit" class="btn btn-success"> + Agregar</button>            </div>';
    document.getElementById("clientes").innerHTML = form;
}

$("#Clientes").on("submit", function(e){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    e.preventDefault();
    var form = $(this);
    var url = '{{URL::to('cliente')}}';
    var formData = new FormData(document.getElementById("Clientes"));
    $.ajax({
        url: url,
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function(jqXHR, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        success: function(data) {
            $("#cargando").css("display", "none");
            document.getElementById("clientes").innerHTML = data+' agreado correctamente.';
            $("#clienteselect").append('<option value='+data+'>'+data+'</option>');
        }
    })

});

function restscript(){

    var form = '<div class="col-lg-12 col-md-12" style="margin-bottom: 10px">        <label>Script</label>        <textarea id="" type="text" class="form-control" name="script" value="" required="" autofocus=""></textarea>        </div>        <div class="col-lg-12 col-md-12">        <button type="submit" id="submit"  class="btn btn-success"> + Agregar</button>        </div>';
    document.getElementById("scripts").innerHTML = form;

}

$("#Scripts").on("submit", function(e){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    e.preventDefault();
    var form = $(this);
    var url = '{{URL::to('scripts')}}';
    var formData = new FormData(document.getElementById("Scripts"));
    $.ajax({
        url: url,
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function(jqXHR, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        success: function(data) {
            $("#cargando").css("display", "none");
            document.getElementById("scripts").innerHTML = data+' agreado correctamente.';
            $('#scriptsselect').empty();
            $("#scriptsselect").append('<option value='+data+'>'+data+'</option>');
        }
    })

});


$("#Form").on("submit", function(e){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    e.preventDefault();
    $("#cargando").css("display", "inline");
    var form = $(this);
    var url = '{{URL::to('depurarIvr')}}';
    var formData = new FormData(document.getElementById("Form"));
    //formData.append("dato", "valor");
    //formData.append(f.attr("name"), $(this)[0].files[0]);
    $.ajax({
        url: url,
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    })
        .done(function(result){
        $("#cargando").css("display", "none");
        $("#resultado").css("display", "inline");
        $("#resultado").wrapInner( "<div style='color:#3f51b5'>"+result+"</div>");
    });
});



function escuchar() {
    if ($(document.getElementById('clienteselect')).val() == "") {
        alert("SELECCIONE UN CLIENTE");
        document.getElementById("clienteselect").focus();
        return false;
    }
    if ($(document.getElementById('campaniaselect')).val() == "") {
        alert("SELECCIONE UNA CAMPAÑA");
        document.getElementById("campaniaselect").focus();
        return false;
    }
    if ($(document.getElementById('scriptsselect')).val() == "") {
        alert("SELECCIONE UN SCRIPT");
        document.getElementById("scriptsselect").focus();
        return false;
    }
    if ($(document.getElementById('demo')).val() == "") {
        alert("INGRESE UN NUMERO");
        document.getElementById("demo").focus();
        return false;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var form = $(this);
    var url = '{{URL::to('enviarIvrPrueba')}}';
    var formData = new FormData(document.getElementById("Form"));
    //formData.append("dato", "valor");
    //formData.append(f.attr("name"), $(this)[0].files[0]);
    $.ajax({
        url: url,
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    })
        .done(function(result){
            $("#cargando").css("display", "none");
            $("#resultado").css("display", "inline");
            $("#resultado").wrapInner( "<br><div style='color:#ca4a2c; font-size: 13px; margin-top: 10px'><strong>"+result+"</strong></div> ");
    });
}

$(document).ready(function(){
    $("#enlaceajax").click(function(evento){
        evento.preventDefault();
        $("#cargando").css("display", "inline");
        $("#destino").load("pagina-lenta.php", function(){
            $("#cargando").css("display", "none");

        });
    });
})

$('#myModal').on('shown.bs.modal', function () {
    $('#myInput').focus()
})

function limpiar(){
    $("#resultado").css("display", "none");
}
</script>
@endsection