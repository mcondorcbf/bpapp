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
            <div class="panel-heading"><h3>SCRIPTS ESTATICOS IVR's</h3></div>
            <div class="panel panel-default">
                <br>

                {!! Form::open(array('url'=>'scriptsClientes','method'=>'POST'))!!}
                    {{ csrf_field() }}
                <div class="panel-body">
                    <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                        <label class="col-lg-12">Seleccionar Cliente</label>
                        <div class="col-lg-12">
                            <select name="clienteselect" id="clienteselect" class="form-control" data-live-search="true" required onchange="javascript:handleSelect(this)">
                                <option value="">SELECCIONE UNO</option>
                                @foreach($clientes as $k)
                                    <option value="{{$k['id_cliente']}}">{{$k['nombres']}}</option>
                                @endforeach
                            </select>

                            <script type="text/javascript">
                                function handleSelect(elm)
                                {
                                    $("#idCliente").val(elm.value);
                                    if(clienteselect.value!=''){
                                        var form = $(this);
                                        var url = '{{URL::to('campanias/')}}'+elm.value;
                                        var formData = new FormData(document.getElementById("Form"));
                                        $.ajaxSetup({
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });
                                        $.ajax({
                                            url: url,
                                            type: "post",
                                            dataType: "json",
                                            data: formData,
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                alert("Error: " + errorThrown);
                                            },
                                            success: function(data) {
                                                jQuery.each(data, function(index, item) {
                                                    $("#campaniaselect").append('<option value='+item['id_campania']+'>'+item['nombre_campania']+'</option>');
                                                });
                                                $("#nuevaCamapania").attr('disabled', false);
                                            }
                                        });
                                    }
                                }
                            </script>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                        <label class="col-lg-12">Seleccionar Campaña</label>
                        <div class="col-lg-12">
                            {{--<select name="campaniaselect" id="campaniaselect" class="selectpicker show-tick form-control" data-live-search="true" required>--}}
                            <select name="campaniaselect" id="campaniaselect" class="form-control" data-live-search="true" required onchange="javascript:campaniatiposcript(this)">
                                <option value="">SELECCIONE UNO</option>
                                @if(count($campanias)>0)
                                    @foreach($campanias as $k)
                                        <option value="{{$k->id_campania}}">{{$k->nombre_campania}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <script type="text/javascript">
                                function campaniatiposcript()
                                {
                                    var url = '{{URL::to('tiposcript')}}';
                                    var formData = new FormData();
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });
                                    $.ajax({
                                        url: url,
                                        type: "post",
                                        dataType: "json",
                                        data: formData,
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            alert("Error: " + errorThrown);
                                        },
                                        success: function(data) {
                                            document.getElementById('tiposcriptsselect').innerHTML='';
                                            $("#tiposcriptsselect").append('<option value="">SELECCIONE UNO</option>');
                                            jQuery.each(data, function(index, item) {
                                                $("#tiposcriptsselect").append('<option value="'+item['id_tipo']+'">'+item['nombre']+'</option>');
                                                return false;
                                            });
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                        <label class="col-lg-12">Seleccionar tipo de Script</label>
                        <div class="col-lg-12" id="tiposcripts">
                            <select name="tiposcriptsselect" id="tiposcriptsselect" class="form-control" data-live-search="true" required onchange="javascript:campaniascript(this)">
                                <option value="">SELECCIONE UNO</option>
                            </select>
                            <script type="text/javascript">
                                function campaniascript(elm)
                                {
                                    var form = $(this);
                                    var url = '{{URL::to('campaniascript?id=')}}'+campaniaselect.value+'&tipo='+document.getElementById('tiposcriptsselect').value;
                                    var formData = campaniaselect.value;
                                    $.ajax({
                                        url: url,
                                        type: "get",
                                        dataType: "json",
                                        data: formData,
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            alert("Error: " + errorThrown);
                                        },
                                        success: function(data) {
                                            document.getElementById('scriptsselect').innerHTML='';
                                            document.getElementById('scriptsselect').innerHTML='<select name="scriptsselect" id="scriptsselect" class="form-control" data-live-search="true" required> <option value="">SELECCIONE UNO</option></select>';
                                            jQuery.each(data[0], function(index, item) {
                                                $("#scriptsselect").append('<option value='+item['id_script']+'>'+item['script']+'</option>');
                                            });
                                            $("#idCampania").val(campaniaselect.value);
                                            if(document.getElementById('tiposcriptsselect').value==1){
                                                $("#nuevoScript").attr('disabled', true);
                                            }else{
                                                $("#nuevoScript").attr('disabled', false);
                                                $("#idCampania").val(data[1]);
                                                $("#tipoScript").val(tiposcriptsselect.value);
                                            }
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                        <label class="col-lg-12">Seleccionar Script</label>
                        <div class="col-lg-12">
                            <select name="scriptsselect" id="scriptsselect" class="form-control" data-live-search="true" required>
                                <option value="">SELECCIONE UNO</option>
                            </select>
                        </div>
                        <br>
                        <div class="panel panel-success">
                            <div class="panel-heading"><strong>IVR's finalizados</strong></div>
                            <table class="table table-hover table-striped">
                                <thead>
                                <td>#</td>
                                <td>Cliente</td>
                                <td>Campaña</td>
                                <td>Fecha envío</td>
                                <td>Avance</td>
                                <td>Estado</td>
                                </thead>

                            </table>
                        </div>
                        <div class="col-lg-8">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#scriptModal" id="nuevoScript" disabled>+ Editar script</button>

                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#scriptModal" id="nuevoScript" disabled>+ Agregar nuevo script</button>
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