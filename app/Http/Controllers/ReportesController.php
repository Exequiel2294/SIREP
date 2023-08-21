<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Data;
use App\Models\Historial;
use App\Models\ComentarioArea;
use App\Models\Variable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\MinaTrait;
use App\Traits\ProcesosTrait;


class ReportesController extends Controller
{
    use MinaTrait, ProcesosTrait;

    public function getpdfcompleto(Request $request)
    {
        $date = $request->get('date');
        $columnsVisibility = $request->get('columnsVisibility');
        $focastVisibility = $request->get('focastVisibility');
        $budgetVisibility = $request->get('budgetVisibility');
        $arrayCount = array_count_values($columnsVisibility);
        if (isset($arrayCount['true']))
        {
            $colspanTFrame = 1;
            if ($focastVisibility) {
                $colspanTFrame+=2;
            }
            if ($budgetVisibility) {
                $colspanTFrame+=2;
            }
            $colspan = ($arrayCount['true']*$colspanTFrame)+2; //+2 porque se cuenta las columnas de nombre de variable y unidad
        }
        else
        {
            $colspan = 2;
        }
        $request = new Request(array('date' => $date, 'type' => 1));
        $tablaprocesos = $this->TraitProcesosTable($request);
        
        $request = new Request(array('date' => $date, 'type' => 1));
        $tablamina = $this->TraitMinaTable($request);
         
        $registrosmina= $tablamina->getData()->data;        
        $registrosprocesos= $tablaprocesos->getData()->data;
        $registros = array_merge($registrosmina, $registrosprocesos);
        
        
        $tablacomentarios =
        DB::select(
            'SELECT ca.nombre AS area, c.comentario AS comentario, u.name AS usuario FROM comentario c
            INNER JOIN users u
            ON c.user_id = u.id
            INNER JOIN comentario_area ca
            ON c.area_id = ca.id
            WHERE c.fecha = ?
            AND ca.area_id = 1',
            [$date]
        );

        $pdf = Pdf::loadView('pdf.combinadoColumnsVisibility', compact('registros', 'tablacomentarios','date', 'columnsVisibility', 'colspan', 'budgetVisibility', 'focastVisibility', 'colspanTFrame'));
        
        

        $pdf->set_paper('a3', 'portrait');
        $pdf->render(); 
        $output = $pdf->output();
        file_put_contents(public_path('pdf/DailyReport.pdf'), $output);

        $ruta = asset('pdf/DailyReport.pdf');

        $response = array();
        $response['ruta'] = $ruta; 
        return response()->json($response);
        //return $pdf->download('DailyReportCompleto'.$this->date.'.pdf');


    }
}