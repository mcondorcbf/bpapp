<?php

namespace App\Http\Controllers;

use App\tbl_carga_mdl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\tbl_formatos as formatos;
use App\tbl_id_carga as id_carga;
use App\tbl_carga_historico as carga_historico;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PHPExcel_IOFactory;
use App\tbl_carga_mdl as carga;
use Config;
use DB;
use Carbon\Carbon;
use PhpParser\Node\Stmt\DeclareDeclare;
use Illuminate\Support\Facades\Validator;
use Response;

class CargaDatosController extends Controller
{

    protected $fecha_act;
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = \Auth::user();
        $this->fecha_act=Carbon::now(-5);
    }

    public function index(){
        return view('cargaDatos.index');
    }
    public function import()
    {
        Excel::load('books.csv', function($reader) {

            foreach ($reader->get() as $book) {
                Book::create([
                    'name' => $book->title,
                    'author' =>$book->author,
                    'year' =>$book->publication_year
                ]);
            }
        });
        return Book::all();
    }
    public function importExport()
    {
        return view('importExport');
    }
    public function downloadExcel($type)
    {
        $data = formatos::get()->toArray();
        return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
    public function leerConvertirExcel(Request $request)
    {
        $path = Input::file('import_file')->getRealPath();
        $inputFileType = PHPExcel_IOFactory::identify(file('import_file')->name);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);

       /* // Creamos un objeto PHPExcel
        $objPHPExcel = new PHPExcel();

        dd($objPHPExcel);
        // Leemos un archivo Excel 2007
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');

        $objPHPExcel = $objReader->load("Archivo.xlsx");

        // Indicamos que se pare en la hoja uno del libro
        $objPHPExcel->setActiveSheetIndex(0);
        //Escribimos en la hoja en la celda B1
        $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Hola mundo');
        // Color rojo al texto
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
        // Texto alineado a la derecha
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        // Damos un borde a la celda
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        //Guardamos el archivo en formato Excel 2007
        //Si queremos trabajar con Excel 2003, basta cambiar el 'Excel2007' por 'Excel5' y el nombre del archivo de salida cambiar su formato por '.xls'
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        return 'Archivo_salida';
        $objWriter->save("Archivo_salida.xlsx");*/
    }

    public function importExcel(Request $request)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 5600);



        $file=$request->file('import_file');
        $path = $request->file('import_file')->getRealPath();

        $objReader=PHPExcel_IOFactory::createReaderForFile($path);
        $worksheetData=$objReader->listWorksheetInfo($path);
        echo '<h3>Worksheet Information</h3>';
        echo '<ol>';
        foreach ($worksheetData as $worksheet) {

            echo '<li>', $worksheet['worksheetName'], '<br />';
            echo 'Rows: ', $worksheet['totalRows'],
            ' Columns: ', $worksheet['totalColumns'], '<br />';
            echo 'Cell Range: A1: ',
            $worksheet['lastColumnLetter'], $worksheet['totalRows'];
            echo '</li>';
        }
        echo '</ol>';

        $dir = public_path() . "/storage/csv/";

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        //echo $dir;
        //$archivo=Input::file('import_file')->getClientOriginalName();
        $archivo="carga.csv";

        try{
        Input::file('import_file')->move($dir, $archivo);
        }catch (Exception $e) {
            return $e->getMessage();

        }

        $csv = $dir.$archivo;

        echo $csv;

        return $csv;
        return view('cargaDatos.index', compact('archivo'));
    }

    public function procesarExcel(Request $request)
    {
        ini_set('memory_limit', '700M');
        ini_set('max_execution_time', 3600);
        ini_set('auto_detect_line_endings', true);

        $dir = public_path() . "/storage/csv/";

        $archivo = "carga.csv";

        $csv = $dir.$archivo;


            sleep(1);



        echo $csv;

        if ($request->tipo=='campo'){
            $id_carga=id_carga::where('tipo','campo')->where('estado',1)->orderBy('id_carga','desc')->get();

            //DELETE FROM tbl_carga WHERE id_carga=(SELECT id_carga FROM tbl_id_carga where tipo='campo' and estado=1 ORDER BY id_carga desc limit 1);

            if(count($id_carga)>0){
                foreach ($id_carga as $k){
                    carga::where('id_carga',$k->id_carga)->delete();
                }
            }

            //UPDATE tbl_id_carga set estado=0 where tipo='campo' and estado=1;
            $id_carga = id_carga::where('estado',1)->where('tipo','campo')->update(['estado' => 0]);

            //INSERT INTO tbl_id_carga (fecha,estado,tipo) VALUES ((select now()), '1', 'campo');
            $id_carga= new id_carga();
            $id_carga->fecha=date('Y-m-d H:i:s');
            $id_carga->estado=1;
            $id_carga->tipo='campo';
            $id_carga->save();
        }
        elseif ($request->tipo='telefonia'){
            //UPDATE tbl_id_carga set estado=0 where tipo='telefonia' and estado=1;
            $id_carga = id_carga::where('estado',1)->where('tipo','telefonia')->first();
            if(count($id_carga)>0){
                $id_carga->estado = 0;
                $id_carga->save();
            }

            //INSERT INTO tbl_id_carga (fecha,estado,tipo) VALUES ((select now()), '1', 'campo');
            $id_carga= new id_carga();
            $id_carga->fecha=date('Y-m-d h:m:s');
            $id_carga->estado=1;
            $id_carga->tipo='telefonia';
            $id_carga->save();
        }

        $this->linkid = mysqli_init();
        mysqli_options($this->linkid, MYSQLI_OPT_LOCAL_INFILE, true);
        //servidor 21.32
        mysqli_real_connect($this->linkid, "172.16.21.35", "root", "Cobefec*123", "BDDCBFUIOBPAPP01");
        //servidor local
        //mysqli_real_connect($this->linkid, "localhost", "root", "C0b3f3c-", "bddcbfuiobpapp01");
        // ejecutar carga de datos
        ///var/www/html/bpapp/public/storage/csv/carga.csv

        $csv= str_replace("\\", "/", $csv);
        echo $csv;
        try{
        $load_data = <<<load
        LOAD DATA INFILE '$csv'
        INTO TABLE tbl_carga
        FIELDS TERMINATED BY ';' ENCLOSED BY ''
        LINES TERMINATED BY '\n'
        IGNORE 1 ROWS
        (CEDULA,NOMSOC,EMPRESA_SOC,DIRECCION,P1,T1,P2,T2,P3,T3,NOMBRE_CIUDAD,ZONA,EMAIL,STOTOT,VAXFAC,TRIESGO,CICLOF,EDCART,ACTUALES_ORIG,D30_ORIG,D60_ORIG,D90_ORIG,DMAS90_ORIG,FECHACOMPROMISO,
        FECHALLAM,DESCRIPCION,OBSERVACION,MOTIVO_1,INTERES_TOTAL,PROM_PAG,DEBITO_AUT,NOMESTAB,SIMULACION_DIF_sum,N_DIF,DEBITO,CREDITO,PAGO,CODIGO,CUOTAS_PTES,CUOTA_REF_VIG_PENDIENTE,
        VALOR_PEN_REF_VIG,IXF,SCORE_DINERS,PAGO_REAL,REESTRUCT_VIGENTE,DES_ESPECIALIDAD,CODRET,BOLNAC,EMPRESA,CAMPANA_CON_ECE,STOTOT_VISA,VAXFAC_VISA,TRIESGO_VISA,CICLOF_VISA,EDCART_VISA,
        ACTUALES_ORIG_VISA,D30_ORIG_VISA,D60_ORIG_VISA,D90_ORIG_VISA,DMAS90_ORIG_VISA,VAPAMI_VISA,FECHACOMPROMISO_VISA,FECHALLAM_VISA,DESCRIPCION_VISA,OBSERVACION_VISA,MOTIVO_1_VISA,INTERES_TOTAL_VISA,
        PROM_PAG_VISA,DEBITO_AUT_VISA,NOMESTAB_VISA,SIMULACION_DIF_sum_VISA,N_DIF_VISA,DEBITO_VISA,CREDITO_VISA,PAGO_VISA,CODIGO_VISA,CUOTAS_PTES_VISA,CUOTA_REF_VIG_PENDIENTE_VISA,VALOR_PEN_REF_VIG_VISA,
        IXF_VISA,SCORE_DINERS_VISA,PAGO_REAL_VISA,REESTRUCT_VIGENTE_VISA,DES_ESPECIALIDAD_VISA,CODRET_VISA,BOLNAC_VISA,EMPRESA_VISA,CAMPANA_CON_ECE_VISA,STOTOT_DISCOVER,VAXFAC_DISCOVER,TRIESGO_DISCOVER,
        CICLOF_DISCOVER,EDCART_DISCOVER,ACTUALES_ORIG_DISCOVER,D30_ORIG_DISCOVER,D60_ORIG_DISCOVER,D90_ORIG_DISCOVER,DMAS90_ORIG_DISCOVER,VAPAMI_DISCOVER,FECHACOMPROMISO_DISCOVER,FECHALLAM_DISCOVER,
        DESCRIPCION_DISCOVER,OBSERVACION_DISCOVER,MOTIVO_1_DISCOVER,INTERES_TOTAL_DISCOVER,PROM_PAG_DISCOVER,DEBITO_AUT_DISCOVER,NOMESTAB_DISCOVER,SIMULACION_DIF_sum_DISCOVER,N_DIF_DISCOVER,
        DEBITO_DISCOVER,CREDITO_DISCOVER,PAGO_DISCOVER,CODIGO_DISCOVER,CUOTAS_PTES_DISCOVER,CUOTA_REF_VIG_PENDIENTE_DISCOVER,VALOR_PEN_REF_VIG_DISCOVER,IXF_DISCOVER,SCORE_DINERS_DISCOVER,
        PAGO_REAL_DISCOVER,REESTRUCT_VIGENTE_DISCOVER,DES_ESPECIALIDAD_DISCOVER,CODRET_DISCOVER,BOLNAC_DISCOVER,EMPRESA_DISCOVER,CAMPANA_CON_ECE_DISCOVER,TOTAL_CUOTAS_REF,TOTAL_CUOTAS_REF_VI,
        TOTAL_CUOTAS_REF_DI)
        set id_carga = (SELECT id_carga FROM tbl_id_carga ORDER BY id_carga desc LIMIT 1), estado = 1;
load;
        mysqli_query($this->linkid, $load_data);
        }catch (Exception $e) {
            $mensaje='Ocurrió un error.';
            Log::info("Exception: " . $e->getMessage());

        }
        // ejecutar carga de datos
        $load_data='';
        try{
        $load_data = <<<load
        LOAD DATA INFILE '$csv'
        INTO TABLE tbl_carga_historico
        FIELDS TERMINATED BY ';' ENCLOSED BY ''
        LINES TERMINATED BY '\n'
        IGNORE 1 ROWS
        (CEDULA,NOMSOC,EMPRESA_SOC,DIRECCION,P1,T1,P2,T2,P3,T3,NOMBRE_CIUDAD,ZONA,EMAIL,STOTOT,VAXFAC,TRIESGO,CICLOF,EDCART,ACTUALES_ORIG,D30_ORIG,D60_ORIG,D90_ORIG,DMAS90_ORIG,FECHACOMPROMISO,
        FECHALLAM,DESCRIPCION,OBSERVACION,MOTIVO_1,INTERES_TOTAL,PROM_PAG,DEBITO_AUT,NOMESTAB,SIMULACION_DIF_sum,N_DIF,DEBITO,CREDITO,PAGO,CODIGO,CUOTAS_PTES,CUOTA_REF_VIG_PENDIENTE,
        VALOR_PEN_REF_VIG,IXF,SCORE_DINERS,PAGO_REAL,REESTRUCT_VIGENTE,DES_ESPECIALIDAD,CODRET,BOLNAC,EMPRESA,CAMPANA_CON_ECE,STOTOT_VISA,VAXFAC_VISA,TRIESGO_VISA,CICLOF_VISA,EDCART_VISA,
        ACTUALES_ORIG_VISA,D30_ORIG_VISA,D60_ORIG_VISA,D90_ORIG_VISA,DMAS90_ORIG_VISA,VAPAMI_VISA,FECHACOMPROMISO_VISA,FECHALLAM_VISA,DESCRIPCION_VISA,OBSERVACION_VISA,MOTIVO_1_VISA,INTERES_TOTAL_VISA,
        PROM_PAG_VISA,DEBITO_AUT_VISA,NOMESTAB_VISA,SIMULACION_DIF_sum_VISA,N_DIF_VISA,DEBITO_VISA,CREDITO_VISA,PAGO_VISA,CODIGO_VISA,CUOTAS_PTES_VISA,CUOTA_REF_VIG_PENDIENTE_VISA,VALOR_PEN_REF_VIG_VISA,
        IXF_VISA,SCORE_DINERS_VISA,PAGO_REAL_VISA,REESTRUCT_VIGENTE_VISA,DES_ESPECIALIDAD_VISA,CODRET_VISA,BOLNAC_VISA,EMPRESA_VISA,CAMPANA_CON_ECE_VISA,STOTOT_DISCOVER,VAXFAC_DISCOVER,TRIESGO_DISCOVER,
        CICLOF_DISCOVER,EDCART_DISCOVER,ACTUALES_ORIG_DISCOVER,D30_ORIG_DISCOVER,D60_ORIG_DISCOVER,D90_ORIG_DISCOVER,DMAS90_ORIG_DISCOVER,VAPAMI_DISCOVER,FECHACOMPROMISO_DISCOVER,FECHALLAM_DISCOVER,
        DESCRIPCION_DISCOVER,OBSERVACION_DISCOVER,MOTIVO_1_DISCOVER,INTERES_TOTAL_DISCOVER,PROM_PAG_DISCOVER,DEBITO_AUT_DISCOVER,NOMESTAB_DISCOVER,SIMULACION_DIF_sum_DISCOVER,N_DIF_DISCOVER,
        DEBITO_DISCOVER,CREDITO_DISCOVER,PAGO_DISCOVER,CODIGO_DISCOVER,CUOTAS_PTES_DISCOVER,CUOTA_REF_VIG_PENDIENTE_DISCOVER,VALOR_PEN_REF_VIG_DISCOVER,IXF_DISCOVER,SCORE_DINERS_DISCOVER,
        PAGO_REAL_DISCOVER,REESTRUCT_VIGENTE_DISCOVER,DES_ESPECIALIDAD_DISCOVER,CODRET_DISCOVER,BOLNAC_DISCOVER,EMPRESA_DISCOVER,CAMPANA_CON_ECE_DISCOVER,TOTAL_CUOTAS_REF,TOTAL_CUOTAS_REF_VI,
        TOTAL_CUOTAS_REF_DI)
        set id_carga = (SELECT id_carga FROM tbl_id_carga ORDER BY id_carga desc LIMIT 1), estado = 1;
load;
        mysqli_query($this->linkid, $load_data);

        }catch (Exception $e) {
            $mensaje='Ocurrió un error.';
            Log::info("Exception: " . $e->getMessage());

        }
        return $id_carga;

/*
        $query = sprintf("LOAD DATA INFILE '%s'
        INTO TABLE tbl_carga
        FIELDS TERMINATED BY ';' ENCLOSED BY ''
        LINES TERMINATED BY '\n'
        IGNORE 1 ROWS
        (CEDULA,NOMSOC,EMPRESA_SOC,DIRECCION,P1,T1,P2,T2,P3,T3,NOMBRE_CIUDAD,ZONA,EMAIL,STOTOT,VAXFAC,TRIESGO,CICLOF,EDCART,ACTUALES_ORIG,D30_ORIG,D60_ORIG,D90_ORIG,DMAS90_ORIG,FECHACOMPROMISO,
        FECHALLAM,DESCRIPCION,OBSERVACION,MOTIVO_1,INTERES_TOTAL,PROM_PAG,DEBITO_AUT,NOMESTAB,SIMULACION_DIF_sum,N_DIF,DEBITO,CREDITO,PAGO,CODIGO,CUOTAS_PTES,CUOTA_REF_VIG_PENDIENTE,
        VALOR_PEN_REF_VIG,IXF,SCORE_DINERS,PAGO_REAL,REESTRUCT_VIGENTE,DES_ESPECIALIDAD,CODRET,BOLNAC,EMPRESA,CAMPANA_CON_ECE,STOTOT_VISA,VAXFAC_VISA,TRIESGO_VISA,CICLOF_VISA,EDCART_VISA,
        ACTUALES_ORIG_VISA,D30_ORIG_VISA,D60_ORIG_VISA,D90_ORIG_VISA,DMAS90_ORIG_VISA,VAPAMI_VISA,FECHACOMPROMISO_VISA,FECHALLAM_VISA,DESCRIPCION_VISA,OBSERVACION_VISA,MOTIVO_1_VISA,INTERES_TOTAL_VISA,
        PROM_PAG_VISA,DEBITO_AUT_VISA,NOMESTAB_VISA,SIMULACION_DIF_sum_VISA,N_DIF_VISA,DEBITO_VISA,CREDITO_VISA,PAGO_VISA,CODIGO_VISA,CUOTAS_PTES_VISA,CUOTA_REF_VIG_PENDIENTE_VISA,VALOR_PEN_REF_VIG_VISA,
        IXF_VISA,SCORE_DINERS_VISA,PAGO_REAL_VISA,REESTRUCT_VIGENTE_VISA,DES_ESPECIALIDAD_VISA,CODRET_VISA,BOLNAC_VISA,EMPRESA_VISA,CAMPANA_CON_ECE_VISA,STOTOT_DISCOVER,VAXFAC_DISCOVER,TRIESGO_DISCOVER,
        CICLOF_DISCOVER,EDCART_DISCOVER,ACTUALES_ORIG_DISCOVER,D30_ORIG_DISCOVER,D60_ORIG_DISCOVER,D90_ORIG_DISCOVER,DMAS90_ORIG_DISCOVER,VAPAMI_DISCOVER,FECHACOMPROMISO_DISCOVER,FECHALLAM_DISCOVER,
        DESCRIPCION_DISCOVER,OBSERVACION_DISCOVER,MOTIVO_1_DISCOVER,INTERES_TOTAL_DISCOVER,PROM_PAG_DISCOVER,DEBITO_AUT_DISCOVER,NOMESTAB_DISCOVER,SIMULACION_DIF_sum_DISCOVER,N_DIF_DISCOVER,
        DEBITO_DISCOVER,CREDITO_DISCOVER,PAGO_DISCOVER,CODIGO_DISCOVER,CUOTAS_PTES_DISCOVER,CUOTA_REF_VIG_PENDIENTE_DISCOVER,VALOR_PEN_REF_VIG_DISCOVER,IXF_DISCOVER,SCORE_DINERS_DISCOVER,
        PAGO_REAL_DISCOVER,REESTRUCT_VIGENTE_DISCOVER,DES_ESPECIALIDAD_DISCOVER,CODRET_DISCOVER,BOLNAC_DISCOVER,EMPRESA_DISCOVER,CAMPANA_CON_ECE_DISCOVER,TOTAL_CUOTAS_REF,TOTAL_CUOTAS_REF_VI,
        TOTAL_CUOTAS_REF_DI)
        set id_carga = (SELECT id_carga FROM tbl_id_carga ORDER BY id_carga desc LIMIT 1), estado = 1;", addslashes($csv));

        DB::connection()->getpdo()->exec($query);

        $query2 = sprintf("LOAD DATA INFILE '%s'
        INTO TABLE tbl_carga_historico
        FIELDS TERMINATED BY ';' ENCLOSED BY ''
        LINES TERMINATED BY '\n'
        IGNORE 1 ROWS
        (CEDULA,NOMSOC,EMPRESA_SOC,DIRECCION,P1,T1,P2,T2,P3,T3,NOMBRE_CIUDAD,ZONA,EMAIL,STOTOT,VAXFAC,TRIESGO,CICLOF,EDCART,ACTUALES_ORIG,D30_ORIG,D60_ORIG,D90_ORIG,DMAS90_ORIG,FECHACOMPROMISO,
        FECHALLAM,DESCRIPCION,OBSERVACION,MOTIVO_1,INTERES_TOTAL,PROM_PAG,DEBITO_AUT,NOMESTAB,SIMULACION_DIF_sum,N_DIF,DEBITO,CREDITO,PAGO,CODIGO,CUOTAS_PTES,CUOTA_REF_VIG_PENDIENTE,
        VALOR_PEN_REF_VIG,IXF,SCORE_DINERS,PAGO_REAL,REESTRUCT_VIGENTE,DES_ESPECIALIDAD,CODRET,BOLNAC,EMPRESA,CAMPANA_CON_ECE,STOTOT_VISA,VAXFAC_VISA,TRIESGO_VISA,CICLOF_VISA,EDCART_VISA,
        ACTUALES_ORIG_VISA,D30_ORIG_VISA,D60_ORIG_VISA,D90_ORIG_VISA,DMAS90_ORIG_VISA,VAPAMI_VISA,FECHACOMPROMISO_VISA,FECHALLAM_VISA,DESCRIPCION_VISA,OBSERVACION_VISA,MOTIVO_1_VISA,INTERES_TOTAL_VISA,
        PROM_PAG_VISA,DEBITO_AUT_VISA,NOMESTAB_VISA,SIMULACION_DIF_sum_VISA,N_DIF_VISA,DEBITO_VISA,CREDITO_VISA,PAGO_VISA,CODIGO_VISA,CUOTAS_PTES_VISA,CUOTA_REF_VIG_PENDIENTE_VISA,VALOR_PEN_REF_VIG_VISA,
        IXF_VISA,SCORE_DINERS_VISA,PAGO_REAL_VISA,REESTRUCT_VIGENTE_VISA,DES_ESPECIALIDAD_VISA,CODRET_VISA,BOLNAC_VISA,EMPRESA_VISA,CAMPANA_CON_ECE_VISA,STOTOT_DISCOVER,VAXFAC_DISCOVER,TRIESGO_DISCOVER,
        CICLOF_DISCOVER,EDCART_DISCOVER,ACTUALES_ORIG_DISCOVER,D30_ORIG_DISCOVER,D60_ORIG_DISCOVER,D90_ORIG_DISCOVER,DMAS90_ORIG_DISCOVER,VAPAMI_DISCOVER,FECHACOMPROMISO_DISCOVER,FECHALLAM_DISCOVER,
        DESCRIPCION_DISCOVER,OBSERVACION_DISCOVER,MOTIVO_1_DISCOVER,INTERES_TOTAL_DISCOVER,PROM_PAG_DISCOVER,DEBITO_AUT_DISCOVER,NOMESTAB_DISCOVER,SIMULACION_DIF_sum_DISCOVER,N_DIF_DISCOVER,
        DEBITO_DISCOVER,CREDITO_DISCOVER,PAGO_DISCOVER,CODIGO_DISCOVER,CUOTAS_PTES_DISCOVER,CUOTA_REF_VIG_PENDIENTE_DISCOVER,VALOR_PEN_REF_VIG_DISCOVER,IXF_DISCOVER,SCORE_DINERS_DISCOVER,
        PAGO_REAL_DISCOVER,REESTRUCT_VIGENTE_DISCOVER,DES_ESPECIALIDAD_DISCOVER,CODRET_DISCOVER,BOLNAC_DISCOVER,EMPRESA_DISCOVER,CAMPANA_CON_ECE_DISCOVER,TOTAL_CUOTAS_REF,TOTAL_CUOTAS_REF_VI,
        TOTAL_CUOTAS_REF_DI)
        set id_carga = (SELECT id_carga FROM tbl_id_carga ORDER BY id_carga desc LIMIT 1), estado = 1;", addslashes($csv));

        DB::connection()->getpdo()->exec($query2);
        return $id_carga;
*/
    }
}