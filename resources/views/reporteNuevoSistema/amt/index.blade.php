@extends('layouts.appReportesNuevoSistema')
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Latest compiled and minified CSS -->
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
@endsection
@section('content')

<div class="col-lg-12">
    <div class="panel with-nav-tabs panel-info">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#primer_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(1);">REPORTE ATM HISTORIAL DE GESTIONES</a></li>
                <li class="nav-item"><a href="#general_cuentas" data-toggle="tab" onclick="$('#reporte_nro').val(2);">GENERAL DE CUENTAS ATM</a></li>
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border" >
                        <div class="tab-pane fade active in" id="primer_reporte">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="reporteAmt" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Marca:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_marca" id="id_marca" required readonly>
                                                            <option selected>Seleccione Uno</option>
                                                            <option value="13">AUTORIDAD DE TRANSITO MUNICIPAL</option>
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

                                                        <select class="form-control" title="SELECCIONE UNO" name="id_campana" id="id_campana" required>
                                                            <option value="">Seleccione Uno</option>
                                                        </select>

                                                        <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon"></span>
                                                                </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Fecha inicio:
                                                    <div class="input-group date" id="datetimepicker1">
                                                        <input type="text" class="span2 form-control" value="{{isset($fecha_inicio) ? $fecha_inicio : date('d/m/Y')}}" id="dpd1" name="fecha_inicio" required="" readonly>
                                                        <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>
                                                    <table class="table">
                                                        <tr>
                                                            <th>
                                                                <div class="col-md-6 col-lg-3">
                                                                    <button type="submit" class="btn btn-success" name="descargar" value="0"><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>


                                    </div>
                                </form>
                                <nav class="navbar navbar-default">
                                    <div class="container-fluid">
                                        <div class="navbar-header">
                                            <a class="navbar-brand" href="#" id="cuentas1" style="color: #000; margin-bottom: 15px"></a>
                                            <div id="loader-icon2" style="display:none; color: green;padding-top: 5px" align="center">
                                                <img src="{{asset('images/loading.gif')}}" width="70"><br>PROCESANDO . . .
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="general_cuentas">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="reporteGeneralCuentasAtm" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Marca:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_marca" id="id_marca" required readonly>
                                                            <option selected>Seleccione Uno</option>
                                                            <option value="13" selected>AUTORIDAD DE TRANSITO MUNICIPAL</option>
                                                        </select>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr><th>Producto:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_producto" id="id_producto" required>
                                                            <option selected>Seleccione Uno</option>
                                                            <option value="24">ATM</option>
                                                        </select>
                                                        <span class="input-group-addon">
                                <span class="glyphicon glyphicon"></span>
                                </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr><th>Campaña:
                                                    <div class='input-group date' id=''>

                                                        <select class="form-control" title="SELECCIONE UNO" name="id_campana" id="id_campana" required>
                                                            <option value="">Seleccione Uno</option>
                                                        </select>

                                                        <span class="input-group-addon">
                                <span class="glyphicon glyphicon"></span>
                                </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>
                                                    <table class="table">
                                                        <tr>
                                                            <th>
                                                                <div class="col-md-6 col-lg-3">
                                                                    <button type="submit" class="btn btn-success" name="descargar" value="0"><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                    </div>
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
<script type="application/javascript">
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
        $("#cuentas"+reporte_nro).html('- Calculando...');
        $.ajax({
            url: "/gCuentas",
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