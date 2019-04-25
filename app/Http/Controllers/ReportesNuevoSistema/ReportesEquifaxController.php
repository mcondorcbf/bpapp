<?php

namespace App\Http\Controllers\ReportesNuevoSistema;

use App\reportesNuevoSistema\tbl_accounts;
use App\reportesNuevoSistema\tbl_brands;
use App\reportesNuevoSistema\tbl_campaigns;
use App\reportesNuevoSistema\tbl_demarches;
use App\reportesNuevoSistema\tbl_products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ReportesEquifaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reportes()
    {
        try{
            //$marcas=tbl_brands::get();
            $marcas=DB::connection('cobefec3')->select("SELECT id,name FROM cobefec3.brands where id=6 and deleted_at is null and enabled=1;");
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
        return view('reporteNuevoSistema/equifax/index', compact('marcas'));
    }
    //rConsolidadoPagoSemanalMensual
    public function rConsolidadoPagoSemanalMensual(Request $request)
    {
        set_time_limit(0);

        $query="select substr(u.email,1,locate('@',u.email)-1) Gestor, a.target_document Cedula, a.data ->> '$.cliente' Nombre, date(d.created_at) Fecha_Gestion,time(d.created_at) Hora_Gestion, d.action Accion, d.description Observacion,
substr(d.description,1,locate(' ',d.description)-1) factura,
substr(d.description, locate(' ',d.description,locate('VALOR',d.description))+1,locate(' ',d.description,locate(' ',d.description,locate('VALOR',d.description))+3)-(locate(' ',d.description,locate('VALOR',d.description))+1)) valor,
if(locate('/', d.description)=0,'',substr(d.description,locate('/', d.description)-2,10)) fecha, d.sent_status Estado, d.id id_demarch
from cobefec3.brands b, cobefec3.products p, cobefec3.campaigns c, cobefec3.accounts a, cobefec3.agents ag, cobefec3.users u, cobefec3.demarches d
where b.id=p.brand_id and p.id=c.product_id and c.id=a.campaign_id and ag.id=d.agent_id and u.id=ag.user_id
and d.account_id=a.id and d.action='PAGOS COBEFEC (para supervisor)'
and a.campaign_id=".$request->id_campana." order by 4,5
;
";
        //demarches para marcar las gestiones enviadas
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $reportes = json_decode(json_encode($sql), true);
        $i=0;
        foreach($reportes as $reporte) {
            $reportes[$i]['valor']=round($reporte['valor'],2);
            if ($reportes[$i]['Estado']==1) {
                $reportes[$i]['Estado'] = "Enviado";
            }else{
                $reportes[$i]['Estado'] = "No enviado";
            }
            if ($request->envio==1){
                $update="UPDATE demarches SET sent_status=0 WHERE id=".$reporte['id_demarch'].";";
                try {
                    $sql=DB::connection('cobefec3')->statement($update);
                }
                catch(\Exception $e) {
                    return $e->getMessage();
                }
            }
            unset($reportes[$i]['id_demarch']);
            $i++;
        }

        $reportes2 = Array();
        $i=0;
        foreach($reportes as $reporte)
        {
            if ($reporte['Estado'] == "No enviado") {
                $reportes2[$i]['RUC'] = $reporte['Cedula'];
                $reportes2[$i]['CLIENTE'] = $reporte['Nombre'];
                $reportes2[$i]['STATUS'] = $reporte['Accion'];
                $reportes2[$i]['OBSERVACION'] = $reporte['Observacion'];
                if (strlen(trim($reporte['factura'])) < 13) {
                    $factura = $reporte['factura'];
                    //echo "+ ".$factura."<br>";
                    if (preg_match("/FE-/i", $factura) || preg_match("/E-/i", $factura) || preg_match("/-/i", $factura)) {
                        //echo "encontre FE- ";
                        $factura = str_replace("F", "", $factura);
                        $factura = str_replace("f", "", $factura);
                        $factura = str_replace("E", "", $factura);
                        $factura = str_replace("e", "", $factura);
                        $factura = str_replace("-", "", $factura);
                    }
                    //echo strlen($factura)."<br>";
                    $factura=trim($factura);
                    if (strlen($factura) < 10) {
                        $ceros = 10 - strlen($factura);
                        for ($j = 1; $j <= $ceros; $j++) {
                            $factura = '0' . $factura;
                        }
                    }
                    //echo "factura con ceros ".$factura."<br>";
                    $factura = "FE-" . $factura;
                    //echo "factura final ".$factura."<br>";
                    $reporte['factura'] = $factura;
                    $reportes[$i]['factura'] = $factura;
                }elseif (strlen(trim($reporte['factura'])) > 13){
                    $factura = $reporte['factura'];

                    //echo "+ ".$factura."<br>";
                    if (preg_match("/FE-/i", $factura) || preg_match("/E-/i", $factura) || preg_match("/-/i", $factura)) {
                        //echo "encontre FE- ";
                        $factura = str_replace("F", "", $factura);
                        $factura = str_replace("f", "", $factura);
                        $factura = str_replace("E", "", $factura);
                        $factura = str_replace("e", "", $factura);
                        $factura = str_replace("-", "", $factura);
                    }
                    //echo strlen($factura)."<br>";
                    $factura=trim($factura);
                    if (strlen($factura) > 10) {
                        $factura=intval($factura);
                        $ceros = 10 - strlen($factura);
                        for ($j = 1; $j <= $ceros; $j++) {
                            $factura = '0' . $factura;
                        }
                        //echo "factura con ceros ".$factura."<br>";
                        $factura = "FE-" . $factura;
                        //echo "factura final ".$factura."<br>";
                        $reporte['factura'] = $factura;
                        $reportes[$i]['factura'] = $factura;
                    }
                }
                $reportes2[$i]['FACTURA'] = $reporte['factura'];
                $reportes2[$i]['VALOR'] = $reporte['valor'];
                $reportes2[$i]['FECHA'] = $reporte['fecha'];
                $i++;
            }
        }
        $query="select name from cobefec3.campaigns where id=".$request->id_campana.";";
        try {
            $sql=DB::connection('cobefec3')->select($query);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }

        $campana=$sql[0]->name;

        try {
            \Excel::create('CONSOLIDADO DE PAGO SEMANAL Y MENSUAL '.$campana.' COBEFEC '.date('d-m-Y'), function($excel) use (&$reportes,&$reportes2){
                $excel->sheet('BASE DEPURADA', function($sheet) use($reportes) {
                    $sheet->fromArray($reportes,null,'A1',true);
                });
                $excel->sheet('FORMATO DE ENVIO A LA MARCA', function($sheet) use($reportes2) {
                    $sheet->fromArray($reportes2, null, 'A5', null, false);
                    $sheet->row(1, array("","","Consolidado de Pagos","","","",""));
                    setlocale(LC_TIME,"es_ES.UTF-8");
                    $sheet->row(2, array("","","Hasta : ".strftime("%d de %B del %Y"),"","","",""));
                    $sheet->row(4, array("RUC","CLIENTE","STATUS","OBSERVACION","FACTURA","VALOR","FECHA"));
                    $sheet->row(4, function($row) {

                        // call cell manipulation methods
                        $row->setBackground('#FFFF00');

                    });
                    $sheet->setBorder("A4:G4", 'thin');
                });
            })->export('xlsx');
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}