@extends('layouts.appReportesNuevoSistema')
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/assets/loading/jquery.loading-indicator.css">
    <script src="/assets/loading/jquery.loading-indicator.js"></script>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="js/upload/jquery.ui.widget.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="js/upload/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="js/upload/jquery.fileupload.js"></script>
@endsection
@section('content')
<div class="col-lg-12">
    <div class="panel with-nav-tabs panel-primary">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="nac-item"><a href="rDiners" >REPORTE SFTP</a></li>
                <li class="nav-item"><a href="rDiners" >REPORTE RECUPERACIÓN VS META</a></li>
                <li class="nav-item"><a href="rDiners" >REPORTE FOCALIZACIONES DE CARTERA</a></li>
                <li class="nav-item"><a href="rDiners" >INFOME DIARIO DE COBERTURA</a></li>
                <li class="nav-item"><a href="rDiners" >REPORTE DE MARCACIONES</a></li>
                <li class="active"><a href="#" data-toggle="tab">CUENTAS X88</a></li>
                <input type="hidden" id="reporte_nro" value="1">
            </ul>
        </div>

        <div class="panel-body">
            <div class="content">
                <div class="form-group">
                    <div class="tab-content tab-content-border">
                        <div class="tab-pane fade active in" id="r_cuentas_x88">
                            <div class="col-md-4 col-lg-4 col-md-offset-2 col-lg-offset-2">
                                <form action="{{ URL::to('importExcelCx88') }}" id="formulario" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="well col-lg-offset-3 col-md-offset-3" align="center">
                                        <div id="camp">
                                        <h4>Diners > Campo </h4>
                                        <select class="form-control" title="SELECCIONE UNO" name="id_campana" id="id_campana" required>
                                            <option value="">Seleccione Uno</option>
                                            @foreach($campanas as $campana)
                                                <option value="{{$campana->id}}">{{$campana->name}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <br>
                                    <div align="center" id="carga_archivo">
                                        <label><span class="glyphicon glyphicon-ok"></span> seleccione el archivo</label><br>
                                        <span class="btn btn-success fileinput-button">
    <!-- The file input field used as target for the file upload widget -->
    <input id="fileupload" type="file" name="import_file" accept=".xlsx">
    </span>
                                        <!-- The global progress bar -->
                                        <div id="progress" class="progress">
                                            <div class="progress-bar progress-bar-striped active" role="progressbar" id="progreso"></div>
                                        </div>
                                        <!-- The container for the uploaded files -->
                                    </div>
                                    <div id="files" class="files" style="display: none;">
                                    </div>
                                    <div id="loaderDiv" style="display: none" align="center"> <img src="images/ajax-loader.gif"/> </div>
                                    </div>
                                </form>

                            </div>
                            <div class="col-md-3 col-lg-3">
                                <form role="search" action="descargaCuentasX88" method="post">
                                    {{ csrf_field() }}
                                    <div class="well col-lg-offset-4 col-md-offset-4" align="center">
                                        <table class="table" align="center">
                                            <thead>
                                            <tr>
                                                <th><div align="center"><span class="glyphicon glyphicon-calendar"></span> Seleccione la fecha</div>
                                                    <div class="input-group date" id="datetimepicker1">
                                                        <input type="text" class="span2 form-control" value="{{date('d/m/Y')}}" id="dpd1" name="fecha_inicio" required="" readonly>
                                                    </div>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-download-alt"></span> Descargar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /*jslint unparam: true */
    /*global window, $ */
    var archivo='';
    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        var url = window.location.hostname === 'blueimp.github.io' ?
            '' : 'importExcelCx88';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#fileupload').fileupload(
            {
                autoUpload: true,
                add: function (e, data) {
                    //data.context = $('<p/>').text('Uploading...').appendTo(document.body);
                    data.submit();
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    //setInterval('',10000);
                    document.getElementById("progreso").innerHTML =(progress-1)+"%";
                    $('#progress .progress-bar').css(
                        'width',
                        progress-1 + '%'
                    );
                },
                done: function (e, data) {
                    $.each(data.files, function (index, file)
                    {
                        document.getElementById('fileupload').disabled = true;
                        document.getElementById("carga_archivo").style.display='none';
                        document.getElementById("files").style.display='block';
                        archivo=file.name;
                        document.getElementById("files").innerHTML ='<div class="alert alert-info" role="alert">ARCHIVO <strong>'+file.name+'</strong> LISTO PARA PROCESARLO. <br><button type="button" class="btn btn-primary" onclick="procesar()"><span class="glyphicon glyphicon-ok"></span> Procesar</button><input type="hidden" name="archivo" id="archivo" value="'+file.name+'"></div>';
                    });
                }
            });
    });

    function contador(progress){
        document.getElementById("progreso").innerHTML =progress.val+"%";
    }

    function procesar() {
        if(document.getElementById("id_campana").value==''){alert('Debe seleccionar una campaña'); return false; exit();}

        if(confirm("Seguro que desea procesar?")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var formData = new FormData(document.getElementById("formulario"));
            console.log(formData);
            var token = $("input[name='_token']").val();
            var arch=archivo;
            var campana=document.getElementById("id_campana").value;
            $.ajax({
                url: 'cargaDatosExcel',
                type: "post",
                data: {archivo:arch,campana:campana},
                beforeSend: function(){
                    document.getElementById("loaderDiv").style.display='block';
                    //$("#loaderDiv").show();
                },
                success: function (data) {
                    document.getElementById("camp").style.display='none';
                    document.getElementById("loaderDiv").style.display='none';
                    document.getElementById("files").innerHTML ='';
                    document.getElementById("files").innerHTML ='<div class="alert alert-success" role="alert">BASE ACTUALIZADA EXITOSAMENTE - ARCHIVO: '+archivo+'</div>';
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("Error: " + jqXHR.status);
                    console.log("Error: " + errorThrown);
                    //alert('A ocurrido un error, favor revisar que el archivo sea el correcto');
                    document.getElementById("loaderDiv").style.display='none';
                    document.getElementById("files").innerHTML ='';
                    if(jqXHR.status!=423) {
                        document.getElementById("files").innerHTML = '<div class="alert alert-info" role="alert"> Se encontró un error en el archivo cargado, favor revisar y subir nuevamente. <br><a href="cuentasX88S" class="btn btn-danger"><span class="glyphicon glyphicon-alert"></span> Click aqui para empezar nuevamente</a></div>';
                    }else {
                        document.getElementById("files").innerHTML = '<div class="alert alert-info" role="alert"> No puede subir nuevas cuentas.<br>Mensaje: Actualmente existen cuentas sin gestionar.<br><a href="cuentasX88S" class="btn btn-warning"><span class="glyphicon glyphicon-alert"></span> Click aqui para empezar nuevamente</a></div>';
                    }
                    return false;
                }
            });
        }else{
            return false;
        }
    }
</script>
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
    $(document).ready(function() {
        $('#diners').DataTable( {
            "scrollY": 500,
            "scrollX": true,
            "order": [[ 0, "desc" ]]
        } );
    } );
</script>
@endsection