@extends('layouts.appBmi')
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js"></script>


    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#lista').DataTable( {
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[-1,10,20,30], ['Todo',10,20,30]]
            });
        });

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
                var url = '{{URL::to('reportCitasConfirmadasBmiPost')}}';
                var formData = new FormData(document.getElementById("Form"));

                document.getElementById("fecha_inicioP").value=document.getElementById("dpd1").value;
                document.getElementById("fecha_finP").value=document.getElementById("dpd2").value;
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
                        $("#data2f").empty();
                        $('#loader-icon').show();
                        $("#procesado").html('');
                    }})
                    .done(function( data, textStatus, jqXHR ) {
                        $("#data2").empty();
                        $("#procesado").html(data);

                        console.log( "La solicitud se ha completado correctamente." );
                        var count=1;

                        $.each(data['reportes'], function (key, item) {
                            $("#data2").append("<tr><td>"+ count +"</td><td><a class='btn-xs btn-primary' href=''>" + item['usuario_gestion'] + "</a></td><td>" + item['cedula_cliente'] + "</td><td>" + item['nombres'] + "</td><td>" + item['nombres_asesor'] + "</td><td>" + item['fecha_visitat'] + "</td><td>" + item['hora_visita'] + " </td><td>" + item['tipo_descripcion'] + "</td><td>" + item['accion_descripcion'] + "</td><td>" + item['observaciones'] + "</td></tr>");
                            count++;
                        })

                        $('#loader-icon').hide();
                    })
                    .fail(function( jqXHR, textStatus, errorThrown ) {
                        if ( console && console.log ) {
                            $('#loader-icon').hide();
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
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">REPORTE  CITAS CONFIRMADAS TLC</div>

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
                                    <input type="hidden" value="1" id="fechas" name="fechas">
                                    <div class="well">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Fecha inicio:
                                                    <div class='input-group date' id='datetimepicker1'>
                                                        <input type="text" class="span2 form-control" value="" id="dpd1" name="fecha_inicio" required>
                                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                    </div>
                                                </th>
                                                <th>Fecha fin:
                                                    <div class='input-group date' id='datetimepicker1'>
                                                        <input type="text" class="span2 form-control" value="" id="dpd2" name="fecha_fin" required>
                                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>

                                        <button type="submit" class="btn btn-primary">Procesar</button>
                                    </div>
                                </form>

                                <div class="panel panel-success">
                                    <table id="lista" class="display" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>N.</th>
                                            <th>ASESOR TELEFONICO</th>
                                            <th>C.I</th>
                                            <th>NOMBRES	CLIENTE</th>
                                            <th>NOMBRES	ASESOR</th>
                                            <th>FECHA DE VISITA</th>
                                            <th>HORA DE VISITA</th>
                                            <th>TIPO</th>
                                            <th>ACCION</th>
                                            <th>OBSERVACION</th>
                                        </tr>
                                        </thead>
                                        <tbody id="data2">
                                        <?php $i=1;$citas_programadas_t=0;$visitas_realizadas_t=0;$porcentaje_cumplimiento_t=0;$seguimiento_t=0;$cierre_centas_t=0;$porcentaje_eficiencia_t=0;$no_efectivas_t=0;?>
                                        @if(count($reportes)>0)
                                        @foreach($reportes as $k)
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td><a class="btn-xs btn-primary" href="{{url('rankingAsesor/')}}">{{$i}}</a></td>
                                                <td>{{$k->usuario_gestion}}</td>
                                                <td>{{$k->cedula_cliente}}</td>
                                                <td>{{$k->nombres_cliente}}</td>
                                                <td>{{$k->nombres_asesor}}</td>
                                                <td>{{$k->fecha_visitat}}</td>
                                                <td>{{$k->hora_visita}}</td>
                                                <td>{{$k->tipo_descripcion}}</td>
                                                <td>{{$k->accion_descripcion}}</td>
                                                <td>{{$k->observaciones}}</td>
                                                <?php $i++;?>
                                            </tr>
                                        @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div id="loader-icon" style="display:none; color: green;" align="center">
                                    <img src="{{asset('images/loading.gif')}}"><br>PROCESANDO . . .
                                </div>
                                <div id="procesado" style="display:none;">

                                </div>
                            </div>
                            <form role="search" action="/descargaReportCitasConfirmadas">
                                <input type="hidden" value="{{date('d/m/Y')}}" id="fecha_inicioP" name="fecha_inicio">
                                <input type="hidden" value="{{date('d/m/Y')}}" id="fecha_finP" name="fecha_fin">
                                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save"></span> Descargar Reporte</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection