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
            "lengthMenu": [[5,10,50,-1], [5,10,50,'Todo']]
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
                <li class="active"><a href="{{url('/monitoreoCexRes')}}"><span class="glyphicon glyphicon-eye-open"> </span> MONITOREO EN LÍNEA RESUMEN</a></li>

                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border" >
                        <div class="tab-pane fade active in" id="primer_reporte">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="rMonitoreo" method="post">
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
                                                <th>
                                                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span> Procesar</button>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </form>

                                <div style="width: 100%; height: 250px; overflow-y: scroll;">
                                <table id="" class="table" style="width:100%; font-size: 11px;">
                                    <thead class="alert alert-success">
                                    <tr>
                                        <th>#</th>
                                        <th>CEDULA</th>
                                        <th>IMEI</th>
                                        <th>TELEFONO</th>
                                        <th>NOMBRES</th>
                                        <th>TIEMPO DE PARADA</th>
                                        <th>STATUS BATERIA</th>
                                        <th>STATUS APLICACION</th>
                                        <th>STATUS GPS</th>
                                        <th>OBSERVACION</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="data2">
                                    <?php $i=1;?>
                                    @foreach($dispositivos as $dispositivo)
                                        <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$dispositivo['cedula']}}</td>
                                        <td>{{$dispositivo['imei']}}</td>
                                        <td>{{$dispositivo['telefono']}}</td>
                                        <td>{{$dispositivo['nombre']}}</td>
                                        <td>{{$dispositivo['tiempo_parado']}}</td>
                                        <td>{{$dispositivo['bateria_porcentaje']}}</td>
                                        <td>{{$dispositivo['appStatus']}}</td>
                                        <td>{{$dispositivo['status_gps']}}</td>
                                        <td>{{$dispositivo['alerta_mensaje']}}</td>
                                        <td><button class="modalButton btn btn-{{$dispositivo['alerta']}} btn-xs" id="button{{$dispositivo['id']}}" onclick="datos('{{$dispositivo['cedula']}}','{{isset($fecha) ? $fecha: date('Y-m-d')}}','{{$dispositivo['imei']}}')"><span class="glyphicon glyphicon-eye-open"> </span> Monitorear</button></td>
                                        </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                <iframe src="" width="100%" height="0" frameborder="0" scrolling="yes" id="iframe"></iframe>
                                </div>

                                <div class="col-md-6 col-lg-6">
                                    <iframe src="" width="100%" height="0" frameborder="0" scrolling="yes" id="iframe2"></iframe>
                                </div>
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
    setInterval("refresh()", 5000);

    function refresh() {

        var fecha = '{{isset($fecha_inicio) ? $fecha_inicio: date('Y-m-d')}}';
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "/monitoreoCexRes",
            method: 'POST',
            data: {fecha: fecha, _token: token},
            success: function (data) {
                $("#data2").empty();
                $.each(data, function (key, item) {
                //console.log(item);

                $("#data2").append('<tr><td>'+item["id"]+'</td>  <td>'+item["cedula"]+'</td> <td>'+item["imei"]+'</td>   <td>'+item["telefono"]+'</td> <td>'+item["nombre"]+'</td> <td>'+item["tiempo_parado"]+'</td> <td>'+item["bateria_porcentaje"]+'</td> <td>'+item["appStatus"]+'</td> <td>'+item["status_gps"]+'</td> <td>'+item["alerta_mensaje"]+'</td> <td><button class="modalButton btn btn-'+item["alerta"]+' btn-xs" id="button'+item["id"]+'" onclick="datos(\''+item["cedula"]+'\',\''+fecha+'\',\''+item["imei"]+'\')"><span class="glyphicon glyphicon-eye-open"> </span> Monitorear</button></td></tr>');

                });
            }
        });
    }

    function datos(cedula,fecha,imei) {
            var url = 'mapaCex/'+cedula+'/'+fecha;
            if (url=='mapaCex/undefined'){return true;}
            console.log(url);
            $('#iframe').attr('src', url);
            $('#iframe').attr('height', 800);

            url = 'dashCex/'+fecha+'/'+imei;
            $('#iframe2').attr('src', url);
            $('#iframe2').attr('height', 800);

            $('#iframe').reload();
            $('#iframe2').reload();
    }


</script>
@endsection