@extends('layouts.app')
@section('scripts')
    <script src="/js/app.js"></script>
@endsection
@section('content')
@include('busqueda.scripts')
<div class="container col-xs-12 col-md-12 col-lg-12">
    <div class="row">
        <div >
            <div class="panel panel-default">

            <div class="panel-heading"><a href="{{url('/home')}}" style="font-size: 14px" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span><strong> Nueva Búsqueda</strong></a></div>
            @if($cliente->CEDULA=='SC')
                <div class="panel-body">
                    Cédula no econtrada o gestionada. <br>
                </div>
            @else
                    <form id="formulario" class="form-horizontal" method="post" action="{{ url('/procesar') }}">
                        {{ csrf_field()}}
                        <input type="hidden" name="carga" id="carga" value="{{$cliente->id}}">
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="">

                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#deudor" data-toggle="tab">Datos Deudor</a>
                                        </li>
                                        </li>
                                        <li class=""><a href="#conyugue" data-toggle="tab">Datos Conyugue</a>
                                    </ul>

                                    <div class="tab-content tab-content-border">
                                        <div class="tab-pane fade active in" id="deudor">
                                            <div class="col-md-12 col-xs-12 col-lg-6" style="padding:0">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr style="background-color: #0e3950; color: #fff;">
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size: 14px">
                                                            <strong>Cédula:</strong>
                                                            @if(strlen($cliente->CEDULA)==9 || strlen($cliente->CEDULA)==12)
                                                                0{{$cliente->CEDULA}}
                                                                <input type="hidden" name="cedula" id="cedula" value="0{{$cliente->CEDULA}}">
                                                                || id: {{$cliente->id}}
                                                            @else
                                                                {{$cliente->CEDULA}}
                                                                <input type="hidden" name="cedula" id="cedula" value="{{$cliente->CEDULA}}">
                                                                || id: {{$cliente->id}}
                                                            @endif
                                                            || fecha carga: {{$id_carga->fecha}}
                                                            {{--|| <strong>Tipo:</strong> {{$id_carga->tipo}}--}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Nombres:</strong> {{$cliente->NOMSOC}} <input type="hidden" name="nombres" id="nombres" value="{{$cliente->NOMSOC}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Empresa: </strong>{{$cliente->EMPRESA_SOC}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Dirección: </strong>{{$cliente->DIRECCION}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Email: </strong>{{$cliente->EMAIL}}
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-lg-6" style="padding:0">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr style="background-color: #0e3950; color: #fff;">
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Teléfono 1: </strong>{{$cliente->P1.$cliente->T1}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Teléfono 2: </strong>{{$cliente->P2.$cliente->T2}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Teléfono 3: </strong>{{$cliente->P3.$cliente->T3}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Nombre Ciudad: </strong>{{$cliente->NOMBRE_CIUDAD}}<input type="hidden" name="ciudad" id="ciudad" value="{{$cliente->NOMBRE_CIUDAD}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Zona: </strong>{{$cliente->ZONA}}<input type="hidden" name="zona" id="zona" value="{{$cliente->ZONA}}">
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="conyugue">
                                            <div class="col-md-12 col-xs-12 col-lg-6" style="padding:0">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr style="background-color: #0e3950; color: #fff;">
                                                        <td colspan="2">ACTUALIZACION DE DATOS CONYUGUE</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                <span style="margin-right: 20px"><strong>Estado Civil:</strong>
                                                <select class="form-control" name="estado_civil" id="estado_civil" onchange="estad_civ()" required>
                                                        <option value="0" >SELECCIONE UNO</option>
                                                    @foreach($estado_civil as $k)
                                                        @if($k->descripcion=='SOLTERO/A')
                                                            <option value="{{$k->descripcion}}" selected>{{$k->descripcion}}</option>
                                                        @endif
                                                        <option value="{{$k->descripcion}}">{{$k->descripcion}}</option>
                                                    @endforeach
                                                </select>
                                                </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <span style="margin-right: 20px"><strong>Cédula Conyugue:</strong> <input type="text" class="form-control" name="cedula_conyugue" id="cedula_conyugue" required> </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <span style="margin-right: 20px"><strong>Nombres Conyugue:</strong> <input type="text" class="form-control"  name="nombres_conyugue" id="nombres_conyugue" required> </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <span style="margin-right: 20px"><strong>Excepción Observación:</strong> <input type="text" class="form-control" name="excepcion_firma_conyugue" id="excepcion_firma_conyugue"> </span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-lg-6" style="padding:0">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr style="background-color: #0e3950; color: #fff;">
                                                        <td colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="margin-right: 20px"><strong>Ingresos Reales:</strong> <input type="text" class="form-control" name="ingresos_reales" id="ingresos_reales" required> </span>
                                                        </td>
                                                        <td>
                                                            <span style="margin-right: 20px"><strong>Gastos Reales:</strong> <input type="text" class="form-control" name="gastos_reales" id="gastos_reales" required> </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                <span style="margin-right: 20px"><strong>Actividad Económica:</strong>
                                                    <select class="form-control" name="actividad_economica" id="actividad_economica" required>
                                                                <option value="0">SELECCIONE UNO</option>
                                                        @foreach($actividad_economica as $k)
                                                            <option value="{{$k->descripcion}}">{{$k->descripcion}}</option>
                                                        @endforeach
                                                    </select>
                                                </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <span style="margin-right: 20px"><strong>Dirección de visita para refinanciamiento:</strong> <input type="text" class="form-control" name="direccion_visita_refinanciamiento" id="direccion_visita_refinanciamiento" value="" required> </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <span style="margin-right: 20px"><strong>Teléfonos:</strong> <input type="text" class="form-control" name="telefonos_refinanciamiento" id="telefonos_refinanciamiento" value="" required> </span>
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

                <div class="panel-body" style="padding: 0">
                    <!--Tarjetas de credito -->
                    <div class="panel-group" id="accordion">
                        @if($diners<35)
                        <div class="panel-default col-lg-4" >
                            <div class="panel-heading" style="background-color: #3a77bf; border-color: #000; color: #fff;font-size: 11px;">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" onclick="calcularDiners();">
                                    <h4 class="panel-title">
                                        DINERS<input type="hidden" name="diners" id="diners" value="diners">
                                    </h4>
                                </a>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                <div class="">
                                    <div class="form-group">
                                        <div class="col-md-12 col-xs-12 col-lg-6">
                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                <tbody>
                                                <tr style="background-color: #0e3950; color: #fff;">
                                                    <td colspan="2">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span style="margin-right: 20px"><strong>Ciclo:</strong> {{$cliente->CICLOF}}<input type="hidden" name="ciclo_diners" id="ciclo_diners" value="{{$cliente->CICLOF}}"> </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Edad de cartera: </strong>{{$edad_cartera}}<input type="hidden" name="edad_cartera" id="edad_cartera" value="{{$edad_cartera}}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Código de cancelación: </strong>{{$codigo_cancelacion}}<input type="hidden" name="codigo_cancelacion" id="codigo_cancelacion" value="{{$codigo_cancelacion}}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Código de cancelación actual: </strong>
                                                        <select class="form-control" name="codigo_de_cancelacion_solicitado" id="codigo_de_cancelacion_solicitado" style="background-color: #f2dede">

                                                            @if($codigo_cancelacion==86)
                                                                <option value="86" selected>86</option>
                                                            @endif
                                                            @if($codigo_cancelacion==87 || $codigo_cancelacion==0 || $codigo_cancelacion=='NO SE PUEDE NEGOCIAR, CUENTA EN LEGAL' || $codigo_cancelacion=='')
                                                                <option value="87" selected>87</option>
                                                            @endif
                                                            <option value="SELECCIONE UNO">SELECCIONE UNO</option>
                                                            @foreach($codigo_cancelacion_solicitado as $k)
                                                                <option value="{{$k->descripcion}}">{{$k->descripcion}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Código de boletín:</strong> {{$cod_boletin}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                <tbody>
                                                <tr style="background-color: #0e3950; color: #fff;">
                                                    <td colspan="2">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Débito automático:</strong> {{$cliente->DEBITO_AUT}}<input type="hidden" name="debito_automatico" id="debito_automatico" value="{{$cliente->DEBITO_AUT}}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #86e874;">
                                                        <strong>Promedio de pago:</strong> ${{$cliente->PROM_PAG}}<input type="hidden" name="promedio_de_pago" id="promedio_de_pago" value="{{$cliente->PROM_PAG}}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Score:</strong> {{$cliente->SCORE_DINERS}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                            </div>
                                            <div>
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                <tr style="background-color: #0e3950; color: #fff;">
                                                    <td colspan="2"></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Campaña:</strong> {{$cliente->CAMPAŃA_CON_ECE}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Ejecutivo actual a cargo de cuenta: </strong> {{$cliente->EMPRESA}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Lugar de trabajo:</strong> {{$cliente->EMPRESA_SOC}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Fecha última gestión:</strong> {{$cliente->FECHALLAM}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Motivo de gestión:</strong> {{$cliente->MOTIVO_1}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Descripción de gestión:</strong> {{$cliente->DESCRIPCION}}
                                                    </td>
                                                </tr>
                                                <tr style="background-color: #86e874;">
                                                    <td colspan="2">
                                                        <strong>Observación de gestión:</strong> {{$cliente->OBSERVACION}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Fecha de compromiso:</strong> {{$cliente->FECHACOMPROMISO}}
                                                    </td>
                                                </tr>

                                                <tr style="background-color: #0e3950; color: #fff;">
                                                    <td colspan="2"></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Financiamiento Vigente:</strong> {{$cliente->NOMESTAB}} <input type="hidden" value="{{$cliente->NOMESTAB}}" id="financiamiento_vigente" name="financiamiento_vigente">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Nro Cuotas Pendientes:</strong> {{$cliente->CUOTAS_PTES}} <strong>|</strong> {{$cliente->TOTAL_CUOTAS_REF}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Valor Cuotas Pendientes:</strong> ${{$cliente->VALOR_PEN_REF_VIG}}
                                                    </td>
                                                </tr><tr>
                                                    <td colspan="2">
                                                        <strong>Segunda Reestructuración:</strong> {{$cliente->REESTRUCT_VIGENTE}}<div class="alert alert-warning" name="reestructuracion_vigente" id="reestructuracion_vigente">
                                                            <input type="hidden" value="{{$cliente->REESTRUCT_VIGENTE2}}" id="segunda_reestructuracion_diners" name="segunda_reestructuracion_diners">
                                                            {{$cliente->REESTRUCT_VIGENTE2}}</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Total Riesgo:</strong> ${{$cliente->TRIESGO}}<input type="hidden" id="total_riesgo" name="total_riesgo" value="{{$cliente->TRIESGO}}">
                                                    </td>
                                                </tr>


                                                </tbody>
                                            </table>
                                        </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                            Saldos Facturados
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 90 y más 90 días: </strong> <input type="hidden" id="saldo90ymas_h" name="saldo90ymas_h" value="{{$saldo90ymas}}"> <span id="saldo90ymas">${{$saldo90ymas}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 60 días: </strong> <input type="hidden" id="saldo60dias_h" name="saldo60dias_h" value="{{$cliente->D60_ORIG}}"> $<span id="saldo60dias">{{$cliente->D60_ORIG}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 30 días: </strong> <input type="hidden" id="saldo30dias_h" name="saldo30dias_h" value="{{$cliente->D30_ORIG}}"> $<span id="saldo30dias">{{$cliente->D30_ORIG}}</span>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldos actuales:</strong> <input type="hidden" id="saldos_actuales_h" name="saldos_actuales_h" value="{{$cliente->ACTUALES_ORIG}}"> $<span id="saldos_actuales">{{$cliente->ACTUALES_ORIG}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Deuda Actual:</strong> <input type="hidden" id="deuda_actual_h" name="deuda_actual_h" value="{{$deuda_actual}}"> $<span id="deuda_actual">{{$deuda_actual}}</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        &nbsp;
                                                    </td>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Intereses Facturados:</strong> <input type="hidden" id="intereses_facturados_h" name="intereses_facturados_h" value="{{$intereses_facturados}}"> <span id="intereses_facturados">${{$intereses_facturados}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Números de diferidos facturados: </strong> {{$cliente->N_DIF}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Total Valor Pre Cancelacion Diferidos :</strong> <input type="hidden" id="valor_pre_cancelacion_diferidos_h" name="valor_pre_cancelacion_diferidos_h" value="{{$cliente->SIMULACION_DIF_sum}}"> <span id="valor_pre_cancelacion_diferidos">${{$cliente->SIMULACION_DIF_sum}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        @if($cliente->DES_ESPECIALIDAD<=0)
                                                            <td colspan="2" style="background-color: #e9f70e">
                                                                <strong>Especialidad Venta Vehículos:</strong> {{$cliente->DES_ESPECIALIDAD}}
                                                            </td>
                                                        @else
                                                            <td colspan="2">
                                                                <strong>Especialidad Venta Vehículos:</strong> {{$cliente->DES_ESPECIALIDAD}}
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-lg-6">
                                            <div>
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                            Pagos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874; font-size: 15px">
                                                            <strong>Abono efectivo del sistema: </strong> <input type="hidden" id="abono_efectivo_sistema_h" name="abono_efectivo_sistema_h" value="{{$cliente->PAGO_REAL}}"> $<span id="abono_efectivo_sistema">{{$cliente->PAGO_REAL}}</span></strong><input type="checkbox" id="abono_efectivo_sistema_chk" name="abono_efectivo_sistema_chk" onchange="calcularDiners()" checked style="display: none;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Abono negociador: </strong> <input class="form-control" type="text" id="abono_negociador" name="abono_negociador" value="0" oninput="calcularDiners()" required>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Negociación Especial: </strong><input type="checkbox" id="negociacion_especial_chk" name="negociacion_especial_chk" onchange="calcularDiners()">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo Interes: </strong><strong>Desactivar</strong><input type="checkbox" id="desactivar_chk" name="desactivar_chk" onchange="calcularDiners()"><br><input class="form-control" type="text" id="saldo_interes" name="saldo_interes" value="0" oninput="calcularDiners()" required disabled>
                                                            <div class="alert alert-danger" role="alert" id="alerta">Abono no cubre interes de: <strong>${{$intereses_facturados}}</strong></div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874; font-size: 15px">
                                                            <strong>Abono Total: </strong> <input type="hidden" id="abono_total_h" name="abono_total_h" value="0"><input type="hidden" id="abono_total_i" name="abono_total_i" value="0"> $<span id="abono_total">0</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Abono mismo día del corte: </strong><input type="checkbox" id="abono_mismo_dia_del_corte_chk" name="abono_mismo_dia_del_corte_chk" onchange="calcularDiners()">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <input class="form-control" type="text" id="valor_abono_mismo_dia_del_corte" name="valor_abono_mismo_dia_del_corte" required disabled>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                            <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                <tbody>
                                            <tr>
                                                <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                    Saldo Fac. Despues de Abono
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <strong>Saldo a 90 y más 90 días: </strong> <input type="hidden" id="saldoa90ymas_despues_abono_h" name="saldoa90ymas_despues_abono_h" value="0"> $<span id="saldoa90ymas_despues_abono">0</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <strong>Saldo a 60 días: </strong> <input type="hidden" id="saldoa60dias_despues_abono_h" name="saldoa60dias_despues_abono_h" value="0"> $<span id="saldoa60dias_despues_abono">0</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <strong>Saldo a 30 días: </strong> <input type="hidden" id="saldoa30dias_despues_abono_h" name="saldoa30dias_despues_abono_h" value="0"> $<span id="saldoa30dias_despues_abono">0</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <strong>Saldos actuales:</strong> <input type="hidden" id="saldos_actuales_despues_abono_h" name="saldos_actuales_despues_abono_h" value="{{$cliente->ACTUALES_ORIG}}"> $<span id="saldos_actuales_despues_abono">{{$cliente->ACTUALES_ORIG}}</span>
                                                </td>
                                            </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                            Valores por facturar
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Intereses por facturar: </strong> <input type="hidden" id="intereses_por_facturar_h" name="intereses_por_facturar_h" value="{{$cliente->IXF}}"> $<span id="intereses_por_facturar">{{$cliente->IXF}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874;">
                                                            <strong>Corrientes por facturar: </strong> <input type="hidden" id="corrientes_por_facturar_h" name="corrientes_por_facturar_h" value="{{$cliente->VAXFAC}}"> <br>$<span id="corrientes_por_facturar">{{$cliente->VAXFAC}}</span> <input type="checkbox" id="corrientes_por_facturar_chk" name="corrientes_por_facturar_chk" onchange="calcularDiners()">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>ND por facturar: </strong> ${{$cliente->DEBITO}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>NC por facturar:</strong> ${{$cliente->CREDITO}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Gastos de cobranza / otros:</strong> <input type="hidden" id="gastos_de_cobranza_h" name="gastos_de_cobranza_h" value="0"> $<span id="gastos_de_cobranza">0</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Valor otras tarjetas:</strong> $<input type="hidden" id="valor_otras_tarjetas_h" name="valor_otras_tarjetas_h" value="0">
                                                        </td>
                                                    </tr>
                                                    </tbody></table></div>
                                            <div>
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                            Financiamiento
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874;">
                                                            <strong>Tipo de Financiamiento </strong> <input type="hidden" id="tipofinaciamiento_h" name="tipofinaciamiento_h" value="NOVACION"> <span id="tipofinaciamiento">NOVACION</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Total: </strong> <strong><input type="radio" id="total_chk" name="exigible_chk" onchange="calcularDiners()" checked required value="1"></strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Exigible: </strong> <strong><input type="radio" id="exigible_chk" name="exigible_chk" onchange="calcularDiners()" value="2"></strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Plazo financiamiento: </strong>
                                                            <select class="form-control" name="plazo_financiamiento" id="plazo_financiamiento" onchange="verIntereses(this.value,'d')" style="background-color: #f2dede">
                                                                <option value="0" selected>SELECCIONE UNO</option>
                                                                @foreach($intereses_meses as $k)
                                                                    <option value="{{$k->id}}">{{$k->meses_plazo}}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" id="factor_calculado_h" name="factor_calculado_h" value="0">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Motivo de no pago: </strong>
                                                            <select class="form-control" name="motivo_no_pago" id="motivo_no_pago" onchange="obligatoriosDiners()" style="background-color: #f2dede;">
                                                                <option value="0" selected>SELECCIONE UNO</option>
                                                                @foreach($motivo_no_pago as $k)
                                                                    <option value="{{$k->id}}">{{$k->descripcion}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                    </div>
                                        <div class="col-md-12 col-xs-12 col-lg-12">
                                            <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16); font-size: 14px;">
                                                <tr>
                                                    <td colspan="2" style="background-color: #29739a; color: #fff;">
                                                        <strong>VALOR OTRAS TARJETAS: </strong><input type="checkbox" id="otras_tarjetas_chk" name="otras_tarjetas_chk" onchange="calcularDiners()">
                                                        || <strong>VALOR VISA: </strong><input type="checkbox" id="valor_visa" name="valor_visa" onchange="valorVisa()" disabled>
                                                        || <strong>VALOR DISCOVER: </strong><input type="checkbox" id="valor_discover" name="valor_discover" onchange="valorDiscover()" disabled>
                                                        <input class="form-control" type="text" id="valor_otras_tarjetas" name="valor_otras_tarjetas" value="0" oninput="calcularDiners()" disabled >
                                                        <input class="form-control" type="hidden" id="unifica" name="unifica" value="0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>VALOR A FINANCIAR: </strong> <input type="hidden" id="valor_a_financiar_h" name="valor_a_financiar_h" value="0"> <span id="valor_a_financiar">0</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>TOTAL INTERESES: </strong> <input type="hidden" id="total_intereses_h" name="total_intereses_h" value="0"> $<span id="total_intereses">0</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>TOTAL FINANCIAMIENTO: </strong> <input type="hidden" id="total_financiamiento_h" name="total_financiamiento_h" value="0"> $<span id="total_financiamiento">0</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>VALOR CUOTA MENSUAL: </strong> <input type="hidden" id="valor_cuota_mensual_h" name="valor_cuota_mensual_h" value="0"> $<span id="valor_cuota_mensual">0</span>
                                                    </td>
                                                </tr>
                                            </table>

                                            <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Observación unificación: </strong> <textarea class="form-control" id="observacion_unificacion" name="observacion_unificacion" disabled></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Observación datos info.socio: </strong> <textarea class="form-control" id="observacion_datos_info_sor" name="observacion_datos_info_sor" disabled></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                </div>
                            </div>
                            </div>
                            <div style="margin: 0 auto; padding: 10px 0 10px 0">
                                <span class="button-checkbox">
                                    <div id="submitformDiners" class="btn btn-primary" data-color="primary" style="background-color:#3a77bf" onclick="consolidarDiners();"><input type="checkbox" name="consolidar_diners" id="consolidar_diners" class="" /> SELECCIONAR DINERS</div>
                                </span>
                            </div>

                        </div>
                        @endif
                        @if($visa<35)
                        <div class="panel-default col-lg-4" >
                            <div class="panel-heading" style="background-color: #777; border-color: #000; color: #fff;">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" onclick="calcularVisa();">
                                    <h4 class="panel-title">
                                        VISA<input type="hidden" name="visa" id="visa" value="visa">
                                    </h4>
                                </a>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse">
                                <div class="">
                                    <div class="form-group">
                                        <div class="col-md-12 col-xs-12 col-lg-6">
                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr style="background-color: #4c4c4c; color: #fff;">
                                                        <td colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <span style="margin-right: 20px"><strong>Ciclo:</strong> {{$cliente->CICLOF_VISA}}<input type="hidden" name="ciclo_visa" id="ciclo_visa" value="{{$cliente->CICLOF_VISA}}"></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Edad de cartera: </strong>{{$edad_cartera_v}}<input type="hidden" name="edad_cartera_v" id="edad_cartera_v" value="{{$edad_cartera_v}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Código de cancelación: </strong>{{$codigo_cancelacion_v}}<input type="hidden" name="codigo_cancelacion_v" id="codigo_cancelacion_v" value="{{$codigo_cancelacion_v}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Código de cancelación actual: </strong>
                                                            <select class="form-control" name="codigo_de_cancelacion_solicitado_v" id="codigo_de_cancelacion_solicitado_v" style="background-color: #f2dede">
                                                                @if($codigo_cancelacion==86)
                                                                    <option value="86" selected>86</option>
                                                                @endif
                                                                @if($codigo_cancelacion==87 || $codigo_cancelacion==0 || $codigo_cancelacion=='NO SE PUEDE NEGOCIAR, CUENTA EN LEGAL' || $codigo_cancelacion=='')
                                                                    <option value="87" selected>87</option>
                                                                @endif
                                                                <option value="SELECCIONE UNO">SELECCIONE UNO</option>
                                                                @foreach($codigo_cancelacion_solicitado as $k)
                                                                    <option value="{{$k->descripcion}}">{{$k->descripcion}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Código de boletín:</strong> {{$cod_boletin_v}}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr style="background-color: #4c4c4c; color: #fff;">
                                                        <td colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Débito automático:</strong> {{$cliente->DEBITO_AUT_VISA}}<input type="hidden" name="debito_automatico_v" id="debito_automatico_v" value="{{$cliente->DEBITO_AUT_VISA}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874;">
                                                            <strong>Promedio de pago:</strong> ${{$cliente->PROM_PAG_VISA}}<input type="hidden" name="promedio_de_pago_v" id="promedio_de_pago_v" value="{{$cliente->PROM_PAG_VISA}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Score:</strong> {{$cliente->SCORE_DINERS_VISA}}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div>
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr style="background-color: #4c4c4c; color: #fff;">
                                                        <td colspan="2"></td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Campaña:</strong> {{$cliente->CAMPAŃA_CON_ECE_VISA}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Ejecutivo actual a cargo de cuenta: </strong> {{$cliente->EMPRESA_VISA}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Lugar de trabajo:</strong> {{$cliente->EMPRESA_SOC}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Fecha última gestión:</strong> {{$cliente->FECHALLAM_VISA}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Motivo de gestión:</strong> {{$cliente->MOTIVO_1_VISA}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Descripción de gestión:</strong> {{$cliente->DESCRIPCION_VISA}}
                                                        </td>
                                                    </tr>
                                                    <tr style="background-color: #86e874;">
                                                        <td colspan="2">
                                                            <strong>Observación de gestión:</strong> {{$cliente->OBSERVACION_VISA}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Fecha de compromiso:</strong> {{$cliente->FECHACOMPROMISO_VISA}}
                                                        </td>
                                                    </tr>

                                                    <tr style="background-color: #4c4c4c; color: #fff;">
                                                        <td colspan="2"></td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Financiamiento Vigente:</strong> {{$cliente->NOMESTAB_VISA}}<input type="hidden" value="{{$cliente->NOMESTAB}}" id="financiamiento_vigente_v" name="financiamiento_vigente_v">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Nro Cuotas Pendientes:</strong> {{$cliente->CUOTAS_PTES_VISA}} <strong>|</strong> {{$cliente->TOTAL_CUOTAS_REF_VI}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Valor Cuotas Pendientes:</strong> ${{$cliente->VALOR_PEN_REF_VIG_VISA}}
                                                        </td>
                                                    </tr><tr>
                                                        <td colspan="2">
                                                            <strong>Segunda Reestructuración:</strong> {{$cliente->REESTRUCT_VIGENTE_VISA}}
                                                            <div class="alert alert-warning" name="reestructuracion_vigente" id="reestructuracion_vigente">{{$cliente->REESTRUCT_VIGENTE_VISA2}}
                                                                <input type="hidden" value="{{$cliente->REESTRUCT_VIGENTE_VISA2}}" id="segunda_reestructuracion_visa" name="segunda_reestructuracion_visa">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Total Riesgo:</strong> ${{$cliente->TRIESGO_VISA}}<input type="hidden" id="total_riesgo_v" name="total_riesgo_v" value="{{$cliente->TRIESGO_VISA}}">
                                                        </td>
                                                    </tr>


                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #4c4c4c; color: #fff;">
                                                            Saldos Facturados
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 90 y más 90 días: </strong> <input type="hidden" id="saldo90ymas_h_v" name="saldo90ymas_h_v" value="{{$saldo90ymas_v}}"> <span id="saldo90ymas_v">${{$saldo90ymas_v}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 60 días: </strong> <input type="hidden" id="saldo60dias_h_v" name="saldo60dias_h_v" value="{{$cliente->D60_ORIG_VISA}}"> $<span id="saldo60dias_v">{{$cliente->D60_ORIG_VISA}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 30 días: </strong> <input type="hidden" id="saldo30dias_h_v" name="saldo30dias_h_v" value="{{$cliente->D30_ORIG_VISA}}"> $<span id="saldo30dias_v">{{$cliente->D30_ORIG_VISA}}</span>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldos actuales:</strong> <input type="hidden" id="saldos_actuales_h_v" name="saldos_actuales_h_v" value="{{$cliente->ACTUALES_ORIG_VISA}}"> $<span id="saldos_actuales_v">{{$cliente->ACTUALES_ORIG_VISA}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Mínimo a pagar:</strong> <input type="hidden" id="minimo_a_pagar_h_v" name="minimo_a_pagar_h_v" value="{{$cliente->VAPAMI_VISA}}"> $<span id="minimo_a_pagar_v">{{$cliente->VAPAMI_VISA}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Deuda Actual:</strong> <input type="hidden" id="deuda_actual_h_v" name="deuda_actual_h_v" value="{{$deuda_actual_v}}"> $<span id="deuda_actual_v">{{$deuda_actual_v}}</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <td colspan="2" style="background-color: #4c4c4c; color: #fff;">
                                                        &nbsp;
                                                    </td>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Intereses Facturados:</strong> <input type="hidden" id="intereses_facturados_h_v" name="intereses_facturados_h_v" value="{{$intereses_facturados_v}}"> <span id="intereses_facturados">${{$intereses_facturados_v}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Números de diferidos facturados: </strong> {{$cliente->N_DIF_VISA}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Total Valor Pre Cancelacion Diferidos :</strong> <input type="hidden" id="valor_pre_cancelacion_diferidos_h_v" name="valor_pre_cancelacion_diferidos_h_v" value="{{$cliente->SIMULACION_DIF_sum_VISA}}"> <span id="valor_pre_cancelacion_diferidos_v">${{$cliente->SIMULACION_DIF_sum_VISA}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        @if($cliente->DES_ESPECIALIDAD_VISA<=0)
                                                            <td colspan="2" style="background-color: #e9f70e">
                                                                <strong>Especialidad Venta Vehículos:</strong> {{$cliente->DES_ESPECIALIDAD_VISA}}
                                                            </td>
                                                        @else
                                                            <td colspan="2">
                                                                <strong>Especialidad Venta Vehículos:</strong> {{$cliente->DES_ESPECIALIDAD_VISA}}
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-lg-6">
                                            <div>
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #4c4c4c; color: #fff;">
                                                            Pagos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874; font-size: 15px">
                                                            <strong style="font-size: 14px">Abono efectivo del sistema: </strong> <input type="hidden" id="abono_efectivo_sistema_h_v" name="abono_efectivo_sistema_h_v" value="{{$cliente->PAGO_REAL_VISA}}"> $<span id="abono_efectivo_sistema_v">{{$cliente->PAGO_REAL_VISA}}</span><input type="checkbox" id="abono_efectivo_sistema_chk_v" name="abono_efectivo_sistema_chk_v" onchange="calcularVisa()" checked style="display: none;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Abono negociador: </strong> <input class="form-control" type="text" id="abono_negociador_v" name="abono_negociador_v" value="0" oninput="calcularVisa()" required>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Negociacion Especial: </strong><input type="checkbox" id="negociacion_especial_chk_v" name="negociacion_especial_chk_v" onchange="calcularVisa()">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo Interes: </strong><strong>Desactivar</strong><input type="checkbox" id="desactivar_chk_v" name="desactivar_chk_v" onchange="calcularVisa()"><br><input class="form-control" type="text" id="saldo_interes_v" name="saldo_interes_v" value="0" oninput="calcularVisa()" required disabled>
                                                            <div class="alert alert-danger" role="alert" id="alerta_v">Abono no cubre interes de <strong>${{$intereses_facturados_v}}</strong></div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874;">
                                                            <strong>Abono Total: </strong> <input type="hidden" id="abono_total_h_v" name="abono_total_h_v" value="0"><input type="hidden" id="abono_total_i_v" name="abono_total_i_v" value="0"> $<span id="abono_total_v">0</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Abono mismo día del corte: </strong><input type="checkbox" id="abono_mismo_dia_del_corte_chk_v" name="abono_mismo_dia_del_corte_chk_v" onchange="calcularVisa()">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <input class="form-control" type="text" id="valor_abono_mismo_dia_del_corte_v" name="valor_abono_mismo_dia_del_corte_v" required disabled>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874;">
                                                            <strong>TIPO DE CUENTA NORMAL/ESPECIAL: </strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <select class="form-control" name="tipo_cuenta_v" id="tipo_cuenta_v" required style="background-color: #f2dede" onclick="obligatoriosVisa()">
                                                                <option >SELECCIONE UNO</option>
                                                                <option value="NORMAL" selected>NORMAL </option>
                                                                <option value="ESPECIAL" >ESPECIAL</option>
                                                                <option value="ALTO RIESGO" >ALTO RIESGO</option>
                                                                <option value="ALTO RIESGO ESPECIAL" >ALTO RIESGO ESPECIAL</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #4c4c4c; color: #fff;">
                                                            Saldo Fac. Despues de Abono
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 90 y más 90 días: </strong> <input type="hidden" id="saldoa90ymas_despues_abono_h_v" name="saldoa90ymas_despues_abono_h_v" value="0"> $<span id="saldoa90ymas_despues_abono_v">0</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 60 días: </strong> <input type="hidden" id="saldoa60dias_despues_abono_h_v" name="saldoa60dias_despues_abono_h_v" value="0"> $<span id="saldoa60dias_despues_abono_v">0</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 30 días: </strong> <input type="hidden" id="saldoa30dias_despues_abono_h_v" name="saldoa30dias_despues_abono_h_v" value="0"> $<span id="saldoa30dias_despues_abono_v">0</span>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldos actuales:</strong> <input type="hidden" id="saldos_actuales_despues_abono_h_v" name="saldos_actuales_despues_abono_h_v" value="{{$cliente->ACTUALES_ORIG}}"> $<span id="saldos_actuales_despues_abono_v">{{$cliente->ACTUALES_ORIG}}</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #4c4c4c; color: #fff;">
                                                            Valores por facturar
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Intereses por facturar: </strong> <input type="hidden" id="intereses_por_facturar_h_v" name="intereses_por_facturar_h_v" value="{{$cliente->IXF_VISA}}"> $<span id="intereses_por_facturar_v">{{$cliente->IXF_VISA}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Corrientes por facturar: </strong> <input type="hidden" id="corrientes_por_facturar_h_v" name="corrientes_por_facturar_h_v" value="{{$cliente->VAXFAC_VISA}}"> $<span id="corrientes_por_facturar_v">{{$cliente->VAXFAC_VISA}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>ND por facturar: </strong> ${{$cliente->DEBITO_VISA}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>NC por facturar:</strong> ${{$cliente->CREDITO_VISA}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Gastos de cobranza / otros:</strong> <input type="hidden" id="gastos_de_cobranza_h_v" name="gastos_de_cobranza_h_v" value="0"> $<span id="gastos_de_cobranza_v">0</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Valor otras tarjetas:</strong> $<input type="hidden" id="valor_otras_tarjetas_h_v" name="valor_otras_tarjetas_h_v" value="0">
                                                        </td>
                                                    </tr>
                                                    </tbody></table></div>
                                            <div>
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #4c4c4c; color: #fff;">
                                                            Financiamiento
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874;">
                                                            <strong>Tipo de Financiamiento </strong> <input type="hidden" id="tipofinaciamiento_h_v" name="tipofinaciamiento_h_v" value="NOVACION"> <span id="tipofinaciamiento_v">NOVACION</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Total: </strong> <strong><input type="radio" id="total_chk_v" name="exigible_chk_v" onchange="calcularVisa()" checked required value="1">Total</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Exigible: </strong> <strong><input type="radio" id="exigible_chk_v" name="exigible_chk_v" onchange="calcularVisa()" value="2">Exigible</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Plazo financiamiento: </strong><input type="hidden" id="formato_consolidado_v" name="formato_consolidado_v" value="">
                                                            <select class="form-control" name="plazo_financiamiento_v" id="plazo_financiamiento_v" onchange="verIntereses(this.value,'v')" style="background-color: #f2dede">
                                                                <option selected>0</option>
                                                                @foreach($intereses_meses_v as $k)
                                                                    <option value="{{$k->id}}">{{$k->meses_plazo}}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" id="factor_calculado_h_v" name="factor_calculado_h_v" value="0">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Motivo de no pago: </strong>
                                                            <select class="form-control" name="motivo_no_pago_v" id="motivo_no_pago_v" onchange="obligatoriosVisa()" style="background-color: #f2dede">
                                                                <option value="0" selected>SELECCIONE UNO</option>
                                                                @foreach($motivo_no_pago as $k)
                                                                    <option value="{{$k->id}}">{{$k->descripcion}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="col-md-12 col-xs-12 col-lg-12">
                                            <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);font-size: 14px;">
                                                <tr>
                                                    <td colspan="2" style="background-color: #29739a; color: #fff;">
                                                        <strong>VALOR OTRAS TARJETAS: </strong><input type="checkbox" id="otras_tarjetas_chk_v" name="otras_tarjetas_chk_v" onchange="calcularVisa()"><input class="form-control" type="text" id="valor_otras_tarjetas_v" name="valor_otras_tarjetas_v" value="0" oninput="calcularVisa()" disabled>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>VALOR A FINANCIAR: </strong> <input type="hidden" id="valor_a_financiar_h_v" name="valor_a_financiar_h_v" value="0"> <span id="valor_a_financiar_v">0</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>TOTAL INTERESES: </strong> <input type="hidden" id="total_intereses_h_v" name="total_intereses_h_v" value="0"> $<span id="total_intereses_v">0</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>TOTAL FINANCIAMIENTO: </strong> <input type="hidden" id="total_financiamiento_h_v" name="total_financiamiento_h_v" value="0"> $<span id="total_financiamiento_v">0</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>VALOR CUOTA MENSUAL: </strong> <input type="hidden" id="valor_cuota_mensual_h_v" name="valor_cuota_mensual_h_v" value="0"> $<span id="valor_cuota_mensual_v">0</span>
                                                    </td>
                                                </tr>
                                            </table>

                                            <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Observación unificación: </strong> <textarea class="form-control" id="observacion_unificacion_v" name="observacion_unificacion_v" disabled></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Observación datos info.socio: </strong> <textarea class="form-control" id="observacion_datos_info_sor_v" name="observacion_datos_info_sor_v" disabled></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="margin: 0 auto; padding: 10px 0 10px 0">
                                <span class="button-checkbox">
                                    <div id="submitformVisa" class="btn btn-primary" data-color="primary" style="background-color:#777" onclick="consolidarVisa();"><input type="checkbox" name="consolidar_visa" id="consolidar_visa" class="" /> SELECCIONAR VISA</div>
                                    <input type="checkbox" name="consolidar_visa" id="consolidar_visa" class="hidden" />
                                </span>
                            </div>
                        </div>
                        @endif
                        @if($discover<35)
                        <div class="panel-default col-lg-4">
                            <div class="panel-heading" style="background-color: #ff9a22; border-color: #000; color: #fff;">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" onclick="calcularDiscover()">
                                    <h4 class="panel-title">
                                    DISCOVER<input type="hidden" name="discover" id="discover" value="discover">
                                    </h4>
                                </a>
                            </div>
                            <div id="collapse3" class="panel-collapse collapse">
                                <div class="">
                                    <div class="form-group">
                                        <div class="col-md-12 col-xs-12 col-lg-6">
                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr style="background-color: #de7900; color: #fff;">
                                                        <td colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <span style="margin-right: 20px"><strong>Ciclo:</strong> {{$cliente->CICLOF_DISCOVER}}<input type="hidden" name="ciclo_discover" id="ciclo_discover" value="{{$cliente->CICLOF_DISCOVER}}"></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Edad de cartera: </strong>{{$edad_cartera_dis}}<input type="hidden" name="edad_cartera_dis" id="edad_cartera_dis" value="{{$edad_cartera_dis}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Código de cancelación: </strong>{{$codigo_cancelacion_dis}}<input type="hidden" name="codigo_cancelacion_dis" id="codigo_cancelacion_dis" value="{{$codigo_cancelacion_dis}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Código de cancelación actual: </strong>
                                                            <select class="form-control" name="codigo_de_cancelacion_solicitado_dis" id="codigo_de_cancelacion_solicitado_dis" style="background-color: #f2dede">
                                                                @if($codigo_cancelacion==86)
                                                                    <option value="86" selected>86</option>
                                                                @endif
                                                                @if($codigo_cancelacion==87 || $codigo_cancelacion==0 || $codigo_cancelacion=='NO SE PUEDE NEGOCIAR, CUENTA EN LEGAL' || $codigo_cancelacion=='')
                                                                    <option value="87" selected>87</option>
                                                                @endif
                                                                <option value="SELECCIONE UNO">SELECCIONE UNO</option>
                                                                @foreach($codigo_cancelacion_solicitado as $k)
                                                                    <option value="{{$k->descripcion}}">{{$k->descripcion}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Código de boletín:</strong> {{$cod_boletin_dis}}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr style="background-color: #de7900; color: #fff;">
                                                        <td colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Débito automático:</strong> {{$cliente->DEBITO_AUT_DISCOVER}}<input type="hidden" name="debito_automatico_dis" id="debito_automatico_dis" value="{{$cliente->DEBITO_AUT_DISCOVER}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874;">
                                                            <strong>Promedio de pago:</strong> ${{$cliente->PROM_PAG_DISCOVER}}<input type="hidden" name="promedio_de_pago_dis" id="promedio_de_pago_dis" value="{{$cliente->PROM_PAG_DISCOVER}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Score:</strong> {{$cliente->SCORE_DINERS_DISCOVER}}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div>
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr style="background-color: #de7900; color: #fff;">
                                                        <td colspan="2"></td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Campaña:</strong> {{$cliente->CAMPAŃA_CON_ECE_DISCOVER}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Ejecutivo actual a cargo de cuenta: </strong> {{$cliente->EMPRESA_DISCOVER}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Lugar de trabajo:</strong> {{$cliente->EMPRESA_SOC}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Fecha última gestión:</strong> {{$cliente->FECHALLAM_DISCOVER}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Motivo de gestión:</strong> {{$cliente->MOTIVO_1_DISCOVER}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Descripción de gestión:</strong> {{$cliente->DESCRIPCION_DISCOVER}}
                                                        </td>
                                                    </tr>
                                                    <tr style="background-color: #86e874;">
                                                        <td colspan="2">
                                                            <strong>Observación de gestión:</strong> {{$cliente->OBSERVACION_DISCOVER}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Fecha de compromiso:</strong> {{$cliente->FECHACOMPROMISO_DISCOVER}}
                                                        </td>
                                                    </tr>

                                                    <tr style="background-color: #de7900; color: #fff;">
                                                        <td colspan="2"></td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Financiamiento Vigente:</strong> {{$cliente->NOMESTAB_DISCOVER}}<input type="hidden" value="{{$cliente->NOMESTAB}}" id="financiamiento_vigente_dis" name="financiamiento_vigente_dis">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Nro Cuotas Pendientes:</strong> {{$cliente->CUOTAS_PTES_DISCOVER}} <strong>|</strong> {{$cliente->TOTAL_CUOTAS_REF_disI}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Valor Cuotas Pendientes:</strong> ${{$cliente->VALOR_PEN_REF_VIG_DISCOVER}}
                                                        </td>
                                                    </tr><tr>
                                                        <td colspan="2">
                                                            <strong>Segunda Reestructuración:</strong> {{$cliente->REESTRUCT_VIGENTE_DISCOVER}}
                                                            <div class="alert alert-warning" name="reestructuracion_vigente" id="reestructuracion_vigente">{{$cliente->REESTRUCT_VIGENTE_DISCOVER2}}
                                                                <input type="hidden" value="{{$cliente->REESTRUCT_VIGENTE_VISA2}}" id="segunda_reestructuracion_discover" name="segunda_reestructuracion_discover">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Total Riesgo:</strong> ${{$cliente->TRIESGO_DISCOVER}}<input type="hidden" id="total_riesgo_dis" name="total_riesgo_dis" value="{{$cliente->TRIESGO_DISCOVER}}">
                                                        </td>
                                                    </tr>


                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #de7900; color: #fff;">
                                                            Saldos Facturados
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 90 y más 90 días: </strong> <input type="hidden" id="saldo90ymas_h_dis" name="saldo90ymas_h_dis" value="{{$saldo90ymas_dis}}"> <span id="saldo90ymas_dis">${{$saldo90ymas_dis}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 60 días: </strong> <input type="hidden" id="saldo60dias_h_dis" name="saldo60dias_h_dis" value="{{$cliente->D60_ORIG_DISCOVER}}"> $<span id="saldo60dias_dis">{{$cliente->D60_ORIG_DISCOVER}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 30 días: </strong> <input type="hidden" id="saldo30dias_h_dis" name="saldo30dias_h_dis" value="{{$cliente->D30_ORIG_DISCOVER}}"> $<span id="saldo30dias_dis">{{$cliente->D30_ORIG_DISCOVER}}</span>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldos actuales:</strong> <input type="hidden" id="saldos_actuales_h_dis" name="saldos_actuales_h_dis" value="{{$cliente->ACTUALES_ORIG_DISCOVER}}"> $<span id="saldos_actuales_dis">{{$cliente->ACTUALES_ORIG_DISCOVER}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Mínimo a pagar:</strong> <input type="hidden" id="minimo_a_pagar_h_dis" name="minimo_a_pagar_h_dis" value="{{$cliente->VAPAMI_DISCOVER}}"> $<span id="minimo_a_pagar_dis">{{$cliente->VAPAMI_DISCOVER}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Deuda Actual:</strong> <input type="hidden" id="deuda_actual_h_dis" name="deuda_actual_h_dis" value="{{$deuda_actual_dis}}"> $<span id="deuda_actual_dis">{{$deuda_actual_dis}}</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <td colspan="2" style="background-color: #de7900; color: #fff;">
                                                        &nbsp;
                                                    </td>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Intereses Facturados:</strong> <input type="hidden" id="intereses_facturados_h_dis" name="intereses_facturados_h_dis" value="{{$intereses_facturados_dis}}"> <span id="intereses_facturados">${{$intereses_facturados_dis}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Números de diferidos facturados: </strong> {{$cliente->N_DIF_DISCOVER}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Total Valor Pre Cancelacion Diferidos :</strong> <input type="hidden" id="valor_pre_cancelacion_diferidos_h_dis" name="valor_pre_cancelacion_diferidos_h_dis" value="{{$cliente->SIMULACION_DIF_sum_DISCOVER}}"> <span id="valor_pre_cancelacion_diferidos_dis">${{$cliente->SIMULACION_DIF_sum_DISCOVER}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        @if($cliente->DES_ESPECIALIDAD_DISCOVER<=0)
                                                            <td colspan="2" style="background-color: #e9f70e">
                                                                <strong>Especialidad Venta Vehículos:</strong> {{$cliente->DES_ESPECIALIDAD_DISCOVER}}
                                                            </td>
                                                        @else
                                                            <td colspan="2">
                                                                <strong>Especialidad Venta Vehículos:</strong> {{$cliente->DES_ESPECIALIDAD_DISCOVER}}
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-lg-6">
                                            <div>
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #de7900; color: #fff;">
                                                            Pagos
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874; font-size: 15px">
                                                            <strong style="font-size: 14px">Abono efectivo del sistema: </strong> <input type="hidden" id="abono_efectivo_sistema_h_dis" name="abono_efectivo_sistema_h_dis" value="{{$cliente->PAGO_REAL_DISCOVER}}"> $<span id="abono_efectivo_sistema_dis">{{$cliente->PAGO_REAL_DISCOVER}}</span><input type="checkbox" id="abono_efectivo_sistema_chk_dis" name="abono_efectivo_sistema_chk_dis" onchange="calcularDiscover()" checked style="display: none;">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Abono negociador: </strong> <input class="form-control" type="text" id="abono_negociador_dis" name="abono_negociador_dis" value="0" oninput="calcularDiscover()" required>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Negociacion Especial: </strong><input type="checkbox" id="negociacion_especial_chk_dis" name="negociacion_especial_chk_dis" onchange="calcularDiscover()">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo Interes: </strong><strong>Desactivar</strong><input type="checkbox" id="desactivar_chk_dis" name="desactivar_chk_dis" onchange="calcularDiscover()"><br><input class="form-control" type="text" id="saldo_interes_dis" name="saldo_interes_dis" value="0" oninput="calcularDiscover()" required disabled>
                                                            <div class="alert alert-danger" role="alert" id="alerta_dis">Abono no cubre interes de <strong>${{$intereses_facturados_dis}}</strong></div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874;">
                                                            <strong>Abono Total: </strong> <input type="hidden" id="abono_total_h_dis" name="abono_total_h_dis" value="0"><input type="hidden" id="abono_total_i_dis" name="abono_total_i_dis" value="0"> $<span id="abono_total_dis">0</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Abono mismo día del corte: </strong><input type="checkbox" id="abono_mismo_dia_del_corte_chk_dis" name="abono_mismo_dia_del_corte_chk_dis" onchange="calcularDiscover()">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <input class="form-control" type="text" id="valor_abono_mismo_dia_del_corte_dis" name="valor_abono_mismo_dia_del_corte_dis" required disabled>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874;">
                                                            <strong>TIPO DE CUENTA NORMAL/ESPECIAL</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <select class="form-control" name="tipo_cuenta_dis" id="tipo_cuenta_dis" required style="background-color: #f2dede" onclick="obligatoriosDiscover()">
                                                                <option >SELECCIONE UNO</option>
                                                                <option value="NORMAL" selected>NORMAL </option>
                                                                <option value="ESPECIAL" >ESPECIAL</option>
                                                                <option value="ALTO RIESGO" >ALTO RIESGO</option>
                                                                <option value="ALTO RIESGO ESPECIAL" >ALTO RIESGO ESPECIAL</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #de7900; color: #fff;">
                                                            Saldo Fac. Despues de Abono
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 90 y más 90 días: </strong> <input type="hidden" id="saldoa90ymas_despues_abono_h_dis" name="saldoa90ymas_despues_abono_h_dis" value="0"> $<span id="saldoa90ymas_despues_abono_dis">0</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 60 días: </strong> <input type="hidden" id="saldoa60dias_despues_abono_h_dis" name="saldoa60dias_despues_abono_h_dis" value="0"> $<span id="saldoa60dias_despues_abono_dis">0</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldo a 30 días: </strong> <input type="hidden" id="saldoa30dias_despues_abono_h_dis" name="saldoa30dias_despues_abono_h_dis" value="0"> $<span id="saldoa30dias_despues_abono_dis">0</span>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Saldos actuales:</strong> <input type="hidden" id="saldos_actuales_despues_abono_h_dis" name="saldos_actuales_despues_abono_h_dis" value="{{$cliente->ACTUALES_ORIG}}"> $<span id="saldos_actuales_despues_abono_dis">{{$cliente->ACTUALES_ORIG}}</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-lg-6" style="padding: 1px;">
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #de7900; color: #fff;">
                                                            Valores por facturar
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Intereses por facturar: </strong> <input type="hidden" id="intereses_por_facturar_h_dis" name="intereses_por_facturar_h_dis" value="{{$cliente->IXF_DISCOVER}}"> $<span id="intereses_por_facturar_dis">{{$cliente->IXF_DISCOVER}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Corrientes por facturar: </strong> <input type="hidden" id="corrientes_por_facturar_h_dis" name="corrientes_por_facturar_h_dis" value="{{$cliente->VAXFAC_DISCOVER}}"> $<span id="corrientes_por_facturar_dis">{{$cliente->VAXFAC_DISCOVER}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>ND por facturar: </strong> ${{$cliente->DEBITO_DISCOVER}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>NC por facturar:</strong> ${{$cliente->CREDITO_DISCOVER}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Gastos de cobranza / otros:</strong> <input type="hidden" id="gastos_de_cobranza_h_dis" name="gastos_de_cobranza_h_dis" value="0"> $<span id="gastos_de_cobranza_dis">0</span>
                                                        </td>
                                                    </tr>
                                                    </tbody></table></div>
                                            <div>
                                                <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #de7900; color: #fff;">
                                                            Financiamiento
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="background-color: #86e874;">
                                                            <strong>Tipo de Financiamiento </strong> <input type="hidden" id="tipofinaciamiento_h_dis" name="tipofinaciamiento_h_dis" value="NOVACION"> <span id="tipofinaciamiento_dis">NOVACION</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Total: </strong> <strong><input type="radio" id="total_chk_dis" name="exigible_chk_dis" onchange="calcularDiscover()" checked required value="1">Total</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Exigible: </strong> <strong><input type="radio" id="exigible_chk_dis" name="exigible_chk_dis" onchange="calcularDiscover()" value="2">Exigible</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Plazo financiamiento: </strong><input type="hidden" id="formato_consolidado_dis" name="formato_consolidado_dis" value="">
                                                            <select class="form-control" name="plazo_financiamiento_dis" id="plazo_financiamiento_dis" onchange="verIntereses(this.value,'dis')" style="background-color: #f2dede">
                                                                <option selected>0</option>
                                                                @foreach($intereses_meses_dis as $k)
                                                                    <option value="{{$k->id}}">{{$k->meses_plazo}}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" id="factor_calculado_h_dis" name="factor_calculado_h_dis" value="0">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <strong>Motivo de no pago: </strong>
                                                            <select class="form-control" name="motivo_no_pago_dis" id="motivo_no_pago_dis" onchange="obligatoriosDiscover()" style="background-color: #f2dede">
                                                                <option value="0" selected>SELECCIONE UNO</option>
                                                                @foreach($motivo_no_pago as $k)
                                                                    <option value="{{$k->id}}">{{$k->descripcion}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="col-md-12 col-xs-12 col-lg-12">
                                            <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16); font-size: 14px;">
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>VALOR A FINANCIAR: </strong> <input type="hidden" id="valor_a_financiar_h_dis" name="valor_a_financiar_h_dis" value="0"> <span id="valor_a_financiar_dis">0</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>TOTAL INTERESES: </strong> <input type="hidden" id="total_intereses_h_dis" name="total_intereses_h_dis" value="0"> $<span id="total_intereses_dis">0</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>TOTAL FINANCIAMIENTO: </strong> <input type="hidden" id="total_financiamiento_h_dis" name="total_financiamiento_h_dis" value="0"> $<span id="total_financiamiento_dis">0</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="background-color: #0e3950; color: #fff;">
                                                        <strong>VALOR CUOTA MENSUAL: </strong> <input type="hidden" id="valor_cuota_mensual_h_dis" name="valor_cuota_mensual_h_dis" value="0"> $<span id="valor_cuota_mensual_dis">0</span>
                                                    </td>
                                                </tr>
                                            </table>

                                            <table class="table table-striped" style="background-color: rgba(100, 172, 243, 0.16);">
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Observación unificación: </strong> <textarea class="form-control" id="observacion_unificacion_dis" name="observacion_unificacion_dis" disabled></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <strong>Observación datos info.socio: </strong> <textarea class="form-control" id="observacion_datos_info_sor_dis" name="observacion_datos_info_sor_dis" disabled></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="margin: 0 auto; padding: 10px 0 10px 0">
                                <span class="button-checkbox">
                                    <div id="submitformDiscover" class="btn btn-primary" data-color="primary" style="background-color:#ff9a22" onclick="consolidarDiscover();"><input type="checkbox" name="consolidar_discover" id="consolidar_discover" class="" /> SELECCIONAR DISCOVER</div>
                                    <input type="checkbox" name="consolidar_discover" id="consolidar_discover" class="hidden" />
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                        <div style="width:100px; margin: 0 auto; padding: 10px 0 10px 0">
                            <div style="width:100px; margin: 0 auto; padding: 10px 0 10px 0">
                                <button type="submit" id="submitform" class="btn btn-success">PROCESAR TODO</button>
                            </div>
                        </div>
                    </form>
            @endif
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.button-checkbox').each(function () {

            // Settings
            var $widget = $(this),
                $button = $widget.find('button'),
                $checkbox = $widget.find('input:checkbox'),
                color = $button.data('color'),
                settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
                };

            // Event Handlers
            $button.on('click', function () {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                updateDisplay();
            });
            $checkbox.on('change', function () {
                updateDisplay();
            });

            // Actions
            function updateDisplay() {
                var isChecked = $checkbox.is(':checked');

                // Set the button's state
                $button.data('state', (isChecked) ? "on" : "off");

                // Set the button's icon
                $button.find('.state-icon')
                    .removeClass()
                    .addClass('state-icon ' + settings[$button.data('state')].icon);

                // Update the button's color
                if (isChecked) {
                    $button
                        .removeClass('btn-default')
                        .addClass('btn-' + color + ' active');
                }
                else {
                    $button
                        .removeClass('btn-' + color + ' active')
                        .addClass('btn-default');
                }
            }

            // Initialization
            function init() {

                updateDisplay();

                // Inject the icon if applicable
                if ($button.find('.state-icon').length == 0) {
                    $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                }
            }
            init();
        });
    });
</script>
@endsection