<?php
use App\tbl_archivos as archivos;
?><!-- Styles -->
<link href="/css/app.css" rel="stylesheet">
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css"/>
<style>
    th{
        text-align: right;
    }
</style>
<!-- Scripts -->
<script src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/jquery-1.12.4.js"></script>

<div class="form-group">
    <div class="tab-content tab-content-border">
        <div class="tab-pane fade active in" id="dinersconsolidado">
            <div class="col-md-12 col-lg-12">
                <form class="form-horizontal" role="form" method="post" action="{{ url('/observacion/'.$gestion->id) }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <br>
                        <input name="id" type="hidden" value="{{$gestion->id}}">
                        <label >GESTIÓN: {{$gestion->id}}</label><br>
                        <label >GESTOR: {{$gestion->gestor}}</label><br>
                        <label >OBSERVACIÓN ACTUAL: {{$gestion->observacion}} </label><br>
                        <table class="table table-striped" style="font-size: 11px">
                            <tbody>
                            <?php $i=1;$color='';$estado=''; $v=$gestion;?>
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
                                    <th>ESTADO</th>
                                    <td><strong>{{$estado}}</strong></td>
                                </tr>
                                <tr>
                                    <th>OBSERVACIONES</th>
                                    <td>{{$v->observacion}}</td>
                                </tr>
                                <tr>
                                    <th>FECHA SOLICITUD</th>
                                    <td>{{$v->fecha_solicitud}}</td>
                                </tr>
                                <tr>
                                    <th>MARCA</th>
                                    <td>{{$v->marca}}</td>
                                </tr>
                                <tr>
                                    <th>DIGITOS CÉDULA</th>
                                    <td>{{$v->digitos_cedula}}</td>
                                </tr>
                                <tr>
                                    <th>NOMBRE DEL CLIENTE</th>
                                    <td>{{$v->nombre_cliente}}</td>
                                </tr>
                                <tr>
                                    <th>PLAZO</th>
                                    <td>{{$v->plazo}}</td>
                                </tr>
                                <tr>
                                    <th>CICLO</th>
                                    <td>{{$v->ciclo}}</td>
                                </tr>
                                <tr>
                                    <th>TIPO DE REFINANCIACION</th>
                                    <td>{{$v->tipo_de_refinanciacion}}</td>
                                </tr>
                                <tr>
                                    <th>ZONA</th>
                                    <td>{{$v->zona}}</td>
                                </tr>
                                <tr>
                                    <th>GESTOR</th>
                                    <td>{{$v->gestor}}</td>
                                </tr>
                                <tr>
                                    <th>ARCHIVOS</th>
                                    <td>
                                        <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get();?>
                                        @foreach($archivos as $k)
                                            <a href="/download/{{$k->id}}" class="btn-xs btn-success">{{$k->nombre}}</a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
