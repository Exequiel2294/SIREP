<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Traits\MinaTrait;
use App\Traits\ProcesosTrait;


class SendDailyReportIntranet extends Command
{
    use MinaTrait, ProcesosTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:dailyreportintranet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envío DailyReport Intranet';

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

        if ( (int)date('d',strtotime("-1 days")) == 4) {
            $j = 9;
        }
        else {
            $j = 1;
        }
        for ($i = 1; $i <= $j; $i++) {

            $this->date = date('Y-m-d',strtotime('-'.$i.' days'));
            $this->i = $i;
       
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
                //return view('pdf.combinado', compact('registros', 'date', 'tablacomentarios'));
                $date = $this->date;
                
                $columnsVisibility = [true, true, true, true];
                $focastVisibility = 0;//Para volver a la normalidad hay que cambiar el valor a 1
                $budgetVisibility = 1;
                $colspanTFrame = 3;
                $colspan = 16;//Tener en cuenta esto cuando se haga un cambio ya que es la fila que subdivide entre las variables
    
                $pdf = Pdf::loadView('pdf.customizeColumnsVisibility', compact('registros', 'tablacomentarios','date', 'columnsVisibility', 'colspan', 'budgetVisibility', 'focastVisibility', 'colspanTFrame'));
                $pdf->set_paper('a3', 'portrait');
                $pdf->render(); 
                if ( env('APP_ENV') == 'production')
                {
                    $data["subject"] = "Daily Report Intranet ";
                    $data["email"] = "mmsa.soporteit@mansfieldmin.com";
                }
                else
                {
                    $data["subject"] = "Daily Report Intranet ";
                    $data["email"] = "ecayampi@mansfieldmin.com";
                }
                Mail::send('mails.dailytablecombinado', $data, function ($message) use ($data, $pdf) {
                    $message->to($data['email']);
                    $message->subject($data["subject"].$this->date);
                    $message->attachData($pdf->output(), 'Daily_Report_'.date('dmy',strtotime('-'.$this->i.' days')).'.pdf'); //attached pdf file
                });
            }
            else
            {
                if ( env('APP_ENV') == 'production')
                {
                    $data["subject"] = "Daily Report Intranet ";
                    $data["email"] = "mmsa.soporteit@mansfieldmin.com";
                }
                else
                {
                    $data["subject"] = "DEV DailyReport Intranet ";
                    $data["email"] = "ecayampi@mansfieldmin.com";
                }
                Mail::send('mails.dailytablecombinadofail', $data, function ($message) use ($data) {
                    $message->to($data['email']);
                    $message->subject($data["subject"].$this->date);
                });
            }
        }
    }
}
