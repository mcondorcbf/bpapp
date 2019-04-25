<link rel="stylesheet" type="text/css" href="{{asset('/css/jquery.dataTables.min.css')}}"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">

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

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js"></script>

<script type="text/javascript" src="/js/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#listas').DataTable( {
            "order": [[ 0, "asc" ]],
            "scrollX": true,
            "lengthMenu": [[10,20,30,-1], [10,20,30,'Todo']]
        });
        $('#listaf').DataTable( {
            "order": [[ 0, "asc" ]],
            "scrollX": true,
            "lengthMenu": [[10,20,30,-1], [10,20,30,'Todo']]
        });

        $('#listaps').DataTable( {
            "order": [[ 0, "asc" ]],
            "scrollX": true,
            "lengthMenu": [[10,20,30,-1], [10,20,30,'Todo']]
        });
        $('#listapf').DataTable( {
            "order": [[ 0, "asc" ]],
            "scrollX": true,
            "lengthMenu": [[10,20,30,-1], [10,20,30,'Todo']]
        });

        $('#listams').DataTable( {
            "order": [[ 0, "asc" ]],
            "scrollX": true,
            "lengthMenu": [[10,20,30,-1], [10,20,30,'Todo']]
        });
        $('#listamf').DataTable( {
            "order": [[ 0, "asc" ]],
            "scrollX": true,
            "lengthMenu": [[10,20,30,-1], [10,20,30,'Todo']]
        });
    });
</script>
<style>
    .col-form-label{
        text-align: right;
    }
    .container{
        font-size: 12px;
    }
    table{
        font-size: 11px;
    }
    .panel-primary>.panel-heading .badge{
        color: #ffffff;
        background-color: #245799;
    }
</style>
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="font-size: 11px">

                <div class="panel with-nav-tabs panel-primary">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"> <a href="#primeroa" data-toggle="tab" onclick="$('#reporte_nro').val(1);">CITAS AGENCIA SEGUIMIENTO <span class="badge">{{count($citas_seguimiento)}}</span></a></li>
                            <li class="nav-item"><a href="#segundoa" data-toggle="tab" onclick="$('#reporte_nro').val(2);">CITAS AGENCIA FINALIZADAS <span class="badge">{{count($citas_finalizadas)}}</span></a></li>
                            <input type="hidden" id="reporte_nro" value="1">
                        </ul>
                    </div>

                    <div class="panel-body">
                        <div class="content">
                            <div class="form-group">
                                <div class="tab-content tab-content-border" >
                                    <div class="tab-pane fade active in" id="primeroa">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-hover table-striped display" id="listas" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Cliente</th>
                                                <th>Fecha de visita</th>
                                                <th>Fecha próxima gestión</th>
                                                <th>Observaciones</th>
                                                <th>Estado</th>
                                                </thead>
                                                <tbody id="data2">
                                                <?php $i=1;?>
                                                @foreach($citas_seguimiento as $k)
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->nombres}}
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->fecha_cita}} {{$k->citaHistorial->hora_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->fecha_proxima_visita}}
                                                        </td>
                                                        <td>
                                                            {{$k->observaciones}}
                                                        </td>
                                                        <td>
                                                            @if($k->accion->peso==100)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @elseif($k->accion->peso==50)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @elseif($k->accion->peso<50)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <?php $i++?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="segundoa">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-hover table-striped display" id="listaf" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Cliente</th>
                                                <th>Fecha de visita</th>
                                                <th>Observaciones</th>
                                                <th>Estado</th>
                                                </thead>
                                                <tbody id="data2">
                                                <?php $i=1;?>
                                                @foreach($citas_finalizadas as $k)
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->nombres}}
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->fecha_cita}} {{$k->citaHistorial->hora_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->observaciones}}
                                                        </td>
                                                        @if($k)
                                                            <td>
                                                                @if($k->accion->peso==100)
                                                                    <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                                @elseif($k->accion->peso==50)
                                                                    <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                                @elseif($k->accion->peso<50)
                                                                    <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                                @endif
                                                            </td>
                                                        @else
                                                            <td>

                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>

                                                            </td>
                                                        @endif
                                                    </tr>
                                                    <?php $i++?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel with-nav-tabs panel-warning">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#primero" data-toggle="tab" onclick="$('#reporte_nro').val(1);">CITAS PROPIAS SEGUIMIENTO <span class="badge">{{count($citas_propias_seguimiento)}}</span></a></li>
                            <li class="nav-item"><a href="#segundo" data-toggle="tab" onclick="$('#reporte_nro').val(2);">CITAS PROPIAS FINALIZADAS <span class="badge">{{count($citas_propias_finalizadas)}}</span></a></li>
                            <input type="hidden" id="reporte_nro" value="1">
                        </ul>
                    </div>

                    <div class="panel-body">
                        <div class="content">
                            <div class="form-group">
                                <div class="tab-content tab-content-border" >
                                    <div class="tab-pane fade active in" id="primero">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-hover table-striped display" id="listaps" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Cliente</th>
                                                <th>Fecha de visita</th>
                                                <th>Fecha próxima gestión</th>
                                                <th>Observaciones</th>
                                                <th>Estado</th>
                                                </thead>
                                                <tbody id="data2">
                                                <?php $i=1;?>
                                                @foreach($citas_propias_seguimiento as $k)
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->nombres}}
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->fecha_cita}} {{$k->citaHistorial->hora_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->fecha_proxima_visita}}
                                                        </td>
                                                        <td>
                                                            {{$k->observaciones}}
                                                        </td>
                                                        @if($k)
                                                            <td>
                                                                @if($k->accion->peso==100)
                                                                    <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                                @elseif($k->accion->peso==50)
                                                                    <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                                @elseif($k->accion->peso<50)
                                                                    <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                                @endif
                                                            </td>
                                                        @else
                                                            <td>

                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>

                                                            </td>
                                                        @endif
                                                    </tr>
                                                    <?php $i++?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="segundo">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-hover table-striped display" id="listapf" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Cliente</th>
                                                <th>Fecha de visita</th>
                                                <th>Observaciones</th>
                                                <th>Estado</th>
                                                </thead>
                                                <tbody id="data2">
                                                <?php $i=1;?>
                                                @foreach($citas_propias_finalizadas as $k)
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->nombres}}
                                                        </td>
                                                        <td>
                                                            {{$k->citaHistorial->fecha_cita}} {{$k->citaHistorial->hora_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->observaciones}}
                                                        </td>
                                                        @if($k)
                                                            <td>
                                                                @if($k->accion->peso==100)
                                                                    <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                                @elseif($k->accion->peso==50)
                                                                    <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                                @elseif($k->accion->peso<50)
                                                                    <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                                @endif
                                                            </td>
                                                        @else
                                                            <td>

                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>

                                                            </td>
                                                            <td>

                                                            </td>
                                                        @endif
                                                    </tr>
                                                    <?php $i++?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel with-nav-tabs panel-success">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#primerom" data-toggle="tab" onclick="$('#reporte_nro').val(1);">CITAS MANUALES SEGUIMIENTO <span class="badge">{{count($citas_manuales_seguimiento)}}</span></a></li>
                            <li class="nav-item"><a href="#segundom" data-toggle="tab" onclick="$('#reporte_nro').val(2);">CITAS MANUALES FINALIZADAS <span class="badge">{{count($citas_manuales_finalizadas)}}</span></a></li>
                            <input type="hidden" id="reporte_nro" value="1">
                        </ul>
                    </div>

                    <div class="panel-body">
                        <div class="content">
                            <div class="form-group">
                                <div class="tab-content tab-content-border" >
                                    <div class="tab-pane fade active in" id="primerom">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-hover table-striped display" id="listams" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Cliente</th>
                                                <th>Fecha de visita</th>
                                                <th>Fecha próxima gestión</th>
                                                <th>Observaciones</th>
                                                <th>Estado</th>
                                                </thead>
                                                <tbody id="data2">
                                                <?php $i=1;?>
                                                @foreach($citas_manuales_seguimiento as $k)
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                                        </td>
                                                        <td>
                                                            {{$k->nombres}}
                                                        </td>
                                                        <td>
                                                            {{$k->fecha_cita}} {{$k->hora_cita}}
                                                        </td>
                                                        <td>
                                                            {{$k->fecha_proxima_visita}}
                                                        </td>
                                                        <td>
                                                            {{$k->observacion}}
                                                        </td>
                                                        <td>
                                                            @if($k->accion->peso==100)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @elseif($k->accion->peso==50)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @elseif($k->accion->peso<50)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <?php $i++?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="segundom">
                                        <div class="col-md-12 col-lg-12">
                                            <table class="table table-hover table-striped display" id="listamf" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Cliente</th>
                                                <th>Fecha de visita</th>
                                                <th>Observaciones</th>
                                                <th>Estado</th>
                                                </thead>
                                                <tbody id="data2">
                                                <?php $i=1;?>
                                                @foreach($citas_manuales_finalizadas as $k)
                                                    <tr>
                                                        <td>
                                                            <button type="button" class="modalButton1{{$k->cedula_cliente}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/clienteRk/'.$k->cedula_cliente)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->cedula_cliente}})">{{$i}}</button>
                                                        </td>
                                                        <td>
                                                            {{$k->nombres}}
                                                        </td>
                                                        <td>
                                                            {{$k->fecha_cita}} {{$k->hora_cita}}
                                                        </td>


                                                        <td>
                                                            {{$k->observacion}}
                                                        </td>
                                                        <td>
                                                            @if($k->accion->peso==100)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-success btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @elseif($k->accion->peso==50)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-warning btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @elseif($k->accion->peso<50)
                                                                <button type="button" class="modalButton1{{$k->id_gestion}} btn btn-danger btn-xs" data-toggle="modal" data-src="{{url('/gestionShow/'.$k->id_gestion)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1{{$k->id_gestion}})">{{$k->accion->descripcion}}</button>
                                                            @endif
                                                        </td>

                                                    </tr>
                                                    <?php $i++?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

    <script type="text/javascript">

    </script>