<?php
use App\tbl_archivos as archivos;
?>@extends('layouts.appsupervisor')
@section('scripts')

@endsection
@section('content')
<style type="text/css">
    a {
        color: #FFF;
        text-decoration: none;
    }
</style>
<script>
    $(document).ready(function() {
        $('#diners').DataTable( {
            "scrollY": 500,
            "scrollX": true
        } );

        $('#visa').DataTable( {
            "scrollY": 500,
            "scrollX": true
        } );
        $('#visar').DataTable( {
            "scrollY": 500,
            "scrollX": true
        } );
        $('#discoverr').DataTable( {
            "scrollY": 500,
            "scrollX": true
        } );
        $('#discover').DataTable( {
            "scrollY": 500,
            "scrollX": true
        } );
    } );

    function marcar(obj,chk) {
        elem=obj.getElementsByTagName('input');
        for(i=0;i<elem.length;i++)
            elem[i].checked=chk.checked;
    }

</script>

<h1 align="center"> ENVIADOS </h1>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">OBSERVACION</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="body">
            <iframe class="embed-responsive-item" src="" id="frame" frameborder="0"></iframe>
        </div>
        <div class="modal-footer">
            <a href="/#" style="background-color: #337ab7" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</a>
        </div>
    </div>
</div>
</div>

<div class="form-group">
    <div class="" style="background-color: #001e73;">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#dinersconsolidado" data-toggle="tab">DINERS</a></li>
            <li class=""><a href="#visaconsolidado" data-toggle="tab">VISA</a></li>
            <li class=""><a href="#discoverconsolidado" data-toggle="tab">DISCOVER</a></li>
        </ul>

        <div class="tab-content tab-content-border" style="font-size: 10px">
            <div class="tab-pane fade active in" id="dinersconsolidado">
                <div class="col-md-12 col-lg-12">
                    <div class="panel-heading " style="background-color: #3a77bf; border-color: #000; color: #fff;">CONSOLIDADO DINERS </div>

                    <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/procesarEnviados') }}" style="font-size: 10px">
                    {{ csrf_field() }}
                    <!-- Nav tabs -->
                        <div>
                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span> <strong>SELECCIONAR TODOS</strong> <input type="checkbox" onclick="marcar(this.parentNode,this)" />
                            <table id="diners" class="display" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>-</th>
                                    <th>ESTADO</th>
                                    <th>MENSAJE</th>
                                    <th>OBSERVACIONES</th>
                                    <th>FECHA SOLICITUD</th>
                                    <th>MARCA</th>
                                    <th>DIGITOS CÉDULA</th>
                                    <th>NOMBRE DEL CLIENTE</th>
                                    <th>PLAZO</th>
                                    <th>CICLO</th>
                                    <th>TIPO DE REFINANCIACION</th>
                                    <th>ZONA</th>
                                    <th>GESTOR</th>
                                    <th>ARCHIVOS</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php $i=1;$color='';$estado='';?>
                                @foreach($gestiones_d as $k=>$v)
                                    @if($v->id_estado_gestion==2)
                                        <?php $color='';$estado='CONSOLIDADO';?>
                                    @endif
                                    @if($v->id_estado_gestion==3)
                                        <?php $color='style=background-color:#e9a747';?>
                                        <?php $estado='PENDIENTE TLC';?>
                                    @endif
                                    @if($v->id_estado_gestion==4)
                                        <?php $color='style=background-color:#c5fdc8';?>
                                        <?php $estado='PENDIENTE DINERS';?>
                                    @endif
                                    @if($v->id_estado_gestion==5)
                                        <?php $color='style=background-color:#9ca1da';?>
                                        <?php $estado='PENDIENTE CEX';?>
                                    @endif
                                    <tr {{$color}}>
                                        <td><button type="button" class="modalButton" data-toggle="modal" data-src="{{url('/ver/'.$v->id)}}" data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen="">{{$v->id}}</button></td>
                                        <td><input type="checkbox" name="gestion[]" value="{{$v->id}}"> </td>
                                        <td><strong>{{$estado}}</strong></td>
                                        <td><strong>{{$v->mensaje}}</strong></td>
                                        <td align="left"><button type="button" class="modalButton" data-toggle="modal" data-src="{{url('observacion/'.$v->id)}}"  data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen=""><?php echo str_replace ( '//' , '<br>- ' , $v->observacion );?></button></td>
                                        <td>{{$v->created_at}}</td>
                                        <td>{{$v->marca}}</td>
                                        <td>{{$v->digitos_cedula}}</td>
                                        <td>{{$v->nombre_cliente}}</td>
                                        <td>{{$v->plazo}}</td>
                                        <td>{{$v->ciclo}}</td>
                                        <td>{{$v->tipo_de_refinanciacion}}</td>
                                        <td>{{$v->zona}}</td>
                                        <td>{{$v->gestor}}</td>
                                        <td>
                                            <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get(); $contador=0;?>
                                            @foreach($archivos as $k)
                                                <br><span class="label label-primary" style="font-size: 10px"><span class="glyphicon glyphicon-paperclip"></span> <a href="/download/{{$k->id}}" >{{$k->nombre}}</a></span>
                                                <?php $contador=1;?>
                                            @endforeach
                                            @if($contador>0)
                                                <br><span class="label label-danger" style="font-size: 10px"><span class="glyphicon glyphicon-compressed"></span> <a href="/comprimir/{{$v->id}}" >Descargar comprimido</a></span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        OBSERVACIÓN:
                        <textarea class="form-control" required name="observaciondin" id="observaciondin"></textarea><br>
                        <button type="submit" class="btn btn-default" value="descarga" name="descarga">
                            <span class="glyphicon glyphicon-download-alt"></span> DESCARGAR LISTA
                        </button>
                        <input type="hidden" name="tarjeta" value='DINERS CLUB'>
                        <button type="submit" class="btn btn-primary" style="background-color: #3a77bf; border-color: #000; color: #fff;" name="estado" value="6" onclick="confirmarDiners()">
                            <span class="glyphicon glyphicon-ok"></span> APROBADOS
                        </button>
                        <button type="submit" class="btn btn-danger" style="border-color: #000; color: #fff;" name="estado" value="7" onclick="confirmarDiners()">
                            <span class="glyphicon glyphicon-remove"></span> RECHAZADOS
                        </button>
                        <button type="submit" class="btn btn-primary" style="background-color: #4caf50; border-color: #000; color: #fff;" name="estado" value="4" onclick="confirmarDiners()">
                            <span class="glyphicon glyphicon-asterisk"></span> PENDIENTES DINERS
                        </button>
                        <button type="submit" class="btn btn-danger" style="background-color: #e28803; border-color: #000; color: #fff;" name="estado" value="3" onclick="confirmarDiners()">
                            <span class="glyphicon glyphicon-asterisk"></span> PENDIENTES TLC
                        </button>
                        <button type="submit" class="btn btn-danger" style="background-color: #7176b1; border-color: #000; color: #fff;" name="estado" value="5" onclick="confirmarDiners()">
                            <span class="glyphicon glyphicon-asterisk"></span> PENDIENTES CEX
                        </button>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="visaconsolidado">
                <div class="col-md-12 col-lg-12">

                    <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/procesarEnviados') }}">
                        {{ csrf_field() }}

                        <ul class="nav nav-tabs" role="tablist" style="background-color: #777;">
                            <li role="presentation" class="active"><a href="#tab1_v" aria-controls="tab1_v" role="tab" data-toggle="tab">CONSOLIDADO VISA ROTATIVO</a></li>
                            <li role="presentation"><a href="#tab2_v" aria-controls="tab2_v" role="tab" data-toggle="tab">CONSOLIDADO VISA REFINANCIAMIENTO</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="tab1_v">
                                <div>
                                    <span class="glyphicon glyphicon-star" aria-hidden="true"></span> <strong>SELECCIONAR TODOS</strong> <input type="checkbox" onclick="marcar(this.parentNode,this)" />
                                    <table id="visar" class="display" cellspacing="0" >
                                        <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>-</th>
                                            <th>ESTADO</th>
                                            <th>MENSAJE</th>
                                            <th>OBSERVACIONES</th>
                                            <th>FECHA SOLICITUD</th>
                                            <th>MARCA</th>
                                            <th>GESTOR</th>
                                            <th>USUARIO SOLICITA</th>
                                            <th>CORTE</th>
                                            <th>CUENTA</th>
                                            <th>CEDULA</th>
                                            <th>NOMBRE</th>
                                            <th>TIPO</th>
                                            <th>OBSERVACION</th>
                                            <th>ARCHIVOS</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1;$color='';$estado='';?>
                                        @foreach($gestiones_vr as $k=>$v)
                                            @if($v->observacion_negociacion_especial!='')
                                                <?php $color='style=background-color:#fffca9';?>
                                            @endif
                                            @if($v->id_estado_gestion==2)
                                                <?php $color='';?>
                                            @endif
                                            @if($v->id_estado_gestion==3)
                                                <?php $color='style=background-color:#ffe4bd';?>
                                                <?php $estado='PENDIENTE TLC';?>
                                            @endif
                                            @if($v->id_estado_gestion==4)
                                                <?php $color='style=background-color:#c5fdc8';?>
                                                <?php $estado='PENDIENTE DINERS';?>
                                            @endif
                                            @if($v->id_estado_gestion==5)
                                                <?php $color='style=background-color:#dfe1f3';?>
                                                <?php $estado='PENDIENTE CEX';?>
                                            @endif
                                            <tr {{$color}}>
                                                    <td>{{$v->id}}</td>
                                                    <td><input type="checkbox" name="gestion[]" value="{{$v->id}}"> </td>
                                                    <td><strong>{{$estado}}</strong></td>
                                                    <td><strong>{{$v->mensaje}}</strong></td>
                                                    <td align="left"><button type="button" class="modalButton" data-toggle="modal" data-src="{{url('ver/'.$v->id)}}"  data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen=""><?php echo str_replace ( '//' , '<br>- ' , $v->observacion );?></button></td>
                                                    <td>{{$v->created_at}}</td>
                                                    <td>{{$v->marca}}</td>
                                                    <td>{{$v->gestor}}</td>
                                                    <td>{{$v->cod_encargado}}</td>
                                                    <td>{{$v->ciclo}}</td>
                                                    <td>{{$v->cuenta}}</td>
                                                    <td>{{$v->digitos_cedula}}</td>
                                                    <td>{{$v->nombre_cliente}}</td>
                                                    <td>{{$v->tipo_cuenta}}</td>
                                                    <td>{{$v->observacion_negociacion_especial}}</td>
                                                    <td>
                                                        <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get(); $contador=0;?>
                                                        @foreach($archivos as $k)
                                                            <br><span class="label label-primary" style="font-size: 10px"><span class="glyphicon glyphicon-paperclip"></span> <a href="/download/{{$k->id}}" >{{$k->nombre}}</a></span>
                                                            <?php $contador=1;?>
                                                        @endforeach
                                                        @if($contador>0)
                                                            <br><span class="label label-danger" style="font-size: 10px"><span class="glyphicon glyphicon-compressed"></span> <a href="/comprimir/{{$v->id}}" >Descargar comprimido</a></span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab2_v">
                                <div>
                                    <span class="glyphicon glyphicon-star" aria-hidden="true"></span> <strong>SELECCIONAR TODOS</strong> <input type="checkbox" onclick="marcar(this.parentNode,this)" />
                                    <table id="visa" class="display" cellspacing="0" >
                                        <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>-</th>
                                            <th>ESTADO</th>
                                            <th>MENSAJE</th>
                                            <th>OBSERVACIONES</th>
                                            <th>FECHA SOLICITUD</th>
                                            <th>MARCA</th>
                                            <th>DIGITOS CÉDULA</th>
                                            <th>NOMBRE DEL CLIENTE</th>
                                            <th>PLAZO</th>
                                            <th>CICLO</th>
                                            <th>TIPO DE REFINANCIACION</th>
                                            <th>ZONA</th>
                                            <th>GESTOR</th>
                                            <th>ARCHIVOS</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1;$color='';$estado='';?>
                                        @foreach($gestiones_v as $k=>$v)
                                            @if($v->id_estado_gestion==2)
                                                <?php $color='';?>
                                            @endif
                                            @if($v->id_estado_gestion==3)
                                                <?php $color='style=background-color:#ffe4bd';?>
                                                <?php $estado='PENDIENTE TLC';?>
                                            @endif
                                            @if($v->id_estado_gestion==4)
                                                <?php $color='style=background-color:#c5fdc8';?>
                                                <?php $estado='PENDIENTE DINERS';?>
                                            @endif
                                            @if($v->id_estado_gestion==5)
                                                <?php $color='style=background-color:#dfe1f3';?>
                                                <?php $estado='PENDIENTE CEX';?>
                                            @endif
                                            <tr {{$color}}>
                                                <td>{{$v->id}}</td>
                                                <td><input type="checkbox" name="gestion[]" value="{{$v->id}}"> </td>
                                                <td><strong>{{$estado}}</strong></td>
                                                <td><strong>{{$v->mensaje}}</strong></td>
                                                <td align="left"><button type="button" class="modalButton" data-toggle="modal" data-src="{{url('ver/'.$v->id)}}"  data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen=""><?php echo str_replace ( '//' , '<br>- ' , $v->observacion );?></button></td>
                                                <td>{{$v->created_at}}</td>
                                                <td>{{$v->marca}}</td>
                                                <td>{{$v->digitos_cedula}}</td>
                                                <td>{{$v->nombre_cliente}}</td>
                                                <td>{{$v->plazo}}</td>
                                                <td>{{$v->ciclo}}</td>
                                                <td>{{$v->tipo_de_refinanciacion}}</td>
                                                <td>{{$v->zona}}</td>
                                                <td>{{$v->gestor}}</td>
                                                <td>
                                                    <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get(); $contador=0;?>
                                                    @foreach($archivos as $k)
                                                        <br><span class="label label-primary" style="font-size: 10px"><span class="glyphicon glyphicon-paperclip"></span> <a href="/download/{{$k->id}}" >{{$k->nombre}}</a></span>
                                                        <?php $contador=1;?>
                                                    @endforeach
                                                    @if($contador>0)
                                                        <br><span class="label label-danger" style="font-size: 10px"><span class="glyphicon glyphicon-compressed"></span> <a href="/comprimir/{{$v->id}}" >Descargar comprimido</a></span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="tarjeta" value='VISA INTERDIN'>
                        OBSERVACIÓN:
                        <textarea class="form-control" required name="observacionv" id="observacionv"></textarea><br>
                        <button type="submit" class="btn btn-default" value="descarga" name="descarga">
                            <span class="glyphicon glyphicon-download-alt"></span> DESCARGAR LISTA
                        </button>
                        <input type="hidden" name="tarjeta" value='VISA INTERDIN'>
                        <button type="submit" class="btn btn-primary" style="background-color: #3a77bf; border-color: #000; color: #fff;" name="estado" value="6" onclick="confirmarVisa()">
                            <span class="glyphicon glyphicon-ok"></span> APROBADOS
                        </button>
                        <button type="submit" class="btn btn-danger" style="border-color: #000; color: #fff;" name="estado" value="7" onclick="confirmarVisa()">
                            <span class="glyphicon glyphicon-remove"></span> RECHAZADOS
                        </button>
                        <button type="submit" class="btn btn-primary" style="background-color: #4caf50; border-color: #000; color: #fff;" name="estado" value="4" onclick="confirmarVisa()">
                            <span class="glyphicon glyphicon-asterisk"></span> PENDIENTES DINERS
                        </button>
                        <button type="submit" class="btn btn-danger" style="background-color: #e28803; border-color: #000; color: #fff;" name="estado" value="3" onclick="confirmarVisa()">
                            <span class="glyphicon glyphicon-asterisk"></span> PENDIENTES TLC
                        </button>
                        <button type="submit" class="btn btn-danger" style="background-color: #7176b1; border-color: #000; color: #fff;" name="estado" value="5" onclick="confirmarVisa()">
                            <span class="glyphicon glyphicon-asterisk"></span> PENDIENTES CEX
                        </button>
                        <input type="hidden" name="formato" id="formato_v" value="rotativo">
                    </form>

                </div>
            </div>
            <div class="tab-pane fade" id="discoverconsolidado">
                <div class="col-md-12 col-lg-12">

                    <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/procesarEnviados') }}">
                        {{ csrf_field() }}

                        <ul class="nav nav-tabs" role="tablist" style="background-color: #ff9a22;">
                            <li role="presentation" class="active"><a href="#tab1_dis" aria-controls="tab1_dis" role="tab" data-toggle="tab">CONSOLIDADO DISCOVER ROTATIVO</a></li>
                            <li role="presentation"><a href="#tab2_dis" aria-controls="tab2_dis" role="tab" data-toggle="tab">CONSOLIDADO DISCOVER REFINANCIAMIENTO</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="tab1_dis">
                                <div>
                                    <span class="glyphicon glyphicon-star" aria-hidden="true"></span> <strong>SELECCIONAR TODOS</strong> <input type="checkbox" onclick="marcar(this.parentNode,this)" />
                                    <table id="discoverr" class="display" cellspacing="0" >
                                        <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>-</th>
                                            <th>ESTADO</th>
                                            <th>MENSAJE</th>
                                            <th>OBSERVACIONES</th>
                                            <th>FECHA SOLICITUD</th>
                                            <th>MARCA</th>
                                            <th>GESTOR</th>
                                            <th>USUARIO SOLICITA</th>
                                            <th>CORTE</th>
                                            <th>CUENTA</th>
                                            <th>CEDULA</th>
                                            <th>NOMBRE</th>
                                            <th>TIPO</th>
                                            <th>OBSERVACION</th>
                                            <th>ARCHIVOS</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1;$color='';$estado='';?>
                                        @foreach($gestiones_disr as $k=>$v)
                                                @if($v->observacion_negociacion_especial!='')
                                                    <?php $color='style=background-color:#fffca9';?>
                                                @endif
                                                @if($v->id_estado_gestion==2)
                                                    <?php $color='';?>
                                                @endif
                                                @if($v->id_estado_gestion==3)
                                                    <?php $color='style=background-color:#ffe4bd';?>
                                                    <?php $estado='PENDIENTE TLC';?>
                                                @endif
                                                @if($v->id_estado_gestion==4)
                                                    <?php $color='style=background-color:#c5fdc8';?>
                                                    <?php $estado='PENDIENTE DINERS';?>
                                                @endif
                                                @if($v->id_estado_gestion==5)
                                                    <?php $color='style=background-color:#dfe1f3';?>
                                                    <?php $estado='PENDIENTE CEX';?>
                                                @endif
                                                <tr {{$color}}>
                                                    <td>{{$v->id}}</td>
                                                    <td><input type="checkbox" name="gestion[]" value="{{$v->id}}"> </td>
                                                    <td><strong>{{$estado}}</strong></td>
                                                    <td><strong>{{$v->mensaje}}</strong></td>
                                                    <td align="left"><button type="button" class="modalButton" data-toggle="modal" data-src="{{url('ver/'.$v->id)}}"  data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen=""><?php echo str_replace ( '//' , '<br>- ' , $v->observacion );?></button></td>
                                                    <td>{{$v->created_at}}</td>
                                                    <td>{{$v->marca}}</td>
                                                    <td>{{$v->gestor}}</td>
                                                    <td>{{$v->cod_encargado}}</td>
                                                    <td>{{$v->ciclo}}</td>
                                                    <td>{{$v->cuenta}}</td>
                                                    <td>{{$v->digitos_cedula}}</td>
                                                    <td>{{$v->nombre_cliente}}</td>
                                                    <td>{{$v->tipo_cuenta}}</td>
                                                    <td>{{$v->observacion_negociacion_especial}}</td>
                                                    <td>
                                                        <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get(); $contador=0;?>
                                                        @foreach($archivos as $k)
                                                            <br><span class="label label-primary" style="font-size: 10px"><span class="glyphicon glyphicon-paperclip"></span> <a href="/download/{{$k->id}}" >{{$k->nombre}}</a></span>
                                                            <?php $contador=1;?>
                                                        @endforeach
                                                        @if($contador>0)
                                                            <br><span class="label label-danger" style="font-size: 10px"><span class="glyphicon glyphicon-compressed"></span> <a href="/comprimir/{{$v->id}}" >Descargar comprimido</a></span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab2_dis">
                                <div>
                                    <span class="glyphicon glyphicon-star" aria-hidden="true"></span> <strong>SELECCIONAR TODOS</strong> <input type="checkbox" onclick="marcar(this.parentNode,this)" />
                                    <table id="discover" class="display" cellspacing="0" >
                                        <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>-</th>
                                            <th>ESTADO</th>
                                            <th>MENSAJE</th>
                                            <th>OBSERVACIONES</th>
                                            <th>FECHA SOLICITUD</th>
                                            <th>MARCA</th>
                                            <th>DIGITOS CÉDULA</th>
                                            <th>NOMBRE DEL CLIENTE</th>
                                            <th>PLAZO</th>
                                            <th>CICLO</th>
                                            <th>TIPO DE REFINANCIACION</th>
                                            <th>ZONA</th>
                                            <th>GESTOR</th>
                                            <th>ARCHIVOS</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php $i=1;$color='';$estado='';?>
                                        @foreach($gestiones_dis as $k=>$v)
                                            @if($v->id_estado_gestion==2)
                                                <?php $color='';?>
                                            @endif
                                            @if($v->id_estado_gestion==3)
                                                <?php $color='style=background-color:#ffe4bd';?>
                                                <?php $estado='PENDIENTE TLC';?>
                                            @endif
                                            @if($v->id_estado_gestion==4)
                                                <?php $color='style=background-color:#c5fdc8';?>
                                                <?php $estado='PENDIENTE DINERS';?>
                                            @endif
                                            @if($v->id_estado_gestion==5)
                                                <?php $color='style=background-color:#dfe1f3';?>
                                                <?php $estado='PENDIENTE CEX';?>
                                            @endif
                                            <tr {{$color}}>
                                                <td>{{$v->id}}</td>
                                                <td><input type="checkbox" name="gestion[]" value="{{$v->id}}"> </td>
                                                <td><strong>{{$estado}}</strong></td>
                                                <td><strong>{{$v->mensaje}}</strong></td>
                                                <td align="left"><button type="button" class="modalButton" data-toggle="modal" data-src="{{url('ver/'.$v->id)}}"  data-width="570" data-height="281" data-target="#myModal" data-video-fullscreen=""><?php echo str_replace ( '//' , '<br>- ' , $v->observacion );?></button></td>
                                                <td>{{$v->created_at}}</td>
                                                <td>{{$v->marca}}</td>
                                                <td>{{$v->digitos_cedula}}</td>
                                                <td>{{$v->nombre_cliente}}</td>
                                                <td>{{$v->plazo}}</td>
                                                <td>{{$v->ciclo}}</td>
                                                <td>{{$v->tipo_de_refinanciacion}}</td>
                                                <td>{{$v->zona}}</td>
                                                <td>{{$v->gestor}}</td>
                                                <td>
                                                    <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get(); $contador=0;?>
                                                    @foreach($archivos as $k)
                                                        <br><span class="label label-primary" style="font-size: 10px"><span class="glyphicon glyphicon-paperclip"></span> <a href="/download/{{$k->id}}" >{{$k->nombre}}</a></span>
                                                        <?php $contador=1;?>
                                                    @endforeach
                                                    @if($contador>0)
                                                        <br><span class="label label-danger" style="font-size: 10px"><span class="glyphicon glyphicon-compressed"></span> <a href="/comprimir/{{$v->id}}" >Descargar comprimido</a></span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="tarjeta" value='DISCOVER'>
                        <input type="hidden" name="formato" value='rotativo'>
                        OBSERVACIÓN:
                        <textarea class="form-control" required name="observaciondis" id="observaciondis"></textarea><br>
                        <button type="submit" class="btn btn-default" value="descarga" name="descarga">
                            <span class="glyphicon glyphicon-download-alt"></span> DESCARGAR LISTA
                        </button>
                        <input type="hidden" name="tarjeta" value='DISCOVER'>
                        <button type="submit" class="btn btn-primary" style="background-color: #3a77bf; border-color: #000; color: #fff;" name="estado" value="6" onclick="confirmarDiscover()">
                            <span class="glyphicon glyphicon-ok"></span> APROBADOS
                        </button>
                        <button type="submit" class="btn btn-danger" style="border-color: #000; color: #fff;" name="estado" value="7" onclick="confirmarDiscover()">
                            <span class="glyphicon glyphicon-remove"></span> RECHAZADOS
                        </button>
                        <button type="submit" class="btn btn-primary" style="background-color: #4caf50; border-color: #000; color: #fff;" name="estado" value="4" onclick="confirmarDiscover()">
                            <span class="glyphicon glyphicon-asterisk"></span> PENDIENTES DINERS
                        </button>
                        <button type="submit" class="btn btn-danger" style="background-color: #e28803; border-color: #000; color: #fff;" name="estado" value="4" onclick="confirmarDiscover()">
                            <span class="glyphicon glyphicon-asterisk"></span> PENDIENTES TLC
                        </button>
                        <button type="submit" class="btn btn-danger" style="background-color: #7176b1; border-color: #000; color: #fff;" name="estado" value="5" onclick="confirmarDiscover()">
                            <span class="glyphicon glyphicon-asterisk"></span> PENDIENTES CEX
                        </button>
                        <input type="hidden" name="formato" id="formato_dis" value="rotativo">
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            console.log ( $(e.target).attr('aria-controls') );

            if($(e.target).attr('aria-controls')=='tab1_v'){document.getElementById('formato_v').value='rotativo';}
            if($(e.target).attr('aria-controls')=='tab2_v'){document.getElementById('formato_v').value='refinanciamiento';}

            if($(e.target).attr('aria-controls')=='tab1_dis'){document.getElementById('formato_dis').value='rotativo';}
            if($(e.target).attr('aria-controls')=='tab2_dis'){document.getElementById('formato_dis').value='refinanciamiento';}

            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    });
    function comprimir(id){
        alert(id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ url('/comprimir?id=') }}'+id,
            method: 'get',
            data:id,
            dataType: 'json',
            beforeSend: function () {
                $("#resultado").html("Procesando, espere por favor...");
            },

            success: function(result) {
                var url = URL.createObjectURL(result);
                var $a = $('<a />', {
                    'href': url,
                    'download': 'document.zip',
                    'text': "click"
                }).hide().appendTo("body")[0].click();
                // URL.revokeObjectURL(url);
            }
        });
    }

    function confirmarDiners(){
        var result = confirm("Seguro que desea continuar?");
        if (result==true) {
            if(document.getElementById('observaciondin').value==''){
                alert('Favor ingresar una observación');
            }
        }else{
            return false;
        }
    }
    function confirmarVisa(){
        var result = confirm("Seguro que desea continuar?");
        if (result==true) {
            if(document.getElementById('observacionv').value==''){
                alert('Favor ingresar una observación');
            }
        }else{
            return false;
        }
    }
    function confirmarDiscover(){
        var result = confirm("Seguro que desea continuar?");
        if (result==true) {
            if(document.getElementById('observaciondis').value==''){
                alert('Favor ingresar una observación');
            }
        }else{
            return false;
        }
    }

    ( function($) {

        function iframeModalOpen(){


            $('.modalButton').on('click', function(e) {
                var src = $(this).attr('data-src');
                var width = $(this).attr('data-width') || 640;
                var height = $(this).attr('data-height') || 360;

                var allowfullscreen = $(this).attr('data-video-fullscreen');


                $("#myModal iframe").attr({
                    'src': src,
                    'height': height,
                    'width': width,
                    'allowfullscreen':''
                });
            });


            $('#myModal').on('hidden.bs.modal', function(){
                $(this).find('iframe').html("");
                $(this).find('iframe').attr("src", "");
            });
        }

        $(document).ready(function(){
            iframeModalOpen();
        });

    } ) ( jQuery );
</script>
@endsection