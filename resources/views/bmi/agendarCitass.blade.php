@extends('layouts.appBmi')
@section('scripts')
    {{--<script src="/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/datepicker/css/datepicker.css')}}" />

    <!-- Latest compiled and minified CSS -->
    <script src="{{asset('vendor/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>--}}

    <link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="/js/datatables.min.js"></script>

    <script type="application/javascript">
        $(document).ready(function() {
            $('#lista_clientes').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 5, "asc" ]],
                "lengthMenu": [[10,20,-1], [10,20,"All"]]
            } );
            $('#lista_citas').DataTable( {
                "scrollY": true,
                "scrollX": true,
                "order": [[ 5, "asc" ]],
                "lengthMenu": [[-1,100,50,20,10], ["All",100,50,20,10]]
            } );
        } );
        $().ready(function()
        {

            $('.pasar').click(function() {
                if($('#origen option:selected').val()==undefined){return false;}

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $("#cargando").css("display", "inline");
                var form = $(this);
                var url = '{{URL::to('usuariosClientes')}}';

                var formData = $('#origen option:selected').val();
                //formData.append("dato", "valor");
                //formData.append(f.attr("name"), $(this)[0].files[0]);
                $.ajax({
                    url: url,
                    type: "POST",
                    url: url,
                    data: $("#formulario").serialize(),
                    success: function(data)
                    {
                        $("#cargando").css("display", "none");
                        $("#resultado").css("display", "inline");
                        $("#resultado").wrapInner( "<div style='color:#3f51b5'>"+data+"</div>");
                    }
                });
                return !$('#origen option:selected').remove().appendTo('#destino');
            });
            $('.quitar').click(function() {

                return !$('#destino option:selected').remove().appendTo('#origen');
            });
            $('.pasartodos').click(function() { $('#origen option').each(function() {
                if(document.getElementById('clienteselect').value==''){
                    alert('Debe Seleccionar un cliente');
                    document.getElementById("clienteselect").focus();
                    return false;
                }
                $(this).remove().appendTo('#destino'); });
            });
            $('.quitartodos').click(function() { $('#destino option').each(function() { $(this).remove().appendTo('#origen'); }); });
            $('.submit').click(function() { $('#destino option').prop('selected', 'selected'); });
        });

        function selecciona(id) {
            id=document.getElementById(id);
            for (i=0; ele = id.options[i]; i++)
                ele.selected = true;
        }
    </script>
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title">Agendar Citas</h1>
        </div>
        <div class="panel-body" style="font-size: 11px">
            <div  class="panel panel-warning">
                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>NOMBRES</th>
                        <th>CEDULA</th>
                        <th>CELULAR</th>
                        <th>EMAIL PERSONAL</th>
                        <th>EMAIL CORPORATIVO</th>
                        <th>FECHA DE CONTRATO</th>
                        <th>RANKING</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td><a href="{{ url('/agendarCitassBmi/'.$asesor->cedula_asesor) }}" class="modalButton btn btn-success">{{$asesor->cedula_asesor}}</a></td>
                        <td>{{$asesor->nombres}}</td>
                        <td>{{$asesor->celular}}</td>
                        <td>{{$asesor->email_personal}}</td>
                        <td>{{$asesor->email_corporativo}}</td>
                        <td>{{$asesor->fecha_contrato}}</td>
                        <td>{{$asesor->ranking_asesor->descripcion}}
                        <br>Nivel: {{$asesor->ranking_asesor->nivel}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Citas asignadas</strong></div>

                <form id="logout-form" action="{{ url('/agendarCitas2Bmi') }}" method="POST" onsubmit="selecciona('destino')">
                    {{ csrf_field() }}
                    <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="lista_citas">
                        <thead>
                        <tr>
                            <th>CEDULA</th>
                            <th>NOMBRES</th>
                            <th>TELEFONO</th>
                            <th>SUELDO</th>
                            <th>DIRECCION DE VISITA</th>
                            <th>HORA CITA</th>
                            <th>DATOS ADICIONALES</th>
                            <th>RANKING</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($citas as $cita)
                            @if(\App\bmi\tbl_gestiones::where('id_gestion',$cita->id_gestion)->count()==0)
                            <tr class="alert ">
                                <td class="{{$cita->success}}">{{$cita->cedula_cliente}}</td>
                                <td class="{{$cita->success}}">{{$cita->nombres}}</td>
                                <td class="{{$cita->success}}">{{$cita->clientes->celular}}</td>
                                <td class="{{$cita->success}}">{{$cita->clientes->salario}}</td>
                                <td class="{{$cita->success}}">{{$cita->direccion_cita}}</td>
                                <td class="{{$cita->success}}">{{$cita->hora_cita}}</td>
                                <td class="{{$cita->success}}">{{$cita->clientes->datos_adicionales}}</td>
                                <td class="{{$cita->success}}">
                                    {{$cita->clientes->ranking_cliente->descripcion}}
                                    <?php $cliente_manual=\App\bmi\tbl_citas::where('id_cita',$cita->id_cita)->where('cita_automatica',0)->count();
                                    $cliente_automatico=\App\bmi\tbl_citas::where('id_cita',$cita->id_cita)->where('cita_automatica',1)->count();?>
                                    @if($cliente_manual>0)
                                        <span class="badge badge-success" style="background-color: green">manual</span>
                                    @elseif($cliente_automatico>0)
                                        <span class="badge badge-success" style="background-color:#2a6496">automático</span>
                                    @endif
                                </td>
                                <td  class="{{$cita->success}}">
                                    <a href="{{url('/eliminarCita/0/'.$cita->asesor."/".$cita->id_cita)}}" class="modalButton btn btn-xs btn-danger">Eliminar</a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="panel panel-success">
                <div class="panel-heading"><strong>Clientes Disponibles</strong></div>

                <form id="logout-form" action="{{ url('/agendarCitas2Bmi') }}" method="POST" onsubmit="selecciona('destino')">
                    {{ csrf_field() }}
                    <input type="hidden" name="asesor" value="{{$asesor->cedula_asesor}}">
                <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="lista_clientes">
                    <thead>
                    <tr>
                        <th>CEDULA</th>
                        <th>NOMBRES</th>
                        <th>TELEFONO</th>
                        <th>SUELDO</th>
                        <th>DIRECCION DE VISITA</th>
                        <th>FECHA CITA</th>
                        <th>HORA CITA</th>
                        <th>DATOS ADICIONALES</th>
                        <th>RANKING</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($clientes as $k)
                        <tr>
                            <td>
                                <?php
                                $cedula_cliente=trim($k->cedula_cliente);
                                if(strlen($cedula_cliente)==9){$cedula_cliente= (string) '0'.$cedula_cliente;}
                                ?>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="cliente[]" value="{{$cedula_cliente}}" >{{$cedula_cliente}}</label>
                                    </div>
                            </td>
                            <td>{{$k->nombres}}</td>
                            <td>{{$k->celular}}</td>
                            <td>${{$k->salario}}</td>
                            <td>{{$k->direccion_visita}}</td>
                            <td>{{$k->fecha_cita}}</td>
                            <td>{{$k->hora_cita}}</td>
                            <td>{{$k->datos_adicionales}}</td>
                            <td>
                                {{$k->ranking_cliente->descripcion}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                    <a href="{{url('agendarCitasBmi')}}" class="btn btn-success" id="getSelected">< Regresar</a>
                    <button class="btn btn-primary" id="getSelected">Asignar</button>
                </form>
            </div>


            {{--<form id="logout-form" action="{{ url('/agendarCitas2Bmi') }}" method="POST" onsubmit="selecciona('destino')">
                {{ csrf_field() }}
                <br>
                <div class="col-lg-12 col-md-12">

                    <div class="col-lg-4 col-md-4">
                        <a href='#' class='list-group-item active'><span class='glyphicon glyphicon-th-large'></span> Clientes Activos</a>
                        <input type="hidden" name="cedula_asesor" value="{{$asesor->cedula_asesor}}">
                        <select name="origen[]" id="origen" multiple="multiple" size="8" class="form-control">
                            @foreach($clientes as $k)
                                <option value="{{$k->cedula_cliente}}">{{$k->ranking_cliente->descripcion}} - ${{$k->salario}} - {{$k->nombres}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <input type="button" class="pasar izq form-control" value="Asignar >>"><input type="button" class="quitar der form-control" value="<< Remover"><br />
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <a href='#' class='list-group-item active'><span class='glyphicon glyphicon-th-large'></span> Clientes Asignados</a>
                        <select name="destino[]" id="destino" multiple="multiple" size="8" class="form-control">
                            @foreach($citas as $k)
                                <option value="{{$k->cedula}}">{{$k->nombres}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-4">
                        Fecha de asignacion:
                        <input type="date" value="" class="form-control">
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <br>
                        <a href="{{url('agendarCitasBmi')}}" class="btn btn-success" id="getSelected">< Regresar</a>
                        <button class="btn btn-primary" id="getSelected">Guardar</button>
                    </div>

                </div>
            </form>--}}

        </div>
    </div>
@endsection