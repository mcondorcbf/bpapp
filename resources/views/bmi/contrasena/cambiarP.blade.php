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
            $('#fecha_visita').datetimepicker();
            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
            var checkin = $('#fecha_visita').datetimepicker({
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
            <div class="col-lg-6 col-lg-offset-3 panel">

                <div class="col-lg-12" align="center">
                    <label class="col-lg-12" style="color: #9c1014">Ingresar una nueva contraseña</label>
                </div>

                <form class="form-horizontal" role="form" method="post" action="{{ url('/cambiarPs') }}">
                    {{ csrf_field() }}
                    <div class="col-lg-12">
                        <label class="col-lg-6">Contraseña: </label>
                        <div class="col-lg-6">
                            <input class="form-control" type="password" name="contrasena_n" required>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <label class="col-lg-6">Repita la contraseña: </label>
                        <div class="col-lg-6">
                            <input class="form-control" type="password" name="contrasena_n2" required>
                        </div>
                    </div>

                    <div class="col-lg-12"> </div>

                    <div class="col-lg-6 col-lg-offset-6">
                        <button type="submit" class="btn btn-success btn-md"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
                    </div>

                    <div class="col-lg-12 alert {{$estilo}}">
                        <ul>
                            <?php echo $mensaje;?>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection