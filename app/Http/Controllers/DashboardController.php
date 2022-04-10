<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Data;
use App\Models\Historial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index(Request $request)
    { 
        return view('dashboard');

    }

    public function procesostable(Request $request)
    {
        if(request()->ajax()) {
            $this->date = $request->get('fecha');
            $this->pparray = 
            [10004, 10010, 10012, 10015, 10016, 10018, 10024, 10030, 10033, 10035, 10036, 
             10040, 10041, 10042, 10043, 10044, 10049, 10050, 10051, 10054, 10055, 
             10056, 10057, 10058];
            $this->sumarray = 
            [10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 
             10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 
             10059, 10060, 10061];
            $this->promarray = 
             [10003, 10007, 10009, 10014, 10017, 10021, 10026, 10029, 10034];
            $this->divarray = 
              [10006, 10013, 10020, 10032];
            //MES REAL
            $this->summesreal10005 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10005
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesreal10011 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10011
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->summesreal10019 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10019
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->summesreal10039 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10039
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesreal10045 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10045
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesreal10052 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10052
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesreal10061 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10061
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            //MES BUDGET
            $this->summesbudget10005 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10005
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->summesbudget10011 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10011
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->summesbudget10019 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10019
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesbudget10039 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10039
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesbudget10045 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10045
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesbudget10052 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10052
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesbudget10061 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10061
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            //Trimestre Real
            $this->sumtrireal10005 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10005
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtrireal10011 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10011
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtrireal10019 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10019
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtrireal10039 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10039
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtrireal10045 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10045
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtrireal10052 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10052
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtrireal10061 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10061
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            //Trimestre Budget
            $this->sumtribudget10005 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10005
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtribudget10011 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10011
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtribudget10019 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10019
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtribudget10039 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10039
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtribudget10045 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10045
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtribudget10052 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10052
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtribudget10061 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10061
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            //Anio Real
            $this->sumanioreal10005 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10005
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumanioreal10011 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10011
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumanioreal10019 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10019
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumanioreal10039 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10039
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumanioreal10045 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10045
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumanioreal10052 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10052
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumanioreal10061 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[data]
                WHERE variable_id = 10061
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            //Anio Budget
            $this->sumaniobudget10005 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10005
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumaniobudget10011 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10011
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumaniobudget10019 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10019
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumaniobudget10039 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10039
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumaniobudget10045 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10045
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumaniobudget10052 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10052
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumaniobudget10061 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [mansfield].[dbo].[budget]
                WHERE variable_id = 10061
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $where = ['variable.estado' => 1, 'categoria.area_id' => 1, 'categoria.estado' => 1, 'subcategoria.estado' => 1, 'data.fecha' => $this->date];
            $table = DB::table('data')
                            ->join('variable','data.variable_id','=','variable.id')
                            ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                            ->join('categoria','subcategoria.categoria_id','=','categoria.id')
                            ->leftJoin('budget', function($join){
                                $join->on('data.variable_id', '=', 'budget.variable_id');
                                $join->on('data.fecha', '=', 'budget.fecha');
                            })
                            ->where($where)
                            ->select(
                                'data.id as id',
                                'data.variable_id as variable_id',
                                'data.fecha as fecha',
                                'categoria.orden as cat_orden',
                                'categoria.nombre as cat',
                                'subcategoria.orden as subcat_orden',
                                'subcategoria.nombre as subcat',
                                'variable.id as variable_id',
                                'variable.nombre as variable', 
                                'variable.orden as var_orden',
                                'variable.unidad as unidad',
                                'data.valor as dia_real',
                                'budget.valor as dia_budget',
                                'data.valor as anio_budget'
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
                    ->addColumn('dia_real', function($data)
                    {         
                        if(isset($data->dia_real)) 
                        {            
                            $d_real = $data->dia_real;
                            if($d_real > 100)
                            {
                                return number_format(round($d_real), 0, ',', '.');
                            }
                            else
                            {
                                return number_format($d_real, 2, ',', '.');
                            }
                        }
                        else
                        {
                            return '-';
                        }
                    })
                    ->addColumn('dia_budget', function($data)
                    {           
                        if(isset($data->dia_budget))
                        {
                            $d_budget = $data->dia_budget;
                            if($d_budget > 100)
                            {
                                return number_format(round($d_budget), 0, ',', '.');
                            }
                            else
                            {
                                return number_format($d_budget, 2, ',', '.');
                            }
                        }
                        else
                        {
                            return '-' ;
                        }
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
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10004) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10005) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
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
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10010) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->summesreal10011;
                                break;
                                case 10012:                                       
                                    //10012 MMSA_HPGR_P80 mm
                                    //Promedio Ponderado Mensual(10011 MMSA_HPGR_Mineral Triturado t, 10012 MMSA_HPGR_P80 mm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10012) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->summesreal10011;
                                break;
                                case 10015:                                         
                                    //10015 MMSA_AGLOM_Adición de Cemento kg/t
                                    //Promedio Ponderado Mensual(10019 MMSA_AGLOM_Mineral Aglomerado t, 10015 MMSA_AGLOM_Adición de Cemento kg/t)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10015) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->summesreal10019;
                                break;
                                case 10016:                                         
                                    //10016 MMSA_AGLOM_Adición de CN ppm
                                    //Promedio Ponderado Mensual(10066 MMSA_AGLOM_Flujo, 10016 MMSA_AGLOM_Adición de CN ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10016) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10066) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10066
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY MONTH(fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10018:                                         
                                    //10018 MMSA_AGLOM_Humedad %
                                    //Promedio Ponderado Mensual(10019 MMSA_AGLOM_Mineral Aglomerado t,10018 MMSA_AGLOM_Humedad %)                        
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10018) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->summesreal10019;
                                break;
                                case 10024:                                       
                                    //10024 MMSA_APILAM_PYS_Ley Au g/t 
                                    //Promedio Ponderado Mensual(10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t, 10024 MMSA_APILAM_PYS_Ley Au g/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10024) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10025) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10025
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY MONTH(fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10033:                                         
                                    //10033 MMSA_APILAM_STACKER_Recuperación %
                                    //Promedio Ponderado Mensual(10011 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10033) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->summesreal10011; 
                                break;
                                case 10035:                                       
                                    //10035 MMSA_APILAM_TA_Ley Au g/t
                                    //Promedio Ponderado Mensual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10035) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10039; 
                                break;
                                case 10036:                                         
                                    //10036 MMSA_APILAM_TA_Recuperación %
                                    //Promedio Ponderado Mensual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10036) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10039; 
                                break;
                                case 10040:                                         
                                    //10040 MMSA_SART_Eficiencia %
                                    //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10040 MMSA_SART_Eficiencia %)                     
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10040) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10045; 
                                break;
                                case 10041:                                         
                                    //10041 MMSA_SART_Ley Au Alimentada ppm
                                    //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10041 MMSA_SART_Ley Au Alimentada ppm)                   
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10041) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10045;  
                                break;
                                case 10042:                                         
                                    //10042 MMSA_SART_Ley Au Salida ppm
                                    //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10042 MMSA_SART_Ley Au Salida ppm)                 
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10042) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10045;  
                                break;
                                case 10043:                                         
                                    //10043 MMSA_SART_Ley Cu Alimentada ppm
                                    //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10043 MMSA_SART_Ley Cu Alimentada ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10043) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10045;  
                                break;
                                case 10044:                                         
                                    //10044 MMSA_SART_Ley Cu Salida ppm
                                    //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10044 MMSA_SART_Ley Cu Salida ppm)                    
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10044) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10045;  
                                break;
                                case 10049:                                         
                                    //10049 MMSA_ADR_Eficiencia %
                                    //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 10049 MMSA_ADR_Eficiencia %)                    
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10049) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10052; 
                                break;
                                case 10050:                                         
                                    //10050 MMSA_ADR_Ley de Au BLS ppm
                                    //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 10050 MMSA_ADR_Ley de Au BLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10050) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10052; 
                                break;
                                case 10051:                                         
                                    //10051 MMSA_ADR_Ley de Au PLS ppm
                                    //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 10051 MMSA_ADR_Ley de Au PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10051) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10052; 
                                break;
                                case 10054:                                         
                                    //10054 MMSA_LIXI_CN en solución PLS ppm
                                    //Promedio Ponderado Mensual(10061 MMSA_LIXI_Solución PLS m3, 10054 MMSA_LIXI_CN en solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10054) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10061; 
                                break;
                                case 10055:                                         
                                    //10055 MMSA_LIXI_CN Solución Barren ppm
                                    //Promedio Ponderado Mensual(10059 MMSA_LIXI_Solución Barren m3, 10055 MMSA_LIXI_CN Solución Barren ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10055) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10059) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10059
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY MONTH(fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10056:                                         
                                    //10056 MMSA_LIXI_CN Solución ILS ppm
                                    //Promedio Ponderado Mensual(10060 MMSA_LIXI_Solución ILS m3, 10056 MMSA_LIXI_CN Solución ILS ppm)                       
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10056) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10060) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10060
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY MONTH(fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10057:                                         
                                    //10057 MMSA_LIXI_Ley Au Solución PLS ppm
                                    //Promedio Ponderado Mensual(10061 MMSA_LIXI_Solución PLS m3, 10057 MMSA_LIXI_Ley Au Solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10057) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesreal10061; 
                                break;
                                case 10058:                                       
                                    //10058 MMSA_LIXI_pH en Solución PLS
                                    //Promedio Ponderado Mensual(10061 MMSA_LIXI_Solución PLS m3, 10058 MMSA_LIXI_pH en Solución PLS)                     
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10058) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
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
                                        return number_format(round($m_real), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($m_real, 2, ',', '.');
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
                                $mes_real= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as mes_real
                                    FROM [mansfield].[dbo].[data]
                                    WHERE variable_id = ?
                                    AND  MONTH(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    GROUP BY MONTH(fecha)', 
                                    [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );
                                if(isset($mes_real[0]->mes_real))
                                {
                                    $m_real = $mes_real[0]->mes_real;
                                    if($m_real > 100)
                                    {
                                        return number_format(round($m_real), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($m_real, 2, ',', '.');
                                    }
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
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = ?
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND valor <> 0 
                                        GROUP BY MONTH(fecha)', 
                                        [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );  
                                    if(isset($mes_real[0]->mes_real))
                                    {
                                        $m_real = $mes_real[0]->mes_real;
                                        if($m_real > 100)
                                        {
                                            return number_format(round($m_real), 0, ',', '.');
                                        }
                                        else
                                        {
                                            return number_format($m_real, 2, ',', '.');
                                        }
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
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10062
                                                    AND  MONTH(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY MONTH(fecha)', 
                                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10013:                                       
                                                //10013 MMSA_HPGR_Productividad t/h                   
                                                //sumatoria.mensual(10011 MMSA_HPGR_Mineral Triturado t)/ sumatoria.mesual(10063 MMSA_HPGR_Horas Operativas Trituración Terciaria h)                      
                                                $suma= $this->summesreal10011;                                     
                                                $suma2= DB::select(
                                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10063
                                                    AND  MONTH(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY MONTH(fecha)', 
                                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10020:                                       
                                                //10020 MMSA_AGLOM_Productividad t/h                  
                                                //sumatoria.mensual(10019 MMSA_AGLOM_Mineral Aglomerado t)/ sumatoria.mensual(10064 MMSA_AGLOM_Horas Operativas Aglomeración h)                      
                                                $suma= $this->summesreal10019;                                     
                                                $suma2= DB::select(
                                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10064
                                                    AND  MONTH(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY MONTH(fecha)', 
                                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10032:                                       
                                                //10032 MMSA_APILAM_STACKER_Productividad t/h                 
                                                //sumatoria.mensual(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)/ sumatoria.mensual(10065 MMSA_APILAM_STACKER_Tiempo Operativo h)                      
                                                $suma= DB::select(
                                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10031
                                                    AND  MONTH(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY MONTH(fecha)', 
                                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                );                                     
                                                $suma2= DB::select(
                                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10065
                                                    AND  MONTH(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY MONTH(fecha)', 
                                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                        }                            
                                        if(isset($suma[0]->suma) && isset($suma2[0]->suma))
                                        {
                                            if ($suma2[0]->suma > 0)
                                            {
                                                $m_real = $suma[0]->suma/$suma2[0]->suma;
                                                if($m_real > 100)
                                                {
                                                    return number_format(round($m_real), 0, ',', '.');
                                                }
                                                else
                                                {
                                                    return number_format($m_real, 2, ',', '.');
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
                                        return $data->variable_id;
                                    }
                                }
                            }
                        } 
                    })
                    ->addColumn('mes_budget', function($data)
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
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10004) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10005) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10005
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY MONTH(fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10010:
                                case 10030:                                       
                                    //10010 Ley Au MMSA_HPGR_Ley Au 
                                    //Promedio Ponderado Mensual(10011 MMSA_HPGR_Mineral Triturado t, 10010 MMSA_HPGR_Ley Au g/t)                         
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10010) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->summesbudget10011;
                                break;
                                case 10012:                                       
                                    //10012 MMSA_HPGR_P80 mm
                                    //Promedio Ponderado Mensual(10011 MMSA_HPGR_Mineral Triturado t, 10012 MMSA_HPGR_P80 mm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10012) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->summesbudget10011;
                                break;
                                case 10015:                                         
                                    //10015 MMSA_AGLOM_Adición de Cemento kg/t
                                    //Promedio Ponderado Mensual(10019 MMSA_AGLOM_Mineral Aglomerado t, 10015 MMSA_AGLOM_Adición de Cemento kg/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10015) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->summesbudget10019;
                                break;
                                case 10016:
                                    $mes_budget= DB::select(
                                        'SELECT MONTH(fecha) as month, AVG(valor) as mes_budget
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = ?
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND valor <> 0 
                                        GROUP BY MONTH(fecha)', 
                                        [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );  
                                    if(isset($mes_budget[0]->mes_budget))
                                    {
                                        $m_budget = $mes_budget[0]->mes_budget;
                                        if($m_budget > 100)
                                        {
                                            return number_format(round($m_budget), 0, ',', '.');
                                        }
                                        else
                                        {
                                            return number_format($m_budget, 2, ',', '.');
                                        }
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10018:                                         
                                    //10018 MMSA_AGLOM_Humedad %
                                    //Promedio Ponderado Mensual(10019 MMSA_AGLOM_Mineral Aglomerado t,10018 MMSA_AGLOM_Humedad %)                        
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10018) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->summesbudget10019;
                                break;
                                case 10024:                                       
                                    //10024 MMSA_APILAM_PYS_Ley Au g/t 
                                    //Promedio Ponderado Mensual(10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t, 10024 MMSA_APILAM_PYS_Ley Au g/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10024) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10025) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10025
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY MONTH(fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10033:                                         
                                    //10033 MMSA_APILAM_STACKER_Recuperación %
                                    //Promedio Ponderado Mensual(10011 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10033) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->summesbudget10011; 
                                break;
                                case 10035:                                       
                                    //10035 MMSA_APILAM_TA_Ley Au g/t
                                    //Promedio Ponderado Mensual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10035) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10039; 
                                break;
                                case 10036:                                         
                                    //10036 MMSA_APILAM_TA_Recuperación %
                                    //Promedio Ponderado Mensual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10036) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10039; 
                                break;
                                case 10040:                                         
                                    //10040 MMSA_SART_Eficiencia %
                                    //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10040 MMSA_SART_Eficiencia %)                     
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10040) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10045; 
                                break;
                                case 10041:                                         
                                    //10041 MMSA_SART_Ley Au Alimentada ppm
                                    //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10041 MMSA_SART_Ley Au Alimentada ppm)                   
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10041) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10045;  
                                break;
                                case 10042:                                         
                                    //10042 MMSA_SART_Ley Au Salida ppm
                                    //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10042 MMSA_SART_Ley Au Salida ppm)                 
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10042) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10045;  
                                break;
                                case 10043:                                         
                                    //10043 MMSA_SART_Ley Cu Alimentada ppm
                                    //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10043 MMSA_SART_Ley Cu Alimentada ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10043) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10045;  
                                break;
                                case 10044:                                         
                                    //10044 MMSA_SART_Ley Cu Salida ppm
                                    //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 10044 MMSA_SART_Ley Cu Salida ppm)                    
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10044) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10045;  
                                break;
                                case 10049:                                         
                                    //10049 MMSA_ADR_Eficiencia %
                                    //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 10049 MMSA_ADR_Eficiencia %)                    
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10049) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10052; 
                                break;
                                case 10050:                                         
                                    //10050 MMSA_ADR_Ley de Au BLS ppm
                                    //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 10050 MMSA_ADR_Ley de Au BLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10050) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10052; 
                                break;
                                case 10051:                                         
                                    //10051 MMSA_ADR_Ley de Au PLS ppm
                                    //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 10051 MMSA_ADR_Ley de Au PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10051) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10052; 
                                break;
                                case 10054:                                         
                                    //10054 MMSA_LIXI_CN en solución PLS ppm
                                    //Promedio Ponderado Mensual(10061 MMSA_LIXI_Solución PLS m3, 10054 MMSA_LIXI_CN en solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10054) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10061; 
                                break;
                                case 10055:                                         
                                    //10055 MMSA_LIXI_CN Solución Barren ppm
                                    //Promedio Ponderado Mensual(10059 MMSA_LIXI_Solución Barren m3, 10055 MMSA_LIXI_CN Solución Barren ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10055) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10059) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10059
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY MONTH(fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10056:                                         
                                    //10056 MMSA_LIXI_CN Solución ILS ppm
                                    //Promedio Ponderado Mensual(10060 MMSA_LIXI_Solución ILS m3, 10056 MMSA_LIXI_CN Solución ILS ppm)                       
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10056) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10060) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10060
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY MONTH(fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10057:                                         
                                    //10057 MMSA_LIXI_Ley Au Solución PLS ppm
                                    //Promedio Ponderado Mensual(10061 MMSA_LIXI_Solución PLS m3, 10057 MMSA_LIXI_Ley Au Solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10057) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10061; 
                                break;
                                case 10058:                                       
                                    //10058 MMSA_LIXI_pH en Solución PLS
                                    //Promedio Ponderado Mensual(10061 MMSA_LIXI_Solución PLS m3, 10058 MMSA_LIXI_pH en Solución PLS)                     
                                    $sumaproducto= DB::select(
                                        'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10058) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->summesbudget10061; 
                                break;
                            }                            
                            if(isset($sumaproducto[0]->sumaproducto) && isset($suma[0]->suma))
                            {
                                if ($suma[0]->suma > 0)
                                {
                                    $m_budget = $sumaproducto[0]->sumaproducto/$suma[0]->suma;
                                    if($m_budget > 100)
                                    {
                                        return number_format(round($m_budget), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($m_budget, 2, ',', '.');
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
                                $mes_budget= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as mes_budget
                                    FROM [mansfield].[dbo].[budget]
                                    WHERE variable_id = ?
                                    AND  MONTH(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    GROUP BY MONTH(fecha)', 
                                    [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );
                                if(isset($mes_budget[0]->mes_budget))
                                {
                                    $m_budget = $mes_budget[0]->mes_budget;
                                    if($m_budget > 100)
                                    {
                                        return number_format(round($m_budget), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($m_budget, 2, ',', '.');
                                    }
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
                                    $mes_budget= DB::select(
                                        'SELECT MONTH(fecha) as month, AVG(valor) as mes_budget
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = ?
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND valor <> 0 
                                        GROUP BY MONTH(fecha)', 
                                        [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );  
                                    if(isset($mes_budget[0]->mes_budget))
                                    {
                                        $m_budget = $mes_budget[0]->mes_budget;
                                        if($m_budget > 100)
                                        {
                                            return number_format(round($m_budget), 0, ',', '.');
                                        }
                                        else
                                        {
                                            return number_format($m_budget, 2, ',', '.');
                                        }
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
                                                $suma= $this->summesbudget10005;                                     
                                                $suma2= DB::select(
                                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10062
                                                    AND  MONTH(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY MONTH(fecha)', 
                                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10013:                                       
                                                //10013 MMSA_HPGR_Productividad t/h                   
                                                //sumatoria.mensual(10011 MMSA_HPGR_Mineral Triturado t)/ sumatoria.mesual(10063 MMSA_HPGR_Horas Operativas Trituración Terciaria h)                      
                                                $suma= $this->summesbudget10011;                                     
                                                $suma2= DB::select(
                                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10063
                                                    AND  MONTH(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY MONTH(fecha)', 
                                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10020:                                       
                                                //10020 MMSA_AGLOM_Productividad t/h                  
                                                //sumatoria.mensual(10019 MMSA_AGLOM_Mineral Aglomerado t)/ sumatoria.mensual(10064 MMSA_AGLOM_Horas Operativas Aglomeración h)                      
                                                $suma= $this->summesbudget10019;                                     
                                                $suma2= DB::select(
                                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10064
                                                    AND  MONTH(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY MONTH(fecha)', 
                                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10032:                                       
                                                //10032 MMSA_APILAM_STACKER_Productividad t/h                 
                                                //sumatoria.mensual(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)/ sumatoria.mensual(10065 MMSA_APILAM_STACKER_Tiempo Operativo h)                      
                                                $suma= DB::select(
                                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10031
                                                    AND  MONTH(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY MONTH(fecha)', 
                                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                );                                     
                                                $suma2= DB::select(
                                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10065
                                                    AND  MONTH(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY MONTH(fecha)', 
                                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                        }                            
                                        if(isset($suma[0]->suma) && isset($suma2[0]->suma))
                                        {
                                            if ($suma2[0]->suma > 0)
                                            {
                                                $m_budget = $suma[0]->suma/$suma2[0]->suma;
                                                if($m_budget > 100)
                                                {
                                                    return number_format(round($m_budget), 0, ',', '.');
                                                }
                                                else
                                                {
                                                    return number_format($m_budget, 2, ',', '.');
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
                                        return $data->variable_id;
                                    }
                                }
                            }
                        }
                    })
                    ->addColumn('trimestre_real', function($data)
                    {              
                        if (in_array($data->variable_id, $this->pparray))
                        {
                            switch($data->variable_id)
                            {
                                case 10004:                                       
                                    //Promedio Ponderado Trimestral(10005,10004)                    
                                    //10004	Ley Au	MMSA_TP_Ley Au	g
                                    //10005	MMSA_TP_Mineral Triturado t                          
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10004) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10005) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10005
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10010:
                                case 10030:                                       
                                    //10010 Ley Au MMSA_HPGR_Ley Au 
                                    //Promedio Ponderado Trimestral(10011 MMSA_HPGR_Mineral Triturado t, 10010 MMSA_HPGR_Ley Au g/t)                         
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10010) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumtrireal10011;
                                break;
                                case 10012:                                       
                                    //10012 MMSA_HPGR_P80 mm
                                    //Promedio Ponderado Trimestral(10011 MMSA_HPGR_Mineral Triturado t, 10012 MMSA_HPGR_P80 mm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10012) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumtrireal10011;
                                break;
                                case 10015:                                         
                                    //10015 MMSA_AGLOM_Adición de Cemento kg/t
                                    //Promedio Ponderado Trimestral(10019 MMSA_AGLOM_Mineral Aglomerado t, 10015 MMSA_AGLOM_Adición de Cemento kg/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10015) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumtrireal10019;
                                break;
                                case 10016:                                       
                                    //10016 MMSA_AGLOM_Adición de CN ppm                    
                                    //Promedio Ponderado Trimestral(10066 MMSA_AGLOM_Flujo, 10016 MMSA_AGLOM_Adición de CN ppm)                         
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10016) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10066) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10066
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10018:                                         
                                    //10018 MMSA_AGLOM_Humedad %
                                    //Promedio Ponderado Trimestral(10019 MMSA_AGLOM_Mineral Aglomerado t,10018 MMSA_AGLOM_Humedad %)                        
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10018) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumtrireal10019;
                                break;
                                case 10024:                                       
                                    //10024 MMSA_APILAM_PYS_Ley Au g/t 
                                    //Promedio Ponderado Trimestral(10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t, 10024 MMSA_APILAM_PYS_Ley Au g/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10024) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10025) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10025
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10033:                                         
                                    //10033 MMSA_APILAM_STACKER_Recuperación %
                                    //Promedio Ponderado Trimestral(10011 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10033) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumtrireal10011; 
                                break;
                                case 10035:                                       
                                    //10035 MMSA_APILAM_TA_Ley Au g/t
                                    //Promedio Ponderado Trimestral(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10035) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10039; 
                                break;
                                case 10036:                                         
                                    //10036 MMSA_APILAM_TA_Recuperación %
                                    //Promedio Ponderado Trimestral(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10036) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10039; 
                                break;
                                case 10040:                                         
                                    //10040 MMSA_SART_Eficiencia %
                                    //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 10040 MMSA_SART_Eficiencia %)                     
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10040) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10045; 
                                break;
                                case 10041:                                         
                                    //10041 MMSA_SART_Ley Au Alimentada ppm
                                    //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 10041 MMSA_SART_Ley Au Alimentada ppm)                   
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10041) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10045;  
                                break;
                                case 10042:                                         
                                    //10042 MMSA_SART_Ley Au Salida ppm
                                    //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 10042 MMSA_SART_Ley Au Salida ppm)                 
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10042) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10045;  
                                break;
                                case 10043:                                         
                                    //10043 MMSA_SART_Ley Cu Alimentada ppm
                                    //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 10043 MMSA_SART_Ley Cu Alimentada ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10043) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10045;  
                                break;
                                case 10044:                                         
                                    //10044 MMSA_SART_Ley Cu Salida ppm
                                    //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 10044 MMSA_SART_Ley Cu Salida ppm)                    
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10044) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10045;  
                                break;
                                case 10049:                                         
                                    //10049 MMSA_ADR_Eficiencia %
                                    //Promedio Ponderado Trimestral(10052 MMSA_ADR_PLS a Carbones m3, 10049 MMSA_ADR_Eficiencia %)                    
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10049) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10052; 
                                break;
                                case 10050:                                         
                                    //10050 MMSA_ADR_Ley de Au BLS ppm
                                    //Promedio Ponderado Trimestral(10052 MMSA_ADR_PLS a Carbones m3, 10050 MMSA_ADR_Ley de Au BLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10050) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10052; 
                                break;
                                case 10051:                                         
                                    //10051 MMSA_ADR_Ley de Au PLS ppm
                                    //Promedio Ponderado Trimestral(10052 MMSA_ADR_PLS a Carbones m3, 10051 MMSA_ADR_Ley de Au PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10051) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10052; 
                                break;
                                case 10054:                                         
                                    //10054 MMSA_LIXI_CN en solución PLS ppm
                                    //Promedio Ponderado Trimestral(10061 MMSA_LIXI_Solución PLS m3, 10054 MMSA_LIXI_CN en solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10054) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10061; 
                                break;
                                case 10055:                                         
                                    //10055 MMSA_LIXI_CN Solución Barren ppm
                                    //Promedio Ponderado Trimestral(10059 MMSA_LIXI_Solución Barren m3, 10055 MMSA_LIXI_CN Solución Barren ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10055) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10059) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10059
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10056:                                         
                                    //10056 MMSA_LIXI_CN Solución ILS ppm
                                    //Promedio Ponderado Trimestral(10060 MMSA_LIXI_Solución ILS m3, 10056 MMSA_LIXI_CN Solución ILS ppm)                       
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10056) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10060) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10060
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10057:                                         
                                    //10057 MMSA_LIXI_Ley Au Solución PLS ppm
                                    //Promedio Ponderado Trimestral(10061 MMSA_LIXI_Solución PLS m3, 10057 MMSA_LIXI_Ley Au Solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10057) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10061; 
                                break;
                                case 10058:                                       
                                    //10058 MMSA_LIXI_pH en Solución PLS
                                    //Promedio Ponderado Trimestral(10061 MMSA_LIXI_Solución PLS m3, 10058 MMSA_LIXI_pH en Solución PLS)                     
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10058) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtrireal10061; 
                                break;
                            }                         
                            if(isset($sumaproducto[0]->sumaproducto) && isset($suma[0]->suma))
                            {
                                if ($suma[0]->suma > 0)
                                {
                                    $t_real = $sumaproducto[0]->sumaproducto/$suma[0]->suma;
                                    if($t_real > 100)
                                    {
                                        return number_format(round($t_real), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($t_real, 2, ',', '.');
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
                                $trimestre_real= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_real
                                    FROM [mansfield].[dbo].[data]
                                    WHERE variable_id = ?
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );
                                if(isset($trimestre_real[0]->trimestre_real))
                                {
                                    $t_real = $trimestre_real[0]->trimestre_real;
                                    if($t_real > 100)
                                    {
                                        return number_format(round($t_real), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($t_real, 2, ',', '.');
                                    }
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
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, AVG(valor) as trimestre_real
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = ?
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND valor <> 0
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                    if(isset($trimestre_real[0]->trimestre_real))
                                    {
                                        $t_real = $trimestre_real[0]->trimestre_real;
                                        if($t_real > 100)
                                        {
                                            return number_format(round($t_real), 0, ',', '.');
                                        }
                                        else
                                        {
                                            return number_format($t_real, 2, ',', '.');
                                        }
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
                                                //sumatoria.trimestral(10005 MMSA_TP_Mineral Triturado t)/sumatoria.trimestral(10062 MMSA_TP_Horas Operativas Trituración Primaria h)                         
                                                $suma= $this->sumtrireal10005;                                     
                                                $suma2= DB::select(
                                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10062
                                                    AND  DATEPART(QUARTER, fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10013:                                       
                                                //10013 MMSA_HPGR_Productividad t/h                   
                                                //sumatoria.trimestral(10011 MMSA_HPGR_Mineral Triturado t)/ sumatoria.trimestral(10063 MMSA_HPGR_Horas Operativas Trituración Terciaria h)                      
                                                $suma= $this->sumtrireal10011;                                     
                                                $suma2= DB::select(
                                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10063
                                                    AND  DATEPART(QUARTER, fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10020:                                       
                                                //10020 MMSA_AGLOM_Productividad t/h                  
                                                //sumatoria.trimestral(10019 MMSA_AGLOM_Mineral Aglomerado t)/ sumatoria.trimestral(10064 MMSA_AGLOM_Horas Operativas Aglomeración h)                      
                                                $suma= $this->sumtrireal10019;                                     
                                                $suma2= DB::select(
                                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10064
                                                    AND  DATEPART(QUARTER, fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10032:                                       
                                                //10032 MMSA_APILAM_STACKER_Productividad t/h                 
                                                //sumatoria.trimestral(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)/ sumatoria.trimestral(10065 MMSA_APILAM_STACKER_Tiempo Operativo h)                      
                                                $suma= DB::select(
                                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10031
                                                    AND  DATEPART(QUARTER, fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                                );                                     
                                                $suma2= DB::select(
                                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10065
                                                    AND  DATEPART(QUARTER, fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                        }                            
                                        if(isset($suma[0]->suma) && isset($suma2[0]->suma))
                                        {
                                            if ($suma2[0]->suma > 0)
                                            {
                                                $t_real = $suma[0]->suma/$suma2[0]->suma;
                                                if($t_real > 100)
                                                {
                                                    return number_format(round($t_real), 0, ',', '.');
                                                }
                                                else
                                                {
                                                    return number_format($t_real, 2, ',', '.');
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
                                        return $data->variable_id;
                                    }
                                }
                            }
                        } 
                    })
                    ->addColumn('trimestre_budget', function($data)
                    {           
                        if (in_array($data->variable_id, $this->pparray))
                        {
                            switch($data->variable_id)
                            {
                                case 10004:                                       
                                    //Promedio Ponderado Trimestral(10005,10004)                    
                                    //10004	Ley Au	MMSA_TP_Ley Au	g
                                    //10005	MMSA_TP_Mineral Triturado t                          
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10004) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10005) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10005
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10010:
                                case 10030:                                       
                                    //10010 Ley Au MMSA_HPGR_Ley Au 
                                    //Promedio Ponderado Trimestral(10011 MMSA_HPGR_Mineral Triturado t, 10010 MMSA_HPGR_Ley Au g/t)                         
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10010) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumtribudget10011;
                                break;
                                case 10012:                                       
                                    //10012 MMSA_HPGR_P80 mm
                                    //Promedio Ponderado Trimestral(10011 MMSA_HPGR_Mineral Triturado t, 10012 MMSA_HPGR_P80 mm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10012) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumtribudget10011;
                                break;
                                case 10015:                                         
                                    //10015 MMSA_AGLOM_Adición de Cemento kg/t
                                    //Promedio Ponderado Trimestral(10019 MMSA_AGLOM_Mineral Aglomerado t, 10015 MMSA_AGLOM_Adición de Cemento kg/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10015) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumtribudget10019;
                                break;
                                case 10016:
                                    //Promedio valores <>0
                                    $trimestre_budget= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, AVG(valor) as trimestre_budget
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = ?
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND valor <> 0
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                    if(isset($trimestre_budget[0]->trimestre_budget))
                                    {
                                        $t_budget = $trimestre_budget[0]->trimestre_budget;
                                        if($t_budget > 100)
                                        {
                                            return number_format(round($t_budget), 0, ',', '.');
                                        }
                                        else
                                        {
                                            return number_format($t_budget, 2, ',', '.');
                                        }
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10018:                                         
                                    //10018 MMSA_AGLOM_Humedad %
                                    //Promedio Ponderado Trimestral(10019 MMSA_AGLOM_Mineral Aglomerado t,10018 MMSA_AGLOM_Humedad %)                        
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10018) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumtribudget10019;
                                break;
                                case 10024:                                       
                                    //10024 MMSA_APILAM_PYS_Ley Au g/t 
                                    //Promedio Ponderado Trimestral(10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t, 10024 MMSA_APILAM_PYS_Ley Au g/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10024) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10025) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10025
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10033:                                         
                                    //10033 MMSA_APILAM_STACKER_Recuperación %
                                    //Promedio Ponderado Trimestral(10011 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10033) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumtribudget10011; 
                                break;
                                case 10035:                                       
                                    //10035 MMSA_APILAM_TA_Ley Au g/t
                                    //Promedio Ponderado Trimestral(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10035) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10039; 
                                break;
                                case 10036:                                         
                                    //10036 MMSA_APILAM_TA_Recuperación %
                                    //Promedio Ponderado Trimestral(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10036) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10039; 
                                break;
                                case 10040:                                         
                                    //10040 MMSA_SART_Eficiencia %
                                    //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 10040 MMSA_SART_Eficiencia %)                     
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10040) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10045; 
                                break;
                                case 10041:                                         
                                    //10041 MMSA_SART_Ley Au Alimentada ppm
                                    //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 10041 MMSA_SART_Ley Au Alimentada ppm)                   
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10041) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10045;  
                                break;
                                case 10042:                                         
                                    //10042 MMSA_SART_Ley Au Salida ppm
                                    //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 10042 MMSA_SART_Ley Au Salida ppm)                 
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10042) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10045;  
                                break;
                                case 10043:                                         
                                    //10043 MMSA_SART_Ley Cu Alimentada ppm
                                    //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 10043 MMSA_SART_Ley Cu Alimentada ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10043) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10045;  
                                break;
                                case 10044:                                         
                                    //10044 MMSA_SART_Ley Cu Salida ppm
                                    //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 10044 MMSA_SART_Ley Cu Salida ppm)                    
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10044) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10045;  
                                break;
                                case 10049:                                         
                                    //10049 MMSA_ADR_Eficiencia %
                                    //Promedio Ponderado Trimestral(10052 MMSA_ADR_PLS a Carbones m3, 10049 MMSA_ADR_Eficiencia %)                    
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10049) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10052; 
                                break;
                                case 10050:                                         
                                    //10050 MMSA_ADR_Ley de Au BLS ppm
                                    //Promedio Ponderado Trimestral(10052 MMSA_ADR_PLS a Carbones m3, 10050 MMSA_ADR_Ley de Au BLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10050) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10052; 
                                break;
                                case 10051:                                         
                                    //10051 MMSA_ADR_Ley de Au PLS ppm
                                    //Promedio Ponderado Trimestral(10052 MMSA_ADR_PLS a Carbones m3, 10051 MMSA_ADR_Ley de Au PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10051) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10052; 
                                break;
                                case 10054:                                         
                                    //10054 MMSA_LIXI_CN en solución PLS ppm
                                    //Promedio Ponderado Trimestral(10061 MMSA_LIXI_Solución PLS m3, 10054 MMSA_LIXI_CN en solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10054) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10061; 
                                break;
                                case 10055:                                         
                                    //10055 MMSA_LIXI_CN Solución Barren ppm
                                    //Promedio Ponderado Trimestral(10059 MMSA_LIXI_Solución Barren m3, 10055 MMSA_LIXI_CN Solución Barren ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10055) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10059) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10059
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10056:                                         
                                    //10056 MMSA_LIXI_CN Solución ILS ppm
                                    //Promedio Ponderado Trimestral(10060 MMSA_LIXI_Solución ILS m3, 10056 MMSA_LIXI_CN Solución ILS ppm)                       
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10056) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10060) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10060
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10057:                                         
                                    //10057 MMSA_LIXI_Ley Au Solución PLS ppm
                                    //Promedio Ponderado Trimestral(10061 MMSA_LIXI_Solución PLS m3, 10057 MMSA_LIXI_Ley Au Solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10057) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10061; 
                                break;
                                case 10058:                                       
                                    //10058 MMSA_LIXI_pH en Solución PLS
                                    //Promedio Ponderado Trimestral(10061 MMSA_LIXI_Solución PLS m3, 10058 MMSA_LIXI_pH en Solución PLS)                     
                                    $sumaproducto= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10058) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumtribudget10061; 
                                break;
                            }                         
                            if(isset($sumaproducto[0]->sumaproducto) && isset($suma[0]->suma))
                            {
                                if ($suma[0]->suma > 0)
                                {
                                    $t_budget = $sumaproducto[0]->sumaproducto/$suma[0]->suma;
                                    if($t_budget > 100)
                                    {
                                        return number_format(round($t_budget), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($t_budget, 2, ',', '.');
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
                                $trimestre_budget= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_budget
                                    FROM [mansfield].[dbo].[budget]
                                    WHERE variable_id = ?
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );
                                if(isset($trimestre_budget[0]->trimestre_budget))
                                {
                                    $t_budget = $trimestre_budget[0]->trimestre_budget;
                                    if($t_budget > 100)
                                    {
                                        return number_format(round($t_budget), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($t_budget, 2, ',', '.');
                                    }
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
                                    $trimestre_budget= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, AVG(valor) as trimestre_budget
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = ?
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND valor <> 0
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                    if(isset($trimestre_budget[0]->trimestre_budget))
                                    {
                                        $t_budget = $trimestre_budget[0]->trimestre_budget;
                                        if($t_budget > 100)
                                        {
                                            return number_format(round($t_budget), 0, ',', '.');
                                        }
                                        else
                                        {
                                            return number_format($t_budget, 2, ',', '.');
                                        }
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
                                                //sumatoria.trimestral(10005 MMSA_TP_Mineral Triturado t)/sumatoria.trimestral(10062 MMSA_TP_Horas Operativas Trituración Primaria h)                         
                                                $suma= $this->sumtribudget10005;                                     
                                                $suma2= DB::select(
                                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10062
                                                    AND  DATEPART(QUARTER, fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10013:                                       
                                                //10013 MMSA_HPGR_Productividad t/h                   
                                                //sumatoria.trimestral(10011 MMSA_HPGR_Mineral Triturado t)/ sumatoria.trimestral(10063 MMSA_HPGR_Horas Operativas Trituración Terciaria h)                      
                                                $suma= $this->sumtribudget10011;                                     
                                                $suma2= DB::select(
                                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10063
                                                    AND  DATEPART(QUARTER, fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10020:                                       
                                                //10020 MMSA_AGLOM_Productividad t/h                  
                                                //sumatoria.trimestral(10019 MMSA_AGLOM_Mineral Aglomerado t)/ sumatoria.trimestral(10064 MMSA_AGLOM_Horas Operativas Aglomeración h)                      
                                                $suma= $this->sumtribudget10019;                                     
                                                $suma2= DB::select(
                                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10064
                                                    AND  DATEPART(QUARTER, fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10032:                                       
                                                //10032 MMSA_APILAM_STACKER_Productividad t/h                 
                                                //sumatoria.trimestral(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)/ sumatoria.trimestral(10065 MMSA_APILAM_STACKER_Tiempo Operativo h)                      
                                                $suma= DB::select(
                                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10031
                                                    AND  DATEPART(QUARTER, fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                                );                                     
                                                $suma2= DB::select(
                                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10065
                                                    AND  DATEPART(QUARTER, fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                        }                            
                                        if(isset($suma[0]->suma) && isset($suma2[0]->suma))
                                        {
                                            if ($suma2[0]->suma > 0)
                                            {
                                                $t_budget = $suma[0]->suma/$suma2[0]->suma;
                                                if($t_budget > 100)
                                                {
                                                    return number_format(round($t_budget), 0, ',', '.');
                                                }
                                                else
                                                {
                                                    return number_format($t_budget, 2, ',', '.');
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
                                        return $data->variable_id;
                                    }
                                }
                            }
                        } 
                    })
                    ->addColumn('anio_real', function($data)
                    {         
                        if (in_array($data->variable_id, $this->pparray))
                        {
                            switch($data->variable_id)
                            {
                                case 10004:                                       
                                    //Promedio Ponderado Anual(10005,10004)                    
                                    //10004	Ley Au	MMSA_TP_Ley Au	g
                                    //10005	MMSA_TP_Mineral Triturado t                          
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10004) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10005) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10005
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY YEAR(fecha)', 
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10010:
                                case 10030:                                       
                                    //10010 Ley Au MMSA_HPGR_Ley Au 
                                    //Promedio Ponderado Anual(10011 MMSA_HPGR_Mineral Triturado t, 10010 MMSA_HPGR_Ley Au g/t)                         
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10010) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumanioreal10011;
                                break;
                                case 10012:                                       
                                    //10012 MMSA_HPGR_P80 mm
                                    //Promedio Ponderado Anual(10011 MMSA_HPGR_Mineral Triturado t, 10012 MMSA_HPGR_P80 mm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10012) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumanioreal10011;
                                break;
                                case 10015:                                         
                                    //10015 MMSA_AGLOM_Adición de Cemento kg/t
                                    //Promedio Ponderado Anual(10019 MMSA_AGLOM_Mineral Aglomerado t, 10015 MMSA_AGLOM_Adición de Cemento kg/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10015) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumanioreal10019;
                                break;
                                case 10016:                                       
                                    //10016 MMSA_AGLOM_Adición de CN ppm 
                                    //Promedio Ponderado Anual(10066 MMSA_AGLOM_Flujo, 10016 MMSA_AGLOM_Adición de CN ppm)                        
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10016) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10066) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10066
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY YEAR(fecha)', 
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10018:                                         
                                    //10018 MMSA_AGLOM_Humedad %
                                    //Promedio Ponderado Anual(10019 MMSA_AGLOM_Mineral Aglomerado t,10018 MMSA_AGLOM_Humedad %)                        
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10018) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumanioreal10019;
                                break;
                                case 10024:                                       
                                    //10024 MMSA_APILAM_PYS_Ley Au g/t 
                                    //Promedio Ponderado Anual(10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t, 10024 MMSA_APILAM_PYS_Ley Au g/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10024) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10025) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10025
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY YEAR(fecha)', 
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10033:                                         
                                    //10033 MMSA_APILAM_STACKER_Recuperación %
                                    //Promedio Ponderado Anual(10011 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10033) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumanioreal10011; 
                                break;
                                case 10035:                                       
                                    //10035 MMSA_APILAM_TA_Ley Au g/t
                                    //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10035) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10039; 
                                break;
                                case 10036:                                         
                                    //10036 MMSA_APILAM_TA_Recuperación %
                                    //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10036) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10039; 
                                break;
                                case 10040:                                         
                                    //10040 MMSA_SART_Eficiencia %
                                    //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, 10040 MMSA_SART_Eficiencia %)                     
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10040) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10045; 
                                break;
                                case 10041:                                         
                                    //10041 MMSA_SART_Ley Au Alimentada ppm
                                    //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, 10041 MMSA_SART_Ley Au Alimentada ppm)                   
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10041) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10045;  
                                break;
                                case 10042:                                         
                                    //10042 MMSA_SART_Ley Au Salida ppm
                                    //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, 10042 MMSA_SART_Ley Au Salida ppm)                 
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10042) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10045;  
                                break;
                                case 10043:                                         
                                    //10043 MMSA_SART_Ley Cu Alimentada ppm
                                    //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, 10043 MMSA_SART_Ley Cu Alimentada ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10043) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10045;  
                                break;
                                case 10044:                                         
                                    //10044 MMSA_SART_Ley Cu Salida ppm
                                    //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, 10044 MMSA_SART_Ley Cu Salida ppm)                    
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10044) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10045;  
                                break;
                                case 10049:                                         
                                    //10049 MMSA_ADR_Eficiencia %
                                    //Promedio Ponderado Anual(10052 MMSA_ADR_PLS a Carbones m3, 10049 MMSA_ADR_Eficiencia %)                    
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10049) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10052; 
                                break;
                                case 10050:                                         
                                    //10050 MMSA_ADR_Ley de Au BLS ppm
                                    //Promedio Ponderado Anual(10052 MMSA_ADR_PLS a Carbones m3, 10050 MMSA_ADR_Ley de Au BLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10050) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10052; 
                                break;
                                case 10051:                                         
                                    //10051 MMSA_ADR_Ley de Au PLS ppm
                                    //Promedio Ponderado Anual(10052 MMSA_ADR_PLS a Carbones m3, 10051 MMSA_ADR_Ley de Au PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10051) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10052; 
                                break;
                                case 10054:                                         
                                    //10054 MMSA_LIXI_CN en solución PLS ppm
                                    //Promedio Ponderado Anual(10061 MMSA_LIXI_Solución PLS m3, 10054 MMSA_LIXI_CN en solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10054) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10061; 
                                break;
                                case 10055:                                         
                                    //10055 MMSA_LIXI_CN Solución Barren ppm
                                    //Promedio Ponderado Anual(10059 MMSA_LIXI_Solución Barren m3, 10055 MMSA_LIXI_CN Solución Barren ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10055) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10059) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10059
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY YEAR(fecha)', 
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10056:                                         
                                    //10056 MMSA_LIXI_CN Solución ILS ppm
                                    //Promedio Ponderado Anual(10060 MMSA_LIXI_Solución ILS m3, 10056 MMSA_LIXI_CN Solución ILS ppm)                       
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10056) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10060) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = 10060
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY YEAR(fecha)', 
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10057:                                         
                                    //10057 MMSA_LIXI_Ley Au Solución PLS ppm
                                    //Promedio Ponderado Anual(10061 MMSA_LIXI_Solución PLS m3, 10057 MMSA_LIXI_Ley Au Solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10057) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10061; 
                                break;
                                case 10058:                                       
                                    //10058 MMSA_LIXI_pH en Solución PLS
                                    //Promedio Ponderado Anual(10061 MMSA_LIXI_Solución PLS m3, 10058 MMSA_LIXI_pH en Solución PLS)                     
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10058) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[data]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumanioreal10061; 
                                break;
                            }                         
                            if(isset($sumaproducto[0]->sumaproducto) && isset($suma[0]->suma))
                            {
                                if ($suma[0]->suma > 0)
                                {
                                    $a_real = $sumaproducto[0]->sumaproducto/$suma[0]->suma;
                                    if($a_real > 100)
                                    {
                                        return number_format(round($a_real), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($a_real, 2, ',', '.');
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
                                $anio_real= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) as anio_real
                                    FROM [mansfield].[dbo].[data]
                                    WHERE variable_id = ?
                                    AND  YEAR(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    GROUP BY YEAR(fecha)', 
                                    [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );
                                if(isset($anio_real[0]->anio_real))
                                {
                                    $a_real = $anio_real[0]->anio_real;
                                    if($a_real > 100)
                                    {
                                        return number_format(round($a_real), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($a_real, 2, ',', '.');
                                    }
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
                                    $anio_real= DB::select(
                                        'SELECT YEAR(fecha) as year, AVG(valor) as anio_real
                                        FROM [mansfield].[dbo].[data]
                                        WHERE variable_id = ?
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND valor <> 0
                                        GROUP BY YEAR(fecha)', 
                                        [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                    if(isset($anio_real[0]->anio_real))
                                    {
                                        $a_real = $anio_real[0]->anio_real;
                                        if($a_real > 100)
                                        {
                                            return number_format(round($a_real), 0, ',', '.');
                                        }
                                        else
                                        {
                                            return number_format($a_real, 2, ',', '.');
                                        }
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
                                                //sumatoria.anual(10005 MMSA_TP_Mineral Triturado t)/sumatoria.anual(10062 MMSA_TP_Horas Operativas Trituración Primaria h)                         
                                                $suma= $this->sumanioreal10005;                                     
                                                $suma2= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10062
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10013:                                       
                                                //10013 MMSA_HPGR_Productividad t/h                   
                                                //sumatoria.anual(10011 MMSA_HPGR_Mineral Triturado t)/ sumatoria.anual(10063 MMSA_HPGR_Horas Operativas Trituración Terciaria h)                      
                                                $suma= $this->sumanioreal10011;                                     
                                                $suma2= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10063
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10020:                                       
                                                //10020 MMSA_AGLOM_Productividad t/h                  
                                                //sumatoria.anual(10019 MMSA_AGLOM_Mineral Aglomerado t)/ sumatoria.anual(10064 MMSA_AGLOM_Horas Operativas Aglomeración h)                      
                                                $suma= $this->sumanioreal10019;                                     
                                                $suma2= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10064
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10032:                                       
                                                //10032 MMSA_APILAM_STACKER_Productividad t/h                 
                                                //sumatoria.anual(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)/ sumatoria.anual(10065 MMSA_APILAM_STACKER_Tiempo Operativo h)                      
                                                $suma= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10031
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                );                                     
                                                $suma2= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[data]
                                                    WHERE variable_id = 10065
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                        }                            
                                        if(isset($suma[0]->suma) && isset($suma2[0]->suma))
                                        {
                                            if ($suma2[0]->suma > 0)
                                            {
                                                $a_real = $suma[0]->suma/$suma2[0]->suma;
                                                if($a_real > 100)
                                                {
                                                    return number_format(round($a_real), 0, ',', '.');
                                                }
                                                else
                                                {
                                                    return number_format($a_real, 2, ',', '.');
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
                                        return $data->variable_id;
                                    }
                                }
                            }
                        } 
                    })
                    ->addColumn('anio_budget', function($data)
                    {          
                        if (in_array($data->variable_id, $this->pparray))
                        {
                            switch($data->variable_id)
                            {
                                case 10004:                                       
                                    //Promedio Ponderado Anual(10005,10004)                    
                                    //10004	Ley Au	MMSA_TP_Ley Au	g
                                    //10005	MMSA_TP_Mineral Triturado t                          
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10004) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10005) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10005
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY YEAR(fecha)', 
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10010:
                                case 10030:                                       
                                    //10010 Ley Au MMSA_HPGR_Ley Au 
                                    //Promedio Ponderado Anual(10011 MMSA_HPGR_Mineral Triturado t, 10010 MMSA_HPGR_Ley Au g/t)                         
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10010) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumaniobudget10011;
                                break;
                                case 10012:                                       
                                    //10012 MMSA_HPGR_P80 mm
                                    //Promedio Ponderado Anual(10011 MMSA_HPGR_Mineral Triturado t, 10012 MMSA_HPGR_P80 mm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10012) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumaniobudget10011;
                                break;
                                case 10015:                                         
                                    //10015 MMSA_AGLOM_Adición de Cemento kg/t
                                    //Promedio Ponderado Anual(10019 MMSA_AGLOM_Mineral Aglomerado t, 10015 MMSA_AGLOM_Adición de Cemento kg/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10015) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumaniobudget10019;
                                break;
                                case 10016:
                                    //Promedio valores <>0
                                    $anio_budget= DB::select(
                                        'SELECT YEAR(fecha) as year, AVG(valor) as anio_budget
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = ?
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND valor <> 0
                                        GROUP BY YEAR(fecha)', 
                                        [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                    if(isset($anio_budget[0]->anio_budget))
                                    {
                                        $a_budget = $anio_budget[0]->anio_budget;
                                        if($a_budget > 100)
                                        {
                                            return number_format(round($a_budget), 0, ',', '.');
                                        }
                                        else
                                        {
                                            return number_format($a_budget, 2, ',', '.');
                                        }
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10018:                                         
                                    //10018 MMSA_AGLOM_Humedad %
                                    //Promedio Ponderado Anual(10019 MMSA_AGLOM_Mineral Aglomerado t,10018 MMSA_AGLOM_Humedad %)                        
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10018) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10019) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumaniobudget10019;
                                break;
                                case 10024:                                       
                                    //10024 MMSA_APILAM_PYS_Ley Au g/t 
                                    //Promedio Ponderado Anual(10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t, 10024 MMSA_APILAM_PYS_Ley Au g/t)                        
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10024) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10025) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10025
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY YEAR(fecha)', 
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10033:                                         
                                    //10033 MMSA_APILAM_STACKER_Recuperación %
                                    //Promedio Ponderado Anual(10011 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10033) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10011) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma = $this->sumaniobudget10011; 
                                break;
                                case 10035:                                       
                                    //10035 MMSA_APILAM_TA_Ley Au g/t
                                    //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10035) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10039; 
                                break;
                                case 10036:                                         
                                    //10036 MMSA_APILAM_TA_Recuperación %
                                    //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10036) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10039) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10039; 
                                break;
                                case 10040:                                         
                                    //10040 MMSA_SART_Eficiencia %
                                    //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, 10040 MMSA_SART_Eficiencia %)                     
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10040) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10045; 
                                break;
                                case 10041:                                         
                                    //10041 MMSA_SART_Ley Au Alimentada ppm
                                    //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, 10041 MMSA_SART_Ley Au Alimentada ppm)                   
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10041) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10045;  
                                break;
                                case 10042:                                         
                                    //10042 MMSA_SART_Ley Au Salida ppm
                                    //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, 10042 MMSA_SART_Ley Au Salida ppm)                 
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10042) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10045;  
                                break;
                                case 10043:                                         
                                    //10043 MMSA_SART_Ley Cu Alimentada ppm
                                    //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, 10043 MMSA_SART_Ley Cu Alimentada ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10043) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10045;  
                                break;
                                case 10044:                                         
                                    //10044 MMSA_SART_Ley Cu Salida ppm
                                    //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, 10044 MMSA_SART_Ley Cu Salida ppm)                    
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10044) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10045) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10045;  
                                break;
                                case 10049:                                         
                                    //10049 MMSA_ADR_Eficiencia %
                                    //Promedio Ponderado Anual(10052 MMSA_ADR_PLS a Carbones m3, 10049 MMSA_ADR_Eficiencia %)                    
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10049) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10052; 
                                break;
                                case 10050:                                         
                                    //10050 MMSA_ADR_Ley de Au BLS ppm
                                    //Promedio Ponderado Anual(10052 MMSA_ADR_PLS a Carbones m3, 10050 MMSA_ADR_Ley de Au BLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10050) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10052; 
                                break;
                                case 10051:                                         
                                    //10051 MMSA_ADR_Ley de Au PLS ppm
                                    //Promedio Ponderado Anual(10052 MMSA_ADR_PLS a Carbones m3, 10051 MMSA_ADR_Ley de Au PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10051) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10052) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10052; 
                                break;
                                case 10054:                                         
                                    //10054 MMSA_LIXI_CN en solución PLS ppm
                                    //Promedio Ponderado Anual(10061 MMSA_LIXI_Solución PLS m3, 10054 MMSA_LIXI_CN en solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10054) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10061; 
                                break;
                                case 10055:                                         
                                    //10055 MMSA_LIXI_CN Solución Barren ppm
                                    //Promedio Ponderado Anual(10059 MMSA_LIXI_Solución Barren m3, 10055 MMSA_LIXI_CN Solución Barren ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10055) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10059) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10059
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY YEAR(fecha)', 
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10056:                                         
                                    //10056 MMSA_LIXI_CN Solución ILS ppm
                                    //Promedio Ponderado Anual(10060 MMSA_LIXI_Solución ILS m3, 10056 MMSA_LIXI_CN Solución ILS ppm)                       
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10056) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10060) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= DB::select(
                                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = 10060
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY YEAR(fecha)', 
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10057:                                         
                                    //10057 MMSA_LIXI_Ley Au Solución PLS ppm
                                    //Promedio Ponderado Anual(10061 MMSA_LIXI_Solución PLS m3, 10057 MMSA_LIXI_Ley Au Solución PLS ppm)                      
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10057) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10061; 
                                break;
                                case 10058:                                       
                                    //10058 MMSA_LIXI_pH en Solución PLS
                                    //Promedio Ponderado Anual(10061 MMSA_LIXI_Solución PLS m3, 10058 MMSA_LIXI_pH en Solución PLS)                     
                                    $sumaproducto= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10058) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [mansfield].[dbo].[budget]
                                        where variable_id = 10061) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma= $this->sumaniobudget10061; 
                                break;
                            }                         
                            if(isset($sumaproducto[0]->sumaproducto) && isset($suma[0]->suma))
                            {
                                if ($suma[0]->suma > 0)
                                {
                                    $a_budget = $sumaproducto[0]->sumaproducto/$suma[0]->suma;
                                    if($a_budget > 100)
                                    {
                                        return number_format(round($a_budget), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($a_budget, 2, ',', '.');
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
                                $anio_budget= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) as anio_budget
                                    FROM [mansfield].[dbo].[budget]
                                    WHERE variable_id = ?
                                    AND  YEAR(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    GROUP BY YEAR(fecha)', 
                                    [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );
                                if(isset($anio_budget[0]->anio_budget))
                                {
                                    $a_budget = $anio_budget[0]->anio_budget;
                                    if($a_budget > 100)
                                    {
                                        return number_format(round($a_budget), 0, ',', '.');
                                    }
                                    else
                                    {
                                        return number_format($a_budget, 2, ',', '.');
                                    }
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
                                    $anio_budget= DB::select(
                                        'SELECT YEAR(fecha) as year, AVG(valor) as anio_budget
                                        FROM [mansfield].[dbo].[budget]
                                        WHERE variable_id = ?
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND valor <> 0
                                        GROUP BY YEAR(fecha)', 
                                        [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                    if(isset($anio_budget[0]->anio_budget))
                                    {
                                        $a_budget = $anio_budget[0]->anio_budget;
                                        if($a_budget > 100)
                                        {
                                            return number_format(round($a_budget), 0, ',', '.');
                                        }
                                        else
                                        {
                                            return number_format($a_budget, 2, ',', '.');
                                        }
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
                                                //sumatoria.anual(10005 MMSA_TP_Mineral Triturado t)/sumatoria.anual(10062 MMSA_TP_Horas Operativas Trituración Primaria h)                         
                                                $suma= $this->sumaniobudget10005;                                     
                                                $suma2= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10062
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10013:                                       
                                                //10013 MMSA_HPGR_Productividad t/h                   
                                                //sumatoria.anual(10011 MMSA_HPGR_Mineral Triturado t)/ sumatoria.anual(10063 MMSA_HPGR_Horas Operativas Trituración Terciaria h)                      
                                                $suma= $this->sumaniobudget10011;                                     
                                                $suma2= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10063
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10020:                                       
                                                //10020 MMSA_AGLOM_Productividad t/h                  
                                                //sumatoria.anual(10019 MMSA_AGLOM_Mineral Aglomerado t)/ sumatoria.anual(10064 MMSA_AGLOM_Horas Operativas Aglomeración h)                      
                                                $suma= $this->sumaniobudget10019;                                     
                                                $suma2= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10064
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                            case 10032:                                       
                                                //10032 MMSA_APILAM_STACKER_Productividad t/h                 
                                                //sumatoria.anual(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)/ sumatoria.anual(10065 MMSA_APILAM_STACKER_Tiempo Operativo h)                      
                                                $suma= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10031
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                );                                     
                                                $suma2= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                    FROM [mansfield].[dbo].[budget]
                                                    WHERE variable_id = 10065
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                ); 
                                            break;
                                        }                            
                                        if(isset($suma[0]->suma) && isset($suma2[0]->suma))
                                        {
                                            if ($suma2[0]->suma > 0)
                                            {
                                                $a_budget = $suma[0]->suma/$suma2[0]->suma;
                                                if($a_budget > 100)
                                                {
                                                    return number_format(round($a_budget), 0, ',', '.');
                                                }
                                                else
                                                {
                                                    return number_format($a_budget, 2, ',', '.');
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
                                        return $data->variable_id;
                                    }
                                }
                            }
                        } 
                    })
                    ->addColumn('action', function($data)
                    {
                        $button = '';  
                        if (Auth::user()->hasAnyRole(['Reportes_E', 'Admin']))
                        {
                            $button .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" class="btn-action-table edit" title="Editar registro"><i style="color:#0F62AC;" class="fa-lg fa fa-edit"></i></a>';
                        }     
                        else
                        {
                            $button .= '<a href="javascript:void(0)" name="edit" class="btn-action-table edit2" title="No tiene los permisos necesarios"><i style="color:#0F62AC;" class="fa-lg fa fa-edit"></i></a>';
                        }                   
                        return $button;
                    })
                    ->rawColumns(['categoria','subcategoria','dia_real','dia_budget','dia_porcentaje','mes_real','mes_budget','trimestre_real','anio_real','action'])
                    ->addIndexColumn()
                    ->make(true);
        } 
    }

    public function edit($id)
    {        
        $where = array('data.id' => $id);
        $generic =  DB::table('area')
                    ->join('categoria', 'categoria.area_id', '=','area.id')
                    ->join('subcategoria', 'subcategoria.categoria_id', '=','categoria.id')
                    ->join('variable', 'variable.subcategoria_id', '=','subcategoria.id')
                    ->join('data', 'data.variable_id', '=','variable.id')
                    ->where($where)
                    ->select('area.nombre as area','categoria.nombre as categoria','subcategoria.nombre as subcategoria','variable.nombre as variable','data.id as id','data.valor as valor', 'data.fecha as fecha')
                    ->first();
        return response()->json($generic);
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
                $data->update(
                [
                    'valor' =>$newvalue
                ]);
                if($oldvalue != $newvalue)
                {
                    Historial::create([
                        'data_id' => $id,
                        'fecha' => date('y-m-d h:i:s'),
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
