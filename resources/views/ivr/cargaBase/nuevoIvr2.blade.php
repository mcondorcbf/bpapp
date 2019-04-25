<?php use App\Role;?>
@extends('layouts.appIvr')
@section('scripts')
    <script src="{{asset('js/app.js')}}"></script>
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-select/dist/css/bootstrap-select.css')}}">
    <script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#myExcel').modal('show');
        });
        function excelOn() {
            $("#resultadoExcel").css("display", "none");
            $("#Excel").css("display", "block");
        }
    </script>
@endsection
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-11">
            <div class="panel-heading"><h3>CREAR NUEVO IVR</h3></div>
            <div class="panel panel-default">
                <br>
                <form class="form-horizontal" id="Form" role="form" method="post" action="{{ url('/procesarIvr') }}">
                    {{ csrf_field() }}
                <div class="panel-body">
                    <div class='alert alert-success col-lg-12 col-md-12' align="center">
                        <h4>ARCHIVO BASE: <strong>{{$archivo}}</strong></h4>
                        <input type="hidden" name="archivo" id="archivo" value="{{$archivo}}">
                        <input type="hidden" name="dir" id="dir" value="{{$dir.$archivo}}">
                        <span id="excelBases">
                        </span><br>
                        <button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#myExcel" id="nuevoExcel" onclick="excelOn()"><span class="glyphicon glyphicon-edit"></span> Editar mapeo</button>
                    </div>
                    <div>

                    </div>
                    <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                        <label class="col-lg-12">Seleccionar Cliente</label>
                        <div class="col-lg-12">
                            <select name="clienteselect" id="clienteselect" class="form-control" data-live-search="true" required onchange="javascript:handleSelect(this)">
                                <option value="">SELECCIONE UNO</option>
                                @if(count($cliente)>0)
                                <option value="{{$cliente->id_cliente}}" selected>{{$cliente->nombres}}</option>
                                @endif
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
                                                if(elm.value==11){
                                                    obj = document.getElementById('indexar');
                                                    obj.style.display = 'block';
                                                    $("#sisGestCamapana").attr('disabled', false);
                                                }else{
                                                    obj = document.getElementById('indexar');
                                                    obj.style.display = 'none';
                                                    $("#sisGestCamapana").attr('disabled', true);
                                                }
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
                                            });
                                        }
                                    });
                                }
                            </script>
                        </div>
                        <br>
                        <div class="col-lg-10">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#campaniaModal" id="nuevaCamapania" disabled>+ Agregar nueva campaña</button>
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
                        <div class="col-lg-10">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#scriptModal" id="nuevoScript" disabled>+ Agregar nuevo script</button>
                        </div>
                    </div>

                    <div class="form-horizontal col-lg-12 col-md-6">
                        <label class="col-lg-12">Escuchar demo</label>
                        <div class="col-lg-12">
                            <input id="demo" type="text" class="form-control" name="demo" value=""  maxlength="12" autocomplete>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div id="escuchar" class="btn btn-success" onclick="escuchar()">Escuchar <span class="glyphicon glyphicon-play"></span></div>
                            <div id="resultado" style="display:none; color: green;"></div>
                        </div>
                    </div>

                    <div class="form-horizontal col-lg-12 col-md-12">
                        <br>
                        <label class="col-lg-12">Agendar IVR</label>
                        <div class="col-lg-4 col-md-5">
                            <input id="fecha_agenda" type="datetime-local" class="form-control" name="fecha_agenda" value="" disabled >
                        </div>
                        <div class="col-lg-12 col-md-6">
                            <div class="radio">
                                <label><input type="radio" name="habilitado" value="0" checked="checked" onclick="agendarIvr(this)">Envío Inmediato </label> <label> <input type="radio" name="habilitado" value="1" onclick="agendarIvr(this)"> Envío Programado </label>
                            </div>
                        </div>
                    </div>
                    @if(count($sisGestionCampanas)>0)
                    <div class="form-horizontal col-lg-12 col-md-12" id="indexar" style="display: none">
                        <br>
                        <label class="col-lg-12">Seleccione una campaña del sistema de gestión (Solo aplica para ATM)</label>
                        <div class="col-lg-4 col-md-5">
                            <select name="sisGestCamapana" id="sisGestCamapana" class="form-control" data-live-search="true" required >
                                <option value="">SELECCIONE UNO</option>
                                @foreach($sisGestionCampanas as $k)
                                    <option value="{{$k->id}}">{{$k->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                        <br>
                            <div class="col-lg-12 col-md-12">
                                <a href="{{url('nuevoIvr')}}" id="atras"  class="btn btn-primary "><span class="glyphicon glyphicon-backward"></span> Atras</a>
                                <button type="submit" id="siguiente"  class="btn btn-primary" onclick="return confirmSubmit()">Procesar <span class="glyphicon glyphicon-forward"></span></button>
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
                        <input type="hidden" name="idUsuario" value="{{$user->id}}">

                        <input type="hidden" name="idCliente" id="idCliente" value="{{$idCliente}}">

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
                <div style="font-size: 12px">
                    <label>Variables del sistema disponibles: </label> <span id="variables"></span><br>
                    <label>Script de ejemplo: </label> Estimado usuario su número teléfonico es {telefono}.
                </div>
                <div id="scriptss">
                    <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                        <label>Script</label>
                        <input type="hidden" name="idCampania" id="idCampania" value="">
                        <input type="hidden" name="tipoScript" id="tipoScript" value="">
                        <textarea id="" type="text" class="form-control" name="script" value="" required="" autofocus="" rows="8"></textarea>
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

<div class="modal fade" id="myExcel" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <h3>Mapeo de campos Excel</h3><br>
                <strong>Relacione los campos del archivo excel subido con las variables del sistema.<br>
                    <span style="color: #c73535;">Nota:</span> Recuerde que la variable del sistema {telefono} es obligatoria.</strong>
            </div>
            <div class="modal-body">
                <div id="resultadoExcel" align="center" style="display:none"><h4>Datos Guardados Correctamente:</h4> <a href="#" data-dismiss="modal" class="btn-sm btn-success">Cerrar <span class="glyphicon glyphicon-ok-sign"></span> </a></div>
                <form class="form-horizontal" id="Excel" role="Excel">
                    {{ csrf_field() }}
                    <input type="hidden" name="total" value="{{count($cabeceraExcel)}}">
                    <table class="table table-hover table-striped">
                        <thead>
                        <td>Archivo Excel</td>
                        <td>Variables del Sistema</td>
                        </thead>
                        <?php $i=0;?>
                        @foreach($cabeceraExcel as $key=>$value)

                            <tr>
                                <td>
                                    @if($i==0)
                                    <select name="excel{{$key}}" id="excel{{$key}}" class="form-control" data-live-search="true" required>
                                        <option value="telefono">telefono</option>
                                    @else
                                    <select name="excel{{$key}}" id="excel{{$key}}" class="form-control" data-live-search="true">
                                        <option value="">SELECCIONE UNO </option>
                                        @foreach($cabeceraExcel as $k=>$v)
                                            <option value="{{$v}}">{{$v}}</option>
                                        @endforeach
                                    @endif
                                    </select>

                                </td>
                                <td>
                                    @if($i==0)
                                    <select name="columna{{$key}}" id="columna{{$key}}" class="form-control" data-live-search="true" required style="background-color: #f0f0f0">
                                        <option value="telefono">telefono</option>
                                    </select>
                                    @else
                                    <select name="columna{{$key}}" id="columna{{$key}}" class="form-control" data-live-search="true" style="background-color: #eae4c3">
                                        <option value="">SELECCIONE UNO </option>
                                        @foreach($columnas as $k=>$v)
                                            <option value="{{$v}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </td>
                            </tr>
                            <?php $i++;?>
                        @endforeach
                    </table>
                    <div class="modal-footer">
                        <button type="submit" id="submit" class="btn btn-success">Guardar <span class="glyphicon glyphicon-floppy-save"></span></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
function restcampanias(){

var form = '<div class="col-lg-12 col-md-12" style="margin-bottom: 10px">            <label>Nombre de la campaña</label>        <input type="text" class="form-control" name="nombre_campania" required="">            </div>            <div class="col-lg-12 col-md-12">            <button type="submit" id="submit" class="btn btn-success"> + Agregar</button>            </div>';
    document.getElementById("campanias").innerHTML = form;

}

$("#Excel").on("submit", function(e){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    e.preventDefault();
    var form = $(this);
    var url = '{{URL::to('mapeoExcel')}}';
    var formData = new FormData(this);
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
            document.getElementById("excelBases").innerHTML='';
            document.getElementById("variables").innerHTML='';
            jQuery.each(data[0], function(index, item) {
                $("#resultadoExcel").css("display", "block");
                $("#Excel").css("display", "none");
                $("#excelBases").append('<input type="hidden" name="excelp'+index+'" id="excelp" value="'+item['excel']+'"><input type="hidden" name="basep'+index+'" id="basep" value="'+item['base']+'">');

                $("#variables").append('{'+item['base']+'} ');
            });
            $("#excelBases").append('<input type="hidden" name="total" id="excelp" value="'+data[1]+'">');

        }
    })

});

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
        dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function(jqXHR, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        success: function(data) {
            $("#cargando").css("display", "none");
            document.getElementById("campanias").innerHTML = data['nombre_campania']+' agreado correctamente.';
            $("#idCliente").val(data);
            $("#campaniaselect").append('<option value='+data['id_campania']+'>'+data['nombre_campania']+'</option>');
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
    document.getElementById("scriptss").innerHTML = '';
    var form = '<div class="col-lg-12 col-md-12" style="margin-bottom: 10px">        <label>Script</label>       <input type="hidden" name="idCampania" id="idCampania" value="'+document.getElementById('campaniaselect').value+'"> <input type="hidden" name="tipoScript" id="tipoScript" value="'+document.getElementById('tiposcriptsselect').value+'"> <textarea id="" type="text" class="form-control" name="script" value="" required="" autofocus="" rows="8"></textarea>        </div>        <div class="col-lg-12 col-md-12">        <button type="submit" id="submit"  class="btn btn-success"> + Agregar</button>        </div>';
    document.getElementById("scriptss").innerHTML =form;
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
        dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function(jqXHR, textStatus, errorThrown) {
            alert("Error: " + errorThrown);
        },
        success: function(data) {
            document.getElementById("scriptss").innerHTML = '<strong>Script agreado correctamente</strong><br>'+data['script'];
            $('#scriptsselect').append(new Option(data['script'], data['id_script'], true, true));
            //$("#scriptsselect").append('<option value='+data+'>'+data+'</option>');
        }
    })

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
    if ($(document.getElementById('tiposcriptsselect')).val() == "") {
        alert("SELECCIONE UN TIPO DE SCRIPT");
        document.getElementById("tiposcriptsselect").focus();
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

function confirmSubmit()
{
    var agree=confirm("Está seguro que desea continuar?");
    if (agree)
        return true ;
    else
        return false ;
}
function agendarIvr(id){
    if(id.value==0){
        document.getElementById('fecha_agenda').disabled=true;
    }
    if(id.value==1){
        document.getElementById('fecha_agenda').disabled=false;
    }
}
</script>
@endsection