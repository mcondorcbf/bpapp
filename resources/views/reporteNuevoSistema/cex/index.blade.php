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
        $( document ).ready(function() {
            $('#fecha').datepicker();
            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

            var checkin = $('#dpd1').datepicker({
                onRender: function(date) {
                    return date.valueOf() < now.valueOf() ? 'enabled' : '';
                }
            }).on('changeDate', function(ev){
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
            $('#tbl_ca').DataTable( {
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[-1,10,50,100], ['Todo',10,50,100]]
            } );
        } );

        $(document).ready(function() {
            $('#example-getting-started').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true
            });
        });

    </script>
    <script type="text/javascript" src="{{asset('js/bootstrap-multiselect.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/bootstrap-multiselect.css')}}" type="text/css"/>
@endsection
@section('content')

<div class="col-lg-12">
    <div class="panel with-nav-tabs panel-primary">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="{{url('/rCex')}}">CONTROL DE ASISTENCIA (DISPOSITIVOS)</a></li>
                <li class="nav-item"><a href="{{url('/rCexCumplimiento')}}">REPORTE DE CUMPLIMIENTO</a></li>
                <li class="nav-item"><a href="{{url('/rCexAsignaciones')}}">REPORTE ASIGNACIONES CEX</a></li>
                <li class="nav-item"><a href="{{url('/rCexAsignaciones2')}}">INVENTARIO</a></li>
                <li class="nav-item"><a href="{{url('/rCexInventario')}}">RESUMEN ASIGNACION</a></li>
                <li class="nav-item"><a href="{{url('/rCexZonificacion')}}">REPORTE ZONIFICACION</a></li>
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border" >
                        <div class="tab-pane fade active in" id="primer_reporte">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="rAsistencia" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Fecha inicio:
                                                    <div class="input-group date" id="datetimepicker1">
                                                        <input type="text" class="span2 form-control" value="{{isset($fecha_inicio) ? $fecha_inicio : date('d/m/Y')}}" id="dpd1" name="fecha_inicio" required="" readonly>
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th>Fecha fin:
                                                    <div class="input-group date" id="datetimepicker1">
                                                        <input type="text" class="span2 form-control" value="{{isset($fecha_fin) ? $fecha_fin : date('d/m/Y')}}" id="dpd2" name="fecha_fin" required="" readonly>
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </th>
                                                <th>Gestores:
                                                    <div class="input-group date" id="datetimepicker1">
                                                        {!! Form::select('gestores[]',[]+$gestores,null,['class'=>'form-control','multiple'=>'multiple','id'=>'example-getting-started']) !!}
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span> Procesar</button>
                                    </div>
                                </form>

                                <table id="tbl_ca" class="table table-sm table-dark" style="width:100%; font-size: 11px;">
                                    <thead class="alert alert-info">
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
                                        <th>OBSERVACIÓN</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1;?>
                                    @foreach($reportes as $k)
                                    {{--@if(strtotime($k->tiempos_muertos)>strtotime($tiemposMuertos) || $k->tiempos_muertos=='00:00:00')
                                        <tr class="alert alert-danger">
                                    @else
                                        <tr>
                                    @endif--}}
                                    <tr class="alert alert-danger">
                                        <td>{{$i}}</td>
                                        <td>{{$k->gestor_cex}}</td>
                                        <td>{{$k->fecha}}</td>
                                        <td>{{$k->hora_primera_gestion}}</td>
                                        <td>{{$k->hora_ultima_gestion}}</td>
                                        <td>{{$k->hora_almuerzo}}</td>
                                        <td>{{$k->horas_trabajadas}}</td>
                                        <td>{{$k->jornada_completa}}</td>
                                        <td>{{$k->tiempos_muertos}}</td>
                                        <td>{{$k->tiempo_medicion}}</td>
                                        <td>{{$k->porcentaje_ocupacion}}%</td>
                                        <td>
                                            <?php $observaciones=\App\reportesNuevoSistema\cex\cex_observacion_horast::where('horas_trabajadas_id',$k->id)->get();?>
                                            <ul>
                                            @foreach($observaciones as $observacion)
                                                <li><strong>{{$observacion->tipoCex->nombre}}</strong> <br> - {{$observacion->fecha}} | {{$observacion->cedula}} {{$observacion->nombres}} {{$observacion->observacion}} | {{$observacion->hora_inicio}} {{$observacion->hora_fin}}</li>
                                            @endforeach
                                            </ul>
                                            <form role="search" action="obCexAsistencia" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{$k->id}}">
                                                <input type="hidden" name="fecha_inicio" value="{{isset($fecha_inicio) ? $fecha_inicio : date('d/m/Y')}}">
                                                <input type="hidden" name="fecha_fin" value="{{isset($fecha_fin) ? $fecha_fin : date('d/m/Y')}}">
                                                <button class="modalButton btn btn-danger btn-xs">Ingresar nueva observación</button>
                                            </form>
                                        {{--@if(strtotime($k->tiempos_muertos)>strtotime($tiemposMuertos) || $k->tiempos_muertos=='00:00:00')
                                            <a href="{{ url('/obCexAsistencia/'.$k->id) }}" class="modalButton btn btn-danger btn-xs">Ingresar nueva observación</a>
                                        @else
                                            --}}{{--<a href="{{ url('/obCexAsistencia/'.$k->id) }}" class="modalButton btn btn-warning btn-xs">Ingresar nueva observación</a>--}}{{--
                                        @endif--}}
                                        </td>
                                    </tr><?php $i++;?>
                                    @endforeach
                                    </tbody>
                                </table>
                                <form role="search" action="rAsistenciaExcel" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="fecha_inicio" value="{{isset($fecha_inicio) ? $fecha_inicio : date('d/m/Y')}}">
                                    <input type="hidden" name="fecha_fin" value="{{isset($fecha_fin) ? $fecha_fin : date('d/m/Y')}}">
                                    <input type="hidden" name="gestores" value="{{isset($gestoresa) ? str_replace('"','',$gestoresa)   : ''}}">
                                    <div class="well">
                                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="segundo_reporte">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="#">
                                </form>
                                <nav class="navbar navbar-default">
                                    <div class="container-fluid">
                                        <div class="navbar-header">
                                            <a class="navbar-brand" href="#" id="cuentas2" style="color: #000; margin-bottom: 15px"></a>
                                            <div id="loader-icon2" style="display:none; color: green;padding-top: 5px" align="center">
                                                <img src="{{asset('images/loading.gif')}}" width="70"><br>PROCESANDO . . .
                                            </div>
                                        </div>
                                    </div>
                                </nav>
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

</script>
@endsection