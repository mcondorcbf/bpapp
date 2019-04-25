<table>
    <thead>
    <tr style="background-color: #1e5778; color: #fff">
        <td>CUENTA</td>
        <td>NOMBRE</td>
        <td>CEDULA</td>


        <td>MES ING</td>
        <td>AÑO ING</td>
        <td>EDAD TOTAL</td>
        @if($marca=='DINERS')<td>EDAD RECNS</td>@endif
        @if($marca=='VISA')<td>EDAD FINAL</td>@endif
        @if($marca=='DISCOVER')<td>EDAD FINAL</td>@endif

        <td>CODRET</td>
        <td>CARGO</td>
        <td>INGRESO</td>

        <td>CR</td>
        @if($marca=='DINERS')<td>FIN. VEHICULAR</td>@endif
        @if($marca=='DINERS')<td>CASH ADVANCE</td>@endif
        <td>TIPO REFINANCIACION</td>
        <td>CONTACTABILIDAD</td>
        <td>MOTIVO</td>
        <td>SUBMOTIVO</td>

        @if($marca=='DINERS')<td>CASTIGADA SIS FINANCIERO</td>@endif
        @if($marca=='VISA')<td>CASTIGADA SIS FINANC</td>@endif
        @if($marca=='DISCOVER')<td>CASTIGADA SIS FINANC</td>@endif

        <td>CIUDAD</td>
        <td>ZONA</td>
        @if($marca=='DINERS')<td>TKAPITAL DINERS</td>@endif
        @if($marca=='VISA')<td>TKAPITAL VISA</td>@endif
        @if($marca=='DISCOVER')<td>KAPITAL DISCOVER</td>@endif

        @if($marca=='DINERS')<td>RIESGO DINERS</td>@endif
        @if($marca=='VISA')<td>RIESGO VISA</td>@endif
        @if($marca=='DISCOVER')<td>RIESGO DISCOVER</td>@endif

        @if($marca=='DINERS')<td>CTA VISA</td>@endif
        @if($marca=='VISA')<td>CTA DINERS</td>@endif
        @if($marca=='DISCOVER')<td>CTA DINERS</td>@endif

        @if($marca=='DINERS')<td>CICLO VISA</td>@endif
        @if($marca=='VISA')<td>CICLO DINERS</td>@endif
        @if($marca=='DISCOVER')<td>CICLO DINERS</td>@endif

        @if($marca=='DINERS')<td>MORA VISA</td>@endif
        @if($marca=='VISA')<td>MORA DINERS</td>@endif
        @if($marca=='DISCOVER')<td>MORA DINERS</td>@endif

        @if($marca=='DINERS')<td>SALDO VISA</td>@endif
        @if($marca=='VISA')<td>SALDO DINERS</td>@endif
        @if($marca=='DISCOVER')<td>SALDO DINERS</td>@endif

        @if($marca=='DINERS')<td>TKAPITAL VISA</td>@endif
        @if($marca=='VISA')<td>TKAPITAL DINERS</td>@endif
        @if($marca=='DISCOVER')<td>KAPITAL DINERS</td>@endif

        @if($marca=='DINERS')<td>RIESGO VISA</td>@endif
        @if($marca=='VISA')<td>RIESGO DINERS</td>@endif
        @if($marca=='DISCOVER')<td>RIESGO DINERS</td>@endif

        @if($marca=='DINERS')<td>CTA DISCOVER</td>@endif
        @if($marca=='VISA')<td>CTA DISCOVER</td>@endif
        @if($marca=='DISCOVER')<td>CTA VISA</td>@endif

        @if($marca=='DINERS')<td>CICLO DISCOVER</td>@endif
        @if($marca=='VISA')<td>CICLO DISCOVER</td>@endif
        @if($marca=='DISCOVER')<td>CICLO VISA</td>@endif

        @if($marca=='DINERS')<td>MORA DISCOVER</td>@endif
        @if($marca=='VISA')<td>MORA DISCOVER</td>@endif
        @if($marca=='DISCOVER')<td>MORA VISA</td>@endif

        @if($marca=='DINERS')<td>SALDO DISCOVER</td>@endif
        @if($marca=='VISA')<td>SALDO DISCOVER</td>@endif
        @if($marca=='DISCOVER')<td>SALDO VISA</td>@endif

        @if($marca=='DINERS')<td>TKAPITAL DISCOVER</td>@endif
        @if($marca=='VISA')<td>TKAPITAL DISCOVER</td>@endif
        @if($marca=='DISCOVER')<td>KAPITAL VISA</td>@endif

        @if($marca=='DINERS')<td>RIESGO DISCOVER</td>@endif
        @if($marca=='VISA')<td>RIESGO DISCOVER</td>@endif
        @if($marca=='DISCOVER')<td>RIESGO VISA</td>@endif

        <td>KAPITAL</td>
        <td>RIESGO TOTAL</td>
        <td>NIVEL</td>
        <td>GESTION REALIZADA</td>
        <td>BURO DE CREDITO</td>
        <td>CPD</td>

        <td>PARAMETRO</td>
        <td>DECISION FINAL</td>
        <td>DECISION EJECUTIVO</td>
        <td>LLAMADA</td>
        <td>VISITA</td>
        <td>PROTOCOLO</td>

        @if($marca=='VISA')<td>PENDIENTE_ACTUAL</td>@endif
        @if($marca=='DISCOVER')<td>PRODUCTO</td>@endif
        @if($marca=='DISCOVER')<td>ACTU</td>@endif

        <td>D30</td>
        <td>D60</td>
        <td>D90</td>
        @if($marca=='DINERS')<td>DMAS90</td>@endif
        @if($marca=='DISCOVER')<td>DMAS90</td>@endif

        @if($marca=='VISA')<td>MASD90</td>@endif
        @if($marca=='VISA')<td>PENDIENTE_MORA</td>@endif
        @if($marca=='VISA')<td>TOTAL_PENDIENTE</td>@endif
        @if($marca=='DISCOVER')<td>MORA</td>@endif
        @if($marca=='DISCOVER')<td>SALDO</td>@endif
        @if($marca=='DISCOVER')<td>NOMBREEJECUTIVO_DOMICILIO</td>@endif

        @if($marca=='DINERS')<td>STOTMO</td>@endif
        @if($marca=='DINERS')<td>ACTUALES</td>@endif
        @if($marca=='DINERS')<td>STOTOT</td>@endif

        <td>CICLO</td>

        @if($marca=='DINERS')<td>PRODUCTO</td>@endif
        @if($marca=='VISA')<td>PRODUCTO</td>@endif

        <td>MOTIVO_1</td>

        @if($marca=='DINERS')<td>EJECUTIVO</td>@endif
        @if($marca=='VISA')<td>EJECUTIVOVISITA</td>@endif

        @if($marca=='DINERS')<td>VINCULADO</td>@endif
        @if($marca=='VISA')<td>VinculadoPrincipal</td>@endif
        @if($marca=='DISCOVER')<td>VINCULADO</td>@endif

        <td>OBSERVACIONESDEVINCULACION</td>
        @if($marca=='DINERS')<td>CLIENTEVIP</td><td>EMPLEADO</td>@endif
        @if($marca=='VISA')<td>EMPLEADO</td><td>CLIENTEVIP</td>@endif
        @if($marca=='DISCOVER')<td>EMPLEADO</td><td>CLIENTEVIP</td>@endif
        <td>CEDULACyg</td>
        <td>NOMSOC_Cyg</td>
        <td>CODPRI_DC_Cyg</td>
        <td>CODPRI_DIS_Cyg</td>
        <td>CODPRI_ID_Cyg</td>
        <td>MES-3</td>
        <td>MES-2</td>
        <td>MES-1</td>
        <td>AJUST_ULT_MES</td>
        <td>VALOR TOTAL</td>
        <td>No. AJUSTES</td>
        @if($marca=='DINERS')<td>PAGO MARZO</td>@endif
        @if($marca=='VISA' || $marca=='DISCOVER')<td>PAGOS MARZO</td>@endif

        <td>CALLE PRINCIPAL</td>
        <td>NUMERACION</td>
        <td>CALLE SECUNDARIA</td>
        <td>SECTOR</td>
        <td>PARROQUIA</td>
        <td>CIUDAD </td>
        <td>CANTON</td>
        <td>PROVINCIA</td>
        <td>REFERENCIA</td>
        <td>MAIL</td>
        <td>RANGO_RIESGO</td>
        <td>RESPONSABLE</td>
        <td>VISITA DOMICILO</td>
        <td>VISITA OFICINA</td>
        <td>DATABOK</td>
        <td>PAGINAS INVESTIGACION</td>
        <td>CONYUGUE</td>
        <td>CIRCULO FAMILIAR</td>
        <td>MIGRACION</td>
        <td>RESULTADO FINAL</td>

    </tr>
    </thead>
    <tbody>
    <?php $i=1;?>
    @foreach($reportes as $reporte)
    <tr>
        <td>{{$i}}</td>
        <td>{{$reporte['nombre']}}</td>
        <td>{{$reporte['cedula']}}</td>


        <td>{{$reporte['mes_ing']}}</td>
        <td>{{$reporte['ano_ing']}}</td>
        <td>{{$reporte['edad_total']}}</td>
        @if($marca=='DINERS')<td>{{$reporte['edad_recns']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['edad_final']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['edad_final']}}</td>@endif

        <td>{{$reporte['codret']}}</td>
        <td>{{$reporte['cargo']}}</td>
        <td>{{$reporte['ingreso']}}</td>

        <td>{{$reporte['cr']}}</td>
        @if($marca=='DINERS')<td>{{$reporte['fin_vehicular']}}</td>@endif
        @if($marca=='DINERS')<td>{{$reporte['cash_advance']}}</td>@endif
        <td>{{$reporte['tipo_refinanciacion']}}</td>
        <td>{{$reporte['contactabilidad']}}</td>
        <td>{{$reporte['motivo']}}</td>
        <td>{{$reporte['submotivo']}}</td>

        <td>{{$reporte['castigada_sis_financiero']}}</td>
        <td>{{$reporte['ciudad']}}</td>
        <td>{{$reporte['zona']}}</td>
        @if($marca=='DINERS')<td>{{$reporte['tkapital_diners']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['tkapital_visa']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['kapital_discover']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['riesgo_diners']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['riesgo_visa']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['riesgo_discover']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['cta_visa']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['cta_diners']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['cta_diners']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['ciclo_visa']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['ciclo_diners']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['ciclo_diners']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['mora_visa']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['mora_diners']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['mora_diners']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['saldo_visa']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['saldo_diners']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['saldo_diners']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['tkapital_visa']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['tkapital_diners']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['kapital_diners']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['riesgo_visa']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['riesgo_diners']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['riesgo_diners']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['cta_discover']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['cta_discover']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['cta_visa']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['ciclo_discover']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['ciclo_discover']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['ciclo_visa']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['mora_discover']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['mora_discover']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['mora_visa']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['saldo_discover']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['saldo_discover']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['saldo_visa']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['tkapital_discover']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['tkapital_discover']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['kapital_visa']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['riesgo_discover']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['riesgo_discover']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['riesgo_visa']}}</td>@endif

        <td>{{$reporte['kapital']}}</td>
        <td>{{$reporte['riesgo_total']}}</td>
        <td>{{$reporte['nivel']}}</td>
        <td><?php echo $reporte['gestion_realizada'];?></td>
        <td>{{$reporte['buro_de_credito']}}</td>
        <td>{{$reporte['cpd']}}</td>

        <td>{{$reporte['parametro']}}</td>
        <td>{{$reporte['decision_final']}}</td>
        <td>{{$reporte['decision_ejecutivo']}}</td>
        <td>{{$reporte['llamada']}}</td>
        <td>{{$reporte['visita']}}</td>
        <td>{{$reporte['protocolo']}}</td>

        @if($marca=='VISA')<td>{{$reporte['pendiente_actual']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['producto']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['actu']}}</td>@endif

        <td>{{$reporte['d30']}}</td>
        <td>{{$reporte['d60']}}</td>
        <td>{{$reporte['d90']}}</td>
        @if($marca=='DINERS')<td>{{$reporte['dmas90']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['dmas90']}}</td>@endif

        @if($marca=='VISA')<td>{{$reporte['masd90']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['pendiente_mora']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['total_pendiente']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['mora']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['saldo']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['nombreejecutivo_domicilio']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['stotmo']}}</td>@endif
        @if($marca=='DINERS')<td>{{$reporte['actuales']}}</td>@endif
        @if($marca=='DINERS')<td>{{$reporte['stotot']}}</td>@endif

        <td>{{$reporte['ciclo']}}</td>

        @if($marca=='DINERS')<td>{{$reporte['producto']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['producto']}}</td>@endif

        <td>{{$reporte['motivo_1']}}</td>

        @if($marca=='DINERS')<td>{{$reporte['ejecutivo']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['ejecutivovisita']}}</td>@endif

        @if($marca=='DINERS')<td>{{$reporte['vinculado']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['vinculadoprincipal']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['vinculado']}}</td>@endif

        <td>{{$reporte['observacionesdevinculacion']}}</td>
        @if($marca=='DINERS')<td>{{$reporte['clientevip']}}</td><td>{{$reporte['empleado']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['empleado']}}</td><td>{{$reporte['clientevip']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['empleado']}}</td><td>{{$reporte['clientevip']}}</td>@endif
        <td>{{$reporte['cedulacyg']}}</td>
        <td>{{$reporte['nomsoc_cyg']}}</td>
        <td>{{$reporte['codpri_dc_cyg']}}</td>
        <td>{{$reporte['codpri_dis_cyg']}}</td>
        <td>{{$reporte['codpri_id_cyg']}}</td>
        <td>{{$reporte['mes_3']}}</td>
        <td>{{$reporte['mes_2']}}</td>
        <td>{{$reporte['mes_1']}}</td>
        <td>{{$reporte['ajust_ult_mes']}}</td>
        <td>{{$reporte['valor_total']}}</td>
        <td>{{$reporte['no_ajustes']}}</td>
        @if($marca=='DINERS')<td>{{$reporte['pago_marzo']}}</td>@endif
        @if($marca=='VISA')<td>{{$reporte['pagos_marzo']}}</td>@endif
        @if($marca=='DISCOVER')<td>{{$reporte['pagos_marzo']}}</td>@endif

        <td>{{$reporte['calle_principal']}}</td>
        <td>{{$reporte['numeracion']}}</td>
        <td>{{$reporte['calle_secundaria']}}</td>
        <td>{{$reporte['sector']}}</td>
        <td>{{$reporte['parroquia']}}</td>
        <td>{{$reporte['ciudad2']}}</td>
        <td>{{$reporte['canton']}}</td>
        <td>{{$reporte['provincia']}}</td>
        <td>{{$reporte['referencia']}}</td>
        <td>{{$reporte['mail']}}</td>
        <td>{{$reporte['rango_riesgo']}}</td>
        <td>{{$reporte['agente_actual']}}</td>
        <td>{{$reporte['visita_domicilio']}}</td>
        <td>{{$reporte['visita_oficina']}}</td>
        <td>{{$reporte['databook']}}</td>
        <td>{{$reporte['paginas_investigacion']}}</td>
        <td>{{$reporte['estado_civil']}}</td>
        <td>{{$reporte['circulo_familiar']}}</td>
        <td>{{$reporte['migracion']}}</td>
        <td>{{$reporte['resultado_final']}}</td>
    </tr>
    <?php $i++;?>
    @endforeach
    </tbody>
</table>