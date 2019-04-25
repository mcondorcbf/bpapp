<?php

namespace App\Jobs;

use App\ivrs\tbl_carga as carga;
use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Logging\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;


class SendIvrs implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $carga=carga::where('estado',1)->limit(5)->get();
        $carga->each(function($k) {

            $k->estado=0;
            $k->save();
            Log::info("Envié un job con id:".$k->id);

        });

    }
}
