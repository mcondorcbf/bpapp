@extends('layouts.appPredictivos')
@section('scripts')

@endsection
@section('content')
<div class="container col-xs-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">PREDICTIVO DINERS</div>

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
                            {!! Form::open(['action' => 'Predictivos\Predictivo2Controller@procesarPredictivo','method' => 'POST', 'id' => 'Form', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) !!}
                                <div class="well">
                                    <table class="table">
                                        <thead>

                                        <tr>Escoja la campaña:
                                            <div class='input-group date' id='datetimepicker1'>
                                                <select class="form-control" name="campania" id="campania">
                                                    <option selected>SELECCIONE UNO</option>
                                                    @foreach($campanias as $k)
                                                        <option value="{{$k->id}}">{{$k->name}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </tr>
                                        <tr>Ingrese el intervalo de agendamiento automático:
                                            <div class='input-group date' id='datetimepicker1'>
                                            <select class="form-control" name="agendamiento" id="agendamiento">
                                                <option selected>SELECCIONE UNO</option>
                                                <option value="minutos">Minutos</option>
                                                <option value="horas">Horas</option>
                                            </select>
                                            </div>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type="number" min="1" class="form-control" value="" id="tiempo" name="tiempo" required>
                                            </div>
                                        </tr>
                                        <tr>Ingrese el tiempo de insistencia solo en minutos:
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type="number" min="1" max="5" class="form-control" value="" id="tiempo_insistencia" name="tiempo_insistencia" required>
                                            </div>
                                        </tr>
                                        </thead>
                                    </table>
                                    <button type="submit" class="btn btn-default">Procesar</button>
                                </div>
                            {!! Form::close() !!}


                            <div id="loader-icon" style="display:none; color: green;" align="center">
                                <img src="{{asset('images/loading.gif')}}"><br>PROCESANDO . . .
                            </div>
                            <div id="procesado" style="display:none;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection