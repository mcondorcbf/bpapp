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
            $('#fecha_visita').datetimepicker({
                format: 'YYYY-MM-DD HH:mm'
            });
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
        $( document ).ready(function() {
            $('#fecha_pvisita').datetimepicker({
                format: 'YYYY-MM-DD HH:mm'
            });
            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
            var checkin = $('#fecha_pvisita').datetimepicker({
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

        function productoOb(){

            if(document.getElementById("tipo").value==''){
                alert('DEBE SELECCIONAR UN TIPO');
                document.getElementById("tipo").style.backgroundColor="#f2dede";
                return false;
            }
            if(document.getElementById("accion").value=='--- Seleccione uno ---'){
                alert('DEBE SELECCIONAR UNA ACCION');
                document.getElementById("accion").style.backgroundColor="#f2dede";
                return false;
            }
            if(document.getElementById("producto").value=='' && document.getElementById("accion").value==2){
                alert('DEBE SELECCIONAR UN PRODUCTO');
                return false;
            }
            if(document.getElementById("producto").value=='' && document.getElementById("accion").value==1){
                alert('DEBE SELECCIONAR UN PRODUCTO');
                return false;
            }
        }

    </script>

    <link rel="stylesheet" href="/assets/loading/jquery.loading-indicator.css">
    <script src="/assets/loading/jquery.loading-indicator.js"></script>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            @if(is_null($cita))
                <div class="col-md-6 col-md-offset-4">
                    <div class="panel-heading"><h3>La gestión ya fue realizada.</h3></div>
                </div>
            @else
            <div class="col-md-8 col-md-offset-2">
                <div class="panel-heading"><h3>Formulario de gestión.</h3></div>
                <div class="panel panel-default">
                    <br>
                    {!! Form::open(array('url'=>'/'.$url.'/'.$id_cita,'method'=>'POST'))!!}
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-4">Datos del cliente</label>
                            <input type="hidden" value="{{$cita->usuario_gestion}}" id="usuario_gestion" name="usuario_gestion" required>
                            <input type="hidden" value="{{$cita->fecha_cita}} {{$cita->hora_cita}}" id="fecha_cita_programada" name="fecha_cita_programada" required>
                            <input type="hidden" value="{{$cita->pais}}" id="pais" name="pais" required>
                            <input type="hidden" value="{{$cita->id_cita_original}}" id="id_cita_original" name="id_cita_original" required>
                            <input name="id_cita" type="hidden" class="form-control" value="{{$id_cita}}">
                            <input name="id_gestion_cobefec" type="hidden" class="form-control" value="{{$cita->id_gestion_cobefec}}">
                        </div>
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-4">Nombres: </label>
                            <div class='col-lg-8 input-group date' id=''>
                                {{$cita->nombres}}
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-4">Empresa: </label>
                            <div class='col-lg-8 input-group date' id=''>
                                {{isset($cita->clientes->empresa->nombre)}}
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-4">Dirección visita: </label>
                            <div class='col-lg-8 input-group date' id=''>
                                {{$cita->direccion_cita}}
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-4">Fecha y hora agendada: </label>
                            <div class='col-lg-8 input-group date' id=''>
                                {{$cita->fecha_cita}}
                            </div>
                        </div>
                        <hr>

                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-4">Fecha y hora de visita: *</label>
                            <div class='col-lg-4 input-group date' id='datetimepicker1'>
                                <input type="text" class="span2 form-control" value="{{date('Y-m-d H:i')}}" id="fecha_visita" name="fecha_visita" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Tipo: *</label>
                            <div class="col-lg-12">
                                {!! Form::select('tipo',[''=>'--- Seleccione uno ---']+$tipo,null,['id' => 'tipo', 'class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Acción: *</label>
                            <div class="col-lg-12">
                                {!! Form::select('accion',[''=>'--- Seleccione uno---'],null,['id' => 'accion', 'class'=>'form-control']) !!}
                            </div>
                        </div>

                        <div id="subacciones" style="display: none;">
                            <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                                <label class="col-lg-12">Motivo: *</label>
                                <div class="col-lg-12">
                                    <div class="radio">
                                        <label><input type="radio" name="subaccion" id="subaccion" required value="Presupuesto" disabled>Presupuesto</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="subaccion" id="subaccion2" required value="Ya tiene seguro" disabled>Ya tiene seguro</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="subaccion" id="subaccion3" required value="Otros" disabled>Otros</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script type="text/javascript">
                            $("select[name='tipo']").change(function(){
                                var homeLoader = $('body').loadingIndicator({
                                    useImage: false,
                                }).data("loadingIndicator");
                                homeLoader.show();
                                var id_tipo = $(this).val();
                                var token = $("input[name='_token']").val();
                                $.ajax({
                                    url: "/select-ajax",
                                    method: 'POST',
                                    data: {id_tipo:id_tipo, _token:token},
                                    success: function(data) {
                                        $("#element").hide();
                                        $("#fecha_visit").hide();
                                        $("select[name='accion']").html('');
                                        $("select[name='accion']").html(data.options);
                                        homeLoader.hide();
                                        document.getElementById("tipo").style.backgroundColor="#fff";
                                    }
                                });
                            });

                            $("select[name='accion']").change(function(){
                                var id_accion = $(this).val();
                                var token = $("input[name='_token']").val();
                                var homeLoader = $('body').loadingIndicator({
                                    useImage: false,
                                }).data("loadingIndicator");
                                homeLoader.show();
                                $.ajax({
                                    url: "/select-accion",
                                    method: 'POST',
                                    data: {id_accion:id_accion, _token:token},
                                    success: function(data) {
                                        console.log(data.calendario);
                                        console.log(data.options);
                                        $("#cedula_cliente").prop("disabled",true);
                                        if(data.options==0){
                                            $("#element").hide();
                                            $("#element_fecha").hide();
                                            $("#subacciones").show();
                                            $("#suma_asegurada").prop("disabled",true);
                                            $("#valor_prima").prop("disabled",true);
                                            $("#subaccion").prop("disabled",false);
                                            $("#subaccion2").prop("disabled",false);
                                            $("#subaccion3").prop("disabled",false);
                                            if(data.calendario==1) {
                                                $("#element_fecha").show();
                                                $("#fecha_pvisita").prop("disabled", false);
                                                $("#fecha_visit").show();
                                            }else{
                                                $("#element_fecha").hide();
                                                $("#fecha_pvisita").prop("disabled", true);
                                                $("#fecha_visit").hide();
                                            }
                                        }
                                        if(data.options>0 && data.options<50){
                                            $("#element").hide();
                                            $("#element_fecha").hide();
                                            $("#subacciones").hide();
                                            $("#suma_asegurada").prop("disabled",true);
                                            $("#valor_prima").prop("disabled",true);
                                            $("#subaccion").prop("disabled",true);
                                            $("#subaccion2").prop("disabled",true);
                                            $("#subaccion3").prop("disabled",true);
                                            if(data.calendario==1) {
                                                $("#element_fecha").show();
                                                $("#fecha_pvisita").prop("disabled", false);
                                                $("#fecha_visit").show();
                                            }else{
                                                $("#element_fecha").hide();
                                                $("#fecha_pvisita").prop("disabled", true);
                                                $("#fecha_visit").hide();
                                            }
                                        }
                                        if(data.options==40){
                                            $("#element").hide();
                                            $("#element_fecha").hide();
                                            $("#subacciones").hide();
                                            $("#suma_aseguradat").hide();
                                            $("#valor_primat").hide();
                                            $("#suma_asegurada").prop("disabled",true);
                                            $("#valor_prima").prop("disabled",true);
                                            $("#subaccion").prop("disabled",true);
                                            $("#subaccion2").prop("disabled",true);
                                            $("#subaccion3").prop("disabled",true);
                                            if(data.calendario==1) {
                                                $("#element_fecha").show();
                                                $("#fecha_pvisita").prop("disabled", false);
                                                $("#fecha_visit").show();
                                            }else{
                                                $("#element_fecha").hide();
                                                $("#fecha_pvisita").prop("disabled", true);
                                                $("#fecha_visit").hide();
                                            }
                                        }
                                        if(data.options==50){
                                            $("#element").show();
                                            $("#element_fecha").show();
                                            $("#valor_prima").show();
                                            $("#suma_aseguradat").show();
                                            $("#valor_primat").show();
                                            $("#subacciones").hide();
                                            $("#suma_aseguradat").hide();
                                            $("#valor_primat").hide();
                                            $("#suma_asegurada").prop("disabled",true);
                                            $("#valor_prima").prop("disabled",true);
                                            $("#subaccion").prop("disabled",true);
                                            $("#subaccion2").prop("disabled",true);
                                            $("#subaccion3").prop("disabled",true);
                                            if(data.calendario==1) {
                                                $("#element_fecha").show();
                                                $("#fecha_pvisita").prop("disabled", false);
                                                $("#fecha_visit").show();
                                            }else{
                                                $("#element_fecha").show();
                                                $("#fecha_pvisita").prop("disabled", true);
                                                $("#fecha_visit").hide();
                                            }
                                        }
                                        if( data.options==100){
                                            $("#element").show();
                                            $("#element_fecha").show();
                                            $("#subacciones").hide();
                                            $("#suma_aseguradat").show();
                                            $("#valor_primat").show();
                                            $("#suma_asegurada").prop("disabled",false);
                                            $("#valor_prima").prop("disabled",false);
                                            $("#subaccion").prop("disabled",true);
                                            $("#subaccion2").prop("disabled",true);
                                            $("#subaccion3").prop("disabled",true);
                                            $("#cedula_cliente").prop("disabled",false);
                                            if(data.calendario==1) {
                                                $("#element_fecha").show();
                                                $("#fecha_pvisita").prop("disabled", false);
                                                $("#fecha_visit").show();
                                            }else{
                                                $("#element_fecha").hide();
                                                $("#fecha_pvisita").prop("disabled", true);
                                                $("#fecha_visit").hide();
                                            }
                                        }
                                        homeLoader.hide();
                                        document.getElementById("accion").style.backgroundColor="#fff";
                                    }
                                });
                            });
                        </script>

                    <div id="element_fecha" style="display: none;">
                        <div id="fecha_visit" class="col-lg-12 col-md-12" style="margin-bottom: 10px; display: none">
                            <label class="col-lg-5">Fecha y hora de próxima cita: *</label>
                            <div class='col-lg-4 input-group date' id='datetimepicker1'>
                                <input type="text" class="span2 form-control" value="" id="fecha_pvisita" name="fecha_pvisita" disabled required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                        <div id="element" style="display: none;">
                        <div class="col-lg-12 col-md-12" id="suma_aseguradat" style="margin-bottom: 10px">
                            <label class="col-lg-12">Suma asegurada: *</label>
                            <div class="col-lg-12">
                                <input id="suma_asegurada" name="suma_asegurada" type="text" class="form-control" value="" disabled required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" id="valor_primat" style="margin-bottom: 10px">
                            <label class="col-lg-12">Valor de la prima: *</label>
                            <div class="col-lg-12">
                                <input name="valor_prima" id="valor_prima" type="text" class="form-control" value="" disabled required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" style="margin-bottom: 10px">
                            <label class="col-lg-12">Producto:</label>
                            <div class="col-lg-12">
                                {!! Form::select('producto',[''=>'--- Seleccione uno ---']+$productos,null,['id' => 'producto', 'class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12" id="cedula_clientet" style="margin-bottom: 10px">
                            <label class="col-lg-12">Cédula cliente: *</label>
                            <div class="col-lg-12">
                                <input id="cedula_cliente" name="cedula_cliente" type="text" class="form-control" value="" disabled required>
                            </div>
                        </div>
</div>
                        <div class="col-lg-12 col-md-12" >
                            <label class="col-lg-12">Observaciones: *</label>
                            <textarea name="observaciones" type="text" class="form-control" value="" required></textarea>
                        </div>

                        <div class="col-lg-12 col-md-6" style="margin-bottom: 10px">
                            <br>
                            <div class="col-lg-12 col-md-12">
                                <a href="{{url('home')}}" id="atras"  class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Atras </a>
                                <button type="submit" id="guardar" onclick="productoOb()" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar </button>
                            </div>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>
            </div>
            @endif
        </div>
    </div>


    <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

    <script type="text/javascript">

    </script>


@endsection