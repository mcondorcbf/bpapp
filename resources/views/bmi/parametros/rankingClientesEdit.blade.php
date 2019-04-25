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
            $('#fecha_visita').datepicker();

            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

            var checkin = $('#fecha_visita').datepicker({
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
                $('#hora_visita')[-10].focus();
            }).data('datepicker');
            var checkout = $('#hora_visita').datepicker({
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
            <div class="col-md-8 col-md-offset-2">
                <div class="panel-heading"><h3>Ranking de Cliente {{$cliente->id_ranking_cliente}}</h3></div>
                <div class="panel panel-default">
                    <br>

                    {!! Form::open(array('url'=>'rankingClienteP','method'=>'POST'))!!}
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-3">Monto Inicial *</label>
                            <div class='col-lg-3 input-group'>
                                <input type="hidden" value="{{$cliente->id_ranking_cliente}}" id="id_ranking_cliente" name="id_ranking_cliente">
                                <input type="number" class="form-control" value="{{$cliente->monto_ini}}" id="monto_ini" name="monto_ini" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-3">Monto Final: *</label>
                            <div class='col-lg-3 input-group'>
                                <input type="number" class="form-control" value="{{$cliente->monto_fin}}" id="monto_fin" name="monto_fin" required>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-3">Nivel: *</label>
                            <div class='col-lg-3 input-group'>
                                <input type="number" class="form-control" value="{{$cliente->nivel}}" id="nivel" name="nivel" required>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12" >
                            <label class="col-lg-3">Descripción: *</label>
                            <div class='col-lg-6 input-group'>
                                <input type="text" class="form-control" value="{{$cliente->descripcion}}" id="descripcion" name="descripcion" required>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                            <br>
                            <div class="col-lg-12 col-md-12">
                                <a href="{{url('rankingClientes')}}" id="atras"  class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Atras </a>
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