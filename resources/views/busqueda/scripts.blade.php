<style type="text/css">
    .sel{
        background-color:#f2dede ;
        padding: 5px 8px;
        border: none;
        box-shadow: none;
        background-image: none;
        appearance: none;
    }
    select option {
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
    }
    .panel-title{
        color: #fff;
        font-weight: bold;
    }
</style>

<script>

    function consolidarDiners(){
        if(document.getElementById('consolidar_diners').checked==false){
            document.getElementById('submitformDiners').innerHTML='<input type="checkbox" name="consolidar_diners" id="consolidar_diners" class="" /> SELECCIONAR DINERS';
            document.getElementById('consolidar_diners').checked=true;
        }else{
            document.getElementById('submitformDiners').innerHTML='<input type="checkbox" name="consolidar_diners" id="consolidar_diners" class="" /> SELECCIONAR DINERS';
            document.getElementById('consolidar_diners').checked=false;
        }
        return false;
    }

    function consolidarVisa(){
        if(document.getElementById('consolidar_visa').checked==false){
            document.getElementById('submitformVisa').innerHTML='<input type="checkbox" name="consolidar_visa" id="consolidar_visa" class="" /> SELECCIONAR VISA';
            document.getElementById('consolidar_visa').checked=true;
        }else{
            document.getElementById('submitformVisa').innerHTML='<input type="checkbox" name="consolidar_visa" id="consolidar_visa" class="" /> SELECCIONAR VISA';
            document.getElementById('consolidar_visa').checked=false;
        }
        return false;
    }

    function consolidarDiscover(){
        if(document.getElementById('consolidar_discover').checked==false){
            document.getElementById('submitformDiscover').innerHTML='<input type="checkbox" name="consolidar_discover" id="consolidar_discover" class="" /> SELECCIONAR DISCOVER';
            document.getElementById('consolidar_discover').checked=true;
        }else{
            document.getElementById('submitformDiscover').innerHTML='<input type="checkbox" name="consolidar_discover" id="consolidar_discover" class="" /> SELECCIONAR DISCOVER';
            document.getElementById('consolidar_discover').checked=false;
        }
        return false;
    }
    //Validaciones campos obligatorios
    function estad_civ(){
        if ($(document.getElementById('estado_civil')).val() != "CASADO/A") {
            document.getElementById('cedula_conyugue').value = "";
            document.getElementById('cedula_conyugue').disabled = true;

            document.getElementById('nombres_conyugue').disabled = true;
            document.getElementById('nombres_conyugue').value = "";

            document.getElementById('excepcion_firma_conyugue').disabled = true;
            document.getElementById('excepcion_firma_conyugue').value = "";

        }else{
            document.getElementById('cedula_conyugue').disabled = false;
            document.getElementById('nombres_conyugue').disabled = false;
            document.getElementById('excepcion_firma_conyugue').disabled = false;
        }
    }
    //----------------------------------------------------------------------
    // cuando se activa el submit
    //----------------------------------------------------------------------

    $(function() {
        $('#submitform').click(function() {
            if(document.getElementById('consolidar_diners').checked==true || document.getElementById('consolidar_visa').checked==true || document.getElementById('consolidar_discover').checked==true){
                $("#formulario input,textarea,select[required]").each(function () {
                    // si el valor es empty empiezo a validar cada campo
                    if ($(this).val() == "") {
                        $(".tab-pane.fade.active.in").removeClass("active in");
                        $(this).closest(".tab-pane.fade").addClass("active in");
                        $(this).closest(".form-control").addClass("form-control").css("background", "#fdc9c9");

                        // oculto el acordion activo para activar el acordion en el que se encuentra el error
                        $(".panel-collapse.in").removeClass("in");
                        $(this).closest(".panel-collapse").addClass("in").css("height", "auto");

                        // toggle bar color
                        $(".panel-group").children().removeClass("panel-default");
                        $(".panel-group").children().addClass("panel-default");
                        $(this).closest(".panel-default").toggleClass('panel-default panel-default');

                        // toggle
                        $(".panel-heading").find("i.indicator").removeClass("glyphicon-chevron-down");
                        $(".panel-heading").find("i.indicator").addClass("glyphicon-chevron-right");
                        $(this).closest(".panel-collapse").parent().find("i.indicator").toggleClass('glyphicon-chevron-down glyphicon-chevron-right');

                        // stop scrolling through the required elements
                        return false;
                    }

                    //VALIDACIONES DATOS CONYUGUE
                    if ($(document.getElementById('estado_civil')).val() == "CASADO/A") {
                        if (document.getElementById('cedula_conyugue').value.length < 10) {
                            alert("Ingrese correctamente la cédula del conyugue");
                            $(".tab-pane.fade.active.in").removeClass("active in");
                            $(document.getElementById('cedula_conyugue')).closest(".tab-pane.fade").addClass("active in");
                            $(document.getElementById('cedula_conyugue')).closest(".form-control").addClass("form-control").css("background", "#ebccd1");
                            // hide the currently open accordion and open the one with the invalid field
                            $(".panel-collapse.in").removeClass("in");
                            $(document.getElementById('cedula_conyugue')).closest(".panel-collapse").addClass("in").css("height", "auto");
                            document.getElementById("cedula_conyugue").focus();
                            return false;
                        } else {
                            document.getElementById("cedula_conyugue").style.backgroundColor = "#fff"
                        }
                    }

                    if ($(document.getElementById('estado_civil')).val() == 0) {
                        alert("Seleccione un estado civil");
                        $(".tab-pane.fade.active.in").removeClass("active in");
                        $(document.getElementById('estado_civil')).closest(".tab-pane.fade").addClass("active in");
                        $(document.getElementById('estado_civil')).closest(".form-control").addClass("form-control").css("background", "#ebccd1");
                        // hide the currently open accordion and open the one with the invalid field
                        $(".panel-collapse.in").removeClass("in");
                        $(document.getElementById('estado_civil')).closest(".panel-collapse").addClass("in").css("height", "auto");
                        document.getElementById("estado_civil").focus();
                        return false;
                    } else {
                        document.getElementById("estado_civil").style.backgroundColor = "#fff";
                    }

                    if ($(document.getElementById('actividad_economica')).val() == 0) {
                        alert("Seleccione una actividad económica");
                        $(".tab-pane.fade.active.in").removeClass("active in");
                        $(document.getElementById('actividad_economica')).closest(".tab-pane.fade").addClass("active in");
                        $(document.getElementById('actividad_economica')).closest(".form-control").addClass("form-control").css("background", "#ebccd1");
                        // hide the currently open accordion and open the one with the invalid field
                        $(".panel-collapse.in").removeClass("in");
                        $(document.getElementById('actividad_economica')).closest(".panel-collapse").addClass("in").css("height", "auto");
                        document.getElementById("actividad_economica").focus();
                        return false;
                    } else {
                        document.getElementById("actividad_economica").style.backgroundColor = "#fff";
                    }

                });

            }else{alert('Debe seleccionar por lo menos una tarjeta'); return false;}
        });

        $('#submitformDiners').click(function() {
            //Validaciones abono negociador diners visa  discover intereses_facturados_h
            //Diners
            if (document.getElementById('diners').value == 'diners') {
                if (parseFloat($(document.getElementById('abono_total_i')).val()) < parseFloat($(document.getElementById('intereses_facturados_h')).val())) {
                    if(document.getElementById('desactivar_chk').checked==false) {
                        if (parseFloat($(document.getElementById('abono_negociador')).val()) < parseFloat($(document.getElementById('intereses_facturados_h')).val())) {
                            // oculto el acordion activo para activar el acordion en el que se encuentra el error
                            $(".panel-collapse.in").removeClass("in");
                            $(document.getElementById('abono_negociador')).closest(".panel-collapse").addClass("in").css("height", "auto");

                            // toggle bar color
                            $(".panel-group").children().removeClass("panel-default");
                            $(".panel-group").children().addClass("panel-default");
                            $(this).closest(".panel-default").toggleClass('panel-default panel-default');

                            $(document.getElementById('abono_negociador')).closest(".panel-collapse").addClass("in").css("height", "auto");
                            alert("Abono no cubre intereses tarjeta Diners");
                            document.getElementById("abono_negociador").focus();
                            document.getElementById('submitformDiners').innerHTML = '<input type="checkbox" name="consolidar_diners" id="consolidar_diners" class="" /> SELECCIONAR DINERS';
                            document.getElementById('consolidar_diners').checked = false;
                            return false;
                        }
                    }
                }

                if (parseInt($(document.getElementById('plazo_financiamiento')).val()) == 0) {
                    // oculto el acordion activo para activar el acordion en el que se encuentra el error
                    $(".panel-collapse.in").removeClass("in");
                    $(document.getElementById('plazo_financiamiento')).closest(".panel-collapse").addClass("in").css("height", "auto");
                    // toggle bar color
                    $(".panel-group").children().removeClass("panel-default");
                    $(".panel-group").children().addClass("panel-default");
                    $(this).closest(".panel-default").toggleClass('panel-default panel-default');

                    $(document.getElementById('plazo_financiamiento')).closest(".panel-collapse").addClass("in").css("height", "auto");
                    alert("Seleccione un plazo de financiamiento tarjeta Diners");
                    document.getElementById("plazo_financiamiento").focus();
                    document.getElementById('submitformDiners').innerHTML='<input type="checkbox" name="consolidar_diners" id="consolidar_diners" class="" /> SELECCIONAR DINERS';
                    document.getElementById('consolidar_diners').checked=false;
                    return false;
                }

                if (parseInt($(document.getElementById('motivo_no_pago')).val()) == 0) {
                    // oculto el acordion activo para activar el acordion en el que se encuentra el error
                    $(".panel-collapse.in").removeClass("in");
                    $(document.getElementById('motivo_no_pago')).closest(".panel-collapse").addClass("in").css("height", "auto");

                    // toggle bar color
                    $(".panel-group").children().removeClass("panel-default");
                    $(".panel-group").children().addClass("panel-default");
                    $(this).closest(".panel-default").toggleClass('panel-default panel-default');

                    $(document.getElementById('motivo_no_pago')).closest(".panel-collapse").addClass("in").css("height", "auto");
                    alert("Seleccione un de motivo de no pago tarjeta Diners");
                    document.getElementById("motivo_no_pago").focus();
                    document.getElementById('submitformDiners').innerHTML='<input type="checkbox" name="consolidar_diners" id="consolidar_diners" class="" /> SELECCIONAR DINERS';
                    document.getElementById('consolidar_diners').checked=false;
                    return false;
                }

                if ($(document.getElementById('abono_negociador')).val() == '') {
                    alert("El campo abono negociador no puede estar vacío");
                    $(".tab-pane.fade.active.in").removeClass("active in");
                    $(document.getElementById('abono_negociador')).closest(".tab-pane.fade").addClass("active in");

                    // hide the currently open accordion and open the one with the invalid field
                    $(".panel-collapse.in").removeClass("in");
                    $(document.getElementById('abono_negociador')).closest(".panel-collapse").addClass("in").css("height", "auto");
                    document.getElementById("abono_negociador").focus();
                    document.getElementById('submitformDiners').innerHTML='<input type="checkbox" name="consolidar_diners" id="consolidar_diners" class="" /> SELECCIONAR DINERS';
                    document.getElementById('consolidar_diners').checked=false;
                    return false;
                }

                if ($(document.getElementById('codigo_de_cancelacion_solicitado')).val() != undefined) {
                    if ($(document.getElementById('codigo_de_cancelacion_solicitado')).val() == 'SELECCIONE UNO') {
                        alert("Seleccione un código de cancelación actual Diners");
                        $(".tab-pane.fade.active.in").removeClass("active in");
                        $(document.getElementById('codigo_de_cancelacion_solicitado')).closest(".tab-pane.fade").addClass("active in");
                        $(document.getElementById('codigo_de_cancelacion_solicitado')).closest(".form-control").addClass("form-control").css("background", "#ebccd1");
                        // hide the currently open accordion and open the one with the invalid field
                        $(".panel-collapse.in").removeClass("in");
                        $(document.getElementById('codigo_de_cancelacion_solicitado')).closest(".panel-collapse").addClass("in").css("height", "auto");
                        document.getElementById("codigo_de_cancelacion_solicitado").focus();
                        document.getElementById('submitformDiners').innerHTML='<input type="checkbox" name="consolidar_diners" id="consolidar_diners" class="" /> SELECCIONAR DINERS';
                        document.getElementById('consolidar_diners').checked=false;
                        return false;
                    } else {
                        document.getElementById("codigo_de_cancelacion_solicitado").style.backgroundColor = "#fff";
                    }
                }
            }
        });

        $('#submitformVisa').click(function() {
            //Validaciones abono negociador diners visa  discover intereses_facturados_h
            //Visa
            if (document.getElementById('visa').value == 'visa') {
                if (parseFloat($(document.getElementById('abono_total_i_v')).val()) < parseFloat($(document.getElementById('intereses_facturados_h_v')).val())) {
                    if(document.getElementById('desactivar_chk_v').checked==false) {
                        if (parseFloat($(document.getElementById('abono_negociador_v')).val()) < parseFloat($(document.getElementById('intereses_facturados_h_v')).val())) {
                            // oculto el acordion activo para activar el acordion en el que se encuentra el error
                            $(".panel-collapse.in").removeClass("in");
                            $(document.getElementById('abono_negociador_v')).closest(".panel-collapse").addClass("in").css("height", "auto");

                            // toggle bar color
                            $(".panel-group").children().removeClass("panel-default");
                            $(".panel-group").children().addClass("panel-default");
                            $(this).closest(".panel-default").toggleClass('panel-default panel-default');

                            $(document.getElementById('abono_negociador_v')).closest(".panel-collapse").addClass("in").css("height", "auto");
                            alert("Abono no cubre intereses tarjeta Visa");
                            document.getElementById("abono_negociador_v").focus();
                            document.getElementById('submitformVisa').innerHTML = '<input type="checkbox" name="consolidar_visa" id="consolidar_visa" class="" /> SELECCIONAR VISA';
                            document.getElementById('consolidar_visa').checked = false;
                            return false;
                        }
                    }
                }

                if (parseInt($(document.getElementById('plazo_financiamiento_v')).val()) == 0) {
                    // oculto el acordion activo para activar el acordion en el que se encuentra el error
                    $(".panel-collapse.in").removeClass("in");
                    $(document.getElementById('plazo_financiamiento_v')).closest(".panel-collapse").addClass("in").css("height", "auto");

                    // toggle bar color
                    $(".panel-group").children().removeClass("panel-default");
                    $(".panel-group").children().addClass("panel-default");
                    $(this).closest(".panel-default").toggleClass('panel-default panel-default');

                    $(document.getElementById('plazo_financiamiento_v')).closest(".panel-collapse").addClass("in").css("height", "auto");
                    alert("Seleccione un plazo de financiamiento tarjeta Visa");
                    document.getElementById("plazo_financiamiento_v").focus();
                    document.getElementById('submitformVisa').innerHTML='<input type="checkbox" name="consolidar_visa" id="consolidar_visa" class="" /> SELECCIONAR VISA';
                    document.getElementById('consolidar_visa').checked=false;
                    return false;
                }

                if (parseInt($(document.getElementById('motivo_no_pago_v')).val()) == 0) {
                    // oculto el acordion activo para activar el acordion en el que se encuentra el error
                    $(".panel-collapse.in").removeClass("in");
                    $(document.getElementById('motivo_no_pago_v')).closest(".panel-collapse").addClass("in").css("height", "auto");

                    // toggle bar color
                    $(".panel-group").children().removeClass("panel-default");
                    $(".panel-group").children().addClass("panel-default");
                    $(this).closest(".panel-default").toggleClass('panel-default panel-default');

                    $(document.getElementById('motivo_no_pago_v')).closest(".panel-collapse").addClass("in").css("height", "auto");
                    alert("Seleccione un de motivo de no pago tarjeta Visa");
                    document.getElementById("motivo_no_pago_v").focus();
                    document.getElementById('submitformVisa').innerHTML='<input type="checkbox" name="consolidar_visa" id="consolidar_visa" class="" /> SELECCIONAR VISA';
                    document.getElementById('consolidar_visa').checked=false;
                    return false;
                }

                if ($(document.getElementById('abono_negociador_v')).val() == '') {
                    alert("El campo abono negociador no puede estar vacío");
                    $(".tab-pane.fade.active.in").removeClass("active in");
                    $(document.getElementById('abono_negociador_v')).closest(".tab-pane.fade").addClass("active in");

                    // hide the currently open accordion and open the one with the invalid field
                    $(".panel-collapse.in").removeClass("in");
                    $(document.getElementById('abono_negociador_v')).closest(".panel-collapse").addClass("in").css("height", "auto");
                    document.getElementById("abono_negociador_v").focus();
                    document.getElementById('submitformVisa').innerHTML='<input type="checkbox" name="consolidar_visa" id="consolidar_visa" class="" /> SELECCIONAR VISA';
                    document.getElementById('consolidar_visa').checked=false;
                    return false;
                }

                if ($(document.getElementById('codigo_de_cancelacion_solicitado_v')).val() != undefined) {
                    if ($(document.getElementById('codigo_de_cancelacion_solicitado_v')).val() == 'SELECCIONE UNO') {
                        alert("Seleccione un código de cancelación actual Visa");
                        $(".tab-pane.fade.active.in").removeClass("active in");
                        $(document.getElementById('codigo_de_cancelacion_solicitado_v')).closest(".tab-pane.fade").addClass("active in");
                        $(document.getElementById('codigo_de_cancelacion_solicitado_v')).closest(".form-control").addClass("form-control").css("background", "#ebccd1");
                        // hide the currently open accordion and open the one with the invalid field
                        $(".panel-collapse.in").removeClass("in");
                        $(document.getElementById('codigo_de_cancelacion_solicitado_v')).closest(".panel-collapse").addClass("in").css("height", "auto");
                        document.getElementById("codigo_de_cancelacion_solicitado_v").focus();
                        document.getElementById('submitformVisa').innerHTML='<input type="checkbox" name="consolidar_visa" id="consolidar_visa" class="" /> SELECCIONAR VISA';
                        document.getElementById('consolidar_visa').checked=false;
                        return false;
                    } else {
                        document.getElementById("codigo_de_cancelacion_solicitado_v").style.backgroundColor = "#fff";

                    }
                }

                if (document.getElementById("tipo_cuenta_v").disabled == false) {
                    if ($(document.getElementById('tipo_cuenta_v')).val() == 'SELECCIONE UNO') {
                        alert("Seleccione un tipo de cuenta");
                        document.getElementById("tipo_cuenta_v").focus();
                        return false;
                    } else {
                        document.getElementById("tipo_cuenta_v").style.backgroundColor = "#fff";
                    }
                }
            }
        });

        $('#submitformDiscover').click(function() {
        //Validaciones abono negociador diners visa  discover intereses_facturados_h
        //Discover
        if(document.getElementById('discover').value=='discover') {
            if (parseFloat($(document.getElementById('abono_total_i_dis')).val()) < parseFloat($(document.getElementById('intereses_facturados_h_dis')).val())) {
                if(document.getElementById('desactivar_chk_dis').checked==false) {
                    if (parseFloat($(document.getElementById('abono_negociador_dis')).val()) < parseFloat($(document.getElementById('intereses_facturados_h_dis')).val())) {
                        // oculto el acordion activo para activar el acordion en el que se encuentra el error
                        $(".panel-collapse.in").removeClass("in");
                        $(document.getElementById('abono_negociador_dis')).closest(".panel-collapse").addClass("in").css("height", "auto");

                        // toggle bar color
                        $(".panel-group").children().removeClass("panel-default");
                        $(".panel-group").children().addClass("panel-default");
                        $(this).closest(".panel-default").toggleClass('panel-default panel-default');

                        $(document.getElementById('abono_negociador_dis')).closest(".panel-collapse").addClass("in").css("height", "auto");
                        alert("Abono no cubre intereses tarjeta Discover");
                        document.getElementById("abono_negociador_dis").focus();
                        document.getElementById('submitformDiscover').innerHTML = '<input type="checkbox" name="consolidar_discover" id="consolidar_discover" class="" /> SELECCIONAR DISCOVER';
                        document.getElementById('consolidar_discover').checked = false;
                        return false;
                    }
                }
            }

            if (parseInt($(document.getElementById('plazo_financiamiento_dis')).val()) == 0) {
                // oculto el acordion activo para activar el acordion en el que se encuentra el error
                $(".panel-collapse.in").removeClass("in");
                $(document.getElementById('plazo_financiamiento_dis')).closest(".panel-collapse").addClass("in").css("height", "auto");

                // toggle bar color
                $(".panel-group").children().removeClass("panel-default");
                $(".panel-group").children().addClass("panel-default");
                $(this).closest(".panel-default").toggleClass('panel-default panel-default');

                $(document.getElementById('plazo_financiamiento_dis')).closest(".panel-collapse").addClass("in").css("height", "auto");
                alert("Seleccione un plazo de financiamiento tarjeta Discover");
                document.getElementById("plazo_financiamiento_dis").focus();
                document.getElementById('submitformDiscover').innerHTML = '<input type="checkbox" name="consolidar_discover" id="consolidar_discover" class="" /> SELECCIONAR DISCOVER';
                document.getElementById('consolidar_discover').checked = false;
                return false;
            }

            if (parseInt($(document.getElementById('motivo_no_pago_dis')).val()) == 0) {
                // oculto el acordion activo para activar el acordion en el que se encuentra el error
                $(".panel-collapse.in").removeClass("in");
                $(document.getElementById('motivo_no_pago_dis')).closest(".panel-collapse").addClass("in").css("height", "auto");

                // toggle bar color
                $(".panel-group").children().removeClass("panel-default");
                $(".panel-group").children().addClass("panel-default");
                $(this).closest(".panel-default").toggleClass('panel-default panel-default');

                $(document.getElementById('motivo_no_pago_dis')).closest(".panel-collapse").addClass("in").css("height", "auto");
                alert("Seleccione un de motivo de no pago tarjeta Discover");
                document.getElementById("motivo_no_pago_dis").focus();
                document.getElementById('submitformDiscover').innerHTML = '<input type="checkbox" name="consolidar_discover" id="consolidar_discover" class="" /> SELECCIONAR DISCOVER';
                document.getElementById('consolidar_discover').checked = false;
                return false;
            }

            if ($(document.getElementById('abono_negociador_dis')).val() == '') {
                alert("El campo abono negociador no puede estar vacío");
                $(".tab-pane.fade.active.in").removeClass("active in");
                $(document.getElementById('abono_negociador_dis')).closest(".tab-pane.fade").addClass("active in");

                // hide the currently open accordion and open the one with the invalid field
                $(".panel-collapse.in").removeClass("in");
                $(document.getElementById('abono_negociador_dis')).closest(".panel-collapse").addClass("in").css("height", "auto");
                document.getElementById("abono_negociador_dis").focus();
                document.getElementById('submitformDiscover').innerHTML = '<input type="checkbox" name="consolidar_discover" id="consolidar_discover" class="" /> SELECCIONAR DISCOVER';
                document.getElementById('consolidar_discover').checked = false;
                return false;
            }

            if ($(document.getElementById('codigo_de_cancelacion_solicitado_dis')).val() != undefined) {
                if ($(document.getElementById('codigo_de_cancelacion_solicitado_dis')).val() == 'SELECCIONE UNO') {
                    alert("Seleccione un código de cancelación actual Discover");
                    $(".tab-pane.fade.active.in").removeClass("active in");
                    $(document.getElementById('codigo_de_cancelacion_solicitado_dis')).closest(".tab-pane.fade").addClass("active in");
                    $(document.getElementById('codigo_de_cancelacion_solicitado_dis')).closest(".form-control").addClass("form-control").css("background", "#ebccd1");
                    // hide the currently open accordion and open the one with the invalid field
                    $(".panel-collapse.in").removeClass("in");
                    $(document.getElementById('codigo_de_cancelacion_solicitado_dis')).closest(".panel-collapse").addClass("in").css("height", "auto");
                    document.getElementById("codigo_de_cancelacion_solicitado_dis").focus();
                    document.getElementById('submitformDiscover').innerHTML = '<input type="checkbox" name="consolidar_discover" id="consolidar_discover" class="" /> SELECCIONAR DISCOVER';
                    document.getElementById('consolidar_discover').checked = false;
                    return false;
                } else {
                    document.getElementById("codigo_de_cancelacion_solicitado_dis").style.backgroundColor = "#fff";

                }
            }

            if (document.getElementById("tipo_cuenta_dis").disabled == false) {
                if ($(document.getElementById('tipo_cuenta_dis')).val() == 'SELECCIONE UNO') {
                    alert("Seleccione un tipo de cuenta");
                    document.getElementById("tipo_cuenta_dis").focus();
                    return false;
                } else {
                    document.getElementById("tipo_cuenta_dis").style.backgroundColor = "#fff";
                }
            }
        }


        });

    });
</script>
