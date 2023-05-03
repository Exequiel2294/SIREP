<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Traits\MinaTrait;

class SendDailyReportMina extends Command
{
    use MinaTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:dailyreportmina';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio DailyReportMina';

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
     * @return int
     */
    public function handle()
    {
        $this->date = date('Y-m-d',strtotime("-1 days")); 

        $request = new Request(array('date' => $this->date, 'type' => 1));
        $tabla = $this->TraitMinaTable($request);
         
        $registros= $tabla->getData()->data;
        if ($registros <> [] && $registros <> NULL)
        {
            $tablacomentarios =
            DB::select(
                'SELECT ca.nombre AS area, c.comentario AS comentario, u.name AS usuario FROM comentario c
                INNER JOIN users u
                ON c.user_id = u.id
                INNER JOIN comentario_area ca
                ON c.area_id = ca.id
                WHERE c.fecha = ?
                AND ca.area_id = 2',
                [$this->date]
            );
            $date = $this->date;
            $pdf = Pdf::loadView('pdf.mina', compact('registros', 'date', 'tablacomentarios'));
            if ( env('APP_ENV') == 'production')
            {
                $data["subject"] = "SIOM DailyReport Mina ";
                $data["email"] = "mmsa.dailyreport_mina@mansfieldmin.com";
            }
            else
            {
                $data["subject"] = "DEV DailyReport Mina ";
                $data["email"] = "mmsa.soporteit@mansfieldmin.com";
            }
            Mail::send('mails.dailytablemina', $data, function ($message) use ($data, $pdf) {
                $message->to($data['email']);
                $message->subject($data["subject"].$this->date);
                $message->attachData($pdf->output(), 'SIOM_DailyReportMina_'.$this->date.'.pdf'); //attached pdf file
            });
        }
        else
        {
            if ( env('APP_ENV') == 'production')
            {
                $data["subject"] = "SIOM DailyReport Mina ";
                $data["email"] = ["mmsa.soporteit@mansfieldmin.com"];
            }
            else
            {
                $data["subject"] = "DEV DailyReport Mina ";
                $data["email"] = "mmsa.soporteit@mansfieldmin.com";
            }
            Mail::send('mails.dailytableminafail', $data, function ($message) use ($data) {
                $message->to($data['email']);
                $message->subject($data["subject"].$this->date);
            });
        }

    }
}
