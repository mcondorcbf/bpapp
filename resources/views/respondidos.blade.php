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

    <h1 align="center"> RESPONDIDOS </h1>
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
                                        <th>ESTADO</th>
                                        <th>OBSERVACIONES</th>
                                        <th>FECHA SOLICITUD</th>
                                        <th>MARCA</th>
                                        <th>COD MOTIVO</th>
                                        <th>MOTIVO DE NO PAGO</th>
                                        <th>EMPRESA EXTERNA</th>
                                        <th>OFICIAL RESPONSABLE</th>
                                        <th>COD ENCARGADO</th>
                                        <th>TIPO</th>
                                        <th>DIGITOS CÉDULA</th>
                                        <th>VALIDA CI</th>
                                        <th>NOMBRE DEL CLIENTE</th>
                                        <th>PLAZO</th>
                                        <th>CICLO</th>
                                        <th>CONSOLIDACION</th>
                                        <th>OBSERVACIONES CONSOLIDACION</th>
                                        <th>TIPO DE REFINANCIACION</th>
                                        <th>CIUDAD</th>
                                        <th>ZONA</th>
                                        <th>ESTADO CIVIL</th>
                                        <th>CI CONYUGE</th>
                                        <th>NOMBRE CONYUGE</th>
                                        <th>VALIDACION CONYUGE</th>
                                        <th>VALOR DEUDA A REF</th>
                                        <th>FIRMA DOCUMENTOS</th>
                                        <th>OBSERVACION EXCEPCION</th>
                                        <th>VALOR ABONO MISMO DIA DEL CORTE</th>
                                        <th>DIRECCION NEG. CON FIRMA DOCUMENTOS</th>
                                        <th>TELEFONOS</th>
                                        <th>GESTOR</th>
                                        <th>CAMPAÑA</th>
                                        <th>GRABACION</th>
                                        <th>DEBITO AUTOMÁTICO</th>
                                        <th>INGRESOS REALES</th>
                                        <th>ACTIVIDAD ECONOMICA</th>
                                        <th>ARCHIVOS</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php $i=1;$color='';$estado='';?>
                                    @foreach($gestiones_d as $k=>$v)
                                        @if($v->id_estado_gestion==6)
                                            <?php $color='style=background-color:#d2e7ff';?>
                                            <?php $estado='APROBADO';?>
                                        @endif
                                        @if($v->id_estado_gestion==7)
                                            <?php $color='style=background-color:#ffe0df';?>
                                            <?php $estado='RECHAZADO';?>
                                        @endif
                                        @if($v->id_estado_gestion==8)
                                            <?php $color='';?>
                                            <?php $estado='DUPLICADO';?>
                                        @endif
                                        <tr {{$color}}>
                                            <td>{{$v->id}}</td>
                                            <td><strong>{{$estado}}</strong></td>
                                            <td>{{$v->observacion}}</td>
                                            <td>{{$v->fecha_solicitud}}</td>
                                            <td>{{$v->marca}}</td>
                                            <td>{{$v->cod_motivo}}</td>
                                            <td>{{$v->motivo_no_pago}}</td>
                                            <td>{{$v->empresa_externa}}</td>
                                            <td>{{$v->oficial_responsable}}</td>
                                            <td>{{$v->cod_encargado}}</td>
                                            <td>{{$v->tipo}}</td>
                                            <td>{{$v->digitos_cedula}}</td>
                                            <td>{{$v->valida_ci}}</td>
                                            <td>{{$v->nombre_cliente}}</td>
                                            <td>{{$v->plazo}}</td>
                                            <td>{{$v->ciclo}}</td>
                                            <td>{{$v->consolidacion}}</td>
                                            <td>{{$v->observaciones_consolidacion}}</td>
                                            <td>{{$v->tipo_de_refinanciacion}}</td>
                                            <td>{{$v->ciudad}}</td>
                                            <td>{{$v->zona}}</td>
                                            <td>{{$v->estado_civil}}</td>
                                            <td>{{$v->ci_conyugue}}</td>
                                            <td>{{$v->nombre_conyugue}}</td>
                                            <td>{{$v->validacion_conyugue}}</td>
                                            <td>{{$v->valor_deuda_a_ref}}</td>
                                            <td>{{$v->firma_documentos}}</td>
                                            <td>{{$v->observaciones_excepcion}}</td>
                                            <td>{{$v->valor_abono_mismo_dia_del_corte}}</td>
                                            <td>{{$v->direccion_neg_con_firma_documentos}}</td>
                                            <td>{{$v->telefonos}}</td>
                                            <td>{{$v->gestor}}</td>
                                            <td>{{$v->campana}}</td>
                                            <td>{{$v->grabacion}}</td>
                                            <td>{{$v->debito_automatico}}</td>
                                            <td>{{$v->ingresos_reales}}</td>
                                            <td>{{$v->actividad_economica}}</td>
                                            <td>
                                                <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get();?>
                                                @foreach($archivos as $k)
                                                    <a href="/download/{{$k->id}}" class="btn-xs btn-success">{{$k->nombre}}</a>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="visaconsolidado">
                    <div class="col-md-12 col-lg-12">

                        <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/consolidarTarjetas') }}">
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
                                                <th>ESTADO</th>
                                                <th>OBSERVACIONES</th>
                                                <th>FECHA SOLICITUD</th>
                                                <th>MARCA</th>
                                                <th>GESTOR</th>
                                                <th>USUARIO SOLICITA</th>
                                                <th>CORTE</th>
                                                <th>CUENTA</th>
                                                <th>CEDULA</th>
                                                <th>NOMBRE</th>
                                                <th>STS CANC ACTUAL</th>
                                                <th>EDAD REAL</th>
                                                <th>STS CANC SOLICITADO</th>
                                                <th>SOLICITA CAMBIO FORMA DE PAGO A MINIMO</th>
                                                <th>PRECANCELACION DIFERIDO</th>
                                                <th>VALOR PAGO EXIGIBLE</th>
                                                <th>VALOR ABONO</th>
                                                <th>VALOR CREDITO</th>
                                                <th>VALOR DEBITO</th>
                                                <th>CUPO</th>
                                                <th>TOTAL RIESGO DEUDA</th>
                                                <th>OBSERVACION</th>
                                                <th>ARCHIVOS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i=1;$color='';$estado='';?>
                                            @foreach($gestiones_vr as $k=>$v)
                                                @if($v->id_estado_gestion==6)
                                                    <?php $color='style=background-color:#d2e7ff';?>
                                                    <?php $estado='APROBADO';?>
                                                @endif
                                                @if($v->id_estado_gestion==7)
                                                    <?php $color='style=background-color:#ffe0df';?>
                                                    <?php $estado='RECHAZADO';?>
                                                @endif
                                                    <tr {{$color}}>
                                                        <td>{{$v->id}}</td>
                                                        <td><strong>{{$estado}}</strong></td>
                                                        <td>{{$v->observacion}}</td>
                                                        <td>{{$v->fecha_solicitud}}</td>
                                                        <td>{{$v->marca}}</td>
                                                        <td>{{$v->gestor}}</td>
                                                        <td>{{$v->cod_encargado}}</td>
                                                        <td>{{$v->ciclo}}</td>
                                                        <td>{{$v->cuenta}}</td>
                                                        <td>{{$v->digitos_cedula}}</td>
                                                        <td>{{$v->nombre_cliente}}</td>
                                                        <td>{{$v->sts_canc_actual}}</td>
                                                        <td>{{$v->edad_real}}</td>
                                                        <td>{{$v->sts_canc_solicitado}}</td>
                                                        <td>{{$v->solicita_cambio_forma_de_pago_minimo}}</td>
                                                        <td>{{$v->precancelacion_diferido}}</td>
                                                        <td>{{$v->valor_pago_exigible}}</td>
                                                        <td>{{$v->valor_abono}}</td>
                                                        <td>{{$v->valor_credito}}</td>
                                                        <td>{{$v->valor_debito}}</td>
                                                        <td>{{$v->cupo}}</td>
                                                        <td>{{$v->total_riesgo_deuda}}</td>
                                                        <td>{{$v->observacion_negociacion_especial}}</td>
                                                        <td>
                                                            <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get();?>
                                                            @foreach($archivos as $k)
                                                                <a href="/download/{{$k->id}}" class="btn-xs btn-success">{{$k->nombre}}</a>
                                                            @endforeach
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
                                                <th>ESTADO</th>
                                                <th>OBSERVACIONES</th>
                                                <th>FECHA SOLICITUD</th>
                                                <th>MARCA</th>
                                                <th>COD MOTIVO</th>
                                                <th>MOTIVO DE NO PAGO</th>
                                                <th>EMPRESA EXTERNA</th>
                                                <th>OFICIAL RESPONSABLE</th>
                                                <th>COD ENCARGADO</th>
                                                <th>TIPO</th>
                                                <th>DIGITOS CÉDULA</th>
                                                <th>VALIDA CI</th>
                                                <th>NOMBRE DEL CLIENTE</th>
                                                <th>PLAZO</th>
                                                <th>CICLO</th>
                                                <th>CONSOLIDACION</th>
                                                <th>OBSERVACIONES CONSOLIDACION</th>
                                                <th>TIPO DE REFINANCIACION</th>
                                                <th>CIUDAD</th>
                                                <th>ZONA</th>
                                                <th>ESTADO CIVIL</th>
                                                <th>CI CONYUGE</th>
                                                <th>NOMBRE CONYUGE</th>
                                                <th>VALIDACION CONYUGE</th>
                                                <th>VALOR DEUDA A REF</th>
                                                <th>FIRMA DOCUMENTOS</th>
                                                <th>OBSERVACION EXCEPCION</th>
                                                <th>VALOR ABONO MISMO DIA DEL CORTE</th>
                                                <th>DIRECCION NEG. CON FIRMA DOCUMENTOS</th>
                                                <th>TELEFONOS</th>
                                                <th>GESTOR</th>
                                                <th>CAMPAÑA</th>
                                                <th>GRABACION</th>
                                                <th>DEBITO AUTOMÁTICO</th>
                                                <th>INGRESOS REALES</th>
                                                <th>ACTIVIDAD ECONOMICA</th>
                                                <th>ARCHIVOS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i=1;$color='';$estado='';?>
                                            @foreach($gestiones_v as $k=>$v)
                                                @if($v->id_estado_gestion==6)
                                                    <?php $color='style=background-color:#d2e7ff';?>
                                                    <?php $estado='APROBADO';?>
                                                @endif
                                                @if($v->id_estado_gestion==7)
                                                    <?php $color='style=background-color:#ffe0df';?>
                                                    <?php $estado='RECHAZADO';?>
                                                @endif
                                                <tr {{$color}}>
                                                    <td>{{$v->id}}</td>
                                                    <td><strong>{{$estado}}</strong></td>
                                                    <td>{{$v->observacion}}</td>
                                                    <td>{{$v->fecha_solicitud}}</td>
                                                    <td>{{$v->marca}}</td>
                                                    <td>{{$v->cod_motivo}}</td>
                                                    <td>{{$v->motivo_no_pago}}</td>
                                                    <td>{{$v->empresa_externa}}</td>
                                                    <td>{{$v->oficial_responsable}}</td>
                                                    <td>{{$v->cod_encargado}}</td>
                                                    <td>{{$v->tipo}}</td>
                                                    <td>{{$v->digitos_cedula}}</td>
                                                    <td>{{$v->valida_ci}}</td>
                                                    <td>{{$v->nombre_cliente}}</td>
                                                    <td>{{$v->plazo}}</td>
                                                    <td>{{$v->ciclo}}</td>
                                                    <td>{{$v->consolidacion}}</td>
                                                    <td>{{$v->observaciones_consolidacion}}</td>
                                                    <td>{{$v->tipo_de_refinanciacion}}</td>
                                                    <td>{{$v->ciudad}}</td>
                                                    <td>{{$v->zona}}</td>
                                                    <td>{{$v->estado_civil}}</td>
                                                    <td>{{$v->ci_conyugue}}</td>
                                                    <td>{{$v->nombre_conyugue}}</td>
                                                    <td>{{$v->validacion_conyugue}}</td>
                                                    <td>{{$v->valor_deuda_a_ref}}</td>
                                                    <td>{{$v->firma_documentos}}</td>
                                                    <td>{{$v->observaciones_excepcion}}</td>
                                                    <td>{{$v->valor_abono_mismo_dia_del_corte}}</td>
                                                    <td>{{$v->direccion_neg_con_firma_documentos}}</td>
                                                    <td>{{$v->telefonos}}</td>
                                                    <td>{{$v->gestor}}</td>
                                                    <td>{{$v->campana}}</td>
                                                    <td>{{$v->grabacion}}</td>
                                                    <td>{{$v->debito_automatico}}</td>
                                                    <td>{{$v->ingresos_reales}}</td>
                                                    <td>{{$v->actividad_economica}}</td>
                                                    <td>
                                                        <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get();?>
                                                        @foreach($archivos as $k)
                                                            <a href="/download/{{$k->id}}" class="btn-xs btn-success">{{$k->nombre}}</a>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="tarjeta" value='VISA INTERDIN'>
                            <button type="submit" class="btn btn-primary" style="background-color: #777; border-color: #000; color: #fff;">
                                <span class="glyphicon glyphicon-download-alt"></span> GENERAR CONSOLIDADO VISA
                            </button>
                            <input type="hidden" name="formato" id="formato_v" value="rotativo">
                        </form>

                    </div>
                </div>
                <div class="tab-pane fade" id="discoverconsolidado">
                    <div class="col-md-12 col-lg-12">

                        <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/consolidarTarjetas') }}">
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
                                                <th>ESTADO</th>
                                                <th>OBSERVACIONES</th>
                                                <th>FECHA SOLICITUD</th>
                                                <th>MARCA</th>
                                                <th>GESTOR</th>
                                                <th>USUARIO SOLICITA</th>
                                                <th>CORTE</th>
                                                <th>CUENTA</th>
                                                <th>CEDULA</th>
                                                <th>NOMBRE</th>
                                                <th>STS CANC ACTUAL</th>
                                                <th>EDAD REAL</th>
                                                <th>STS CANC SOLICITADO</th>
                                                <th>SOLICITA CAMBIO FORMA DE PAGO A MINIMO</th>
                                                <th>PRECANCELACION DIFERIDO</th>
                                                <th>VALOR PAGO EXIGIBLE</th>
                                                <th>VALOR ABONO</th>
                                                <th>VALOR CREDITO</th>
                                                <th>VALOR DEBITO</th>
                                                <th>CUPO</th>
                                                <th>TOTAL RIESGO DEUDA</th>
                                                <th>OBSERVACION</th>
                                                <th>ARCHIVOS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i=1;$color='';$estado='';?>
                                            @foreach($gestiones_disr as $k=>$v)
                                                @if($v->id_estado_gestion==6)
                                                    <?php $color='style=background-color:#d2e7ff';?>
                                                    <?php $estado='APROBADO';?>
                                                @endif
                                                @if($v->id_estado_gestion==7)
                                                    <?php $color='style=background-color:#ffe0df';?>
                                                    <?php $estado='RECHAZADO';?>
                                                @endif
                                                    <tr {{$color}}>
                                                        <td>{{$v->id}}</td>
                                                        <td><strong>{{$estado}}</strong></td>
                                                        <td>{{$v->observacion}}</td>
                                                        <td>{{$v->fecha_solicitud}}</td>
                                                        <td>{{$v->marca}}</td>
                                                        <td>{{$v->gestor}}</td>
                                                        <td>{{$v->cod_encargado}}</td>
                                                        <td>{{$v->ciclo}}</td>
                                                        <td>{{$v->cuenta}}</td>
                                                        <td>{{$v->digitos_cedula}}</td>
                                                        <td>{{$v->nombre_cliente}}</td>
                                                        <td>{{$v->sts_canc_actual}}</td>
                                                        <td>{{$v->edad_real}}</td>
                                                        <td>{{$v->sts_canc_solicitado}}</td>
                                                        <td>{{$v->solicita_cambio_forma_de_pago_minimo}}</td>
                                                        <td>{{$v->precancelacion_diferido}}</td>
                                                        <td>{{$v->valor_pago_exigible}}</td>
                                                        <td>{{$v->valor_abono}}</td>
                                                        <td>{{$v->valor_credito}}</td>
                                                        <td>{{$v->valor_debito}}</td>
                                                        <td>{{$v->cupo}}</td>
                                                        <td>{{$v->total_riesgo_deuda}}</td>
                                                        <td>{{$v->observacion_negociacion_especial}}</td>
                                                        <td>
                                                            <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get();?>
                                                            @foreach($archivos as $k)
                                                                <a href="/download/{{$k->id}}" class="btn-xs btn-success">{{$k->nombre}}</a>
                                                            @endforeach
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
                                                <th>ESTADO</th>
                                                <th>OBSERVACIONES</th>
                                                <th>FECHA SOLICITUD</th>
                                                <th>MARCA</th>
                                                <th>COD MOTIVO</th>
                                                <th>MOTIVO DE NO PAGO</th>
                                                <th>EMPRESA EXTERNA</th>
                                                <th>OFICIAL RESPONSABLE</th>
                                                <th>COD ENCARGADO</th>
                                                <th>TIPO</th>
                                                <th>DIGITOS CÉDULA</th>
                                                <th>VALIDA CI</th>
                                                <th>NOMBRE DEL CLIENTE</th>
                                                <th>PLAZO</th>
                                                <th>CICLO</th>
                                                <th>CONSOLIDACION</th>
                                                <th>OBSERVACIONES CONSOLIDACION</th>
                                                <th>TIPO DE REFINANCIACION</th>
                                                <th>CIUDAD</th>
                                                <th>ZONA</th>
                                                <th>ESTADO CIVIL</th>
                                                <th>CI CONYUGE</th>
                                                <th>NOMBRE CONYUGE</th>
                                                <th>VALIDACION CONYUGE</th>
                                                <th>VALOR DEUDA A REF</th>
                                                <th>FIRMA DOCUMENTOS</th>
                                                <th>OBSERVACION EXCEPCION</th>
                                                <th>VALOR ABONO MISMO DIA DEL CORTE</th>
                                                <th>DIRECCION NEG. CON FIRMA DOCUMENTOS</th>
                                                <th>TELEFONOS</th>
                                                <th>GESTOR</th>
                                                <th>CAMPAÑA</th>
                                                <th>GRABACION</th>
                                                <th>DEBITO AUTOMÁTICO</th>
                                                <th>INGRESOS REALES</th>
                                                <th>ACTIVIDAD ECONOMICA</th>
                                                <th>ARCHIVOS</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $i=1;$color='';$estado='';?>
                                            @foreach($gestiones_dis as $k=>$v)
                                                @if($v->id_estado_gestion==6)
                                                    <?php $color='style=background-color:#d2e7ff';?>
                                                    <?php $estado='APROBADO';?>
                                                @endif
                                                @if($v->id_estado_gestion==7)
                                                    <?php $color='style=background-color:#ffe0df';?>
                                                    <?php $estado='RECHAZADO';?>
                                                @endif
                                                <tr {{$color}}>
                                                    <td>{{$v->id}}</td>
                                                    <td><strong>{{$estado}}</strong></td>
                                                    <td>{{$v->observacion}}</td>
                                                    <td>{{$v->fecha_solicitud}}</td>
                                                    <td>{{$v->marca}}</td>
                                                    <td>{{$v->cod_motivo}}</td>
                                                    <td>{{$v->motivo_no_pago}}</td>
                                                    <td>{{$v->empresa_externa}}</td>
                                                    <td>{{$v->oficial_responsable}}</td>
                                                    <td>{{$v->cod_encargado}}</td>
                                                    <td>{{$v->tipo}}</td>
                                                    <td>{{$v->digitos_cedula}}</td>
                                                    <td>{{$v->valida_ci}}</td>
                                                    <td>{{$v->nombre_cliente}}</td>
                                                    <td>{{$v->plazo}}</td>
                                                    <td>{{$v->ciclo}}</td>
                                                    <td>{{$v->consolidacion}}</td>
                                                    <td>{{$v->observaciones_consolidacion}}</td>
                                                    <td>{{$v->tipo_de_refinanciacion}}</td>
                                                    <td>{{$v->ciudad}}</td>
                                                    <td>{{$v->zona}}</td>
                                                    <td>{{$v->estado_civil}}</td>
                                                    <td>{{$v->ci_conyugue}}</td>
                                                    <td>{{$v->nombre_conyugue}}</td>
                                                    <td>{{$v->validacion_conyugue}}</td>
                                                    <td>{{$v->valor_deuda_a_ref}}</td>
                                                    <td>{{$v->firma_documentos}}</td>
                                                    <td>{{$v->observaciones_excepcion}}</td>
                                                    <td>{{$v->valor_abono_mismo_dia_del_corte}}</td>
                                                    <td>{{$v->direccion_neg_con_firma_documentos}}</td>
                                                    <td>{{$v->telefonos}}</td>
                                                    <td>{{$v->gestor}}</td>
                                                    <td>{{$v->campana}}</td>
                                                    <td>{{$v->grabacion}}</td>
                                                    <td>{{$v->debito_automatico}}</td>
                                                    <td>{{$v->ingresos_reales}}</td>
                                                    <td>{{$v->actividad_economica}}</td>
                                                    <td>
                                                        <?php $archivos=archivos::select('id','ruta','nombre')->where('id_gestion',$v->id)->get();?>
                                                        @foreach($archivos as $k)
                                                            <a href="/download/{{$k->id}}" class="btn-xs btn-success">{{$k->nombre}}</a>
                                                        @endforeach
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
                            <button type="submit" class="btn btn-primary" style="background-color: #ff9a22; border-color: #000; color: #fff;">
                                <span class="glyphicon glyphicon-download-alt"></span> GENERAR CONSOLIDADO DISCOVER
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

        function confirmar(){
            var result = confirm("Seguro que desea continuar?");
            if (result==true) {
                if(document.getElementById('observacion').value==''){
                    alert('Favor ingresar una observación');
                }
            }else{
                return false;
            }
        }
    </script>
@endsection