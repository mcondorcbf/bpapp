@extends('layouts.app')
@section('scripts')
    <script src="/js/app.js"></script>
@endsection
@section('content')
<style type="text/css">
    .fdiners{
        background-color:#b9d9ff ;
    }
    .fvisa{
        background-color:#efefef ;
    }
    .fdiscover{
        background-color:#ffedd9 ;
    }
    .DINERSCLUB{
        background-color:#b9d9ff ;
        font-weight: bold;
    }
    .VISAINTERDIN{
        background-color:#efefef ;
        font-weight: bold;
    }
    .DISCOVER{
        background-color:#ffedd9 ;
        font-weight: bold;
    }
    .hidden{
        visibility: hidden;
    }
    .hr {border: 1px solid #c4c4c4;
        height: 2px;
        width: 100%;}
</style>
<div class="row">
        <div class="panel panel-default">

            <div class="panel-heading" style="margin-left: 15px">
                <a href="{{url('/home')}}" style="font-size: 14px" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span><strong> Nueva Búsqueda</strong></a>
            </div>

                @if(count($proceso_d)>0)
                    <div class="col-lg-4 col-xs-12 successMessagesDINERSCLUB">
                    <div class="panel-heading" style="background-color: #3a77bf; border-color: #000; color: #fff;"><strong>CONSOLIDADO DINERS REFINACIAMIENTO</strong></div>
                    <table id="diners" class="table table-hover " cellspacing="0" >
                        <tbody>
                            <tr><th>FECHA SOLICITUD</th><td>{{$proceso_d['fecha_solicitud'] or ''}}</td></tr>
                            <tr class="fdiners"><th>MARCA</th><td>{{$proceso_d['marca'] or ''}}</td></tr>
                            <tr><th>COD MOTIVO</th><td>{{$proceso_d['cod_motivo'] or ''}}</td></tr>
                            <tr class="fdiners"><th>MOTIVO DE NO PAGO</th><td>{{$proceso_d['motivo_de_no_pago'] or ''}}</td></tr>
                            <tr><th>EMPRESA EXTERNA</th><td>{{$proceso_d['empresa_externa'] or ''}}</td></tr>
                            <tr class="fdiners"><th>OFICIAL RESPONSABLE</th><td>{{$proceso_d['oficial_responsable'] or ''}}</td></tr>
                            <tr><th>COD ENCARGADO</th><td>{{$proceso_d['cod_encargado'] or ''}}</td></tr>
                            <tr class="fdiners"><th>TIPO</th><td>{{$proceso_d['tipo'] or ''}}</td></tr>
                            <tr><th>DIGITOS CÉDULA</th><td>{{$proceso_d['digitos_cedula'] or ''}}</td></tr>
                            <tr class="fdiners"><th>VALIDA CI</th><td>{{$proceso_d['valida_ci'] or ''}}</td></tr>
                            <tr><th>NOMBRE DEL CLIENTE</th><td>{{$proceso_d['nombre_del_cliente'] or ''}}</td></tr>
                            <tr class="fdiners"><th>PLAZO</th><td>{{$proceso_d['plazo'] or ''}}</td></tr>
                            <tr><th>CICLO</th><td>{{$proceso_d['ciclo'] or ''}}</td></tr>
                            <tr class="fdiners"><th>CONSOLIDACION</th><td>{{$proceso_d['consolidacion'] or ''}}</td></tr>
                            <tr><th>OBSERVACIONES CONSOLIDACION</th><td>{{$proceso_d['observaciones_consolidacion'] or ''}}</td></tr>
                            <tr class="fdiners"><th>TIPO DE REFINANCIACION</th><td>{{$proceso_d['tipo_de_refinanciacion'] or ''}}</td></tr>
                            <tr><th>CIUDAD</th><td>{{$proceso_d['ciudad'] or ''}}</td></tr>
                            <tr class="fdiners"><th>ZONA</th><td>{{$proceso_d['zona'] or ''}}</td></tr>
                            <tr><th>ESTADO CIVIL</th><td>{{$proceso_d['estado_civil'] or ''}}</td></tr>
                            <tr class="fdiners"><th>CI CONYUGE</th><td>{{$proceso_d['ci_conyugue'] or ''}}</td></tr>
                            <tr><th>NOMBRE CONYUGE</th><td>{{$proceso_d['nombre_conyugue'] or ''}}</td></tr>
                            <tr class="fdiners"><th>VALIDACION CONYUGE</th><td>{{$proceso_d['validacion_conyugue'] or ''}}</td></tr>
                            <tr><th>VALOR DEUDA A REF</th><td>{{$proceso_d['valor_deuda_ref'] or ''}}</td></tr>
                            <tr class="fdiners"><th>FIRMA DOCUMENTOS</th><td>{{$proceso_d['firma_documentos'] or ''}}</td></tr>
                            <tr><th>OBSERVACION EXCEPCION</th><td>{{$proceso_d['observacion_excepcion'] or ''}}</td></tr>
                            <tr class="fdiners"><th>VALOR ABONO MISMO DIA DEL CORTE</th><td>{{$proceso_d['valor_abono_mismo_dia_del_corte'] or ''}}</td></tr>
                            <tr><th>DIRECCION NEG. CON FIRMA DOCUMENTOS</th><td>{{$proceso_d['direccion_neg_con_firma_documentos'] or ''}}</td></tr>
                            <tr class="fdiners"><th>TELEFONOS</th><td>{{$proceso_d['telefonos_c'] or ''}}</td></tr>
                            <tr><th>GESTOR</th><td>{{$proceso_d['gestor'] or ''}}</td></tr>
                            <tr class="fdiners"><th>CAMPAÑA</th><td>{{$proceso_d['campania'] or ''}}</td></tr>
                            <tr><th>GRABACION</th><td>{{$proceso_d['grabacion'] or ''}}</td></tr>
                            <tr class="fdiners"><th>DEBITO AUTOMÁTICO</th><td>{{$proceso_d['debito_automatico'] or ''}}</td></tr>
                            <tr><th>INGRESOS REALES</th><td>{{$proceso_d['ingresos_reales'] or ''}}</td></tr>
                            <tr><th>GASTOS REALES</th><td>{{$proceso_d['gastos_reales'] or ''}}</td></tr>
                            <tr class="fdiners"><th>ACTIVIDAD ECONOMICA</th><td>{{$proceso_d['actividad_economica'] or ''}}</td></tr>
                            @if($proceso_d['observacion_negociacion_especial'])
                                <tr style="background-color:#ffb9b9;"><th>OBSERVACION</th><td>{{$proceso_d['observacion_negociacion_especial'] or ''}}</td>
                            @endif
                        </tbody>
                    </table>
                    <form class="form-horizontal" name="guardarN" role="form" method="post">
                        {{ csrf_field() }}
                        <input class="hidden" type="checkbox" id="tarjeta" value='diners'>
                        <input type="hidden" name="proceso_d" value='{{serialize($proceso_d)}}'>


                        <div type="submit" class="btn btn-success col-md-12 col-lg-12 col-xs-12" id="submitform">
                            <strong>GENERAR FICHA DINERS</strong> <input type="checkbox" id="ficha_chk" name="ficha_chk" onchange="generarFichas()" <?php if ($proceso_d['ficha']==1){echo 'checked';}?>>
                        </div>
                        <div>
                            <label class="col-md-2 col-lg-2 col-xs-2">Motivo: </label>
                            <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="motivo" name="motivo" style="height:100px" required <?php if ($proceso_d['ficha']==''){echo 'disabled';}?>></textarea></label>
                        </div>
                        <div>
                            <label class="col-md-2 col-lg-2 col-xs-2">Recomendación: </label>
                            <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="recomendacion" name="recomendacion" style="height:100px" required <?php if ($proceso_d['ficha']==''){echo 'disabled';}?>></textarea></label>
                        </div>

                        <div>
                            <label class="col-md-2 col-lg-2 col-xs-2"> </label>
                            <div class="col-md-10 col-lg-10 col-xs-10 ">
                                <label><input type="radio" name="rrecomendacion" id="rrecomendacion0" value="NO BOLETIN" <?php if ($proceso_d['ficha']==''){echo 'disabled';}?>>NO BOLETIN</label>
                                <label><input type="radio" name="rrecomendacion" id="rrecomendacion1" value="CX87" <?php if ($proceso_d['ficha']==''){echo 'disabled';}?>>CX87</label>
                                <label><input type="radio" name="rrecomendacion" id="rrecomendacion2" value="CX86" <?php if ($proceso_d['ficha']==''){echo 'disabled';}?>>CX86</label>
                                <label><input type="radio" name="rrecomendacion" id="rrecomendacion3" value="LINEA DE CRÉDITO" <?php if ($proceso_d['ficha']==''){echo 'disabled';}?>>LINEA DE CRÉDITO</label>
                                <label><input type="radio" name="rrecomendacion" id="rrecomendacion4" value="ATM Y REFERENCIALES" <?php if ($proceso_d['ficha']==''){echo 'disabled';}?>>ATM Y REFERENCIALES</label>
                            </div>
                            <center>
                                <button id="verficha" name="verficha" class="btn btn-primary" value="DINERS" disabled onclick="descargar()"><span class="glyphicon glyphicon-list-alt"></span> <strong>VER FICHA</strong></button>
                            </center>
                        </div>
                        <hr class="hr">
                        <div>
                            <label class="col-md-2 col-lg-2 col-xs-2">Mensaje: </label>
                            <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="mensaje" name="mensaje" style="height:100px"></textarea></label>
                        </div>
                        <div>
                            <label class="col-md-2 col-lg-2 col-xs-2">Archivos: </label>
                            <label for="filestyle-2" class="btn btn-default col-md-10 col-lg-10 col-xs-10">
                                <span class="icon-span-filestyle glyphicon glyphicon-folder-open"></span>
                                <input type="file"  multiple="multiple" id="archivos" name="archivos">
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary col-md-12 col-lg-12 col-xs-12" onclick="guardar()">
                            <strong>GUARDAR NEGOCIACION DINERS</strong>
                        </button>
                    </form>
                </div>
                @endif

                @if(count($proceso_v)>0)
                    @if($proceso_v['formato_consolidado']==1)
                        <div class="col-lg-4 col-xs-12 successMessagesVISAINTERDIN">
                            <div class="panel-heading" style="background-color: #777; border-color: #000; color: #fff;"><strong>CONSOLIDADO VISA REFINANCIAMIENTO</strong></div>
                            <table id="visa" class="table table-hover " cellspacing="0" >
                                <tbody>
                                <tr><th>FECHA SOLICITUD</th><td>{{$proceso_v['fecha_solicitud'] or ''}}</td></tr>
                                <tr class="fvisa"><th>MARCA</th><td>{{$proceso_v['marca'] or ''}}</td></tr>
                                <tr><th>COD MOTIVO</th><td>{{$proceso_v['cod_motivo'] or ''}}</td></tr>
                                <tr class="fvisa"><th>MOTIVO DE NO PAGO</th><td>{{$proceso_v['motivo_de_no_pago'] or ''}}</td></tr>
                                <tr><th>EMPRESA EXTERNA</th><td>{{$proceso_v['empresa_externa'] or ''}}</td></tr>
                                <tr class="fvisa"><th>OFICIAL RESPONSABLE</th><td>{{$proceso_v['oficial_responsable'] or ''}}</td></tr>
                                <tr><th>COD ENCARGADO</th><td>{{$proceso_v['cod_encargado'] or ''}}</td></tr>
                                <tr class="fvisa"><th>TIPO</th><td>{{$proceso_v['tipo'] or ''}}</td></tr>
                                <tr><th>DIGITOS CÉDULA</th><td>{{$proceso_v['digitos_cedula'] or ''}}</td></tr>
                                <tr class="fvisa"><th>VALIDA CI</th><td>{{$proceso_v['valida_ci'] or ''}}</td></tr>
                                <tr><th>NOMBRE DEL CLIENTE</th><td>{{$proceso_v['nombre_del_cliente'] or ''}}</td></tr>
                                <tr class="fvisa"><th>PLAZO</th><td>{{$proceso_v['plazo'] or ''}}</td></tr>
                                <tr><th>CICLO</th><td>{{$proceso_v['ciclo'] or ''}}</td></tr>
                                <tr class="fvisa"><th>CONSOLIDACION</th><td>{{$proceso_v['consolidacion'] or ''}}</td></tr>
                                <tr><th>OBSERVACIONES CONSOLIDACION</th><td>{{$proceso_v['observaciones_consolidacion'] or ''}}</td></tr>
                                <tr class="fvisa"><th>TIPO DE REFINANCIACION</th><td>{{$proceso_v['tipo_de_refinanciacion'] or ''}}</td></tr>
                                <tr><th>CIUDAD</th><td>{{$proceso_v['ciudad'] or ''}}</td></tr>
                                <tr class="fvisa"><th>ZONA</th><td>{{$proceso_v['zona'] or ''}}</td></tr>
                                <tr><th>ESTADO CIVIL</th><td>{{$proceso_v['estado_civil'] or ''}}</td></tr>
                                <tr class="fvisa"><th>CI CONYUGE</th><td>{{$proceso_v['ci_conyugue'] or ''}}</td></tr>
                                <tr><th>NOMBRE CONYUGE</th><td>{{$proceso_v['nombre_conyugue'] or ''}}</td></tr>
                                <tr class="fvisa"><th>VALIDACION CONYUGE</th><td>{{$proceso_v['validacion_conyugue'] or ''}}</td></tr>
                                <tr><th>VALOR DEUDA A REF</th><td>{{$proceso_v['valor_deuda_ref'] or ''}}</td></tr>
                                <tr class="fvisa"><th>FIRMA DOCUMENTOS</th><td>{{$proceso_v['firma_documentos'] or ''}}</td></tr>
                                <tr><th>OBSERVACION EXCEPCION</th><td>{{$proceso_v['observacion_excepcion'] or ''}}</td></tr>
                                <tr class="fvisa"><th>VALOR ABONO MISMO DIA DEL CORTE</th><td>{{$proceso_v['valor_abono_mismo_dia_del_corte'] or ''}}</td></tr>
                                <tr><th>DIRECCION NEG. CON FIRMA DOCUMENTOS</th><td>{{$proceso_v['direccion_neg_con_firma_documentos'] or ''}}</td></tr>
                                <tr class="fvisa"><th>TELEFONOS</th><td>{{$proceso_v['telefonos_c'] or ''}}</td></tr>
                                <tr><th>GESTOR</th><td>{{$proceso_v['gestor'] or ''}}</td></tr>
                                <tr class="fvisa"><th>CAMPAÑA</th><td>{{$proceso_v['campania'] or ''}}</td></tr>
                                <tr><th>GRABACION</th><td>{{$proceso_v['grabacion'] or ''}}</td></tr>
                                <tr class="fvisa"><th>DEBITO AUTOMÁTICO</th><td>{{$proceso_v['debito_automatico'] or ''}}</td></tr>
                                <tr><th>INGRESOS REALES</th><td>{{$proceso_v['ingresos_reales'] or ''}}</td></tr>
                                <tr><th>GASTOS REALES</th><td>{{$proceso_v['gastos_reales'] or ''}}</td></tr>
                                <tr class="fvisa"><th>ACTIVIDAD ECONOMICA</th><td>{{$proceso_v['actividad_economica'] or ''}}</td></tr>
                                @if($proceso_v['observacion_negociacion_especial'])
                                    <tr style="background-color:#ffb9b9;"><th>OBSERVACION</th><td>{{$proceso_v['observacion_negociacion_especial'] or ''}}</td>
                                @endif
                                </tbody>
                            </table>
                            <form class="form-horizontal" name="guardarN" role="form" method="post">
                                {{ csrf_field() }}
                                <input class="hidden" type="checkbox" id="tarjeta" value='visa'>
                                <input type="hidden" name="proceso_v" value='{{serialize($proceso_v)}}'>

                                <div type="submit" class="btn btn-success col-md-12 col-lg-12 col-xs-12" id="submitform">
                                    <strong>GENERAR FICHA VISA</strong> <input type="checkbox" id="ficha_chk_v" name="ficha_chk_v" onchange="generarFichas_v()" <?php if ($proceso_v['ficha']==''){echo 'checked';}?>>
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Motivo: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="motivo_v" name="motivo_v" style="height:100px" required <?php if ($proceso_v['ficha']==''){echo 'disabled';}?>></textarea></label>
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Recomendación: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="recomendacion_v" name="recomendacion_v" style="height:100px" required <?php if ($proceso_v['ficha']==''){echo 'disabled';}?>></textarea></label>
                                </div>

                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2"> </label>
                                    <div class="col-md-10 col-lg-10 col-xs-10 ">
                                        <label><input type="radio" name="rrecomendacion_v" id="rrecomendacion_v0" value="NO BOLETIN" <?php if ($proceso_v['ficha']==''){echo 'disabled';}?>>NO BOLETIN</label>
                                        <label><input type="radio" name="rrecomendacion_v" id="rrecomendacion_v1" value="CX87" <?php if ($proceso_v['ficha']==''){echo 'disabled';}?>>CX87</label>
                                        <label><input type="radio" name="rrecomendacion_v" id="rrecomendacion_v2" value="CX86" <?php if ($proceso_v['ficha']==''){echo 'disabled';}?>>CX86</label>
                                        <label><input type="radio" name="rrecomendacion_v" id="rrecomendacion_v3" value="LINEA DE CRÉDITO" <?php if ($proceso_v['ficha']==''){echo 'disabled';}?>>LINEA DE CRÉDITO</label>
                                        <label><input type="radio" name="rrecomendacion_v" id="rrecomendacion_v4" value="ATM Y REFERENCIALES" <?php if ($proceso_v['ficha']==''){echo 'disabled';}?>>ATM Y REFERENCIALES</label>
                                    </div>
                                    <center>
                                        <button id="verficha_v" name="verficha" class="btn btn-primary" value="VISA" disabled onclick="descargar_v()"><span class="glyphicon glyphicon-list-alt"></span> <strong>VER FICHA</strong></button>
                                    </center>
                                </div>
                                <hr class="hr">
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Mensaje: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="mensaje_v" name="mensaje_v" style="height:100px"></textarea></label>
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Archivos: </label>
                                    <label for="filestyle-2" class="btn btn-default col-md-10 col-lg-10 col-xs-10">
                                        <span class="icon-span-filestyle glyphicon glyphicon-folder-open"></span>
                                        <input type="file"  multiple="multiple" id="archivos_v" name="archivos">
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary col-md-12 col-lg-12 col-xs-12" style="background-color: #777777; color: #fff;" onclick="guardar_v()">
                                    <strong>GUARDAR NEGOCIACION VISA</strong>
                                </button>
                            </form>
                        </div>
                    @endif
                    @if($proceso_v['formato_consolidado']==2)
                        <div class="col-lg-4 col-xs-12 successMessagesVISAINTERDIN">
                            <div class="panel-heading" style="background-color: #777; border-color: #000; color: #fff;"><strong>CONSOLIDADO VISA ROTATIVO</strong></div>
                            <table id="visa" class="table table-hover " cellspacing="0" >
                                <tbody>
                                <tr><th>FECHA SOLICITUD</th><td>{{$proceso_v['fecha_solicitud'] or ''}}</td></tr>
                                <tr class="fvisa"><th>MARCA</th><td>{{$proceso_v['marca'] or ''}}</td></tr>
                                <tr><th>USUARIO SOLICITA</th><td>{{$proceso_v['cod_encargado'] or ''}}</td></tr>
                                <tr class="fvisa"><th>CORTE</th><td>{{$proceso_v['ciclo'] or ''}}</td></tr>
                                <tr><th>CUENTA</th><td>{{$proceso_v['cuenta'] or ''}}</td></tr>
                                <tr class="fvisa"><th>CEDULA</th><td>{{$proceso_v['digitos_cedula'] or ''}}</td></tr>
                                <tr><th>NOMBRE</th><td>{{$proceso_v['nombre_del_cliente'] or ''}}</td></tr>
                                <tr class="fvisa"><th>STS CANC ACTUAL</th><td>{{$proceso_v['codigo_cancelacion'] or ''}}</td></tr>
                                <tr><th>EDAD REAL</th><td>{{$proceso_v['edad_cartera'] or ''}}</td></tr>
                                <tr class="fvisa"><th>STS CANC SOLICITADO</th><td>{{$proceso_v['codigo_de_cancelacion_solicitado'] or ''}}</td></tr>
                                <tr><th>SOLICITA CAMBIO FORMA DE PAGO A MINIMO</th><td></td></tr>
                                <tr class="fvisa"><th>PRECANCELACION DIFERIDO</th><td>SI</td></tr>
                                <tr><th>VALOR PAGO EXIGIBLE</th><td>{{$proceso_v['valor_pago_exigible'] or ''}}</td></tr>
                                <tr class="fvisa"><th>VALOR ABONO</th><td>{{$proceso_v['valor_abono'] or ''}}</td></tr>
                                <tr><th>VALOR CREDITO</th><td>{{$proceso_v['valor_credito'] or ''}}</td></tr>
                                <tr class="fvisa"><th>VALOR DEBITO</th><td>{{$proceso_v['valor_debito'] or ''}}</td></tr>
                                <tr><th>CUPO</th><td> </td></tr>
                                <tr class="fvisa"><th>TOTAL RIESGO DEUDA</th><td>{{$proceso_v['total_riesgo_deuda'] or ''}}</td></tr>
                                <tr><th>DEBITO AUTOMATICO</th><td>{{$proceso_v['debito_automatico'] or ''}}</td></tr>
                                <tr class="fvisa"><th>TIPO DE CUENTA NORMAL/ESPECIAL</th><td> </td></tr>
                                <tr><th>INGRESOS REALES</th><td>{{$proceso_v['ingresos_reales']}}</td></tr>
                                <tr class="fvisa"><th>TIPO DE TRABAJO (FIJO / TEMPORAL / SIN TRABAJO)</th><td>{{$proceso_v['actividad_economica']}}</td></tr>
                                <tr><th>VALOR ABONO MISMO DIA DEL CORTE</th><td>{{$proceso_v['valor_abono_mismo_dia_del_corte'] or ''}}</td></tr>
                                @if($proceso_v['observacion_negociacion_especial'])
                                    <tr style="background-color:#ffb9b9;"><th>OBSERVACION</th><td>{{$proceso_v['observacion_negociacion_especial'] or ''}}</td>
                                @endif
                                </tbody>
                            </table>
                            <form class="form-horizontal" name="guardarN" role="form" method="post">
                                {{ csrf_field() }}
                                <input class="hidden" type="checkbox" id="tarjeta" value='visa'>
                                <input type="hidden" name="proceso_v" value='{{serialize($proceso_v)}}'>

                                <div type="submit" class="btn btn-success col-md-12 col-lg-12 col-xs-12" id="submitform">
                                    <strong>GENERAR FICHA VISA</strong> <input type="checkbox" id="ficha_chk_v" name="ficha_chk_v" onchange="generarFichas_v()">
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Motivo: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="motivo_v" name="motivo_v" style="height:100px" required disabled></textarea></label>
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Recomendación: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="recomendacion_v" name="recomendacion_v" style="height:100px" required disabled></textarea></label>
                                </div>

                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2"> </label>
                                    <div class="col-md-10 col-lg-10 col-xs-10 ">
                                        <label><input type="radio" name="rrecomendacion_v" id="rrecomendacion_v0" value="NO BOLETIN" disabled>NO BOLETIN</label>
                                        <label><input type="radio" name="rrecomendacion_v" id="rrecomendacion_v1" value="CX87" disabled>CX87</label>
                                        <label><input type="radio" name="rrecomendacion_v" id="rrecomendacion_v2" value="CX86" disabled>CX86</label>
                                        <label><input type="radio" name="rrecomendacion_v" id="rrecomendacion_v3" value="LINEA DE CRÉDITO" disabled>LINEA DE CRÉDITO</label>
                                        <label><input type="radio" name="rrecomendacion_v" id="rrecomendacion_v4" value="ATM Y REFERENCIALES" disabled>ATM Y REFERENCIALES</label>
                                    </div>
                                    <center>
                                        <button id="verficha_v" name="verficha" class="btn btn-primary" disabled onclick="descargar_v()"><span class="glyphicon glyphicon-list-alt"></span> <strong>VER FICHA</strong></button>
                                    </center>
                                </div>
                                <hr class="hr">
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Mensaje: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="mensaje_v" name="mensaje_v" style="height:100px"></textarea></label>
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Archivos: </label>
                                    <label for="filestyle-2" class="btn btn-default col-md-10 col-lg-10 col-xs-10">
                                        <span class="icon-span-filestyle glyphicon glyphicon-folder-open"></span>
                                        <input type="file"  multiple="multiple" id="archivos_v" name="archivos">
                                    </label>
                                </div>

                                <button type="submit" class="btn col-md-12 col-lg-12 col-xs-12" id="submitform" style="background-color: #777777; color: #fff;" onclick="guardar_v()">
                                    <strong>GUARDAR NEGOCIACION VISA</strong>
                                </button>
                            </form>
                            </div>
                    @endif
                @endif

                @if(count($proceso_dis)>0)
                    @if($proceso_dis['formato_consolidado']==1)
                        <div class="col-lg-4 col-xs-12 successMessagesDISCOVER">
                            <div class="panel-heading" style="background-color: #ff9a22; border-color: #000; color: #fff;"><strong>CONSOLIDADO DISCOVER REFINANCIAMIENTO</strong></div>
                            <table id="discover" class="table table-hover " cellspacing="0" >
                                <tbody>
                                <tr><th>FECHA SOLICITUD</th><td>{{$proceso_dis['fecha_solicitud'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>MARCA</th><td>{{$proceso_dis['marca'] or ''}}</td></tr>
                                <tr><th>COD MOTIVO</th><td>{{$proceso_dis['cod_motivo'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>MOTIVO DE NO PAGO</th><td>{{$proceso_dis['motivo_de_no_pago'] or ''}}</td></tr>
                                <tr><th>EMPRESA EXTERNA</th><td>{{$proceso_dis['empresa_externa'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>OFICIAL RESPONSABLE</th><td>{{$proceso_dis['oficial_responsable'] or ''}}</td></tr>
                                <tr><th>COD ENCARGADO</th><td>{{$proceso_dis['cod_encargado'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>TIPO</th><td>{{$proceso_dis['tipo'] or ''}}</td></tr>
                                <tr><th>DIGITOS CÉDULA</th><td>{{$proceso_dis['digitos_cedula'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>VALIDA CI</th><td>{{$proceso_dis['valida_ci'] or ''}}</td></tr>
                                <tr><th>NOMBRE DEL CLIENTE</th><td>{{$proceso_dis['nombre_del_cliente'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>PLAZO</th><td>{{$proceso_dis['plazo'] or ''}}</td></tr>
                                <tr><th>CICLO</th><td>{{$proceso_dis['ciclo'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>CONSOLIDACION</th><td>{{$proceso_dis['consolidacion'] or ''}}</td></tr>
                                <tr><th>OBSERVACIONES CONSOLIDACION</th><td>{{$proceso_dis['observaciones_consolidacion'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>TIPO DE REFINANCIACION</th><td>{{$proceso_dis['tipo_de_refinanciacion'] or ''}}</td></tr>
                                <tr><th>CIUDAD</th><td>{{$proceso_dis['ciudad'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>ZONA</th><td>{{$proceso_dis['zona'] or ''}}</td></tr>
                                <tr><th>ESTADO CIVIL</th><td>{{$proceso_dis['estado_civil'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>CI CONYUGE</th><td>{{$proceso_dis['ci_conyugue'] or ''}}</td></tr>
                                <tr><th>NOMBRE CONYUGE</th><td>{{$proceso_dis['nombre_conyugue'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>VALIDACION CONYUGE</th><td>{{$proceso_dis['validacion_conyugue'] or ''}}</td></tr>
                                <tr><th>VALOR DEUDA A REF</th><td>{{$proceso_dis['valor_deuda_ref'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>FIRMA DOCUMENTOS</th><td>{{$proceso_dis['firma_documentos'] or ''}}</td></tr>
                                <tr><th>OBSERVACION EXCEPCION</th><td>{{$proceso_dis['observacion_excepcion'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>VALOR ABONO MISMO DIA DEL CORTE</th><td>{{$proceso_dis['valor_abono_mismo_dia_del_corte'] or ''}}</td></tr>
                                <tr><th>DIRECCION NEG. CON FIRMA DOCUMENTOS</th><td>{{$proceso_dis['direccion_neg_con_firma_documentos'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>TELEFONOS</th><td>{{$proceso_dis['telefonos_c'] or ''}}</td></tr>
                                <tr><th>GESTOR</th><td>{{$proceso_dis['gestor'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>CAMPAÑA</th><td>{{$proceso_dis['campania'] or ''}}</td></tr>
                                <tr><th>GRABACION</th><td>{{$proceso_dis['grabacion'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>DEBITO AUTOMÁTICO</th><td>{{$proceso_dis['debito_automatico'] or ''}}</td></tr>
                                <tr><th>INGRESOS REALES</th><td>{{$proceso_dis['ingresos_reales'] or ''}}</td></tr>
                                <tr><th>GASTOS REALES</th><td>{{$proceso_dis['gastos_reales'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>ACTIVIDAD ECONOMICA</th><td>{{$proceso_dis['actividad_economica'] or ''}}</td></tr>
                                @if($proceso_dis['observacion_negociacion_especial'])
                                    <tr style="background-color:#ffb9b9;"><th>OBSERVACION</th><td>{{$proceso_dis['observacion_negociacion_especial'] or ''}}</td>
                                @endif
                                </tbody>
                            </table>
                            <form class="form-horizontal" name="guardarN" role="form" method="post">
                                {{ csrf_field() }}
                                <input class="hidden" type="checkbox" id="tarjeta" value='discover'>
                                <input type="hidden" name="proceso_dis" value='{{serialize($proceso_dis)}}'>

                                <div type="submit" class="btn btn-success col-md-12 col-lg-12 col-xs-12" id="submitform">
                                    <strong>GENERAR FICHA DISCOVER</strong> <input type="checkbox" id="ficha_chk_dis" name="ficha_chk_dis" onchange="generarFichas_dis()">
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Motivo: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="motivo_dis" name="motivo_dis" style="height:100px" required disabled></textarea></label>
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Recomendación: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="recomendacion_dis" name="recomendacion_dis" style="height:100px" required disabled></textarea></label>
                                </div>

                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2"> </label>
                                    <div class="col-md-10 col-lg-10 col-xs-10 ">
                                        <label><input type="radio" name="rrecomendacion_dis" id="rrecomendacion_dis0" value="NO BOLETIN" disabled>NO BOLETIN</label>
                                        <label><input type="radio" name="rrecomendacion_dis" id="rrecomendacion_dis1" value="CX87" disabled>CX87</label>
                                        <label><input type="radio" name="rrecomendacion_dis" id="rrecomendacion_dis2" value="CX86" disabled>CX86</label>
                                        <label><input type="radio" name="rrecomendacion_dis" id="rrecomendacion_dis3" value="LINEA DE CRÉDITO" disabled>LINEA DE CRÉDITO</label>
                                        <label><input type="radio" name="rrecomendacion_dis" id="rrecomendacion_dis4" value="ATM Y REFERENCIALES" disabled>ATM Y REFERENCIALES</label>
                                    </div>
                                    <center>
                                        <button id="verficha_dis" name="verficha" class="btn btn-primary" disabled onclick="descargar_dis()"><span class="glyphicon glyphicon-list-alt"></span> <strong>VER FICHA</strong></button>
                                    </center>
                                </div>
                                <hr class="hr">
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Mensaje: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="mensaje_dis" name="mensaje_dis" style="height:100px"></textarea></label>
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Archivos: </label>
                                    <label for="filestyle-2" class="btn btn-default col-md-10 col-lg-10 col-xs-10">
                                        <span class="icon-span-filestyle glyphicon glyphicon-folder-open"></span>
                                        <input type="file"  multiple="multiple" id="archivos_dis" name="archivos">
                                    </label>
                                </div>

                                <button type="submit" class="btn col-md-12 col-lg-12 col-xs-12" style="background-color: #ff9a22; color: #fff;" onclick="guardar_dis()">
                                    <strong>GUARDAR NEGOCIACION DISCOVER</strong>
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($proceso_dis['formato_consolidado']==2)
                        <div class="col-lg-4 col-xs-12 successMessagesDISCOVER">
                            <div class="panel-heading" style="background-color: #ff9a22; border-color: #000; color: #fff;"><strong>CONSOLIDADO DISCOVER ROTATIVO</strong></div>
                            <table id="discover" class="table table-hover " cellspacing="0" >
                                <tbody>
                                <tr><th>FECHA SOLICITUD</th><td>{{$proceso_dis['fecha_solicitud'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>MARCA</th><td>{{$proceso_dis['marca'] or ''}}</td></tr>
                                <tr><th>USUARIO SOLICITA</th><td>{{$proceso_dis['cod_encargado'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>CORTE</th><td>{{$proceso_dis['ciclo'] or ''}}</td></tr>
                                <tr><th>CUENTA</th><td>{{$proceso_dis['cuenta'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>CEDULA</th><td>{{$proceso_dis['digitos_cedula'] or ''}}</td></tr>
                                <tr><th>NOMBRE</th><td>{{$proceso_dis['nombre_del_cliente'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>STS CANC ACTUAL</th><td>{{$proceso_dis['codigo_cancelacion'] or ''}}</td></tr>
                                <tr><th>EDAD REAL</th><td>{{$proceso_dis['edad_cartera'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>STS CANC SOLICITADO</th><td>{{$proceso_dis['codigo_de_cancelacion_solicitado'] or ''}}</td></tr>
                                <tr><th>SOLICITA CAMBIO FORMA DE PAGO A MINIMO</th><td></td></tr>
                                <tr class="fdiscover"><th>PRECANCELACION DIFERIDO</th><td>SI</td></tr>
                                <tr><th>VALOR PAGO EXIGIBLE</th><td>{{$proceso_dis['valor_pago_exigible'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>VALOR ABONO</th><td>{{$proceso_dis['valor_abono'] or ''}}</td></tr>
                                <tr><th>VALOR CREDITO</th><td>{{$proceso_dis['valor_credito'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>VALOR DEBITO</th><td>{{$proceso_dis['valor_debito'] or ''}}</td></tr>
                                <tr><th>CUPO</th><td> </td></tr>
                                <tr class="fdiscover"><th>TOTAL RIESGO DEUDA</th><td>{{$proceso_dis['total_riesgo_deuda'] or ''}}</td></tr>
                                <tr><th>DEBITO AUTOMATICO</th><td>{{$proceso_dis['debito_automatico'] or ''}}</td></tr>
                                <tr class="fdiscover"><th>TIPO DE CUENTA NORMAL/ESPECIAL</th><td>{{$proceso_dis['tipo_cuenta']}}</td></tr>
                                <tr><th>INGRESOS REALES</th><td>{{$proceso_dis['ingresos_reales']}}</td></tr>
                                <tr class="fdiscover"><th>TIPO DE TRABAJO (FIJO / TEMPORAL / SIN TRABAJO)</th><td>{{$proceso_dis['actividad_economica']}}</td></tr>
                                <tr><th>VALOR ABONO MISMO DIA DEL CORTE</th><td>{{$proceso_dis['valor_abono_mismo_dia_del_corte'] or ''}}</td></tr>
                                @if($proceso_dis['observacion_negociacion_especial'])
                                    <tr style="background-color:#ffb9b9;"><th>OBSERVACION</th><td>{{$proceso_dis['observacion_negociacion_especial'] or ''}}</td>
                                @endif
                                </tbody>
                            </table>
                            <form class="form-horizontal" name="guardarN" role="form" method="post">
                                {{ csrf_field() }}
                                <input class="hidden" type="checkbox" id="tarjeta" value='discover'>
                                <input type="hidden" name="proceso_dis" value='{{serialize($proceso_dis)}}'>

                                <div type="submit" class="btn btn-success col-md-12 col-lg-12 col-xs-12" id="submitform">
                                    <strong>GENERAR FICHA DISCOVER</strong> <input type="checkbox" id="ficha_chk_dis" name="ficha_chk_dis" onchange="generarFichas_dis()">
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Motivo: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="motivo_dis" name="motivo_dis" style="height:100px" required disabled></textarea></label>
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Recomendación: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="recomendacion_dis" name="recomendacion_dis" style="height:100px" required disabled></textarea></label>
                                </div>

                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2"> </label>
                                    <div class="col-md-10 col-lg-10 col-xs-10 ">
                                        <label><input type="radio" name="rrecomendacion_dis" id="rrecomendacion_dis0" value="NO BOLETIN" disabled>NO BOLETIN</label>
                                        <label><input type="radio" name="rrecomendacion_dis" id="rrecomendacion_dis1" value="CX87" disabled>CX87</label>
                                        <label><input type="radio" name="rrecomendacion_dis" id="rrecomendacion_dis2" value="CX86" disabled>CX86</label>
                                        <label><input type="radio" name="rrecomendacion_dis" id="rrecomendacion_dis3" value="LINEA DE CRÉDITO" disabled>LINEA DE CRÉDITO</label>
                                        <label><input type="radio" name="rrecomendacion_dis" id="rrecomendacion_dis4" value="ATM Y REFERENCIALES" disabled>ATM Y REFERENCIALES</label>
                                    </div>
                                    <center>
                                        <button id="verficha_dis" name="verficha" class="btn btn-primary" onclick="descargar_dis()" disabled><span class="glyphicon glyphicon-list-alt"></span> <strong>VER FICHA</strong></button>
                                    </center>
                                </div>
                                <hr class="hr">
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Mensaje: </label>
                                    <label class="col-md-10 col-lg-10 col-xs-10"><textarea class="form-control" id="mensaje_dis" name="mensaje_dis" style="height:100px"></textarea></label>
                                </div>
                                <div>
                                    <label class="col-md-2 col-lg-2 col-xs-2">Archivos: </label>
                                    <label for="filestyle-2" class="btn btn-default col-md-10 col-lg-10 col-xs-10">
                                        <span class="icon-span-filestyle glyphicon glyphicon-folder-open"></span>
                                        <input type="file"  multiple="multiple" id="archivos_dis" name="archivos">
                                    </label>
                                </div>

                                <button type="submit" class="btn col-md-12 col-lg-12 col-xs-12" id="submitform" style="background-color: #ff9a22; color: #fff;" onclick="guardar_dis()">
                                    <strong>GUARDAR NEGOCIACION DISCOVER</strong>
                                </button>
                            </form>
                        </div>
                    @endif
                @endif


        </div>
</div>
<div class="errorMessages"></div>
<script>

    $(function() {
        $("form[name=guardarN]").on("submit", function(e)
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var archivos = '';//Inicializo la variable archivos donde vamos a guardar como objetos los archivos adjuntos
            var motivo = '';
            var recomendacion = '';
            var mensaje = '';
            var verficha = '';

            if($(this).find('input[type=checkbox]').val()=='diners'){
                archivos = document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
                motivo = document.getElementById("motivo").value;
                recomendacion = document.getElementById("recomendacion").value;
                mensaje = document.getElementById("mensaje").value;
                verficha = document.getElementById("verficha").value
            }

            if($(this).find('input[type=checkbox]').val()=='visa'){
                archivos = document.getElementById("archivos_v");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos_v'
                motivo = document.getElementById("motivo_v").value;
                recomendacion = document.getElementById("recomendacion_v").value;
                mensaje = document.getElementById("mensaje_v").value;
                verficha = document.getElementById("verficha_v").value
            }

            if($(this).find('input[type=checkbox]').val()=='discover'){
                archivos = document.getElementById("archivos_dis");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos_dis'
                motivo = document.getElementById("motivo_dis").value;
                recomendacion = document.getElementById("recomendacion_dis").value;
                mensaje = document.getElementById("mensaje_dis").value;
                verficha = document.getElementById("verficha_dis").value
            }

            var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
            //Creamos una instancia del Objeto FormData.
            var archivos = new FormData();

            /* Como son multiples archivos creamos un ciclo for que recorra la el arreglo de los archivos seleccionados en el input
             Este y añadimos cada elemento al formulario FormData en forma de arreglo, utilizando la variable i (autoincremental) como
             indice para cada archivo, si no hacemos esto, los valores del arreglo se sobre escriben*/
            for(i=0; i<archivo.length; i++){
                archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
            }

            var d = $(this);//Selecciono en una variable todo el formulario
            // Encontramos el botón Enviar del formulario al que le hicimos click
            for (var i = 0; i < (d.find('input').not('input[type=file]').not('input[type=submit]').not('input[type=checkbox]').not('input[type=radio]').length); i++) {
                // buscará todos los input menos el valor "file" y "sumbit . Serán diferenciados en el PHP gracias al "name" de cada uno.
                archivos.append( (d.find('input').not('input[type=file]').not('input[type=submit]').not('input[type=checkbox]').not('input[type=checkbox]').eq(i).attr("name")),(d.find('input').not('input[type=file]').not('input[type=submit]').not('input[type=checkbox]').not('input[type=checkbox]').eq(i).val()));
                archivos.append( (d.find('input[type=checkbox]').eq(i).attr("name")),(d.find('input[type=checkbox]').eq(i).is(":checked")));
            }
            for (var i = 0; i < (d.find('input[type=radio]').length); i++) {
                if(d.find('input[type=radio]').eq(i).is(":checked")){
                    archivos.append('rrecomendacion',(d.find('input[type=radio]').eq(i).val()));
                }
            }

            archivos.append('motivo',motivo);
            archivos.append('recomendacion',recomendacion);
            archivos.append('mensaje',mensaje);
            archivos.append('verficha',verficha);

            e.preventDefault();
            e.stopPropagation();
            $.ajax({
                url: '{{ url('/generar') }}',
                method: $(this).attr("method"),
                contentType:false,
                data:archivos,
                processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
                cache:false, //Para que el formulario no guarde cache
                success: function(res)
                {
                    if(res.message)
                    {
                        clearMessages();
                        console.log('Mensaje: '+res.message);

                        var html = "<div class='alert alert-info "+ res.css +"'>";
                        html+="<p>" + res.message + "</p>";
                        html += "</div>";

                        $(".successMessages"+res.css).html(html);

                        $("form[name=guardarN]")[0].reset();

                    }else{
                        var a = document.createElement("a");
                        a.href = res.file;
                        a.download = 'ficha.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    if(jqXHR)
                    {
                        clearMessages();
                        var errors = jqXHR.responseJSON;
                        var html = "<div class='alert alert-danger'>";
                        for(error in errors)
                        {
                            html+="<p>" + errors[error] + "</p>";
                        }
                        alert('Ocurrió un error!');
                        html += "<strong>OCURRIÓ UN ERROR!</strong></div>";
                        $(".errorMessages").html(html);
                    }
                }
            })
        })
    });

    function clearMessages()
    {
        $(".errorMessages").html('');
        $(".successMessages").html('');
    }

    function generarFichas() {
        var ficha_chk = document.getElementById('ficha_chk').checked;
        if (ficha_chk == true) {
            document.getElementById('verficha').disabled = false;
            document.getElementById('motivo').disabled = false;
            document.getElementById('recomendacion').disabled = false;
            document.getElementById('recomendacion').value = "Por lo  manifestación de compromiso y voluntad de pago  por parte  el socio se sugiere  se proceda con la reestructuración    para otorgar una mayor facilidad en el cumplimiento de los pagos de la tarjeta Diners, se recomienda mantener Cx86.";

            for (i = 0; i <= 4; i++) {
                document.getElementById('rrecomendacion' + i).disabled = false;
            }
        } else {
            document.getElementById('verficha').disabled = true;
            document.getElementById('motivo').disabled = true;
            document.getElementById('recomendacion').disabled = true;
            document.getElementById('recomendacion').value = "";
            for (i = 0; i <= 4; i++) {
                document.getElementById('rrecomendacion' + i).checked = false;
                document.getElementById('rrecomendacion' + i).disabled = true;
            }
        }
    }

    function generarFichas_v() {
        var ficha_chk_v = document.getElementById('ficha_chk_v').checked;
        if (ficha_chk_v == true) {
            document.getElementById('verficha_v').disabled = false;
            document.getElementById('motivo_v').disabled = false;
            document.getElementById('recomendacion_v').disabled = false;
            document.getElementById('recomendacion_v').value = "Por lo  manifestación de compromiso y voluntad de pago  por parte  el socio se sugiere  se proceda con la reestructuración    para otorgar una mayor facilidad en el cumplimiento de los pagos de la tarjeta Diners, se recomienda mantener Cx86.";
            for (i = 0; i <= 4; i++) {
                document.getElementById('rrecomendacion_v' + i).disabled = false;
            }
        } else {
            document.getElementById('verficha_v').disabled = true;
            document.getElementById('motivo_v').disabled = true;
            document.getElementById('recomendacion_v').disabled = true;
            document.getElementById('recomendacion_v').value = "";
            for (i = 0; i <= 4; i++) {
                document.getElementById('rrecomendacion_v' + i).checked = false;
                document.getElementById('rrecomendacion_v' + i).disabled = true;
            }
        }
    }

    function generarFichas_dis(){
        var ficha_chk_dis=document.getElementById('ficha_chk_dis').checked;
        if (ficha_chk_dis==true) {
            document.getElementById('verficha_dis').disabled = false;
            document.getElementById('motivo_dis').disabled = false;
            document.getElementById('recomendacion_dis').disabled = false;
            document.getElementById('recomendacion_dis').value = "Por lo  manifestación de compromiso y voluntad de pago  por parte  el socio se sugiere  se proceda con la reestructuración    para otorgar una mayor facilidad en el cumplimiento de los pagos de la tarjeta Diners, se recomienda mantener Cx86.";
            for (i=0; i<=4; i++){
                document.getElementById('rrecomendacion_dis'+i).disabled = false;
            }
        }else{
            document.getElementById('verficha_dis').disabled = true;
            document.getElementById('verficha_dis').value='';
            document.getElementById('motivo_dis').disabled = true;
            document.getElementById('recomendacion_dis').disabled = true;
            document.getElementById('recomendacion_dis').value = "";
            for (i=0; i<=4; i++){
                document.getElementById('rrecomendacion_dis'+i).checked = false;
                document.getElementById('rrecomendacion_dis'+i).disabled = true;
            }
        }
    }

    function descargar(){
        document.getElementById('verficha').value='DINERS';
    }
    function descargar_v(){
        document.getElementById('verficha_v').value='VISA';
    }
    function descargar_dis(){
        document.getElementById('verficha_dis').value='DISCOVER';
    }
    function guardar(){
        document.getElementById('verficha').value='GUARDAR';
    }
    function guardar_v(){
        document.getElementById('verficha_v').value='GUARDAR';
    }
    function guardar_dis(){
        document.getElementById('verficha_dis').value='GUARDAR';
    }
</script>
@endsection