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
        $vbles_c = DB::table('variable')->whereIn('tipo',[4, 8])->pluck('id')->toArray();
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
            $conciliado = 
            DB::table('conciliado_data AS cd')
            ->join('variable AS v', 'cd.variable_id', '=', 'v.id')
            ->join('subcategoria AS s', 'v.subcategoria_id', '=', 's.id')
            ->join('categoria AS c', 's.categoria_id', '=', 'c.id')
            ->where(['cd.fecha' => date('Y-m-t', strtotime($selecteddate)), 'c.area_id' => $request->get('area_id')])
            ->count();          
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
                if ($conciliado == 0)
                {
                    if (date('Y-m-d', strtotime($selecteddate)) == date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))))
                    {  
                        $vbles_11h = DB::table('variable')->where('tipo',2)->pluck('id')->toArray();
                        if (in_array($request->variable_id,$vbles_11h) && (int)date('H') < 14)
                        {
                            $data['msg'] = 'No puede modificar esta variable hasta que la misma sea cargada a las 11hs';
                            $data['val'] = -1;
                            return response()->json($data);
                        }
                        else
                        {
                            $vbles_10h = DB::table('variable')->where('tipo',9)->pluck('id')->toArray();
                            if (in_array($request->variable_id,$vbles_10h) && (int)date('H', time() - 3600 * 3) < 10)
                            {
                                $data['msg'] = 'No puede modificar esta variable hasta que la misma sea cargada a las 10hs';
                                $data['val'] = -1;
                                return response()->json($data);
                            }
                            else
                            {
                                $vbles_11_21h = DB::table('variable')->where('tipo',5)->pluck('id')->toArray();
                                if (in_array($request->variable_id,$vbles_11_21h))
                                {
                                    if ((int)date('H', time() - 3600 * 3) < 11)
                                    {
                                        $data['msg'] = 'No puede modificar esta variable hasta que la misma sea cargada a las 11hs';
                                        $data['val'] = -1;
                                        return response()->json($data);
                                    }
                                    else
                                    {
                                        if ((int)date('H', time() - 3600 * 3) < 21)
                                        {
                                            $where = array('data.id' => $request->id);
                                            $data['msg'] = 'El valor de esta variable se sobreescribirá a 21hs. del dia corriente.';
                                            $data['val'] = 2;
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
                                    }
                                }   
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
                        DB::table('data')
                            ->whereIn('variable_id',[10011,10019,10031])
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

    public function complete_value($id)
    {            
        $data =  Data::findOrFail($id);
        switch((int)$data->variable_id)
        {                                    
            case 10002:
                //MMSA_TP_Au Triturado oz                  
                //((10005 MMSA_TP_Mineral Triturado t)*(10004 MMSA_TP_Ley Au g/t)) / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10005) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10004) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;
            case 10006:
                //MMSA_TP_Productividad t/h
                //(10005 MMSA_TP_Mineral Triturado t)/ (10062 MMSA_TP_Horas Operativas Trituración Primaria h)                                    
                $d_real = 
                DB::select(
                    'SELECT A.valor/B.valor as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10005) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10062
                    AND valor <> 0) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                   
            case 10008:
                //MMSA_TP_Au Triturado oz                  
                //((10011 MMSA_TP_Mineral Triturado t)*(10010 MMSA_TP_Ley Au g/t)) / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10011) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10010) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;
            case 10013:
                //MMSA_HPGR_Productividad (t/h) t/h
                //(10011 MMSA_HPGR_Mineral Triturado t)/ (10063 Horas Operativas Trituración Terciaria h)                                    
                $d_real = 
                DB::select(
                    'SELECT A.valor/B.valor as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10011) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10063
                    AND valor <> 0) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;
            case 10015:
                //MMSA_AGLOM_Adición de Cemento kg/t
                //((10067 MMSA_AGLOM_Cemento) * 1000) / (10019 MMSA_AGLOM_Mineral Aglomerado)                              
                $d_real = 
                DB::select(
                    'SELECT (A.valor * 1000) / B.valor as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10067) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10019
                    AND valor <> 0) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;
            case 10020:
                //10020 MMSA_AGLOM_Productividad t/h
                //(10019 MMSA_AGLOM_Mineral Aglomerado t)/ (10064 MMSA_AGLOM_Horas Operativas Aglomeración)                                    
                $d_real = 
                DB::select(
                    'SELECT A.valor/B.valor as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10019) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10064
                    AND valor <> 0) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                   
            case 10022:
                //10022 MMSA_APILAM_PYS_Au Extraible Trituración Secundaria Apilado Camiones (oz)                  
                //(((10026 MMSA_APILAM_PYS_Recuperación %)/ 100) * (10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t) * (10024 MMSA_APILAM_PYS_Ley Au g/t)) / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT ((A.valor/100) * B.valor * C.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10026) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10025) as B
                    ON A.fecha = B.fecha
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10024) as C
                    ON A.fecha = C.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                
            case 10023:
                //MMSA_APILAM_PYS_Au Trituración Secundaria Apilado Camiones oz                  
                //((10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t)*(10024 MMSA_APILAM_PYS_Ley Au g/t) / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10025) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10024) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                  
            case 10027:
                //MMSA_APILAM_STACKER_Au Apilado Stacker (oz)                  
                //((10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker (t))*(10030 MMSA_APILAM_STACKER_Ley Au (g/t)) / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10031) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10030) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                   
            case 10028:
                //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                //(((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT ((A.valor/100) * B.valor * C.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10033) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10031) as B
                    ON A.fecha = B.fecha
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10030) as C
                    ON A.fecha = C.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;
            case 10032:
                //10032 MMSA_APILAM_STACKER_Productividad t/h
                //(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)/ (10065 MMSA_APILAM_STACKER_Tiempo Operativo)                                    
                $d_real = 
                DB::select(
                    'SELECT A.valor/B.valor as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10031) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10065
                    AND valor <> 0) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                                                                   
            case 10037:
                //10037: MMSA_APILAM_TA_Total Au Apilado (oz)                  
                //((10039 MMSA_APILAM_TA_Total Mineral Apilado t)*(10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035                                    
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10039) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10035) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;              
            case 10038:
                //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                //(((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT ((A.valor/100) * B.valor * C.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10036) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10039) as B
                    ON A.fecha = B.fecha
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10035) as C
                    ON A.fecha = C.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;             
            case 10040:
                //10040 MMSA_SART_Eficiencia (%)
                //(((10043 MMSA_SART_Ley Cu Alimentada ppm) - (10044 MMSA_SART_Ley Cu Salida ppm)) * 100) / (10043 MMSA_SART_Ley Cu Alimentada ppm)                               
                $d_real = 
                DB::select(
                    'SELECT ((A.valor-B.valor) * 100) / A.valor as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10043
                    AND valor <> 0) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10044) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                
            case 10046:
                //Au Adsorbido - MMSA_ADR_Au Adsorbido (oz)                 
                //((10052 MMSA_ADR_PLS a Carbones) * ((10051 MMSA_ADR_Ley de Au PLS)-(10050 MMSA_ADR_Ley de Au BLS))) / 31.1035                           
                $d_real = 
                DB::select(
                    'SELECT (A.valor * (B.valor-C.valor))/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10052) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10051) as B
                    ON A.fecha = B.fecha
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10050) as C
                    ON A.fecha = C.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;               
            case 10049:
                //MMSA_ADR_Eficiencia (%)
                //(((10051 MMSA_ADR_Ley de Au PLS) - (10050 MMSA_ADR_Ley de Au BLS)) * 100) / (10051 MMSA_ADR_Ley de Au PLS)                               
                $d_real = 
                DB::select(
                    'SELECT ((A.valor-B.valor) * 100) / A.valor as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10051
                    AND valor <> 0) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10050) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                 
            case 10053:
                //MMSA_LIXI_Au Lixiviado (oz)                  
                //((10061 MMSA_LIXI_Solución PLS)*(10057 MMSA_LIXI_Ley Au Solución PLS) / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10061) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10057) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;
            case 10072:
                //Au ROM a Trituradora oz                  
                //(10070*10071 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10070) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10071) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;
            case 10075:
                //Au ROM Alta Ley a Stockpile oz                  
                //(10073*10074 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10073) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10074) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;
            case 10078:
                //Au ROM Media Ley a Stockpile oz                  
                //(10076*10077 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10076) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10077) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;
            case 10081:
                //Au ROM Baja Ley a Stockpile oz                  
                //(10079*10080 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10079) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10080) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;
            case 10084:
                //Total Au ROM a Stockpiles oz                  
                //(10082*10083 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10082) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10083) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                            
            case 10090:
                //Total Au Minado oz                  
                //(10088*10089 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10088) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10089) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                            
            case 10095:
                //Au Alta Ley Stockpile a Trituradora oz                  
                //(10093*10094 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10093) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10094) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                            
            case 10099:
                //Au Media Ley Stockpile a Trituradora oz                  
                //(10097*10098 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10097) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10098) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                            
            case 10102:
                //Au Baja Ley Stockpile a Trituradora oz                  
                //(10100*10101 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10100) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10101) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                            
            case 10105:
                //Au de Stockpiles a Trituradora oz                  
                //(10103*10104 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10103) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10104) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break;                            
            case 10108:
                //Au (ROM+Stockpiles) a Trituradora oz                  
                //(10106*10107 / 31.1035                                     
                $d_real = 
                DB::select(
                    'SELECT (A.valor * B.valor)/31.1035 as dia_real FROM
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10106) as A
                    INNER JOIN   
                    (SELECT fecha, variable_id, [valor]
                    FROM [dbo].[data]
                    where variable_id = 10107) as B
                    ON A.fecha = B.fecha
                    WHERE  DATEPART(y, A.fecha) = ?',
                    [(int)date('z', strtotime($data->fecha)) + 1]
                ); 
            break; 
            default:             
                return $data->valor;   
            break; 
        }
        if(isset($d_real[0]->dia_real)) 
        { 
            $d_real = $d_real[0]->dia_real;   
        }        
        else
        {
            $d_real = '-';
        }  
        return $d_real;
    }
}
