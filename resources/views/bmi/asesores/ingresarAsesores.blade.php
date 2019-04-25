@extends('layouts.appBmi')
@section('scripts')
<script src="/js/app.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
<script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js"></script>
<script>
    $( document ).ready(function() {
        $('#fecha_contrato').datepicker();

        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        var checkin = $('#fecha_contrato').datepicker({
            onRender: function(date) {
                return date.valueOf() < now.valueOf() ? 'enabled' : '';
            }
        }).on('changeDate', function(ev) {
            if (ev.date.valueOf() > checkout.date.valueOf()) {
                var newDate = new Date(ev.date)
                newDate.setDate(newDate.getDate() + 1);
                checkout.setValue(newDate);
            }else{
            }
            checkin.hide();
            $('#fecha_nacimiento')[-10].focus();
        }).data('datepicker');
        var checkout = $('#fecha_nacimiento').datepicker({
            onRender: function(date) {
                return date.valueOf() <= checkin.date.valueOf() ? 'enabled' : '';
            }
        }).on('changeDate', function(ev) {
            checkout.hide();
        }).data('datepicker');
    });
</script>
@endsection
@section('content')
    <div class="container">
        <div class="row">

            @if ($error!='')
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>Corrige los siguientes errores:</p>
                    <ul>
                            <li>{{ $error }}</li>
                    </ul>
                </div>
            @endif
            @if ($mensaje!='')
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>Información:</p>
                    <ul>
                        <li>{{ $mensaje }}</li>
                    </ul>
                </div>
            @endif

            <div class="col-md-8 col-md-offset-2">
                <div class="panel-heading"><h3>Ingresar Asesor</h3></div>
                <div class="panel panel-default">
                    <br>

                    {!! Form::open(array('url'=>'ingresarAsesorBmi','method'=>'POST'))!!}
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Nombres: * </label>
                            <div class="col-lg-12">
                                <input name="nombres" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Cédula: *</label>
                            <div class="col-lg-12">
                                <input name="cedula" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Correo corporativo (usuario):</label>
                            <div class="col-lg-12">
                                <input name="email" type="email" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Correo personal: *</label>
                            <div class="col-lg-12">
                                <input name="email_personal" type="email" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Celular:</label>
                            <div class="col-lg-12">
                                <input name="celular" type="text" class="form-control" value="">
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-12" >
                            <label class="col-lg-5">Fecha de contrato: * </label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type="text" class="span2 form-control" value="" id="fecha_contrato" name="fecha_contrato" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-12" style="margin-top: 15px">
                            <label class="col-lg-5">Fecha de nacimiento: * </label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type="text" class="span2 form-control" value="" id="fecha_nacimiento" name="fecha_nacimiento" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Tipo:</label>
                            <div class="col-lg-12">
                                <select class="form-control" name="tipo">
                                    @foreach ($tipo_asesor as $k)
                                    <option value="{{$k->id_tipo_asesor}}">{{$k->nombre}}</option>
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
                                <a href="{{url('bmi')}}" id="atras"  class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Atras </a>
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