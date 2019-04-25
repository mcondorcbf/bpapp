<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="{{asset('/css/jquery.dataTables.min.css')}}"/>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel-body">
            <div class="content">
                <div class="links" id="centrales">
                    @foreach($citas as $cita)
                        <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/citasUp') }}">
                            {{ csrf_field() }}
                            <h3 align="center">{{$cita->ranking_asesor->descripcion}} - Nivel {{$cita->ranking_asesor->nivel}}</h3>
                            <div class="well col-md-12 col-lg-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Máximo de citas:
                                            <div class='input-group date col-md-2 col-lg-2'>
                                                <input type="hidden" value="{{$cita->id_parametros_citas}}" id="id_parametros_citas" name="id_parametros_citas" >
                                                <input type="number" class="span2 form-control" value="{{$cita->citas_max}}" id="citas_max" name="citas_max" min='0' max='99' required>
                                                <span class="input-group-addon"></span>
                                            </div>
                                            <br>Hora inicio:
                                            <div class='input-group date col-md-2 col-lg-2'>
                                                <input type="text" class="span2 form-control" value="{{$cita->hora_inicio}}" id="hora_inicio" name="hora_inicio" required>
                                                <span class="input-group-addon"></span>
                                            </div>
                                            <br>Tiempo entre citas en minutos:
                                            <div class='input-group date col-md-2 col-lg-2'>
                                                <input type="number" class="span2 form-control" value="{{$cita->tiempo_citas}}" id="tiempo_citas" name="tiempo_citas" min='0' max='999' required>
                                                <span class="input-group-addon"></span>
                                            </div>
                                        </th>
                                        <th >
                                            <h3 align="center">Asesores disponibles:</h3>
                                            <div class='col-lg-2' align="center">
                                                <h3>{{\App\bmi\tbl_asesores::where('id_ranking',$cita->id_ranking)->where('estado',1)->count()}}</h3>
                                            </div>
                                            <h3 align="center">Total clientes:</h3>
                                            <div class='col-lg-2' align="center">
                                                <?php $ranking_clientes=\App\bmi\tbl_ranking_cliente::where('estado',1)->get();?>
                                                    @foreach($ranking_clientes as $k)
                                                        <h3>{{$k->descripcion}}:{{\App\bmi\tbl_clientes::where('estado',1)->where('id_ranking',$k->id_ranking_cliente)->count()}}{{$k->id_ranking_cliente}}</h3>
                                                    @endforeach
                                            </div>
                                        </th>
                                    </tr>
                                    </thead>
                                </table>
                                <button type="submit" class="btn btn-success">Actualizar</button>
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>