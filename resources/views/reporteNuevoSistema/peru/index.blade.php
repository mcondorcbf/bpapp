@extends('layouts.appReportesNuevoSistema')
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/assets/loading/jquery.loading-indicator.css">
    <script src="/assets/loading/jquery.loading-indicator.js"></script>

    <script type="text/javascript" src="{{asset('js/bootstrap-multiselect.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/bootstrap-multiselect.css')}}" type="text/css"/>

@endsection
@section('content')

<div class="col-lg-12">
    <div class="panel with-nav-tabs panel-primary">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#primer_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(1);">BELCORP</a></li>
                <li class="nav-item"><a href="#segundo_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(2);">MARCAS FINANCIERAS</a></li>
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border" >
                        <div class="tab-pane fade active in" id="primer_reporte">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="rBGeneralPeru" method="post">
                                        {{ csrf_field() }}
                                            <div class="well">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Marca:
                                                            <div class='input-group date' id=''>
                                                                <select class="form-control" title="SELECCIONE UNO" name="id_marca" id="id_marca" required>
                                                                    <option value="3" selected >BELCORP</option>
                                                                </select>
                                                                <span class="input-group-addon"></span>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr><th>Producto:
                                                            <div class='input-group date' id=''>
                                                                <select class="form-control" title="SELECCIONE UNO" name="id_producto" id="id_producto" required>
                                                                    <option value="">Seleccione Uno</option>
                                                                    @foreach($productos_belcorp as $k)
                                                                        <option value="{{$k->id}}">{{$k->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon"></span>
                                </span>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Campaña:
                                                            <div class='input-group date' id=''>
                                                                <select class="form-control" title="SELECCIONE UNO" name="id_campana1" id="id_campana1" required>
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
                                <form role="search" action="rFGeneralPeru" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Marca:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_marca" id="id_marca" required>
                                                            <option value="1" selected >MARCAS FINANCIERAS</option>
                                                        </select>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr><th>Producto:
                                                    <div class='input-group date' id=''>
                                                        <select class="form-control" title="SELECCIONE UNO" name="id_producto" id="id_producto" required>
                                                            <option value="">Seleccione Uno</option>
                                                            @foreach($productos_mfinancieras as $k)
                                                                <option value="{{$k->id}}">{{$k->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="input-group-addon">
                                <span class="glyphicon glyphicon"></span>
                                </span>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr><th>Campaña:
                                                    <div class='input-group date' id=''>

                                                        <select class="form-control" title="SELECCIONE UNO" name="id_campana2" id="id_campana2" required >
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
            url: "/gProductoPe",
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
            url: "/gCampanaPe",
            method: 'POST',
            data: {id_producto:id_producto, _token:token},
            success: function(data) {
                $("select[name='id_campana"+reporte_nro+"']").html('');
                $("select[name='id_campana"+reporte_nro+"']").html(data.options);
                $("#cuentas"+reporte_nro).html('');
                homeLoader.hide();
            }
        });
    });

    $("select[name='id_campana1']").change(function(){
        var reporte_nro = $('#reporte_nro').val();
        $('#loader-icon'+reporte_nro).show();
        $("#cuentas"+reporte_nro).html('');
        var id_campana = $(this).val();
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "/gCuentasPe",
            method: 'POST',
            data: {id_campana:id_campana, _token:token},
            success: function(data) {
                console.log(reporte_nro);
                $('#loader-icon'+reporte_nro).hide();
                $("#cuentas"+reporte_nro).html('- '+data.cuentas+' Cuentas<br>'+'- '+data.gestiones+' Gestiones');
            }
        });
    });

    $("select[name='id_campana2']").change(function(){
        var reporte_nro = $('#reporte_nro').val();
        $('#loader-icon'+reporte_nro).show();
        $("#cuentas"+reporte_nro).html('');
        var id_campana = $(this).val();
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "/gCuentasPe",
            method: 'POST',
            data: {id_campana:id_campana, _token:token},
            success: function(data) {
                console.log(reporte_nro);
                $('#loader-icon'+reporte_nro).hide();
                $("#cuentas"+reporte_nro).html('- '+data.cuentas+' Cuentas<br>'+'- '+data.gestiones+' Gestiones');
            }
        });
    });


    function cuentas() {
        var countChecked = function() {
        var n = 0;



        var reporte_nro = $('#reporte_nro').val();

        if($("#cuentasInhabilitadas"+reporte_nro).is(':checked')) {
            var n = 1;
        } else {
            var n = 0;
        }


        $('#loader-icon'+reporte_nro).show();
        $("#cuentas"+reporte_nro).html('');
        var cuentasInhabilitadas = n;
        var id_campana = $('#id_campana'+reporte_nro).val();
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "/gCuentasPe",
            method: 'POST',
            data: {id_campana:id_campana, _token:token,cuentasInhabilitadas:cuentasInhabilitadas},
            success: function(data){
                $('#loader-icon'+reporte_nro).hide();
                $("#cuentas"+reporte_nro).html('- '+data.cuentas+' Cuentas<br>'+'- '+data.gestiones+' Gestiones');
            }
            });
        };
        countChecked();
        //$( "input[type=checkbox]" ).on( "click", countChecked );
    }

</script>
@endsection