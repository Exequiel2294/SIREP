<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Traits\MinaTrait;
use App\Traits\ProcesosTrait;


class SendDailyReportCombinado extends Command
{
    use MinaTrait, ProcesosTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:dailyreportcombinado';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviop DailyReport Combinado';

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
        $tablaprocesos = $this->TraitProcesosTable($request);

        $request = new Request(array('date' => $this->date, 'type' => 1));
        $tablamina = $this->TraitMinaTable($request);
         
        $registrosmina= $tablamina->getData()->data;        
        $registrosprocesos= $tablaprocesos->getData()->data;
        $registros = array_merge($registrosmina, $registrosprocesos);
        if ($registros <> [] && $registros <> NULL)
        {
            $tablacomentarios =
            DB::select(
                'SELECT ca.nombre AS area, c.comentario AS comentario, u.name AS usuario FROM comentario c
                INNER JOIN users u
                ON c.user_id = u.id
                INNER JOIN comentario_area ca
                ON c.area_id = ca.id
                WHERE c.fecha = ?',
                [$this->date]
            );
            $date = $this->date;
            //return view('pdf.combinado', compact('registros', 'date', 'tablacomentarios'));
            $pdf = Pdf::loadView('pdf.combinado', compact('registros', 'date', 'tablacomentarios')); 
            if ( env('APP_ENV') == 'production')
            {
                $data["subject"] = "DailyReport ";
                $data["email"] = "mmsa.dailyreport@mansfieldmin.com";
            }
            else
            {
                $data["subject"] = "DEV DailyReport ";
                $data["email"] = "mmsa.soporteit@mansfieldmin.com";
            }
            Mail::send('mails.dailytablecombinado', $data, function ($message) use ($data, $pdf) {
                $message->to($data['email']);
                $message->subject($data["subject"].$this->date);
                $message->attachData($pdf->output(), 'DailyReport'.$this->date.'.pdf'); //attached pdf file
            });
        }
        else
        {
            if ( env('APP_ENV') == 'production')
            {
                $data["subject"] = "DailyReport ";
                $data["email"] = ["mmsa.soporteit@mansfieldmin.com"];
            }
            else
            {
                $data["subject"] = "DEV DailyReport ";
                $data["email"] = "mmsa.soporteit@mansfieldmin.com";
            }
            Mail::send('mails.dailytablecombinadofail', $data, function ($message) use ($data) {
                $message->to($data['email']);
                $message->subject($data["subject"].$this->date);
            });
        }
    }
}