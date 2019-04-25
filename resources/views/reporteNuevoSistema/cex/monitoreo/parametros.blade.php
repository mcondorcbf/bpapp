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

@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel-heading"><h3>PARAMETROS MONITOREO CEX</h3></div>
            <div class="panel panel-default">
                <br>
                <form class="form-horizontal" id="Form" role="form" method="post" action="{{ url('/procesarParametrosCex') }}">
                    {{ csrf_field() }}
                    <div class="panel-body">

                        <div class="col-lg-12 col-md-6">
                            <label class="col-lg-12">Tiempo mínimo de parada:</label>
                            <div class="col-lg-3">
                                <input id="tiempo_parada" name="tiempo_parada" type="time" class="form-control" value="{{$parametros->tiempo_parada ? $parametros->tiempo_parada : ''}}" autocomplete>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6">
                            <label class="col-lg-12">Distancia de parada en metros</label>
                            <div class="col-lg-2">
                                <input id="distancia" name="distancia" type="number" class="form-control" value="{{$parametros->distancia ? $parametros->distancia : ''}}" autocomplete>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6">
                            <label class="col-lg-12">Hora Inicio</label>
                            <div class="col-lg-3">
                                <input id="hora_inicio" name="hora_inicio" type="time" class="form-control" value="{{$parametros->hora_inicio ? $parametros->hora_inicio : ''}}" autocomplete>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6">
                            <label class="col-lg-12">Hora Fin</label>
                            <div class="col-lg-3">
                                <input id="hora_fin" name="hora_fin" type="time" class="form-control" value="{{$parametros->hora_fin ? $parametros->hora_fin : ''}}" autocomplete>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6">
                            <label class="col-lg-12">Tiempo para marcar como fuera de línea:</label>
                            <div class="col-lg-3">
                                <input id="time_out" name="time_out" type="time" class="form-control" value="{{$parametros->time_out ? $parametros->time_out : ''}}" autocomplete>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                            <br>
                            <div class="col-lg-12 col-md-12">
                                <a href="{{url('monitoreoCexRes')}}" id="atras"  class="btn btn-primary "><span class="glyphicon glyphicon-backward"></span> Atras</a>
                                <button type="submit" id="siguiente"  class="btn btn-success" onclick="return confirmSubmit()">Guardar <span class="glyphicon glyphicon-save"></span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection