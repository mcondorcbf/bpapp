<?php
use App\tbl_archivos as archivos;
?>@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src="/js/datatables.min.js"></script>
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
                "scrollX": true
            } );

            $('#visa').DataTable( {
                "scrollX": true
            } );
            $('#visar').DataTable( {
                "scrollX": true
            } );
            $('#discover').DataTable( {
                "scrollX": true
            } );
            $('#discoverr').DataTable( {
                "scrollX": true
            } );
        } );
    </script>



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
                        <div class="panel-heading " style="background-color: #3a77bf; border-color: #000; color: #fff;">CONSOLIDADO DINERS</div>
                        <form class="form-horizontal panel panel-default" role="form" method="post" action="{{ url('/consolidarTarjetas') }}">
                        <?php $estado='';?>
                        {{ csrf_field() }}
                        <!-- Nav tabs -->
                            <table id="diners" class="display" cellspacing="0" >
                                <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>ESTADO</th>
                                    <th>MENSAJE</th>
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

                                <?php $i=1;?>
                                @if(count($gestiones_d)>0)
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
                                    @if($v->id_estado_gestion==6)
                                        <?php $color='style=background-color:#3a77bf';?>
                                        <?php $estado='PENDIENTE CEX';?>
                                    @endif
                                    <tr {{$color}}>
                                        <td>{{$v->id}}</td>
                                        <td><strong>{{$estado}}</strong></td>
                                        <td>{{$v->mensaje}}</td>
                                        <td align="left"><?php echo str_replace ( '//' , '<br>- ' , $v->observacion );?></td>
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
                                            <?php $archivos=archivos::select('ruta','nombre')->where('id_gestion',$v->id)->get();
                                            if (count($archivos)>0){
                                                echo '<strong><a href="'.url('/comprimir?id=').$v->id.'" style="color:#337ab7">* Descargar todo</a></strong><br>';
                                                foreach ($archivos as $l)
                                                {
                                                    echo '<a href="'.$l->ruta.'/'.$l->nombre.'" target="_blank" style="color:#000">'.'-'.$l->nombre.'</a><br>';
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                            <input type="hidden" name="tarjeta" value='DINERS CLUB'>
                            <button type="submit" class="btn btn-primary" style="background-color: #3a77bf; border-color: #000; color: #fff;">
                                GENERAR CONSOLIDADO DINERS CLUB
                            </button>
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
                                    <table id="visar" class="display" cellspacing="0" >
                                        <thead>
                                        <tr>
                                            <th>N°</th>
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
                                        <?php $i=1;?>
                                        @if(count($gestiones_vr)>0)
                                        @foreach($gestiones_vr as $k=>$v)
                                            @if($v->observacion_negociacion_especial!='')
                                                <tr style="background-color: #f5cccc;">
                                            @else
                                                <tr>
                                                @endif
                                                    <td>{{$i++}}</td>
                                                    <td><strong>{{$estado}}</strong></td>
                                                    <td>{{$v->mensaje}}</td>
                                                    <td align="left"><?php echo str_replace ( '//' , '<br>- ' , $v->observacion );?></td>
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
                                                        <?php $archivos=archivos::select('ruta','nombre')->where('id_gestion',$v->id)->get();
                                                        if (count($archivos)>0){
                                                            echo '<strong><a href="'.url('/comprimir?id=').$v->id.'" style="color:#337ab7">* Descargar todo</a></strong><br>';
                                                            foreach ($archivos as $l){
                                                                echo '<a href="'.$l->ruta.'/'.$l->nombre.'" target="_blank" style="color:#000">'.'-'.$l->nombre.'</a><br>';
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab2_v">
                                    <table id="visa" class="display" cellspacing="0" >
                                        <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>ESTADO</th>
                                            <th>MENSAJE</th>
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
                                        <?php $i=1;?>
                                        @if(count($gestiones_v)>0)
                                        @foreach($gestiones_v as $k=>$v)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td><strong>{{$estado}}</strong></td>
                                                <td>{{$v->mensaje}}</td>
                                                <td align="left"><?php echo str_replace ( '//' , '<br>- ' , $v->observacion );?></td>
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
                                                    <?php $archivos=archivos::select('ruta','nombre')->where('id_gestion',$v->id)->get();
                                                    if (count($archivos)>0){
                                                        echo '<strong><a href="'.url('/comprimir?id=').$v->id.'" style="color:#337ab7">* Descargar todo</a></strong><br>';
                                                        foreach ($archivos as $l){
                                                            echo '<a href="'.$l->ruta.'/'.$l->nombre.'" target="_blank" style="color:#000">'.'-'.$l->nombre.'</a><br>';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <input type="hidden" name="tarjeta" value='VISA'>
                            <button type="submit" class="btn btn-primary" style="background-color: #777; border-color: #000; color: #fff;">
                                GENERAR CONSOLIDADO VISA
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
                                    <table id="discoverr" class="display" cellspacing="0" >
                                        <thead>
                                        <tr>
                                            <th>N°</th>
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
                                        <?php $i=1;?>
                                        @if(count($gestiones_disr)>0)
                                        @foreach($gestiones_disr as $k=>$v)
                                            @if($v->observacion_negociacion_especial!='')
                                                <tr style="background-color: #f5cccc;">
                                            @else
                                                <tr>
                                                    @endif
                                                    <td>{{$i++}}</td>
                                                    <td><strong>{{$estado}}</strong></td>
                                                    <td>{{$v->mensaje}}</td>
                                                    <td align="left"><?php echo str_replace ( '//' , '<br>- ' , $v->observacion );?></td>
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
                                                        <?php $archivos=archivos::select('ruta','nombre')->where('id_gestion',$v->id)->get();
                                                        if (count($archivos)>0){
                                                            echo '<strong><a href="'.url('/comprimir?id=').$v->id.'" style="color:#337ab7">* Descargar todo</a></strong><br>';
                                                            foreach ($archivos as $l){
                                                                echo '<a href="'.$l->ruta.'/'.$l->nombre.'" target="_blank" style="color:#000">'.'-'.$l->nombre.'</a><br>';
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab2_dis">
                                    <table id="discover" class="display" cellspacing="0" >
                                        <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>ESTADO</th>
                                            <th>MENSAJE</th>
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
                                        <?php $i=1;?>
                                        @if(count($gestiones_dis)>0)
                                        @foreach($gestiones_dis as $k=>$v)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td><strong>{{$estado}}</strong></td>
                                                <td>{{$v->mensaje}}</td>
                                                <td align="left"><?php echo str_replace ( '//' , '<br>- ' , $v->observacion );?></td>
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
                                                    <?php $archivos=archivos::select('ruta','nombre')->where('id_gestion',$v->id)->get();
                                                    if (count($archivos)>0){
                                                        echo '<strong><a href="'.url('/comprimir?id=').$v->id.'" style="color:#337ab7">* Descargar todo</a></strong><br>';
                                                        foreach ($archivos as $l){
                                                            echo '<a href="'.$l->ruta.'/'.$l->nombre.'" target="_blank" style="color:#000">'.'-'.$l->nombre.'</a><br>';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <input type="hidden" name="tarjeta" value='DISCOVER'>
                            <input type="hidden" name="formato" value='rotativo'>
                            <button type="submit" class="btn btn-primary" style="background-color: #ff9a22; border-color: #000; color: #fff;">
                                GENERAR CONSOLIDADO DISCOVER
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
    </script>
@endsection