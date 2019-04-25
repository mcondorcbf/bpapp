@extends('layouts.appReportesNuevoSistemaCex')
@section('scripts')
@endsection
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

<!-- owl.carousel CSS
		============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/owl.carousel.css">
<link rel="stylesheet" href="/storage/kiaalap/css/owl.theme.css">
<link rel="stylesheet" href="/storage/kiaalap/css/owl.transitions.css">
<!-- animate CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/animate.css">
<!-- normalize CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/normalize.css">
<!-- meanmenu icon CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/meanmenu.min.css">
<!-- main CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/main.css">
<!-- educate icon CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/educate-custon-icon.css">
<!-- morrisjs CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/morrisjs/morris.css">
<!-- mCustomScrollbar CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/scrollbar/jquery.mCustomScrollbar.min.css">
<!-- metisMenu CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/metisMenu/metisMenu.min.css">
<link rel="stylesheet" href="/storage/kiaalap/css/metisMenu/metisMenu-vertical.css">
<!-- calendar CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/calendar/fullcalendar.min.css">
<link rel="stylesheet" href="/storage/kiaalap/css/calendar/fullcalendar.print.min.css">
<!-- style CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/style.css">
<!-- responsive CSS
    ============================================ -->
<link rel="stylesheet" href="/storage/kiaalap/css/responsive.css">
<!-- modernizr JS
    ============================================ -->
<script src="/storage/kiaalap/js/vendor/modernizr-2.8.3.min.js"></script>
@section('content')

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

                                    // This example creates a 2-pixel-wide red polyline showing the path of
                                    // the first trans-Pacific flight between Oakland, CA, and Brisbane,
                                    // Australia which was made by Charles Kingsford Smith.

                                    function initMap() {
                                        var map = new google.maps.Map(document.getElementById('map'), {
                                            zoom: 18,
                                            center: {lat: -0.1649873, lng: -78.4899342},
                                            mapTypeId: 'terrain'
                                        });

                                        var flightPlanCoordinates = [
                                            @foreach($coordenadas as $k)
                                            {lat: {{$k->latitud}} , lng: {{$k->longitud}} },
                                            @if($loop->last)
                                                {lat: {{$k->latitud}} , lng: {{$k->longitud}} }
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

                                        <?php $i=0;$j=0;?>
                                        @foreach($coordenadas as $k)
                                            var contentString{{$i}} = '<div id="content">'+
                                                '<div id="siteNotice">'+
                                                '</div>'+
                                                '<h1 id="firstHeading" class="firstHeading">{{$i}}</h1>'+
                                                '<div id="bodyContent">'+
                                                '<p><b>Imei: </b>{{$k->imei}}'+
                                                '<br><b>Secuencia: </b>{{$k->secuencia}}'+
                                                '<br><b>Bateria: </b>{{$k->bateria_porcentaje}}'+
                                                '<br><b>Fecha y hora: </b>{{$k->update_time}}</p>'+
                                                '</div>'+
                                                '</div>';
                                            var infowindow{{$i}} = new google.maps.InfoWindow({
                                                content: contentString{{$i}}
                                            });
                                            var marker{{$i}} = new google.maps.Marker({
                                                position: {lat: {{$k->latitud}} , lng: {{$k->longitud}} },
                                                map: map,
                                                title: 'Monitoreo en línea'
                                            });

                                            marker{{$i}}.addListener('click', function() {
                                                <?php $il=0;?>
                                                @foreach($coordenadas as $key)
                                                    infowindow{{$il}}.close();
                                                <?php $il++;?>
                                                @endforeach
                                                    infowindow{{$i}}.open(map, marker{{$i}});

                                            <?php $j=$i;?>
                                            });
                                            <?php $i++;?>
                                        @endforeach
                                            flightPath.setMap(map);

                                    }
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
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="charts-single-pro responsive-mg-b-30">
                        <div class="alert-title">
                            <h2>Basic Line Chart</h2>
                            <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                        </div>
                        <div id="basic-chart">
                            <canvas id="basiclinechart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="charts-single-pro">
                        <div class="alert-title">
                            <h2>Line Chart Multi Axis</h2>
                            <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                        </div>
                        <div id="axis-chart">
                            <canvas id="linechartmultiaxis"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="charts-single-pro mg-tb-30 responsive-mg-b-0">
                        <div class="alert-title">
                            <h2>Line Chart Stepped</h2>
                            <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                        </div>
                        <div id="stepped-chart">
                            <canvas id="linechartstepped"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="charts-single-pro mg-tb-30">
                        <div class="alert-title">
                            <h2>Line Chart Interpolation</h2>
                            <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                        </div>
                        <div id="polation-chart">
                            <canvas id="linechartinterpolation"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="charts-single-pro responsive-mg-b-30">
                        <div class="alert-title">
                            <h2>Agentes por mes</h2>
                            <p>Agentes por mes.</p>
                        </div>
                        <div class="sparkline10-graph">
                            <div id="pie"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="charts-single-pro">
                        <div class="alert-title">
                            <h2>Chart Line point circle</h2>
                            <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                        </div>
                        <div id="circle-chart">
                            <canvas id="linechartpointcircle"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="charts-single-pro mg-t-30">
                        <div class="alert-title">
                            <h2>Chart Line Point rectRot</h2>
                            <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                        </div>
                        <div id="rectRot-chart">
                            <canvas id="linechartpointrectRot"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="charts-single-pro mg-t-30">
                        <div class="alert-title">
                            <h2>Chart Line point cross</h2>
                            <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                        </div>
                        <div id="cross-chart">
                            <canvas id="linechartpointcross"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Charts End-->
    <div class="footer-copyright-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer-copy-right">
                        <p>Copyright © 2018. All rights reserved. Template by <a href="https://colorlib.com/wp/templates/">Colorlib</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- jquery
    ============================================ -->
<script src="/storage/kiaalap/js/jquery-1.12.4.min.js"></script>
<!-- bootstrap JS
    ============================================ -->
<script src="/storage/kiaalap/js/bootstrap.min.js"></script>
<!-- wow JS
    ============================================ -->
<script src="/storage/kiaalap/js/wow.min.js"></script>
<!-- price-slider JS
    ============================================ -->
<script src="/storage/kiaalap/js/jquery-price-slider.js"></script>
<!-- meanmenu JS
    ============================================ -->
<script src="/storage/kiaalap/js/jquery.meanmenu.js"></script>
<!-- owl.carousel JS
    ============================================ -->
<script src="/storage/kiaalap/js/owl.carousel.min.js"></script>
<!-- sticky JS
    ============================================ -->
<script src="/storage/kiaalap/js/jquery.sticky.js"></script>
<!-- scrollUp JS
    ============================================ -->
<script src="/storage/kiaalap/js/jquery.scrollUp.min.js"></script>
<!-- mCustomScrollbar JS
    ============================================ -->
<script src="/storage/kiaalap/js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="/storage/kiaalap/js/scrollbar/mCustomScrollbar-active.js"></script>
<!-- metisMenu JS
    ============================================ -->
<script src="/storage/kiaalap/js/metisMenu/metisMenu.min.js"></script>
<script src="/storage/kiaalap/js/metisMenu/metisMenu-active.js"></script>
<!-- Charts JS
    ============================================ -->
<script src="/storage/kiaalap/js/charts/Chart.js"></script>
<script src="/storage/kiaalap/js/charts/line-chart.js"></script>
<!-- tab JS
    ============================================ -->
<script src="/storage/kiaalap/js/tab.js"></script>
<!-- plugins JS
    ============================================ -->
<script src="/storage/kiaalap/js/plugins.js"></script>
<!-- main JS
    ============================================ -->
<script src="/storage/kiaalap/js/main.js"></script>
<!-- tawk chat JS
    ============================================ -->
<script src="/storage/kiaalap/js/tawk-chat.js"></script>
<!-- c3 JS
		============================================ -->
<script src="/storage/kiaalap/js/c3-charts/d3.min.js"></script>
<script src="/storage/kiaalap/js/c3-charts/c3.min.js"></script>
<script src="/storage/kiaalap/js/c3-charts/c3-active.js"></script>

<script>
    /*----------------------------------------*/
    /*  5.  Line Chart styles
     /*----------------------------------------*/

    var ctx = document.getElementById("linechartstyles");
    var linechartstyles = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["January", "February", "March", "April", "May"],
            datasets: [{
                label: "Unfilled",
                fill: false,
                backgroundColor: '#006DF0',
                borderColor: '#006DF0',
                data: [25, 30, 35, 40, 45]
            }, {
                label: "Dashed",
                fill: false,
                backgroundColor: '#933EC5',
                borderColor: '#933EC5',
                borderDash: [1, 1],
                data: [50, -10, 50, -10, 50]

            }, {
                label: "Filled",
                fill: true,
                backgroundColor: '#D80027',
                borderColor: '#D80027',
                data: [20 , 100, 120, 160, 200]

            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Line Chart Style'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: false,
                        maxRotation: 0
                    },
                    ticks: {
                        fontColor: "#fff", // this here
                    }
                }],
                yAxes: [{
                    ticks: {
                        autoSkip: false,
                        maxRotation: 0
                    },
                    ticks: {
                        fontColor: "#fff", // this here
                    }
                }]
            }
        }
    });
</script>
@endsection