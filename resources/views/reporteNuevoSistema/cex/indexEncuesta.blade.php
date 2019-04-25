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
                <li class="@if($menu=='primer_reporte') active @else nav_item @endif"><a href="#primer_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(1);">RESULTADO ENCUESTA - AUDITORÍA</a></li>
                <li class="@if($menu=='segundo_reporte') active @else nav_item @endif"><a href="#segundo_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(2);">USUARIOS</a></li>
                <li class="@if($menu=='tercer_reporte') active @else nav_item @endif"><a href="#tercer_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(3);">LISTA DE PREGUNTAS</a></li>
                <li class="@if($menu=='cuarto_reporte') active @else nav_item @endif"><a href="#cuarto_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(3);">FORMULARIO ENCUESTA</a></li>
                <li class="@if($menu=='quinto_reporte') active @else nav_item @endif"><a href="#quinto_reporte" data-toggle="tab" onclick="$('#reporte_nro').val(3);">GRUPO USUARIO</a></li>
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border" >
                        <div class="tab-pane fade @if($menu=='primer_reporte') active in @endif" id="primer_reporte">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="rConsolidadoSM" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">
                                        <table class="table table-hover table-striped display" id="lista_p" cellspacing="0" width="100%">
                                            <thead>
                                            <th>#</th>
                                            <th>Fecha encuesta</th>
                                            <th>Nombres gestor</th>
                                            <th>Calificación</th>
                                            </thead>
                                            <tbody id="data">
                                            <?php $i=1;?>
                                                <tr>
                                                    <td>1</td>
                                                    <td>17/05/2018</td>
                                                    <td>Luis Acosta Grijalba</td>
                                                    <td>13/15</td>
                                                </tr>
                                            <?php $i++?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                                <nav class="navbar navbar-default">
                                    <div class="container-fluid">
                                        <div class="navbar-header">
                                            <a class="navbar-brand" href="#" id="cuentas1" style="color: #000; margin-bottom: 15px"></a>
                                            <div id="loader-icon1" style="display:none; color: green;padding-top: 5px" align="center">
                                                <img src="{{asset('images/loading.gif')}}" width="70"><br>PROCESANDO . . .
                                            </div>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>

                        <div class="tab-pane fade @if($menu=='segundo_reporte') active in @endif" id="segundo_reporte">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="reditUsuario" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">
                                        <table class="table table-hover table-striped display" id="lista_u" cellspacing="0" width="100%">
                                            <thead>
                                            <th>#</th>
                                            <th>Nombres gestor</th>
                                            <th>Correo</th>
                                            <th>Rol</th>
                                            <th>Acción</th>
                                            </thead>
                                            <tbody id="data2">
                                            <?php $i=1;?>
                                            @foreach($usuarios as $k)
                                            @if($k->rol->nombre=='Supervisor' || $k->rol->nombre=='Coordinador')
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>{{$k->nombres}}</td>
                                                <td>{{$k->email}}</td>
                                                <td>{{$k->rol->nombre}}</td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-src="" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1)"><span class="glyphicon glyphicon-edit"></span> Editar</button>
                                                    <div class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> Eliminar</div>
                                                    <a class="btn btn-primary btn-xs" href="{{url('/usuariosGrupos/'.$k->id_usuario)}}"><span class="glyphicon glyphicon-refresh"></span> Asignar gestores</a>
                                                </td>
                                            </tr>
                                            @endif
                                            <?php $i++?>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </form>

                                <nav class="navbar navbar-default">
                                    <form role="search" action="reditUsuario" method="post">
                                        {{ csrf_field() }}
                                        <div class="well">
                                            <table class="table table-hover table-striped display" id="lista_ug" cellspacing="0" width="100%">
                                                <thead>
                                                <th>#</th>
                                                <th>Nombres gestor</th>
                                                <th>Correo</th>
                                                <th>Rol</th>
                                                <th>Acción</th>
                                                </thead>
                                                <tbody id="data2">
                                                <?php $i=1;?>
                                                @foreach($usuarios as $k)
                                                @if($k->rol->nombre=='Gestor' || $k->rol->nombre=='Gestor/Monitor')
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$k->nombres}}</td>
                                                        <td>{{$k->email}}</td>
                                                        <td>{{$k->rol->nombre}}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-src="" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1)"><span class="glyphicon glyphicon-edit"></span> Editar</button>
                                                            <div class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> Eliminar</div>
                                                        </td>
                                                    </tr>
                                                    <?php $i++?>
                                                @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                </nav>

                                <div class="panel-group" id="accordion">
                                    <nav class="navbar navbar-default">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-plus"></span><strong> INGRESAR NUEVO USUARIO</strong></a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                         <form role="search" action="rnuevoUsuario" method="post">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="menu" value="segundo_reporte">
                                                            <div class="panel-body">
                                                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                                                    <label class="col-lg-2">Nombres:</label>
                                                                    <div class='col-lg-3 input-group date'>
                                                                        <input type="text" class="span2 form-control" value="" id="nombres" name="nombres" required>
                                                                        <span class="input-group-addon"></span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                                                    <label class="col-lg-2">Cédula:</label>
                                                                    <div class='col-lg-3 input-group date'>
                                                                        <input type="text" class="span2 form-control" value="" id="cedula" name="cedula" required>
                                                                        <span class="input-group-addon"></span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                                                    <label class="col-lg-2">Correo:</label>
                                                                    <div class="col-lg-3 input-group date">
                                                                        <input type="text" class="span2 form-control" value="" id="email" name="email" required>
                                                                        <span class="input-group-addon"></span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                                                    <label class="col-lg-2">Contraseña:</label>
                                                                    <div class="col-lg-3 input-group date">
                                                                        <input type="password" class="span2 form-control" value="" id="pass" name="pass" required>
                                                                        <span class="input-group-addon"></span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                                                    <label class="col-lg-2">Rol:</label>
                                                                    <div class="col-lg-3 input-group date">
                                                                        {!! Form::select('rol',[''=>'--- Seleccione uno---']+$roles,null,['class'=>'form-control']) !!}
                                                                        <span class="input-group-addon"></span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                                                                    <div class="col-lg-12 col-md-12">
                                                                        <button type="submit" id="guardar"  class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade @if($menu=='tercer_reporte') active in @endif" id="tercer_reporte">
                            <div class="col-md-12 col-lg-12">
                                <form role="search" action="reditPregunta" method="post">
                                    {{ csrf_field() }}
                                    <div class="well">
                                        <table class="table table-hover table-striped display" id="lista_preguntas" cellspacing="0" width="100%">
                                            <thead>
                                            <th>#</th>
                                            <th>Categoria</th>
                                            <th>Pregunta</th>
                                            <th>Puntaje</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                            </thead>
                                            <tbody id="data2">
                                            <?php $i=1;?>
                                            @foreach($preguntas as $k)
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$k->categoria->nombre}}</td>
                                                    <td>{{$k->descripcion}}</td>
                                                    <td>{{$k->puntos}}</td>
                                                    <td>{{$k->estado_pregunta}}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-src="" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1)"><span class="glyphicon glyphicon-edit"></span> Editar</button>
                                                        <div class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> Eliminar</div>
                                                    </td>
                                                </tr>
                                                <?php $i++?>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </form>

                                <div class="panel-group" id="accordion">
                                    <nav class="navbar navbar-default">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsepOne"><span class="glyphicon glyphicon-plus"></span><strong> INGRESAR NUEVA PREGUNTA</strong></a>
                                                </h4>
                                            </div>
                                            <div id="collapsepOne" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <form role="search" action="rnuevaPregunta" method="post">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="menu" value="tercer_reporte">
                                                            <div class="panel-body">
                                                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                                                    <label class="col-lg-2">Pregunta:</label>
                                                                    <div class='col-lg-9 input-group date'>
                                                                        <input type="text" class="span2 form-control" value="" id="pregunta" name="pregunta" required>
                                                                        <span class="input-group-addon"></span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                                                    <label class="col-lg-2">Puntaje:</label>
                                                                    <div class='col-lg-2 input-group date'>
                                                                        <input type="text" class="span2 form-control" value="" id="puntaje" name="puntaje" required>
                                                                        <span class="input-group-addon"></span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                                                    <label class="col-lg-2">Categoría:</label>
                                                                    <div class="col-lg-3 input-group date">
                                                                        {!! Form::select('categoria',[''=>'--- Seleccione uno---']+$categorias,null,['class'=>'form-control']) !!}
                                                                        <span class="input-group-addon"></span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                                                                    <div class="col-lg-12 col-md-12">
                                                                        <button type="submit" id="guardar"  class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade @if($menu=='cuarto_reporte') active in @endif" id="cuarto_reporte">
                            <div class="col-md-12 col-lg-12">

                                 <div class="panel panel-primary">
                                    <?php $categoriacs=\App\reportesNuevoSistema\encuestasCex\tbl_categorias::where('estado_categoria',1)->get();?>
                                    @foreach($categoriacs as $k)
                                    <div class="panel-heading">{{$k->nombre}}</div>
                                    <nav class="navbar navbar-default">
                                        <div class="panel-body">
                                            <div class="form-horizontal">
                                                <?php $preguntacs=\App\reportesNuevoSistema\encuestasCex\tbl_preguntas::where('id_categoria',$k->id_categoria)->get();?>
                                                @foreach($preguntacs as $key)
                                                <div class="row col-md-12">
                                                    <div class="form-group col-md-6">
                                                        <label for="pregunta" class="col-lg-9 control-label" name="pregunta">{{$key->descripcion}}</label>
                                                        <div class="col-lg-3">
                                                            <div class="radio">
                                                                <label><input type="radio" name="{{$key->id_pregunta}}" required>Si</label>
                                                                <label></label>
                                                                <label><input type="radio" name="{{$key->id_pregunta}}" required>No</label>
                                                            </div>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="observaciones" class="col-lg-5 control-label">Observaciones</label>
                                                        <div class="col-lg-5">
                                                            <textarea class="form-control"  name="observaciones{{$k->id_pregunta}}"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </nav>
                                    @endforeach
                                    <div class="row">
                                        <div class="col-sm-offset-5 col-sm-2 text-center">
                                            <div class="btn-group" data-toggle="buttons">
                                                <button type="button" id="btn_enviar" class="btn btn-primary">Enviar resultado</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>

                        <div class="tab-pane fade @if($menu=='cuarto_reporte') active in @endif" id="quinto_reporte">
                            <div class="col-md-12 col-lg-12">



                                    <nav class="navbar navbar-default">
                                        <div class="panel-body">
                                            <div class="form-horizontal">
                                                <table class="table table-hover table-striped display" id="lista_usuarios_grupos2" cellspacing="0" width="100%">
                                                    <thead>
                                                    <th>#</th>
                                                    <th>Categoria</th>
                                                    <th>Pregunta</th>
                                                    <th>Puntaje</th>
                                                    <th>Estado</th>
                                                    <th>Acción</th>
                                                    </thead>
                                                    <tbody id="data2">
                                                    <?php $i=1;?>
                                                    @foreach($preguntas as $k)
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{$k->categoria->nombre}}</td>
                                                            <td>{{$k->descripcion}}</td>
                                                            <td>{{$k->puntos}}</td>
                                                            <td>{{$k->estado_pregunta}}</td>
                                                            <td>
                                                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-src="" data-target="#myModal" data-video-fullscreen="" onclick="alerta(1)"><span class="glyphicon glyphicon-edit"></span> Editar</button>
                                                                <div class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> Eliminar</div>
                                                            </td>
                                                        </tr>
                                                        <?php $i++?>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </nav>
                                    <div class="row">
                                        <div class="col-sm-offset-5 col-sm-2 text-center">
                                            <div class="btn-group" data-toggle="buttons">
                                                <button type="button" id="btn_enviar" class="btn btn-primary">Enviar resultado</button>
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