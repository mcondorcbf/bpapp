@extends('layouts.app')
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

                {!! Form::open(['method' => 'POST', 'id' => 'Form', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) !!}
                <div class="col-lg-6 col-md-6" style="margin-bottom: 10px">
                    <label>Cargar Base</label>
                </div>
                <div class="col-xs-offset-1">
                {!! Form::file('file', ['id' =>'file', 'class' => 'form-control'])!!}
                <button type="submit" id="submit"  class="btn btn-success" >Agregar</button>
                </div>
                {!! Form::close() !!}

                <form class="form-horizontal" role="form" method="post" action="{{ url('/busqueda') }}">
                    {{ csrf_field() }}
                <div class="panel-heading">CREAR NUEVO IVR</div>
                <div class="panel-body">

                    <div class="col-lg-6 col-md-6" style="margin-bottom: 10px">
                        <label>Seleccionar Cliente</label>
                        <div class="col-lg-10">
                            <select id="basic" class="selectpicker show-tick form-control" data-live-search="true">
                                    <option selected>SELECCIONE UNO</option>
                                    <option>ASD</option>
                                    <option >Bla</option>
                                    <option>Ble</option>
                            </select>
                        </div>


                    </div>
                    <div class="col-lg-6 col-md-6" style="margin-bottom: 10px">
                        <label>Seleccionar Campaña</label>
                        <input id="" type="text" class="form-control" name="script" value="" required autofocus>
                    </div>
                    <div class="col-lg-6 col-md-6" style="margin-bottom: 10px">
                        <label>Seleccionar Script</label>
                        <input id="" type="text" class="form-control" name="script" value="" required autofocus>
                    </div>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                       Agregar nuevo Script
                    </button>

                    <div class="col-lg-6 col-md-6" style="margin-bottom: 10px">
                        <label>Escuchar demo</label>
                        <input id="" type="text" class="form-control" name="script" value="" required autofocus>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-offset-1">
                            <button type="submit" class="btn btn-primary">
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<a href="#" id="enlaceajax">Haz clic!</a>

<br>
<div id="destino"></div>
<div id="content" style="display:none;">
    Bienvenido a Jose Aguilar, ejemplo simple de carga ajax <br>
    <img src="http://www.lacosox.org/sites/default/files/plazacuracautin.jpg">
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
        //console.log(result);
    });
});

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
</script>
@endsection