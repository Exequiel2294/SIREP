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
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Traits\MinaTrait;
use App\Traits\ProcesosTrait;


class DashboardController extends Controller
{
    use MinaTrait, ProcesosTrait;
    public function index(Request $request)
    { 
        $where = ['area_id' => 1, 'estado' => 1];
        $areas = ComentarioArea::orderBy('nombre')->where($where)->pluck('nombre','id')->toArray();
        return view('dashboard_process', compact('areas'));
    }

    public function index2(Request $request)
    { 
        $where = ['area_id' => 2, 'estado' => 1];
        $areas = ComentarioArea::orderBy('nombre')->where($where)->pluck('nombre','id')->toArray();
        return view('dashboard_mine', compact('areas'));
    }

    public function procesostable(Request $request)
    {        
        if(request()->ajax()) {
            $this->date = $request->get('fecha');
           
            $request = new Request(array('date' => $this->date, 'type' => 0));
            $tabla = $this->TraitProcesosTable($request);

            return $tabla;
        } 
    }

    public function getpdfprocesostable($date)
    {
        if ($date == 1)
        {         
            $this->date = date('Y-m-d',strtotime("-1 days"));
        }
        else
        {   
            $this->date = $date;
        }

        $request = new Request(array('date' => $this->date, 'type' => 1));
        $tabla = $this->TraitProcesosTable($request);

        $registros= $tabla->getData()->data;
        $tablacomentarios =
        DB::select(
            'SELECT ca.nombre AS area, c.comentario AS comentario, u.name AS usuario FROM comentario c
            INNER JOIN users u
            ON c.user_id = u.id
            INNER JOIN comentario_area ca
            ON c.area_id = ca.id
            WHERE c.fecha = ?
            AND ca.area_id = 1',
            [$this->date]
        );
        $date = $this->date;
        $pdf = Pdf::loadView('pdf.procesos', compact('registros', 'tablacomentarios','date'));
        return $pdf->download('DailyReportProcesos'.$this->date.'.pdf');
    }

    public function minatable(Request $request)
    {        
        if(request()->ajax()) {
            $this->date = $request->get('fecha');  

            $request = new Request(array('date' => $this->date, 'type' => 0));
            $tabla = $this->TraitMinaTable($request);

            return $tabla;
        } 
    }

    public function getpdfminatable($date)
    {
        if ($date == 1)
        {         
            $this->date = date('Y-m-d',strtotime("-1 days"));
        }
        else
        {   
            $this->date = $date;
        }

        $request = new Request(array('date' => $this->date, 'type' => 1));
        $tabla = $this->TraitMinaTable($request);

        $registros= $tabla->getData()->data;
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
        return $pdf->download('DailyReportMina'.$this->date.'.pdf');
    }

    public function edit(Request $request)
    { 
        $vbles_c = DB::table('variable')->where('tipo',4)->pluck('id')->toArray();
        $selecteddate = $request->selecteddate;   
        if (in_array($request->variable_id,$vbles_c))
        {
            $data['val'] = -2;
            switch ($request->variable_id)
            {
                
                case 10002:	//MMSA_TP_Au Triturado (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>TP_Mineral Triturado</li>
                                <li>TP_Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10006:	//MMSA_TP_Productividad (t/h)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>TP_Mineral Triturado</li>
                                <li>TP_Horas Operativas</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>A / B</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10008:	//MMSA_HPGR_Au Triturado (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>HPGR_Mineral Triturado</li>
                                <li>HPGR_Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10013:	//MMSA_HPGR_Productividad (t/h)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>HPGR_Mineral Triturado</li>
                                <li>HPGR_Horas Operativas</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>A / B</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10015:	//MMSA_AGLOM_Adición de Cemento (kg/t)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>AGLOM_Mineral Aglomerado</li>
                                <li>AGLOM_Cemento</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( B * 1000 )/A</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10020:	//MMSA_AGLOM_Productividad (t/h)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>AGLOM_Mineral Aglomerado</li>
                                <li>AGLOM_Horas Operativas</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>A / B</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10027:	//MMSA_APILAM_STACKER_Au Apilado Stacker (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>APIL_ST_Mineral Apilado</li>
                                <li>APIL_ST_Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10028:	//MMSA_APILAM_STACKER_Au Extraible Apilado Stacker
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>APIL_ST_Mineral Apilado</li>
                                <li>APIL_ST_Ley Au</li>
                                <li>APIL_ST_Recuperación</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>(( C / 100 ) * A * B) / 31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10032:	//MMSA_APILAM_STACKER_Productividad (t/h)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>APIL_ST_Mineral Apilado</li>
                                <li>APIL_ST_Tiempo Operativo</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>A / B</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10037:	//MMSA_APILAM_TA_Total Au Apilado (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>APIL_TA_Mineral Apilado</li>
                                <li>APIL_TA_Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10038:	//MMSA_APILAM_TA_Total Au Extraible Apilado (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>APIL_TA_Mineral Apilado</li>
                                <li>APIL_TA_Ley Au</li>
                                <li>APIL_TA_Recuperación</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>(( C / 100 ) * A * B) / 31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10040:	//MMSA_SART_Eficiencia (%)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>SART_Ley Cu Alimentada</li>
                                <li>SART_Ley Cu Salida</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>(( A - B ) * 100) / A</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10046:	//MMSA_ADR_Au Adsorbido (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>ADR_PLS a Carbones</li>
                                <li>ADR_Ley de Au PLS+ILS</li>
                                <li>ADR_Ley de Au BLS</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * ( B - C ))/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10049:	//MMSA_ADR_Eficiencia (%)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>ADR_Ley de Au PLS+ILS</li>
                                <li>ADR_Ley de Au BLS</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>(( A - B ) * 100) / A</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10053:	//MMSA_LIXI_Au Lixiviado (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>LIXI_Solución PLS</li>
                                <li>LIXI_Ley Au Solución PLS</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10072:	//Au ROM a Trituradora (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Mineral ROM a Trituradora</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10075:	//Au ROM Alta Ley a Stockpile (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Mineral ROM Alta Ley a Stockpile</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10078:	//Au ROM Media Ley a Stockpile (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Mineral ROM Media Ley a Stockpile</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10081:	//Au ROM Baja Ley a Stockpile (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Mineral ROM Baja Ley a Stockpile</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10084:	//Total Au ROM a Stockpiles (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Total Mineral ROM a Stockpiles</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10087:	//Au ROM a Leach Pad (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Mineral ROM a Leach Pad</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10090:	//Total Au Minado (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Total Mineral Minado</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10095:	//Au Alta Ley Stockpile a Trituradora (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Alta Ley Stockpile a Trituradora</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10099:	//Au Media Ley Stockpile a Trituradora (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Media Ley Stockpile a Trituradora</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10102:	//Au Baja Ley Stockpile a Trituradora (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Baja Ley Stockpile a Trituradora</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10105:	//Au de Stockpiles a Trituradora (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Total de Stockpiles a Trituradora</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
                case 10108:	//Au (ROM+Stockpiles) a Trituradora (oz)
                    $data['html'] = 
                    '<div>
                        <h2 style="margin-bottom:1.5rem;">Esta Variable es Calculada</h2>  
                        <div style="text-align:left;">
                            <h3>Variables asociadas:</h3>
                            <ol type="A">
                                <li>Total Mineral (ROM+Stockpiles) a Trituradora</li>
                                <li>Ley Au</li>
                            </ol>
                        </div>
                        <div style="text-align:left;">
                            <h3>Calculo Asociado:</h3>
                            <ul>
                                <li>( A * B )/31.1035</li>
                            </ul>
                        </div>
                    </div>';
                break;
            }
            return response()->json($data); 
        }  
        else
        {

            $where = ['user_id' => Auth::user()->id, 'variable_id' => $request->variable_id];    
            $uservbles = DB::table('permisos_variables')
                        ->where($where)
                        ->get();
           if ($uservbles->count() == 0)
           {
                $data['msg'] = 'No cuenta con los permisos necesarios para editar esta variable.';
                $data['val'] = -1;
                return response()->json($data);
           }
           else
           {
                if ((date('m', strtotime($selecteddate)) == date('m') && date('Y', strtotime($selecteddate)) == date('Y')) || 
                (date('m', strtotime($selecteddate))-1 == date('m') && date('Y', strtotime($selecteddate)) == date('Y') && date('d') <= 5))
                {
                    if (date('Y-m-d', strtotime($selecteddate)) == date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))))
                    {  
                        $vbles_11h = DB::table('variable')->where('tipo',2)->pluck('id')->toArray();
                        $vbles_11_21h = DB::table('variable')->where('tipo',5)->pluck('id')->toArray();
                        if (in_array($request->variable_id,$vbles_11h) && (int)date('H') < 14)
                        {
                            $data['msg'] = 'No puede modificar esta variable hasta que la misma sea cargada a las 11hs';
                            $data['val'] = -1;
                            return response()->json($data);
                        }
                        else
                        {
                            if (in_array($request->variable_id,$vbles_11_21h) && (int)date('H') < 24)
                            {
                                $data['msg'] = 'No puede modificar esta variable hasta su ultima carga a las 21hs.';
                                $data['val'] = -1;
                                return response()->json($data);
                            }
                            
                        }
                    }                     
                    
                    $where = array('data.id' => $request->id);
                    $data['val'] = 1;
                    $data['generic'] =  DB::table('area')
                                ->join('categoria', 'categoria.area_id', '=','area.id')
                                ->join('subcategoria', 'subcategoria.categoria_id', '=','categoria.id')
                                ->join('variable', 'variable.subcategoria_id', '=','subcategoria.id')
                                ->join('data', 'data.variable_id', '=','variable.id')
                                ->where($where)
                                ->select('area.nombre as area','categoria.nombre as categoria','subcategoria.nombre as subcategoria','variable.nombre as variable','data.id as id','data.valor as valor', 'data.fecha as fecha')
                                ->first();
                    return response()->json($data);  
                    

                }
                else
                {
                    $data['msg'] = 'No puede modificar data que ya fue conciliada.';
                    $data['val'] = -1;
                    return response()->json($data);   
                }
                
            
           }
        }
    }

    public function load(Request $request)
    {       
        $id = $request->get('id');
        if($id == '' || $id == null)
        {
 
        }
        else
        {
            $validator = Validator::make(
                $request->all(),
                [   
                    'id'    => 'required|numeric|exists:data,id',
                    'valor' => 'string'
                ]            
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                $data = Data::findOrFail($id);
                $oldvalue = $data->valor;
                $newvalue = $request->get('valor');
                switch($data->variable_id)
                {
                    case 10011: 
                    case 10019:
                    case 10031: 
                    case 10039:
                        DB::table('data')
                            ->whereIn('variable_id',[10011,10019,10031,10039])
                            ->where('fecha', $data->fecha)
                            ->update(['valor' =>$newvalue]);                    
                    break;  
                    case 10010: 
                    case 10030:
                    case 10035: 
                        DB::table('data')
                            ->whereIn('variable_id',[10010,10030,10035])
                            ->where('fecha', $data->fecha)
                            ->update(['valor' =>$newvalue]);                    
                    break;   
                    case 10064: 
                    case 10065:
                        DB::table('data')
                            ->whereIn('variable_id',[10064,10065])
                            ->where('fecha', $data->fecha)
                            ->update(['valor' =>$newvalue]);                    
                    break;  
                    case 10051: 
                    case 10057:
                        DB::table('data')
                            ->whereIn('variable_id',[10051,10057])
                            ->where('fecha', $data->fecha)
                            ->update(['valor' =>$newvalue]);                    
                    break;
                    default:
                        $data->update(
                        [
                            'valor' =>$newvalue
                        ]);
                    break;              
                } 
                if($oldvalue != $newvalue)
                {
                    Historial::create([
                        'data_id' => $id,
                        'fecha' => date('Y-m-d H:i:s'),
                        'transaccion' => 'EDIT',
                        'valorviejo' => $oldvalue,
                        'valornuevo' => $newvalue,
                        'usuario' => auth()->user()->name
                    ]);
                }
                return;                
            }
        }        
    } 


}
