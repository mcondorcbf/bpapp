<?php use App\Role;?>
@extends('layouts.appIvr')
@section('scripts')
    <link rel="stylesheet" href="vendor/bootstrap-select/dist/css/bootstrap-select.css">
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.js"></script>
    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script>
        //setInterval("comandos()", 3000);
        setInterval("refresh()", 30000);
        setInterval("refreshProcesados()", 30000);

        function comandos(){
            $("#data").empty();
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{url('/ivr/comandos')}}",
            })
                .done(function( data, textStatus, jqXHR ) {
                    if ( console && console.log ) {
                        console.log( "La solicitud comandos." );
                        $.each(data['ivrprocesos'], function (key, item) {
                            console.log(item);

                        })
                    }
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "La solicitud a fallado: " +  textStatus);
                    }
                })
        }


        function refresh(){
            $("#data").empty();
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{url('/getIvrs')}}",
            })
                .done(function( data, textStatus, jqXHR ) {
                    if ( console && console.log ) {
                        console.log( "La solicitud se ha completado correctamente." );
                        var play='';
                        var pause='';
                        var mensaje='';
                        var imagen='';
                        var urlplay='#';
                        var urlpause='#';
                        var urlAprobar='#';
                        var urlDesAprobar='#';
                        var boton='';
                        $.each(data['ivrprocesos'], function (key, item) {
                            console.log(item);
                            var ejecucion=item['ejecucion'];
                            var estado_aprobado=item['estado_aprobado'];

                            if(parseInt(estado_aprobado)==1){
                                if(parseInt(ejecucion)==0){
                                    play='';
                                    pause='disabled';
                                    mensaje='Pausado';
                                    boton='warning';
                                    imagen='';
                                    urlplay="{{url('play')}}/"+item['id_carga'];
                                }
                                if(parseInt(ejecucion)==1){
                                    play='disabled';
                                    pause='';
                                    mensaje='Procesando';
                                    boton='primary';
                                    imagen="<img src='{{asset('images/ajax-loader-mini.gif')}}'>";
                                    urlpause="{{url('pause')}}/"+item['id_carga'];
                                }

                                @if(\Voyager::can('accede_ivrs_administrador'))
                                    $("#data").append("<tr><td>" + item['id_carga'] + "</td><td>" + item['cliente'] + "</td><td>" + item['id_campania'] + "</td><td>" + item['fecha'] + "</td><td>" + item['procesados'] + "/" + item['total'] + "</td><td>" + item['porcentaje'] + "% "+imagen+" </td>  <td>"+item['fecha_inicio_envio']+"</td>  <td>"+item['canales']+"</td><td>"+mensaje+"<br><a class='btn btn-"+boton+" btn-xs' href='"+urlplay+"' "+play+"><span class='glyphicon glyphicon-play'></span></a> <a class='btn btn-"+boton+" btn-xs' href='"+urlpause+"' "+pause+"><span class='glyphicon glyphicon-pause'></span></a></td></tr>");
                                @elseif(\Voyager::can('accede_ivrs_supervisor'))
                                    $("#data").append("<tr><td>" + item['id_carga'] + "</td><td>" + item['cliente'] + "</td><td>" + item['id_campania'] + "</td><td>" + item['fecha'] + "</td><td>"+item["fecha_inicio_envio"]+"</td><td>" + item['procesados'] + "/" + item['total'] + "</td><td>" + item['porcentaje'] + "% "+imagen+" </td></tr>");
                                @endif
                            }else{
                                @if(\Voyager::can('accede_ivrs_administrador'))
                                    urlAprobar="{{url('aprobarIvr')}}";
                                    urlDesAprobar="{{url('denegarIvr')}}";
                                    $('#data').append('<tr><td>' + item["id_carga"] + '</td><td>' + item["cliente"] + '</td><td>' + item["id_campania"] + '</td><td>' + item["fecha"] + '</td><td>' + item["procesados"] + '/' + item["total"] + '</td><td>' + item["porcentaje"] + '% '+imagen+' </td>  <td>'+item["fecha_inicio_envio"]+'</td>  <td>'+estado_aprobado+'</td><td><form class="form-horizontal" action="'+urlAprobar+'" method="post">{{ csrf_field() }} <input type="hidden" name="id_carga" value="'+item['id_carga']+'"><button class="btn btn-primary btn-xs" type="submit" onclick="return aprobarSubmit()"><span class="glyphicon glyphicon-check"></span> Aprobar</button></form>  <form class="form-horizontal" action="'+urlDesAprobar+'" method="post">{{ csrf_field() }} <input type="hidden" name="id_carga" value="'+item['id_carga']+'"><input type="hidden" name="motivo_'+item['id_carga']+'" id="motivo_'+item['id_carga']+'" value=""><button class="btn btn-danger btn-xs" type="submit" onclick="return negarSubmit('+item['id_carga']+')"><span class="glyphicon glyphicon-ban-circle"></span> Denegar</button></form></td></tr>');
                                @elseif(\Voyager::can('accede_ivrs_supervisor'))
                                    play='disabled';
                                    pause='disabled';
                                    mensaje='<strong style="color: #c70005">Esperando aprobación</strong>';
                                    boton='primary';
                                    imagen="";
                                    urlpause="";
                                $("#data").append("<tr><td>" + item['id_carga'] + "</td><td>" + item['cliente'] + "</td><td>" + item['id_campania'] + "</td><td>" + item['fecha'] + "</td><td>" + item['fecha_inicio_envio'] + "</td><td>" + item['procesados'] + "/" + item['total'] + "</td><td>" + mensaje +" </td></tr>");
                                @endif
                            }
                        })
                    }
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "La solicitud a fallado: " +  textStatus);
                    }
                })
        }
        function refreshProcesados(){
            $("#data2").empty();
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{url('/getIvrs')}}",
            })
                .done(function( data, textStatus, jqXHR ) {
                    if ( console && console.log ) {
                        console.log( "La solicitud se ha completado correctamente." );
                        $.each(data['ivrfinalizados'], function (key, item) {
                            @if(\Voyager::can('accede_ivrs_administrador'))
                                if(item['estado']!='<strong style="color:#b0280d" >Denegado</strong>'){
                                    $("#data2").append("<tr><td>" + item['id_carga'] + "</td><td>" + item['cliente'] + "</td><td>" + item['id_campania'] + "</td><td>" + item['fecha'] + "</td><td>" + item['calendarizado'] + " " + item['fecha_inicio_envio'] + " </td><td>" + item['procesados'] + " / "+ item['indexados'] + "</td><td>" + item['totalllamados'] + "</td><td>" + item['contactabilidad'] + "% </td><td>"+item['motivo']+"</td><td>" + item['estado'] + "</td><td><a href='/reporteIvr"+item['id_carga']+"?id_carga="+item['id_carga']+"&nm="+item['cliente']+"-"+item['id_campania']+"' class='btn-xs btn-success' role='button'><span class='glyphicon glyphicon-download-alt'></span></a></td></tr>");
                                }else{
                                    $("#data2").append("<tr><td>" + item['id_carga'] + "</td><td>" + item['cliente'] + "</td><td>" + item['id_campania'] + "</td><td>" + item['fecha'] + "</td><td>" + item['calendarizado'] + " " + item['fecha_inicio_envio'] + " </td><td>" + item['procesados'] + "</td><td>" + item['totalllamados'] + "</td><td>" + item['contactabilidad'] + "% / " + " / " +item['indexados'] + "</td><td>"+item['motivo']+"</td><td>" + item['estado'] + "</td><td><span class='btn-xs btn-danger' role='button'><span class='glyphicon glyphicon-remove'></span></span></td></tr>");
                                }
                            @else
                                if(item['estado']!='<strong style="color:#b0280d" >Denegado</strong>'){
                                    $("#data2").append("<tr><td>" + item['id_carga'] + "</td><td>" + item['cliente'] + "</td><td>" + item['id_campania'] + "</td><td>" + item['fecha'] + "</td><td>"+item["fecha_inicio_envio"]+"</td><td>" + item['procesados'] + " / " +item['indexados'] + " </td><td>"+item['motivo']+"</td><td>" + item['estado'] + "</td><td><a href='/reporteIvr"+item['id_carga']+"?id_carga="+item['id_carga']+"&nm="+item['cliente']+"-"+item['id_campania']+"' class='btn-xs btn-success' role='button'><span class='glyphicon glyphicon-download-alt'></span></a></td></tr>");
                                }else{
                                    $("#data2").append("<tr><td>" + item['id_carga'] + "</td><td>" + item['cliente'] + "</td><td>" + item['id_campania'] + "</td><td>" + item['fecha'] + "</td><td>"+item["fecha_inicio_envio"]+"</td><td>" + item['procesados'] + "</td><td>"+item['motivo']+"</td><td>" + item['estado'] + "</td><td><span class='btn-xs btn-danger' role='button'><span class='glyphicon glyphicon-remove'></span></span></td></tr>");
                                }
                            @endif
                        })
                    }
                })
                .fail(function( jqXHR, textStatus, errorThrown ) {
                    if ( console && console.log ) {
                        console.log( "La solicitud a fallado: " +  textStatus);
                    }
                })
        }
        $(document).ready(function() {
            refresh();
            refreshProcesados();
        } );

        function aprobarSubmit()
        {
            var agree=confirm("Está seguro que desea aprobar este envío?");
            if (agree){
                return true
            }else{
                return false ;
            }
        }
        function negarSubmit(id)
        {
            var agree=confirm("Está seguro que desea denegar este envío?");
            if (agree){
                var motivo= prompt("Favor, ingrese el motivo", "");
                if (motivo!= null || motivo!= ''){
                    document.getElementById('motivo_'+id).value=motivo;
                    return true
                }else {
                    alert("Debe ingresar un motivo!");
                    return false;
                }
            }else{
                return false ;
            }
        }
    </script>
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
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <div class="panel-group">
                       {{-- @if(Role::where('id',Auth::user()->role_id)->first()->name=='ivradministrador')
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>IVR's en proceso</strong></div>
                            <table class="table table-hover table-striped display" id="example" cellspacing="0" width="100%">
                                <thead>
                                <th>Peer</th>
                                <th>Teléfono</th>
                                <th>Call ID</th>
                                <th>Format</th>
                                <th>Hold</th>
                                <th>Las Message</th>
                                <th>Peer</th>
                                </thead>
                                <tbody id="comandos">

                                </tbody>
                            </table>
                        </div>
                        @endif--}}

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>IVR's en proceso</strong></div>
                            <table class="table table-hover table-striped display" id="example" cellspacing="0" width="100%">
                                <thead>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Campaña</th>
                                <th>Fecha carga</th>

                                <th>Calendarizado</th>
                                <th>Total</th>
                                <th>Avance</th>
                                @if(\Voyager::can('accede_ivrs_administrador'))
                                <th>Canales</th>
                                <th>Status</th>
                                @endif
                                </thead>
                                <tbody id="data">

                                </tbody>
                            </table>
                        </div>

                        <div class="panel panel-success">
                            <div class="panel-heading"><strong>IVR's finalizados</strong></div>
                            <table class="table table-hover table-striped">
                                <thead>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Campaña</th>
                                <th>Fecha carga</th>
                                <th>Calendarizado</th>
                                <th>Total / Indexado</th>
                                @if(\Voyager::can('accede_ivrs_administrador'))
                                <th>Contestados</th>
                                <th>Contactabilidad</th>
                                @endif
                                <th>Mensaje</th>
                                <th>Estado</th>
                                <th>Reporte</th>
                                </thead>
                                <tbody id="data2">

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