@extends('layouts.appReportesNuevoSistema')
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
    $(document).ready(function() {
        $('#lista_p').DataTable( {
            "order": [[ 0, "desc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        } );
    } );
    $(document).ready(function() {
        $('#lista_u').DataTable( {
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        } );
    } );
    $(document).ready(function() {
        $('#lista_ug').DataTable( {
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        } );
    } );
    $(document).ready(function() {
        $('#lista_u2').DataTable( {
            "order": [[ 0, "desc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        } );
    } );
    $(document).ready(function() {
        $('#lista_preguntas').DataTable( {
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        } );
    } );

    $(document).ready(function() {
        $('#lista_usuarios_grupos').DataTable( {
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[10,20,50,-1,], [10,20,50,"Todo"]]
        } );
    } );

    function  alerta(id)
    {
        var src = $('.modalButton'+id).attr('data-src');
        var width = $('.modalButton'+id).attr('data-width') || 780;
        var height = $('.modalButton'+id).attr('data-height') || 500;
        var allowfullscreen = $(this).attr('data-video-fullscreen');

        $("#myModal iframe").attr({
            'src': src,
            'height': height,
            'width': width,
            'allowfullscreen':''
        });

        $('#myModal').on('hidden.bs.modal', function(){
            $(this).find('iframe').html("");
            $(this).find('iframe').attr("src", "");
            //alert('hi');
        });
    }
</script>

@endsection
@section('content')
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 830px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="body">
                <iframe class="embed-responsive-item" src="" id="frame" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="panel with-nav-tabs panel-primary">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#primer_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(1);">RESULTADO ENCUESTA - AUDITORÍA</a></li>
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel panel-default">
            <div align="center">

            </div>
            <div class="panel-body" style="font-size:11px">
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>Gestores vinculados</strong></div>
                    <table class="table table-hover table-striped display table-bordered" id="lista" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>CÉDULA</th>
                            <th>NOMBRES</th>
                            <th>EMAIL</th>
                            <th>ACCION</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1;$result='';?>
                        <tr>
                            <td>#</td>
                            <td>{{$asesor->cedula}}</td>
                            <td>{{$asesor->nombres}}</td>
                            <td>{{$asesor->email}}</td>
                            <td>CLIENTES ASIGNADOS<br><span class="badge badge-success" style="background-color: green">manuales</span><span class="badge badge-success" style="background-color:#2a6496">automáticos</span></td>
                        </tr>
                        </tbody>
                    </table>
                    <?php print_r($result);?></div>
                <div class="panel panel-success">
                    <div class="panel-heading"><strong>Gestores disponibles</strong></div>
                    <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="lista_clientes">
                        <thead>
                        <tr>
                            <th>CEDULA</th>
                            <th>NOMBRES</th>
                            <th>TELEFONO</th>
                            <th>SUELDO</th>
                            <th>DIRECCION DE VISITA</th>
                            <th>FECHA CITA</th>
                            <th>HORA CITA</th>
                            <th>DATOS ADICIONALES</th>
                            <th>RANKING</th>
                        </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                    <?php print_r($result);?></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("select[name='id_marca']").change(function(){
        var reporte_nro = $('#reporte_nro').val();
        $('#loader-icon'+reporte_nro).hide();
        var id_marca = $(this).val();
        var token = $("input[name='_token']").val();
        var homeLoader = $('body').loadingIndicator({
            useImage: false,
        }).data("loadingIndicator");
        homeLoader.show();
        $.ajax({
            url: "/gProducto",
            method: 'POST',
            data: {id_marca:id_marca, _token:token},
            success: function(data) {
                $("select[name='id_producto']").html('');
                $("select[name='id_producto']").html(data.options);
                $("#cuentas"+reporte_nro).html('');
                homeLoader.hide();
            }
        });
    });

    $("select[name='id_producto']").change(function(){
        var reporte_nro = $('#reporte_nro').val();
        $('#loader-icon'+reporte_nro).hide();
        var id_producto = $(this).val();
        var token = $("input[name='_token']").val();
        var homeLoader = $('body').loadingIndicator({
            useImage: false,
        }).data("loadingIndicator");
        homeLoader.show();
        $.ajax({
            url: "/gCampana",
            method: 'POST',
            data: {id_producto:id_producto, _token:token},
            success: function(data) {
                $("select[name='id_campana']").html('');
                $("select[name='id_campana']").html(data.options);
                $("#cuentas"+reporte_nro).html('');
                homeLoader.hide();
            }
        });
    });

    $("select[name='id_campana']").change(function(){
        var reporte_nro = $('#reporte_nro').val();
        $('#loader-icon'+reporte_nro).show();
        $("#cuentas"+reporte_nro).html('');
        var id_campana = $(this).val();
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "/gCuentasGenerico",
            method: 'POST',
            data: {id_campana:id_campana, _token:token},
            success: function(data) {
                $('#loader-icon'+reporte_nro).hide();
                console.log(data.cuentas);
                $("#cuentas"+reporte_nro).html('- '+data.cuentas+' Cuentas<br>'+'- '+data.gestiones+' Gestiones');
            }
        });
    });
</script>
@endsection