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
    <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/jquery.dataTables.min.css')}}"/>

    <!-- Scripts -->

    <script src="{{asset('/js/bootstrap.min.js')}}"></script>
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
                        {{ config("Sistema Movistar", "Sistema Movistar") }}
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
                            @if(Role::where('id',Auth::user()->role_id)->first()->name=='ivradministrador')
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle nav nav-tabs" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Administrar IVR's <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{url('administrarIvr')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-list-alt"></span> Asignar Clientes</a>
                                    </li>
                                    <li>
                                        <a href="{{url('scriptsEstaticosIvr')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-record"></span> Administrar Scripts</a>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            <li>
                                <a href="{{url('movistar')}}" class="nav nav-tabs"><span class="glyphicon glyphicon-dashboard"></span> Principal</a>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle nav nav-tabs" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="glyphicon glyphicon-user"></span> {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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