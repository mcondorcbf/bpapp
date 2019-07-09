@extends('layouts.appReportesNuevoSistemaCex')
@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />

<link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
<script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->

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
        $('#tbParadas2').DataTable( {
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[5,10,50,-1], [5,10,50,'Todo']]
        } );
    } );


</script>

<style>
    .loader {
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        opacity: .8;
    }
</style>

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
                                <table id="" class="table " style="width:100%; font-size: 11px;">
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
                                        <td><button class="modalButton btn btn-{{$dispositivo['alerta']}} btn-xs" id="button{{$dispositivo['id']}}" onclick="datos('{{$dispositivo['cedula']}}','{{isset($fecha_inicio) ? $fecha_inicio : date('d/m/Y')}}','{{$dispositivo['imei']}}')"><span class="glyphicon glyphicon-eye-open"> </span> Monitorear</button></td>
                                        </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                </div>

                                <div id="loading-image" class="loader" style="display: none;" align="center"> <img src="/images/loading.gif"></div>

                                <h3 id="imei" align="center" class="alert alert-success"></h3>

                                <div class="col-md-6 col-lg-6" id="datosM" style="display: none">
                                    <iframe src="" width="100%" height="0" frameborder="0" scrolling="yes" id="iframe"></iframe>
                                </div>

                                <div class="col-md-6 col-lg-6">
                                    <iframe src="" width="100%" height="0" frameborder="0" scrolling="yes" id="iframe2"></iframe>
                                </div>


                                <div class="col-md-6 col-lg-6" id="datosD" style="display: none; overflow: scroll; height: 780px;">
                                    <ul class="nav nav-pills" id="myTab" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link" id="paradas_reales-tab" data-toggle="tab" href="#paradas_reales" role="tab" aria-controls="paradas_reales" aria-selected="true" onclick="datosMapaTotal()">RECORRIDO TOTAL</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="paradas-tab" data-toggle="tab" href="#paradas" role="tab" aria-controls="paradas" aria-selected="true" onclick="datosMapa()">PARADAS</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="aplicaciones-tab" data-toggle="tab" href="#aplicaciones" role="tab" aria-controls="aplicaciones" aria-selected="false">APLICACIONES INSTALADAS</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="otros-tab" data-toggle="tab" href="#otros" role="tab" aria-controls="contact" aria-selected="false">OTROS DATOS</a>
                                        </li>
                                    </ul>
                                    <input type="hidden" name="dat" id="dat" value="">
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade active in" id="paradas" role="tabpanel" aria-labelledby="paradas-tab">
                                            <table id="" class="table table-md table-bordered" style="font-size: 11px">
                                                <thead class="alert alert-danger">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Hora Inicio</th>
                                                    <th>Hora Fin</th>
                                                    <th>Tiempo parado</th>
                                                    <th>Distancia (m)</th>
                                                    <th><button onclick="geocodeLatLng();"><span class="glyphicon glyphicon-refresh"> </span> Refrescar direcciones</button></th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbParadas">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="paradas_reales" role="tabpanel" aria-labelledby="paradas_reales-tab">
                                            <table id="" class="table table-bordered" style="font-size: 11px">
                                                <thead class="alert alert-info">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Hora Inicio</th>
                                                    <th>Hora Fin</th>
                                                    <th>Tiempo parado</th>
                                                    <th>Distancia (m)</th>
                                                    <th><button onclick="geocodeLatLng();"><span class="glyphicon glyphicon-refresh"> </span> Refrescar direcciones</button></th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbParadasReales">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="aplicaciones" role="tabpanel" aria-labelledby="aplicaciones-tab">
                                            <table id="" class="table table-bordered" style="font-size: 11px">
                                                <thead class="alert alert-success">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Aplicaciones Instaladas</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbAplicaciones">

                                                </tbody>
                                            </table></div>
                                        <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
                                            <table id="" class="table table-bordered" style="font-size: 11px">
                                                <thead class="alert alert-warning">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tipo de dato</th>
                                                    <th>Status Aplicación</th>
                                                    <th>Cambio Hora</th>
                                                    <th>Zona Horaria</th>
                                                    <th>Status Gps</th>
                                                    <th>Cobertura Móvil(Minutos)</th>
                                                    <th>Cobertura Datos(Megas)</th>
                                                    <th>Hora Móvil</th>
                                                    <th>Hora Server</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbOtros">

                                                </tbody>
                                            </table>
                                        </div>


                                    </div>
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
                                            <a class="navbar-brand" href="#" id="cuentas2" style="color: #000;"></a>
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
<div id="map"></div>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXr_yzUGbJnS1ZumWl2c5QQSf4vJ4gGpc"></script>
<script type="text/javascript">
    setInterval("refresh()", 5000);
    var simei='';
    var sfecha='';

    function refresh() {

        var fecha = '{{isset($fecha_inicio) ? $fecha_inicio : date('Y-m-d')}}';



        if (fecha=={{date('Y-m-d')}}){
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
    }

    function datos(cedula,fecha,imei) {

        $("#imei").empty();
        $('#datosD').css('display','none');
        $('#datosM').css('display','none');
        $("#loading-image").show('slow');

        //MAPA
        var url = 'mapaCexTotal/'+cedula+'/'+fecha;
        if (url=='mapaCexTotal/undefined'){return true;}
        console.log(url);
        $('#iframe').attr('src', url);
        $('#iframe').attr('height', 800);

        //DASHBOARD
        url = 'dashCexG/'+fecha+'/'+imei;
        console.log(url);
        $.ajax({
            type: 'GET',
            url: url,
            dataType:"json",
            beforeSend: function() {

                $("#tbParadas").empty();
                $("#tbParadasReales").empty();
                $("#tbAplicaciones").empty();
                $("#tbOtros").empty();

            },
            success: function (data) {
                //$("#data2").empty();
                simei=data['asesor']["imei"];
                sfecha=fecha;
                $("#imei").append(data['asesor']['nombre']+' - '+data['asesor']['imei']);


                /*if ( $.fn.dataTable.isDataTable( '#tbParadas' ) ) {
                    table = $('#tbParadas').DataTable();
                }

                var table = $('#tbParadas').DataTable( {
                    ajax: data,
                    deferRender: true,
                    columns: [
                        { data: 'secuencia' },
                        { data: 'imei' },
                        { data: 'hora_inicio' },
                        { data: 'hora_fin' },
                        { data: 'tiempo_parado' },
                        { data: 'distancia' },
                        { data: 'direccion' }
                    ],
                    rowId: 'extn',
                    select: true,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Recargar table',
                            action: function () {
                                table.ajax.reload();
                            }
                        }
                    ]
                } );
                */

                var i=1;
                var direccion='';
                $.each(data['paradas'], function (key, item) {

                    if(item["direccion"]!=null){direccion=item["direccion"];}else{direccion='';}

                    $("#tbParadas").append('<tr><td>'+item["secuencia"]+'</td>  <td>'+item["hora_inicio"]+'</td>   <td>'+item["hora_fin"]+'</td> <td>'+item["tiempo_parado"]+'</td> <td>'+item["distancia"]+'</td> <td>'+direccion+'</td></tr>');
                });
                var i=1;
                $.each(data['paradasReales'], function (key, item) {
                    if(item["direccion"]!=null){direccion=item["direccion"];}else{direccion='';}

                    if (item["asyncstatus"]=='1'){
                        $("#tbParadasReales").append('<tr><td>'+item["secuencia"]+'</td> <td> '+item["created_at"]+' </td>   <td> Dato Offline  </td> <td> Dato Offline </td> <td> Dato Offline </td> <td> '+direccion+' </td></tr>');
                    }else{
                        $("#tbParadasReales").append('<tr><td>'+item["secuencia"]+'</td>  <td>'+item["hora_inicio"]+'</td>   <td>'+item["hora_fin"]+'</td> <td>'+item["tiempo_parado"]+'</td> <td>'+item["distancia"]+'</td> <td>'+direccion+'</td></tr>');
                    }
                });
                var i=1;
                $.each(data['apps'], function (key, item) {
                    if(item["className"]!=undefined)$("#tbAplicaciones").append('<tr><td>'+(i++)+'</td>  <td>'+item["className"]+'</td> </tr>');
                });
                var i=1;
                $.each(data['datos'], function (key, item) {
                    var asyncstatus='';appstatus='';cambio_hora='';zona_horaria='';status_gps='';appnetcondata='';appnetconmbdata='';

                    if(item["asyncstatus"]=='1'){asyncstatus='Offline';}
                    if(item["asyncstatus"]=='0'){asyncstatus='Online';}

                    if(item["appstatus"]=='0'){appstatus='App Cerrada';}
                    if(item["appstatus"]=='1'){appstatus='App Abierta';}

                    if(item["cambio_hora"]=='1'){cambio_hora='Hora actualizada';}

                    if(item["zona_horaria"]=='1'){zona_horaria='Hubo un cambio de hora: '+item["dttimeupdate"];}

                    if(item["status_gps"]=='1'){status_gps='Gps Encendido';}
                    if(item["status_gps"]=='0'){status_gps='Gps Apagado';}

                    if(item["appstatus"]==null && item["appnetcondata"]==null){appnetcondata='Sin Cobertura';}
                    if(item["appstatus"]==null && item["appnetcondata"]=='1'){appnetcondata='Con conbertura';}

                    if(item["appstatus"]==null && item["appnetconmbdata"]==null){appnetconmbdata='Sin Cobertura';}
                    if(item["appstatus"]==null && item["appnetconmbdata"]=='1'){appnetconmbdata='Con cobertura';}

                    $("#tbOtros").append('<tr><td>'+(i++)+'</td>  <td>'+asyncstatus+'</td>   <td>'+appstatus+'</td> <td>'+cambio_hora+'</td> <td>'+zona_horaria+'</td> <td>'+status_gps+'</td> <td>'+appnetcondata+'</td> <td>'+appnetconmbdata+'</td> <td>'+item["dttimeupdate"]+'</td> <td>'+item["created_at"]+'</td></tr>');
                });
                $("#loading-image").hide();
                $('#datosD').css('display','block');
                $('#datosM').css('display','block');
            }
        });

        $('#dat').val(cedula+'/'+fecha);



/*
            url = 'dashCex/'+fecha+'/'+imei;
            $('#iframe2').attr('src', url);
            $('#iframe2').attr('height', 800);

            $('#iframe').reload();
            $('#iframe2').reload();
*/

    }

    function datosMapa() {
        $('#datosM').css('display','none');

        //MAPA
        var url = 'mapaCex/'+$('#dat').val();
        if (url=='mapaCex/undefined'){return true;}
        console.log(url);
        $('#iframe').attr('src', url);
        $('#iframe').attr('height', 800);

        $('#datosM').css('display','block');

    }


    function datosMapaTotal() {
        $('#datosM').css('display','none');

        //MAPA
        var url = 'mapaCexTotal/'+$('#dat').val();
        if (url=='mapaCexTotal/undefined'){return true;}
        console.log(url);
        $('#iframe').attr('src', url);
        $('#iframe').attr('height', 800);

        $('#datosM').css('display','block');

    }

    function otrosDatos() {
        $('#datosM').css('display','none');

        //MAPA
        var url = 'paradasCexG/'+$('#dat').val();
        if (url=='paradasCexG/undefined'){return true;}

        $.ajax({
            type: 'GET',
            url: url,
            dataType:"json",
            success: function (data) {
                var i=1;
                $.each(data['datos'], function (key, item) {
                    $("#tbOtros").append('<tr><td>'+(i++)+'</td>  <td>'+item["imei"]+'</td> <td>'+item["asyncstatus"]+'</td>   <td>'+item["appstatus"]+'</td> <td>'+item["zona_horaria"]+'</td> <td>'+item["status_gps"]+'</td> <td>'+item["dttimeupdate"]+'</td> <td>'+item["created_at"]+'</td></tr>');
                });
            }
        });

        $('#dat').val(cedula+'/'+fecha);

    }

    function geocodeLatLng() {
        $.ajax({
            type: 'GET',
            url: '/refreshCexGps/'+simei+'/'+sfecha,
            dataType:"json",
            success: function (data) {
                var i=1;
                $.each(data, function (key, item) {
                    var geocoder = new google.maps.Geocoder;
                    var latlng = {lat: parseFloat(item['latitud']), lng: parseFloat(item['longitud'])};
                    geocoder.geocode({'location': latlng}, function(results, status) {
                        if (status === 'OK') {
                            if (results[0]) {
                                var token = $("input[name='_token']").val();
                                $.ajax({
                                    type: "POST",
                                    url: "{{url('/guardaDireccionesCex')}}",
                                    data:  {_token: token , direccion: results[0].formatted_address , id: item['id'] },
                                    success: function(data) {
                                        console.log(data);
                                    }
                                });
                                console.log(results[0].formatted_address);
                            } else {
                                console.log('No results found');
                            }
                        } else {
                            console.log('Geocoder failed due to: ' + status + ' - '+item['id']);
                        }
                    });
                });
                tablas(sfecha,simei);
            }
        });
    }

    function tablas(fecha,imei) {
        url = 'dashCexG/'+fecha+'/'+imei;
        console.log(url);
        $.ajax({
            type: 'GET',
            url: url,
            dataType:"json",
            beforeSend: function() {

                $("#tbParadas").empty();
                $("#tbParadasReales").empty();


            },
            success: function (data) {
                //$("#data2").empty();
                simei=data['asesor']["imei"];
                sfecha=fecha;

                var i=1;
                var direccion='';
                $.each(data['paradas'], function (key, item) {

                    if(item["direccion"]!=null){direccion=item["direccion"];}else{direccion='';}

                    $("#tbParadas").append('<tr><td>'+item["secuencia"]+'</td>  <td>'+item["hora_inicio"]+'</td>   <td>'+item["hora_fin"]+'</td> <td>'+item["tiempo_parado"]+'</td> <td>'+item["distancia"]+'</td> <td>'+direccion+'</td></tr>');
                });
                var i=1;
                $.each(data['paradasReales'], function (key, item) {
                    if(item["direccion"]!=null){direccion=item["direccion"];}else{direccion='';}

                    if (item["asyncstatus"]=='1'){
                        $("#tbParadasReales").append('<tr><td>'+item["secuencia"]+'</td> <td> '+item["created_at"]+' </td>   <td> Dato Offline  </td> <td> Dato Offline </td> <td> Dato Offline </td> <td> '+direccion+' </td></tr>');
                    }else{
                        $("#tbParadasReales").append('<tr><td>'+item["secuencia"]+'</td>  <td>'+item["hora_inicio"]+'</td>   <td>'+item["hora_fin"]+'</td> <td>'+item["tiempo_parado"]+'</td> <td>'+item["distancia"]+'</td> <td>'+direccion+'</td></tr>');
                    }
                });
            }
        });

    }

</script>

@endsection