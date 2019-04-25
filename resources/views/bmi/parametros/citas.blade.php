@extends('layouts.appBmi')
@section('scripts')
    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script>
        function confirmation(id) {
            var answer = confirm("Seguro que desea eliminar esta acción?")
            if (answer){
                location.href='/tipoAccionD/'+id;
            }
            else{
                // do nothing
            }
        }
    </script>
@endsection
@section('content')
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">CITAS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="body">
                    <iframe class="embed-responsive-item" src="" id="frame" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">PARAMETROS CITAS</div>
                <div class="panel-body">
                    <div class="content">
                        <?php $disponibles=array();?>
                        @foreach($citas as $cita)
                                <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/citasU') }}">
                                    {{ csrf_field() }}
                                    <h3>{{$cita->ranking_asesor->descripcion}} - Nivel {{$cita->ranking_asesor->nivel}}</h3>
                                    <div class="well col-md-12 col-lg-12" valign="top">
                                        @if($cita->estilo!='')
                                        <div class="alert alert-{{$cita->estilo}} col-lg-6">
                                            <?php echo $cita->mensaje;?>
                                        </div>
                                        @endif
                                        <table class="table">
                                            <tr>
                                                <th>Máximo de citas:
                                                    <div class='input-group date col-md-3 col-lg-3  '>
                                                        <input type="hidden" value="{{$cita->id_parametros_citas}}" id="id_parametros_citas" name="id_parametros_citas" >
                                                        <input type="number" class="span2 form-control" value="{{$cita->citas_max}}" id="citas_max" name="citas_max" min='0' max='99' required @if($cita->estado==0) disabled @endif>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                    <br>Hora inicio:
                                                    <div class='input-group date col-md-3 col-lg-3'>
                                                        <input type="time" class="span2 form-control" value="{{$cita->hora_inicio}}" id="hora_inicio" name="hora_inicio" required @if($cita->estado==0) disabled @endif>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                    <br>Tiempo entre citas en minutos:
                                                    <div class='input-group date col-md-3 col-lg-3'>
                                                        <input type="number" class="span2 form-control" value="{{$cita->tiempo_citas}}" id="tiempo_citas" name="tiempo_citas" min='0' max='999' required @if($cita->estado==0) disabled @endif>
                                                        <span class="input-group-addon"></span>
                                                    </div>
                                                </th>
                                                <th align="center">
                                                    <h4 align="center">Asesores disponibles: {{\App\bmi\tbl_asesores::where('id_ranking',$cita->id_ranking)->where('estado',1)->count()}}</h4>
                                                    <h4 align="center"><strong>Clientes disponibles</strong></h4>
                                                    <table border="1" align="center">
                                                        <tr>
                                                            <th></th>
                                                            <?php $ranking_clientes=\App\bmi\tbl_ranking_cliente::where('estado',1)->orderBy('nivel','ASC')->get();?>
                                                            @foreach($ranking_clientes as $k)
                                                                <th>{{$k->descripcion}}</th>
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            <td align="right">TOTAL</td>
                                                            <?php $ranking_clientes=\App\bmi\tbl_ranking_cliente::where('estado',1)->orderBy('nivel','ASC')->get();?>
                                                            <?php $j=0; ?>
                                                            @foreach($ranking_clientes as $k)
                                                                <td align="center">
                                                                    @if($citas_con_clientes>0)
                                                                        @if(isset($disponibles[$j]))
                                                                            {{$disponibles[$j]}}
                                                                        @else
                                                                            <?php $disponibles[$j]=0;?>
                                                                                {{$disponibles[$j]=(\App\bmi\tbl_clientes::where('id_ranking',$k->id_ranking_cliente)->where('estado',1)->count())-$disponibles[$j]}}
                                                                        @endif


                                                                        <?php $j++;?>
                                                                    @else
                                                                        0
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            <td align="right">UTILIZADOS</td>
                                                            <?php $ranking_clientes=\App\bmi\tbl_ranking_cliente::where('estado',1)->orderBy('nivel','ASC')->get();?>
                                                            @foreach($ranking_clientes as $k)
                                                            <td align="center">
                                                                @if($citas_con_clientes>0)
                                                                    {{\App\bmi\tbl_citas_con_clientes::where('id_ranking_cliente',$k->id_ranking_cliente)->where('id_parametros_citas',$cita->id_parametros_citas)->first()['total']}}
                                                                @else
                                                                    0
                                                                @endif
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            <td align="right">DISPONIBLES</td>
                                                            <?php $ranking_clientes=\App\bmi\tbl_ranking_cliente::where('estado',1)->orderBy('nivel','ASC')->get();?>
                                                            <?php $j=0;?>
                                                            @foreach($ranking_clientes as $k)
                                                                <td align="center">
                                                                    @if($citas_con_clientes>0)
                                                                        <?php $disponibles[$j]=\App\bmi\tbl_citas_con_clientes::where('estado',1)->where('id_parametros_citas',$cita->id_parametros_citas)->where('id_ranking_cliente',$k->id_ranking_cliente)->first()['disponibles'];?>
                                                                        {{\App\bmi\tbl_citas_con_clientes::where('estado',1)->where('id_parametros_citas',$cita->id_parametros_citas)->where('id_ranking_cliente',$k->id_ranking_cliente)->first()['disponibles']}}
                                                                    @else
                                                                        0
                                                                    @endif
                                                                        <?php $j++;?>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    </table>

                                                </th>
                                                <th>
                                                    <h4 align="">Clientes distribuidos:</h4>
                                                    <div class="form-group row">
                                                        <?php $ranking_clientes=\App\bmi\tbl_ranking_cliente::where('estado',1)->orderBy('nivel','ASC')->get();?>

                                                        @foreach($ranking_clientes as $k)
                                                                <label><h4>{{$k->descripcion}}:</h4></label>
                                                                <div class='input-group col-lg-3'>
                                                                    <input type="hidden" name="id_ranking_cliente[{{$k->id_ranking_cliente}}]" value="{{$k->id_ranking_cliente}}">
                                                                    <input type="hidden" name="id_parametros_citas" value="{{$cita->id_parametros_citas}}">

                                                                    @if($citas_con_clientes>0)
                                                                    <input type="number" class="form-control " name="total[{{$k->id_ranking_cliente}}]" min="0"
                                                                           max="{{\App\bmi\tbl_clientes::where('id_ranking',$k->id_ranking_cliente)->count()}}"
                                                                           value="{{\App\bmi\tbl_citas_con_clientes::where('id_ranking_cliente',$k->id_ranking_cliente)->where('id_parametros_citas',$cita->id_parametros_citas)->first()['total']}}" @if($cita->estado==0) disabled @endif readonly>
                                                                    @else
                                                                        <h4>0</h4>
                                                                    @endif
                                                                </div>
                                                        @endforeach

                                                    </div>
                                                </th>
                                            </tr>
                                        </table>
                                        @if($cita->estado==1)
                                            <button type="submit" class="btn btn-success">Actualizar</button>
                                            <a href="{{url('/habdesc/'.$cita->id_parametros_citas.'/0')}}" class="btn btn-warning">Deshabilitar</a>
                                        @else
                                            <button type="submit" class="btn btn-success disabled" disabled>Actualizar</button>
                                            <a href="{{url('/habdesc/'.$cita->id_parametros_citas.'/1')}}" class="btn btn-primary">Habilitar</a>
                                        @endif
                                    </div>
                                </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection