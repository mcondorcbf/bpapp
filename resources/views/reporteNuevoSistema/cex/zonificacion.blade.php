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

    <link rel="stylesheet" href="/assets/loading/jquery.loading-indicator.css">
    <script src="/assets/loading/jquery.loading-indicator.js"></script>

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
                @if($supervisores==1)
                    <li class="nav-item"><a href="{{url('/rCexAsignaciones')}}">REPORTE ASIGNACIONES CEX</a></li>
                    <li class="nav-item"><a href="{{url('/rCexCumplimiento')}}">REPORTE DE CUMPLIMIENTO</a></li>
                    <li class="active"><a href="{{url('/rCexZonificacion')}}">REPORTE ZONIFICACION</a></li>
                @else
                <li class="nav-item"><a href="{{url('/rCex')}}">CONTROL DE ASISTENCIA (DISPOSITIVOS)</a></li>
                <li class="nav-item"><a href="{{url('/rCexCumplimiento')}}">REPORTE DE CUMPLIMIENTO</a></li>
                <li class="nav-item"><a href="{{url('/rCexAsignaciones')}}">REPORTE ASIGNACIONES CEX</a></li>
                <li class="nav-item"><a href="{{url('/rCexAsignaciones2')}}">INVENTARIO</a></li>
                <li class="nav-item"><a href="{{url('/rCexInventario')}}">RESUMEN ASIGNACION</a></li>
                <li class="active"><a href="{{url('/rCexZonificacion')}}">REPORTE ZONIFICACION</a></li>
                @endif
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border" >
                        <div class="tab-pane fade active in" id="primer_reporte">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="rZonificacion" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Marca:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_marca" id="id_marca" required>
                                                            <option value="">Seleccione Uno</option>
                                                            @foreach($marcas as $marca)
                                                                <option value="{{$marca->id}}">{{$marca->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr><th>Producto:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_producto" id="id_producto" required>
                                                            <option value="">Seleccione Uno</option>
                                                        </select>
                                                        <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon"></span>
                                                                </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr><th>Campaña:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_campana[]" id="id_campana" multiple required>
                                                            <option value="">Seleccione Uno</option>
                                                        </select>
                                                        <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon"></span>
                                                                </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>

                                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                                    </div>
                                </form>

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
                $("select[name='id_campana[]']").html('');
                $("select[name='id_campana[]']").html(data.options);
                $("#cuentas"+reporte_nro).html('');
                homeLoader.hide();
            }
        });
    });


</script>
@endsection