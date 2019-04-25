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
            var checkin = $('#dpd1sftp').datepicker({
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
            $('#diners').DataTable( {
                "scrollY": 500,
                "scrollX": true,
                "order": [[ 0, "desc" ]]
            } );
        } );
    </script>

@endsection
@section('content')
<div class="col-lg-12">
    <div class="panel with-nav-tabs panel-primary">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#r_sftp" data-toggle="tab" onclick="$('#reporte_nro').val(1);">REPORTE SFTP</a></li>
                <li class="nav-item"><a href="#r_repuracion_meta" data-toggle="tab" onclick="$('#reporte_nro').val(2);">REPORTE RECUPERACIÓN VS META</a></li>
                <li class="nav-item"><a href="#r_focalizacion_cartera" data-toggle="tab" onclick="$('#reporte_nro').val(3);">REPORTE FOCALIZACIONES DE CARTERA</a></li>
                <li class="nav-item"><a href="#r_cobertura_diaria" data-toggle="tab" onclick="$('#reporte_nro').val(4);">INFOME DIARIO DE COBERTURA</a></li>
                <li class="nav-item"><a href="#r_marcaciones" data-toggle="tab" onclick="$('#reporte_nro').val(5);">REPORTE DE MARCACIONES</a></li>
                <li class="nav-item"><a href="#r_general_campo" data-toggle="tab" onclick="$('#reporte_nro').val(6);">GENERAL DE CUENTAS CAMPO</a></li>
                <li class="nav-item"><a href="#r_general_legal" data-toggle="tab" onclick="$('#reporte_nro').val(7);">GENERAL DE CUENTAS LEGAL</a></li>
                <li class="nav-item"><a href="cuentasX88S">CUENTAS X88</a></li>
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border" >
                        <div class="tab-pane fade active in" id="r_sftp">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="sftpDiners" method="post">
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
                                                                            <div class="radio">
                                                                                <label><input name="envio" type="radio" value="0" checked> No enviar</label>
                                                                            </div>
                                                                            <div class="radio">
                                                                                <label><input name="envio" type="radio" value="1"> Enviar</label>
                                                                            </div>
                                                                            <button type="submit" class="btn btn-success" name="descargar" value="0"><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                                                                        </div>
                                                                        <div class="col-md-6 col-lg-2">
                                                                            <div align="center"><span class="glyphicon glyphicon-calendar"></span> Seleccione la fecha
                                                                                <input type="text" class="span2 form-control" value="{{date('d/m/Y')}}" id="dpd1sftp" name="fecha_inicio_sftp" required="" readonly>
                                                                            </div>
                                                                            <button type="submit" class="btn btn-success" name="descargar" value="1"><span class="glyphicon glyphicon-download-alt"></span> Descargar consolidado</button>
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
                                            <div id="loader-icon1" style="display:none; color: green;padding-top: 5px" align="center">
                                                <img src="{{asset('images/loading.gif')}}" width="70"><br>PROCESANDO . . .
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="r_repuracion_meta">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="recupMetaDinersR" method="post">
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
                                        </table>
                                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
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
                        <div class="tab-pane fade" id="r_focalizacion_cartera">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="focalizacionCartera" method="post">
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

                                                        <select class="form-control" title="SELECCIONE UNO" name="id_campana" id="id_campana" required>
                                                            <option value="">Seleccione Uno</option>
                                                        </select>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr><th>Porcentaje de focalización:<br>
                                                    <hr>
                                                    DINERS<br>
                                                    <table>
                                                        <tr>
                                                            <td>+ 90 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control " name="diners_mas_90">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                            <td>90 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control" name="diners_90">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                            <td>60 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control " name="diners_60">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                            <td>30 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control" name="diners_30">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                        </tr>
                                                    </table>
                                                    <hr>
                                                    VISA<br>
                                                    <table>
                                                        <tr>
                                                            <td>+ 90 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control " name="visa_mas_90">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                            <td>90 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control" name="visa_90">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                            <td>60 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control " name="visa_60">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                            <td>30 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control" name="visa_30">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                        </tr>
                                                    </table>
                                                    <hr>
                                                    DISCOVER<br>
                                                    <table>
                                                        <tr>
                                                            <td>+ 90 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control " name="discover_mas_90">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                            <td>90 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control" name="discover_90">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                            <td>60 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control " name="discover_60">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                            <td>30 días:
                                                                <div class='input-group date col-lg-5'>
                                                                    <input value="0" type="number" class="form-control" name="discover_30">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon"></span></span>
                                                                </div></td>
                                                        </tr>
                                                    </table>
                                                </th>
                                            </tr>

                                            </thead>
                                        </table>
                                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                                    </div>
                                </form>
                                <nav class="navbar navbar-default">
                                    <div class="container-fluid">
                                        <div class="navbar-header">
                                            <a class="navbar-brand" href="#" id="cuentas3" style="color: #000; margin-bottom: 15px"></a>
                                            <div id="loader-icon3" style="display:none; color: green;padding-top: 5px" align="center">
                                                <img src="{{asset('images/loading.gif')}}" width="70"><br>PROCESANDO . . .
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="r_cobertura_diaria">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="r_marcaciones" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="r_marcaciones">
                            <div class="col-md-3 col-lg-3 col-md-offset-4 col-lg-offset-4">
                                <form role="search" action="reporteMarcaciones" method="post">
                                    {{ csrf_field() }}
                                    <div class="well col-lg-offset-4 col-md-offset-4" align="center">
                                        <table class="table" align="center">
                                            <thead>
                                            <tr>
                                                <th><div align="center"><span class="glyphicon glyphicon-calendar"></span> Seleccione la fecha</div>
                                                    <div class="input-group date" id="datetimepicker1">
                                                        <input type="text" class="span2 form-control" value="{{date('d/m/Y')}}" id="dpd1" name="fecha_inicio" required="" readonly>
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
                        <div class="tab-pane fade" id="r_general_campo">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="rgeneralCuentasDinersC" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Marca:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_marca" id="id_marca" required readonly>
                                                            <option value="2" selected>Diners</option>
                                                        </select>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr><th>Producto:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_producto" id="id_producto" required readonly>
                                                            <option value="1" selected>Campo</option>
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
                                                            @if(!empty($campanas_campo))
                                                                @foreach($campanas_campo as $key => $value)
                                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                                @endforeach
                                                            @endif
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
                                            <a class="navbar-brand" href="#" id="cuentas6" style="color: #000; margin-bottom: 15px"></a>
                                            <div id="loader-icon6" style="display:none; color: green;padding-top: 5px" align="center">
                                                <img src="{{asset('images/loading.gif')}}" width="70"><br>PROCESANDO . . .
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="r_general_legal">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="rgeneralCuentasDinersLegal" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Marca:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_marca" id="id_marca" required readonly>
                                                            <option value="10" selected>Diners Legal</option>
                                                        </select>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr><th>Producto:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_producto" id="id_producto" required readonly>
                                                            <option value="19" selected>Legal</option>
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
                                                            @if(!empty($campanas_legal))
                                                                @foreach($campanas_legal as $key => $value)
                                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                                @endforeach
                                                            @endif
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
                                            <a class="navbar-brand" href="#" id="cuentas7" style="color: #000; margin-bottom: 15px"></a>
                                            <div id="loader-icon7" style="display:none; color: green;padding-top: 5px" align="center">
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