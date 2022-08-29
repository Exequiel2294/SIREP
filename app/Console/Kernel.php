<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\SendDailyReport',
        'App\Console\Commands\SendDailyReportMina',  
        'App\Console\Commands\SendDailyReportCombinado',      
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //HORARIO UTC
        $schedule->command('send:dailyreport')->between('13:40', '23:40');
        $schedule->command('send:dailyreportmina')->between('13:40', '23:40');
        $schedule->command('send:dailyreportcombinado')->between('13:40', '23:40');
        /*$schedule->call(function () {
            DB::table('data_conexions')->delete();
        })->everyMinute(); //->dailyAt('16:00');*/
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
