@extends('layouts.appIvr')
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js"></script>


    <script>
        $( document ).ready(function() {
            $('#fecha').datepicker();

            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

            var checkin = $('#dpd1').datepicker({
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
                $('#dpd2')[0].focus();
            }).data('datepicker');
            var checkout = $('#dpd2').datepicker({

                onRender: function(date) {

                    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
                }
            }).on('changeDate', function(ev) {
                checkout.hide();
            }).data('datepicker');
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#Form").on("submit", function(e){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                $("#cargando").css("display", "inline");
                var form = $(this);
                var url = '{{URL::to('procesarReporteIvr')}}';
                var formData = new FormData(document.getElementById("Form"));

                $.ajax({
                    url: url,
                    type: "post",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    datatype: 'html',
                    beforeSend: function(){
                        $("#data2").empty();
                        $('#loader-icon').show();
                        $("#procesado").html('');
                    }})
                    .done(function( data, textStatus, jqXHR ) {
                        $("#data2").empty();
                        $("#procesado").html(data);

                            console.log( "La solicitud se ha completado correctamente." );
                            $.each(data['ivrfinalizados'], function (key, item) {
                                $("#data2").append("<tr><td>" + item['id_carga'] + "</td><td>" + item['cliente'] + "</td><td>" + item['id_campania'] + "</td><td>" + item['fecha'] + "</td><td>" + item['calendarizado'] + " " + item['fecha_inicio_envio'] + " </td><td>" + item['totalivrs'] + "</td><td>" + item['totalllamados'] + "</td><td>" + item['contactabilidad'] + "</td><td><a href='/reporteIvr"+item['id_carga']+"?id_carga="+item['id_carga']+"&nm="+item['cliente']+"-"+item['id_campania']+"' class='btn-xs btn-success' role='button'><span class='glyphicon glyphicon-download-alt'></span></a></td></tr>");
                            })

                        $('#loader-icon').hide();
                    })
                    .fail(function( jqXHR, textStatus, errorThrown ) {
                        if ( console && console.log ) {
                            console.log( "La solicitud a fallado: " +  textStatus);
                        }
                    })
            });
        });
    </script>
@endsection
@section('content')


<div class="container col-xs-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">REPORTES IVRS</div>

                    <div class="panel-body">
                        <div class="content">

                            <div class="links" id="centrales">
                                <nav class="navbar navbar-default">
                                    <div class="container-fluid">
                                        <div class="navbar-header">
                                            <a class="navbar-brand" href="#">
                                                INGRESE UN RAGO DE FECHA
                                            </a>
                                        </div>
                                    </div>
                                </nav>
                                <form role="search" id="Form">
                                    <div class="well">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Fecha inicio:
                                                    <div class='input-group date' id='datetimepicker1'>
                                                        <input type="text" class="span2 form-control" value="" id="dpd1" name="fecha_inicio" readonly required>
                                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                    </div>
                                                </th>
                                                <th>Fecha fin:
                                                    <div class='input-group date' id='datetimepicker1'>
                                                        <input type="text" class="span2 form-control" value="" id="dpd2" name="fecha_fin" readonly required>
                                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <div>
                                            @foreach($clientes as $k)
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="clientes[]" value="{{$k->id_cliente}}"> {{$k->nombres}}
                                                    </label>
                                                </div>
                                            @endforeach
                                            <br>
                                        </div>
                                        <button type="submit" class="btn btn-default">Procesar</button>
                                    </div>
                                </form>

                                <div class="panel panel-success">
                                    <div class="panel-heading"><strong>Reporte IVR's finalizados</strong></div>
                                    <table class="table table-hover table-striped">
                                        <thead>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Campaña</th>
                                        <th>Fecha envío</th>
                                        <th>Calendarizado</th>
                                        <th>Total</th>
                                        <th>Contestados</th>
                                        <th>% Contactabilidad</th>
                                        <th>Reporte</th>
                                        </thead>
                                        <tbody id="data2">
                                        @foreach($ivrfinalizados as $k)
                                            <tr>
                                                <td>{{$k['id_carga']}}</td>
                                                <td>{{$k['cliente']}}</td>
                                                <td>{{$k['id_campania']}}</td>
                                                <td>{{$k['fecha']}}</td>
                                                <td>{{$k['calendarizado']}} {{$k['fecha_inicio_envio']}}</td>
                                                <td>{{$k['procesados']}}</td>
                                                <td>{{$k['totalllamados']}}</td>
                                                <td>{{$k['contactabilidad']}}</td>
                                                <td><a href="{{asset('/reporteIvr'.$k['id_carga'].'?id_carga='.$k['id_carga'])}}&nm={{$k['cliente']}}-{{$k['id_campania']}}-{{$k['fecha_inicio_envio']}}" class="btn-xs btn-success" role="button"><span class="glyphicon glyphicon-download-alt"></span></a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div id="loader-icon" style="display:none; color: green;" align="center">
                                    <img src="{{asset('images/loading.gif')}}"><br>PROCESANDO . . .
                                </div>
                                <div id="procesado" style="display:none;">
                                    <table class="table">
                                        <tr>
                                            <th>FECHA</th>
                                            <th>OPERADORA</th>
                                            <th>MINUTOS</th>
                                        </tr>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection