<?php
use App\reportesNuevoSistema\cuentasX88\tbl_observaciones as observaciones;
?>@extends('layouts.appsupervisor')
@section('scripts')

    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="js/upload/jquery.ui.widget.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="js/upload/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="js/upload/jquery.fileupload.js"></script>
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
@endsection
@section('content')
<style type="text/css">
    a {
        color: #FFF;
        text-decoration: none;
    }
</style>
<script>
    $(document).ready(function() {
        $('#diners').DataTable( {
            "scrollX": true
        } );

        $('#visa').DataTable( {
            "scrollX": true
        } );
        $('#discover').DataTable( {
            "scrollX": true
        } );
    } );
</script>
<div class="content">
    <h2 align="center">CARGAR CUENTAS X88</h2>
    <div class="panel with-nav-tabs panel-primary" >

        <div class="panel-body">
            <div class="col-md-4 col-lg-5 col-md-offset-2 col-lg-offset-3">
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
                        <div id="resultado" style="display:none; color: green;"></div>
                        <div id="loaderDiv" style="display: none" align="center"> <img src="images/ajax-loader.gif"/> </div>
                    </div>
                </form>

            </div>
           {{-- <div class="col-md-3 col-lg-3">
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
            </div>--}}
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
                        document.getElementById("files").innerHTML = '<div class="alert alert-info" role="alert" id="repetidos"> Se encontró un error en el archivo cargado, favor revisar y subir nuevamente. <br><a href="cuentasX88Carga" class="btn btn-danger"><span class="glyphicon glyphicon-alert"></span> Click aqui para empezar nuevamente</a></div>';
                            $('#repetidos').append('<div style="color: #D9534F">'+jqXHR.responseText + '</div>');
                    }else {
                        document.getElementById("files").innerHTML = '<div class="alert alert-info" role="alert"> No puede subir nuevas cuentas.<br>Mensaje: Actualmente existen cuentas sin gestionar.<br><a href="cuentasX88Carga" class="btn btn-warning"><span class="glyphicon glyphicon-alert"></span> Click aqui para empezar nuevamente</a></div>';
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