@extends('layouts.appsupervisor')
@section('scripts')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="js/upload/jquery.ui.widget.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="js/upload/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="js/upload/jquery.fileupload.js"></script>
@endsection
@section('content')

<div class="container col-xs-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="panel panel-default">
            <div class="panel-heading"><a href="{{url('/home')}}"><strong><< Nueva Búsqueda</strong></a></div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="">

                        <div class="">
                            <div class="tab-pane fade active in" id="deudor">
                                <div class="col-md-12 col-xs-12 col-lg-12" style="padding:0">
                                    <div class="container">
                                        {{--<a href="{{ URL::to('downloadExcel/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>
                                        <a href="{{ URL::to('downloadExcel/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>
                                        <a href="{{ URL::to('downloadExcel/csv') }}"><button class="btn btn-success">Download CSV</button></a>
                                        <a href="{{ URL::to('leerConvertirExcel') }}"><button class="btn btn-warning">Leer convertir excel</button></a>--}}

                                        <form action="{{ URL::to('importExcel') }}" id="formulario" class="" method="post" enctype="multipart/form-data">
                                            <div class="form-group" id="tipos">
                                                <label for="exampleSelect1">Tipo</label>
                                                <select class="form-control" id="tipo" name="tipo">
                                                    <option>SELECCIONE UNO</option>
                                                    <option value="telefonia">Telefonía</option>
                                                    <option value="campo">Campo</option>
                                                </select>
                                            </div>

                                            <div id="carga_archivo" class="form-group">
                                                <label>Subir archivo</label><br>
                                                <span class="btn btn-success fileinput-button">
                                                <!-- The file input field used as target for the file upload widget -->
                                                <input id="fileupload" type="file" name="import_file" accept=".csv">
                                                </span>
                                                <br>
                                                <br>
                                                <!-- The global progress bar -->
                                                <div id="progress" class="progress">
                                                    <div class="progress-bar progress-bar-striped active" role="progressbar" id="progreso"></div>
                                                </div>
                                                <!-- The container for the uploaded files -->
                                            </div>

                                            <div id="files" class="files" style="display: none;">
                                            </div>
                                            <div id="loaderDiv" style="display: none"> <img src="images/ajax-loader.gif"/> </div>
                                        </form>
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
                                        '' : 'importExcel';
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
                                    if (document.getElementById("tipo").value=='SELECCIONE UNO'){
                                        alert("Seleccione telefonía o campo");
                                        document.getElementById("tipo").focus();
                                        return false;
                                    }

                                    if(confirm("Seguro que desea actualizar la base actual")) {
                                        $.ajaxSetup({
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });
                                        var formData = new FormData(document.getElementById("formulario"));
                                        console.log(formData);
                                        $.ajax({
                                            url: 'procesarExcel',
                                            type: "post",
                                            dataType: "html",
                                            data: formData,
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            beforeSend: function(){
                                                document.getElementById("loaderDiv").style.display='block';
                                                document.getElementById("tipos").style.display='none';
                                                //$("#loaderDiv").show();
                                            },
                                            success: function (data) {
                                                document.getElementById("loaderDiv").style.display='none';
                                                document.getElementById("files").innerHTML ='';
                                                document.getElementById("files").innerHTML ='<div class="alert alert-success" role="alert">BASE ACTUALIZADA EXITOSAMENTE - ARCHIVO: '+archivo+'</div>';
                                            },
                                            error: function (jqXHR, textStatus, errorThrown) {
                                                console.log("Error: " + errorThrown);
                                                //alert('A ocurrido un error, favor revisar que el archivo sea el correcto');
                                                document.getElementById("loaderDiv").style.display='none';
                                                document.getElementById("files").innerHTML ='';
                                                document.getElementById("files").innerHTML ='<div class="alert alert-info" role="alert"> Se encontró un error en el archivo cargado, favor revisar y subir nuevamente. <a href="cargaDatos" class="btn btn-danger"><span class="glyphicon glyphicon-alert"></span> Click aqui para empezar nuevamente</a></div>';
                                                return false;
                                            }
                                        });
                                    }else{
                                        return false;
                                    }
                                }
                            </script>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection