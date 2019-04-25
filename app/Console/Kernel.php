<?php

namespace App\Console;

use App\Console\Commands\SendIvrs;
use App\Console\Commands\SendPredictivos;
use App\Console\Commands\IndexarGestiones;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SendIvrs::class,
        SendPredictivos::class,
        IndexarGestiones::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ivr:enviar')->everyMinute();
        $schedule->command('predictivo:insistir')->everyMinute();
        $schedule->command('indexar:gestiones')->everyTenMinutes();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
