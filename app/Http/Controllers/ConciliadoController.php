<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\ConciliadoData;
use App\Models\ConciliadoHistorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Models\ActiveDirectory\Group;


class ConciliadoController extends Controller
{
    public function index(Request $request)
    {
        $months = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
        $actmonth = date("m");
        $months = array_filter($months,
            function ($key) use ($actmonth) {
                return $key < (int)$actmonth;
            },
            ARRAY_FILTER_USE_KEY
        );        
        return view('conciliado', compact('months'));
    }

    public function getvariables(Request $request)
    {
        if(request()->ajax()) {
            $this->area_id = $request->get('area_id');
            $this->date = date('Y-m-t',strtotime(date('Y').'-'.$request->get('month').'-01'));
            if ($this->area_id == 1)
            {
                $this->pparray = 
                    [10004, 10010, 10012, 10015, 10018, 10024, 10030, 10033, 10035, 10036, 
                    10040, 10041, 10042, 10043, 10044, 10049, 10050, 10051, 10054, 10055, 
                    10056, 10057, 10058];//se coloca 10015 en pparray por el budget
                $this->sumarray = 
                    [10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 
                    10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 
                    10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067];
                $this->promarray = 
                [10003, 10007, 10009, 10014, 10016, 10017, 10021, 10026, 10029, 10034];
                $this->divarray = 
                [10006, 10013, 10020, 10032];
            

                //INICIO CALCULOS REUTILIZABLES
                    //MES REAL
                    $this->summesreal10005 = 
                    DB::select(
                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                        FROM [mansfield2].[dbo].[data]
                        WHERE variable_id = 10005
                        AND  MONTH(fecha) = ?
                        GROUP BY MONTH(fecha)', 
                        [date('m', strtotime($this->date))]
                    );
                    $this->summesreal10011 = 
                    DB::select(
                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                        FROM [mansfield2].[dbo].[data]
                        WHERE variable_id = 10011
                        AND  MONTH(fecha) = ?
                        GROUP BY MONTH(fecha)', 
                        [date('m', strtotime($this->date))]
                    ); 
                    $this->summesreal10019 = 
                    DB::select(
                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                        FROM [mansfield2].[dbo].[data]
                        WHERE variable_id = 10019
                        AND  MONTH(fecha) = ?
                        GROUP BY MONTH(fecha)', 
                        [date('m', strtotime($this->date))]
                    ); 
                    $this->summesreal10039 = 
                    DB::select(
                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                        FROM [mansfield2].[dbo].[data]
                        WHERE variable_id = 10039
                        AND  MONTH(fecha) = ?
                        GROUP BY MONTH(fecha)', 
                        [date('m', strtotime($this->date))]
                    );
                    $this->summesreal10045 =
                    DB::select(
                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                        FROM [mansfield2].[dbo].[data]
                        WHERE variable_id = 10045
                        AND  MONTH(fecha) = ?
                        GROUP BY MONTH(fecha)', 
                        [date('m', strtotime($this->date))]
                    );
                    $this->summesreal10052 =
                    DB::select(
                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                        FROM [mansfield2].[dbo].[data]
                        WHERE variable_id = 10052
                        AND  MONTH(fecha) = ?
                        GROUP BY MONTH(fecha)', 
                        [date('m', strtotime($this->date))]
                    );
                    $this->summesreal10061 =
                    DB::select(
                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                        FROM [mansfield2].[dbo].[data]
                        WHERE variable_id = 10061
                        AND  MONTH(fecha) = ?
                        GROUP BY MONTH(fecha)', 
                        [date('m', strtotime($this->date))]
                    ); 
                //FIN CALCULOS REUTILIZABLES
                
                $where = ['variable.estado' => 1, 'categoria.area_id' => $this->area_id];
                $table = DB::table('variable')
                                ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                                ->join('categoria','subcategoria.categoria_id','=','categoria.id')
                                ->leftJoin('conciliado_data', function($join){
                                    $join->on('variable.id', '=', 'conciliado_data.variable_id')
                                    ->where('conciliado_data.fecha', '=', $this->date);
                                })
                                ->where($where)
                                ->select(
                                    'categoria.orden as cat_orden',
                                    'categoria.nombre as cat',
                                    'subcategoria.orden as subcat_orden',
                                    'subcategoria.nombre as subcat',
                                    'variable.id as variable_id',
                                    'variable.nombre as variable', 
                                    'variable.orden as var_orden',
                                    'variable.unidad as unidad',
                                    'variable.tipo as tipo',
                                    'conciliado_data.valor as conciliado_data'
                                    )
                                ->get();
                                
                return datatables()->of($table)  
                        ->addColumn('categoria', function($data)
                        {         
                            return '<span style="visibility: hidden">'.$data->cat_orden.'</span>'.$data->cat;
                        })
                        ->addColumn('subcategoria', function($data)
                        {         
                            return '<span style="visibility: hidden">'.$data->subcat_orden.'</span>'.$data->subcat;
                        })
                        ->addColumn('mes_real', function($data)
                        {    
                            if (in_array($data->variable_id, $this->pparray))
                            {
                                switch($data->variable_id)
                                {
                                    case 10004:                                       
                                        //promedio.ponderado.mensual(10005,10004)                    
                                        //10004	Ley Au	MMSA_TP_Ley Au	g
                                        //10005	MMSA_TP_Mineral Triturado t                          
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10004) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10005) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10005; 
                                    break;
                                    case 10010:
                                    case 10030:                                       
                                        //10010 Ley Au MMSA_HPGR_Ley Au 
                                        //Promedio Ponderado Mensual(10011 MMSA_HPGR_Mineral Triturado t, 10010 MMSA_HPGR_Ley Au g/t)                         
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10010) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10011) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma = $this->summesreal10011;
                                    break;
                                    case 10012:                                       
                                        //10012 MMSA_HPGR_P80 mm
                                        //Promedio Ponderado Mensual(10011 MMSA_HPGR_Mineral Triturado t, 10012 MMSA_HPGR_P80 mm)                      
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10012) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10011) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma = $this->summesreal10011;
                                    break;
                                    case 10015:                                       
                                        //10015 MMSA_AGLOM_Adición de Cemento (kg/t)                   
                                        //(sumatoria.mensual(10067 MMSA_AGLOM_Cemento) * 1000)/ sumatoria.mesual(10019 MMSA_AGLOM_Mineral Aglomerado t)                      
                                        $sumaproducto = DB::select(
                                            'SELECT MONTH(fecha) as month, SUM(valor) * 1000 as sumaproducto
                                            FROM [mansfield2].[dbo].[data]
                                            WHERE variable_id = 10067
                                            AND  MONTH(fecha) = ?
                                            GROUP BY MONTH(fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10019; 
                                    break;
                                    case 10018:                                         
                                        //10018 MMSA_AGLOM_Humedad %
                                        //Promedio Ponderado Mensual(10019 MMSA_AGLOM_Mineral Aglomerado t,10018 MMSA_AGLOM_Humedad %)                        
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10018) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10019) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma = $this->summesreal10019;
                                    break;
                                    case 10024:                                       
                                        //10024 MMSA_APILAM_PYS_Ley Au g/t 
                                        //Promedio Ponderado Mensual(10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t, 10024 MMSA_APILAM_PYS_Ley Au g/t)                        
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10024) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10025) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= DB::select(
                                            'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                            FROM [mansfield2].[dbo].[data]
                                            WHERE variable_id = 10025
                                            AND  MONTH(fecha) = ?
                                            GROUP BY MONTH(fecha)', 
                                            [date('m', strtotime($this->date))]
                                        ); 
                                    break;
                                    case 10033:                                         
                                        //10033 MMSA_APILAM_STACKER_Recuperación %
                                        //Promedio Ponderado Mensual(10011 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10033) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10011) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma = $this->summesreal10011; 
                                    break;
                                    case 10035:                                       
                                        //10035 MMSA_APILAM_TA_Ley Au g/t
                                        //Promedio Ponderado Mensual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10035) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10039) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10039; 
                                    break;
                                    case 10036:                                         
                                        //10036 MMSA_APILAM_TA_Recuperación %
                                        //Promedio Ponderado Mensual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10036) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10039) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10039; 
                                    break;
                                    case 10040:                                         
                                        //10040 MMSA_SART_Eficiencia %
                                        //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10040 MMSA_SART_Eficiencia %)   
                                        //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 
                                        //(((10043 MMSA_SART_Ley Cu Alimentada ppm) - (10044 MMSA_SART_Ley Cu Salida ppm)) * 100)/(10043 MMSA_SART_Ley Cu Alimentada ppm))                  
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A1.fecha),SUM((((A1.valor-A2.valor)*100)/A1.valor) * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10043
                                            AND valor <> 0 ) as A1
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10044) as A2
                                            ON A1.fecha = A2.fecha
                                            INNER JOIN
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10045) as B
                                            ON A2.fecha = B.fecha
                                            WHERE MONTH(A1.fecha) =  ?
                                            GROUP BY MONTH(A1.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10045; 
                                    break;
                                    case 10041:                                         
                                        //10041 MMSA_SART_Ley Au Alimentada ppm
                                        //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10041 MMSA_SART_Ley Au Alimentada ppm)                   
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10041) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10045) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10045;  
                                    break;
                                    case 10042:                                         
                                        //10042 MMSA_SART_Ley Au Salida ppm
                                        //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10042 MMSA_SART_Ley Au Salida ppm)                 
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10042) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10045) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10045;  
                                    break;
                                    case 10043:                                         
                                        //10043 MMSA_SART_Ley Cu Alimentada ppm
                                        //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10043 MMSA_SART_Ley Cu Alimentada ppm)                      
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10043) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10045) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10045;  
                                    break;
                                    case 10044:                                         
                                        //10044 MMSA_SART_Ley Cu Salida ppm
                                        //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10044 MMSA_SART_Ley Cu Salida ppm)                    
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10044) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10045) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10045;  
                                    break;
                                    case 10049:                                         
                                        //10049 MMSA_ADR_Eficiencia %
                                        //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 10049 MMSA_ADR_Eficiencia %)   
                                        //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 
                                        //((((10051 MMSA_ADR_Ley de Au PLS) - (10050 MMSA_ADR_Ley de Au BLS)) * 100) / (10051 MMSA_ADR_Ley de Au PLS))                  
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A1.fecha),SUM((((A1.valor-A2.valor)*100)/A1.valor) * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10051
                                            AND valor <> 0 ) as A1
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10050) as A2
                                            ON A1.fecha = A2.fecha
                                            INNER JOIN
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10052) as B
                                            ON A2.fecha = B.fecha
                                            WHERE MONTH(A1.fecha) =  ?
                                            GROUP BY MONTH(A1.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                       
                                        $suma= $this->summesreal10052; 
                                    break;
                                    case 10050:                                         
                                        //10050 MMSA_ADR_Ley de Au BLS ppm
                                        //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 10050 MMSA_ADR_Ley de Au BLS ppm)                      
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10050) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10052) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10052; 
                                    break;
                                    case 10051:                                         
                                        //10051 MMSA_ADR_Ley de Au PLS ppm
                                        //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 10051 MMSA_ADR_Ley de Au PLS ppm)                      
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10051) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10052) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10052; 
                                    break;
                                    case 10054:                                         
                                        //10054 MMSA_LIXI_CN en solución PLS ppm
                                        //Promedio Ponderado Mensual(10061 MMSA_LIXI_Solución PLS m3, 10054 MMSA_LIXI_CN en solución PLS ppm)                      
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10054) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10061) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10061; 
                                    break;
                                    case 10055:                                         
                                        //10055 MMSA_LIXI_CN Solución Barren ppm
                                        //Promedio Ponderado Mensual(10059 MMSA_LIXI_Solución Barren m3, 10055 MMSA_LIXI_CN Solución Barren ppm)                      
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10055) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10059) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= DB::select(
                                            'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                            FROM [mansfield2].[dbo].[data]
                                            WHERE variable_id = 10059
                                            AND  MONTH(fecha) = ?
                                            GROUP BY MONTH(fecha)', 
                                            [date('m', strtotime($this->date))]
                                        ); 
                                    break;
                                    case 10056:                                         
                                        //10056 MMSA_LIXI_CN Solución ILS ppm
                                        //Promedio Ponderado Mensual(10060 MMSA_LIXI_Solución ILS m3, 10056 MMSA_LIXI_CN Solución ILS ppm)                       
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10056) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10060) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= DB::select(
                                            'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                            FROM [mansfield2].[dbo].[data]
                                            WHERE variable_id = 10060
                                            AND  MONTH(fecha) = ?
                                            GROUP BY MONTH(fecha)', 
                                            [date('m', strtotime($this->date))]
                                        ); 
                                    break;
                                    case 10057:                                         
                                        //10057 MMSA_LIXI_Ley Au Solución PLS ppm
                                        //Promedio Ponderado Mensual(10061 MMSA_LIXI_Solución PLS m3, 10057 MMSA_LIXI_Ley Au Solución PLS ppm)                      
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10057) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10061) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10061; 
                                    break;
                                    case 10058:                                       
                                        //10058 MMSA_LIXI_pH en Solución PLS
                                        //Promedio Ponderado Mensual(10061 MMSA_LIXI_Solución PLS m3, 10058 MMSA_LIXI_pH en Solución PLS)                     
                                        $sumaproducto= DB::select(
                                            'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10058) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [mansfield2].[dbo].[data]
                                            where variable_id = 10061) as B
                                            ON A.fecha = B.fecha
                                            WHERE MONTH(A.fecha) =  ?
                                            GROUP BY MONTH(A.fecha)', 
                                            [date('m', strtotime($this->date))]
                                        );                                     
                                        $suma= $this->summesreal10061; 
                                    break;
                                }                            
                                if(isset($sumaproducto[0]->sumaproducto) && isset($suma[0]->suma))
                                {
                                    if ($suma[0]->suma > 0)
                                    {
                                        $m_real = $sumaproducto[0]->sumaproducto/$suma[0]->suma;
                                        if($m_real > 100)
                                        {
                                            return number_format(round($m_real), 0, '.', ',');
                                        }
                                        else
                                        {
                                            return number_format($m_real, 2, '.', ',');
                                        }
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                }
                                else
                                {
                                    return '-';
                                }
                            }
                            else
                            {
                                if (in_array($data->variable_id, $this->sumarray))
                                {
                                    switch($data->variable_id)
                                    { 
                                        case 10002:
                                            //10002: MMSA_TP_Au Triturado                  
                                            //SUMATORIA MENSUAL(((10005 MMSA_TP_Mineral Triturado t)*(10004 MMSA_TP_Ley Au g/t)) / 31.1035)                                     
                                            $mes_real = 
                                            DB::select(
                                                'SELECT MONTH(A.fecha), SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10005) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10004) as B
                                                ON A.fecha = B.fecha
                                                WHERE MONTH(A.fecha) =  ?
                                                GROUP BY MONTH(A.fecha)', 
                                                [date('m', strtotime($this->date))]
                                            ); 
                                        break;
                                        case 10008:
                                        case 10037:
                                            //10008: MMSA_TP_Au Triturado                  
                                            //SUMATORIA MENSUAL(((10011 MMSA_TP_Mineral Triturado t)*(10010 MMSA_TP_Ley Au g/t)) / 31.1035)                                     
                                            $mes_real = 
                                            DB::select(
                                                'SELECT MONTH(A.fecha), SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10011) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10010) as B
                                                ON A.fecha = B.fecha
                                                WHERE MONTH(A.fecha) =  ?
                                                GROUP BY MONTH(A.fecha)', 
                                                [date('m', strtotime($this->date))]
                                            ); 
                                        break;                   
                                        case 10022:
                                            //10022 MMSA_APILAM_PYS_Au Extraible Trituración Secundaria Apilado Camiones (oz)                  
                                            //SUMAMENSUAL((((10026 MMSA_APILAM_PYS_Recuperación %)/ 100) * (10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t) * (10024 MMSA_APILAM_PYS_Ley Au g/t)) / 31.1035)                               
                                            $mes_real = 
                                            DB::select(
                                                'SELECT MONTH(A.fecha), SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as mes_real FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10026) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10025) as B
                                                ON A.fecha = B.fecha
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10024) as C
                                                ON A.fecha = C.fecha
                                                WHERE MONTH(A.fecha) =  ?
                                                GROUP BY MONTH(A.fecha)', 
                                                [date('m', strtotime($this->date))]
                                            );
                                        break;               
                                        case 10023:
                                            //MMSA_APILAM_PYS_Au Trituración Secundaria Apilado Camiones oz                  
                                            //SUMAMENSUAL(((10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t)*(10024 MMSA_APILAM_PYS_Ley Au g/t) / 31.1035)                                    
                                            $mes_real = 
                                            DB::select(
                                                'SELECT MONTH(A.fecha), SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10025) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10024) as B
                                                ON A.fecha = B.fecha
                                                WHERE MONTH(A.fecha) =  ?
                                                GROUP BY MONTH(A.fecha)', 
                                                [date('m', strtotime($this->date))]
                                            ); 
                                        break;  
                                        case 10027:
                                            //10027: MMSA_APILAM_STACKER_Au Apilado Stacker (oz)                  
                                            //SUMATORIA MENSUAL(((10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)*(10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                                     
                                            $mes_real = 
                                            DB::select(
                                                'SELECT MONTH(A.fecha), SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10031) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10030) as B
                                                ON A.fecha = B.fecha
                                                WHERE MONTH(A.fecha) =  ?
                                                GROUP BY MONTH(A.fecha)', 
                                                [date('m', strtotime($this->date))]
                                            ); 
                                        break;
                                        case 10028:
                                        case 10038:
                                            //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                            //SUMAMENSUAL((((10033 MMSA_APILAM_STACKER_Recuperación %)* 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                               
                                            $mes_real = 
                                            DB::select(
                                                'SELECT MONTH(A.fecha), SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as mes_real FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10033) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10031) as B
                                                ON A.fecha = B.fecha
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10030) as C
                                                ON A.fecha = C.fecha
                                                WHERE MONTH(A.fecha) =  ?
                                                GROUP BY MONTH(A.fecha)', 
                                                [date('m', strtotime($this->date))]
                                            ); 
                                        break;                    
                                        case 10046:
                                            //Au Adsorbido - MMSA_ADR_Au Adsorbido (oz)                  
                                            //SUMAMENSUAL(((10052 MMSA_ADR_PLS a Carbones) * ((10051 MMSA_ADR_Ley de Au PLS)-(10050 MMSA_ADR_Ley de Au BLS))) / 31.1035)                               
                                            $mes_real = 
                                            DB::select(
                                                'SELECT MONTH(A.fecha), SUM((A.valor * (B.valor-C.valor))/31.1035) as mes_real FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10052) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10051) as B
                                                ON A.fecha = B.fecha
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10050) as C
                                                ON A.fecha = C.fecha
                                                WHERE MONTH(A.fecha) =  ?
                                                GROUP BY MONTH(A.fecha)', 
                                                [date('m', strtotime($this->date))]
                                            );
                                        break; 
                                        case 10053:
                                            //MMSA_LIXI_Au Lixiviado (oz)                  
                                            //SUMATORIA MENSUAL(((10061 MMSA_LIXI_Solución PLS)*(10057 MMSA_LIXI_Ley Au Solución PLS) / 31.1035 )                                     
                                            $mes_real = 
                                            DB::select(
                                                'SELECT MONTH(A.fecha), SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10061) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [mansfield2].[dbo].[data]
                                                where variable_id = 10057) as B
                                                ON A.fecha = B.fecha
                                                WHERE MONTH(A.fecha) =  ?
                                                GROUP BY MONTH(A.fecha)', 
                                                [date('m', strtotime($this->date))]
                                            ); 
                                        break;
                                        default:
                                            $mes_real= DB::select(
                                                'SELECT MONTH(fecha) as month, SUM(valor) as mes_real
                                                FROM [mansfield2].[dbo].[data]
                                                WHERE variable_id = ?
                                                AND  MONTH(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [$data->variable_id, date('m', strtotime($this->date))]
                                            );                               
                                        break; 
                                    }
                                    if(isset($mes_real[0]->mes_real))
                                    {
                                        $m_real = $mes_real[0]->mes_real;
                                        return number_format($m_real, 6, '.', ',');                                    
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                }
                                else
                                {
                                    if (in_array($data->variable_id, $this->promarray))//revisar si variable 10016 corresponde a este grupo
                                    {
                                        //Promedio valores <>0
                                        $mes_real= DB::select(
                                            'SELECT MONTH(fecha) as month, AVG(valor) as mes_real
                                            FROM [mansfield2].[dbo].[data]
                                            WHERE variable_id = ?
                                            AND  MONTH(fecha) = ?
                                            AND valor <> 0 
                                            GROUP BY MONTH(fecha)', 
                                            [$data->variable_id, date('m', strtotime($this->date))]
                                        );  
                                        if(isset($mes_real[0]->mes_real))
                                        {
                                            $m_real = $mes_real[0]->mes_real;
                                            return number_format($m_real, 6, '.', ',');                                        
                                        }
                                        else
                                        {
                                            return '-';
                                        }

                                    }
                                    else
                                    {
                                        if (in_array($data->variable_id, $this->divarray))
                                        {
                                            switch($data->variable_id)
                                            { 
                                                case 10006:                                       
                                                    //10006	MMSA_TP_Productividad t/h                    
                                                    //sumatoria.mensual(10005 MMSA_TP_Mineral Triturado t)/sumatoria.mesual(10062 MMSA_TP_Horas Operativas Trituración Primaria h)                         
                                                    $suma= $this->summesreal10005;                                     
                                                    $suma2= DB::select(
                                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                        FROM [mansfield2].[dbo].[data]
                                                        WHERE variable_id = 10062
                                                        AND  MONTH(fecha) = ?
                                                        GROUP BY MONTH(fecha)', 
                                                        [date('m', strtotime($this->date))]
                                                    ); 
                                                break;
                                                case 10013:                                       
                                                    //10013 MMSA_HPGR_Productividad t/h                   
                                                    //sumatoria.mensual(10011 MMSA_HPGR_Mineral Triturado t)/ sumatoria.mesual(10063 MMSA_HPGR_Horas Operativas Trituración Terciaria h)                      
                                                    $suma= $this->summesreal10011;                                     
                                                    $suma2= DB::select(
                                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                        FROM [mansfield2].[dbo].[data]
                                                        WHERE variable_id = 10063
                                                        AND  MONTH(fecha) = ?
                                                        GROUP BY MONTH(fecha)', 
                                                        [date('m', strtotime($this->date))]
                                                    ); 
                                                break;
                                                case 10020:                                       
                                                    //10020 MMSA_AGLOM_Productividad t/h                  
                                                    //sumatoria.mensual(10019 MMSA_AGLOM_Mineral Aglomerado t)/ sumatoria.mensual(10064 MMSA_AGLOM_Horas Operativas Aglomeración h)                      
                                                    $suma= $this->summesreal10019;                                     
                                                    $suma2= DB::select(
                                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                        FROM [mansfield2].[dbo].[data]
                                                        WHERE variable_id = 10064
                                                        AND  MONTH(fecha) = ?
                                                        GROUP BY MONTH(fecha)', 
                                                        [date('m', strtotime($this->date))]
                                                    ); 
                                                break;
                                                case 10032:                                       
                                                    //10032 MMSA_APILAM_STACKER_Productividad t/h                 
                                                    //sumatoria.mensual(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)/ sumatoria.mensual(10065 MMSA_APILAM_STACKER_Tiempo Operativo h)                      
                                                    $suma= DB::select(
                                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                        FROM [mansfield2].[dbo].[data]
                                                        WHERE variable_id = 10031
                                                        AND  MONTH(fecha) = ?
                                                        GROUP BY MONTH(fecha)', 
                                                        [date('m', strtotime($this->date))]
                                                    );                                     
                                                    $suma2= DB::select(
                                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                        FROM [mansfield2].[dbo].[data]
                                                        WHERE variable_id = 10065
                                                        AND  MONTH(fecha) = ?
                                                        GROUP BY MONTH(fecha)', 
                                                        [date('m', strtotime($this->date))]
                                                    ); 
                                                break;
                                            }                            
                                            if(isset($suma[0]->suma) && isset($suma2[0]->suma))
                                            {
                                                if ($suma2[0]->suma > 0)
                                                {
                                                    $m_real = $suma[0]->suma/$suma2[0]->suma;
                                                    return number_format($m_real, 6, '.', ',');
                                                    
                                                }
                                                else
                                                {
                                                    return '-';
                                                }
                                            }
                                            else
                                            {
                                                return '-';
                                            }
                                        }
                                        else
                                        {
                                            return $data->variable_id;
                                        }
                                    }
                                }
                            } 
                        })
                        ->rawColumns(['categoria','subcategoria','mes_real'])
                        ->addIndexColumn()
                        ->make(true);
            }
            else
            {
                $this->ley = 
                    [10071, 10074, 10077, 10080, 10083, 10086, 10089, 10094, 10098, 10101, 10104, 10107]; 
                    
                $this->varNoCalculadas = [10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113, 10072, 10075, 10078, 10081, 10084, 10090, 10095, 10099, 10102, 10105, 10108, 10114, 10115, 10116];
                
                //INICIO CALCULOS REUTILIZABLES
                    $year = (int)date('Y', strtotime($this->date));
                    $month = (int)date('m', strtotime($this->date)); 
                    $daypart = (int)date('z', strtotime($this->date)) + 1;
                    //INICIO MES REAL
                        $this->summesrealton = 
                        DB::select(
                            'SELECT v.id AS variable_id, d.mes_real AS mes_real FROM
                            (SELECT variable_id, SUM(valor) AS mes_real
                            FROM [mansfield2].[dbo].[data] 
                            WHERE variable_id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)
                            AND MONTH(fecha) = '.$month.'
                            AND DATEPART(y, fecha) <= '.$daypart.'
                            AND YEAR(fecha) = '.$year.'
                            GROUP BY variable_id) AS d
                            RIGHT JOIN
                            (SELECT id 
                            FROM [mansfield2].[dbo].[variable] 
                            WHERE id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)) AS v
                            ON d.variable_id = v.id
                            ORDER BY id ASC'
                        );
                        $this->summesrealonz =
                        DB::select(
                            'SELECT 10072 as variable_id, SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10070) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10071) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'
                            UNION 
                            SELECT 10075, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10073) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10074) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'
                            UNION 
                            SELECT 10078, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10076) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10077) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'
                            UNION 
                            SELECT 10081, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10079) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10080) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'UNION 
                            SELECT 10084, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10082) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10083) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'
                            UNION 
                            SELECT 10087, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10085) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10086) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'
                            UNION 
                            SELECT 10090, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10088) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10089) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'
                            UNION 
                            SELECT 10095, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10093) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10094) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'
                            UNION 
                            SELECT 10099, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10097) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10098) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'
                            UNION 
                            SELECT 10102, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10100) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10101) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'
                            UNION 
                            SELECT 10105, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10103) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10104) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.'
                            UNION 
                            SELECT 10108, SUM((A.valor * B.valor)/31.1035) FROM
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10106) as A
                            INNER JOIN   
                            (SELECT fecha, valor
                            FROM [mansfield2].[dbo].[data]
                            where variable_id = 10107) as B
                            ON A.fecha = B.fecha
                            WHERE MONTH(A.fecha) = '.$month.'
                            AND DATEPART(y, A.fecha) <= '.$daypart.'
                            AND YEAR(A.fecha) = '.$year.''
                        );
    
                        $this->avgmesrealpor =
                        DB::select(
                            'SELECT v.id AS variable_id, d.mes_real AS mes_real FROM
                            (SELECT variable_id, AVG(valor) AS mes_real
                            FROM [mansfield2].[dbo].[data]
                            WHERE variable_id IN (10114,10115,10116)
                            AND  MONTH(fecha) = '.$month.'
                            AND  DATEPART(y, fecha) <= '.$daypart.'
                            AND YEAR(fecha) = '.$year.'
                            AND valor <> 0
                            GROUP BY variable_id) AS d
                            RIGHT JOIN
                            (SELECT id 
                            FROM [mansfield2].[dbo].[variable] 
                            WHERE id IN (10114,10115,10116)) AS v
                            ON d.variable_id = v.id
                            ORDER BY id ASC'
                        );                
                    //FIN MES REAL
                //FIN CALCULOS REUTILIZABLES

                $where = ['variable.estado' => 1, 'categoria.area_id' => $this->area_id];
                $table = DB::table('variable')
                                ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                                ->join('categoria','subcategoria.categoria_id','=','categoria.id')
                                ->leftJoin('conciliado_data', function($join){
                                    $join->on('variable.id', '=', 'conciliado_data.variable_id')
                                    ->where('conciliado_data.fecha', '=', $this->date);
                                })
                                ->where($where)
                                ->whereIn('variable.id', $this->varNoCalculadas)
                                ->select(
                                    'categoria.orden as cat_orden',
                                    'categoria.nombre as cat',
                                    'subcategoria.orden as subcat_orden',
                                    'subcategoria.nombre as subcat',
                                    'variable.id as variable_id',
                                    'variable.nombre as variable', 
                                    'variable.orden as var_orden',
                                    'variable.unidad as unidad',
                                    'variable.tipo as tipo',
                                    'conciliado_data.valor as conciliado_data'
                                    )
                                ->get();
                                
                return datatables()->of($table)  
                        ->addColumn('categoria', function($data)
                        {         
                            return '<span style="visibility: hidden">'.$data->cat_orden.'</span>'.$data->cat;
                        })
                        ->addColumn('subcategoria', function($data)
                        {         
                            return '<span style="visibility: hidden">'.$data->subcat_orden.'</span>'.$data->subcat;
                        })
                        ->addColumn('mes_real', function($data)
                        { 
                            $mes_real = [];
                            switch($data->variable_id)
                            {
                                case 10070: 
                                    $mes_real = $this->summesrealton[0];
                                break;
                                case 10071:
                                    if(isset($this->summesrealonz[0]->mes_real) && isset($this->summesrealton[0]->mes_real))
                                    {
                                        $au = $this->summesrealonz[0]->mes_real;
                                        $min = $this->summesrealton[0]->mes_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    }                             
                                break;
                                case 10072:
                                    $mes_real = $this->summesrealonz[0];
                                break;
                                case 10073: 
                                    $mes_real = $this->summesrealton[1];
                                break;
                                case 10074:
                                    if(isset($this->summesrealonz[1]->mes_real) && isset($this->summesrealton[1]->mes_real))
                                    {
                                        $au = $this->summesrealonz[1]->mes_real;
                                        $min = $this->summesrealton[1]->mes_real;
                                    }     
                                    else
                                    {
                                        $min = 0;
                                    }                              
                                break;
                                case 10075:
                                    $mes_real = $this->summesrealonz[1];
                                break;                            
                                case 10076: 
                                    $mes_real = $this->summesrealton[2];
                                break;
                                case 10077:
                                    if(isset($this->summesrealonz[2]->mes_real) && isset($this->summesrealton[2]->mes_real))
                                    {
                                        $au = $this->summesrealonz[2]->mes_real;
                                        $min = $this->summesrealton[2]->mes_real;
                                    }  
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10078:
                                    $mes_real = $this->summesrealonz[2];
                                break;
                                case 10079: 
                                    $mes_real = $this->summesrealton[3];
                                break;
                                case 10080:
                                    if(isset($this->summesrealonz[3]->mes_real) && isset($this->summesrealton[3]->mes_real))
                                    {
                                        $au = $this->summesrealonz[3]->mes_real;
                                        $min = $this->summesrealton[3]->mes_real;
                                    }  
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10081:
                                    $mes_real = $this->summesrealonz[3];
                                break;
                                case 10082: 
                                    $mes_real = $this->summesrealton[4];
                                break;
                                case 10083:
                                    if(isset($this->summesrealonz[4]->mes_real) && isset($this->summesrealton[4]->mes_real))
                                    {
                                        $au = $this->summesrealonz[4]->mes_real;
                                        $min = $this->summesrealton[4]->mes_real;
                                    }  
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10084:
                                    $mes_real = $this->summesrealonz[4];
                                break;
                                case 10085: 
                                    $mes_real = $this->summesrealton[5];
                                break;
                                case 10086:
                                    if(isset($this->summesrealonz[5]->mes_real) && isset($this->summesrealton[5]->mes_real))
                                    {
                                        $au = $this->summesrealonz[5]->mes_real;
                                        $min = $this->summesrealton[5]->mes_real;
                                    }  
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10087:
                                    $mes_real = $this->summesrealonz[5];
                                break;
                                case 10088: 
                                    $mes_real = $this->summesrealton[6];
                                break;
                                case 10089:
                                    if(isset($this->summesrealonz[6]->mes_real) && isset($this->summesrealton[6]->mes_real))
                                    {
                                        $au = $this->summesrealonz[6]->mes_real;
                                        $min = $this->summesrealton[6]->mes_real;
                                    }  
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10090:
                                    $mes_real = $this->summesrealonz[6];
                                break;
                                case 10091: 
                                    $mes_real = $this->summesrealton[7];
                                break;
                                case 10092: 
                                    $mes_real = $this->summesrealton[8];
                                break;
                                case 10093: 
                                    $mes_real = $this->summesrealton[9];
                                break;
                                case 10094:
                                    if(isset($this->summesrealonz[7]->mes_real) && isset($this->summesrealton[9]->mes_real))
                                    {
                                        $au = $this->summesrealonz[7]->mes_real;
                                        $min = $this->summesrealton[9]->mes_real;
                                    }  
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10095:
                                    $mes_real = $this->summesrealonz[7];
                                break;
                                case 10097: 
                                    $mes_real = $this->summesrealton[10];
                                break;
                                case 10098:
                                    if(isset($this->summesrealonz[8]->mes_real) && isset($this->summesrealton[10]->mes_real))
                                    {
                                        $au = $this->summesrealonz[8]->mes_real;
                                        $min = $this->summesrealton[10]->mes_real;
                                    }  
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10099:
                                    $mes_real = $this->summesrealonz[8];
                                break;
                                case 10100: 
                                    $mes_real = $this->summesrealton[11];
                                break;
                                case 10101:
                                    if(isset($this->summesrealonz[9]->mes_real) && isset($this->summesrealton[11]->mes_real))
                                    {
                                        $au = $this->summesrealonz[9]->mes_real;
                                        $min = $this->summesrealton[11]->mes_real;
                                    }  
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10102:
                                    $mes_real = $this->summesrealonz[9];
                                break;
                                case 10103: 
                                    $mes_real = $this->summesrealton[12];
                                break;
                                case 10104:
                                    if(isset($this->summesrealonz[10]->mes_real) && isset($this->summesrealton[12]->mes_real))
                                    {
                                        $au = $this->summesrealonz[10]->mes_real;
                                        $min = $this->summesrealton[12]->mes_real;
                                    }  
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10105:
                                    $mes_real = $this->summesrealonz[10];
                                break;
                                case 10106: 
                                    $mes_real = $this->summesrealton[13];
                                break;
                                case 10107:
                                    if(isset($this->summesrealonz[11]->mes_real) && isset($this->summesrealton[13]->mes_real))
                                    {
                                        $au = $this->summesrealonz[11]->mes_real;
                                        $min = $this->summesrealton[13]->mes_real;
                                    }  
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10108:
                                    $mes_real = $this->summesrealonz[11];
                                break;
                                case 10109: 
                                    $mes_real = $this->summesrealton[14];
                                break;
                                case 10110: 
                                    $mes_real = $this->summesrealton[15];
                                break;
                                case 10111: 
                                    $mes_real = $this->summesrealton[16];
                                break;
                                case 10112: 
                                    $mes_real = $this->summesrealton[17];
                                break;
                                case 10113: 
                                    $mes_real = $this->summesrealton[18];
                                break;
                                case 10114:
                                    $mes_real = $this->avgmesrealpor[0];
                                break;
                                case 10115:
                                    $mes_real = $this->avgmesrealpor[1];
                                break;
                                case 10116:
                                    $mes_real = $this->avgmesrealpor[2];
                                break;
                                default:
                                    return '-';
                                break;
                            }
                            if (in_array($data->variable_id, $this->ley))
                            {
                                if ($min > 0)
                                {
                                    $ley = ($au*31.1035)/$min;
                                    return number_format($ley, 2, '.', ',');
                                }
                                else
                                {
                                    return '-';
                                }
                            }
                            if(isset($mes_real->mes_real))
                            {
                                $m_real = $mes_real->mes_real;
                                if($m_real > 100)
                                {
                                    return number_format(round($m_real), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($m_real, 2, '.', ',');
                                }
                            }
                            else
                            {
                                return '-';
                            }
                        })
                        ->rawColumns(['categoria','subcategoria','mes_real'])
                        ->addIndexColumn()
                        ->make(true);

            }
        } 
    }

    public function load(Request $request)
    {       
        $validator = Validator::make(
            $request->all(),
            [   
                'month'    => 'required|numeric',
                'conciliado_load' => 'required|array',
                'conciliado_load.*.variable_id' => 'required|exists:variable,id',
                'conciliado_load.*.value' => 'nullable',
            ]            
        );
        if ($validator->fails()) 
        {
            return response()->json(['error'=>$validator->errors()->all()]);
        }
        else
        {
            $date = date('Y-m-t',strtotime(date('Y').'-'.$request->get('month').'-01'));
            foreach($request->get('conciliado_load') as $variable)
            {
                $where = ['variable_id' => $variable['variable_id'], 'fecha' => $date];
                $row = DB::table('conciliado_data')
                    ->where($where)
                    ->first();
                if (isset($row->id))
                {
                    $conciliado_reg = ConciliadoData::findOrFail($row->id);
                    $oldvalue=$conciliado_reg->valor;
                    $conciliado_reg->update([
                        'valor' => $variable['value']
                    ]);
                    $transaccion ="EDIT";
                    if (number_format($oldvalue, 2) !=  number_format($variable['value'], 2))
                    {                    
                        ConciliadoHistorial::create([
                            'conciliado_data_id' => $conciliado_reg->id,
                            'fecha' => date('Y-m-d H:i:s'),
                            'transaccion' => $transaccion,
                            'valorviejo' => $oldvalue,
                            'valornuevo' => $variable['value'],
                            'usuario' => auth()->user()->name
                        ]);
                    }
                }
                else
                {
                    if ($variable['value'] != null)
                    {
                        $conciliado_reg = ConciliadoData::create([
                            'variable_id' => $variable['variable_id'],
                            'fecha' => date('Y-m-t',strtotime(date('Y').'-'.$request->get('month').'-01')),
                            'valor' => $variable['value']
                        ]);
                        $transaccion ="CREATE";
                        $oldvalue= null;

                        ConciliadoHistorial::create([
                            'conciliado_data_id' => $conciliado_reg->id,
                            'fecha' => date('Y-m-d H:i:s'),
                            'transaccion' => $transaccion,
                            'valorviejo' => $oldvalue,
                            'valornuevo' => $variable['value'],
                            'usuario' => auth()->user()->name
                        ]);
                    }
                }


                
            }               
        }                
    } 
}
