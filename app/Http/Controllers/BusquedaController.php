<?php
namespace App\Http\Controllers;

use App\tbl_id_carga;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\tbl_carga_mdl as carga;
use App\tbl_motivo_no_pago_mdl as motivo;
use App\tbl_tasas_interes as tasas_intereses;
use App\tbl_estado_civil as estado_civil;
use App\tbl_oficial_r as oficial_r;
use App\tbl_actividad_economica as actividad_economica;
use App\tbl_gestiones as gestiones;
use App\tbl_estado_gestion as estado_gestion;
use App\tbl_negociacion_especial as negociacion_especial;
use App\tbl_formatos as formatos;
use App\tbl_codigo_cancelacion as codigo_cancelacion;
use App\tbl_codigo_cancelacion_solicitado as codigo_cancelacion_solicitado;
use App\tbl_archivos as archivos;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Cast\Object_;
use PhpParser\Node\Expr\Include_;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Stmt\DeclareDeclare;
use Carbon\Carbon;
class BusquedaController extends Controller
{

    protected $fecha_act;
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = \Auth::user();
        $this->fecha_act=Carbon::now(-5);
    }

    public function actionPasarCedula()
    {
        $cliente = carga::where('cedula', request()->c_i)
                    ->where('estado', 1)
                    ->orderBy('id_carga', 'DESC')
                    ->first();

        $visa = 0;
        $diners = 0;
        $discover = 0;
        //$cliente = DB::table('tbl_carga')->where('CEDULA', request()->c_i)->get();
        //$cliente = DB::connection()->getPdo();
        //$cliente = DB::connection('mysql')->select('select * from tbl_carga where CEDULA = ?', request()->c_i);

        //dd($cliente);
        if ($cliente == null || $cliente == "") {
            $cliente = new carga();
            $cliente->CEDULA = 'SC';
        }
        else {

            $id_carga=tbl_id_carga::where('id_carga',$cliente->ID_CARGA)->first();
            //DISCOVER
            if ($cliente->EDCART_DISCOVER == '#¡NULO!') {
                $cliente->EDCART_DISCOVER = '';
            }
            if ($cliente->EDCART_DISCOVER == 1) {
                $edad_cartera_dis = "ACTUALES";
            } else {
                $edad_cartera_dis = $cliente->EDCART_DISCOVER;
            }
            if ($cliente->CODRET_DISCOVER == 0) {
                //$codigo_cancelacion_dis = "ACTIVA";
                $codigo_cancelacion_dis = $cliente->CODRET_DISCOVER;
            } else {
                $codigo_cancelacion_dis = $cliente->CODRET_DISCOVER;
            }
            if ($cliente->BOLNAC_DISCOVER == 0) {
                $cod_boletin_dis = "ACTIVA";
            } else {
                $cod_boletin_dis = $cliente->BOLNAC_DISCOVER;
            }

            //VISA
            if ($cliente->EDCART_VISA == '#¡NULO!') {
                $cliente->EDCART_VISA = '';
            }
            if ($cliente->EDCART_VISA == 1) {
                $edad_cartera_v = "ACTUALES";
            } else {
                $edad_cartera_v = $cliente->EDCART_VISA;
            }
            if ($cliente->CODRET_VISA == 0) {
                //$codigo_cancelacion_v = "ACTIVA";
                $codigo_cancelacion_v = $cliente->CODRET_VISA;
            } else {
                $codigo_cancelacion_v = $cliente->CODRET_VISA;
            }
            if ($cliente->BOLNAC_VISA == 0) {
                $cod_boletin_v = "ACTIVA";
            } else {
                $cod_boletin_v = $cliente->BOLNAC_VISA;
            }

            //DINERS
            if ($cliente->EDCART == 1) {
                $edad_cartera = "ACTUALES";
            } else {
                $edad_cartera = $cliente->EDCART;
            }
            if ($cliente->CODRET == 0) {
                //$codigo_cancelacion = "ACTIVA";
                $codigo_cancelacion = $cliente->CODRET;
            } else {
                $codigo_cancelacion = $cliente->CODRET;
            }
            if ($cliente->BOLNAC == 0) {
                $cod_boletin = "ACTIVA";
            } else {
                $cod_boletin = $cliente->BOLNAC;
            }

            //
            if ($cliente->CEDULA == '#¡NULO!') {
                $cliente->CEDULA = '';
            }
            if ($cliente->NOMSOC == '#¡NULO!') {
                $cliente->NOMSOC = '';
            }
            if ($cliente->EMPRESA_SOC == '#¡NULO!') {
                $cliente->EMPRESA_SOC = '';
            }
            if ($cliente->DIRECCION == '#¡NULO!') {
                $cliente->DIRECCION = '';
            }
            if ($cliente->P1 == '#¡NULO!') {
                $cliente->P1 = '';
            }
            if ($cliente->T1 == '#¡NULO!') {
                $cliente->T1 = 0;
            }
            if ($cliente->P2 == '#¡NULO!') {
                $cliente->P2 = '';
            }
            if ($cliente->T2 == '#¡NULO!') {
                $cliente->T2 = 0;
            }
            if ($cliente->P3 == '#¡NULO!') {
                $cliente->P3 = '';
            }
            if ($cliente->T3 == '#¡NULO!') {
                $cliente->T3 = 0;
            }
            if ($cliente->NOMBRE_CIUDAD == '#¡NULO!') {
                $cliente->NOMBRE_CIUDAD = '';
            }
            if ($cliente->ZONA == '#¡NULO!') {
                $cliente->ZONA = '';
            }
            if ($cliente->EMAIL == '#¡NULO!') {
                $cliente->EMAIL = '';
            }
            if ($cliente->STOTOT == '#¡NULO!') {
                $cliente->STOTOT = 0;
                $diners++;
            }
            if ($cliente->VAXFAC == '#¡NULO!') {
                $cliente->VAXFAC = 0;
                $diners++;
            }else{
                $cliente->VAXFAC=(float)str_replace (',','.',$cliente->VAXFAC );
            }
            if ($cliente->TRIESGO == '#¡NULO!') {
                $cliente->TRIESGO = 0;
                $diners++;
            }
            if ($cliente->CICLOF == '#¡NULO!') {
                $cliente->CICLOF = 0;
                $diners++;
            }
            else{
                $cliente->CICLOF=(int)$cliente->CICLOF;
            }
            if ($cliente->EDCART == '#¡NULO!') {
                $cliente->EDCART = 0;
                $diners++;
            }
            if ($cliente->ACTUALES_ORIG == '#¡NULO!') {
                $cliente->ACTUALES_ORIG = 0;
                $diners++;
            }else{
                $cliente->ACTUALES_ORIG=(float)str_replace (',','.',$cliente->ACTUALES_ORIG );
            }
            if ($cliente->D30_ORIG == '#¡NULO!') {
                $cliente->D30_ORIG = 0;
                $diners++;
            }else{
                $cliente->D30_ORIG=(float)str_replace (',','.',$cliente->D30_ORIG );
            }
            if ($cliente->D60_ORIG == '#¡NULO!') {
                $cliente->D60_ORIG = 0;
                $diners++;
            }else{
                $cliente->D60_ORIG=(float)str_replace (',','.',$cliente->D60_ORIG );
            }
            if ($cliente->D90_ORIG == '#¡NULO!') {
                $cliente->D90_ORIG = 0;
                $diners++;
            }else{
                $cliente->D90_ORIG=(float)str_replace (',','.',$cliente->D90_ORIG );
            }
            if ($cliente->DMAS90_ORIG == '#¡NULO!') {
                $cliente->DMAS90_ORIG = 0;
                $diners++;
            }else{
                $cliente->DMAS90_ORIG=(float)str_replace (',','.',$cliente->DMAS90_ORIG );
            }
            if ($cliente->FECHACOMPROMISO == '#¡NULO!') {
                $cliente->FECHACOMPROMISO = '';
                $diners++;
            }
            if ($cliente->FECHALLAM == '#¡NULO!') {
                $cliente->FECHALLAM = '';
                $diners++;
            }
            if ($cliente->DESCRIPCION == '#¡NULO!') {
                $cliente->DESCRIPCION = '';
                $diners++;
            }
            if ($cliente->OBSERVACION == '#¡NULO!') {
                $cliente->OBSERVACION = '';
                $diners++;
            }
            if ($cliente->MOTIVO_1 == '#¡NULO!') {
                $cliente->MOTIVO_1 = '';
                $diners++;
            }
            if ($cliente->INTERES_TOTAL == '#¡NULO!') {
                $cliente->INTERES_TOTAL = 0;
                $diners++;
            }else{
                $cliente->INTERES_TOTAL=(float)str_replace (',','.',$cliente->INTERES_TOTAL );
            }
            if ($cliente->PROM_PAG == '#¡NULO!') {
                $cliente->PROM_PAG = 0;
                $diners++;
            }
            if ($cliente->DEBITO_AUT == '#¡NULO!') {
                $cliente->DEBITO_AUT = '';
                $diners++;
            }
            if ($cliente->NOMESTAB == '#¡NULO!') {
                $cliente->NOMESTAB = '';
                $diners++;
            }
            if ($cliente->SIMULACION_DIF_sum == '#¡NULO!') {
                $cliente->SIMULACION_DIF_sum = 0;
                $diners++;
            }else{
                $cliente->SIMULACION_DIF_sum=(float)str_replace (',','.',$cliente->SIMULACION_DIF_sum );
            }
            if ($cliente->N_DIF == '#¡NULO!') {
                $cliente->N_DIF = 0;
                $diners++;
            }
            if ($cliente->DEBITO == '#¡NULO!') {
                $cliente->DEBITO = 0;
                $diners++;
            }
            if ($cliente->CREDITO == '#¡NULO!') {
                $cliente->CREDITO = 0;
                $diners++;
            }
            if ($cliente->PAGO == '#¡NULO!') {
                $cliente->PAGO = 0;
                $diners++;
            }
            if ($cliente->CODIGO == '#¡NULO!') {
                $cliente->CODIGO = '';
                $diners++;
            }
            if ($cliente->CUOTAS_PTES == '#¡NULO!') {
                $cliente->CUOTAS_PTES = '';
                $diners++;
            }
            if ($cliente->CUOTA_REF_VIG_PENDIENTE == '#¡NULO!') {
                $cliente->CUOTA_REF_VIG_PENDIENTE = '';
                $diners++;
            }
            if ($cliente->VALOR_PEN_REF_VIG == '#¡NULO!' || $cliente->VALOR_PEN_REF_VIG == '') {
                $cliente->VALOR_PEN_REF_VIG = 0;
                $diners++;
            }
            if ($cliente->IXF == '#¡NULO!') {
                $cliente->IXF = 0;
                $diners++;
            }else{
                $cliente->IXF=(float)str_replace (',','.',$cliente->IXF );
            }
            if ($cliente->SCORE_DINERS == '#¡NULO!') {
                $cliente->SCORE_DINERS = '';
                $diners++;
            }
            if ($cliente->PAGO_REAL == '#¡NULO!') {
                $cliente->PAGO_REAL = 0;
                $diners++;
            }else{
                $cliente->PAGO_REAL=(float)str_replace (',','.',$cliente->PAGO_REAL );
            }
            if ($cliente->REESTRUCT_VIGENTE == '#¡NULO!' || $cliente->REESTRUCT_VIGENTE == '') {
                $cliente->REESTRUCT_VIGENTE = '';
                $cliente->REESTRUCT_VIGENTE2 = '';
                $diners++;
            }elseif($cliente->REESTRUCT_VIGENTE=='REESTRUCTURACION'){$cliente->REESTRUCT_VIGENTE2 = 'SEGUNDA REESTRUCTURACION';}
            if ($cliente->DES_ESPECIALIDAD == '#¡NULO!') {
                $cliente->DES_ESPECIALIDAD = '';
                $diners++;
            }
            if ($cliente->CODRET == '#¡NULO!') {
                $cliente->CODRET = '';
                $diners++;
            }
            if ($cliente->BOLNAC == '#¡NULO!') {
                $cliente->BOLNAC = '';
                $diners++;
            }
            if ($cliente->EMPRESA == '#¡NULO!') {
                $cliente->EMPRESA = '';
                $diners++;
            }
            if ($cliente->CAMPAŃA_CON_ECE == '#¡NULO!') {
                $cliente->CAMPAŃA_CON_ECE = '';
                $diners++;
            }

            //VISA
            if ($cliente->STOTOT_VISA == '#¡NULO!') {
                $cliente->STOTOT_VISA = '';
                $visa++;
            }
            if ($cliente->VAXFAC_VISA == '#¡NULO!') {
                $cliente->VAXFAC_VISA = '';
                $visa++;
            }else{
                $cliente->VAXFAC_VISA=(float)str_replace (',','.',$cliente->VAXFAC_VISA );
            }
            if ($cliente->TRIESGO_VISA == '#¡NULO!') {
                $cliente->TRIESGO_VISA = '';
                $visa++;
            }
            if ($cliente->CICLOF_VISA == '#¡NULO!') {
                $cliente->CICLOF_VISA = '';
                $visa++;
            }else{
                $cliente->CICLOF_VISA=(int)$cliente->CICLOF_VISA;
            }

            if ($cliente->ACTUALES_ORIG_VISA == '#¡NULO!') {
                $cliente->ACTUALES_ORIG_VISA = 0;
                $visa++;
            }else{
                $cliente->ACTUALES_ORIG_VISA=(float)str_replace (',','.',$cliente->ACTUALES_ORIG_VISA );
            }
            if ($cliente->D30_ORIG_VISA == '#¡NULO!') {
                $cliente->D30_ORIG_VISA = 0;
                $visa++;
            }else{
                $cliente->D30_ORIG_VISA=(float)str_replace (',','.',$cliente->D30_ORIG_VISA );
            }
            if ($cliente->D60_ORIG_VISA == '#¡NULO!') {
                $cliente->D60_ORIG_VISA = 0;
                $visa++;
            }else{
                $cliente->D60_ORIG_VISA=(float)str_replace (',','.',$cliente->D60_ORIG_VISA );
            }
            if ($cliente->D90_ORIG_VISA == '#¡NULO!') {
                $cliente->D90_ORIG_VISA = 0;
                $visa++;
            }else{
                $cliente->D90_ORIG_VISA=(float)str_replace (',','.',$cliente->D90_ORIG_VISA );
            }
            if ($cliente->DMAS90_ORIG_VISA == '#¡NULO!') {
                $cliente->DMAS90_ORIG_VISA = 0;
                $visa++;
            }else{
                $cliente->DMAS90_ORIG_VISA=(float)str_replace (',','.',$cliente->DMAS90_ORIG_VISA );
            }
            if ($cliente->VAPAMI_VISA == '#¡NULO!') {
                $cliente->VAPAMI_VISA = '';
                $visa++;
            }
            if ($cliente->FECHACOMPROMISO_VISA == '#¡NULO!') {
                $cliente->FECHACOMPROMISO_VISA = '';
                $visa++;
            }
            if ($cliente->FECHALLAM_VISA == '#¡NULO!') {
                $cliente->FECHALLAM_VISA = '';
                $visa++;
            }
            if ($cliente->DESCRIPCION_VISA == '#¡NULO!') {
                $cliente->DESCRIPCION_VISA = '';
                $visa++;
            }
            if ($cliente->OBSERVACION_VISA == '#¡NULO!') {
                $cliente->OBSERVACION_VISA = '';
                $visa++;
            }
            if ($cliente->MOTIVO_1_VISA == '#¡NULO!') {
                $cliente->MOTIVO_1_VISA = '';
                $visa++;
            }
            if ($cliente->INTERES_TOTAL_VISA == '#¡NULO!') {
                $cliente->INTERES_TOTAL_VISA = 0;
                $visa++;
            }else{
                $cliente->INTERES_TOTAL_VISA=(float)str_replace (',','.',$cliente->INTERES_TOTAL_VISA );
            }
            if ($cliente->PROM_PAG_VISA == '#¡NULO!') {
                $cliente->PROM_PAG_VISA = 0;
                $visa++;
            }
            if ($cliente->DEBITO_AUT_VISA == '#¡NULO!') {
                $cliente->DEBITO_AUT_VISA = '';
                $visa++;
            }
            if ($cliente->NOMESTAB_VISA == '#¡NULO!') {
                $cliente->NOMESTAB_VISA = '';
                $visa++;
            }
            if ($cliente->SIMULACION_DIF_sum_VISA == '#¡NULO!') {
                $cliente->SIMULACION_DIF_sum_VISA = '';
                $visa++;
            }else{
                $cliente->SIMULACION_DIF_sum_VISA=(float)str_replace (',','.',$cliente->SIMULACION_DIF_sum_VISA );
            }
            if ($cliente->N_DIF_VISA == '#¡NULO!') {
                $cliente->N_DIF_VISA = '';
                $visa++;
            }
            if ($cliente->DEBITO_VISA == '#¡NULO!') {
                $cliente->DEBITO_VISA = '';
                $visa++;
            }

            if ($cliente->CREDITO_VISA == '#¡NULO!') {
                $cliente->CREDITO_VISA = '';
                $visa++;
            }
            if ($cliente->PAGO_VISA == '#¡NULO!') {
                $cliente->PAGO_VISA = '';
                $visa++;
            }
            if ($cliente->CODIGO_VISA == '#¡NULO!') {
                $cliente->CODIGO_VISA = '';
                $visa++;
            }
            if ($cliente->CUOTAS_PTES_VISA == '#¡NULO!') {
                $cliente->CUOTAS_PTES_VISA = '';
                $visa++;
            }
            if ($cliente->CUOTA_REF_VIG_PENDIENTE_VISA == '#¡NULO!') {
                $cliente->CUOTA_REF_VIG_PENDIENTE_VISA = '';
                $visa++;
            }
            if ($cliente->VALOR_PEN_REF_VIG_VISA == '#¡NULO!') {
                $cliente->VALOR_PEN_REF_VIG_VISA = '';
                $visa++;
            }
            if ($cliente->IXF_VISA == '#¡NULO!') {
                $cliente->IXF_VISA = '';
                $visa++;
            }else{
                $cliente->IXF_VISA=(float)str_replace (',','.',$cliente->IXF_VISA );
            }
            if ($cliente->SCORE_DINERS_VISA == '#¡NULO!') {
                $cliente->SCORE_DINERS_VISA = '';
                $visa++;
            }
            if ($cliente->PAGO_REAL_VISA == '#¡NULO!') {
                $cliente->PAGO_REAL_VISA = 0;
                $visa++;
            }else{
                $cliente->PAGO_REAL_VISA=(float)str_replace (',','.',$cliente->PAGO_REAL_VISA );
            }
            if ($cliente->REESTRUCT_VIGENTE_VISA == '#¡NULO!' || $cliente->REESTRUCT_VIGENTE_VISA == '') {
                $cliente->REESTRUCT_VIGENTE_VISA = '';
                $cliente->REESTRUCT_VIGENTE_VISA2 = '';
                $visa++;
            }elseif($cliente->REESTRUCT_VIGENTE_VISA=='REESTRUCTURACION'){$cliente->REESTRUCT_VIGENTE_VISA2 = 'SEGUNDA REESTRUCTURACION';}

            if ($cliente->DES_ESPECIALIDAD_VISA == '#¡NULO!') {
                $cliente->DES_ESPECIALIDAD_VISA = '';
                $visa++;
            }
            if ($cliente->CODRET_VISA == '#¡NULO!') {
                $cliente->CODRET_VISA = '';
                $visa++;
            }
            if ($cliente->BOLNAC_VISA == '#¡NULO!') {
                $cliente->BOLNAC_VISA = '';
                $visa++;
            }
            if ($cliente->EMPRESA_VISA == '#¡NULO!') {
                $cliente->EMPRESA_VISA = '';
                $visa++;
            }
            if ($cliente->CAMPAŃA_CON_ECE_VISA == '#¡NULO!') {
                $cliente->CAMPAŃA_CON_ECE_VISA = '';
                $visa++;
            }
            if ($cliente->STOTOT_DISCOVER == '#¡NULO!') {
                $cliente->STOTOT_DISCOVER = '';
                $discover++;
            }
            if ($cliente->VAXFAC_DISCOVER == '#¡NULO!') {
                $cliente->VAXFAC_DISCOVER = '';
                $discover++;
            }else{
                $cliente->VAXFAC_DISCOVER=(float)str_replace (',','.',$cliente->VAXFAC_DISCOVER );
            }
            if ($cliente->TRIESGO_DISCOVER == '#¡NULO!') {
                $cliente->TRIESGO_DISCOVER = '';
                $discover++;
            }
            if ($cliente->CICLOF_DISCOVER == '#¡NULO!') {
                $cliente->CICLOF_DISCOVER = '';
                $discover++;
            }else{
                $cliente->CICLOF_DISCOVER=(int)$cliente->CICLOF_DISCOVER;
            }

            if ($cliente->ACTUALES_ORIG_DISCOVER == '#¡NULO!') {
                $cliente->ACTUALES_ORIG_DISCOVER = 0;
                $discover++;
            }else{
                $cliente->ACTUALES_ORIG_DISCOVER=(float)str_replace (',','.',$cliente->ACTUALES_ORIG_DISCOVER );
            }
            if ($cliente->D30_ORIG_DISCOVER == '#¡NULO!') {
                $cliente->D30_ORIG_DISCOVER = 0;
                $discover++;
            }else{
                $cliente->D30_ORIG_DISCOVER=(float)str_replace (',','.',$cliente->D30_ORIG_DISCOVER );
            }


            if ($cliente->D60_ORIG_DISCOVER == '#¡NULO!') {
                $cliente->D60_ORIG_DISCOVER = 0;
                $discover++;
            }else{
                $cliente->D60_ORIG_DISCOVER=(float)str_replace (',','.',$cliente->D60_ORIG_DISCOVER );
            }
            if ($cliente->D90_ORIG_DISCOVER == '#¡NULO!') {
                $cliente->D90_ORIG_DISCOVER = 0;
                $discover++;
            }else{
                $cliente->D90_ORIG_DISCOVER=(float)str_replace (',','.',$cliente->D90_ORIG_DISCOVER );
            }
            if ($cliente->DMAS90_ORIG_DISCOVER == '#¡NULO!') {
                $cliente->DMAS90_ORIG_DISCOVER = 0;
                $discover++;
            }else{
                $cliente->DMAS90_ORIG_DISCOVER=(float)str_replace (',','.',$cliente->DMAS90_ORIG_DISCOVER );
            }
            if ($cliente->VAPAMI_DISCOVER == '#¡NULO!') {
                $cliente->VAPAMI_DISCOVER = '';
                $discover++;
            }
            if ($cliente->FECHACOMPROMISO_DISCOVER == '#¡NULO!') {
                $cliente->FECHACOMPROMISO_DISCOVER = '';
                $discover++;
            }
            if ($cliente->FECHALLAM_DISCOVER == '#¡NULO!') {
                $cliente->FECHALLAM_DISCOVER = '';
                $discover++;
            }
            if ($cliente->DESCRIPCION_DISCOVER == '#¡NULO!') {
                $cliente->DESCRIPCION_DISCOVER = '';
                $discover++;
            }
            if ($cliente->OBSERVACION_DISCOVER == '#¡NULO!') {
                $cliente->OBSERVACION_DISCOVER = '';
                $discover++;
            }
            if ($cliente->MOTIVO_1_DISCOVER == '#¡NULO!') {
                $cliente->MOTIVO_1_DISCOVER = '';
                $discover++;
            }
            if ($cliente->INTERES_TOTAL_DISCOVER == '#¡NULO!') {
                $cliente->INTERES_TOTAL_DISCOVER = 0;
                $discover++;
            }else{
                $cliente->INTERES_TOTAL_DISCOVER=(float)str_replace (',','.',$cliente->INTERES_TOTAL_DISCOVER );
            }
            if ($cliente->PROM_PAG_DISCOVER == '#¡NULO!') {
                $cliente->PROM_PAG_DISCOVER = 0;
                $discover++;
            }
            if ($cliente->DEBITO_AUT_DISCOVER == '#¡NULO!') {
                $cliente->DEBITO_AUT_DISCOVER = '';
                $discover++;
            }
            if ($cliente->NOMESTAB_DISCOVER == '#¡NULO!') {
                $cliente->NOMESTAB_DISCOVER = '';
                $discover++;
            }
            if ($cliente->SIMULACION_DIF_sum_DISCOVER == '#¡NULO!') {
                $cliente->SIMULACION_DIF_sum_DISCOVER = '';
                $discover++;
            }else{
                $cliente->SIMULACION_DIF_sum_DISCOVER=(float)str_replace (',','.',$cliente->SIMULACION_DIF_sum_DISCOVER );
            }
            if ($cliente->N_DIF_DISCOVER == '#¡NULO!') {
                $cliente->N_DIF_DISCOVER = '';
                $discover++;
            }
            if ($cliente->DEBITO_DISCOVER == '#¡NULO!') {
                $cliente->DEBITO_DISCOVER = '';
                $discover++;
            }
            if ($cliente->CREDITO_DISCOVER == '#¡NULO!') {
                $cliente->CREDITO_DISCOVER = '';
                $discover++;
            }
            if ($cliente->PAGO_DISCOVER == '#¡NULO!') {
                $cliente->PAGO_DISCOVER = '';
                $discover++;
            }
            if ($cliente->CODIGO_DISCOVER == '#¡NULO!') {
                $cliente->CODIGO_DISCOVER = '';
                $discover++;
            }
            if ($cliente->CUOTAS_PTES_DISCOVER == '#¡NULO!') {
                $cliente->CUOTAS_PTES_DISCOVER = '';
                $discover++;
            }
            if ($cliente->CUOTA_REF_VIG_PENDIENTE_DISCOVER == '#¡NULO!') {
                $cliente->CUOTA_REF_VIG_PENDIENTE_DISCOVER = '';
                $discover++;
            }
            if ($cliente->VALOR_PEN_REF_VIG_DISCOVER == '#¡NULO!') {
                $cliente->VALOR_PEN_REF_VIG_DISCOVER = '';
                $discover++;
            }
            if ($cliente->IXF_DISCOVER == '#¡NULO!') {
                $cliente->IXF_DISCOVER = '';
                $discover++;
            }else{
                $cliente->IXF_DISCOVER=(float)str_replace (',','.',$cliente->IXF_DISCOVER );
            }
            if ($cliente->SCORE_DINERS_DISCOVER == '#¡NULO!') {
                $cliente->SCORE_DINERS_DISCOVER = '';
                $discover++;
            }
            if ($cliente->PAGO_REAL_DISCOVER == '#¡NULO!') {
                $cliente->PAGO_REAL_DISCOVER = 0;
                $discover++;
            }else{
                $cliente->PAGO_REAL_DISCOVER=(float)str_replace (',','.',$cliente->PAGO_REAL_DISCOVER );
            }
            if ($cliente->REESTRUCT_VIGENTE_DISCOVER == '#¡NULO!') {
                $cliente->REESTRUCT_VIGENTE_DISCOVER = '';
                $cliente->REESTRUCT_VIGENTE_DISCOVER2 = '';
                $discover++;
            }elseif($cliente->REESTRUCT_VIGENTE_DISCOVER=='REESTRUCTURACION'){$cliente->REESTRUCT_VIGENTE_DISCOVER2 = 'SEGUNDA REESTRUCTURACION';}

            if ($cliente->DES_ESPECIALIDAD_DISCOVER == '#¡NULO!') {
                $cliente->DES_ESPECIALIDAD_DISCOVER = '';
                $discover++;
            }
            if ($cliente->CODRET_DISCOVER == '#¡NULO!') {
                $cliente->CODRET_DISCOVER = '';
                $discover++;
            }
            if ($cliente->BOLNAC_DISCOVER == '#¡NULO!') {
                $cliente->BOLNAC_DISCOVER = '';
                $discover++;
            }
            if ($cliente->EMPRESA_DISCOVER == '#¡NULO!') {
                $cliente->EMPRESA_DISCOVER = '';
                $discover++;
            }
            if ($cliente->CAMPAŃA_CON_ECE_DISCOVER == '#¡NULO!') {
                $cliente->CAMPAŃA_CON_ECE_DISCOVER = '';
                $discover++;
            }
            if ($cliente->TOTAL_CUOTAS_REF == '#¡NULO!') {
                $cliente->TOTAL_CUOTAS_REF = '';
            }
            if ($cliente->TOTAL_CUOTAS_REF_VI == '#¡NULO!') {
                $cliente->TOTAL_CUOTAS_REF_VI = '';
            }
            if ($cliente->TOTAL_CUOTAS_REF_DI == '#¡NULO!') {
                $cliente->TOTAL_CUOTAS_REF_DI = '';
            }
        }

        //Validacion saldos facturados
        $deuda_actual = 0;
        $deuda_actual_v = 0;
        $deuda_actual_dis = 0;
        $saldo90ymas = 0;
        $saldo90ymas_v = 0;
        $saldo90ymas_dis = 0;
        $intereses_facturados = 0;
        $intereses_facturados_v = 0;
        $intereses_facturados_dis = 0;
        if ($cliente->CEDULA != 'SC') {
            $saldo90ymas = (float)str_replace (',','.',$cliente->D90_ORIG) + (float)str_replace (',','.',$cliente->DMAS90_ORIG);
            $saldo90ymas_v = (float)str_replace (',','.',$cliente->D90_ORIG_VISA) + (float)str_replace (',','.',$cliente->DMAS90_ORIG_VISA);
            $saldo90ymas_dis = (float)str_replace (',','.',$cliente->D90_ORIG_DISCOVER) + (float)str_replace (',','.',$cliente->DMAS90_ORIG_DISCOVER);

            $deuda_actual = $saldo90ymas + (float)str_replace (',','.',$cliente->D60_ORIG) + (float)str_replace (',','.',$cliente->D30_ORIG) + (float)str_replace (',','.',$cliente->ACTUALES_ORIG);
            $deuda_actual_v = $saldo90ymas_v + (float)str_replace (',','.',$cliente->D60_ORIG_VISA) + (float)str_replace (',','.',$cliente->D30_ORIG_VISA) + (float)str_replace (',','.',$cliente->ACTUALES_ORIG_VISA);
            $deuda_actual_dis = $saldo90ymas_dis + (float)str_replace (',','.',$cliente->D60_ORIG_DISCOVER)+ (float)str_replace (',','.',$cliente->D30_ORIG_DISCOVER ) + (float)str_replace (',','.',$cliente->ACTUALES_ORIG_DISCOVER );

            //dd((float)str_replace (',','.',$cliente->D30_ORIG_DISCOVER ));

            //Calculo de intereses facturados
            if ($cliente->INTERES_TOTAL > 1500) {
                $intereses_facturados = $intereses_facturados + (float)(str_replace (',','.',$cliente->INTERES_TOTAL)+ 50);

            } else {
                if ($cliente->INTERES_TOTAL > 599) {
                    $intereses_facturados = (float)(str_replace (',','.',$cliente->INTERES_TOTAL)) + 40;
                } else {
                    if ($cliente->INTERES_TOTAL > 299) {
                        $intereses_facturados = (float)(str_replace (',','.',$cliente->INTERES_TOTAL)) + 30;
                    } else {
                        if ($cliente->INTERES_TOTAL > 99) {
                            $intereses_facturados = (float)(str_replace (',','.',$cliente->INTERES_TOTAL)) + 25;
                        }else {
                            if ($cliente->INTERES_TOTAL <= 99 && $cliente->INTERES_TOTAL > 0) {
                                $intereses_facturados = (float)(str_replace(',', '.', $cliente->INTERES_TOTAL)) + 20;
                            } else {
                                $intereses_facturados = (float)(str_replace(',', '.', $cliente->INTERES_TOTAL));
                            }
                        }
                    }
                }
            }

            //Calculo de intereses facturados visa
            if ($cliente->INTERES_TOTAL_VISA > 1500) {
                $intereses_facturados_v = $intereses_facturados_v + (float)(str_replace (',','.',$cliente->INTERES_TOTAL_VISA))+ 50;

            } else {
                if ($cliente->INTERES_TOTAL_VISA > 599) {
                    $intereses_facturados_v = (float)(str_replace (',','.',$cliente->INTERES_TOTAL_VISA))+ 40;
                } else {
                    if ($cliente->INTERES_TOTAL_VISA > 299) {
                        $intereses_facturados_v = (float)(str_replace (',','.',$cliente->INTERES_TOTAL_VISA))+ 30;
                    } else {
                        if ($cliente->INTERES_TOTAL_VISA > 99) {
                            $intereses_facturados_v = (float)(str_replace (',','.',$cliente->INTERES_TOTAL_VISA))+ 25;
                        }else {
                            if ($cliente->INTERES_TOTAL_VISA <= 99 && $cliente->INTERES_TOTAL_VISA > 0) {
                                $intereses_facturados_v = (float)(str_replace(',', '.', $cliente->INTERES_TOTAL_VISA)) + 20;
                            } else {
                                $intereses_facturados_v = (float)(str_replace(',', '.', $cliente->INTERES_TOTAL_VISA));
                            }
                        }
                    }
                }
            }

            //Calculo de intereses facturados discover

            if ($cliente->INTERES_TOTAL_DISCOVER > 1500) {
                $intereses_facturados_dis = $intereses_facturados_dis + (float)(str_replace (',','.',$cliente->INTERES_TOTAL_DISCOVER)) + 50;
            } else {
                if ($cliente->INTERES_TOTAL_DISCOVER > 599) {
                    $intereses_facturados_dis =(float)(str_replace (',','.',$cliente->INTERES_TOTAL_DISCOVER))+ 40;
                } else {
                    if ($cliente->INTERES_TOTAL_DISCOVER > 299) {
                        $intereses_facturados_dis = (float)(str_replace (',','.',$cliente->INTERES_TOTAL_DISCOVER))+ 30;
                    } else {
                        if ($cliente->INTERES_TOTAL_DISCOVER > 99) {
                            $intereses_facturados_dis = (float)(str_replace (',','.',$cliente->INTERES_TOTAL_DISCOVER))+ 25;
                        }else{
                            if ($cliente->INTERES_TOTAL_DISCOVER <= 99 && $cliente->INTERES_TOTAL_DISCOVER > 0) {
                                $intereses_facturados_dis = (float)(str_replace (',','.',$cliente->INTERES_TOTAL_DISCOVER))+ 20;
                            }else{
                                $intereses_facturados_dis = (float)(str_replace (',','.',$cliente->INTERES_TOTAL_DISCOVER));
                            }
                        }
                    }
                }
            }
        }

        $motivo_no_pago = motivo::get();
        $tasa_interes = new tasas_intereses();
        $intereses_meses = DB::table('tbl_tasas_interes')->select('*')
            ->where('diners', '=', 1)
            ->get();
        $intereses_meses_v = DB::table('tbl_tasas_interes')->select('*')
            ->where('visa', '=', 1)
            ->get();
        $intereses_meses_dis = DB::table('tbl_tasas_interes')->select('*')
            ->where('discover', '=', 1)
            ->get();
        $estado_civil = estado_civil::get();
        $actividad_economica = actividad_economica::get();

        //dd($visa." ".$diners." ".$discover);

        $codigo_cancelacion_actual = codigo_cancelacion::get();
        $codigo_cancelacion_solicitado = codigo_cancelacion_solicitado::get();

        return view('busqueda.index', compact('cliente', 'edad_cartera', 'codigo_cancelacion', 'cod_boletin', 'saldo90ymas', 'deuda_actual', 'intereses_facturados', 'motivo_no_pago', 'tasas_intereses', 'tasa_interes', 'intereses_meses', 'intereses_meses_v', 'intereses_meses_dis', 'estado_civil', 'edad_cartera_v', 'codigo_cancelacion_v', 'cod_boletin_v', 'saldo90ymas_v', 'deuda_actual_v', 'intereses_facturados_v', 'edad_cartera_dis', 'codigo_cancelacion_dis', 'cod_boletin_dis', 'saldo90ymas_dis', 'deuda_actual_dis', 'intereses_facturados_dis', 'diners', 'visa', 'discover', 'actividad_economica','codigo_cancelacion_actual','codigo_cancelacion_solicitado','id_carga'));
    }

    function getIntereses(Request $request)
    {
        //$tasa_interes = tasas_intereses::where('meses_plazo', parse_str($request->mes));
        if ($request->mes != 0) {
            $tasa_interes = DB::table('tbl_tasas_interes')->select('*')
                ->where('meses_plazo', '=', $request->mes)
                ->get();
        } else {
            $tasa_interes[0] = new tasas_intereses();
            $tasa_interes[0]->factor_calculado = 0;
        }

        /*if($request->ajax()){
            $dato=$request->mes;
            return $dato;
        }*/
        return $tasa_interes;
    }

    public function procesarRefinanciamiento(Request $request)
    {
        //dd($request);

        $mes = date("F");
        if ($mes == "January") $mes = "Enero";
        if ($mes == "February") $mes = "Febrero";
        if ($mes == "March") $mes = "Marzo";
        if ($mes == "April") $mes = "Abril";
        if ($mes == "May") $mes = "Mayo";
        if ($mes == "June") $mes = "Junio";
        if ($mes == "July") $mes = "Julio";
        if ($mes == "August") $mes = "Agosto";
        if ($mes == "September") $mes = "Septiembre";
        if ($mes == "October") $mes = "Octubre";
        if ($mes == "November") $mes = "Noviembre";
        if ($mes == "December") $mes = "Diciembre";

        $proceso_d = array();

        if ($request->diners && $request->consolidar_diners) {
            //$proceso_d=generarArray($request);
            $proceso_d['id_carga'] = $request->carga;
            $proceso_d['nro'] = 1;
            $proceso_d['fecha_solicitud'] = date("d-") . $mes;
            $proceso_d['marca'] = "DINERS CLUB";

            $proceso_d['cod_motivo'] = $request->motivo_no_pago;
            $proceso_d['motivo_de_no_pago'] = motivo::find($request->motivo_no_pago)->descripcion;
            //dd(motivo::find($request->motivo_no_pago));

            $proceso_d['empresa_externa'] = "COBEFEC";
            $oficial_responsable = oficial_r::where('habilitado', '=', 1)->firstOrFail();
            $proceso_d['oficial_responsable'] = $oficial_responsable->descripcion;
            $proceso_d['cod_encargado'] = $oficial_responsable->codigo;
            $tipo = "";

            if ($request->exigible_chk == 1) {
                $tipo = "TOTAL";
            } else {
                $tipo = "EXIGIBLE";
            }
            $proceso_d['tipo'] = $tipo;
            $proceso_d['digitos_cedula'] = $request->cedula;
            $proceso_d['valida_ci'] = "OK";
            $proceso_d['nombre_del_cliente'] = $request->nombres;

            $proceso_d['codigo_cancelacion'] = $request->codigo_cancelacion;


            $proceso_d['plazo'] = $request->plazo_financiamiento;
            $proceso_d['ciclo'] = $request->ciclo_diners;

            $proceso_d['consolidacion'] = "";
            $proceso_d['observaciones_consolidacion'] = "";
            $otras_tarjetas_chk='';
            if($request->otras_tarjetas_chk=='on'){$otras_tarjetas_chk='SI';};
            $proceso_d['consolidacion'] =$otras_tarjetas_chk;
            $proceso_d['observaciones_consolidacion'] =$request->observacion_unificacion;

            $proceso_d['tipo_de_refinanciacion'] = "";
            if (strlen($request->segunda_reestructuracion_diners) > 3) {
                $proceso_d['tipo_de_refinanciacion'] = "SEGUNDA REESTRUCTURACION";
            } else {
                $proceso_d['tipo_de_refinanciacion'] = $request->tipofinaciamiento_h;
            }

            $proceso_d['ciudad'] = $request->ciudad;
            $proceso_d['zona'] = $request->zona;

            $proceso_d['estado_civil'] = $request->estado_civil;
            $proceso_d['ci_conyugue'] = $request->cedula_conyugue;
            $proceso_d['nombre_conyugue'] = $request->nombres_conyugue;
            $request->valor_a_financiar_h = (float)$request->valor_a_financiar_h;
            $proceso_d['validacion_conyugue']='';
            //INICIO VALIDACIONES CONYUGUE
            if (strlen($request->cedula) == 0) {
                $proceso_d['validacion_conyugue']='';
            }else{
                if((float)$request->valor_a_financiar_h < 10001 && strlen($proceso_d['consolidacion'])==0 && ($request->tipofinaciamiento_h == "REFINANCIACION" || $request->tipofinaciamiento_h == "NOVACION")){
                    $proceso_d['validacion_conyugue']='NO SE NECESITA INF. ESTADO CIVIL';
                }else{
                    if ($request->estado_civil == "CASADO/A") {
                        if (strlen($request->cedula_conyugue) == 10) {
                            $proceso_d['validacion_conyugue'] = "OK DATOS";
                        }
                    }else{
                        if ($request->estado_civil == "VIUDO/A" || $request->estado_civil == "DIVORCIADO/A" || $request->estado_civil == "SOLTERO/A") {
                            if ($request->cedula_conyugue == "" && $request->nombres_conyugue == "") {
                                $proceso_d['validacion_conyugue'] = "OK DATOS";
                            }
                        }else{
                            $proceso_d['validacion_conyugue'] = "ERROR REVISAR INF. ESTADO CIVIL";
                        }
                    }

                }
            }
            //FIN VALIDACIONES CONYUGUE

            //VALIDACION FIRMA DOCUMENTOS
            //$proceso_d['valor_deuda_ref'] = $request->valor_a_financiar_h;
            $proceso_d['valor_deuda_ref'] = $request->valor_a_financiar_h;
            //dd($request->saldo_interes);
            //echo $request->valor_a_financiar_h.' <br> ';
            //echo $request->saldo_interes;
            //dd($proceso_d['valor_deuda_ref']);
            $proceso_d['ficha']='';
            if (strlen($request->cedula) == 0) {
                $proceso_d['firma_documentos'] = "";
            }else {
                if ($proceso_d['validacion_conyugue'] == "OK DATOS" || $proceso_d['validacion_conyugue'] == "NO SE NECESITA INF. ESTADO CIVIL") {
                    if ((float)$proceso_d['valor_deuda_ref'] <= 10000 && strlen($proceso_d['consolidacion'])==0 && $proceso_d['tipo_de_refinanciacion'] != "SEGUNDA REESTRUCTURACION" && ($request->tipofinaciamiento_h == "REFINANCIACION" || $request->tipofinaciamiento_h == "NOVACION")) {
                        $proceso_d['firma_documentos'] = "SIN FIRMA DOCUMENTOS";
                    } else {
                        if ($request->estado_civil == "CASADO/A") {
                            if ((float)$proceso_d['valor_deuda_ref'] > 30000 || $proceso_d['tipo_de_refinanciacion'] == "SEGUNDA REESTRUCTURACION") {
                                $proceso_d['firma_documentos'] = "FIRMA DOC. CON CONYUGE Y ELAB FICHA";
                                $proceso_d['ficha']=1;
                            } else {
                                $proceso_d['firma_documentos'] = "FIRMA DOCUMENTOS CON CONYUGE";
                                $proceso_d['ficha']=1;
                            }
                        } else {
                            if ((float)$proceso_d['valor_deuda_ref'] > 30000 || $proceso_d['tipo_de_refinanciacion'] == "SEGUNDA REESTRUCTURACION") {
                                $proceso_d['firma_documentos'] = "FIRMA DOC. CON ELAB FICHA";
                                $proceso_d['ficha']=1;
                            } else {
                                $proceso_d['firma_documentos'] = "FIRMA DOCUMENTOS";
                                $proceso_d['ficha']=1;
                            }
                        }
                    }
                } else {
                    $proceso_d['firma_documentos'] = "ERROR VALIDACION CONYUGE";
                }
            }



            //FIN VALIDACION FIRMA DOCUMENTOS


            //validacion general si el plazo es igual o mayor a 60 meses
            if($request->plazo_financiamiento >= 60) {
                $proceso_d['firma_documentos'] = "FIRMA DOCUMENTOS";
            }


            $proceso_d['observacion_excepcion'] = $request->excepcion_firma_conyugue;
            $proceso_d['valor_abono_mismo_dia_del_corte'] = "";
            $proceso_d['direccion_neg_con_firma_documentos'] = $request->direccion_visita_refinanciamiento;
            $proceso_d['telefonos_c'] = $request->telefonos_refinanciamiento;
            $proceso_d['gestor'] = Auth::user()->name;
            if (Auth::user()->name) {
                $proceso_d['campania'] = "CAMPO";
            } else {
                $proceso_d['campania'] = "CU TELEFONIA";
            }

            $proceso_d['grabacion'] = "SI";

            if ($request->debito_automatico == 'SI') {
                $proceso_d['debito_automatico'] = "SI";
            } else {
                $proceso_d['debito_automatico'] = "NO";
            }

            $proceso_d['ingresos_reales'] = $request->ingresos_reales;
            $proceso_d['gastos_reales'] = $request->gastos_reales;
            $proceso_d['actividad_economica'] = $request->actividad_economica;

            $proceso_d['formato_consolidado']=formatos::where('descripcion','refinanciamiento')->first()->id;
            $proceso_d['saldo_interes'] = $request->saldo_interes;
            $proceso_d['observacion_negociacion_especial']='';
            if($request->negociacion_especial_chk=='on'){
                $proceso_d['observacion_negociacion_especial'] = 'NEGOCIACION ESPECIAL';
            }else{
                $proceso_d['id_negociacion_especial'] = 1;
            }
            $proceso_d['id_negociacion_especial'] = negociacion_especial::where('descripcion','Sin negociacion especial')->first()->id;
            $proceso_d['codigo_de_cancelacion_solicitado'] = $request->codigo_de_cancelacion_solicitado;
            $proceso_d['valor_abono_mismo_dia_del_corte'] = $request->valor_abono_mismo_dia_del_corte;

            $proceso_d['total_intereses_h'] = $request->total_intereses_h;
            $proceso_d['total_financiamiento_h'] = $request->total_financiamiento_h;
            $proceso_d['valor_cuota_mensual_h'] = $request->valor_cuota_mensual_h;
            $proceso_d['abono_total_h'] = $request->abono_total_h;
            $proceso_d['edad_cartera'] = $request->edad_cartera;

            $proceso_d['unifica'] = $request->unifica;

            $proceso_d['visa_edad'] = '';
            $proceso_d['visa_ciclo'] = '';
            $proceso_d['visa_abono_total'] = 0;
            if($request->valor_visa!=null){
                $proceso_d['visa_edad'] = $request->edad_cartera_v;
                $proceso_d['visa_ciclo'] = $request->ciclo_visa;
                $proceso_d['visa_abono_total'] = (float)$request->abono_total_h_v;
            }

            $proceso_d['dis_edad'] = '';
            $proceso_d['dis_ciclo'] = '';
            $proceso_d['dis_abono_total'] = 0;
            if($request->valor_discover!=null){
                $proceso_d['dis_edad'] = $request->edad_cartera_dis;
                $proceso_d['dis_ciclo'] = $request->ciclo_discover;
                $proceso_d['dis_abono_total'] = (float)$request->abono_total_h_dis;
            }

        }
        $proceso_v = array();

        if ($request->visa && $request->consolidar_visa) {
            //$proceso_v=generarArray($request);
            $proceso_v['id_carga'] = $request->carga;
            $proceso_v['nro'] = 1;
            $proceso_v['fecha_solicitud'] = date("d-") . $mes;
            $proceso_v['marca'] = "VISA INTERDIN";

            $proceso_v['cod_motivo'] = $request->motivo_no_pago_v;
            $proceso_v['motivo_de_no_pago'] = motivo::find($request->motivo_no_pago_v)->descripcion;
            //dd(motivo::find($request->motivo_no_pago));

            $proceso_v['empresa_externa'] = "COBEFEC";
            $oficial_responsable = oficial_r::where('habilitado', '=', 1)->firstOrFail();
            $proceso_v['oficial_responsable'] = $oficial_responsable->descripcion;
            $proceso_v['cod_encargado'] = $oficial_responsable->codigo;
            $tipo = "";
            if ($request->exigible_chk_v == 1) {
                $tipo = "TOTAL";
            } else {
                $tipo = "EXIGIBLE";
            }
            $proceso_v['tipo'] = $tipo;
            $proceso_v['digitos_cedula'] = $request->cedula;
            $proceso_v['valida_ci'] = "OK";
            $proceso_v['nombre_del_cliente'] = $request->nombres;

            $proceso_v['codigo_cancelacion'] = $request->codigo_cancelacion_v;
            $proceso_v['edad_cartera'] = $request->edad_cartera_v;
            $proceso_v['valor_pago_exigible'] = str_replace(',','.',$request->minimo_a_pagar_h_v);
            $proceso_v['valor_abono'] = str_replace(',','.',$request->abono_total_h_v);
            $proceso_v['valor_credito'] = (float)$proceso_v['valor_pago_exigible']-(float)$proceso_v['valor_abono'];

            $proceso_v['valor_debito']  =$proceso_v['valor_credito'];
            $proceso_v['total_riesgo_deuda'] = $request->total_riesgo_v;

            $proceso_v['plazo'] = $request->plazo_financiamiento_v;
            $proceso_v['ciclo'] = $request->ciclo_visa;

            $proceso_v['consolidacion'] = "";
            $proceso_v['observaciones_consolidacion'] = "";
            $otras_tarjetas_chk='';
            if($request->otras_tarjetas_chk_v=='on'){$otras_tarjetas_chk='SI';};
            $proceso_v['consolidacion'] =$otras_tarjetas_chk;
            $proceso_v['observaciones_consolidacion'] =$request->observacion_unificacion_v;

            $proceso_v['tipo_de_refinanciacion'] = "";
            if (strlen($request->segunda_reestructuracion_visa) > 3) {
                $proceso_v['tipo_de_refinanciacion'] = "SEGUNDA REESTRUCTURACION";
            } else {
                $proceso_v['tipo_de_refinanciacion'] = $request->tipofinaciamiento_h_v;
            }

            $proceso_v['ciudad'] = $request->ciudad;
            $proceso_v['zona'] = $request->zona;

            $proceso_v['estado_civil'] = $request->estado_civil;
            $proceso_v['ci_conyugue'] = $request->cedula_conyugue;
            $proceso_v['nombre_conyugue'] = $request->nombres_conyugue;
            $request->valor_a_financiar_h_v = (float)$request->valor_a_financiar_h_v;
            $proceso_v['validacion_conyugue']='';
            //INICIO VALIDACIONES CONYUGUE
            if (strlen($request->cedula) == 0) {
                $proceso_v['validacion_conyugue']='';
            }else{
                if((float)$request->valor_a_financiar_h_v < 10001 && strlen($proceso_v['consolidacion'])==0 && ($request->tipofinaciamiento_h_v == "REFINANCIACION" || $request->tipofinaciamiento_h_v == "NOVACION")){
                    $proceso_v['validacion_conyugue']='NO SE NECESITA INF. ESTADO CIVIL';
                }else{
                    if ($request->estado_civil == "CASADO/A") {
                        if (strlen($request->cedula_conyugue) == 10) {
                            $proceso_v['validacion_conyugue'] = "OK DATOS";
                        }
                    }else{
                        if ($request->estado_civil == "VIUDO/A" || $request->estado_civil == "DIVORCIADO/A" || $request->estado_civil == "SOLTERO/A") {
                            if ($request->cedula_conyugue == "" && $request->nombres_conyugue == "") {
                                $proceso_v['validacion_conyugue'] = "OK DATOS";
                            }
                        }else{
                            $proceso_v['validacion_conyugue'] = "ERROR REVISAR INF. ESTADO CIVIL";
                        }
                    }

                }
            }
            //FIN VALIDACIONES CONYUGUE

            //VALIDACION FIRMA DOCUMENTOS
            $proceso_v['valor_deuda_ref'] = $request->valor_a_financiar_h_v;
            $proceso_v['ficha']='';
            if (strlen($request->cedula) == 0) {
                $proceso_v['firma_documentos'] = "";
            }else {
                if ($proceso_v['validacion_conyugue'] == "OK DATOS" || $proceso_v['validacion_conyugue'] == "NO SE NECESITA INF. ESTADO CIVIL") {
                    if ((float)$proceso_v['valor_deuda_ref'] <= 10000 && strlen($proceso_v['consolidacion'])==0 && $proceso_v['tipo_de_refinanciacion'] != "SEGUNDA REESTRUCTURACION" && ($request->tipofinaciamiento_h_v == "REFINANCIACION" || $request->tipofinaciamiento_h_v == "NOVACION")) {
                        $proceso_v['firma_documentos'] = "SIN FIRMA DOCUMENTOS";
                    } else {
                        if ($request->estado_civil == "CASADO/A") {
                            if ((float)$proceso_v['valor_deuda_ref'] > 30000 || $proceso_v['tipo_de_refinanciacion'] == "SEGUNDA REESTRUCTURACION") {
                                $proceso_v['firma_documentos'] = "FIRMA DOC. CON CONYUGE Y ELAB FICHA";
                                $proceso_v['ficha']=1;
                            } else {
                                $proceso_v['firma_documentos'] = "FIRMA DOCUMENTOS CON CONYUGE";
                                $proceso_v['ficha']=1;
                            }
                        } else {
                            if ((float)$proceso_v['valor_deuda_ref'] > 30000 || $proceso_v['tipo_de_refinanciacion'] == "SEGUNDA REESTRUCTURACION") {
                                $proceso_v['firma_documentos'] = "FIRMA DOC. CON ELAB FICHA";
                                $proceso_v['ficha']=1;
                            } else {
                                $proceso_v['firma_documentos'] = "FIRMA DOCUMENTOS";
                                $proceso_v['ficha']=1;
                            }
                        }

                    }
                } else {
                    $proceso_v['firma_documentos'] = "ERROR VALIDACION CONYUGE";
                }
            }

            //FIN VALIDACION FIRMA DOCUMENTOS

            //validacion general si el plazo es igual o mayor a 60 meses
            if($request->plazo_financiamiento_v >= 60) {
                $proceso_v['firma_documentos'] = "FIRMA DOCUMENTOS";
            }

            $proceso_v['observacion_excepcion'] = $request->excepcion_firma_conyugue;
            $proceso_v['valor_abono_mismo_dia_del_corte'] = "";
            $proceso_v['direccion_neg_con_firma_documentos'] = $request->direccion_visita_refinanciamiento;
            $proceso_v['telefonos_c'] = $request->telefonos_refinanciamiento;
            $proceso_v['gestor'] = Auth::user()->name;
            if (Auth::user()->name) {
                $proceso_v['campania'] = "CAMPO";
            } else {
                $proceso_v['campania'] = "CU TELEFONIA";
            }

            $proceso_v['grabacion'] = "SI";
            if ($request->debito_automatico_v == 'SI') {
                $proceso_v['debito_automatico'] = "SI";
            } else {
                $proceso_v['debito_automatico'] = "NO";
            }

            $proceso_v['ingresos_reales'] = $request->ingresos_reales;
            $proceso_v['gastos_reales'] = $request->gastos_reales;
            $proceso_v['actividad_economica'] = $request->actividad_economica;

            $proceso_v['formato_consolidado'] = formatos::where('descripcion',$request->formato_consolidado_v)->first()->id;
            $proceso_v['saldo_interes'] = $request->saldo_interes_v;
            $proceso_v['observacion_negociacion_especial']='';
            if($request->negociacion_especial_chk_v=='on'){
                $proceso_v['observacion_negociacion_especial'] = 'NEGOCIACION ESPECIAL';
                $proceso_v['id_negociacion_especial'] = negociacion_especial::where('descripcion',$request->formato_consolidado_v.' especial')->first()->id;
            }else{
                $proceso_v['id_negociacion_especial'] = 1;
            }
            $proceso_v['codigo_de_cancelacion_solicitado'] = $request->codigo_de_cancelacion_solicitado_v;
            $proceso_v['valor_abono_mismo_dia_del_corte'] = $request->valor_abono_mismo_dia_del_corte_v;

            $proceso_v['total_intereses_h'] = $request->total_intereses_h_v;
            $proceso_v['total_financiamiento_h'] = $request->total_financiamiento_h_v;
            $proceso_v['valor_cuota_mensual_h'] = $request->valor_cuota_mensual_h_v;
            $proceso_v['abono_total_h'] = $request->abono_total_h_v;
            $proceso_v['edad_cartera'] = $request->edad_cartera_v;
            $proceso_v['tipo_cuenta'] = $request->tipo_cuenta_v;

        }
        $proceso_dis = array();

        if ($request->discover && $request->consolidar_discover) {
            $proceso_dis['id_carga'] = $request->carga;
            $proceso_dis['nro'] = 1;
            $proceso_dis['fecha_solicitud'] = date("d-") . $mes;
            $proceso_dis['marca'] = "DISCOVER";

            $proceso_dis['cod_motivo'] = $request->motivo_no_pago_dis;
            $proceso_dis['motivo_de_no_pago'] = motivo::find($request->motivo_no_pago_dis)->descripcion;
            //dd(motivo::find($request->motivo_no_pago));

            $proceso_dis['empresa_externa'] = "COBEFEC";
            $oficial_responsable = oficial_r::where('habilitado', '=', 1)->firstOrFail();
            $proceso_dis['oficial_responsable'] = $oficial_responsable->descripcion;
            $proceso_dis['cod_encargado'] = $oficial_responsable->codigo;
            $tipo = "";
            if ($request->total_chk_dis == true) {
                $tipo = "TOTAL";
            } else {
                $tipo = "EXIGIBLE";
            }
            $proceso_dis['tipo'] = $tipo;
            $proceso_dis['digitos_cedula'] = $request->cedula;
            $proceso_dis['valida_ci'] = "OK";
            $proceso_dis['nombre_del_cliente'] = $request->nombres;

            $proceso_dis['codigo_cancelacion'] = $request->codigo_cancelacion_dis;
            $proceso_dis['edad_cartera'] = $request->edad_cartera_dis;
            $proceso_dis['valor_pago_exigible'] = (float)str_replace(',','.',$request->minimo_a_pagar_h_dis);
            $proceso_dis['valor_abono'] = (float)str_replace(',','.',$request->abono_total_h_dis);
            $proceso_dis['valor_credito'] = $proceso_dis['valor_pago_exigible']-$proceso_dis['valor_abono'];
            $proceso_dis['valor_debito']  = $proceso_dis['valor_credito'];
            $proceso_dis['total_riesgo_deuda'] = $request->total_riesgo_dis;

            $proceso_dis['plazo'] = $request->plazo_financiamiento_dis;
            $proceso_dis['ciclo'] = $request->ciclo_discover;

            $proceso_dis['consolidacion'] = "";
            $proceso_dis['observaciones_consolidacion'] = "";

            $proceso_dis['tipo_de_refinanciacion'] = "";
            if (strlen($request->segunda_reestructuracion_discover) > 3) {
                $proceso_dis['tipo_de_refinanciacion'] = "SEGUNDA REESTRUCTURACION";
            } else {
                $proceso_dis['tipo_de_refinanciacion'] = $request->tipofinaciamiento_h_dis;
            }

            $proceso_dis['ciudad'] = $request->ciudad;
            $proceso_dis['zona'] = $request->zona;

            $proceso_dis['estado_civil'] = $request->estado_civil;
            $proceso_dis['ci_conyugue'] = $request->cedula_conyugue;
            $proceso_dis['nombre_conyugue'] = $request->nombres_conyugue;
            $request->valor_a_financiar_h_dis = (float)$request->valor_a_financiar_h_dis;
            $proceso_dis['validacion_conyugue']='';
            //INICIO VALIDACIONES CONYUGUE
            if (strlen($request->cedula) == 0) {
                $proceso_dis['validacion_conyugue']='';
            }else{
                if((float)$request->valor_a_financiar_h_dis < 10001 && strlen($proceso_dis['consolidacion'])==0 && ($request->tipofinaciamiento_h_dis == "REFINANCIACION" || $request->tipofinaciamiento_h_dis == "NOVACION")){
                    $proceso_dis['validacion_conyugue']='NO SE NECESITA INF. ESTADO CIVIL';
                }else{
                    if ($request->estado_civil == "CASADO/A") {
                        if (strlen($request->cedula_conyugue) == 10) {
                            $proceso_dis['validacion_conyugue'] = "OK DATOS";
                        }
                    }else{
                        if ($request->estado_civil == "VIUDO/A" || $request->estado_civil == "DIVORCIADO/A" || $request->estado_civil == "SOLTERO/A") {
                            if ($request->cedula_conyugue == "" && $request->nombres_conyugue == "") {
                                $proceso_dis['validacion_conyugue'] = "OK DATOS";
                            }
                        }else{
                            $proceso_dis['validacion_conyugue'] = "ERROR REVISAR INF. ESTADO CIVIL";
                        }
                    }

                }
            }
            //FIN VALIDACIONES CONYUGUE

            //VALIDACION FIRMA DOCUMENTOS
            $proceso_dis['valor_deuda_ref'] = $request->valor_a_financiar_h_dis;
            if (strlen($request->cedula) == 0) {
                $proceso_dis['firma_documentos'] = "";
            }else {
                if ($proceso_dis['validacion_conyugue'] == "OK DATOS" || $proceso_dis['validacion_conyugue'] == "NO SE NECESITA INF. ESTADO CIVIL") {
                    if ((float)$proceso_dis['valor_deuda_ref'] <= 10000 && strlen($proceso_dis['consolidacion'])==0 && $proceso_dis['tipo_de_refinanciacion'] != "SEGUNDA REESTRUCTURACION" && ($request->tipofinaciamiento_h_dis == "REFINANCIACION" || $request->tipofinaciamiento_h_dis == "NOVACION")) {
                        $proceso_dis['firma_documentos'] = "SIN FIRMA DOCUMENTOS";
                    } else {
                        if ($request->estado_civil == "CASADO/A") {
                            if ((float)$proceso_dis['valor_deuda_ref'] > 30000 || $proceso_dis['tipo_de_refinanciacion'] == "SEGUNDA REESTRUCTURACION") {
                                $proceso_dis['firma_documentos'] = "FIRMA DOC. CON CONYUGE Y ELAB FICHA";
                            } else {
                                $proceso_dis['firma_documentos'] = "FIRMA DOCUMENTOS CON CONYUGE";
                            }
                        } else {
                            if ((float)$proceso_dis['valor_deuda_ref'] > 30000 || $proceso_dis['tipo_de_refinanciacion'] == "SEGUNDA REESTRUCTURACION") {
                                $proceso_dis['firma_documentos'] = "FIRMA DOC. CON ELAB FICHA";
                            } else {
                                $proceso_dis['firma_documentos'] = "FIRMA DOCUMENTOS";
                            }
                        }

                    }
                } else {
                    $proceso_dis['firma_documentos'] = "ERROR VALIDACION CONYUGE";
                }
            }

            //FIN VALIDACION FIRMA DOCUMENTOS

            //validacion general si el plazo es igual o mayor a 60 meses
            if($request->plazo_financiamiento_dis >= 60) {
                $proceso_dis['firma_documentos'] = "FIRMA DOCUMENTOS";
            }

            $proceso_dis['observacion_excepcion'] = $request->excepcion_firma_conyugue;
            $proceso_dis['valor_abono_mismo_dia_del_corte'] = "";
            $proceso_dis['direccion_neg_con_firma_documentos'] = $request->direccion_visita_refinanciamiento;
            $proceso_dis['telefonos_c'] = $request->telefonos_refinanciamiento;
            $proceso_dis['gestor'] = Auth::user()->name;
            if (Auth::user()->name) {
                $proceso_dis['campania'] = "CAMPO";
            } else {
                $proceso_dis['campania'] = "CU TELEFONIA";
            }

            echo $request->debito_automatico_dis;
            if ($request->debito_automatico_dis == 'SI') {
                $proceso_dis['debito_automatico'] = "SI";
            } else {
                $proceso_dis['debito_automatico'] = "NO";
            }

            $proceso_dis['ingresos_reales'] = $request->ingresos_reales;
            $proceso_dis['gastos_reales'] = $request->gastos_reales;
            $proceso_dis['actividad_economica'] = $request->actividad_economica;
            $proceso_dis['formato_consolidado'] = formatos::where('descripcion',$request->formato_consolidado_dis)->first()->id;
            $proceso_dis['saldo_interes'] = $request->saldo_interes_dis;
            $proceso_dis['observacion_negociacion_especial']='';
            if($request->negociacion_especial_chk_dis=='on'){
                $proceso_dis['observacion_negociacion_especial'] = 'NEGOCIACION ESPECIAL';
                $proceso_dis['id_negociacion_especial'] = negociacion_especial::where('descripcion',$request->formato_consolidado_dis.' especial')->first()->id;
            }else{
                $proceso_dis['id_negociacion_especial'] = 1;
            }
            $proceso_dis['codigo_de_cancelacion_solicitado'] = $request->codigo_de_cancelacion_solicitado_dis;
            $proceso_dis['valor_abono_mismo_dia_del_corte'] = $request->valor_abono_mismo_dia_del_corte_dis;

            $proceso_dis['total_intereses_h'] = $request->total_intereses_h_dis;
            $proceso_dis['total_financiamiento_h'] = $request->total_financiamiento_h_dis;
            $proceso_dis['valor_cuota_mensual_h'] = $request->valor_cuota_mensual_h_dis;
            $proceso_dis['abono_total_h'] = $request->abono_total_h_dis;
            $proceso_dis['edad_cartera'] = $request->edad_cartera_dis;
            $proceso_dis['tipo_cuenta'] = $request->tipo_cuenta_dis;

        }

        //dd($proceso_d);

        return view('busqueda.procesar', compact('proceso_d', 'proceso_v', 'proceso_dis'));
    }

    public function guardarGestion(Request $request)
    {



        $proceso_d = "";
        $proceso_v = "";
        $proceso_dis = "";
        if (unserialize($request->proceso_d)!=false){
            $proceso_d = unserialize($request->proceso_d);
        }
        if (unserialize($request->proceso_v)!=false){
            $proceso_d = unserialize($request->proceso_v);
        }
        if (unserialize($request->proceso_dis)!=false){
            $proceso_d = unserialize($request->proceso_dis);
        }


        $proceso_d['mensaje']=$request->mensaje;
        $proceso_d['motivo'] = $request->motivo;
        $proceso_d['recomendacion'] = $request->recomendacion;
        $proceso_d['rrecomendacion'] = $request->rrecomendacion;
        $proceso_d['ficha'] = 1;
        $proceso_d['verficha'] = $request->verficha;

        if ($request->verficha!='GUARDAR'){

            $abono=$proceso_d['abono_total_h'];
            $edad=$proceso_d['verficha'].' '.$proceso_d['edad_cartera'].' días';
            $ciclo=$proceso_d['verficha'].' C'.$proceso_d['ciclo'];

            if ($proceso_d['unifica']=='visa'){
                $abono=$proceso_d['abono_total_h']+$proceso_d['visa_abono_total'];
                $edad="DINERS " .$proceso_d['edad_cartera']. " días, VISA " .$proceso_d['visa_edad']. " días";
                $ciclo="DINERS C" .$proceso_d['ciclo']. "; VISA C " .$proceso_d['visa_ciclo'];
            }

            if ($proceso_d['unifica']=='discover'){
                $abono=$proceso_d['abono_total_h']+$proceso_d['dis_abono_total'];
                $edad="DINERS " .$proceso_d['edad_cartera']. " días, DISCOVER " .$proceso_d['dis_edad']. " días";
                $ciclo="DINERS C" .$proceso_d['ciclo']. "; DISCOVER C " .$proceso_d['dis_ciclo'];
            }

            if ($proceso_d['unifica']=='visa_discover'){
                $abono=$proceso_d['abono_total_h']+$proceso_d['visa_abono_total']+$proceso_d['dis_abono_total'];
                $edad="DINERS " .$proceso_d['edad_cartera']. " días, VISA " .$proceso_d['visa_edad']. " días, DISCOVER " .$proceso_d['dis_edad']. " días";
                $ciclo="DINERS C" .$proceso_d['ciclo']. "; VISA C " .$proceso_d['visa_ciclo']. "; DISCOVER C " .$proceso_d['dis_ciclo'];
            }


            $myFile=Excel::load(public_path() . '/storage/plantilla/FICHA.xlsx', function($excel) use ($proceso_d,$abono,$edad,$ciclo)
            {
                $excel->getActiveSheet()->setCellValue('D11',$proceso_d['nombre_del_cliente']);
                $excel->getActiveSheet()->setCellValue('D13',date('d-m-Y'));
                $excel->getActiveSheet()->setCellValue('D14',$ciclo);
                $excel->getActiveSheet()->setCellValue('D23',$edad);
                $excel->getActiveSheet()->setCellValue('D28',$proceso_d['motivo']);
                $excel->getActiveSheet()->setCellValue('E38',($proceso_d['valor_deuda_ref']+$abono));
                $excel->getActiveSheet()->setCellValue('E42',($proceso_d['valor_deuda_ref']+$abono));
                $excel->getActiveSheet()->setCellValue('E43',$abono);
                $excel->getActiveSheet()->setCellValue('E45',$proceso_d['valor_deuda_ref']);
                $excel->getActiveSheet()->setCellValue('E46',$proceso_d['total_intereses_h']);
                $excel->getActiveSheet()->setCellValue('E47',$proceso_d['total_financiamiento_h']);
                $excel->getActiveSheet()->setCellValue('E49',$proceso_d['plazo']);
                $excel->getActiveSheet()->setCellValue('E51',$proceso_d['valor_cuota_mensual_h']);
            });

            $myFile = $myFile->string('xlsx'); //change xlsx for the format you want, default is xls
            $response =  array(
                'name' => "filename", //no extention needed
                'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($myFile) //mime type of used format
            );
            return response()->json($response);

        }else{
            $formato = formatos::where('id', 1)->first()->descripcion;

            if (unserialize($request->proceso_d) != false) {
                if ($request->ficha_chk == 'true') {
                    $proceso_d['ficha'] = 1;

                    $abono=0;

                    if ($proceso_d['unifica']!=0){
                        $abono=$proceso_d['abono_total_h']+$proceso_d['"visa_abono_total'];
                    }else{
                        $abono=$proceso_d['abono_total_h'];
                    }

                    Excel::load(public_path() . '/storage/plantilla/FICHA.xlsx', function ($excel) use ($proceso_d,$abono) {
                        $excel->getActiveSheet()->setCellValue('D11', $proceso_d['nombre_del_cliente']);
                        $excel->getActiveSheet()->setCellValue('D13', date('d-m-Y'));
                        $excel->getActiveSheet()->setCellValue('D14', 'DINERS C' . $proceso_d['ciclo']);
                        $excel->getActiveSheet()->setCellValue('D23', 'DINERS ' . $proceso_d['edad_cartera'] . ' días');
                        $excel->getActiveSheet()->setCellValue('D28', $proceso_d['motivo']);
                        $excel->getActiveSheet()->setCellValue('E38', ($proceso_d['valor_deuda_ref'] + $abono));
                        $excel->getActiveSheet()->setCellValue('E42', ($proceso_d['valor_deuda_ref'] + $abono));
                        $excel->getActiveSheet()->setCellValue('E43', $abono);
                        $excel->getActiveSheet()->setCellValue('E45', $proceso_d['valor_deuda_ref']);
                        $excel->getActiveSheet()->setCellValue('E46', $proceso_d['total_intereses_h']);
                        $excel->getActiveSheet()->setCellValue('E47', $proceso_d['total_financiamiento_h']);
                        $excel->getActiveSheet()->setCellValue('E49', $proceso_d['plazo']);
                        $excel->getActiveSheet()->setCellValue('E51', $proceso_d['valor_cuota_mensual_h']);
                    })->store('xls', public_path() . '/storage/excel/');

                } else {
                    $proceso_d['ficha'] = 0;
                }

                $cabecera = guardarGestiones($formato, $proceso_d);
                $proceso_d = array($cabecera['cabecera'], $proceso_d);
                $archivos = guardarFicheros($_FILES, $cabecera['id_gestion'], $proceso_d[1]['marca'], $request);
                //generarExcel($proceso_d);
            }
            if (unserialize($request->proceso_v) != false) {
                if ($request->ficha_chk_v == 'true') {
                    $proceso_d['ficha'] = 1;

                    Excel::load(public_path() . '/storage/plantilla/FICHA.xlsx', function ($excel) use ($proceso_d) {
                        $excel->getActiveSheet()->setCellValue('D11', $proceso_d['nombre_del_cliente']);
                        $excel->getActiveSheet()->setCellValue('D13', date('d-m-Y'));
                        $excel->getActiveSheet()->setCellValue('D14', 'VISA C' . $proceso_d['ciclo']);
                        $excel->getActiveSheet()->setCellValue('D23', 'VISA ' . $proceso_d['edad_cartera'] . ' días');
                        $excel->getActiveSheet()->setCellValue('D28', $proceso_d['motivo']);
                        $excel->getActiveSheet()->setCellValue('E38', ($proceso_d['valor_deuda_ref'] + $proceso_d['abono_total_h']));
                        $excel->getActiveSheet()->setCellValue('E42', ($proceso_d['valor_deuda_ref'] + $proceso_d['abono_total_h']));
                        $excel->getActiveSheet()->setCellValue('E43', $proceso_d['abono_total_h']);
                        $excel->getActiveSheet()->setCellValue('E45', $proceso_d['valor_deuda_ref']);
                        $excel->getActiveSheet()->setCellValue('E46', $proceso_d['total_intereses_h']);
                        $excel->getActiveSheet()->setCellValue('E47', $proceso_d['total_financiamiento_h']);
                        $excel->getActiveSheet()->setCellValue('E49', $proceso_d['plazo']);
                        $excel->getActiveSheet()->setCellValue('E51', $proceso_d['valor_cuota_mensual_h']);
                    })->store('xls', public_path() . '/storage/excel/');

                } else {
                    $proceso_d['ficha'] = 0;
                }

                $formato = formatos::where('id', $proceso_d['formato_consolidado'])->first()->descripcion;
                $cabecera = guardarGestiones($formato, $proceso_d);
                $proceso_d = array($cabecera['cabecera'], $proceso_d);
                $archivos = guardarFicheros($_FILES, $cabecera['id_gestion'], $proceso_d[1]['marca'], $request);
                //generarExcel($proceso_d);
            }
            if (unserialize($request->proceso_dis) != false) {
                if ($request->ficha_chk_dis == 'true') {
                    $proceso_d['ficha'] = 1;

                    Excel::load(public_path() . '/storage/plantilla/FICHA.xlsx', function ($excel) use ($proceso_d) {
                        $excel->getActiveSheet()->setCellValue('D11', $proceso_d['nombre_del_cliente']);
                        $excel->getActiveSheet()->setCellValue('D13', date('d-m-Y'));
                        $excel->getActiveSheet()->setCellValue('D14', 'DISCOVER C' . $proceso_d['ciclo']);
                        $excel->getActiveSheet()->setCellValue('D23', 'DISCOVER ' . $proceso_d['edad_cartera'] . ' días');
                        $excel->getActiveSheet()->setCellValue('D28', $proceso_d['mensaje']);
                        $excel->getActiveSheet()->setCellValue('E38', ($proceso_d['valor_deuda_ref'] + $proceso_d['abono_total_h']));
                        $excel->getActiveSheet()->setCellValue('E42', ($proceso_d['valor_deuda_ref'] + $proceso_d['abono_total_h']));
                        $excel->getActiveSheet()->setCellValue('E43', $proceso_d['abono_total_h']);
                        $excel->getActiveSheet()->setCellValue('E45', $proceso_d['valor_deuda_ref']);
                        $excel->getActiveSheet()->setCellValue('E46', $proceso_d['total_intereses_h']);
                        $excel->getActiveSheet()->setCellValue('E47', $proceso_d['total_financiamiento_h']);
                        $excel->getActiveSheet()->setCellValue('E49', $proceso_d['plazo']);
                        $excel->getActiveSheet()->setCellValue('E51', $proceso_d['valor_cuota_mensual_h']);
                    })->store('xls', public_path() . '/storage/excel/');

                } else {
                    $proceso_d['ficha'] = 0;
                }

                $formato = formatos::where('id', $proceso_d['formato_consolidado'])->first()->descripcion;
                $cabecera = guardarGestiones($formato, $proceso_d);
                $proceso_d = array($cabecera['cabecera'], $proceso_d);
                $archivos = guardarFicheros($_FILES, $cabecera['id_gestion'], $proceso_d[1]['marca'], $request);

                //generarExcel($proceso_d);
            }
            //return view("home");

            $css = str_replace(' ', '', $proceso_d[1]['marca']);
            if ($proceso_d[1]['ficha'] == 1) {
                $destino = public_path() . '/storage/refinanciamientos/' . $cabecera['id_gestion'];
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
                $nombrearchivo = $cabecera['id_gestion'] . '_FICHA.xls';

                copy(public_path() . '/storage/excel/FICHA.xls', public_path() . '/storage/refinanciamientos/' . $cabecera['id_gestion'] . '/' . $nombrearchivo);

                $archivos2 = new archivos();
                $archivos2->nombre = $nombrearchivo;
                $archivos2->ruta = '/storage/refinanciamientos/' . $cabecera['id_gestion'];
                $archivos2->tarjeta = $proceso_d[1]['marca'];
                $archivos2->fecha_carga = $this->fecha_act;
                $archivos2->id_gestion = $cabecera['id_gestion'];
                $archivos2->save();

                $archivos .= ' -> Archivo <a href="' . $destino . '/' . $nombrearchivo . '" target="_blank">' . $nombrearchivo . '</a>';
            }
            //$mensaje = '-> Archivo <b> FICHA.xls </b> Subido correctamente. <br>';
            //if ($ficha=){};
            return response()->json(['message' => $archivos . '<br> NEGOCIACION ' . $proceso_d[1]['marca'] . ' GUARDADA EXITOSAMENTE', 'marca' => $proceso_d[1]['marca'], 'css' => $css]);
        }
    }

    public function consolidarTarjetas(Request $request)
    {

        $cabecera_refinanciamiento = array('Nros', 'FECHA SOLICITUD', 'MARCA', 'COD MOTIVO', 'MOTIVO DE NO PAGO', 'EMPRESA EXTERNA', 'OFICIAL RESPONSABLE', 'COD ENCARGADO', 'TIPO', 'DIGITOS CÉDULA', 'VALIDA CI', 'NOMBRE DEL CLIENTE', 'PLAZO', 'CICLO', 'CONSOLIDACION', 'OBSERVACIONES CONSOLIDACION', 'TIPO DE REFINANCIACION', 'CIUDAD', 'ZONA',
            'VALOR DEUDA A REF','FIRMA DOCUMENTOS','VALIDACION CONYUGE',
            'ESTADO CIVIL', 'CI CONYUGE ', 'NOMBRE CONYUGE',   'OBSERVACION EXCEPCION', 'DIRECCION NEG. CON FIRMA DOCUMENTOS', 'INGRESOS', 'GASTOS', 'VALOR ABONO MISMO DIA DEL CORTE', 'TELEFONOS', 'CAMPAÑA', 'GESTOR', 'DEBITO AUTOMÁTICO', 'ACTIVIDAD ECONOMICA');

        $cabecera_rotativo = array('Nro', 'FECHA SOLICITUD', 'MARCA', 'GESTOR',  'CORTE', 'CUENTA', 'CEDULA', 'NOMBRE', 'CODIGO DE CANCELACION ACTUAL', 'EDAD REAL', 'STS CANC SOLICITADO', 'SOLICITA CAMBIO FORMA DE PAGO A MINIMO', 'PRECANCELACION DIFERIDO', 'VALOR PAGO EXIGIBLE', 'VALOR ABONO', 'VALOR CREDITO', 'VALOR DEBITO', 'CUPO', 'TOTAL RIESGO DEUDA','DEBITO AUTOMATICO','TIPO DE CUENTA NORMAL/ESPECIAL','INGRESOS REALES','TIPO DE TRABAJO (FIJO / TEMPORAL / SIN TRABAJO'
        );


        if ($request->tarjeta=='DINERS CLUB') {
            $gestiones=gestiones::select('id','created_at','marca','cod_motivo','motivo_no_pago','empresa_externa','oficial_responsable','cod_encargado','tipo','digitos_cedula','valida_ci',
                'nombre_cliente','plazo','ciclo','consolidacion','observaciones_consolidacion','tipo_de_refinanciacion','ciudad','zona','valor_deuda_a_ref','firma_documentos',
                'validacion_conyugue',
                'estado_civil','ci_conyugue','nombre_conyugue',
                'observaciones_excepcion','direccion_neg_con_firma_documentos','ingresos_reales','gastos_reales',
                'valor_abono_mismo_dia_del_corte','telefonos','campana','gestor',
                'debito_automatico','actividad_economica')->whereIn('id', $request->gestion)
                ->where('marca',$request->tarjeta)
                ->where('id_formato','=',1)
                ->where('consolidado','=',0)->get();

            foreach ($gestiones as $gestion){
                $gestion['valor_deuda_a_ref']=number_format($gestion['valor_deuda_a_ref'], 2, ',', '');
                $gestion['ingresos_reales']=number_format($gestion['ingresos_reales'], 2, ',', '');
                $gestion['gastos_reales']=number_format($gestion['gastos_reales'], 2, ',', '');
            }
            $gestiones=$gestiones->toArray();

            if($request->descarga==null){
                gestiones::whereIn('id', $request->gestion)->update(['consolidado'=>1,'id_estado_gestion'=>2]);
            }


            $gestioneszip=$gestiones;

            array_unshift($gestiones,$cabecera_refinanciamiento);
            generarExcel($request->tarjeta,'REFINANCIAMIENTO',$gestiones);
            generarZip($gestioneszip);

            $zip=public_path('storage/refinanciamientos/consolidado/'.date('Y-m-d').'.zip');

            try
            {
                return response()->download($zip);
            }catch (\Exception $e) {
                return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
            }
        }

        if ($request->tarjeta=='VISA INTERDIN' && $request->formato=='rotativo') {
            $gestiones = gestiones::select('id','created_at','marca','gestor','ciclo','cuenta','digitos_cedula', 'nombre_cliente','sts_canc_actual','edad_real','sts_canc_solicitado','solicita_cambio_forma_de_pago_minimo','precancelacion_diferido','valor_pago_exigible','valor_abono','valor_credito','valor_debito',
                'cupo','total_riesgo_deuda','debito_automatico','tipo_cuenta','ingresos_reales','actividad_economica')->whereIn('id', $request->gestion)
                ->where('marca',$request->tarjeta)
                ->where('id_formato','=',2)
                ->where('consolidado','=',0)->get();
            foreach ($gestiones as $gestion){
                $gestion['valor_pago_exigible']=number_format($gestion['valor_pago_exigible'], 2, ',', '');
                $gestion['valor_abono']=number_format($gestion['valor_abono'], 2, ',', '');
                $gestion['valor_credito']=number_format($gestion['valor_credito'], 2, ',', '');
                $gestion['valor_debito']=number_format($gestion['valor_debito'], 2, ',', '');
                $gestion['total_riesgo_deuda']=number_format($gestion['total_riesgo_deuda'], 2, ',', '');
                $gestion['ingresos_reales']=number_format($gestion['ingresos_reales'], 2, ',', '');
            }
            $gestiones=$gestiones->toArray();

            if($request->descarga==null){
                gestiones::whereIn('id', $request->gestion)->update(['consolidado' => 1, 'id_estado_gestion' => 2]);
            }

            $gestioneszip=$gestiones;
            array_unshift($gestiones,$cabecera_rotativo);
            generarExcel($request->tarjeta,'ROTATIVO',$gestiones);
            generarZip($gestioneszip);
            $zip=public_path('storage/refinanciamientos/consolidado/'.date('Y-m-d').'.zip');

            try
            {
                return response()->download($zip);
            }catch (\Exception $e) {
                return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
            }

        }elseif ($request->tarjeta=='VISA INTERDIN' && $request->formato=='refinanciamiento'){
            $gestiones=gestiones::select('id','created_at','marca','cod_motivo','motivo_no_pago','empresa_externa','oficial_responsable','cod_encargado','tipo','digitos_cedula','valida_ci',
                'nombre_cliente','plazo','ciclo','consolidacion','observaciones_consolidacion','tipo_de_refinanciacion','ciudad','zona','valor_deuda_a_ref','firma_documentos',
                'validacion_conyugue',
                'estado_civil','ci_conyugue','nombre_conyugue',
                'observaciones_excepcion','direccion_neg_con_firma_documentos','ingresos_reales','gastos_reales',
                'valor_abono_mismo_dia_del_corte','telefonos','campana','gestor',
                'debito_automatico','actividad_economica')->whereIn('id', $request->gestion)
                ->where('marca',$request->tarjeta)
                ->where('id_formato','=',1)
                ->where('consolidado','=',0)->get();
            foreach ($gestiones as $gestion){
                $gestion['valor_deuda_a_ref']=number_format($gestion['valor_deuda_a_ref'], 2, ',', '');
                $gestion['ingresos_reales']=number_format($gestion['ingresos_reales'], 2, ',', '');
                $gestion['gastos_reales']=number_format($gestion['gastos_reales'], 2, ',', '');
            }
            $gestiones=$gestiones->toArray();

            if($request->descarga==null){
                gestiones::whereIn('id', $request->gestion)->update(['consolidado' => 1, 'id_estado_gestion' => 2]);
            }

            $gestioneszip=$gestiones;
            array_unshift($gestiones,$cabecera_refinanciamiento);
            generarExcel($request->tarjeta,'REFINANCIAMIENTO',$gestiones);
            generarZip($gestioneszip);
            $zip=public_path('storage/refinanciamientos/consolidado/'.date('Y-m-d').'.zip');

            try
            {
                return response()->download($zip);
            }catch (\Exception $e) {
                return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
            }
        }

        if($request->tarjeta=='DISCOVER' && $request->formato=='rotativo') {
            $gestiones = gestiones::select('id','created_at','marca','gestor','ciclo','cuenta','digitos_cedula', 'nombre_cliente','sts_canc_actual','edad_real','sts_canc_solicitado','solicita_cambio_forma_de_pago_minimo','precancelacion_diferido','valor_pago_exigible','valor_abono','valor_credito','valor_debito',
                'cupo','total_riesgo_deuda','debito_automatico','tipo_cuenta','ingresos_reales','actividad_economica')->whereIn('id', $request->gestion)
                ->where('marca',$request->tarjeta)
                ->where('id_formato','=',2)
                ->where('consolidado','=',0)->get();
            foreach ($gestiones as $gestion){
                $gestion['valor_pago_exigible']=number_format($gestion['valor_pago_exigible'], 2, ',', '');
                $gestion['valor_abono']=number_format($gestion['valor_abono'], 2, ',', '');
                $gestion['valor_credito']=number_format($gestion['valor_credito'], 2, ',', '');
                $gestion['valor_debito']=number_format($gestion['valor_debito'], 2, ',', '');
                $gestion['total_riesgo_deuda']=number_format($gestion['total_riesgo_deuda'], 2, ',', '');
                $gestion['ingresos_reales']=number_format($gestion['ingresos_reales'], 2, ',', '');
            }
            $gestiones=$gestiones->toArray();

            $gestioneszip=$gestiones;
            array_unshift($gestiones,$cabecera_rotativo);

            if($request->descarga==null){
                gestiones::whereIn('id', $request->gestion)->update(['consolidado' => 1, 'id_estado_gestion' => 2]);
            }

            generarExcel($request->tarjeta,'ROTATIVO',$gestiones);
            generarZip($gestioneszip);
            $zip=public_path('storage/refinanciamientos/consolidado/'.date('Y-m-d').'.zip');

            try
            {
                return response()->download($zip);
            }catch (\Exception $e) {
                return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
            }
        }elseif ($request->tarjeta=='DISCOVER' && $request->formato=='refinanciamiento'){
            $gestiones=gestiones::select('id','created_at','marca','cod_motivo','motivo_no_pago','empresa_externa','oficial_responsable','cod_encargado','tipo','digitos_cedula','valida_ci',
                'nombre_cliente','plazo','ciclo','consolidacion','observaciones_consolidacion','tipo_de_refinanciacion','ciudad','zona','valor_deuda_a_ref','firma_documentos',
                'validacion_conyugue',
                'estado_civil','ci_conyugue','nombre_conyugue',
                'observaciones_excepcion','direccion_neg_con_firma_documentos','ingresos_reales','gastos_reales',
                'valor_abono_mismo_dia_del_corte','telefonos','campana','gestor',
                'debito_automatico','actividad_economica')->whereIn('id', $request->gestion)
                ->where('marca',$request->tarjeta)
                ->where('id_formato','=',1)
                ->where('consolidado','=',0)->get();
            foreach ($gestiones as $gestion){
                $gestion['valor_deuda_a_ref']=number_format($gestion['valor_deuda_a_ref'], 2, ',', '');
                $gestion['ingresos_reales']=number_format($gestion['ingresos_reales'], 2, ',', '');
                $gestion['gastos_reales']=number_format($gestion['gastos_reales'], 2, ',', '');
            }
            $gestiones=$gestiones->toArray();

            if($request->descarga==null){
                gestiones::whereIn('id', $request->gestion)->update(['consolidado' => 1, 'id_estado_gestion' => 2]);
            }

            $gestioneszip=$gestiones;
            array_unshift($gestiones,$cabecera_refinanciamiento);
            generarExcel($request->tarjeta,'REFINANCIAMIENTO',$gestiones);
            generarZip($gestioneszip);
            $zip=public_path('storage/refinanciamientos/consolidado/'.date('Y-m-d').'.zip');

            try
            {
                return response()->download($zip);
            }catch (\Exception $e) {
                return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
            }
        }
    }

    public function descargarZip()
    {
        return response()->download(public_path('storage/refinanciamientos/consolidado/'.date('Y-m-d').'.zip'))->deleteFileAfterSend(true);
    }
}function guardarGestiones($formato,Array $proceso_d)
{
    $cabecera = array();

    if($formato=="rotativo"){
        $cabecera = array('Nro', 'MARCA', 'USUARIO SOLICITA', 'CORTE', 'CUENTA', 'CEDULA', 'NOMBRE', 'STS CANC ACTUAL', 'EDAD REAL', 'STS CANC SOLICITADO', 'SOLICITA CAMBIO FORMA DE PAGO A MINIMO', 'PRECANCELACION DIFERIDO', 'VALOR PAGO EXIGIBLE', 'VALOR ABONO', 'VALOR CREDITO', 'VALOR DEBITO', 'CUPO', 'TOTAL RIESGO DEUDA');
    }
    if($formato=="refinanciamiento"){
        $cabecera = array('Nro', 'FECHA SOLICITUD', 'MARCA', 'COD MOTIVO', 'MOTIVO DE NO PAGO', 'EMPRESA EXTERNA', 'OFICIAL RESPONSABLE', 'COD ENCARGADO', 'TIPO', 'DIGITOS CÉDULA', 'VALIDA CI', 'NOMBRE DEL CLIENTE', 'PLAZO', 'CICLO', 'CONSOLIDACION', 'OBSERVACIONES CONSOLIDACION', 'TIPO DE REFINANCIACION', 'CIUDAD', 'ZONA', 'ESTADO CIVIL', 'CI CONYUGE ', 'NOMBRE CONYUGE', 'VALIDACION CONYUGE', 'VALOR DEUDA A REF', 'FIRMA DOCUMENTOS', 'OBSERVACION EXCEPCION', 'VALOR ABONO MISMO DIA DEL CORTE', 'DIRECCION NEG. CON FIRMA DOCUMENTOS', 'TELEFONOS', 'GESTOR', 'CAMPAÑA', 'GRABACION', 'DEBITO AUTOMÁTICO', 'INGRESOS REALES', 'ACTIVIDAD ECONOMICA');
    }
    //dd($proceso_d);
    $gestiones = new gestiones();
    if ($proceso_d['marca']=='DINERS CLUB') {
        try{
            $gestiones->fecha_solicitud = $proceso_d['fecha_solicitud'];
            //$gestiones->fecha_solicitud = date('Y-m-d H:i:s');
            $gestiones->marca = $proceso_d['marca'];
            $gestiones->cod_motivo = $proceso_d['cod_motivo'];
            $gestiones->motivo_no_pago = $proceso_d['motivo_de_no_pago'];
            $gestiones->empresa_externa = $proceso_d['empresa_externa'];
            $gestiones->oficial_responsable = $proceso_d['oficial_responsable'];
            $gestiones->cod_encargado = $proceso_d['cod_encargado'];
            $gestiones->tipo = $proceso_d['tipo'];
            $gestiones->digitos_cedula = $proceso_d['digitos_cedula'];
            $gestiones->valida_ci = $proceso_d['valida_ci'];
            $gestiones->nombre_cliente = $proceso_d['nombre_del_cliente'];
            $gestiones->plazo = $proceso_d['plazo'];
            $gestiones->ciclo = $proceso_d['ciclo'];
            $gestiones->consolidacion = $proceso_d['consolidacion'];
            $gestiones->observaciones_consolidacion = $proceso_d['observaciones_consolidacion'];
            $gestiones->tipo_de_refinanciacion = $proceso_d['tipo_de_refinanciacion'];
            $gestiones->ciudad = $proceso_d['ciudad'];
            $gestiones->zona = $proceso_d['zona'];
            $gestiones->estado_civil = $proceso_d['estado_civil'];
            $gestiones->ci_conyugue = $proceso_d['ci_conyugue'];
            $gestiones->nombre_conyugue = $proceso_d['nombre_conyugue'];
            $gestiones->validacion_conyugue = $proceso_d['validacion_conyugue'];
            $gestiones->valor_deuda_a_ref = $proceso_d['valor_deuda_ref'];
            $gestiones->firma_documentos = $proceso_d['firma_documentos'];
            $gestiones->observaciones_excepcion = $proceso_d['observacion_excepcion'];
            $gestiones->valor_abono_mismo_dia_del_corte = $proceso_d['valor_abono_mismo_dia_del_corte'];
            $gestiones->direccion_neg_con_firma_documentos = $proceso_d['direccion_neg_con_firma_documentos'];
            $gestiones->telefonos = $proceso_d['telefonos_c'];
            $gestiones->gestor = $proceso_d['gestor'];
            $gestiones->campana = $proceso_d['campania'];
            $gestiones->grabacion = $proceso_d['grabacion'];
            $gestiones->debito_automatico = $proceso_d['debito_automatico'];
            $gestiones->ingresos_reales = $proceso_d['ingresos_reales'];
            $gestiones->gastos_reales = $proceso_d['gastos_reales'];
            $gestiones->actividad_economica = $proceso_d['actividad_economica'];
            $gestiones->fecha_gestion = date("Y-m-d H:m");
            $estado_gestion = estado_gestion::where('descripcion', 'pendiente')->first();
            $gestiones->id_carga=$proceso_d['id_carga'];
            $gestiones->consolidado=0;
            $gestiones->id_estado_gestion = $estado_gestion->id;
            $gestiones->id_negociacion_especial=negociacion_especial::where('descripcion','Sin negociacion especial')->first()->id;
            $gestiones->id_formato=$proceso_d['formato_consolidado'];
            $gestiones->saldo_intereses_negociacion_especial=$proceso_d['saldo_interes'];

            $gestiones->observacion_negociacion_especial=$proceso_d['observacion_negociacion_especial'];

            $gestiones->sts_canc_actual= $proceso_d['codigo_cancelacion'];
            $gestiones->sts_canc_solicitado=$proceso_d['codigo_de_cancelacion_solicitado'];
            $gestiones->valor_abono_mismo_dia_del_corte=$proceso_d['valor_abono_mismo_dia_del_corte'];

            $gestiones->ficha=$proceso_d['ficha'];
            $gestiones->motivo_ficha=$proceso_d['motivo'];
            $gestiones->recomendacion_ficha=$proceso_d['recomendacion'];
            $gestiones->rrecomendacion_ficha=$proceso_d['rrecomendacion'];
            $gestiones->mensaje=$proceso_d['mensaje'];

            $gestiones->save();
        }catch (Exception $e) {
            $mensaje='Ocurrió un error.';
            Log::info("Exception: " . $e->getMessage());

        }
    }
    else{
        try{
            //$gestiones->fecha_solicitud = date('Y-m-d H:i:s');
            $gestiones->fecha_solicitud = $proceso_d['fecha_solicitud'];
            $gestiones->marca = $proceso_d['marca'];
            $gestiones->cod_motivo = $proceso_d['cod_motivo'];
            $gestiones->motivo_no_pago = $proceso_d['motivo_de_no_pago'];
            $gestiones->empresa_externa = $proceso_d['empresa_externa'];
            $gestiones->oficial_responsable = $proceso_d['oficial_responsable'];
            $gestiones->cod_encargado = $proceso_d['cod_encargado'];
            $gestiones->tipo = $proceso_d['tipo'];
            $gestiones->digitos_cedula = $proceso_d['digitos_cedula'];
            $gestiones->valida_ci = $proceso_d['valida_ci'];
            $gestiones->nombre_cliente = $proceso_d['nombre_del_cliente'];
            $gestiones->plazo = $proceso_d['plazo'];
            $gestiones->ciclo = $proceso_d['ciclo'];
            $gestiones->consolidacion = $proceso_d['consolidacion'];
            $gestiones->observaciones_consolidacion = $proceso_d['observaciones_consolidacion'];
            $gestiones->tipo_de_refinanciacion = $proceso_d['tipo_de_refinanciacion'];
            $gestiones->ciudad = $proceso_d['ciudad'];
            $gestiones->zona = $proceso_d['zona'];
            $gestiones->estado_civil = $proceso_d['estado_civil'];
            $gestiones->ci_conyugue = $proceso_d['ci_conyugue'];
            $gestiones->nombre_conyugue = $proceso_d['nombre_conyugue'];
            $gestiones->validacion_conyugue = $proceso_d['validacion_conyugue'];
            $gestiones->valor_deuda_a_ref = $proceso_d['valor_deuda_ref'];
            $gestiones->firma_documentos = $proceso_d['firma_documentos'];
            $gestiones->observaciones_excepcion = $proceso_d['observacion_excepcion'];
            $gestiones->valor_abono_mismo_dia_del_corte = $proceso_d['valor_abono_mismo_dia_del_corte'];
            $gestiones->direccion_neg_con_firma_documentos = $proceso_d['direccion_neg_con_firma_documentos'];
            $gestiones->telefonos = $proceso_d['telefonos_c'];
            $gestiones->gestor = $proceso_d['gestor'];
            $gestiones->campana = $proceso_d['campania'];
            $gestiones->grabacion = (isset($proceso_d['grabacion'])) ? $proceso_d['grabacion'] : '';
            $gestiones->debito_automatico = $proceso_d['debito_automatico'];
            $gestiones->ingresos_reales = $proceso_d['ingresos_reales'];
            $gestiones->gastos_reales = $proceso_d['gastos_reales'];
            $gestiones->actividad_economica = $proceso_d['actividad_economica'];
            $gestiones->fecha_gestion = date("Y-m-d H:m");
            $gestiones->id_carga=$proceso_d['id_carga'];
            $gestiones->consolidado=0;
            $estado_gestion = estado_gestion::where('descripcion', 'pendiente')->first();
            $gestiones->id_estado_gestion = $estado_gestion->id;
            $gestiones->id_negociacion_especial=$proceso_d['id_negociacion_especial'];
            //Para Rotativo
            $gestiones->cuenta ='';
            $gestiones->sts_canc_actual= $proceso_d['codigo_cancelacion'];
            $gestiones->edad_real= $proceso_d['edad_cartera'];
            $gestiones->sts_canc_solicitado=$proceso_d['codigo_de_cancelacion_solicitado'];
            $gestiones->solicita_cambio_forma_de_pago_minimo='';
            $gestiones->precancelacion_diferido='SI';
            $gestiones->valor_pago_exigible=(float)str_replace (',','.',$proceso_d['valor_pago_exigible'] );
            $gestiones->valor_abono=$proceso_d['valor_abono'];
            $gestiones->valor_credito=$proceso_d['valor_credito'];
            $gestiones->valor_debito=$proceso_d['valor_debito'];
            $gestiones->cupo='';
            $gestiones->total_riesgo_deuda=(float)str_replace (',','.',$proceso_d['total_riesgo_deuda']);
            $gestiones->tipo_cuenta=$proceso_d['tipo_cuenta'];
            //$gestiones->total_riesgo_deuda=$gestiones->valor_pago_exigible;
            $gestiones->id_formato=$proceso_d['formato_consolidado'];
            $gestiones->saldo_intereses_negociacion_especial=$proceso_d['saldo_interes'];

            $gestiones->observacion_negociacion_especial=$proceso_d['observacion_negociacion_especial'];
            $gestiones->valor_abono_mismo_dia_del_corte=$proceso_d['valor_abono_mismo_dia_del_corte'];

            $gestiones->ficha=$proceso_d['ficha'];
            $gestiones->motivo_ficha=$proceso_d['motivo'];
            $gestiones->recomendacion_ficha=$proceso_d['recomendacion'];
            $gestiones->rrecomendacion_ficha=$proceso_d['rrecomendacion'];
            $gestiones->mensaje=$proceso_d['mensaje'];

            $gestiones->save();
        }catch (Exception $e) {
            $mensaje='Ocurrió un error.';
            Log::info("Exception: " . $e->getMessage());

        }
        //Guarda el id de proceso como estado 0
        //$carga = carga::where('id',$proceso_d['id_carga'])->update(['estado' => 0]);

    }
    $id_gestion=$gestiones->id;
    return compact('cabecera','id_gestion');

}



function generarExcel($tarjeta,$formato,Array $proceso){
    chdir('storage/refinanciamientos/');
    shell_exec('rm -rf consolidado/');
    shell_exec('mkdir consolidado');
    //usleep(500000);
    try {
        Excel::create($formato . ' ' . $tarjeta . ' ' . date("Y-m-d"), function ($excel) use ($proceso) {
            $excel->sheet('Sheetname', function ($sheet) use ($proceso) {

                $sheet->fromArray($proceso, null, 'A1', false, false);
                $sheet->row(1, function ($row) {
                    $row->setBackground('#95b3d7');
                });
            });
        })->store('xlsx', public_path('storage/refinanciamientos/consolidado/'));
        //download('xlsx');
    }catch(\Exception $e){
        return response()->json('Ocurrio un error: '.$e->getMessage(), 500);
    }
}

function generarZip(Array $gestiones)
{
    /*chdir('storage/refinanciamientos/');
    shell_exec('rm -rf consolidado/');
    shell_exec('mkdir consolidado');*/
    foreach ($gestiones as $k){
    $archivos=archivos::select('ruta','nombre')->where('id_gestion',$k['id'])->get();
    //dd(public_path($archivos[0]->ruta.'/*'));
    //$files = glob(public_path($archivos[0]->ruta.'/*'));
    //Zipper::make('storage/'.$id.'.zip')->add($files)->close();
        if (count($archivos)>0){
            //SHELL
            shell_exec('mkdir consolidado/'.$k['id'].'/');
            shell_exec('cp -r '.$k['id'].'/ consolidado/'.$k['id'].'/');
            $gestion=gestiones::where('id',$k['id'])->first();
            $nombre='consolidado/'.$k['id'].'/'.$k['id'];
            $renombre='consolidado/'.$k['id'].'/'.str_replace(" ", "_", $gestion->nombre_cliente);
            $cliente=str_replace(" ", "_", $gestion->nombre_cliente);
            //echo 'mv tmp-'.$k['id'].'/'.$k['id'].' '.$renombre;
            shell_exec('mv '.$nombre.' '.$renombre);
            //shell_exec('cd ');
            //chdir('consolidado/'.$k['id'].'/');

            //FIN SHELL
            //return $archivos[0]->ruta.'/*';

        }
    };
    shell_exec('zip -r consolidado/'.date('Y-m-d').'.zip consolidado');

    //return response()->download(public_path('storage/refinanciamientos/tmp/test.zip'))->deleteFileAfterSend(true);
}

function guardarFicheros($archivos,$id_gestion,$marca, Request $request)
{
    $mensaje = '';//Declaramos una variable mensaje que almacenara el resultado de las operaciones.
    $i=0;
    //dd($request->file('archivo0'));
    foreach ($archivos as $key) //Iteramos el arreglo de archivos
    {
        if ($key['error'] == UPLOAD_ERR_OK)//Si el archivo se paso correctamente Continuamos
        {
            $destino = public_path() . '/storage/refinanciamientos/'.$id_gestion;
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }
            $nombrearchivo = $id_gestion . '_' . $key['name'];
            $request->file('archivo' . $i)->move($destino, $nombrearchivo);

            $archivos=new archivos();
            $archivos->nombre = $nombrearchivo;
            $archivos->ruta = '/storage/refinanciamientos/'.$id_gestion;
            $archivos->tarjeta = $marca;
            $archivos->fecha_carga = date("Y-m-d H:m:s");
            $archivos->id_gestion = $id_gestion;
            $archivos->save();
        }
        if ($key['error'] == '') //Si no existio ningun error, retornamos un mensaje por cada archivo subido
        {
            $mensaje .= '-> Archivo <b>' . $key['name'] . '</b> Subido correctamente. <br>';
        }
        if ($key['error'] != '')//Si existio algún error retornamos un el error por cada archivo.
        {
            $mensaje .= '-> No se pudo subir el archivo <b>' . $key['name'] . '</b> debido al siguiente Error: n' . $key['error'];
        }
        $i++;
    }
    return $mensaje;
}//puede hacer el pago por deposito o transferencia bancaria