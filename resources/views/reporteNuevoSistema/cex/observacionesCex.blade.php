@extends('layouts.appReportesNuevoSistemaCex')
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
            $('#diners').DataTable( {
                "scrollY": 500,
                "scrollX": true,
                "order": [[ 0, "desc" ]]
            } );
        } );

        $(document).ready(function() {
            $('#tbl_ca').DataTable( {

            } );
        } );
    </script>
@endsection
@section('content')

<div class="col-lg-12">
    <div class="panel with-nav-tabs panel-primary">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="{{url('/rCex')}}">CONTROL DE ASISTENCIA (DISPOSITIVOS)</a></li>
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border" >
                        <div class="tab-pane fade active in" id="primer_reporte">
                            <div class="col-md-12 col-lg-12">
                                <input type="hidden" name="fecha_inicio" value="{{isset($fecha_inicio)}}">
                                <input type="hidden" name="fecha_fin" value="{{isset($fecha_inicio)}}">
                                <table id="tbl_ca" class="display" style="width:100%">
                                    <thead class="alert alert-danger">
                                    <tr>
                                        <th>#</th>
                                        <th>GESTOR CEX</th>
                                        <th>FECHA</th>
                                        <th>HORA PRIMERA GESTION</th>
                                        <th>HORA ULTIMA GESTION</th>
                                        <th>HORA ALMUERZO</th>
                                        <th>HORAS TRABAJADAS</th>
                                        <th>JORNADA COMPLETA</th>
                                        <th>TIEMPOS MUERTOS</th>
                                        <th>TIEMPO MEDICION</th>
                                        <th>PORCENTAJE OCUPACIÓN</th>
                                    </tr>
                                    </thead>
                                    <tbody><?php $i=1;?>
                                    <tr class="alert alert-danger">
                                        <td>{{$i}}</td>
                                        <td>{{$reporte->gestor_cex}}</td>
                                        <td>{{$reporte->fecha}}</td>
                                        <td>{{$reporte->hora_primera_gestion}}</td>
                                        <td>{{$reporte->hora_ultima_gestion}}</td>
                                        <td>{{$reporte->hora_almuerzo}}</td>
                                        <td>{{$reporte->horas_trabajadas}}</td>
                                        <td>{{$reporte->jornada_completa}}</td>
                                        <td>{{$reporte->tiempos_muertos}}</td>
                                        <td>{{$reporte->tiempo_medicion}}</td>
                                        <td>{{$reporte->porcentaje_ocupacion}}%</td>
                                    </tr><?php $i++;?>
                                    </tbody>
                                </table>
                                <form role="search" action="/rControlAsistenciaObs" method="post">
                                {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{$reporte->id}}">
                                    <div class="well">
                                    <label>Observaciones anteriores:</label>
                                        <?php $observaciones=\App\reportesNuevoSistema\cex\cex_observacion_horast::where('horas_trabajadas_id',$reporte->id)->get();?>
                                        <ul>
                                            @foreach($observaciones as $observacion)
                                                <li><strong>{{$observacion->tipoCex->nombre}}</strong> <br> - {{$observacion->fecha}} | {{$observacion->cedula}} {{$observacion->nombres}} {{$observacion->observacion}} | {{$observacion->hora_inicio}} {{$observacion->hora_fin}}</li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="well">
                                        <label>Seleccione el tipo de observación:</label>
                                        {!! Form::select('tipoObservacion',[''=>'--- Seleccione uno---']+$tipoObservacion,null,['class'=>'form-control','id'=>'tipoObservacion','onchange'=>'almuerzos()']) !!}
                                        <div id="almuerzo" class="col-lg-12" style="display: none"><br>
                                            <div class="col-lg-1">
                                                <label>Hora Inicio:</label>
                                            </div>
                                            <div class="col-lg-2">
                                                <input class="form-control" type="time" name="inicio" id="inicio" required disabled>
                                            </div>
                                            <div class="col-lg-1">
                                                <label>Hora Fin:</label>
                                            </div>
                                            <div class="col-lg-2">
                                                <input class="form-control" type="time" name="fin" id="fin" required disabled>
                                            </div>
                                        </div>

                                        <div id="negociaciones" class="col-lg-12" style="display: none"><br>
                                            <div class="col-lg-1">
                                                <label>Cédula:</label>
                                            </div>
                                            <div class="col-lg-2">
                                                <input class="form-control" type="text" name="cedula" id="cedula" required disabled>
                                            </div>
                                            <div class="col-lg-1">
                                                <label>Nombres:</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="nombres" id="nombres" required disabled>
                                            </div>
                                        </div>


                                        <label>Ingrese la observación:</label>
                                        <textarea class="form-control" name="observacion" id="observacion" required></textarea>
                                        <br>
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{$id}}">
                                        <input type="hidden" name="fecha_inicio" value="{{isset($fecha_inicio) ? $fecha_inicio : date('d/m/Y')}}">
                                        <input type="hidden" name="fecha_fin" value="{{isset($fecha_fin) ? $fecha_fin : date('d/m/Y')}}">
                                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-download-alt"></span> Guardar</button>
                                    </div>
                                </form>
                                <form role="search" action="/rRegresarCex" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{$id}}">
                                    <input type="hidden" name="fecha_inicio" value="{{isset($fecha_inicio) ? $fecha_inicio : date('d/m/Y')}}">
                                    <input type="hidden" name="fecha_fin" value="{{isset($fecha_fin) ? $fecha_fin : date('d/m/Y')}}">
                                    <button type="submit" class="btn btn-success"> << Regresar</button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("select[name='id_marca']").change(function(){
        var reporte_nro = $('#reporte_nro').val();
        $('#loader-icon'+reporte_nro).hide();
        var id_marca = $(this).val();
        var token = $("input[name='_token']").val();
        var homeLoader = $('body').loadingIndicator({
            useImage: false,
        }).data("loadingIndicator");
        homeLoader.show();
        $.ajax({
            url: "/gProducto",
            method: 'POST',
            data: {id_marca:id_marca, _token:token},
            success: function(data) {
                $("select[name='id_producto']").html('');
                $("select[name='id_producto']").html(data.options);
                $("#cuentas"+reporte_nro).html('');
                homeLoader.hide();
            }
        });
    });

    $("select[name='id_producto']").change(function(){
        var reporte_nro = $('#reporte_nro').val();
        $('#loader-icon'+reporte_nro).hide();
        var id_producto = $(this).val();
        var token = $("input[name='_token']").val();
        var homeLoader = $('body').loadingIndicator({
            useImage: false,
        }).data("loadingIndicator");
        homeLoader.show();
        $.ajax({
            url: "/gCampana",
            method: 'POST',
            data: {id_producto:id_producto, _token:token},
            success: function(data) {
                $("select[name='id_campana']").html('');
                $("select[name='id_campana']").html(data.options);
                $("#cuentas"+reporte_nro).html('');
                homeLoader.hide();
            }
        });
    });

    $("select[name='id_campana']").change(function(){
        var reporte_nro = $('#reporte_nro').val();
        $('#loader-icon'+reporte_nro).show();
        $("#cuentas"+reporte_nro).html('');
        var id_campana = $(this).val();
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "/gCuentasGenerico",
            method: 'POST',
            data: {id_campana:id_campana, _token:token},
            success: function(data) {
                $('#loader-icon'+reporte_nro).hide();
                console.log(data.cuentas);
                $("#cuentas"+reporte_nro).html('- '+data.cuentas+' Cuentas<br>'+'- '+data.gestiones+' Gestiones');
            }
        });
    });

    function almuerzos(){
        console.log('ingtreso');
        var token = $("input[name='_token']").val();
        var id = $('#tipoObservacion').val();
        $.ajax({
            url: "/gTipos",
            method: 'POST',
            data: {id:id, _token:token},
            success: function(data) {

                if (data.tipo.id==9){
                    //VALIDADOR PARA NEGOCIACIONES
                    console.log(data.tipo.nombre);
                    document.getElementById("almuerzo").style.display= "none"
                    document.getElementById('inicio').value = "";
                    document.getElementById('inicio').disabled = true;
                    document.getElementById('fin').value = "";
                    document.getElementById('fin').disabled = true;
                    document.getElementById('observacion').required = true;

                    document.getElementById("negociaciones").style.display= "block"
                    document.getElementById('cedula').disabled = false;
                    document.getElementById('nombres').disabled = false;
                }else{
                    document.getElementById("negociaciones").style.display= "none"
                    document.getElementById('cedula').value = "";
                    document.getElementById('cedula').disabled = true;
                    document.getElementById('nombres').value = "";
                    document.getElementById('nombres').disabled = true;

                    document.getElementById("almuerzo").style.display= "none"
                    document.getElementById('inicio').value = "";
                    document.getElementById('inicio').disabled = true;
                    document.getElementById('fin').value = "";
                    document.getElementById('fin').disabled = true;
                    document.getElementById('observacion').required = true;
                }
                if (data.tipo.necesita_horas==1) {
                    document.getElementById("almuerzo").style.display= "block"
                    document.getElementById('inicio').disabled = false;
                    document.getElementById('fin').disabled = false;
                    document.getElementById('observacion').required = false;

                    document.getElementById("negociaciones").style.display= "none"
                    document.getElementById('cedula').disabled = true;
                    document.getElementById('nombres').disabled = true;
                }else{
                    document.getElementById("almuerzo").style.display= "none"
                    document.getElementById('inicio').value = "";
                    document.getElementById('inicio').disabled = true;
                    document.getElementById('fin').value = "";
                    document.getElementById('fin').disabled = true;
                    document.getElementById('observacion').required = true;
                }
            }
        });
    }
</script>
@endsection