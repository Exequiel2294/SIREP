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
        'App\Console\Commands\SendDailyReportIntranet',     
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
        $schedule->command('send:dailyreport')->between('17:55', '18:05');// Dailyreport de las 15:00
        $schedule->command('send:dailyreportmina')->between('11:20', '11:40');//Dailyreport de las 08:00
        $schedule->command('send:dailyreportcombinado')->between('19:55', '20:05');//Dailyreport(Lista de Distribucion) de las 17:00
        $schedule->command('send:dailyreportintranet')->between('17:55', '20:05');//Dailyreport(INTRANET) de las 17:00
        /*$schedule->command('send:dailyreportmina')->between('10:00', '22:00');
        $schedule->command('send:dailyreportcombinado')->between('10:00', '22:00');*/
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
