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
dsadasda
<div class="col-lg-12">
    <div class="panel with-nav-tabs panel-primary">
        <div class="panel-heading">
            <ul class="nav nav-tabs">

            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border" >
                        <div class="tab-pane fade active in" id="primer_reporte">
                            <div class="col-md-12 col-lg-12">
                                <div id="map"></div>
                                <script>


                                    function initMap() {
                                        var map = new google.maps.Map(document.getElementById('map'), {
                                            zoom: 15,
                                            center: {lat: {{$coordenadas[0]['latitud']}}, lng: {{$coordenadas[0]['longitud']}} },
                                            mapTypeId: 'terrain'
                                        });



                                        var iconBase =
                                            'https://developers.google.com/maps/documentation/javascript/examples/full/images/';

                                        var icons = {
                                            parking: {
                                                icon: iconBase + 'parking_lot_maps.png'
                                            },
                                            library: {
                                                icon: iconBase + 'library_maps.png'
                                            },
                                            info: {
                                                icon: iconBase + 'info-i_maps.png'
                                            }
                                        };

                                        var flightPlanCoordinates = [
                                            @foreach($coordenadas as $k)
                                            {lat: {{$k['latitud']}} , lng: {{$k['longitud']}} },
                                            @if($loop->last)
                                                {lat: {{$k['latitud']}} , lng: {{$k['longitud']}} }
                                            @endif
                                            @endforeach
                                        ];

                                        var lineSymbol = {
                                            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                                            strokeColor: 'blue',
                                            strokeWeight: 2,
                                            strokeOpacity: 0.6,
                                        };
                                        var flightPath = new google.maps.Polyline({
                                            path: flightPlanCoordinates,
                                            geodesic: true,
                                            strokeColor: '#FF0000',
                                            strokeOpacity: 1.0,
                                            strokeWeight: 2,
                                            icons: [{
                                                icon: lineSymbol,
                                                offset: '0',
                                                repeat: '100px'
                                            }],
                                        });

                                        var image = {
                                            url: 'https://maps.google.com/mapfiles/ms/icons/blue.png',
                                            // This marker is 20 pixels wide by 32 pixels high.
                                            size: new google.maps.Size(32, 32),
                                            // The origin for this image is (0, 0).
                                            origin: new google.maps.Point(0, 0),
                                            // The anchor for this image is the base of the flagpole at (0, 32).
                                            anchor: new google.maps.Point(0, 32)
                                        };

                                        var image2 = {
                                            url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                                            // This marker is 20 pixels wide by 32 pixels high.
                                            size: new google.maps.Size(20, 32),
                                            // The origin for this image is (0, 0).
                                            origin: new google.maps.Point(0, 0),
                                            // The anchor for this image is the base of the flagpole at (0, 32).
                                            anchor: new google.maps.Point(0, 32)
                                        };
                                        var demarche = {
                                            url: 'https://maps.google.com/mapfiles/ms/icons/green.png',
                                            // This marker is 20 pixels wide by 32 pixels high.
                                            size: new google.maps.Size(32, 32),
                                            // The origin for this image is (0, 0).
                                            origin: new google.maps.Point(0, 0),
                                            // The anchor for this image is the base of the flagpole at (0, 32).
                                            anchor: new google.maps.Point(32, 32)
                                        };

                                        <?php $i=0;$j=0;?>
                                        @foreach($coordenadas as $k)
                                        var contentString{{$i}} = '<div id="content">'+
                                                '<div id="siteNotice">'+
                                                '</div>'+
                                                '<h1 id="firstHeading" class="firstHeading">{{$k['secuencia']}}</h1>'+
                                                '<div id="bodyContent">'+
                                                '<p><b>Imei: </b>{{$k['imei']}}'+
                                                '<br><b>Secuencia: </b>{{$k['secuencia']}}'+
                                                '<br><b>Bateria: </b>{{$k['bateria_porcentaje']}}'+
                                                '<br><b>Fecha y hora: </b>{{$k['update_time']}}'+
                                                '<br><b>Latitud: </b>{{$k['latitud']}}'+
                                                '<br><b>Longitud: </b>{{$k['longitud']}}</p>'+
                                                '</div>'+
                                                '</div>';
                                        var infowindow{{$i}} = new google.maps.InfoWindow({
                                            content: contentString{{$i}}
                                        });

                                        var marker{{$i}} = new google.maps.Marker({
                                            position: {lat: {{$k['latitud']}} , lng: {{$k['longitud']}} },
                                            @if($loop->first)
                                            icon: image,
                                            @elseif($loop->last)
                                            icon: image2,
                                            @endif
                                            type: 'parking',
                                            map: map,
                                            animation: google.maps.Animation.DROP,
                                            title: 'Monitoreo en línea'
                                        });
                                        marker{{$i}}.addListener('click', function() {
                                            <?php $il=0;?>
                                            @foreach($coordenadas as $key)
                                                infowindow{{$il}}.close();
                                            <?php $il++;?>
                                            @endforeach
                                                infowindow{{$i}}.open(map, marker{{$i}});

                                            <?php echo $j=$i;?>
                                        });

                                        <?php $i++;?>
                                    @endforeach
                                        flightPath.setMap(map);
                                        //flightPath2.setMap(map);


                                        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                        var labelIndex = 0;

                                        // Add a marker at the center of the map.
                                        @foreach($gestiones as $gestione)

                                        var gestion = <?php echo json_encode( $gestione ) ?>;
                                        addMarker({lat: {{$gestione->latitude}}, lng: {{$gestione->longitude}} }, map, gestion);
                                        @endforeach



                                        function addMarker(location, map, gestion) {
                                            // Add the marker at the clicked location, and add the next-available label
                                            // from the array of alphabetical characters.
                                            var marker = new google.maps.Marker({
                                                position: location,
                                                label: labels[labelIndex++ % labels.length],
                                                map: map,
                                                icon: demarche
                                            });

                                            var direccion=geocodeLatLng(location);
                                            console.log('direccion: '+direccion);

                                            var contentString = '<div id="content">'+
                                                '<div id="siteNotice">'+
                                                '</div>'+
                                                '<div id="bodyContent">'+
                                                '<p><b>Batería: </b>'+gestion['extras']['battery']+'%'+
                                                '<br><b>Hora de gestión: </b>'+gestion['point_time']+
                                                '<br><b>Cédula cuenta: </b>'+gestion['cedula_cuenta']+
                                                '<br><b>Nombre cuenta: </b>'+gestion['nombre_cuenta']+
                                                '<br><b>Producto: </b>'+gestion['producto']+
                                                '<br><b>Campaña: </b>'+gestion['campana']+
                                                '<br><b>Asesor Cex: </b>'+gestion['agente']+
                                                '<br><b>Acción: </b>'+gestion['accion']+
                                                '<br><b>Sub acción: </b>'+gestion['sub_accion']+
                                                '<br><br><b>Descripción: '+gestion['description']+'</b>'+
                                                '<br><b>Direccion: </b>'+direccion+
                                                '</br></div>'+
                                                '</div>';
                                            var infowindow = new google.maps.InfoWindow({
                                                content: contentString
                                            });

                                            marker.addListener('click', function() {
                                                    infowindow.close();
                                                    infowindow.open(map, marker);
                                            });
                                        }
                                    }

                                    function geocodeLatLng(latlng) {
                                        var geocoder = new google.maps.Geocoder;

                                        //var latlng = {lat: parseFloat(ltd), lng: parseFloat(lgt)};

                                        geocoder.geocode({'location': latlng}, function(results, status) {

                                            if (status === 'OK') {
                                                if (results[0]) {
                                                    //console.log('resultado: '+results[0].formatted_address);
                                                    var resultado=results[0].formatted_address
                                                    console.log('resultado: '+results[0]);
                                                    return resultado;
                                                } else {
                                                    console.log('No results found');
                                                }
                                            } else {
                                                console.log('Geocoder failed due to: ' + status + ' - '+entry['id']);
                                            }
                                        });

                                    }
                                        google.maps.event.addDomListener(window, 'load', initialize);
                                </script>
                                <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXr_yzUGbJnS1ZumWl2c5QQSf4vJ4gGpc&callback=initMap">
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="all-content-wrapper">

    <!-- Charts Start-->
    <div class="charts-area mg-b-15">
        <div class="container-fluid">
            <div class="row">
            </div>
            <div class="row">
            </div>
            <div class="row">
            </div>
            <div class="row">
            </div>
        </div>
    </div>
    <!-- Charts End-->
 </div>