<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/js/bootstrap-select.min.js"></script>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Datos del cliente</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Nombres</label>
                            <div class="col-sm-8">
                                {{$cita->nombres}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Empresa</label>
                            <div class="col-sm-8">
                                {{isset($cita->clientes->empresa->nombre)}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Dirección visita:</label>
                            <div class="col-sm-8">
                                {{$cita->direccion_cita}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Fecha y hora agendada:</label>
                            <div class="col-sm-8">
                                {{$cita->fecha_cita}} {{$cita->hora_cita}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Datos de la gestión</h3>
                    </div>

                    <div class="panel-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Fecha y hora visitada</label>
                            <div class="col-sm-8">
                                {{$cita->fecha_visita}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Tipo</label>
                            <div class="col-sm-8">
                                {{$cita->tipo->descripcion}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Acción</label>
                            <div class="col-sm-8">
                                {{$cita->accion->descripcion}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Fecha y hora de próxima cita</label>
                            <div class="col-sm-8">
                                {{$cita->fecha_proxima_visita}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Suma asegurada</label>
                            <div class="col-sm-8">
                                {{$cita->suma_asegurada}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Valor de la prima</label>
                            <div class="col-sm-8">
                                {{$cita->valor_prima}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Producto</label>
                            <div class="col-sm-8">
                                @if($cita->id_cita_orig!='')
                                    @if($cita->producto!='')
                                        {{$cita->producto->descripcion}}
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Observaciones</label>
                            <div class="col-sm-8">
                                {{$cita->observacion}}
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <h4 align="center">Historial de gestiones</h4>
                        <table class="table table-hover table-striped display" id="historiall" cellspacing="0" width="100%" style="font-size: 11px;">
                            <thead>
                            <th>#</th>
                            <th>Fecha y hora de cita agendada</th>
                            <th>Fecha próxima gestión</th>
                            <th>Suma asegurada</th>
                            <th>Valor de la prima</th>
                            <th>Observación</th>
                            <th>Estado</th>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            @if($cita->id_cita_orig!='')
                            <?php $citaes=\App\bmi\tbl_citas_historial_manuales::where('id_cita_orig',$cita->id_cita_orig)->get();?>
                            @foreach($citaes as $k)
                                <tr>
                                    <td>
                                        {{$i}}
                                    </td>
                                    <td>
                                        {{$k->fecha_cita}}
                                    </td>
                                    <td>
                                        {{$k->fecha_proxima_visita}}
                                    </td>
                                    <td>
                                        {{$k->suma_asegurada}}
                                    </td>
                                    <td>
                                        {{$k->valor_prima}}
                                    </td>
                                    <td>
                                        {{$k->observacion}}
                                    </td>
                                    <td>
                                        @if($k->accion->peso==100)
                                            <button type="button" class="modalButton btn btn-success btn-xs" data-toggle="modal">{{$k->accion->descripcion}}</button>
                                        @elseif($k->accion->peso==50)
                                            <button type="button" class="modalButton btn btn-warning btn-xs" data-toggle="modal">{{$k->accion->descripcion}}</button>
                                        @elseif($k->accion->peso<50)
                                            <button type="button" class="modalButton btn btn-danger btn-xs" data-toggle="modal">{{$k->accion->descripcion}}</button>
                                        @endif
                                    </td>
                                </tr>
                                <?php $i++?>
                            @endforeach
                            @endif
                            @if($i>1)
                                <?php $cita=\App\bmi\tbl_citas_historial_manuales::where('id_cita',$cita->id_cita_orig)->first();?>
                            @else
                                <?php $cita=\App\bmi\tbl_citas_historial_manuales::where('id_cita',$cita->id_cita)->first();?>
                            @endif
                            <tr>
                                <td>
                                    {{$i}}
                                </td>
                                <td>
                                    {{$cita->fecha_cita}} {{$cita->hora_cita}}
                                </td>
                                <td>
                                    {{$cita->fecha_proxima_visita}}
                                </td>
                                <td>
                                    {{$cita->suma_asegurada}}
                                </td>
                                <td>
                                    {{$cita->valor_prima}}
                                </td>
                                <td>
                                    {{$cita->observacion}}
                                </td>
                                <td>
                                    @if($cita->accion->peso==100)
                                        <button type="button" class="modalButton btn btn-success btn-xs" data-toggle="modal">{{$cita->accion->descripcion}}</button>
                                    @elseif($cita->accion->peso==50)
                                        <button type="button" class="modalButton btn btn-warning btn-xs" data-toggle="modal">{{$cita->accion->descripcion}}</button>
                                    @elseif($cita->accion->peso<50)
                                        <button type="button" class="modalButton btn btn-danger btn-xs" data-toggle="modal">{{$cita->accion->descripcion}}</button>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>


    <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

    <script type="text/javascript">

    </script>

