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
    <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#primer_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(1);">GENERAL DE CUENTAS</a></li>
                <li class="nav-item"><a href="#segundo_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(2);">-</a></li>
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border" >
                        <div class="tab-pane fade active in" id="primer_reporte">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="rSemaforo" method="post">
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
                                            <a class="navbar-brand" href="#" id="cuentas1" style="color: #000; margin-bottom: 15px"></a>
                                            <div id="loader-icon1" style="display:none; color: green;padding-top: 5px" align="center">
                                                <img src="{{asset('images/loading.gif')}}" width="70"><br>PROCESANDO . . .
                                            </div>
                                        </div>
                                    </div>
                                </nav>
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