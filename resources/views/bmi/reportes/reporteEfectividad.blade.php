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
                "scrollX": true,
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

                    //return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
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
                var url = '{{URL::to('reportEfectividadBmiPost')}}';
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
                        document.getElementById("hist").value=0;
                        $.each(data['reportes'], function (key, item) {
                            $("#data2").append("<tr><td>"+ count +"</td><td><button type='button' class='modalButton1" + item['cedula_asesor'] + " btn btn-primary btn-xs' data-toggle='modal' data-src='/usuarioGestionesShow/" + item['cedula_asesor'] + "' data-width='570' data-height='281' data-target='#myModal' data-video-fullscreen='' onclick='alerta(1" + item['cedula_asesor'] + ")'>" + item['nombre_asesor'] + "</button></td><td>" + item['citas_programadas'] + "</td><td>" + item['visitas_realizadas'] + "</td><td>" + item['porcentaje_cumplimiento'] + "%</td><td>" + item['seguimiento'] + "</td><td>" + item['cierre_de_ventas'] + " </td><td>" + item['porcentaje_eficiencia'] + "%</td><td>" + item['no_efectivas'] + "</td>  <td>" + item['citas_programadas_p'] + "</td><td>" + item['visitas_realizadas_p'] + "</td><td>" + item['porcentaje_cumplimiento_p'] + "%</td><td>" + item['seguimiento_p'] + "</td><td>" + item['cierre_de_ventas_p'] + " </td><td>" + item['porcentaje_eficiencia_p'] + "%</td><td>" + item['no_efectivas_p'] + "</td>  <td>" + item['citas_programadas_m'] + "</td><td>" + item['visitas_realizadas_m'] + "</td><td>" + item['porcentaje_cumplimiento_m'] + "%</td><td>" + item['seguimiento_m'] + "</td><td>" + item['cierre_de_ventas_m'] + " </td><td>" + item['porcentaje_eficiencia_m'] + "%</td><td>" + item['no_efectivas_m'] + "</td>  <td>" + item['total_citas_programadas'] + "</td><td>" + item['total_visitas_realizadas'] + "</td><td>" + item['total_cumplimiento'] + "</td><td>" + item['total_seguimiento'] + "</td><td>" + item['total_cierre_de_ventas'] + "</td><td>" + item['total_eficiencia'] + "</td><td>" + item['total_no_efectivas'] + "</td></tr>");

                            count++;
                        })

                        $("#data2f").empty();
                        $("#data2f").append("<tr><th></th><th></th><th>" + data['citas_programadas_t'] + "</th><th>" + data['visitas_realizadas_t'] + "</th><th>" + data['porcentaje_cumplimiento_t'] + "%</th><th>" + data['seguimiento_t'] + "</th><th>" + data['cierre_ventas_t'] + " </th><th>" + data['porcentaje_eficiencia_t'] + "%</th><th>" + data['no_efectivas_t'] + "</th>  <th>" + data['citas_programadas_p_t'] + "</th><th>" + data['visitas_realizadas_p_t'] + "</th><th>" + data['porcentaje_cumplimiento_p_t'] + "%</th><th>" + data['seguimiento_p_t'] + "</th><th>" + data['cierre_ventas_p_t'] + " </th><th>" + data['porcentaje_eficiencia_p_t'] + "%</th><th>" + data['no_efectivas_p_t'] + "</th>  <th>" + data['citas_programadas_m_t'] + "</th><th>" + data['visitas_realizadas_m_t'] + "</th><th>" + data['porcentaje_cumplimiento_m_t'] + "%</th><th>" + data['seguimiento_m_t'] + "</th><th>" + data['cierre_ventas_m_t'] + " </th><th>" + data['porcentaje_eficiencia_m_t'] + "%</th><th>" + data['no_efectivas_m_t'] + "</th>  <th>" + data['totales_citas'] + "</th><th>" + data['totales_visitas_realizadas'] + "</th><th>" + data['totales_procentaje_cumplimiento'] + "</th><th>" + data['totales_seguimiento'] + "</th><th>" + data['totales_cierre_ventas'] + "</th><th>" + data['totales_procentaje_eficiencia'] + "</th><th>" + data['totales_no_efectivas'] + "</th></tr>");
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

        function  alerta(id) {
            var src = $('.modalButton'+id).attr('data-src');
            var width = 780;
            var height =500;

            var allowfullscreen = $(this).attr('data-video-fullscreen');

            $("#myModal iframe").attr({
                'src': src,
                'height': 500,
                'width': 780,
                'allowfullscreen':''
            });

            $('#myModal').on('hidden.bs.modal', function(){
                $(this).find('iframe').html("");
                $(this).find('iframe').attr("src", "");
            });
        }

    </script>
@endsection
@section('content')
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:830px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="body">
                <iframe class="embed-responsive-item" src="" id="frame" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="container col-xs-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">REPORTE  EFECTIVIDAD</div>

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
                                                    <input type="text" class="span2 form-control" value="" id="dpd1" name="fecha_inicio" required readonly>
                                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                                </div>
                                            </th>
                                            <th>Fecha fin:
                                                <div class='input-group date' id='datetimepicker1'>
                                                    <input type="text" class="span2 form-control" value="" id="dpd2" name="fecha_fin" required readonly>
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
                                <table id="lista" class="display" cellspacing="0" style="font-size: 11px">
                                    <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th colspan="7" style="background-color: #71B8FF;text-align:  center;">CITAS AGENCIA</th>
                                        <th colspan="7" style="background-color: #F7FE72;text-align:  center;">CITAS PROPIAS</th>
                                        <th colspan="7" style="background-color: #70d44f;text-align:  center;">CITAS MANUALES</th>
                                        <th colspan="7" style="background-color: #f0f0ee;text-align:  center;">TOTALES</th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>ASESOR</th>
                                        <th>CITAS PROGRAMADAS</th>
                                        <th>VISITAS REALIZADAS</th>
                                        <th>% CUMPLIMIENTO</th>
                                        <th>SEGUIMIENTO</th>
                                        <th>CIERRE DE VENTAS</th>
                                        <th>% EFICIENCIA</th>
                                        <th>NO EFECTIVAS</th>

                                        <th>CITAS PROGRAMADAS</th>
                                        <th>VISITAS REALIZADAS</th>
                                        <th>% CUMPLIMIENTO</th>
                                        <th>SEGUIMIENTO</th>
                                        <th>CIERRE DE VENTAS</th>
                                        <th>% EFICIENCIA</th>
                                        <th>NO EFECTIVAS</th>

                                        <th>CITAS PROGRAMADAS</th>
                                        <th>VISITAS REALIZADAS</th>
                                        <th>% CUMPLIMIENTO</th>
                                        <th>SEGUIMIENTO</th>
                                        <th>CIERRE DE VENTAS</th>
                                        <th>% EFICIENCIA</th>
                                        <th>NO EFECTIVAS</th>

                                        <th>TOTAL CITAS PROGRAMADAS</th>
                                        <th>TOTAL VISITAS REALIZADAS</th>
                                        <th>TOTAL % CUMPLIMIENTO</th>
                                        <th>TOTAL SEGUIMIENTO</th>
                                        <th>TOTAL CIERRE DE VENTAS</th>
                                        <th>TOTAL % EFICIENCIA</th>
                                        <th>TOTAL NO EFECTIVAS</th>

                                    </tr>
                                    </thead>
                                    <tbody id="data2">
                                    <?php $i=1;?>
                                    @foreach($reportes as $k)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td><button type="button" class="modalButton1{{$k->cedula_asesor}} btn btn-primary btn-xs" data-toggle="modal" data-src="{{url('/usuarioGestionesShow/'.$k->cedula_asesor)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_asesor}})">{{$k->nombre_asesor}}</button>
                                            </td>
                                            <td>{{$k->citas_programadas}}</td>
                                            <td>{{$k->visitas_realizadas}}</td>
                                            <td>{{$k->porcentaje_cumplimiento}}%</td>
                                            <td>{{$k->seguimiento}}</td>
                                            <td>{{$k->cierre_de_ventas}}</td>
                                            <td>{{$k->porcentaje_eficiencia}}%</td>
                                            <td>{{$k->no_efectivas}}</td>

                                            <td>{{$k->citas_programadas_p}}</td>
                                            <td>{{$k->visitas_realizadas_p}}</td>
                                            <td>{{$k->porcentaje_cumplimiento_p}}%</td>
                                            <td>{{$k->seguimiento_p}}</td>
                                            <td>{{$k->cierre_de_ventas_p}}</td>
                                            <td>{{$k->porcentaje_eficiencia_p}}%</td>
                                            <td>{{$k->no_efectivas_p}}</td>

                                            <td>{{$k->citas_programadas_m}}</td>
                                            <td>{{$k->visitas_realizadas_m}}</td>
                                            <td>{{$k->porcentaje_cumplimiento_m}}%</td>
                                            <td>{{$k->seguimiento_m}}</td>
                                            <td>{{$k->cierre_de_ventas_m}}</td>
                                            <td>{{$k->porcentaje_eficiencia_m}}%</td>
                                            <td>{{$k->no_efectivas_m}}</td>

                                            <td>{{$k->total_citas_programadas}}</td>
                                            <td>{{$k->total_visitas_realizadas}}</td>
                                            <td>{{$k->total_cumplimiento}}</td>
                                            <td>{{$k->total_seguimiento}}</td>
                                            <td>{{$k->total_cierre_de_ventas}}</td>
                                            <td>{{$k->total_eficiencia}}</td>
                                            <td>{{$k->total_no_efectivas}}</td>
                                            <?php $i++;?>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot id="data2f">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>{{$citas_programadas_t}}</th>
                                        <th>{{$visitas_realizadas_t}}</th>
                                        <th>{{$porcentaje_cumplimiento_t}}%</th>
                                        <th>{{$seguimiento_t}}</th>
                                        <th>{{$cierre_ventas_t}}</th>
                                        <th>{{$porcentaje_eficiencia_t}}%</th>
                                        <th>{{$no_efectivas_t}}</th>

                                        <th>{{$citas_programadas_p_t}}</th>
                                        <th>{{$visitas_realizadas_p_t}}</th>
                                        <th>{{$porcentaje_cumplimiento_p_t}}%</th>
                                        <th>{{$seguimiento_p_t}}</th>
                                        <th>{{$cierre_ventas_p_t}}</th>
                                        <th>{{$porcentaje_eficiencia_p_t}}%</th>
                                        <th>{{$no_efectivas_p_t}}</th>

                                        <th>{{$citas_programadas_m_t}}</th>
                                        <th>{{$visitas_realizadas_m_t}}</th>
                                        <th>{{$porcentaje_cumplimiento_m_t}}%</th>
                                        <th>{{$seguimiento_m_t}}</th>
                                        <th>{{$cierre_ventas_m_t}}</th>
                                        <th>{{$porcentaje_eficiencia_m_t}}%</th>
                                        <th>{{$no_efectivas_m_t}}</th>

                                        <th>{{$totales_citas}}</th>
                                        <th>{{$totales_visitas_realizadas}}</th>
                                        <th>{{$totales_procentaje_cumplimiento}}%</th>
                                        <th>{{$totales_seguimiento}}</th>
                                        <th>{{$totales_cierre_ventas}}</th>
                                        <th>{{$totales_procentaje_eficiencia}}%</th>
                                        <th>{{$totales_no_efectivas}}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div id="loader-icon" style="display:none; color: green;" align="center">
                                <img src="{{asset('images/loading.gif')}}"><br>PROCESANDO . . .
                            </div>
                            <div id="procesado" style="display:none;">

                            </div>
                        </div>
                        <form role="search" action="/descargarReportEfectividadBmi">
                            <input type="hidden" value="1" id="hist" name="hist">
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