<?php

namespace App\Console\Commands;

use App\reportesNuevoSistema\tbl_demarches;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class IndexarGestiones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexar:gestiones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexa gestiones automaticamente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*-------------------Cron job----------------------*/
        $fecha_actual = date('Y-m-j H:i'); //inicializo la fecha con la hora
        $fecha_anterior = strtotime ( '- 10 minute' , strtotime ( $fecha_actual ) ) ;
        $fecha_anterior = date ( 'H:i' , $fecha_anterior );

        echo '----> la fecha actual: '.$fecha_actual. ' ---> fecha anterior: '.$fecha_anterior.' || ';

        //$demarches=tbl_demarches::where('created_at','>',$fecha_anterior)->get(['id']);
        try{
            $demarches=DB::connection('cobefec3')->select("select id from demarches where type='DM' and date(created_at)='".date('Ymd')."' and time(created_at)>'".$fecha_anterior."' order by created_at;");
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }

        $demarches=json_decode(json_encode($demarches), true);
        $cont=1;
        foreach ($demarches as $k){

            echo $k['id'].' -- ';
            usleep(200000);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://ncobefecapp.cobefec.com/apoyo/gestiones/gestion/".$k['id'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    // Set Here Your Requesred Headers
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                echo "\nExiste error  #: ".$err." ".date('Y-m-d H:i:s');
                return "cURL Error #:" . $err;
            }

        }
        /*----------------Fin Cron Job---------------*/
    }
}
