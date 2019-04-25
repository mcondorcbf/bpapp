<?php use App\Role;?>
@extends('layouts.appAvon')
@section('scripts')
    <link rel="stylesheet" href="vendor/bootstrap-select/dist/css/bootstrap-select.css">
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.js"></script>
    <script type="text/javascript" src="/js/datatables.min.js"></script>

    <style>
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
@endsection
@section('content')
    <div id="resultados"></div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Reportes</div>
                <div class="panel-body">
                    <div class="panel-group">

                        <div class="panel panel-primary ">
                            <table class="table table-hover table-striped display" id="example" cellspacing="0" width="100%">
                                <tbody id="data">
                                <tr>
                                    <td colspan="7" align="center"><h4>REPORTES ELASTIX</h4></td>
                                </tr>
                                <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/generarReporteAvon') }}">
                                    {{ csrf_field() }}
                                <tr>
                                    <td><div class="radio">
                                            <label><input type="radio" name="idReporte" value="1" required>Consolidado </label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="idReporte" value="2" required>Ecuador</label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="idReporte" value="3" required>Perú</label>
                                        </div>
                                    </td>
                                    <td>Fecha Inicio:</td>
                                    <td><input class="form-control" name="finicio" id="finicio" type="date" required></td>
                                    <td>Fecha Fin:</td>
                                    <td><input class="form-control" name="ffin" id="ffin" type="date" required></td>
                                    <td><button type="submit">Enviar</button></td>
                                </tr>
                                </form>


                                <tr>
                                    <td colspan="7" align="center"><h4>REPORTES GENERAL DE CUENTAS</h4></td>
                                </tr>

                                <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/generarReporteAvon') }}">
                                    {{ csrf_field() }}
                                    <tr>
                                        <td><div class="radio">
                                                <label><input type="radio" name="idReporte" value="4" required>Consolidado </label>
                                            </div>
                                            </td>
                                        <td>Estado:</td>
                                        <td><select class="form-control" name="estado" id="estado" >
                                                <option value='2'>Todos</option>
                                                <option value='1'>Habilitados</option>
                                                <option value='0'>Deshabilitados</option>
                                            </select></td>
                                        <td>Campaña:</td>
                                        <td><select class="form-control" name="campanias[]" id="campanias" multiple>
                                                @foreach($campanias as $campania)
                                                <option value="{{$campania->id}}">{{$campania->nombre}}</option>
                                                @endforeach
                                            </select></td>
                                        <td><button type="submit">Enviar</button></td>
                                    </tr>
                                    <tr>
                                        <td><div class="radio">
                                                <label><input type="radio" name="idReporte" value="5" required>Ecuador</label>
                                            </div>
                                            </td>
                                        <td>Estado:</td>
                                        <td><select class="form-control" name="estado" id="estado" >
                                                <option value='2'>Todos</option>
                                                <option value='1'>Habilitados</option>
                                                <option value='0'>Deshabilitados</option>
                                            </select></td>
                                        <td>Campaña:</td>
                                        <td><select class="form-control" name="campanias[]" id="campanias" multiple>
                                                @foreach($campanias as $campania)
                                                    <option value="{{$campania->id}}">{{$campania->nombre}}</option>
                                                @endforeach
                                            </select></td>
                                        <td><button type="submit">Enviar</button></td>
                                    </tr>
                                    <tr>
                                        <td><div class="radio">
                                                <label><input type="radio" name="idReporte" value="6" required>Perú</label>
                                            </div></td>
                                        <td>Estado:</td>
                                        <td><select class="form-control" name="estado" id="estado" >
                                                <option value='2'>Todos</option>
                                                <option value='1'>Habilitados</option>
                                                <option value='0'>Deshabilitados</option>
                                            </select></td>
                                        <td>Campaña:</td>
                                        <td><select class="form-control" name="campanias[]" id="campanias" multiple>
                                                @foreach($campaniaspe as $campania)
                                                    <option value="{{$campania->id}}">{{$campania->nombre}}</option>
                                                @endforeach
                                            </select></td>
                                        <td><button type="submit">Enviar</button></td>
                                    </tr>
                                </form>


                                <tr>
                                    <td colspan="7" align="center"><h4>REPORTES HISTORIAL DE CUENTAS</h4></td>
                                </tr>

                                <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/generarReporteAvon') }}">
                                    {{ csrf_field() }}
                                    <tr>
                                        <td><div class="radio">
                                                <label><input type="radio" name="idReporte" value="7" required>Consolidado </label>
                                            </div>
                                        </td>
                                        <td>Estado:</td>
                                        <td><select class="form-control" name="estado" id="estado" >
                                                <option value='3'>Todos</option>
                                                <option value='1'>Habilitados</option>
                                                <option value='2'>Deshabilitados</option>
                                            </select></td>
                                        <td>Campaña:</td>
                                        <td><select class="form-control" name="campanias" id="campanias" multiple>
                                                @foreach($campanias as $campania)
                                                    <option value="{{$campania->id}}">{{$campania->nombre}}</option>
                                                @endforeach
                                            </select></td>
                                        <td><button type="submit">Enviar</button></td>
                                    </tr>
                                </form>

                                <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/generarReporteAvon') }}">
                                    {{ csrf_field() }}
                                    <tr>
                                        <td><div class="radio">
                                                <label><input type="radio" name="idReporte" value="8" required>Ecuador</label>
                                            </div></td>
                                        <td>Estado:</td>
                                        <td><select class="form-control" name="estado" id="estado" >
                                                <option value='3'>Todos</option>
                                                <option value='1'>Habilitados</option>
                                                <option value='2'>Deshabilitados</option>
                                            </select></td>
                                        <td>Campaña:</td>
                                        <td><select class="form-control" name="campanias" id="campanias" multiple>
                                                @foreach($campanias as $campania)
                                                    <option value="{{$campania->id}}">{{$campania->nombre}}</option>
                                                @endforeach
                                            </select></td>
                                        <td><button type="submit">Enviar</button></td>
                                    </tr>
                                </form>

                                <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/generarReporteAvon') }}">
                                    {{ csrf_field() }}
                                    <tr>
                                        <td><div class="radio">
                                                <label><input type="radio" name="idReporte" value="9" required>Perú</label>
                                            </div></td>
                                        <td>Estado:</td>
                                        <td><select class="form-control" name="estado" id="estado" >
                                                <option value='3'>Todos</option>
                                                <option value='1'>Habilitados</option>
                                                <option value='2'>Deshabilitados</option>
                                            </select></td>
                                        <td>Campaña:</td>
                                        <td><select class="form-control" name="campanias" id="campanias" multiple>
                                                @foreach($campanias as $campania)
                                                    <option value="{{$campania->id}}">{{$campania->nombre}}</option>
                                                @endforeach
                                            </select></td>
                                        <td><button type="submit">Enviar</button></td>
                                    </tr>
                                </form>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
function reporte(id_carga) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        data: "id_carga="+id_carga ,
        url:   "{{url('/reporteIvr')}}"+id_carga,
        type:  'get',
        beforeSend: function () {
            $("#resultado").html("Procesando, espere por favor...");
        },
        success:  function (response) {
            document.location=response;
        }
    });
}
function realizaProceso(valorCaja1, valorCaja2){
    var parametros = {
        "valorCaja1" : valorCaja1,
        "valorCaja2" : valorCaja2
    };
    $.ajax({
        data:  parametros,
        url:   'ejemplo_ajax_proceso.php',
        type:  'post',
        beforeSend: function () {
            $("#resultado").html("Procesando, espere por favor...");
        },
        success:  function (response) {
            $("#resultado").html(response);
        }
    });
}
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.button').click(function(){
            //Añadimos la imagen de carga en el contenedor
            $('#content').html('<div><img src="images/loading.gif"/></div>');
            var page = $(this).attr('data');
            var dataString = 'page='+page;
            $.ajax({
                type: "GET",
                url: "{{url('/nuevoIvr')}}",
                data: dataString,
                success: function(data) {
                    //Cargamos finalmente el contenido deseado
                    $('#content').fadeIn(1000).html(data);
                }
            });
        });
    });
</script>
@endsection