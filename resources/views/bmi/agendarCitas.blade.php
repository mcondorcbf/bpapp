@extends('layouts.appBmi')
@section('scripts')
<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="/js/datatables.min.js"></script>
<script type="application/javascript">
    $(document).ready(function() {
        $('#lista').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[-1,10,20,50], ["All",10,20,50]]
        } );
        $('#listaf').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 0, "asc" ]],
            "lengthMenu": [[-1,100,50,20,10], ["All",100,50,20,10]]
        } );
        $('#lista_clientes').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "order": [[ 5, "asc" ]],
            "lengthMenu": [[10,20,-1], [10,20,"All"]]
        } );
    } );
    $().ready(function()
    {
        $('.pasar').click(function() {
            if($('#origen option:selected').val()==undefined){return false;}

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#cargando").css("display", "inline");
            var form = $(this);
            var url = '{{URL::to('usuariosClientes')}}';

            var formData = $('#origen option:selected').val();
            //formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: url,
                type: "POST",
                url: url,
                data: $("#formulario").serialize(),
                success: function(data)
                {
                    $("#cargando").css("display", "none");
                    $("#resultado").css("display", "inline");
                    $("#resultado").wrapInner( "<div style='color:#3f51b5'>"+data+"</div>");
                }
            });
            return !$('#origen option:selected').remove().appendTo('#destino');
        });
        $('.quitar').click(function() {
            return !$('#destino option:selected').remove().appendTo('#origen');
        });
        $('.pasartodos').click(function() { $('#origen option').each(function() {
            if(document.getElementById('clienteselect').value==''){
                alert('Debe Seleccionar un cliente');
                document.getElementById("clienteselect").focus();
                return false;
            }
            $(this).remove().appendTo('#destino'); });
        });
        $('.quitartodos').click(function() { $('#destino option').each(function() { $(this).remove().appendTo('#origen'); }); });
        $('.submit').click(function() { $('#destino option').prop('selected', 'selected'); });
    });

    ( function($) {

        function iframeModalOpen(){


            $('.modalButton').on('click', function(e) {
                var src = $(this).attr('data-src');
                var width = $(this).attr('data-width') || 640; // larghezza dell'iframe se non impostato usa 640
                var height = $(this).attr('data-height') || 360;

                var allowfullscreen = $(this).attr('data-video-fullscreen');


                $("#myModal iframe").attr({
                    'src': src,
                    'height': height,
                    'width': width,
                    'allowfullscreen':''
                });
            });


            $('#myModal').on('hidden.bs.modal', function(){
                $(this).find('iframe').html("");
                $(this).find('iframe').attr("src", "");
            });
        }

        $(document).ready(function(){
            iframeModalOpen();
        });

    } ) ( jQuery );
</script>
@endsection
@section('content')
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">PARAMETROS DE AGENDAMIENTO</h5>
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

<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Agendar Citas - Asesores Activos</h1>
    </div>
    <hr>
    <div align="center">
        @if($citas>0)
            <a href="{{url('/asignacionAutomatica')}}" class="btn btn-primary disabled" disabled>Asignación automática</a>
            <a href="{{url('/quitarAsignacionAutomatica')}}" class="btn btn-warning ">Desasignar automáticamente</a>
        @else
            <?php $cliente=\App\bmi\tbl_clientes::where('estado',1)->count(); ?>
            @if($cliente>0)
            <a href="{{url('/asignacionAutomatica')}}" class="modalButton btn btn-primary">Asignación automática</a>
            <a href="{{url('/quitarAsignacionAutomatica')}}" class="modalButton btn btn-warning disabled" disabled>Desasignar automáticamente</a>
            {{--<button type="button" class="modalButton btn btn-success" data-toggle="modal" data-src="{{url('/agendamientoAutomatico')}}" data-width="570" data-height="350" data-target="#myModal" data-video-fullscreen="">Ver parámetros Citas</button>--}}
                    <a href="{{url('/citas')}}" class="modalButton btn btn-success">Ver parámetros citas</a>
            @endif
        @endif
    </div>
    <div class="panel-body" style="font-size:11px">
        <div class="panel panel-primary">
            <div class="panel-heading"><strong>Asesores disponibles</strong></div>
        <table class="table table-hover table-striped display table-bordered" id="lista" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>CÉDULA</th>
                <th>NOMBRES</th>
                <th>CELULAR</th>
                <th>EMAIL PERSONAL</th>
                <th>EMAIL CORPORATIVO</th>
                <th>FECHA DE CONTRATO</th>
                <th>RANKING</th>
                <th>CLIENTES ASIGNADOS<br><span class="badge badge-success" style="background-color: green">manuales</span><span class="badge badge-success" style="background-color:#2a6496">automáticos</span></th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1;$result='';?>@foreach($asesores as $k=>$v)
            <tr>
                <td>{{$i}}</td>
                <td><a href="{{ url('/agendarCitassBmi/'.$v->cedula_asesor) }}" class="modalButton btn btn-success btn-xs">{{$v['cedula_asesor']}}</a></td>
                <td>{{$v->nombres}}</td>
                <td>{{$v->celular}}</td>
                <td>{{$v->email_personal}}</td>
                <td>{{$v->email_corporativo}}</td>
                <td>{{$v->fecha_contrato}}</td>
                <td>{{$v->ranking_asesor->descripcion}}</td>
                <?php $cliente_manual=\App\bmi\tbl_citas::where('asesor',$v->cedula_asesor)->where('cita_automatica',0)->whereNull('id_gestion')->count();
                $cliente_automatico=\App\bmi\tbl_citas::where('asesor',$v->cedula_asesor)->where('cita_automatica',1)->whereNull('id_gestion')->count();?>
                <td><span class="badge badge-success" style="background-color: green">{{$cliente_manual}}</span> | <span class="badge badge-success" style="background-color:#2a6496">{{$cliente_automatico}}</span></td>
            </tr><?php $i++;?>
            @endforeach
            </tbody>
        </table>
            <?php print_r($result);?></div>
        <div class="panel panel-success">
            <div class="panel-heading"><strong>Clientes disponibles</strong></div>
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
                @foreach($clientes as $k)
                    <tr>
                        <td>
                        <?php
                        $cedula_cliente=trim($k->cedula_cliente);
                        if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cedula_cliente;}
                        ?>
                        {{$cedula_cliente}}
                        </td>
                        <td>{{$k->nombres}}</td>
                        <td>{{$k->celular}}</td>
                        <td>${{$k->salario}}</td>
                        <td>{{$k->direccion_visita}}</td>
                        <td>{{$k->fecha_cita}}</td>
                        <td>{{$k->hora_cita}}</td>
                        <td>{{$k->datos_adicionales}}</td>
                        <td>
                            {{isset($k->ranking_cliente->descripcion) ? $k->ranking_cliente->descripcion : ''}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <?php print_r($result);?></div>
    </div>
</div>
@endsection