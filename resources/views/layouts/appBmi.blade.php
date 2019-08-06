<?php use App\Role;?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema de procesos COBEFEC</title>

    <!-- Styles -->
    <link href="{{asset('/css/app.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="{{asset('/css/jquery.dataTables.min.css')}}"/>

    <!-- Scripts -->

    <script src="{{asset('/assets/js/bootstrap.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/jquery-1.12.4.js')}}"></script>


    @yield('scripts')


    <script src="{{asset('/js/scripts.js')}}"></script>
    <script src="{{asset('/js/jquery.table2excel.min.js')}}"></script>

    <!-- Scripts -->
    <script>
        window.Laravel =<?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <style type="text/css">
        .btn-diners {
            color: #fff;
            background-color: rgba(8, 10, 60, 0.31);
            border-color: #000;
        }
    </style>

    <style>

        .panel.with-nav-tabs .panel-heading{
            padding: 5px 5px 0 5px;
        }
        .panel.with-nav-tabs .nav-tabs{
            border-bottom: none;
        }
        .panel.with-nav-tabs .nav-justified{
            margin-bottom: -1px;
        }
        /********************************************************************/
        /*** PANEL DEFAULT ***/
        .with-nav-tabs.panel-default .nav-tabs > li > a,
        .with-nav-tabs.panel-default .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-default .nav-tabs > li > a:focus {
            color: #777;
        }
        .with-nav-tabs.panel-default .nav-tabs > .open > a,
        .with-nav-tabs.panel-default .nav-tabs > .open > a:hover,
        .with-nav-tabs.panel-default .nav-tabs > .open > a:focus,
        .with-nav-tabs.panel-default .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-default .nav-tabs > li > a:focus {
            color: #777;
            background-color: #ddd;
            border-color: transparent;
        }
        .with-nav-tabs.panel-default .nav-tabs > li.active > a,
        .with-nav-tabs.panel-default .nav-tabs > li.active > a:hover,
        .with-nav-tabs.panel-default .nav-tabs > li.active > a:focus {
            color: #555;
            background-color: #fff;
            border-color: #ddd;
            border-bottom-color: transparent;
        }
        .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu {
            background-color: #f5f5f5;
            border-color: #ddd;
        }
        .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a {
            color: #777;
        }
        .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
        .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
            background-color: #ddd;
        }
        .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a,
        .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
        .with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
            color: #fff;
            background-color: #555;
        }
        /********************************************************************/
        /*** PANEL PRIMARY ***/
        .with-nav-tabs.panel-primary .nav-tabs > li > a,
        .with-nav-tabs.panel-primary .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-primary .nav-tabs > li > a:focus {
            color: #fff;
        }
        .with-nav-tabs.panel-primary .nav-tabs > .open > a,
        .with-nav-tabs.panel-primary .nav-tabs > .open > a:hover,
        .with-nav-tabs.panel-primary .nav-tabs > .open > a:focus,
        .with-nav-tabs.panel-primary .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-primary .nav-tabs > li > a:focus {
            color: #fff;
            background-color: #3071a9;
            border-color: transparent;
        }
        .with-nav-tabs.panel-primary .nav-tabs > li.active > a,
        .with-nav-tabs.panel-primary .nav-tabs > li.active > a:hover,
        .with-nav-tabs.panel-primary .nav-tabs > li.active > a:focus {
            color: #428bca;
            background-color: #fff;
            border-color: #428bca;
            border-bottom-color: transparent;
        }
        .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu {
            background-color: #428bca;
            border-color: #3071a9;
        }
        .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > li > a {
            color: #fff;
        }
        .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
        .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
            background-color: #3071a9;
        }
        .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > .active > a,
        .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
        .with-nav-tabs.panel-primary .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
            background-color: #4a9fe9;
        }
        /********************************************************************/
        /*** PANEL SUCCESS ***/
        .with-nav-tabs.panel-success .nav-tabs > li > a,
        .with-nav-tabs.panel-success .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-success .nav-tabs > li > a:focus {
            color: #3c763d;
        }
        .with-nav-tabs.panel-success .nav-tabs > .open > a,
        .with-nav-tabs.panel-success .nav-tabs > .open > a:hover,
        .with-nav-tabs.panel-success .nav-tabs > .open > a:focus,
        .with-nav-tabs.panel-success .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-success .nav-tabs > li > a:focus {
            color: #3c763d;
            background-color: #d6e9c6;
            border-color: transparent;
        }
        .with-nav-tabs.panel-success .nav-tabs > li.active > a,
        .with-nav-tabs.panel-success .nav-tabs > li.active > a:hover,
        .with-nav-tabs.panel-success .nav-tabs > li.active > a:focus {
            color: #3c763d;
            background-color: #fff;
            border-color: #d6e9c6;
            border-bottom-color: transparent;
        }
        .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu {
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > li > a {
            color: #3c763d;
        }
        .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
        .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
            background-color: #d6e9c6;
        }
        .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > .active > a,
        .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
        .with-nav-tabs.panel-success .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
            color: #fff;
            background-color: #3c763d;
        }
        /********************************************************************/
        /*** PANEL INFO ***/
        .with-nav-tabs.panel-info .nav-tabs > li > a,
        .with-nav-tabs.panel-info .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-info .nav-tabs > li > a:focus {
            color: #31708f;
        }
        .with-nav-tabs.panel-info .nav-tabs > .open > a,
        .with-nav-tabs.panel-info .nav-tabs > .open > a:hover,
        .with-nav-tabs.panel-info .nav-tabs > .open > a:focus,
        .with-nav-tabs.panel-info .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-info .nav-tabs > li > a:focus {
            color: #31708f;
            background-color: #bce8f1;
            border-color: transparent;
        }
        .with-nav-tabs.panel-info .nav-tabs > li.active > a,
        .with-nav-tabs.panel-info .nav-tabs > li.active > a:hover,
        .with-nav-tabs.panel-info .nav-tabs > li.active > a:focus {
            color: #31708f;
            background-color: #fff;
            border-color: #bce8f1;
            border-bottom-color: transparent;
        }
        .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu {
            background-color: #d9edf7;
            border-color: #bce8f1;
        }
        .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > li > a {
            color: #31708f;
        }
        .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
        .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
            background-color: #bce8f1;
        }
        .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > .active > a,
        .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
        .with-nav-tabs.panel-info .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
            color: #fff;
            background-color: #31708f;
        }
        /********************************************************************/
        /*** PANEL WARNING ***/
        .with-nav-tabs.panel-warning .nav-tabs > li > a,
        .with-nav-tabs.panel-warning .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-warning .nav-tabs > li > a:focus {
            color: #8a6d3b;
        }
        .with-nav-tabs.panel-warning .nav-tabs > .open > a,
        .with-nav-tabs.panel-warning .nav-tabs > .open > a:hover,
        .with-nav-tabs.panel-warning .nav-tabs > .open > a:focus,
        .with-nav-tabs.panel-warning .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-warning .nav-tabs > li > a:focus {
            color: #8a6d3b;
            background-color: #faebcc;
            border-color: transparent;
        }
        .with-nav-tabs.panel-warning .nav-tabs > li.active > a,
        .with-nav-tabs.panel-warning .nav-tabs > li.active > a:hover,
        .with-nav-tabs.panel-warning .nav-tabs > li.active > a:focus {
            color: #8a6d3b;
            background-color: #fff;
            border-color: #faebcc;
            border-bottom-color: transparent;
        }
        .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu {
            background-color: #fcf8e3;
            border-color: #faebcc;
        }
        .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > li > a {
            color: #8a6d3b;
        }
        .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
        .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
            background-color: #faebcc;
        }
        .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > .active > a,
        .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
        .with-nav-tabs.panel-warning .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
            color: #fff;
            background-color: #8a6d3b;
        }
        /********************************************************************/
        /*** PANEL DANGER ***/
        .with-nav-tabs.panel-danger .nav-tabs > li > a,
        .with-nav-tabs.panel-danger .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-danger .nav-tabs > li > a:focus {
            color: #a94442;
        }
        .with-nav-tabs.panel-danger .nav-tabs > .open > a,
        .with-nav-tabs.panel-danger .nav-tabs > .open > a:hover,
        .with-nav-tabs.panel-danger .nav-tabs > .open > a:focus,
        .with-nav-tabs.panel-danger .nav-tabs > li > a:hover,
        .with-nav-tabs.panel-danger .nav-tabs > li > a:focus {
            color: #a94442;
            background-color: #ebccd1;
            border-color: transparent;
        }
        .with-nav-tabs.panel-danger .nav-tabs > li.active > a,
        .with-nav-tabs.panel-danger .nav-tabs > li.active > a:hover,
        .with-nav-tabs.panel-danger .nav-tabs > li.active > a:focus {
            color: #a94442;
            background-color: #fff;
            border-color: #ebccd1;
            border-bottom-color: transparent;
        }
        .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu {
            background-color: #f2dede; /* bg color */
            border-color: #ebccd1; /* border color */
        }
        .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > li > a {
            color: #a94442; /* normal text color */
        }
        .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
        .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
            background-color: #ebccd1; /* hover bg color */
        }
        .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > .active > a,
        .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
        .with-nav-tabs.panel-danger .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
            color: #fff; /* active text color */
            background-color: #a94442; /* active bg color */
        }
    </style>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config("Sistema BMI", "Sistema BMI") }}
                    </a>

                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <div class="col-lg-6">
                        <!-- /input-group -->
                    </div>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Ingresar</a></li>
                        @else
                            @if(Role::where('id',Auth::user()->role_id)->first()->name=='bmiagente')
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle nav nav-tabs" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Bmi <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{url('home')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-list-alt"></span> Ver Citas</a>
                                    </li>
                                    <li>
                                        <a href="{{url('agendarCitasPropias')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-list-alt"></span> Agendar Citas Propias</a>
                                    </li>
                                    <li>
                                        <a href="{{url('historialAs')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-list-alt"></span> Historial de Citas</a>
                                    </li>
                                    <li>
                                        <a href="{{url('historialAsMan')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-list-alt"></span> Historial de Citas Manuales</a>
                                    </li>
                                </ul>
                            </li>
                            @elseif(\Voyager::can('browse_supervisor_bmi'))
                            <li>
                                <a href="{{url('home')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-dashboard"></span> Inicio</a>
                            </li>


                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle nav nav-tabs" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span> Clientes <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{url('nuevaCarga')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Carga de clientes</a>
                                    </li>
                                    <li>
                                        <a href="{{url('busquedabmi')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Lista de clientes</a>
                                    </li>
                                    <li>
                                        <a href="{{url('confirmacionCitaEmail')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Confirmación cita (email)</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle nav nav-tabs" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span> Asesores <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{url('/verAsesoresBmi')}}">
                                            <span class="glyphicon glyphicon-new-window"></span> Ver Asesores
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('/asesorBmi')}}">
                                            <span class="glyphicon glyphicon-new-window"></span> Ingresar Asesores
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('verRankingAsesores')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Ranking Asesores</a>
                                    </li>
                                    {{--<li>
                                        <a href="{{url('gestion')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Gestiones</a>
                                    </li>--}}
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle nav nav-tabs" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span> Citas<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{url('agendarCitasBmi')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Asignar Citas</a>
                                    </li>
                                    {{--<li>--}}
                                        {{--<a href="{{url('agendarCitasBmi')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Agendar Citas</a>--}}
                                    {{--</li>--}}
                                    <li>
                                        <a href="{{url('historialCitas')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Historial Citas</a>
                                    </li>
                                    <li>
                                        <a href="{{url('agendarCitasPropias')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-list-alt"></span> Agendar Citas Propias</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle nav nav-tabs" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span> Parámetros<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{url('rankingClientes')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Ranking de clientes</a>
                                    </li>
                                    <li>
                                        <a href="{{url('rankingAsesores')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Ranking de asesores</a>
                                    </li>
                                    <li>
                                        <a href="{{url('tipoAccion')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Tipo - Acción</a>
                                    </li>
                                    <li>
                                        <a href="{{url('citas')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Citas</a>
                                    </li>
                                    <li>
                                        <a href="{{url('productos')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-new-window"></span> Productos</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle nav nav-tabs" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span> Reportes<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{url('/reportCitasConfirmadasBmi')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-download"></span> Reportes Citas Confirmadas TLC</a>
                                    </li>
                                    <li>
                                        <a href="{{url('/reportEfectividadBmi')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-download"></span> Reportes de efectividad</a>
                                    </li>
                                    <li>
                                        <a href="{{url('/reportGeneralCuentas')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-download"></span> General de cuentas</a>
                                    </li>
                                </ul>
                            </li>

                            @endif
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle nav nav-tabs" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span> {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{url('/home')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-home"></span> Home</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/cambiarP') }}"><span class="glyphicon glyphicon-new-window"></span>
                                            Cambiar contraseña
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <span class="glyphicon glyphicon-log-out"></span>
                                            Cerrar Sesión
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
</body>
</html>