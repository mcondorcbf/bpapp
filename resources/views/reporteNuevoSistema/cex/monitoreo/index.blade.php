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
                    <li class="inactive"><a href="{{url('/monitoreoCexRes')}}"><span class="glyphicon glyphicon-eye-open"> </span> MONITOREO EN LÍNEA RESUMEN</a></li>
                    <li class="active"><a href="{{url('/monitoreoCex')}}"><span class="glyphicon glyphicon-eye-open"> </span> MONITOREO EN LÍNEA</a></li>
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

                                    <table id="tbl_ca" class="table table-sm table-dark" style="width:100%; font-size: 11px;">
                                        <thead class="alert alert-danger">
                                        <tr>
                                            <th>#</th>
                                            <th>CEDULA</th>
                                            <th>IMEI</th>
                                            <th>TELEFONO</th>
                                            <th>NOMBRES</th>
                                            <th>HORA</th>
                                            <th>TIEMPO DETENIDO</th>
                                            <th>HORA SALIDA</th>
                                            <th>TIEMPO RECORRIDO</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1;?>
                                        @foreach($gestors as $gestor)
                                            @if($gestor->imei=='')<tr class="alert alert-danger">@else<tr>@endif
                                            <td>{{$i++}}</td>
                                            <td>{{$gestor->cedula}}</td>
                                            <td>{{$gestor->imei}}</td>
                                            <td>{{$gestor->telefono}}</td>
                                            <td>{{$gestor->nombre}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><button class="modalButton btn btn-primary btn-xs" id="button"><span class="glyphicon glyphicon-eye-open"> </span> Ingresar Observación</button></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col-md-6 col-lg-6">
                                    <iframe src="" width="100%" height="0" frameborder="0" scrolling="yes" id="iframe"></iframe>
                                    </div>

                                    <div class="col-md-6 col-lg-6">
                                        <iframe src="" width="100%" height="0" frameborder="0" scrolling="yes" id="iframe2"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('button').click(function () {
                var url = 'mapaCex/'+$(this).attr('rel');
                if (url=='mapaCex/undefined'){return true;}
                console.log(url);
                $('#iframe').attr('src', url);
                $('#iframe').attr('height', 800);

                url = 'dashCex/'+$(this).attr('rel');
                $('#iframe2').attr('src', url);
                $('#iframe2').attr('height', 800);

                $('#iframe').reload();
                $('#iframe2').reload();
            });
        });

        setInterval("refresh()", 5000);

        function refresh(){

            var imei = '357818090839113';
            var update_time = '{{isset($fecha) ? $fecha: date('Y-m-d')}}';
            var token = $("input[name='_token']").val();
            $.ajax({
                url: "/calculoDistanciaCex",
                method: 'POST',
                data: {imei:imei,update_time:update_time, _token:token},
                success: function(data) {
                    console.log(data);
                    return true;

                    $('#loader-icon'+reporte_nro).hide();
                    console.log(data.cuentas);
                    $("#cuentas"+reporte_nro).html('- '+data.cuentas+' Cuentas<br>'+'- '+data.gestiones+' Gestiones');
                }
            });
        }
    </script>
@endsection