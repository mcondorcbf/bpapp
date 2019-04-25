@extends('layouts.appBmi')
@section('scripts')
    <script src="/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js"></script>

    <script>
        $( document ).ready(function() {
            $('#fecha_cita').datetimepicker({
                format: 'YYYY-MM-DD HH:mm'
            });
            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
            var checkin = $('#fecha_cita').datetimepicker({
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
                $('#hora_visita')[-10].focus();
            }).data('datetimepicker');

            var checkout = $('#hora_visita').datetimepicker({
                onRender: function(date) {
                    return date.valueOf() <= checkin.date.valueOf() ? 'enabled' : '';
                }
            }).on('changeDate', function(ev) {
                checkout.hide();
            }).data('datetimepicker');
        });
    </script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel-heading"><h3>Agendar citas propias</h3></div>
                <div class="panel panel-default">
                    @if(session()->has('flash'))
                    <div class="alert alert-success">
                        {{session('flash')}}
                    </div>
                    @endif
                    <br>
                    {!! Form::open(array('url'=>'/editarCitasPropias/','method'=>'POST'))!!}
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-4">Fecha y hora de la cita: *</label>
                            <div class='col-lg-4 input-group date' id='datetimepicker1'>
                                <input type="text" class="span2 form-control" value="{{$cliente_propio->fecha_cita}} {{$cliente_propio->hora_cita}}" id="fecha_cita" name="fecha_cita" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Nombres: *</label>
                            <div class="col-lg-12">
                                <input name="id_cita" type="hidden" value="{{$cliente_propio->id_cita}}">
                                <input name="nombres" type="text" class="form-control" value="{{$cliente_propio->nombres}}" required>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Teléfono: *</label>
                            <div class="col-lg-12">
                                <input name="telefono" type="text" class="form-control" value="{{$cliente_propio->telefono}}" required>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Dirección: *</label>
                            <div class="col-lg-12">
                                <input name="direccion_cita" type="text" class="form-control" value="{{$cliente_propio->direccion_cita}}" required>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12" >
                            <label class="col-lg-12">Observaciones: </label>
                            <textarea name="observacion" type="text" class="form-control" required>{{$cliente_propio->observacion}}</textarea>
                        </div>

                        <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                            <br>
                            <div class="col-lg-12 col-md-12">
                                <a href="{{url('home')}}" id="atras"  class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Atras </a>
                                <button type="submit" id="guardar"  class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar </button>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("#accion_id").change(function () {

                $('#tipo_id').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');

                $("#accion_id option:selected").each(function () {
                    id_estado = $(this).val();
                    $.post("/", { id_estado: id_estado }, function(data){
                        $("#tipo_id").html(data);
                    });
                });
            })
        });
    </script>

    <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

    <script type="text/javascript">

    </script>


@endsection