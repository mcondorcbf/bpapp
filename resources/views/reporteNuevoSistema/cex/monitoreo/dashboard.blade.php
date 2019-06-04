<link href="/css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" src="/js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
</style>

<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
    }
    #floating-panel {
        position: absolute;
        top: 5px;
        left: 50%;
        margin-left: -180px;
        width: 350px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
    }
    #latlng {
        width: 225px;
    }
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<ul class="nav nav-pills" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="paradas-tab" data-toggle="tab" href="#paradas" role="tab" aria-controls="paradas" aria-selected="true">PARADAS</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="paradas_reales-tab" data-toggle="tab" href="#paradas_reales" role="tab" aria-controls="paradas_reales" aria-selected="true">RECORRIDO TOTAL</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="aplicaciones-tab" data-toggle="tab" href="#aplicaciones" role="tab" aria-controls="aplicaciones" aria-selected="false">APLICACIONES INSTALADAS</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="otros-tab" data-toggle="tab" href="#otros" role="tab" aria-controls="contact" aria-selected="false">OTROS DATOS</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="paradas" role="tabpanel" aria-labelledby="paradas-tab">
        <table id="tbl_ca" class="table table-bordered" style="width:100%; font-size: 11px;">
            <thead class="alert alert-danger">
            <tr>
                <th>#</th>
                <th>IMEI</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Tiempo parado</th>
                <th>Distancia (m)</th>
                <th><button onclick="location.reload();"><span class="glyphicon glyphicon-refresh"> </span> Refrescar direcciones</button></th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1;?>
            @if(count($paradas)>0)
            @foreach($paradas as $parada)
                <tr>
                    <td>{{$parada['secuencia']}}</td>
                    <td>{{$parada['imei']}}</td>
                    <td>{{$parada['hora_inicio']}}</td>
                    <td>{{$parada['hora_fin']}}</td>
                    <td>{{$parada['tiempo_parado']}}</td>
                    <td>{{$parada['distancia']}}</td>
                    <td id="{{$parada['id']}}">
                        {{ csrf_field() }}
                        @if(isset($parada['direccion']))
                            <strong>{{$parada['direccion']}}</strong>
                        @else
                            {{$parada['latitud']}},{{$parada['longitud']}}
                        @endif
                    </td>
                </tr><?php $i++;?>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="paradas_reales" role="tabpanel" aria-labelledby="paradas_reales-tab">
        <table id="tbl_ca" class="table table-bordered" style="width:100%; font-size: 11px;">
            <thead class="alert alert-success">
            <tr>
                <th>#</th>
                <th>IMEI</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Tiempo parado</th>
                <th>Distancia (m)</th>
                <th><button onclick="location.reload();"><span class="glyphicon glyphicon-refresh"> </span> Refrescar direcciones</button></th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1;?>
            @foreach($paradasReales as $parada)
                <tr>
                    <td>{{$parada['secuencia']}}</td>
                    <td>{{$parada['imei']}}</td>
                    <td>{{$parada['hora_inicio']}}</td>
                    <td>{{$parada['hora_fin']}}</td>
                    <td>{{$parada['tiempo_parado']}}</td>
                    <td>{{$parada['distancia']}}</td>
                    <td id="{{$parada['id']}}">
                        {{ csrf_field() }}
                        @if(isset($parada['direccion']))
                            <strong>{{$parada['direccion']}}</strong>
                        @else
                            {{$parada['latitud']}},{{$parada['longitud']}}
                        @endif
                    </td>
                </tr><?php $i++;?>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="aplicaciones" role="tabpanel" aria-labelledby="aplicaciones-tab">
        <table id="tbl_ca" class="table table-bordered" style="width:100%; font-size: 11px;">
            <thead class="alert alert-success">
            <tr>
                <th>#</th>
                <th>Aplicaciones Instaladas</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1;?>
            @foreach($apps as $app)
                @if(isset($app['className']))
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$app['className']}}</td>
                    </tr><?php $i++;?>
                @endif
            @endforeach
            </tbody>
        </table></div>
    <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
        <table id="tbl_ca" class="table table-bordered" style="width:100%; font-size: 11px;">
            <thead class="alert alert-warning">
            <tr>
                <th>#</th>
                <th>Imei</th>
                <th>Tipo de dato</th>
                <th>Status Aplicación</th>
                <th>Cambio Hora</th>
                <th>Status Gps</th>
                <th>Hora Móvil</th>
                <th>Hora Server</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; $appStatus=''; $zona_horaria=''; $status_gps=''; $asyncStatus='';?>
            @foreach($datos as $dato)


                @if(isset($dato['asyncStatus']))
                    @if($dato['asyncStatus']=='1')
                        <?php $asyncStatus='Online';?>
                    @elseif($dato['asyncStatus']=='0')
                        <?php $asyncStatus='Offline';?>
                    @elseif($dato['asyncStatus']=='')
                        <?php $asyncStatus='';?>
                    @endif
                @endif

                @if(isset($dato['appStatus']))
                    @if($dato['appStatus']=='1')
                        <?php $appStatus='App Encendida';?>
                    @elseif($dato['appStatus']=='0')
                        <?php $appStatus='App Apagada';?>
                    @elseif($dato['appStatus']=='')
                        <?php $appStatus='';?>
                    @endif
                @endif

                @if(isset($dato['zona_horaria']))
                    @if($dato['zona_horaria']=='1')
                        <?php $zona_horaria='Hubo un cambio de hora - '.$dato['dttimeupdate'];?>
                    @else
                        <?php $zona_horaria='';?>
                    @endif
                @endif

                @if(isset($dato['status_gps']))
                    @if($dato['status_gps']=='1')
                        <?php $status_gps='Gps Encendido';?>
                    @elseif($dato['status_gps']=='0')
                        <?php $status_gps='Gps Apagado';?>
                    @elseif($dato['status_gps']=='')
                        <?php $status_gps='';?>
                    @endif
                @endif
                @if(isset($dato['appStatus'])=='' && isset($dato['zona_horaria'])=='' && isset($dato['status_gps'])=='')
                @else
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$dato['imei']}}</td>
                        <td>{{$asyncStatus}}</td>
                        <td>{{$appStatus}}</td>
                        <td>{{$zona_horaria}}</td>
                        <td>{{$status_gps}}</td>
                        <td>{{$dato['dttimeupdate']}}</td>
                        <td>{{$dato['created_at']}}</td>
                    </tr>
                    <?php $i++;?>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function initMap() {
            var geocoder = new google.maps.Geocoder;
            var infowindow = new google.maps.InfoWindow;
            //geocodeLatLng(geocoder, infowindow, -0.116815, -78.4867917,{lat: {{$parada['latitud']}}, lng: {{$parada['longitud']}}});
            var latlngs = <?php echo json_encode( $paradasDirecciones ) ?>;
            geocodeLatLng(geocoder, infowindow, latlngs);
        }

        function geocodeLatLng(geocoder, infowindow,latlngs) {
            //var latlng = {lat: parseFloat(ltd), lng: parseFloat(lgt)};
            latlngs.forEach(function(entry) {
                setTimeout('',1000);
                setInterval('',1000);
                var latlng = {lat: parseFloat(entry['latitud']), lng: parseFloat(entry['longitud'])};
                geocoder.geocode({'location': latlng}, function(results, status) {

                    if (status === 'OK') {
                        if (results[0]) {
                            infowindow.setContent(results[0].formatted_address);
                            var token = $("input[name='_token']").val();
                            $.ajax({
                                type: "POST",
                                url: "{{url('/guardaDireccionesCex')}}",
                                data:  {_token: token , direccion: results[0].formatted_address , id: entry['id'] },
                                success: function(data) {
                                    console.log(data);
                                }
                            });
                            console.log(results[0].formatted_address);
                        } else {
                            console.log('No results found');
                        }
                    } else {
                        console.log('Geocoder failed due to: ' + status + ' - '+entry['id']);
                    }
                });
            });
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXr_yzUGbJnS1ZumWl2c5QQSf4vJ4gGpc&callback=initMap"></script>
</div>