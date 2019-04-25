var interes_calculado_diners;
var interes_calculado_visa;
var interes_calculado_discover;
function calcularDiners(){
    console.clear();


    var abono_efectivo_sistema_chk=document.getElementById('abono_efectivo_sistema_chk').checked;
    var abono_negociador = parseFloat(document.getElementById('abono_negociador').value);

    //Validación abono negociador ingresa con valor nulo o vacío
    if(isNaN(abono_negociador)){abono_negociador=0;}
    //Fin Validación

    var intereses_facturados = parseFloat(document.getElementById('intereses_facturados_h').value).toFixed(2);
    var abono_efectivo_sistema=0;
    if (abono_efectivo_sistema_chk==true) {
        abono_efectivo_sistema = parseFloat(document.getElementById('abono_efectivo_sistema_h').value);
    }else{
        abono_efectivo_sistema = 0;
    }
    var abono_total=0;

    var negociacion_especial_chk=document.getElementById('negociacion_especial_chk').checked;
    var saldo_interes=parseFloat(document.getElementById('saldo_interes').value);

    if (negociacion_especial_chk==true) {
        document.getElementById('saldo_interes').disabled = false;
        saldo_interes = parseFloat(document.getElementById('saldo_interes').value);
        if(isNaN(saldo_interes)){saldo_interes=0;}
    }else{
        document.getElementById('saldo_interes').disabled = true;
        document.getElementById('saldo_interes').value = 0;
        saldo_interes=parseFloat(0);
    }
    console.log("abono_total + saldo_interes: "+abono_total +" - "+ saldo_interes);

    abono_total = parseFloat(saldo_interes+abono_efectivo_sistema+abono_negociador).toFixed(2);

    console.log("abono_total: "+abono_total);
    document.getElementById('abono_total').innerHTML = abono_total ;
    document.getElementById('abono_total_h').value = abono_total ;
    console.log("intereses_facturados: "+intereses_facturados);
    if (parseFloat(abono_total)>parseFloat(intereses_facturados)){
        console.log("abono_total>intereses_facturados si ");
        document.getElementById('alerta').className= "alert alert-success";
        document.getElementById('alerta').innerHTML = "Abono si cubre interes de <strong>$"+intereses_facturados+"</strong>";
    }else{
        console.log("abono_total>intereses_facturados no ");
        document.getElementById('alerta').className= "alert alert-danger";
        document.getElementById('alerta').innerHTML = "Abono no cubre interes de <strong>$"+intereses_facturados+"</strong>";
    }
    document.getElementById('abono_total').innerHTML = abono_total;
    document.getElementById('abono_total_h').value = abono_total;

    //Validacion Saldos Fac. Despues de abono
    var saldo90ymas=parseFloat(document.getElementById('saldo90ymas_h').value).toFixed(2);
    var abono_validador=0;

    //console.log("saldo90ymas: "+saldo90ymas);
    //console.log("abono_total-intereses_facturados: "+(abono_total-intereses_facturados));
    if (saldo90ymas>0.01){
        //abono_validador=abono_total-intereses_facturados;
        if ((abono_total-intereses_facturados)<0){
            abono_validador=0;
        }else{
            abono_validador=abono_total-0;
        }
    }else{
        //console.log("else");
        abono_validador=abono_total;
    }

    //console.log("abono_total: "+abono_total);
    //console.log("intereses_facturados: "+intereses_facturados);
    console.log("abono_validador: "+abono_validador);

    var saldo90ymas_despues_abono=0;
    if(abono_validador>saldo90ymas){
        saldo90ymas_despues_abono=0;
    }else{
        saldo90ymas_despues_abono=saldo90ymas-abono_validador;
    }

    saldo90ymas_despues_abono=saldo90ymas_despues_abono.toFixed(2);
    document.getElementById('saldoa90ymas_despues_abono_h').value=saldo90ymas_despues_abono;
    document.getElementById('saldoa90ymas_despues_abono').innerHTML=saldo90ymas_despues_abono;

    //Validacion  Saldo fAct despues de abono (Saldo a 60 dias)
    var validador60dias_despues_abono=0;
    if((abono_validador-saldo90ymas)<0){
        validador60dias_despues_abono=0;
    }else{
        validador60dias_despues_abono=abono_validador-saldo90ymas;
    }
    console.log("validador60dias_despues_abono: "+validador60dias_despues_abono);

    var saldo60dias=parseFloat(document.getElementById('saldo60dias_h').value);
    var saldoa60dias_despues_abono=0;
    if(validador60dias_despues_abono>saldo60dias){
        saldoa60dias_despues_abono=0;
    }else{
        saldoa60dias_despues_abono=saldo60dias-validador60dias_despues_abono;
    }


    console.log("saldoa60dias_despues_abono: "+saldoa60dias_despues_abono);

    document.getElementById('saldoa60dias_despues_abono_h').value=saldoa60dias_despues_abono;
    document.getElementById('saldoa60dias_despues_abono').innerHTML=saldoa60dias_despues_abono;

    //Validacion  Saldo fact despues de abono (Saldo a 30 dias)
    var validador30dias_despues_abono=0;
    var saldo60dias=parseFloat(document.getElementById('saldo60dias_h').value);

    if((validador60dias_despues_abono-saldo60dias)<0){
        validador30dias_despues_abono=0;
    }else{
        validador30dias_despues_abono=validador60dias_despues_abono-saldo60dias;
    }
    console.log("validador30dias_despues_abono: "+validador30dias_despues_abono);

    var saldoa30dias_despues_abono=0;
    var saldo30dias=parseFloat(document.getElementById('saldo30dias_h').value);
    if(validador30dias_despues_abono>saldo30dias){
        saldoa30dias_despues_abono=0;
    }else{
        saldoa30dias_despues_abono=saldo30dias-validador30dias_despues_abono;
    }
    document.getElementById('saldoa30dias_despues_abono_h').value=saldoa30dias_despues_abono.toFixed(2);;
    document.getElementById('saldoa30dias_despues_abono').innerHTML=saldoa30dias_despues_abono.toFixed(2);;

    //Validacion  Saldo Actuales despues de abono
    var validador_saldo_actual=0;
    var saldo30dias=parseFloat(document.getElementById('saldo30dias_h').value);

    if((validador30dias_despues_abono-saldo30dias)<0){
        validador_saldo_actual=0;
    }else{
        validador_saldo_actual=(validador30dias_despues_abono-saldo30dias).toFixed(2);
    }
    console.log("validador_saldo_actual: "+validador_saldo_actual);

    var saldos_actuales_despues_abono=0;
    var saldos_actuales=parseFloat(document.getElementById('saldos_actuales_h').value);
    if(validador_saldo_actual>saldos_actuales){
        saldos_actuales_despues_abono=0;
    }else{
        saldos_actuales_despues_abono=(parseFloat(saldos_actuales)-parseFloat(validador_saldo_actual)).toFixed(2);
    }
    console.log("saldos_actuales_despues_abono: "+saldos_actuales_despues_abono);
    document.getElementById('saldos_actuales_despues_abono_h').value=saldos_actuales_despues_abono;
    document.getElementById('saldos_actuales_despues_abono').innerHTML=saldos_actuales_despues_abono;

    //Validaciones Financiamiento
    var novacion="";
    if(saldoa30dias_despues_abono<0.01){
        novacion="NOVACION";
    }else{
        if(saldo90ymas_despues_abono<0.01){
            novacion="REFINANCIACION";
        }else{
            novacion="REESTRUCTURACION";
        }
    }
    console.log("novacion: "+novacion);
    document.getElementById('tipofinaciamiento_h').value=novacion;
    document.getElementById('tipofinaciamiento').innerHTML=novacion;

    //Validaciones Totales total_chk
    //Validaciones valor a financiar
    var total_chk=document.getElementById('total_chk').checked;
    var valor_a_financiar =0;
    var deuda_actual = parseFloat(document.getElementById('deuda_actual_h').value);
    var intereses_por_facturar = document.getElementById('intereses_por_facturar_h').value;
    var corrientes_por_facturar = parseFloat(document.getElementById('corrientes_por_facturar_h').value);
    var gastos_de_cobranza = parseFloat(document.getElementById('gastos_de_cobranza_h').value);
    var valor_pre_cancelacion_diferidos= parseFloat(document.getElementById('valor_pre_cancelacion_diferidos_h').value);
    var valor_otras_tarjetas = parseFloat(document.getElementById('valor_otras_tarjetas_h').value);

    if(total_chk==true ){
        if(abono_total==0){
            valor_a_financiar = 0;
        }else{
            valor_a_financiar = parseFloat(deuda_actual) + parseFloat(intereses_por_facturar) + parseFloat(corrientes_por_facturar) + parseFloat(gastos_de_cobranza) + parseFloat(valor_pre_cancelacion_diferidos) + parseFloat(valor_otras_tarjetas) - parseFloat(abono_total);
        }
        console.log("deuda_actual+intereses_por_facturar+corrientes_por_facturar+gastos_de_cobranza+valor_pre_cancelacion_diferidos+valor_otras_tarjetas-abono_total: "+valor_a_financiar);
        valor_a_financiar = valor_a_financiar.toFixed(2);
        console.log("valor_a_financiar: "+valor_a_financiar+" abono total: "+abono_total );
    }else{
        valor_a_financiar= valor_a_financiar.toFixed(2);
        console.log("total: falso");
    }
    document.getElementById('valor_a_financiar_h').value = valor_a_financiar;
    document.getElementById('valor_a_financiar').innerHTML = valor_a_financiar;


    //Validaciones total intereses
    interes_calculado_diners = parseFloat(document.getElementById('factor_calculado_h').value).toFixed(12);
    interes_calculado_diners = (interes_calculado_diners/100).toFixed(12);

    var total_intereses = 0;
    var plazo_financiamiento = document.getElementById("plazo_financiamiento").value;

    console.log("interes_calculado_diners: "+interes_calculado_diners);
    console.log('plazo_financiamiento: '+plazo_financiamiento);
    console.log('valor_a_financiar: '+valor_a_financiar);
    total_intereses = parseFloat(valor_a_financiar*interes_calculado_diners).toFixed(2);
    console.log('total_intereses: '+total_intereses);

    document.getElementById('total_intereses_h').value = total_intereses;
    document.getElementById('total_intereses').innerHTML = total_intereses;

    //Validaciones total financiamiento
    var total_financiamiento = (parseFloat(valor_a_financiar)+parseFloat(total_intereses)).toFixed(2);
    console.log("total_financiamiento: "+total_financiamiento);
    document.getElementById('total_financiamiento_h').value = total_financiamiento;
    document.getElementById('total_financiamiento').innerHTML = total_financiamiento;

    //Validacion valor cuota mensual
    var exigible_chk = document.getElementById('exigible_chk').checked;
    var valor_cuota_mensual=0;
    valor_cuota_mensual = (parseFloat(total_financiamiento)/parseFloat(plazo_financiamiento)).toFixed(2);
    if(valor_cuota_mensual=="Infinity" || valor_cuota_mensual=="-Infinity"){valor_cuota_mensual=0;}
    if(total_chk==true && exigible_chk==true){
    }else{
        if(valor_cuota_mensual!=0){
            valor_cuota_mensual= parseFloat(total_financiamiento/plazo_financiamiento).toFixed(2);
        }else{
            valor_cuota_mensual=0;
        }
    }
    if(isNaN(valor_cuota_mensual)){valor_cuota_mensual=0;}
    console.log("valor_cuota_mensual: "+valor_cuota_mensual);
    document.getElementById('valor_cuota_mensual_h').value = parseFloat(valor_cuota_mensual).toFixed(2);
    document.getElementById('valor_cuota_mensual').innerHTML = parseFloat(valor_cuota_mensual).toFixed(2);

    //Verificamos campos obligatoriosDiners llenos
    obligatoriosDiners()

}

function calcularVisa(){
    console.clear();

    var abono_efectivo_sistema_chk=document.getElementById('abono_efectivo_sistema_chk_v').checked;
    var abono_negociador = parseFloat(document.getElementById('abono_negociador_v').value);

    //Validación abono negociador ingresa con valor nulo o vacío
    if(isNaN(abono_negociador)){abono_negociador=0;}
    //Fin Validación

    var intereses_facturados = parseFloat(document.getElementById('intereses_facturados_h_v').value).toFixed(2);
    var abono_efectivo_sistema=0;
    if (abono_efectivo_sistema_chk==true) {
        abono_efectivo_sistema = parseFloat(document.getElementById('abono_efectivo_sistema_h_v').value);
    }else{
        abono_efectivo_sistema = 0;
    }
    var abono_total=0;

    var negociacion_especial_chk=document.getElementById('negociacion_especial_chk_v').checked;
    var saldo_interes=parseFloat(document.getElementById('saldo_interes_v').value);

    if (negociacion_especial_chk==true) {
        document.getElementById('saldo_interes_v').disabled = false;
        saldo_interes = parseFloat(document.getElementById('saldo_interes_v').value);
        if(isNaN(saldo_interes)){saldo_interes=0;}
    }else{
        document.getElementById('saldo_interes_v').disabled = true;
        document.getElementById('saldo_interes_v').value = 0;
        saldo_interes=parseFloat(0);
    }
    console.log("abono_total + saldo_interes: "+abono_total +" - "+ saldo_interes);

    abono_total = parseFloat(saldo_interes+abono_efectivo_sistema+abono_negociador).toFixed(2);
    console.log("abono_total_v: "+abono_total);
    document.getElementById('abono_total_v').innerHTML = abono_total ;
    document.getElementById('abono_total_h_v').value = abono_total ;
    console.log("intereses_facturados: "+intereses_facturados);
    if (parseFloat(abono_total)>parseFloat(intereses_facturados)){
        console.log("abono_total>intereses_facturados si ");
        document.getElementById('alerta_v').className= "alert alert-success";
        document.getElementById('alerta_v').innerHTML = "Abono si cubre interes de <strong>$"+intereses_facturados+"</strong>";
    }else{
        console.log("abono_total>intereses_facturados no ");
        document.getElementById('abono_total_v').innerHTML = abono_total;
        document.getElementById('abono_total_h_v').value = abono_total;
        document.getElementById('alerta_v').className= "alert alert-danger";
        document.getElementById('alerta_v').innerHTML = "Abono no cubre interes de <strong>$"+intereses_facturados+"</strong>";
    }

    //Validacion Saldos Fac. Despues de abono
    var saldo90ymas=parseFloat(document.getElementById('saldo90ymas_h_v').value).toFixed(2);
    var abono_validador=0;

    //console.log("saldo90ymas: "+saldo90ymas);
    //console.log("abono_total-intereses_facturados: "+(abono_total-intereses_facturados));
    if (saldo90ymas>0.01){
        //abono_validador=abono_total-intereses_facturados;
        if ((abono_total-intereses_facturados)<0){
            abono_validador=0;
        }else{
            abono_validador=abono_total-0;
        }
    }else{
        //console.log("else");
        abono_validador=abono_total;
    }

    //console.log("abono_total: "+abono_total);
    //console.log("intereses_facturados: "+intereses_facturados);
    console.log("abono_validador: "+abono_validador);

    var saldo90ymas_despues_abono=0;
    if(abono_validador>saldo90ymas){
        saldo90ymas_despues_abono=0;
    }else{
        saldo90ymas_despues_abono=saldo90ymas-abono_validador;
    }

    saldo90ymas_despues_abono=saldo90ymas_despues_abono.toFixed(2);
    document.getElementById('saldoa90ymas_despues_abono_h_v').value=saldo90ymas_despues_abono;
    document.getElementById('saldoa90ymas_despues_abono_v').innerHTML=saldo90ymas_despues_abono;

    //Validacion  Saldo fAct despues de abono (Saldo a 60 dias)
    var validador60dias_despues_abono=0;
    if((abono_validador-saldo90ymas)<0){
        validador60dias_despues_abono=0;
    }else{
        validador60dias_despues_abono=abono_validador-saldo90ymas;
    }
    console.log("validador60dias_despues_abono: "+validador60dias_despues_abono);

    var saldo60dias=parseFloat(document.getElementById('saldo60dias_h_v').value);
    var saldoa60dias_despues_abono=0;
    if(validador60dias_despues_abono>saldo60dias){
        saldoa60dias_despues_abono=0;
    }else{
        saldoa60dias_despues_abono=saldo60dias-validador60dias_despues_abono;
    }
    console.log("saldoa60dias_despues_abono: "+saldoa60dias_despues_abono);

    document.getElementById('saldoa60dias_despues_abono_h_v').value=saldoa60dias_despues_abono;
    document.getElementById('saldoa60dias_despues_abono_v').innerHTML=saldoa60dias_despues_abono;

    //Validacion  Saldo fact despues de abono (Saldo a 30 dias)
    var validador30dias_despues_abono=0;
    var saldo60dias=parseFloat(document.getElementById('saldo60dias_h_v').value);

    if((validador60dias_despues_abono-saldo60dias)<0){
        validador30dias_despues_abono=0;
    }else{
        validador30dias_despues_abono=validador60dias_despues_abono-saldo60dias;
    }
    console.log("validador30dias_despues_abono: "+validador30dias_despues_abono);

    var saldoa30dias_despues_abono=0;
    var saldo30dias=parseFloat(document.getElementById('saldo30dias_h_v').value);
    if(validador30dias_despues_abono>saldo30dias){
        saldoa30dias_despues_abono=0;
    }else{
        saldoa30dias_despues_abono=saldo30dias-validador30dias_despues_abono;
    }
    document.getElementById('saldoa30dias_despues_abono_h_v').value=saldoa30dias_despues_abono.toFixed(2);;
    document.getElementById('saldoa30dias_despues_abono_v').innerHTML=saldoa30dias_despues_abono.toFixed(2);;

    //Validacion  Saldo Actuales despues de abono
    var validador_saldo_actual=0;
    var saldo30dias=parseFloat(document.getElementById('saldo30dias_h_v').value);

    if((validador30dias_despues_abono-saldo30dias)<0){
        validador_saldo_actual=0;
    }else{
        validador_saldo_actual=(validador30dias_despues_abono-saldo30dias).toFixed(2);
    }
    console.log("validador_saldo_actual: "+validador_saldo_actual);

    var saldos_actuales_despues_abono=0;
    var saldos_actuales=parseFloat(document.getElementById('saldos_actuales_h_v').value);
    if(validador_saldo_actual>saldos_actuales){
        saldos_actuales_despues_abono=0;
    }else{
        saldos_actuales_despues_abono=(parseFloat(saldos_actuales)-parseFloat(validador_saldo_actual)).toFixed(2);
    }
    console.log("saldos_actuales_despues_abono: "+saldos_actuales_despues_abono);
    document.getElementById('saldos_actuales_despues_abono_h_v').value=saldos_actuales_despues_abono;
    document.getElementById('saldos_actuales_despues_abono_v').innerHTML=saldos_actuales_despues_abono;

    //Validaciones Financiamiento
    var novacion="";
    if(saldoa30dias_despues_abono<0.01){
        novacion="NOVACION";
    }else{
        if(saldo90ymas_despues_abono<0.01){
            novacion="REFINANCIACION";
        }else{
            novacion="REESTRUCTURACION";
        }
    }
    console.log("novacion: "+novacion);
    document.getElementById('tipofinaciamiento_h_v').value=novacion;
    document.getElementById('tipofinaciamiento_v').innerHTML=novacion;

    //Validaciones Totales total_chk
    //Validaciones valor a financiar
    var total_chk=document.getElementById('total_chk_v').checked;
    var valor_a_financiar =0;
    var deuda_actual = parseFloat(document.getElementById('deuda_actual_h_v').value);
    var intereses_por_facturar = document.getElementById('intereses_por_facturar_h_v').value;
    var corrientes_por_facturar = parseFloat(document.getElementById('corrientes_por_facturar_h_v').value);
    var gastos_de_cobranza = parseFloat(document.getElementById('gastos_de_cobranza_h_v').value);
    var valor_pre_cancelacion_diferidos= parseFloat(document.getElementById('valor_pre_cancelacion_diferidos_h_v').value);
    var valor_otras_tarjetas = parseFloat(document.getElementById('valor_otras_tarjetas_h_v').value);

    if (total_chk==true ){
        if(abono_total==0){
            valor_a_financiar = 0;
        }else{
            valor_a_financiar = parseFloat(deuda_actual) + parseFloat(intereses_por_facturar) + parseFloat(corrientes_por_facturar) + parseFloat(gastos_de_cobranza) + parseFloat(valor_pre_cancelacion_diferidos) + parseFloat(valor_otras_tarjetas) - parseFloat(abono_total);
        }

        console.log("deuda_actual: "+deuda_actual+"intereses_por_facturar: "+intereses_por_facturar+"corrientes_por_facturar: "+corrientes_por_facturar+"gastos_de_cobranza: "+gastos_de_cobranza+"valor_pre_cancelacion_diferidos: "+valor_pre_cancelacion_diferidos+"valor_otras_tarjetas: "+valor_otras_tarjetas+"abono_total: "+abono_total+" valor_a_financiar:"+valor_a_financiar);
        valor_a_financiar = valor_a_financiar.toFixed(2);
        console.log("valor_a_financiar: "+valor_a_financiar+" abono total: "+abono_total );
    }else{
        valor_a_financiar= valor_a_financiar.toFixed(2);
        console.log("total: falso");
    }
    document.getElementById('valor_a_financiar_h_v').value = valor_a_financiar;
    document.getElementById('valor_a_financiar_v').innerHTML = valor_a_financiar;


    //Validaciones total intereses
    interes_calculado_visa = parseFloat(document.getElementById('factor_calculado_h_v').value).toFixed(12);
    interes_calculado_visa = (interes_calculado_visa/100).toFixed(12);

    var total_intereses = 0;
    var plazo_financiamiento = document.getElementById("plazo_financiamiento_v").value;

    console.log("interes_calculado_visa: "+interes_calculado_visa);
    console.log('plazo_financiamiento: '+plazo_financiamiento);
    console.log('valor_a_financiar: '+valor_a_financiar);
    total_intereses = parseFloat(valor_a_financiar*interes_calculado_visa).toFixed(2);
    console.log('total_intereses: '+total_intereses);

    document.getElementById('total_intereses_h_v').value = total_intereses;
    document.getElementById('total_intereses_v').innerHTML = total_intereses;

    //Validaciones total financiamiento
    var total_financiamiento = (parseFloat(valor_a_financiar)+parseFloat(total_intereses)).toFixed(2);
    console.log("total_financiamiento: "+total_financiamiento);
    document.getElementById('total_financiamiento_h_v').value = total_financiamiento;
    document.getElementById('total_financiamiento_v').innerHTML = total_financiamiento;

    //Validacion valor cuota mensual
    var exigible_chk = document.getElementById('exigible_chk_v').checked;
    var valor_cuota_mensual=0;
    valor_cuota_mensual = (parseFloat(total_financiamiento)/parseFloat(plazo_financiamiento)).toFixed(2);
    if(valor_cuota_mensual=="Infinity" || valor_cuota_mensual=="-Infinity"){valor_cuota_mensual=0;}
    if(total_chk==true && exigible_chk==true){
    }else{
        if(valor_cuota_mensual!=0){
            valor_cuota_mensual= parseFloat(total_financiamiento/plazo_financiamiento).toFixed(2);
        }else{
            valor_cuota_mensual=0;
        }
    }

    if(plazo_financiamiento>=2 && plazo_financiamiento<=24) {
        document.getElementById("formato_consolidado_v").value='rotativo';
    }
    if(plazo_financiamiento>=36 && plazo_financiamiento<=60) {
        document.getElementById("formato_consolidado_v").value='refinanciamiento';
    }


    if(isNaN(valor_cuota_mensual)){valor_cuota_mensual=0;}
    console.log("valor_cuota_mensual: "+valor_cuota_mensual);
    document.getElementById('valor_cuota_mensual_h_v').value = parseFloat(valor_cuota_mensual).toFixed(2);
    document.getElementById('valor_cuota_mensual_v').innerHTML = parseFloat(valor_cuota_mensual).toFixed(2);

    //Verificamos campos obligatoriosVisa llenos
    obligatoriosVisa()

}

function calcularDiscover(){
    console.clear();

    var abono_efectivo_sistema_chk=document.getElementById('abono_efectivo_sistema_chk_dis').checked;
    var abono_negociador = parseFloat(document.getElementById('abono_negociador_dis').value);

    //Validación abono negociador ingresa con valor nulo o vacío
    if(isNaN(abono_negociador)){abono_negociador=0;}
    //Fin Validación

    var intereses_facturados = parseFloat(document.getElementById('intereses_facturados_h_dis').value).toFixed(2);
    var abono_efectivo_sistema=0;
    if (abono_efectivo_sistema_chk==true) {
        abono_efectivo_sistema = parseFloat(document.getElementById('abono_efectivo_sistema_h_dis').value);
    }else{
        abono_efectivo_sistema = 0;
    }
    var abono_total=0;

    var negociacion_especial_chk=document.getElementById('negociacion_especial_chk_dis').checked;
    var saldo_interes=parseFloat(document.getElementById('saldo_interes_dis').value);

    if (negociacion_especial_chk==true) {
        document.getElementById('saldo_interes_dis').disabled = false;
        saldo_interes = parseFloat(document.getElementById('saldo_interes_dis').value);
        if(isNaN(saldo_interes)){saldo_interes=0;}
    }else{
        document.getElementById('saldo_interes_dis').disabled = true;
        document.getElementById('saldo_interes_dis').value = 0;
        saldo_interes=parseFloat(0);
    }
    console.log("abono_total + saldo_interes: "+abono_total +" - "+ saldo_interes);

    abono_total = parseFloat(saldo_interes+abono_efectivo_sistema+abono_negociador).toFixed(2);
    console.log("abono_total_dis: "+abono_total);
    document.getElementById('abono_total_dis').innerHTML = abono_total ;
    document.getElementById('abono_total_h_dis').value = abono_total ;
    console.log("intereses_facturados: "+intereses_facturados);
    if (parseFloat(abono_total)>parseFloat(intereses_facturados)){
        console.log("abono_total>intereses_facturados si ");
        document.getElementById('alerta_dis').className= "alert alert-success";
        document.getElementById('alerta_dis').innerHTML = "Abono si cubre interes de <strong>$"+intereses_facturados+"</strong>";
    }else{
        console.log("abono_total>intereses_facturados no ");
        document.getElementById('abono_total_dis').innerHTML = abono_total;
        document.getElementById('abono_total_h_dis').value = abono_total;
        document.getElementById('alerta_dis').className= "alert alert-danger";
        document.getElementById('alerta_dis').innerHTML = "Abono no cubre interes de <strong>$"+intereses_facturados+"</strong>";
    }

    //Validacion Saldos Fac. Despues de abono
    var saldo90ymas=parseFloat(document.getElementById('saldo90ymas_h_dis').value).toFixed(2);
    var abono_validador=0;

    //console.log("saldo90ymas: "+saldo90ymas);
    //console.log("abono_total-intereses_facturados: "+(abono_total-intereses_facturados));
    if (saldo90ymas>0.01){
        //abono_validador=abono_total-intereses_facturados;
        if ((abono_total-intereses_facturados)<0){
            abono_validador=0;
        }else{
            abono_validador=abono_total-0;
        }
    }else{
        //console.log("else");
        abono_validador=abono_total;
    }

    //console.log("abono_total: "+abono_total);
    //console.log("intereses_facturados: "+intereses_facturados);
    console.log("abono_validador: "+abono_validador);

    var saldo90ymas_despues_abono=0;
    if(abono_validador>saldo90ymas){
        saldo90ymas_despues_abono=0;
    }else{
        saldo90ymas_despues_abono=saldo90ymas-abono_validador;
    }

    saldo90ymas_despues_abono=saldo90ymas_despues_abono.toFixed(2);
    document.getElementById('saldoa90ymas_despues_abono_h_dis').value=saldo90ymas_despues_abono;
    document.getElementById('saldoa90ymas_despues_abono_dis').innerHTML=saldo90ymas_despues_abono;

    //Validacion  Saldo fAct despues de abono (Saldo a 60 dias)
    var validador60dias_despues_abono=0;
    if((abono_validador-saldo90ymas)<0){
        validador60dias_despues_abono=0;
    }else{
        validador60dias_despues_abono=abono_validador-saldo90ymas;
    }
    console.log("validador60dias_despues_abono: "+validador60dias_despues_abono);

    var saldo60dias=parseFloat(document.getElementById('saldo60dias_h_dis').value);
    var saldoa60dias_despues_abono=0;
    if(validador60dias_despues_abono>saldo60dias){
        saldoa60dias_despues_abono=0;
    }else{
        saldoa60dias_despues_abono=saldo60dias-validador60dias_despues_abono;
    }
    console.log("saldoa60dias_despues_abono: "+saldoa60dias_despues_abono);

    document.getElementById('saldoa60dias_despues_abono_h_dis').value=saldoa60dias_despues_abono;
    document.getElementById('saldoa60dias_despues_abono_dis').innerHTML=saldoa60dias_despues_abono;

    //Validacion  Saldo fact despues de abono (Saldo a 30 dias)
    var validador30dias_despues_abono=0;
    var saldo60dias=parseFloat(document.getElementById('saldo60dias_h_dis').value);

    if((validador60dias_despues_abono-saldo60dias)<0){
        validador30dias_despues_abono=0;
    }else{
        validador30dias_despues_abono=validador60dias_despues_abono-saldo60dias;
    }
    console.log("validador30dias_despues_abono: "+validador30dias_despues_abono);

    var saldoa30dias_despues_abono=0;
    var saldo30dias=parseFloat(document.getElementById('saldo30dias_h_dis').value);
    if(validador30dias_despues_abono>saldo30dias){
        saldoa30dias_despues_abono=0;
    }else{
        saldoa30dias_despues_abono=saldo30dias-validador30dias_despues_abono;
    }
    document.getElementById('saldoa30dias_despues_abono_h_dis').value=saldoa30dias_despues_abono.toFixed(2);
    document.getElementById('saldoa30dias_despues_abono_dis').innerHTML=saldoa30dias_despues_abono.toFixed(2);

    //Validacion  Saldo Actuales despues de abono
    var validador_saldo_actual=0;
    var saldo30dias=parseFloat(document.getElementById('saldo30dias_h_dis').value);

    if((validador30dias_despues_abono-saldo30dias)<0){
        validador_saldo_actual=0;
    }else{
        validador_saldo_actual=(validador30dias_despues_abono-saldo30dias).toFixed(2);
    }
    console.log("validador_saldo_actual: "+validador_saldo_actual);

    var saldos_actuales_despues_abono=0;
    var saldos_actuales=parseFloat(document.getElementById('saldos_actuales_h_dis').value);
    if(validador_saldo_actual>saldos_actuales){
        saldos_actuales_despues_abono=0;
    }else{
        saldos_actuales_despues_abono=(parseFloat(saldos_actuales)-parseFloat(validador_saldo_actual)).toFixed(2);
    }
    console.log("saldos_actuales_despues_abono: "+saldos_actuales_despues_abono);
    document.getElementById('saldos_actuales_despues_abono_h_dis').value=saldos_actuales_despues_abono;
    document.getElementById('saldos_actuales_despues_abono_dis').innerHTML=saldos_actuales_despues_abono;

    //Validaciones Financiamiento
    var novacion="";
    if(saldoa30dias_despues_abono<0.01){
        novacion="NOVACION";
    }else{
        if(saldo90ymas_despues_abono<0.01){
            novacion="REFINANCIACION";
        }else{
            novacion="REESTRUCTURACION";
        }
    }
    console.log("novacion: "+novacion);
    document.getElementById('tipofinaciamiento_h_dis').value=novacion;
    document.getElementById('tipofinaciamiento_dis').innerHTML=novacion;

    //Validaciones Totales total_chk
    //Validaciones valor a financiar
    var total_chk=document.getElementById('total_chk_dis').checked;
    var valor_a_financiar =0;
    var deuda_actual = parseFloat(document.getElementById('deuda_actual_h_dis').value);
    var intereses_por_facturar = document.getElementById('intereses_por_facturar_h_dis').value;
    var corrientes_por_facturar = parseFloat(document.getElementById('corrientes_por_facturar_h_dis').value);
    var gastos_de_cobranza = parseFloat(document.getElementById('gastos_de_cobranza_h_dis').value);
    var valor_pre_cancelacion_diferidos= parseFloat(document.getElementById('valor_pre_cancelacion_diferidos_h_dis').value);
    var valor_otras_tarjetas = parseFloat(document.getElementById('valor_otras_tarjetas_h_dis').value);

    if (total_chk==true ){
        if(abono_total==0){
            valor_a_financiar = 0;
        }else{
            valor_a_financiar = parseFloat(deuda_actual) + parseFloat(intereses_por_facturar) + parseFloat(corrientes_por_facturar) + parseFloat(gastos_de_cobranza) + parseFloat(valor_pre_cancelacion_diferidos) + parseFloat(valor_otras_tarjetas) - parseFloat(abono_total);
        }

        console.log("deuda_actual: "+deuda_actual+"intereses_por_facturar: "+intereses_por_facturar+"corrientes_por_facturar: "+corrientes_por_facturar+"gastos_de_cobranza: "+gastos_de_cobranza+"valor_pre_cancelacion_diferidos: "+valor_pre_cancelacion_diferidos+"valor_otras_tarjetas: "+valor_otras_tarjetas+"abono_total: "+abono_total+" valor_a_financiar:"+valor_a_financiar);
        valor_a_financiar = valor_a_financiar.toFixed(2);
        console.log("valor_a_financiar: "+valor_a_financiar+" abono total: "+abono_total );
    }else{
        valor_a_financiar= valor_a_financiar.toFixed(2);
        console.log("total: falso");
    }
    document.getElementById('valor_a_financiar_h_dis').value = valor_a_financiar;
    document.getElementById('valor_a_financiar_dis').innerHTML = valor_a_financiar;


    //Validaciones total intereses
    interes_calculado_discover = parseFloat(document.getElementById('factor_calculado_h_dis').value).toFixed(12);
    interes_calculado_discover = (interes_calculado_discover/100).toFixed(12);

    var total_intereses = 0;
    var plazo_financiamiento = document.getElementById("plazo_financiamiento_dis").value;

    console.log("interes_calculado_disisa: "+interes_calculado_discover);
    console.log('plazo_financiamiento: '+plazo_financiamiento);
    console.log('valor_a_financiar: '+valor_a_financiar);
    total_intereses = parseFloat(valor_a_financiar*interes_calculado_discover).toFixed(2);
    console.log('total_intereses: '+total_intereses);

    document.getElementById('total_intereses_h_dis').value = total_intereses;
    document.getElementById('total_intereses_dis').innerHTML = total_intereses;

    //Validaciones total financiamiento
    var total_financiamiento = (parseFloat(valor_a_financiar)+parseFloat(total_intereses)).toFixed(2);
    console.log("total_financiamiento: "+total_financiamiento);
    document.getElementById('total_financiamiento_h_dis').value = total_financiamiento;
    document.getElementById('total_financiamiento_dis').innerHTML = total_financiamiento;

    //Validacion valor cuota mensual
    var exigible_chk = document.getElementById('exigible_chk_dis').checked;
    var valor_cuota_mensual=0;
    valor_cuota_mensual = (parseFloat(total_financiamiento)/parseFloat(plazo_financiamiento)).toFixed(2);
    if(valor_cuota_mensual=="Infinity" || valor_cuota_mensual=="-Infinity"){valor_cuota_mensual=0;}
    if(total_chk==true && exigible_chk==true){
    }else{
        if(valor_cuota_mensual!=0){
            valor_cuota_mensual= parseFloat(total_financiamiento/plazo_financiamiento).toFixed(2);
        }else{
            valor_cuota_mensual=0;
        }
    }

    if(plazo_financiamiento>=2 && plazo_financiamiento<=36) {
        document.getElementById("formato_consolidado_dis").value='rotativo';
    }
    if(plazo_financiamiento>=48 && plazo_financiamiento<=60) {
        document.getElementById("formato_consolidado_dis").value='refinanciamiento';
    }

    if(isNaN(valor_cuota_mensual)){valor_cuota_mensual=0;}
    console.log("valor_cuota_mensual: "+valor_cuota_mensual);
    document.getElementById('valor_cuota_mensual_h_dis').value = parseFloat(valor_cuota_mensual).toFixed(2);
    document.getElementById('valor_cuota_mensual_dis').innerHTML = parseFloat(valor_cuota_mensual).toFixed(2);

    //Verificamos campos obligatoriosVisa llenos
    obligatoriosDiscover()

}

function obligatoriosDiners() {
    //Validacion plazo financiamiento
    if(document.getElementById("plazo_financiamiento").value==0){
        document.getElementById("plazo_financiamiento").style.backgroundColor="#f2dede";
    }else{
        document.getElementById("plazo_financiamiento").style.backgroundColor="#fff";
    }

    //Validacion motivo de no pago
    var motivo_no_pago = document.getElementById("motivo_no_pago").value;
    if(document.getElementById("motivo_no_pago").value==0){
        document.getElementById("motivo_no_pago").style.backgroundColor="#f2dede";
    }else{
        document.getElementById("motivo_no_pago").style.backgroundColor="#fff";
    }
}

function obligatoriosVisa() {
    //Validacion plazo financiamiento
    if(document.getElementById("plazo_financiamiento_v").value==0){
        document.getElementById("plazo_financiamiento_v").style.backgroundColor="#f2dede";
    }else{
        document.getElementById("plazo_financiamiento_v").style.backgroundColor="#fff";
    }

    //Validacion motivo de no pago
    var motivo_no_pago = document.getElementById("motivo_no_pago_v").value;
    if(document.getElementById("motivo_no_pago_v").value==0){
        document.getElementById("motivo_no_pago_v").style.backgroundColor="#f2dede";
    }else{
        document.getElementById("motivo_no_pago_v").style.backgroundColor="#fff";
    }
}

function obligatoriosDiscover() {
    //Validacion plazo financiamiento
    if(document.getElementById("plazo_financiamiento_dis").value==0){
        document.getElementById("plazo_financiamiento_dis").style.backgroundColor="#f2dede";
    }else{
        document.getElementById("plazo_financiamiento_dis").style.backgroundColor="#fff";
    }

    //Validacion motivo de no pago
    var motivo_no_pago = document.getElementById("motivo_no_pago_dis").value;
    if(document.getElementById("motivo_no_pago_dis").value==0){
        document.getElementById("motivo_no_pago_dis").style.backgroundColor="#f2dede";
    }else{
        document.getElementById("motivo_no_pago_dis").style.backgroundColor="#fff";
    }
}


function verIntereses(meses,tarjeta) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var parametros = {
        "mes" : meses
    };
    $.ajax({
        data:  parametros,
        url:   'getIntereses',
        type:  'post',
        beforeSend: function () {
            //$("#resultado").html("Procesando, espere por favor...");
        },
        success:  function (data) {
            var dhtml="";
            if(tarjeta=='d'){document.getElementById('factor_calculado_h').value = data[0].factor_calculado; interes_calculado_diners = data[0].factor_calculado; calcularDiners();}
            if(tarjeta=='v'){document.getElementById('factor_calculado_h_v').value = data[0].factor_calculado; interes_calculado_visa = data[0].factor_calculado; calcularVisa();}
            if(tarjeta=='dis'){document.getElementById('factor_calculado_h_dis').value = data[0].factor_calculado; interes_calculado_discover = data[0].factor_calculado; calcularDiscover();}

            //alert(interes_calculado_diners);
            //$("#factor_calculado_h").html(data[0].factor_calculado);
            //console.log("Factor Calculado: "+data[0].factor_calculado);
            //$("#total_intereses").html(data[0].factor_calculado);
        }
    });
}