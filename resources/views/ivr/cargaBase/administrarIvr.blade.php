@extends('layouts.appIvr')
@section('scripts')
    <script src="{{asset('/js/app.js')}}"></script>
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-select/dist/css/bootstrap-select.css')}}">
    <script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>
@endsection
@section('content')
<script>
    $().ready(function()
    {

        $('.pasar').click(function() {
            if (document.getElementById('clienteselect').value==''){
                alert('Debe Seleccionar un cliente');
                document.getElementById("clienteselect").focus();
                return false;
            }
            if($('#origen option:selected').val()==undefined){return false;}

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#cargando").css("display", "inline");
            var form = $(this);
            var url = '{{URL::to('usuariosClientes')}}';

            var formData = $('#origen option:selected').val();
            //formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: url,
                type: "POST",
                url: url,
                data: $("#formulario").serialize(),
                success: function(data)
                {
                    $("#cargando").css("display", "none");
                    $("#resultado").css("display", "inline");
                    $("#resultado").wrapInner( "<div style='color:#3f51b5'>"+data+"</div>");
                }
            });
            return !$('#origen option:selected').remove().appendTo('#destino');
        });
        $('.quitar').click(function() {

            return !$('#destino option:selected').remove().appendTo('#origen');
        });
        $('.pasartodos').click(function() { $('#origen option').each(function() {
            if(document.getElementById('clienteselect').value==''){
                alert('Debe Seleccionar un cliente');
                document.getElementById("clienteselect").focus();
                return false;
            }
            $(this).remove().appendTo('#destino'); });
        });
        $('.quitartodos').click(function() { $('#destino option').each(function() { $(this).remove().appendTo('#origen'); }); });
        $('.submit').click(function() { $('#destino option').prop('selected', 'selected'); });
    });
</script>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel-heading"><h3>CLIENTES IVR's</h3></div>
            <div class="panel panel-default">
                <br>

                {!! Form::open(array('url'=>'usuariosClientes','method'=>'POST'))!!}
                    {{ csrf_field() }}
                <div class="panel-body">
                    <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                        <label class="col-lg-12">Seleccionar Cliente</label>
                        <div class="col-lg-12">
                            <select name="clienteselect" id="clienteselect" class="form-control" data-live-search="true" required onchange="javascript:handleSelect(this)">
                                    <option value="">SELECCIONE UNO</option>
                                @foreach($clientes as $k)
                                    @if(isset($idCliente) && $idCliente->id_cliente==$k->id_cliente)
                                        <option value="{{$idCliente->id_cliente}}" selected>{{$idCliente->nombres}}</option>
                                    @else
                                        <option value="{{$k->id_cliente}}">{{$k->nombres}}</option>
                                    @endif
                                @endforeach
                            </select>

                            <script type="text/javascript">
                                function handleSelect(elm)
                                {
                                    if(clienteselect.value!=''){
                                        window.location = '/clientesIvr/'+elm.value;
                                    }
                                }
                            </script>
                        </div>
                        <br>
                        <div class="col-lg-10">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#clienteModal" onclick="restcliente()">+ Agregar nuevo cliente</button>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <label class="col-lg-12 col-md-12">Seleccionar Usuario</label>
                        <div class="col-lg-4 col-md-4">
                            <label class="col-lg-12 col-md-12">Lista de usuarios</label>
                            <select name="origen[]" id="origen" multiple="multiple" size="8" class="form-control">
                                @foreach($noautorizados as $k)
                                    <option value="{{$k['id']}}">{{$k['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="col-lg-12 col-md-12">&nbsp;</label>
                            <input type="button" class="pasar izq form-control" value="Asignar >>"><input type="button" class="quitar der form-control" value="<< Remover"><br />
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label class="col-lg-12 col-md-12">Usuarios Autorizados</label>
                            <select name="destino[]" id="destino" multiple="multiple" size="8" class="form-control">
                                @foreach($autorizados as $k)
                                    <option value="{{$k['id']}}">{{$k['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                        <br>
                        <div class="col-lg-12 col-md-12">

                            <br>
                            &nbsp;
                        </div>
                            <div class="col-lg-12 col-md-12">
                                <a href="{{url('ivr')}}" id="atras"  class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Atras </a>
                                <button type="submit" id="guardar"  class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar </button>

                            </div>
                    </div>
                </div>
                {!!Form::close()!!}
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