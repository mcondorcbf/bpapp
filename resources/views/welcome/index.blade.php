<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BPAPP</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @if (Auth::check())
                <a href="{{ url('/home') }}">Inicio</a> ||
                <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @else
                <a href="{{ url('/login') }}">Ingresar</a>
            @endif
        </div>
        <div class="well" style="max-width: 600px; margin: 0 auto 10px;" >
            @if(\Voyager::can('accede_bpapp_supervisor'))
                <a href="{{ url('/inicio/6') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-th-list"> </span> Sistema de Refinanciamientos Diners Supervisor</strong></a>
            @endif
            @if(\Voyager::can('accede_bpapp_gestor'))
                <a href="{{ url('/inicio/15') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-th-list"> </span> Sistema de Refinanciamientos Diners Gestor</strong></a>
            @endif

            @if(\Voyager::can('accede_predictivo_supervisor'))
                <a href="{{ url('/inicio/7') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-earphone"> </span> Sistema de Remarcado Predictivo</strong></a>
            @endif

            @if(\Voyager::can('browse_tbl_reportes_diners'))
                <a href="{{ url('/inicio/8') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> Reportes Diners</strong></a>
            @endif

            @if(\Voyager::can('browse_tbl_reportes_cooperativa_29'))
                <a href="{{ url('/inicio/9') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> Reportes Cooperativa 29 de Octubre</strong></a>
            @endif

            @if(\Voyager::can('browse_tbl_reportes_equifax'))
                <a href="{{ url('/inicio/10') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> Reportes Equifax</strong></a>
            @endif

            @if(\Voyager::can('browse_tbl_reportes_bco_guayaquil'))
                <a href="{{ url('/inicio/11') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> Reportes Banco Guayaquil</strong></a>
            @endif

            @if(\Voyager::can('browse_tbl_reportes_cex'))
                <a href="{{ url('/inicio/12') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> Reportes CEX</strong></a>
            @endif

            @if(\Voyager::can('browse_tbl_encuestas_cex_supervisor'))
                <a href="{{ url('/inicio/13') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> Encuestas CEX</strong></a>
            @endif

            @if(\Voyager::can('browse_tbl_reportes_belcorp'))
                <a href="{{ url('/inicio/14') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> Belcorp</strong></a>
            @endif

            @if(\Voyager::can('browse_tbl_atm'))
                <a href="{{ url('/inicio/16') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> ATM</strong></a>
            @endif

            @if(\Voyager::can('accede_ivrs_supervisor') || \Voyager::can('accede_ivrs_administrador'))
                <a href="{{ url('/inicio/17') }}" type="button" class="btn btn-primary btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> IVRS</strong></a>
            @endif

            @if(\Voyager::can('browse_tbl_reportes_belcorp_peru'))
                <a href="{{ url('/inicio/18') }}" type="button" class="btn btn-success btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> Reportes Peru</strong></a>
            @endif

            @if(\Voyager::can('browse_tbl_cex_monitoreo'))
                <a href="{{ url('/monitoreoCexRes') }}" type="button" class="btn btn-danger btn-lg btn-block"><strong><span class="glyphicon glyphicon-save-file"> </span> Monitoreo CEX</strong></a>
            @endif

        </div>
    @else
        <div class="content">
            <div class="title m-b-md">
                Sistema de Procesos <BR> COBEFEC
            </div>
        </div>
    @endif
</div>
</body>
</html>