@extends('layouts.appCentrales')
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js"></script>


    <script>
        $( document ).ready(function() {
            $('#fecha').datepicker();

            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

            var checkin = $('#dpd1').datepicker({
                onRender: function(date) {
                    return date.valueOf() < now.valueOf() ? 'enabled' : '';
                }
            }).on('changeDate', function(ev) {
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

                    return date.valueOf() <= checkin.date.valueOf() ? 'enabled' : '';
                }
            }).on('changeDate', function(ev) {
                checkout.hide();
            }).data('datepicker');
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#Form").on("submit", function(e){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                $("#cargando").css("display", "inline");
                var form = $(this);
                var url = '{{URL::to('procesarCentral')}}';
                var formData = new FormData(document.getElementById("Form"));
                $.ajax({
                    url: url,
                    type: "post",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    datatype: 'html',
                    beforeSend: function(){
                        $('#loader-icon').show();
                        $("#procesado").html('');
                    },
                    complete: function(){
                        $('#loader-icon').hide();
                    },
                    success: function(data){
                        $("#procesado").css("display", "block");
                        $("#procesado").html(data);
                    },
                    error:function(){
                        console.log('Hubo un error');
                    }
                });
            });
        });
    </script>
@endsection
@section('content')
@include('busqueda.scripts')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Centrales Telefónicas</div>

                <div class="panel-body">
                    <div class="content">

                        <div class="links" id="centrales">
                            <nav class="navbar navbar-default">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="#">
                                            INGRESE UN RAGO DE FECHA
                                        </a>
                                    </div>
                                </div>
                            </nav>
                            <form role="search" id="Form">
                                <div class="well">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Fecha inicio:
                                                <div class='input-group date' id='datetimepicker1'>
                                                    <input type="text" class="span2 form-control" value="" id="dpd1" name="fecha_inicio" required>
                                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                </div>
                                            </th>
                                            <th>Fecha fin:
                                                <div class='input-group date' id='datetimepicker1'>
                                                    <input type="text" class="span2 form-control" value="" id="dpd2" name="fecha_fin" required>
                                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                </div>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                    <div>
                                        <select class="form-control selectpicker" multiple title="SELECCIONE UNO" name="operadores[]" required>
                                            <option value="0">CLARO</option>
                                            <option value="1">MOVISTAR</option>
                                            <option value="2">CNT</option>
                                            <option value="3">SETEL</option>
                                            <option value="4">MOVISTAR PERU</option>
                                        </select>
                                        <br>
                                    </div>
                                    <button type="submit" class="btn btn-default">Procesar</button>
                                </div>
                            </form>

                            <div id="loader-icon" style="display:none; color: green;" align="center">
                                <img src="{{asset('images/loading.gif')}}"><br>PROCESANDO . . .
                            </div>
                            <div id="procesado" style="display:none;">
                                <table class="table">
                                    <tr>
                                        <th>FECHA</th>
                                        <th>OPERADORA</th>
                                        <th>MINUTOS</th>
                                    </tr>
                                    @foreach($centrales as $k)
                                        <tr>
                                            <td>{{$k['OPERADORA']}}</td>
                                            <td>{{$k['OPERADORA']}}</td>
                                            <td>{{$k['MINUTOS']}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection