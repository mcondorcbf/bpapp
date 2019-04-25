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
            $('#tbl_ca1').DataTable( {
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[-1,10,20,30], ['Todo',10,20,30]]
            } );
            $('#tbl_ca2').DataTable( {
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[-1,10,20,30], ['Todo',10,20,30]]
            } );
            $('#tbl_ca3').DataTable( {
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[-1,10,20,30], ['Todo',10,20,30]]
            } );
            $('#tbl_ca4').DataTable( {
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[-1,10,20,30], ['Todo',10,20,30]]
            } );
            $('#tbl_ca5').DataTable( {
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[-1,10,20,30], ['Todo',10,20,30]]
            } );
            $('#tbl_ca6').DataTable( {
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[-1,10,20,30], ['Todo',10,20,30]]
            } );
        } );

    </script>

@endsection
@section('content')

<div class="col-lg-12">
    <div class="panel with-nav-tabs panel-primary">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                @if($supervisores==1)
                    <li class="nav-item"><a href="{{url('/rCexAsignaciones')}}">REPORTE ASIGNACIONES CEX</a></li>
                    <li class="active"><a href="{{url('/rCexCumplimiento')}}">REPORTE DE CUMPLIMIENTO</a></li>
                    <li class="nav-item"><a href="{{url('/rCexZonificacion')}}">REPORTE ZONIFICACION</a></li>
                @else
                    <li class="nav-item"><a href="{{url('/rCex')}}">CONTROL DE ASISTENCIA (DISPOSITIVOS)</a></li>
                    <li class="active"><a href="{{url('/rCexCumplimiento')}}">REPORTE DE CUMPLIMIENTO</a></li>
                    <li class="nav-item"><a href="{{url('/rCexAsignaciones')}}">REPORTE ASIGNACIONES CEX</a></li>
                    <li class="nav-item"><a href="{{url('/rCexAsignaciones2')}}">INVENTARIO</a></li>
                    <li class="nav-item"><a href="{{url('/rCexInventario')}}">RESUMEN ASIGNACION</a></li>
                    <li class="nav-item"><a href="{{url('/rCexZonificacion')}}">REPORTE ZONIFICACION</a></li>
                @endif
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <form role="search" action="rCexCumplimientoP" method="post">
                    {{ csrf_field() }}
                    <div class="well">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Fecha inicio:
                                    <div class="input-group date" id="datetimepicker1">
                                        <input type="text" class="span2 form-control" value="{{date('d/m/Y')}}" id="dpd1" name="fecha_inicio" required="" readonly>
                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                    </div>
                                </th>
                                <th>Fecha fin:
                                    <div class="input-group date" id="datetimepicker1">
                                        <input type="text" class="span2 form-control" value="{{date('d/m/Y')}}" id="dpd2" name="fecha_fin" required="" readonly>
                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                    </div>
                </form>
               {{-- <div class="panel with-nav-tabs panel-success">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#primer_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(1);">BELCORP</a></li>
                        <li class="nav-item"><a href="#segundo_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(2);">DINERS</a></li>
                        <li class="nav-item"><a href="#tercer_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(3);">MULTIMARCAS</a></li>
                        <input type="hidden" id="reporte_nro" value="1">
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="tab-content tab-content-border" >
                            <div class="tab-pane fade active in" id="primer_reporte">
                                <div class="col-md-12 col-lg-12">
                                    <form role="search" action="" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="fecha_inicio" value="{{isset($fecha_inicio)}}">
                                        <input type="hidden" name="fecha_fin" value="{{isset($fecha_inicio)}}">
                                        <div class="well">
                                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade active in" id="segundo_reporte">
                                <div class="col-md-12 col-lg-12">
                                </div>
                            </div>

                            <div class="tab-pane fade active in" id="tercer_reporte">
                                <div class="col-md-12 col-lg-12">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>--}}


            </div>
        </div>
    </div>
</div>
@endsection