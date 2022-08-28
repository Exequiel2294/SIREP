<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;


class SendDailyReportCombinado extends Command
{
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
        $this->ley = 
          [10071, 10074, 10077, 10080, 10083, 10086, 10089, 10094, 10098, 10101, 10104, 10107]; 

        //INICIO CALCULOS REUTILIZABLES PROCESOS
            //MES REAL
            $this->summesreal10005 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10005
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesreal10011 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10011
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->summesreal10019 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10019
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->summesreal10039 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10039
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesreal10045 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10045
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesreal10052 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10052
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesreal10061 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[data]
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
                FROM [dbo].[budget]
                WHERE variable_id = 10005
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->summesbudget10011 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10011
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->summesbudget10019 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10019
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesbudget10039 = 
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10039
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesbudget10045 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10045
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesbudget10052 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10052
                AND  MONTH(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY MONTH(fecha)', 
                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->summesbudget10061 =
            DB::select(
                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                FROM [dbo].[budget]
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
                FROM [dbo].[data]
                WHERE variable_id = 10005
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtrireal10011 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10011
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtrireal10019 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10019
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtrireal10039 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10039
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtrireal10045 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10045
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtrireal10052 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10052
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtrireal10061 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[data]
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
                FROM [dbo].[budget]
                WHERE variable_id = 10005
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtribudget10011 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10011
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtribudget10019 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10019
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumtribudget10039 = 
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10039
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtribudget10045 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10045
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtribudget10052 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10052
                AND  DATEPART(QUARTER, fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY DATEPART(QUARTER, fecha)', 
                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumtribudget10061 =
            DB::select(
                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                FROM [dbo].[budget]
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
                FROM [dbo].[data]
                WHERE variable_id = 10005
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumanioreal10011 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10011
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumanioreal10019 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10019
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumanioreal10039 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10039
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumanioreal10045 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10045
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumanioreal10052 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[data]
                WHERE variable_id = 10052
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumanioreal10061 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[data]
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
                FROM [dbo].[budget]
                WHERE variable_id = 10005
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumaniobudget10011 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10011
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            ); 
            $this->sumaniobudget10019 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10019
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumaniobudget10039 = 
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10039
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumaniobudget10045 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10045
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumaniobudget10052 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10052
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
            $this->sumaniobudget10061 =
            DB::select(
                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                FROM [dbo].[budget]
                WHERE variable_id = 10061
                AND  YEAR(fecha) = ?
                AND  DATEPART(y, fecha) <= ?
                GROUP BY YEAR(fecha)',
                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
            );
        //FIN CALCULOS REUTILIZABLES PROCESOS            
        $where = ['variable.estado' => 1, 'categoria.area_id' => 1, 'categoria.estado' => 1, 'subcategoria.estado' => 1, 'data.fecha' => $this->date];
        $tableprocesos = DB::table('data')
                        ->join('variable','data.variable_id','=','variable.id')
                        ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                        ->join('categoria','subcategoria.categoria_id','=','categoria.id')
                        ->leftJoin('budget', function($join){
                            $join->on('data.variable_id', '=', 'budget.variable_id');
                            $join->on('data.fecha', '=', 'budget.fecha');
                        })
                        ->where($where)
                        ->select(
                            'data.variable_id as variable_id',
                            'data.fecha as fecha',
                            'variable.id as variable_id',
                            'variable.unidad as unidad',
                            'variable.nombre as nombre',
                            'variable.export as var_export',
                            'data.valor as dia_real',
                            'variable.subcategoria_id as subcategoria_id',
                            'budget.valor as dia_budget',
                            'data.valor as anio_budget'
                            )
                        ->orderBy('variable.orden', 'asc')
                        ->get();
                        
        $tablaprocesos = datatables()->of($tableprocesos)  
                ->addColumn('dia_real', function($data)
                {        
                    switch($data->variable_id)
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
                                where variable_id = 10004
                                AND valor <> 0) as B
                                ON A.fecha = B.fecha
                                WHERE  DATEPART(y, A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
                            ); 
                        break;                   
                        case 10008:
                        case 10037:
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
                                where variable_id = 10010
                                AND valor <> 0) as B
                                ON A.fecha = B.fecha
                                WHERE  DATEPART(y, A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
                            ); 
                        break;                   
                        case 10028:
                        case 10038:
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
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
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
                                [(int)date('z', strtotime($this->date)) + 1]
                            ); 
                        break;
                        default:                        
                            if(isset($data->dia_real)) 
                            { 
                                $d_real = $data->dia_real;
                                if($d_real > 100)
                                {
                                    return number_format(round($d_real), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($d_real, 2, '.', ',');
                                }
                            }        
                            else
                            {
                                return '-';
                            }                                       
                        break; 
                    } 
                    if(isset($d_real[0]->dia_real)) 
                    { 
                        $d_real = $d_real[0]->dia_real;
                        if($d_real > 100)
                        {
                            return number_format(round($d_real), 0, '.', ',');
                        }
                        else
                        {
                            return number_format($d_real, 2, '.', ',');
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
                            return number_format(round($d_budget), 0, '.', ',');
                        }
                        else
                        {
                            return number_format($d_budget, 2, '.', ',');
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
                                    FROM [dbo].[data]
                                    where variable_id = 10004) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10010) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10012) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                //10015 MMSA_AGLOM_Adición de Cemento (kg/t)                   
                                //(sumatoria.mensual(10067 MMSA_AGLOM_Cemento) * 1000)/ sumatoria.mesual(10019 MMSA_AGLOM_Mineral Aglomerado t)                      
                                $sumaproducto = DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) * 1000 as sumaproducto
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10067
                                    AND  MONTH(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    GROUP BY MONTH(fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= $this->summesreal10019; 
                            break;
                            case 10018:                                         
                                //10018 MMSA_AGLOM_Humedad %
                                //Promedio Ponderado Mensual(10019 MMSA_AGLOM_Mineral Aglomerado t,10018 MMSA_AGLOM_Humedad %)                        
                                $sumaproducto= DB::select(
                                    'SELECT MONTH(A.fecha),SUM(A.valor * B.valor) as sumaproducto FROM
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10018) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10024) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10025) as B
                                    ON A.fecha = B.fecha
                                    WHERE MONTH(A.fecha) =  ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10033) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10035) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10036) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                //Promedio Ponderado Mensual(10045 MMSA_SART_PLS a SART m3, 
                                //(((10043 MMSA_SART_Ley Cu Alimentada ppm) - (10044 MMSA_SART_Ley Cu Salida ppm)) * 100)/(10043 MMSA_SART_Ley Cu Alimentada ppm))                  
                                $sumaproducto= DB::select(
                                    'SELECT MONTH(A1.fecha),SUM((((A1.valor-A2.valor)*100)/A1.valor) * B.valor) as sumaproducto FROM
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10043
                                    AND valor <> 0 ) as A1
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10044) as A2
                                    ON A1.fecha = A2.fecha
                                    INNER JOIN
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10045) as B
                                    ON A2.fecha = B.fecha
                                    WHERE MONTH(A1.fecha) =  ?
                                    AND  DATEPART(y, A1.fecha) <=  ?
                                    GROUP BY MONTH(A1.fecha)', 
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
                                    FROM [dbo].[data]
                                    where variable_id = 10041) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10042) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10043) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10044) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                //Promedio Ponderado Mensual(10052 MMSA_ADR_PLS a Carbones m3, 
                                //((((10051 MMSA_ADR_Ley de Au PLS) - (10050 MMSA_ADR_Ley de Au BLS)) * 100) / (10051 MMSA_ADR_Ley de Au PLS))                  
                                $sumaproducto= DB::select(
                                    'SELECT MONTH(A1.fecha),SUM((((A1.valor-A2.valor)*100)/A1.valor) * B.valor) as sumaproducto FROM
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10051
                                    AND valor <> 0 ) as A1
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10050) as A2
                                    ON A1.fecha = A2.fecha
                                    INNER JOIN
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10052) as B
                                    ON A2.fecha = B.fecha
                                    WHERE MONTH(A1.fecha) =  ?
                                    AND  DATEPART(y, A1.fecha) <=  ?
                                    GROUP BY MONTH(A1.fecha)', 
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
                                    FROM [dbo].[data]
                                    where variable_id = 10050) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10051) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10054) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10055) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10059) as B
                                    ON A.fecha = B.fecha
                                    WHERE MONTH(A.fecha) =  ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10056) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10060) as B
                                    ON A.fecha = B.fecha
                                    WHERE MONTH(A.fecha) =  ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10057) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10058) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                        FROM [dbo].[data]
                                        where variable_id = 10005) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10004) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
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
                                        FROM [dbo].[data]
                                        where variable_id = 10011) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10010) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;                   
                                case 10022:
                                    //10022 MMSA_APILAM_PYS_Au Extraible Trituración Secundaria Apilado Camiones (oz)                  
                                    //SUMAMENSUAL((((10026 MMSA_APILAM_PYS_Recuperación %)/ 100) * (10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t) * (10024 MMSA_APILAM_PYS_Ley Au g/t)) / 31.1035)                               
                                    $mes_real = 
                                    DB::select(
                                        'SELECT MONTH(A.fecha), SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as mes_real FROM
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
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break;               
                                case 10023:
                                    //MMSA_APILAM_PYS_Au Trituración Secundaria Apilado Camiones oz                  
                                    //SUMAMENSUAL(((10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t)*(10024 MMSA_APILAM_PYS_Ley Au g/t) / 31.1035)                                    
                                    $mes_real = 
                                    DB::select(
                                        'SELECT MONTH(A.fecha), SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10025) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10024) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;  
                                case 10027:
                                    //10027: MMSA_APILAM_STACKER_Au Apilado Stacker (oz)                  
                                    //SUMATORIA MENSUAL(((10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)*(10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                                     
                                    $mes_real = 
                                    DB::select(
                                        'SELECT MONTH(A.fecha), SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10031) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10030) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
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
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;                    
                                case 10046:
                                    //Au Adsorbido - MMSA_ADR_Au Adsorbido (oz)                  
                                    //SUMAMENSUAL(((10052 MMSA_ADR_PLS a Carbones) * ((10051 MMSA_ADR_Ley de Au PLS)-(10050 MMSA_ADR_Ley de Au BLS))) / 31.1035)                               
                                    $mes_real = 
                                    DB::select(
                                        'SELECT MONTH(A.fecha), SUM((A.valor * (B.valor-C.valor))/31.1035) as mes_real FROM
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
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break; 
                                case 10053:
                                    //MMSA_LIXI_Au Lixiviado (oz)                  
                                    //SUMATORIA MENSUAL(((10061 MMSA_LIXI_Solución PLS)*(10057 MMSA_LIXI_Ley Au Solución PLS) / 31.1035 )                                     
                                    $mes_real = 
                                    DB::select(
                                        'SELECT MONTH(A.fecha), SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10061) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10057) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                default:
                                    $mes_real= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as mes_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY MONTH(fecha)', 
                                        [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                               
                                break; 
                            }
                            if(isset($mes_real[0]->mes_real))
                            {
                                $m_real = $mes_real[0]->mes_real;
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
                            if (in_array($data->variable_id, $this->promarray))//revisar si variable 10016 corresponde a este grupo
                            {
                                //Promedio valores <>0
                                $mes_real= DB::select(
                                    'SELECT MONTH(fecha) as month, AVG(valor) as mes_real
                                    FROM [dbo].[data]
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
                                                FROM [dbo].[data]
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
                                                FROM [dbo].[data]
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
                                                FROM [dbo].[data]
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
                                                FROM [dbo].[data]
                                                WHERE variable_id = 10031
                                                AND  MONTH(fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                            );                                     
                                            $suma2= DB::select(
                                                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                FROM [dbo].[data]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10004) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10005) as B
                                    ON A.fecha = B.fecha
                                    WHERE MONTH(A.fecha) =  ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10010) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10012) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10015) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
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
                                        return number_format(round($m_budget), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($m_budget, 2, '.', ',');
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10018) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10024) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10025) as B
                                    ON A.fecha = B.fecha
                                    WHERE MONTH(A.fecha) =  ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10033) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10035) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10036) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10040) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10041) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10042) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10043) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10044) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10049) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10050) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10051) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10054) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10055) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10059) as B
                                    ON A.fecha = B.fecha
                                    WHERE MONTH(A.fecha) =  ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10056) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10060) as B
                                    ON A.fecha = B.fecha
                                    WHERE MONTH(A.fecha) =  ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10057) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10058) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    return number_format(round($m_budget), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($m_budget, 2, '.', ',');
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
                                FROM [dbo].[budget]
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
                                    return number_format(round($m_budget), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($m_budget, 2, '.', ',');
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
                                    FROM [dbo].[budget]
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
                                        return number_format(round($m_budget), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($m_budget, 2, '.', ',');
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
                                                FROM [dbo].[budget]
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
                                                FROM [dbo].[budget]
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
                                                FROM [dbo].[budget]
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
                                                FROM [dbo].[budget]
                                                WHERE variable_id = 10031
                                                AND  MONTH(fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                            );                                     
                                            $suma2= DB::select(
                                                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                FROM [dbo].[budget]
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
                                                return number_format(round($m_budget), 0, '.', ',');
                                            }
                                            else
                                            {
                                                return number_format($m_budget, 2, '.', ',');
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
                                    FROM [dbo].[data]
                                    where variable_id = 10004) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10005) as B
                                    ON A.fecha = B.fecha
                                    WHERE DATEPART(QUARTER, A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10010) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10012) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                //10015 MMSA_AGLOM_Adición de Cemento (kg/t)                   
                                //(sumatoria.trimestral(10067 MMSA_AGLOM_Cemento) * 1000)/ sumatoria.trimestral(10019 MMSA_AGLOM_Mineral Aglomerado t)                      
                                $sumaproducto= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) * 1000 as sumaproducto
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10067
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );                                    
                                $suma= $this->sumtrireal10019; 
                            break;
                            case 10018:                                         
                                //10018 MMSA_AGLOM_Humedad %
                                //Promedio Ponderado Trimestral(10019 MMSA_AGLOM_Mineral Aglomerado t,10018 MMSA_AGLOM_Humedad %)                        
                                $sumaproducto= DB::select(
                                    'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(A.valor * B.valor) as sumaproducto FROM
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10018) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10024) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10025) as B
                                    ON A.fecha = B.fecha
                                    WHERE DATEPART(QUARTER, A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10033) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10035) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10036) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                //Promedio Ponderado Trimestral(10045 MMSA_SART_PLS a SART m3, 
                                //(((10043 MMSA_SART_Ley Cu Alimentada ppm) - (10044 MMSA_SART_Ley Cu Salida ppm)) * 100) / (10043 MMSA_SART_Ley Cu Alimentada ppm)               
                                $sumaproducto= DB::select(
                                    'SELECT DATEPART(QUARTER, A1.fecha) as quarter, SUM((((A1.valor-A2.valor)*100)/A1.valor) * B.valor) as sumaproducto FROM
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10043
                                    AND valor <> 0) as A1
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10044) as A2
                                    ON A1.fecha = A2.fecha                                        
                                    INNER JOIN 
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10045) as B
                                    ON A2.fecha = B.fecha
                                    WHERE DATEPART(QUARTER, A1.fecha) = ?
                                    AND  DATEPART(y, A1.fecha) <=  ?
                                    GROUP BY DATEPART(QUARTER, A1.fecha)', 
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
                                    FROM [dbo].[data]
                                    where variable_id = 10041) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10042) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10043) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10044) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                //Promedio Ponderado Trimestral(10052 MMSA_ADR_PLS a Carbones m3, 
                                //((((10051 MMSA_ADR_Ley de Au PLS) - (10050 MMSA_ADR_Ley de Au BLS)) * 100) / (10051 MMSA_ADR_Ley de Au PLS))               
                                $sumaproducto= DB::select(
                                    'SELECT DATEPART(QUARTER, A1.fecha) as quarter, SUM((((A1.valor-A2.valor)*100)/A1.valor) * B.valor) as sumaproducto FROM
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10051
                                    AND valor <> 0) as A1
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10050) as A2
                                    ON A1.fecha = A2.fecha                                        
                                    INNER JOIN 
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10052) as B
                                    ON A2.fecha = B.fecha
                                    WHERE DATEPART(QUARTER, A1.fecha) = ?
                                    AND  DATEPART(y, A1.fecha) <=  ?
                                    GROUP BY DATEPART(QUARTER, A1.fecha)', 
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
                                    FROM [dbo].[data]
                                    where variable_id = 10050) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10051) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10054) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10055) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10059) as B
                                    ON A.fecha = B.fecha
                                    WHERE DATEPART(QUARTER, A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10056) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10060) as B
                                    ON A.fecha = B.fecha
                                    WHERE DATEPART(QUARTER, A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10057) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10058) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    return number_format(round($t_real), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($t_real, 2, '.', ',');
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
                                    //SUMATORIA TRIMESTRAL(((10005 MMSA_TP_Mineral Triturado t)*(10004 MMSA_TP_Ley Au g/t)) / 31.1035)                                 
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM((A.valor * B.valor)/31.1035) as trimestre_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10005) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10004) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break;
                                case 10008:   
                                case 10037:
                                    //10008: MMSA_TP_Au Triturado                  
                                    //SUMATORIA TRIMESTRAL(((10011 MMSA_TP_Mineral Triturado t)*(10010 MMSA_TP_Ley Au g/t)) / 31.1035)                                 
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM((A.valor * B.valor)/31.1035) as trimestre_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10011) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10010) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break;                   
                                case 10022:
                                    //10022 MMSA_APILAM_PYS_Au Extraible Trituración Secundaria Apilado Camiones (oz)                  
                                    //SUMATRIMESTRAL((((10026 MMSA_APILAM_PYS_Recuperación %)/ 100) * (10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t) * (10024 MMSA_APILAM_PYS_Ley Au g/t)) / 31.1035)                               
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as trimestre_real FROM
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
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break;               
                                case 10023:
                                    //MMSA_APILAM_PYS_Au Trituración Secundaria Apilado Camiones oz                  
                                    //SUMATORIA TRIMESTRAL(((10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t)*(10024 MMSA_APILAM_PYS_Ley Au g/t) / 31.1035)                              
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM((A.valor * B.valor)/31.1035) as trimestre_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10025) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10024) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break; 
                                case 10027:   
                                    //10027: MMSA_APILAM_STACKER_Au Apilado Stacker oz                  
                                    //SUMATORIA TRIMESTRAL(((10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker T)*(10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                                 
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM((A.valor * B.valor)/31.1035) as trimestre_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10031) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10030) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break;
                                case 10028:
                                case 10038:
                                    //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                    //SUMATRIMESTRAL((((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                               
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as trimestre_real FROM
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
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break;                  
                                case 10046:
                                    //Au Adsorbido - MMSA_ADR_Au Adsorbido (oz)                  
                                    //SUMATRIMESTRAL(((10052 MMSA_ADR_PLS a Carbones) * ((10051 MMSA_ADR_Ley de Au PLS)-(10050 MMSA_ADR_Ley de Au BLS))) / 31.1035)                               
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM((A.valor * (B.valor-C.valor))/31.1035) as trimestre_real FROM
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
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break; 
                                case 10053:   
                                    //MMSA_LIXI_Au Lixiviado (oz)                  
                                    //SUMATORIA TRIMESTRAL(((10061 MMSA_LIXI_Solución PLS)*(10057 MMSA_LIXI_Ley Au Solución PLS) / 31.1035)                                 
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM((A.valor * B.valor)/31.1035) as trimestre_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10061) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10057) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break;
                                default:
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break;
                            }
                            if(isset($trimestre_real[0]->trimestre_real))
                            {
                                $t_real = $trimestre_real[0]->trimestre_real;
                                if($t_real > 100)
                                {
                                    return number_format(round($t_real), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($t_real, 2, '.', ',');
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
                                    FROM [dbo].[data]
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
                                        return number_format(round($t_real), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($t_real, 2, '.', ',');
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
                                                FROM [dbo].[data]
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
                                                FROM [dbo].[data]
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
                                                FROM [dbo].[data]
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
                                                FROM [dbo].[data]
                                                WHERE variable_id = 10031
                                                AND  DATEPART(QUARTER, fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                            );                                     
                                            $suma2= DB::select(
                                                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                FROM [dbo].[data]
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
                                                return number_format(round($t_real), 0, '.', ',');
                                            }
                                            else
                                            {
                                                return number_format($t_real, 2, '.', ',');
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10004) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10005) as B
                                    ON A.fecha = B.fecha
                                    WHERE DATEPART(QUARTER, A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10010) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10012) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10015) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
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
                                        return number_format(round($t_budget), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($t_budget, 2, '.', ',');
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10018) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10024) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10025) as B
                                    ON A.fecha = B.fecha
                                    WHERE DATEPART(QUARTER, A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10033) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10035) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10036) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10040) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10041) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10042) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10043) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10044) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10049) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10050) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10051) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10054) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10055) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10059) as B
                                    ON A.fecha = B.fecha
                                    WHERE DATEPART(QUARTER, A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10056) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10060) as B
                                    ON A.fecha = B.fecha
                                    WHERE DATEPART(QUARTER, A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10057) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10058) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    return number_format(round($t_budget), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($t_budget, 2, '.', ',');
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
                                FROM [dbo].[budget]
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
                                    return number_format(round($t_budget), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($t_budget, 2, '.', ',');
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
                                    FROM [dbo].[budget]
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
                                        return number_format(round($t_budget), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($t_budget, 2, '.', ',');
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
                                                FROM [dbo].[budget]
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
                                                FROM [dbo].[budget]
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
                                                FROM [dbo].[budget]
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
                                                FROM [dbo].[budget]
                                                WHERE variable_id = 10031
                                                AND  DATEPART(QUARTER, fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                                            );                                     
                                            $suma2= DB::select(
                                                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                FROM [dbo].[budget]
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
                                                return number_format(round($t_budget), 0, '.', ',');
                                            }
                                            else
                                            {
                                                return number_format($t_budget, 2, '.', ',');
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
                                    FROM [dbo].[data]
                                    where variable_id = 10004) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10005) as B
                                    ON A.fecha = B.fecha
                                    WHERE YEAR(A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY YEAR(A.fecha)',
                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10010) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10012) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                //10015 MMSA_AGLOM_Adición de Cemento (kg/t)                   
                                //(sumatoria.anual(10067 MMSA_AGLOM_Cemento) * 1000)/ sumatoria.anual(10019 MMSA_AGLOM_Mineral Aglomerado t)                      
                                $sumaproducto= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) * 1000 as sumaproducto
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10067
                                    AND  YEAR(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    GROUP BY YEAR(fecha)', 
                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                    
                                $suma= $this->sumanioreal10019; 
                            break;
                            case 10018:                                         
                                //10018 MMSA_AGLOM_Humedad %
                                //Promedio Ponderado Anual(10019 MMSA_AGLOM_Mineral Aglomerado t,10018 MMSA_AGLOM_Humedad %)                        
                                $sumaproducto= DB::select(
                                    'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10018) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10024) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10025) as B
                                    ON A.fecha = B.fecha
                                    WHERE YEAR(A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY YEAR(A.fecha)',
                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10033) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10035) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10036) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                //Promedio Ponderado Anual(10045 MMSA_SART_PLS a SART m3, ((10043 MMSA_SART_Ley Cu Alimentada ppm) - (10044 MMSA_SART_Ley Cu Salida ppm)) * 100))                   
                                $sumaproducto= DB::select(
                                    'SELECT YEAR(A1.fecha) as year, SUM((((A1.valor-A2.valor)*100)/A1.valor) * B.valor) as sumaproducto FROM
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10043
                                    AND valor <> 0) as A1
                                    INNER JOIN
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10044) as A2
                                    ON A1.fecha = A2.fecha
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10045) as B
                                    ON A2.fecha = B.fecha
                                    WHERE YEAR(A1.fecha) = ?
                                    AND  DATEPART(y, A1.fecha) <=  ?
                                    GROUP BY YEAR(A1.fecha)',
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
                                    FROM [dbo].[data]
                                    where variable_id = 10041) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10042) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10043) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10044) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                //Promedio Ponderado Anual((((10051 MMSA_ADR_Ley de Au PLS) - (10050 MMSA_ADR_Ley de Au BLS)) * 100) / (10051 MMSA_ADR_Ley de Au PLS))                   
                                $sumaproducto= DB::select(
                                    'SELECT YEAR(A1.fecha) as year, SUM((((A1.valor-A2.valor)*100)/A1.valor) * B.valor) as sumaproducto FROM
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10051
                                    AND valor <> 0) as A1
                                    INNER JOIN
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10050) as A2
                                    ON A1.fecha = A2.fecha
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10052) as B
                                    ON A2.fecha = B.fecha
                                    WHERE YEAR(A1.fecha) = ?
                                    AND  DATEPART(y, A1.fecha) <=  ?
                                    GROUP BY YEAR(A1.fecha)',
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
                                    FROM [dbo].[data]
                                    where variable_id = 10050) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10051) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10054) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10055) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10059) as B
                                    ON A.fecha = B.fecha
                                    WHERE YEAR(A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY YEAR(A.fecha)',
                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10056) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
                                    where variable_id = 10060) as B
                                    ON A.fecha = B.fecha
                                    WHERE YEAR(A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY YEAR(A.fecha)',
                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10057) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    FROM [dbo].[data]
                                    where variable_id = 10058) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[data]
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
                                    return number_format(round($a_real), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($a_real, 2, '.', ',');
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
                                    //SUMATORIA ANUAL(((10005 MMSA_TP_Mineral Triturado t)*(10004 MMSA_TP_Ley Au g/t)) / 31.1035)    
                                    $anio_real= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM((A.valor * B.valor)/31.1035) as anio_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10005) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10004) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10008: 
                                case 10037:  
                                    //10008: MMSA_TP_Au Triturado                  
                                    //SUMATORIA ANUAL(((10011 MMSA_TP_Mineral Triturado t)*(10010 MMSA_TP_Ley Au g/t)) / 31.1035)    
                                    $anio_real= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM((A.valor * B.valor)/31.1035) as anio_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10011) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10010) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;                 
                                case 10022:
                                    //10022 MMSA_APILAM_PYS_Au Extraible Trituración Secundaria Apilado Camiones (oz)                  
                                    //SUMAANUAL((((10026 MMSA_APILAM_PYS_Recuperación %)/ 100) * (10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t) * (10024 MMSA_APILAM_PYS_Ley Au g/t)) / 31.1035)     
                                    $anio_real= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as anio_real FROM
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
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break;             
                                case 10023:
                                    //MMSA_APILAM_PYS_Au Trituración Secundaria Apilado Camiones oz                  
                                    //SUMATORIA ANUAL(((10025 MMSA_APILAM_PYS_Mineral Trituración Secundaria Apilado Camiones t)*(10024 MMSA_APILAM_PYS_Ley Au g/t) / 31.1035)     
                                    $anio_real= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM((A.valor * B.valor)/31.1035) as anio_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10025) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10024) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break; 
                                case 10027:   
                                    //10027: MMSA_APILAM_STACKER_Au Apilado Stacker oz                  
                                    //SUMATORIA ANUAL(((10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t)*(10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)    
                                    $anio_real= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM((A.valor * B.valor)/31.1035) as anio_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10031) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10030) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                case 10028: 
                                case 10038:  
                                    //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                    //SUMAANUAL((((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)     
                                    $anio_real= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as anio_real FROM
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
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;                
                                case 10046:
                                    //Au Adsorbido - MMSA_ADR_Au Adsorbido (oz)                  
                                    //SUMAANUAL(((10052 MMSA_ADR_PLS a Carbones) * ((10051 MMSA_ADR_Ley de Au PLS)-(10050 MMSA_ADR_Ley de Au BLS))) / 31.1035)     
                                    $anio_real= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM((A.valor * (B.valor-C.valor))/31.1035) as anio_real FROM
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
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break; 
                                case 10053:   
                                    //MMSA_LIXI_Au Lixiviado (oz)                  
                                    //SUMATORIA ANUAL(((10061 MMSA_LIXI_Solución PLS)*(10057 MMSA_LIXI_Ley Au Solución PLS) / 31.1035)    
                                    $anio_real= DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM((A.valor * B.valor)/31.1035) as anio_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10061) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10057) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    ); 
                                break;
                                default:
                                    $anio_real= DB::select(
                                        'SELECT YEAR(fecha) as year, SUM(valor) as anio_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  YEAR(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        GROUP BY YEAR(fecha)', 
                                        [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );
                                break;
                            }
                            if(isset($anio_real[0]->anio_real))
                            {
                                $a_real = $anio_real[0]->anio_real;
                                if($a_real > 100)
                                {
                                    return number_format(round($a_real), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($a_real, 2, '.', ',');
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
                                    FROM [dbo].[data]
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
                                        return number_format(round($a_real), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($a_real, 2, '.', ',');
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
                                                FROM [dbo].[data]
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
                                                FROM [dbo].[data]
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
                                                FROM [dbo].[data]
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
                                                FROM [dbo].[data]
                                                WHERE variable_id = 10031
                                                AND  YEAR(fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                GROUP BY YEAR(fecha)', 
                                                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                            );                                     
                                            $suma2= DB::select(
                                                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                FROM [dbo].[data]
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
                                                return number_format(round($a_real), 0, '.', ',');
                                            }
                                            else
                                            {
                                                return number_format($a_real, 2, '.', ',');
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10004) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10005) as B
                                    ON A.fecha = B.fecha
                                    WHERE YEAR(A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY YEAR(A.fecha)',
                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10010) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10012) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10015) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
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
                                        return number_format(round($a_budget), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($a_budget, 2, '.', ',');
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10018) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10024) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10025) as B
                                    ON A.fecha = B.fecha
                                    WHERE YEAR(A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY YEAR(A.fecha)',
                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10033) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10035) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10036) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10040) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10041) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10042) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10043) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10044) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10049) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10050) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10051) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10054) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10055) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10059) as B
                                    ON A.fecha = B.fecha
                                    WHERE YEAR(A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY YEAR(A.fecha)',
                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10056) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
                                    where variable_id = 10060) as B
                                    ON A.fecha = B.fecha
                                    WHERE YEAR(A.fecha) = ?
                                    AND  DATEPART(y, A.fecha) <=  ?
                                    GROUP BY YEAR(A.fecha)',
                                    [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                );                                     
                                $suma= DB::select(
                                    'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10057) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    FROM [dbo].[budget]
                                    where variable_id = 10058) as A
                                    INNER JOIN   
                                    (SELECT fecha, variable_id, [valor]
                                    FROM [dbo].[budget]
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
                                    return number_format(round($a_budget), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($a_budget, 2, '.', ',');
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
                                FROM [dbo].[budget]
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
                                    return number_format(round($a_budget), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($a_budget, 2, '.', ',');
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
                                    FROM [dbo].[budget]
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
                                        return number_format(round($a_budget), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($a_budget, 2, '.', ',');
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
                                                FROM [dbo].[budget]
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
                                                FROM [dbo].[budget]
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
                                                FROM [dbo].[budget]
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
                                                FROM [dbo].[budget]
                                                WHERE variable_id = 10031
                                                AND  YEAR(fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                GROUP BY YEAR(fecha)', 
                                                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                            );                                     
                                            $suma2= DB::select(
                                                'SELECT YEAR(fecha) as year, SUM(valor) as suma
                                                FROM [dbo].[budget]
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
                                                return number_format(round($a_budget), 0, '.', ',');
                                            }
                                            else
                                            {
                                                return number_format($a_budget, 2, '.', ',');
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
                ->rawColumns(['categoria','subcategoria','dia_real','dia_budget','mes_real','mes_budget','trimestre_real','anio_real'])
                ->make(true);

        //INICIO CALCULOS REUTILIZABLES MINA
            $year = (int)date('Y', strtotime($this->date));
            $quarter = ceil(date('m', strtotime($this->date))/3);
            $month = (int)date('m', strtotime($this->date)); 
            $day = (int)date('d', strtotime($this->date));
            $daypart = (int)date('z', strtotime($this->date)) + 1;
            //INICIO MES REAL
                $this->summesrealton = 
                DB::select(
                    'SELECT v.id AS variable_id, d.mes_real AS mes_real FROM
                    (SELECT variable_id, SUM(valor) AS mes_real
                    FROM [dbo].[data] 
                    WHERE variable_id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)
                    AND MONTH(fecha) = '.$month.'
                    AND DATEPART(y, fecha) <= '.$daypart.'
                    AND YEAR(fecha) = '.$year.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );
                $this->summesrealonz =
                DB::select(
                    'SELECT 10072 as variable_id, SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10070) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10071) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10075, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10073) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10074) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10078, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10076) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10077) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10081, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10079) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10080) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'UNION 
                    SELECT 10084, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10082) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10083) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10087, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10085) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10086) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10090, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10088) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10089) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10095, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10093) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10094) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10099, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10097) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10098) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10102, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10100) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10101) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10105, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10103) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10104) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10108, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10106) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
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
                    FROM [dbo].[data]
                    WHERE variable_id IN (10114,10115,10116)
                    AND  MONTH(fecha) = '.$month.'
                    AND  DATEPART(y, fecha) <= '.$daypart.'
                    AND YEAR(fecha) = '.$year.'
                    AND valor <> 0
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10114,10115,10116)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );
            
            //FIN MES REAL
            //INICIO MES BUDGET                
                $this->summesbudgetton = 
                DB::select(
                    'SELECT v.id AS variable_id, ((d.valor/DAY(d.fecha))*'.$day.') AS mes_budget FROM
                    (SELECT variable_id, fecha, valor
                    FROM [dbo].[budget]
                    WHERE variable_id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)
                    AND MONTH(fecha) = '.$month.'                        
                    AND YEAR(fecha) = '.$year.') AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );                    
                $this->summesbudgetonz =
                DB::select(
                    'SELECT 10072 as variable_id, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10070) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10071) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10075, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10073) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10074) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10078, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10076) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10077) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10081, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10079) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10080) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10084, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10082) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10083) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10087, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10085) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10086) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10090, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10088) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10089) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10095, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10093) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10094) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10099, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10097) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10098) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10102, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10100) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10101) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10105, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10103) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10104) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10108, ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.' as mes_budget FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10106) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10107) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.''
                );
                $this->avgmesbudgetpor =
                DB::select(
                    'SELECT v.id AS variable_id, d.valor AS mes_budget FROM
                    (SELECT variable_id, valor
                    FROM [dbo].[budget]
                    WHERE variable_id IN (10114,10115,10116)
                    AND  MONTH(fecha) = '.$month.'
                    AND YEAR(fecha) = '.$year.') AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10114,10115,10116)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );
            //FIN MES BUDGET
            //INICIO TRIMESTRE REAL
                $this->sumtrirealton = 
                DB::select(
                    'SELECT v.id AS variable_id, d.tri_real AS tri_real FROM
                    (SELECT variable_id, SUM(valor) AS tri_real
                    FROM [dbo].[data] 
                    WHERE variable_id IN (10070, 10073, 10076, 10079, 10082, 10085,10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)
                    AND DATEPART(QUARTER, fecha) = '.$quarter.'
                    AND DATEPART(y, fecha) <= '.$daypart.'
                    AND YEAR(fecha) = '.$year.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );

                $this->sumtrirealonz =
                DB::select(
                    'SELECT 10072 as variable_id, SUM((A.valor * B.valor)/31.1035) as tri_real FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10070) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10071) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10075, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10073) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10074) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10078, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10076) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10077) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10081, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10079) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10080) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'UNION 
                    SELECT 10084, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10082) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10083) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10087, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10085) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10086) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10090, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10088) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10089) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10095, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10093) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10094) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10099, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10097) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10098) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10102, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10100) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10101) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10105, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10103) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10104) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10108, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10106) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10107) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    AND YEAR(A.fecha) = '.$year.''
                );

                $this->avgtrirealpor =
                    DB::select(
                        'SELECT v.id AS variable_id, d.mes_real AS mes_real FROM
                        (SELECT variable_id, AVG(valor) AS mes_real
                        FROM [dbo].[data]
                        WHERE variable_id IN (10114,10115,10116)
                        AND  DATEPART(QUARTER, fecha) = '.$quarter.'
                        AND  DATEPART(y, fecha) <= '.$daypart.'
                        AND YEAR(fecha) = '.$year.'
                        AND valor <> 0
                        GROUP BY variable_id) AS d
                        RIGHT JOIN
                        (SELECT id 
                        FROM [dbo].[variable] 
                        WHERE id IN (10114,10115,10116)) AS v
                        ON d.variable_id = v.id
                        ORDER BY id ASC'
                    );
            //FIN TRIMESTRE REAL
            //INICIO TRIMESTRE BUDGET
                $this->sumtribudgetton = 
                DB::select(
                    'SELECT v.id AS variable_id, d.valor as trimestre_budget
                    FROM
                    (SELECT variable_id, 
                    SUM(CASE	
                        WHEN MONTH(fecha) < '.$month.' THEN valor
                        WHEN MONTH(fecha) = '.$month.' THEN (valor/DAY(fecha)) * '.$day.'
                    END) AS valor
                    FROM [dbo].[budget]
                    WHERE variable_id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)
                    AND DATEPART(QUARTER, fecha) = '.$quarter.'
                    AND MONTH(fecha) <= '.$month.'
                    AND YEAR(fecha) = '.$year.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );

                $this->sumtribudgetonz =
                DB::select(
                    'SELECT 10072 as variable_id,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10070) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10071) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10075,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10073) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10074) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10078,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10076) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10077) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10081,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10079) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10080) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10084,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10082) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10083) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10087,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10085) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10086) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10090,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10088) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10089) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10095,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10093) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10094) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10099,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10097) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10098) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10102,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10100) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10101) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10105,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10103) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10104) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10108,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS trimestre_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10106) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10107) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.''
                );
                $this->avgtribudgetpor =
                DB::select(
                    'SELECT v.id AS variable_id, (d.valor/(CASE WHEN d.dias = 0 THEN NULL ELSE d.dias END)) AS trimestre_budget FROM
                    (SELECT variable_id, 
                    SUM(CASE	
                        WHEN MONTH(fecha) < '.$month.' THEN valor * DAY(fecha)
                        WHEN MONTH(fecha) = '.$month.' THEN valor * '.$day.'
                    END) AS valor,
                    SUM(CASE	
                        WHEN MONTH(fecha) < '.$month.' THEN DAY(fecha)
                        WHEN MONTH(fecha) = '.$month.' THEN '.$day.'
                    END) AS dias
                    FROM [dbo].[budget]
                    WHERE variable_id IN (10114,10115,10116)
                    AND DATEPART(QUARTER, fecha) = '.$quarter.'
                    AND MONTH(fecha) <= '.$month.'
                    AND YEAR(fecha) = '.$year.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10114,10115,10116)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );
            //FIN TRIMESTRE BUDGET
            //INICIO ANIO REAL
                $this->sumaniorealton = 
                DB::select(
                    'SELECT v.id AS variable_id, d.valor AS anio_real FROM
                    (SELECT variable_id, SUM(valor) AS valor
                    FROM [dbo].[data] 
                    WHERE variable_id IN (10070, 10073, 10076, 10079, 10082, 10085,10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)
                    AND YEAR(fecha) = '.$year.'
                    AND DATEPART(y, fecha) <= '.$daypart.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );

                $this->sumaniorealonz =
                DB::select(
                    'SELECT 10072 as variable_id, SUM((A.valor * B.valor)/31.1035) as anio_real FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10070) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10071) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10075, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10073) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10074) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10078, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10076) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10077) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10081, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10079) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10080) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10084, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10082) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10083) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10087, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10085) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10086) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10090, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10088) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10089) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10095, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10093) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10094) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10099, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10097) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10098) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10102, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10100) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10101) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10105, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10103) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10104) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.'
                    UNION 
                    SELECT 10108, SUM((A.valor * B.valor)/31.1035) FROM
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10106) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[data]
                    where variable_id = 10107) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND DATEPART(y, A.fecha) <= '.$daypart.''
                );

                $this->avganiorealpor =
                DB::select(
                    'SELECT v.id AS variable_id, d.mes_real AS mes_real FROM
                    (SELECT variable_id, AVG(valor) AS mes_real
                    FROM [dbo].[data]
                    WHERE variable_id IN (10114,10115,10116)
                    AND  YEAR(fecha) = '.$year.'
                    AND  DATEPART(y, fecha) <= '.$daypart.'
                    AND valor <> 0
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10114,10115,10116)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );
            //FIN ANIO REAL
            //INICIO ANIO BUDGET
                $this->sumaniobudgetton = 
                DB::select(
                    'SELECT v.id AS variable_id, d.valor as anio_budget
                    FROM
                    (SELECT variable_id, 
                    SUM(CASE	
                        WHEN MONTH(fecha) < '.$month.' THEN valor
                        WHEN MONTH(fecha) = '.$month.' THEN (valor/DAY(fecha)) * '.$day.'
                    END) AS valor
                    FROM [dbo].[budget]
                    WHERE variable_id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)
                    AND MONTH(fecha) <= '.$month.'
                    AND YEAR(fecha) = '.$year.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );

                $this->sumaniobudgetonz =
                DB::select(
                    'SELECT 10072 as variable_id,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10070) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10071) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10075,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10073) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10074) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10078,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10076) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10077) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10081,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10079) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10080) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10084,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10082) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10083) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10087,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10085) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10086) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10090,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10088) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10089) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10095,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10093) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10094) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10099,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10097) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10098) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10102,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10100) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10101) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10105,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10103) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10104) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.'
                    UNION 
                    SELECT 10108,
                    SUM(CASE	
                        WHEN MONTH(A.fecha) < '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN ( ( (A.valor/DAY(A.fecha)) *  B.valor ) / 31.1035) * '.$day.'
                    END) AS anio_budget
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10106) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10107) as B
                    ON A.fecha = B.fecha
                    WHERE YEAR(A.fecha) = '.$year.'
                    AND MONTH(A.fecha) <= '.$month.''
                );
                $this->avganiobudgetpor =
                DB::select(
                    'SELECT v.id AS variable_id, (d.valor/(CASE WHEN d.dias = 0 THEN NULL ELSE d.dias END)) AS trimestre_budget FROM
                    (SELECT variable_id, 
                    SUM(CASE	
                        WHEN MONTH(fecha) < '.$month.' THEN valor * DAY(fecha)
                        WHEN MONTH(fecha) = '.$month.' THEN valor * '.$day.'
                    END) AS valor,
                    SUM(CASE	
                        WHEN MONTH(fecha) < '.$month.' THEN DAY(fecha)
                        WHEN MONTH(fecha) = '.$month.' THEN '.$day.'
                    END) AS dias
                    FROM [dbo].[budget]
                    WHERE variable_id IN (10114,10115,10116)                        
                    AND YEAR(fecha) = '.$year.'
                    AND MONTH(fecha) <= '.$month.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10114,10115,10116)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );
            //FIN ANIO BUDGET
        //FIN CALCULOS REUTILIZABLES MINA
        $where = ['variable.estado' => 1, 'categoria.area_id' => 2, 'categoria.estado' => 1, 'subcategoria.estado' => 1, 'data.fecha' => $this->date];
        $tablemina = DB::table('data')
                        ->join('variable','data.variable_id','=','variable.id')
                        ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                        ->join('categoria','subcategoria.categoria_id','=','categoria.id')
                        ->where($where)
                        ->select(
                            'data.variable_id as variable_id',
                            'data.fecha as fecha',
                            'variable.id as variable_id',
                            'variable.nombre as nombre', 
                            'variable.orden as var_orden',
                            'variable.unidad as unidad',
                            'data.valor as dia_real',
                            'variable.subcategoria_id as subcategoria_id',
                            'data.valor as anio_budget'
                            )
                        ->orderBy('variable.orden', 'asc')
                        ->get();
                        
        $tablamina = datatables()->of($tablemina)  
            ->addColumn('dia_real', function($data)
            {        
                switch($data->variable_id)
                {                                    
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
                            [(int)date('z', strtotime($this->date)) + 1]
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
                            [(int)date('z', strtotime($this->date)) + 1]
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
                            [(int)date('z', strtotime($this->date)) + 1]
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
                            [(int)date('z', strtotime($this->date)) + 1]
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
                            [(int)date('z', strtotime($this->date)) + 1]
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
                            [(int)date('z', strtotime($this->date)) + 1]
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
                            [(int)date('z', strtotime($this->date)) + 1]
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
                            [(int)date('z', strtotime($this->date)) + 1]
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
                            [(int)date('z', strtotime($this->date)) + 1]
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
                            [(int)date('z', strtotime($this->date)) + 1]
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
                            [(int)date('z', strtotime($this->date)) + 1]
                        ); 
                    break;   
                    default:                        
                        if(isset($data->dia_real)) 
                        { 
                            $d_real = $data->dia_real;
                            if($d_real > 100)
                            {
                                return number_format(round($d_real), 0, '.', ',');
                            }
                            else
                            {
                                return number_format($d_real, 2, '.', ',');
                            }
                        }        
                        else
                        {
                            return '-';
                        }                                       
                    break; 
                } 
                if(isset($d_real[0]->dia_real)) 
                { 
                    $d_real = $d_real[0]->dia_real;
                    if($d_real > 100)
                    {
                        return number_format(round($d_real), 0, '.', ',');
                    }
                    else
                    {
                        return number_format($d_real, 2, '.', ',');
                    }
                }        
                else
                {
                    return '-';
                }                                           

            })
            ->addColumn('dia_budget', function($data)
            {
                switch($data->unidad)
                {
                    case 't':
                        $dia_budget= DB::select(
                            'SELECT valor/DAY(fecha) as dia_budget
                            FROM [dbo].[budget]
                            WHERE variable_id = ?
                            AND MONTH(fecha) = ?',
                            [$data->variable_id, date('m', strtotime($this->date))]

                        ); 
                    break;
                    case 'g/t':
                    case '%':
                        $dia_budget= DB::select(
                            'SELECT valor as dia_budget
                            FROM [dbo].[budget]
                            WHERE variable_id = ?
                            AND MONTH(fecha) = ?',
                            [$data->variable_id, date('m', strtotime($this->date))]

                        );
                    break;
                    case 'oz':
                        switch($data->variable_id)
                        {                                    
                            case 10072:
                                //Au ROM a Trituradora oz                  
                                //(10070*10071 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10070) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10071) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                ); 
                            break;
                            case 10075:
                                //Au ROM Alta Ley a Stockpile oz                  
                                //(10073*10074 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10073) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10074) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                );
                            break;
                            case 10078:
                                //Au ROM Media Ley a Stockpile oz                  
                                //(10076*10077 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10076) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10077) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                );
                            break;
                            case 10081:
                                //Au ROM Baja Ley a Stockpile oz                  
                                //(10079*10080 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10079) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10080) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                );
                            break;
                            case 10084:
                                //Total Au ROM a Stockpiles oz                  
                                //(10082*10083 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10082) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10083) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                );
                            break;                            
                            case 10090:
                                //Total Au Minado oz                  
                                //(10088*10089 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10088) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10089) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                );
                            break;                            
                            case 10095:
                                //Au Alta Ley Stockpile a Trituradora oz                  
                                //(10093*10094 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10093) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10094) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                );
                            break;                            
                            case 10099:
                                //Au Media Ley Stockpile a Trituradora oz                  
                                //(10097*10098 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10097) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10098) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                );
                            break;                            
                            case 10102:
                                //Au Baja Ley Stockpile a Trituradora oz                  
                                //(10100*10101 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10100) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10101) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                );
                            break;                            
                            case 10105:
                                //Au de Stockpiles a Trituradora oz                  
                                //(10103*10104 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10103) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10104) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                );
                            break;                            
                            case 10108:
                                //Au (ROM+Stockpiles) a Trituradora oz                  
                                //(10106*10107 / 31.1035                                     
                                $dia_budget = 
                                DB::select(
                                    'SELECT ((A.valor/DAY(A.fecha)) * B.valor)/31.1035 as dia_budget FROM
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10106) as A
                                    INNER JOIN   
                                    (SELECT fecha, valor
                                    FROM [dbo].[budget]
                                    where variable_id = 10107) as B
                                    ON MONTH(A.fecha) = MONTH(B.fecha)
                                    WHERE MONTH(A.fecha) = ?',
                                    [date('m', strtotime($this->date))]
                                );
                            break;   
                        } 
                    break;
                }
                if(isset($dia_budget[0]->dia_budget)) 
                { 
                    $d_budget = $dia_budget[0]->dia_budget;
                    if($d_budget > 100)
                    {
                        return number_format(round($d_budget), 0, '.', ',');
                    }
                    else
                    {
                        return number_format($d_budget, 2, '.', ',');
                    }
                }        
                else
                {
                    return '-';
                } 
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
            ->addColumn('mes_budget', function($data)
            {
                $mes_budget = [];
                switch($data->variable_id)
                {
                    case 10070: 
                        $mes_budget = $this->summesbudgetton[0];
                    break;
                    case 10071:
                        if(isset($this->summesbudgetonz[0]->mes_budget) && isset($this->summesbudgetton[0]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[0]->mes_budget;
                            $min = $this->summesbudgetton[0]->mes_budget;
                        }   
                        else
                        {
                            $min = 0;
                        }                             
                    break;
                    case 10072:
                        $mes_budget = $this->summesbudgetonz[0];
                    break;
                    case 10073: 
                        $mes_budget = $this->summesbudgetton[1];
                    break;
                    case 10074:
                        if(isset($this->summesbudgetonz[1]->mes_budget) && isset($this->summesbudgetton[1]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[1]->mes_budget;
                            $min = $this->summesbudgetton[1]->mes_budget;
                        }     
                        else
                        {
                            $min = 0;
                        }                              
                    break;
                    case 10075:
                        $mes_budget = $this->summesbudgetonz[1];
                    break;                            
                    case 10076: 
                        $mes_budget = $this->summesbudgetton[2];
                    break;
                    case 10077:
                        if(isset($this->summesbudgetonz[2]->mes_budget) && isset($this->summesbudgetton[2]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[2]->mes_budget;
                            $min = $this->summesbudgetton[2]->mes_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10078:
                        $mes_budget = $this->summesbudgetonz[2];
                    break;
                    case 10079: 
                        $mes_budget = $this->summesbudgetton[3];
                    break;
                    case 10080:
                        if(isset($this->summesbudgetonz[3]->mes_budget) && isset($this->summesbudgetton[3]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[3]->mes_budget;
                            $min = $this->summesbudgetton[3]->mes_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10081:
                        $mes_budget = $this->summesbudgetonz[3];
                    break;
                    case 10082: 
                        $mes_budget = $this->summesbudgetton[4];
                    break;
                    case 10083:
                        if(isset($this->summesbudgetonz[4]->mes_budget) && isset($this->summesbudgetton[4]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[4]->mes_budget;
                            $min = $this->summesbudgetton[4]->mes_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10084:
                        $mes_budget = $this->summesbudgetonz[4];
                    break;
                    case 10085: 
                        $mes_budget = $this->summesbudgetton[5];
                    break;
                    case 10086:
                        if(isset($this->summesbudgetonz[5]->mes_budget) && isset($this->summesbudgetton[5]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[5]->mes_budget;
                            $min = $this->summesbudgetton[5]->mes_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10087:
                        $mes_budget = $this->summesbudgetonz[5];
                    break;
                    case 10088: 
                        $mes_budget = $this->summesbudgetton[6];
                    break;
                    case 10089:
                        if(isset($this->summesbudgetonz[6]->mes_budget) && isset($this->summesbudgetton[6]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[6]->mes_budget;
                            $min = $this->summesbudgetton[6]->mes_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10090:
                        $mes_budget = $this->summesbudgetonz[6];
                    break;
                    case 10091: 
                        $mes_budget = $this->summesbudgetton[7];
                    break;
                    case 10092: 
                        $mes_budget = $this->summesbudgetton[8];
                    break;
                    case 10093: 
                        $mes_budget = $this->summesbudgetton[9];
                    break;
                    case 10094:
                        if(isset($this->summesbudgetonz[7]->mes_budget) && isset($this->summesbudgetton[9]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[7]->mes_budget;
                            $min = $this->summesbudgetton[9]->mes_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10095:
                        $mes_budget = $this->summesbudgetonz[7];
                    break;
                    case 10097: 
                        $mes_budget = $this->summesbudgetton[10];
                    break;
                    case 10098:
                        if(isset($this->summesbudgetonz[8]->mes_budget) && isset($this->summesbudgetton[10]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[8]->mes_budget;
                            $min = $this->summesbudgetton[10]->mes_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10099:
                        $mes_budget = $this->summesbudgetonz[8];
                    break;
                    case 10100: 
                        $mes_budget = $this->summesbudgetton[11];
                    break;
                    case 10101:
                        if(isset($this->summesbudgetonz[9]->mes_budget) && isset($this->summesbudgetton[11]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[9]->mes_budget;
                            $min = $this->summesbudgetton[11]->mes_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10102:
                        $mes_budget = $this->summesbudgetonz[9];
                    break;
                    case 10103: 
                        $mes_budget = $this->summesbudgetton[12];
                    break;
                    case 10104:
                        if(isset($this->summesbudgetonz[10]->mes_budget) && isset($this->summesbudgetton[12]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[10]->mes_budget;
                            $min = $this->summesbudgetton[12]->mes_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10105:
                        $mes_budget = $this->summesbudgetonz[10];
                    break;
                    case 10106: 
                        $mes_budget = $this->summesbudgetton[13];
                    break;
                    case 10107:
                        if(isset($this->summesbudgetonz[11]->mes_budget) && isset($this->summesbudgetton[13]->mes_budget))
                        {
                            $au = $this->summesbudgetonz[11]->mes_budget;
                            $min = $this->summesbudgetton[13]->mes_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10108:
                        $mes_budget = $this->summesbudgetonz[11];
                    break;
                    case 10109: 
                        $mes_budget = $this->summesbudgetton[14];
                    break;
                    case 10110: 
                        $mes_budget = $this->summesbudgetton[15];
                    break;
                    case 10111: 
                        $mes_budget = $this->summesbudgetton[16];
                    break;
                    case 10112: 
                        $mes_budget = $this->summesbudgetton[17];
                    break;
                    case 10113: 
                        $mes_budget = $this->summesbudgetton[18];
                    break;
                    case 10114:
                        $mes_budget = $this->avgmesbudgetpor[0];
                    break;
                    case 10115:
                        $mes_budget = $this->avgmesbudgetpor[1];
                    break;
                    case 10116:
                        $mes_budget = $this->avgmesbudgetpor[2];
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
                if(isset($mes_budget->mes_budget))
                {
                    $m_budget = $mes_budget->mes_budget;
                    if($m_budget > 100)
                    {
                        return number_format(round($m_budget), 0, '.', ',');
                    }
                    else
                    {
                        return number_format($m_budget, 2, '.', ',');
                    }
                }
                else
                {
                    return '-';
                }
            })
            ->addColumn('trimestre_real', function($data)
            {                      
                $tri_real = [];
                switch($data->variable_id)
                {
                    case 10070: 
                        $tri_real = $this->sumtrirealton[0];
                    break;
                    case 10071:
                        if(isset($this->sumtrirealonz[0]->tri_real) && isset($this->sumtrirealton[0]->tri_real))
                        {
                            $au = $this->sumtrirealonz[0]->tri_real;
                            $min = $this->sumtrirealton[0]->tri_real;
                        }   
                        else
                        {
                            $min = 0;
                        }                             
                    break;
                    case 10072:
                        $tri_real = $this->sumtrirealonz[0];
                    break;
                    case 10073: 
                        $tri_real = $this->sumtrirealton[1];
                    break;
                    case 10074:
                        if(isset($this->sumtrirealonz[1]->tri_real) && isset($this->sumtrirealton[1]->tri_real))
                        {
                            $au = $this->sumtrirealonz[1]->tri_real;
                            $min = $this->sumtrirealton[1]->tri_real;
                        }     
                        else
                        {
                            $min = 0;
                        }                              
                    break;
                    case 10075:
                        $tri_real = $this->sumtrirealonz[1];
                    break;                            
                    case 10076: 
                        $tri_real = $this->sumtrirealton[2];
                    break;
                    case 10077:
                        if(isset($this->sumtrirealonz[2]->tri_real) && isset($this->sumtrirealton[2]->tri_real))
                        {
                            $au = $this->sumtrirealonz[2]->tri_real;
                            $min = $this->sumtrirealton[2]->tri_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10078:
                        $tri_real = $this->sumtrirealonz[2];
                    break;
                    case 10079: 
                        $tri_real = $this->sumtrirealton[3];
                    break;
                    case 10080:
                        if(isset($this->sumtrirealonz[3]->tri_real) && isset($this->sumtrirealton[3]->tri_real))
                        {
                            $au = $this->sumtrirealonz[3]->tri_real;
                            $min = $this->sumtrirealton[3]->tri_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10081:
                        $tri_real = $this->sumtrirealonz[3];
                    break;
                    case 10082: 
                        $tri_real = $this->sumtrirealton[4];
                    break;
                    case 10083:
                        if(isset($this->sumtrirealonz[4]->tri_real) && isset($this->sumtrirealton[4]->tri_real))
                        {
                            $au = $this->sumtrirealonz[4]->tri_real;
                            $min = $this->sumtrirealton[4]->tri_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10084:
                        $tri_real = $this->sumtrirealonz[4];
                    break;
                    case 10085: 
                        $tri_real = $this->sumtrirealton[5];
                    break;
                    case 10086:
                        if(isset($this->sumtrirealonz[5]->tri_real) && isset($this->sumtrirealton[5]->tri_real))
                        {
                            $au = $this->sumtrirealonz[5]->tri_real;
                            $min = $this->sumtrirealton[5]->tri_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10087:
                        $tri_real = $this->sumtrirealonz[5];
                    break;
                    case 10088: 
                        $tri_real = $this->sumtrirealton[6];
                    break;
                    case 10089:
                        if(isset($this->sumtrirealonz[6]->tri_real) && isset($this->sumtrirealton[6]->tri_real))
                        {
                            $au = $this->sumtrirealonz[6]->tri_real;
                            $min = $this->sumtrirealton[6]->tri_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10090:
                        $tri_real = $this->sumtrirealonz[6];
                    break;
                    case 10091: 
                        $tri_real = $this->sumtrirealton[7];
                    break;
                    case 10092: 
                        $tri_real = $this->sumtrirealton[8];
                    break;
                    case 10093: 
                        $tri_real = $this->sumtrirealton[9];
                    break;
                    case 10094:
                        if(isset($this->sumtrirealonz[7]->tri_real) && isset($this->sumtrirealton[9]->tri_real))
                        {
                            $au = $this->sumtrirealonz[7]->tri_real;
                            $min = $this->sumtrirealton[9]->tri_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10095:
                        $tri_real = $this->sumtrirealonz[7];
                    break;
                    case 10097: 
                        $tri_real = $this->sumtrirealton[10];
                    break;
                    case 10098:
                        if(isset($this->sumtrirealonz[8]->tri_real) && isset($this->sumtrirealton[10]->tri_real))
                        {
                            $au = $this->sumtrirealonz[8]->tri_real;
                            $min = $this->sumtrirealton[10]->tri_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10099:
                        $tri_real = $this->sumtrirealonz[8];
                    break;
                    case 10100: 
                        $tri_real = $this->sumtrirealton[11];
                    break;
                    case 10101:
                        if(isset($this->sumtrirealonz[9]->tri_real) && isset($this->sumtrirealton[11]->tri_real))
                        {
                            $au = $this->sumtrirealonz[9]->tri_real;
                            $min = $this->sumtrirealton[11]->tri_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10102:
                        $tri_real = $this->sumtrirealonz[9];
                    break;
                    case 10103: 
                        $tri_real = $this->sumtrirealton[12];
                    break;
                    case 10104:
                        if(isset($this->sumtrirealonz[10]->tri_real) && isset($this->sumtrirealton[12]->tri_real))
                        {
                            $au = $this->sumtrirealonz[10]->tri_real;
                            $min = $this->sumtrirealton[12]->tri_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10105:
                        $tri_real = $this->sumtrirealonz[10];
                    break;
                    case 10106: 
                        $tri_real = $this->sumtrirealton[13];
                    break;
                    case 10107:
                        if(isset($this->sumtrirealonz[11]->tri_real) && isset($this->sumtrirealton[13]->tri_real))
                        {
                            $au = $this->sumtrirealonz[11]->tri_real;
                            $min = $this->sumtrirealton[13]->tri_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10108:
                        $tri_real = $this->sumtrirealonz[11];
                    break;
                    case 10109: 
                        $tri_real = $this->sumtrirealton[14];
                    break;
                    case 10110: 
                        $tri_real = $this->sumtrirealton[15];
                    break;
                    case 10111: 
                        $tri_real = $this->sumtrirealton[16];
                    break;
                    case 10112: 
                        $tri_real = $this->sumtrirealton[17];
                    break;
                    case 10113: 
                        $tri_real = $this->sumtrirealton[18];
                    break;
                    case 10114:
                        $tri_real = $this->avgtrirealpor[0];
                    break;
                    case 10115:
                        $tri_real = $this->avgtrirealpor[1];
                    break;
                    case 10116:
                        $tri_real = $this->avgtrirealpor[2];
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
                if(isset($tri_real->tri_real))
                {
                    $m_real = $tri_real->tri_real;
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
            ->addColumn('trimestre_budget', function($data)
            { 
                $trimestre_budget = [];
                switch($data->variable_id)
                {
                    case 10070: 
                        $trimestre_budget = $this->sumtribudgetton[0];
                    break;
                    case 10071:
                        if(isset($this->sumtribudgetonz[0]->trimestre_budget) && isset($this->sumtribudgetton[0]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[0]->trimestre_budget;
                            $min = $this->sumtribudgetton[0]->trimestre_budget;
                        }   
                        else
                        {
                            $min = 0;
                        }                             
                    break;
                    case 10072:
                        $trimestre_budget = $this->sumtribudgetonz[0];
                    break;
                    case 10073: 
                        $trimestre_budget = $this->sumtribudgetton[1];
                    break;
                    case 10074:
                        if(isset($this->sumtribudgetonz[1]->trimestre_budget) && isset($this->sumtribudgetton[1]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[1]->trimestre_budget;
                            $min = $this->sumtribudgetton[1]->trimestre_budget;
                        }     
                        else
                        {
                            $min = 0;
                        }                              
                    break;
                    case 10075:
                        $trimestre_budget = $this->sumtribudgetonz[1];
                    break;                            
                    case 10076: 
                        $trimestre_budget = $this->sumtribudgetton[2];
                    break;
                    case 10077:
                        if(isset($this->sumtribudgetonz[2]->trimestre_budget) && isset($this->sumtribudgetton[2]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[2]->trimestre_budget;
                            $min = $this->sumtribudgetton[2]->trimestre_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10078:
                        $trimestre_budget = $this->sumtribudgetonz[2];
                    break;
                    case 10079: 
                        $trimestre_budget = $this->sumtribudgetton[3];
                    break;
                    case 10080:
                        if(isset($this->sumtribudgetonz[3]->trimestre_budget) && isset($this->sumtribudgetton[3]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[3]->trimestre_budget;
                            $min = $this->sumtribudgetton[3]->trimestre_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10081:
                        $trimestre_budget = $this->sumtribudgetonz[3];
                    break;
                    case 10082: 
                        $trimestre_budget = $this->sumtribudgetton[4];
                    break;
                    case 10083:
                        if(isset($this->sumtribudgetonz[4]->trimestre_budget) && isset($this->sumtribudgetton[4]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[4]->trimestre_budget;
                            $min = $this->sumtribudgetton[4]->trimestre_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10084:
                        $trimestre_budget = $this->sumtribudgetonz[4];
                    break;
                    case 10085: 
                        $trimestre_budget = $this->sumtribudgetton[5];
                    break;
                    case 10086:
                        if(isset($this->sumtribudgetonz[5]->trimestre_budget) && isset($this->sumtribudgetton[5]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[5]->trimestre_budget;
                            $min = $this->sumtribudgetton[5]->trimestre_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10087:
                        $trimestre_budget = $this->sumtribudgetonz[5];
                    break;
                    case 10088: 
                        $trimestre_budget = $this->sumtribudgetton[6];
                    break;
                    case 10089:
                        if(isset($this->sumtribudgetonz[6]->trimestre_budget) && isset($this->sumtribudgetton[6]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[6]->trimestre_budget;
                            $min = $this->sumtribudgetton[6]->trimestre_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10090:
                        $trimestre_budget = $this->sumtribudgetonz[6];
                    break;
                    case 10091: 
                        $trimestre_budget = $this->sumtribudgetton[7];
                    break;
                    case 10092: 
                        $trimestre_budget = $this->sumtribudgetton[8];
                    break;
                    case 10093: 
                        $trimestre_budget = $this->sumtribudgetton[9];
                    break;
                    case 10094:
                        if(isset($this->sumtribudgetonz[7]->trimestre_budget) && isset($this->sumtribudgetton[9]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[7]->trimestre_budget;
                            $min = $this->sumtribudgetton[9]->trimestre_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10095:
                        $trimestre_budget = $this->sumtribudgetonz[7];
                    break;
                    case 10097: 
                        $trimestre_budget = $this->sumtribudgetton[10];
                    break;
                    case 10098:
                        if(isset($this->sumtribudgetonz[8]->trimestre_budget) && isset($this->sumtribudgetton[10]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[8]->trimestre_budget;
                            $min = $this->sumtribudgetton[10]->trimestre_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10099:
                        $trimestre_budget = $this->sumtribudgetonz[8];
                    break;
                    case 10100: 
                        $trimestre_budget = $this->sumtribudgetton[11];
                    break;
                    case 10101:
                        if(isset($this->sumtribudgetonz[9]->trimestre_budget) && isset($this->sumtribudgetton[11]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[9]->trimestre_budget;
                            $min = $this->sumtribudgetton[11]->trimestre_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10102:
                        $trimestre_budget = $this->sumtribudgetonz[9];
                    break;
                    case 10103: 
                        $trimestre_budget = $this->sumtribudgetton[12];
                    break;
                    case 10104:
                        if(isset($this->sumtribudgetonz[10]->trimestre_budget) && isset($this->sumtribudgetton[12]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[10]->trimestre_budget;
                            $min = $this->sumtribudgetton[12]->trimestre_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10105:
                        $trimestre_budget = $this->sumtribudgetonz[10];
                    break;
                    case 10106: 
                        $trimestre_budget = $this->sumtribudgetton[13];
                    break;
                    case 10107:
                        if(isset($this->sumtribudgetonz[11]->trimestre_budget) && isset($this->sumtribudgetton[13]->trimestre_budget))
                        {
                            $au = $this->sumtribudgetonz[11]->trimestre_budget;
                            $min = $this->sumtribudgetton[13]->trimestre_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10108:
                        $trimestre_budget = $this->sumtribudgetonz[11];
                    break;
                    case 10109: 
                        $trimestre_budget = $this->sumtribudgetton[14];
                    break;
                    case 10110: 
                        $trimestre_budget = $this->sumtribudgetton[15];
                    break;
                    case 10111: 
                        $trimestre_budget = $this->sumtribudgetton[16];
                    break;
                    case 10112: 
                        $trimestre_budget = $this->sumtribudgetton[17];
                    break;
                    case 10113: 
                        $trimestre_budget = $this->sumtribudgetton[18];
                    break;
                    case 10114:
                        $trimestre_budget = $this->avgmesbudgetpor[0];
                    break;
                    case 10115:
                        $trimestre_budget = $this->avgmesbudgetpor[1];
                    break;
                    case 10116:
                        $trimestre_budget = $this->avgmesbudgetpor[2];
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
                if(isset($trimestre_budget->trimestre_budget))
                {
                    if ($trimestre_budget->trimestre_budget > 0)
                    {
                        $m_budget = $trimestre_budget->trimestre_budget;
                        if($m_budget > 100)
                        {
                            return number_format(round($m_budget), 0, '.', ',');
                        }
                        else
                        {
                            return number_format($m_budget, 2, '.', ',');
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
            })
            ->addColumn('anio_real', function($data)
            {                     
                $anio_real = [];
                switch($data->variable_id)
                {
                    case 10070: 
                        $anio_real = $this->sumaniorealton[0];
                    break;
                    case 10071:
                        if(isset($this->sumaniorealonz[0]->anio_real) && isset($this->sumaniorealton[0]->anio_real))
                        {
                            $au = $this->sumaniorealonz[0]->anio_real;
                            $min = $this->sumaniorealton[0]->anio_real;
                        }   
                        else
                        {
                            $min = 0;
                        }                             
                    break;
                    case 10072:
                        $anio_real = $this->sumaniorealonz[0];
                    break;
                    case 10073: 
                        $anio_real = $this->sumaniorealton[1];
                    break;
                    case 10074:
                        if(isset($this->sumaniorealonz[1]->anio_real) && isset($this->sumaniorealton[1]->anio_real))
                        {
                            $au = $this->sumaniorealonz[1]->anio_real;
                            $min = $this->sumaniorealton[1]->anio_real;
                        }     
                        else
                        {
                            $min = 0;
                        }                              
                    break;
                    case 10075:
                        $anio_real = $this->sumaniorealonz[1];
                    break;                            
                    case 10076: 
                        $anio_real = $this->sumaniorealton[2];
                    break;
                    case 10077:
                        if(isset($this->sumaniorealonz[2]->anio_real) && isset($this->sumaniorealton[2]->anio_real))
                        {
                            $au = $this->sumaniorealonz[2]->anio_real;
                            $min = $this->sumaniorealton[2]->anio_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10078:
                        $anio_real = $this->sumaniorealonz[2];
                    break;
                    case 10079: 
                        $anio_real = $this->sumaniorealton[3];
                    break;
                    case 10080:
                        if(isset($this->sumaniorealonz[3]->anio_real) && isset($this->sumaniorealton[3]->anio_real))
                        {
                            $au = $this->sumaniorealonz[3]->anio_real;
                            $min = $this->sumaniorealton[3]->anio_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10081:
                        $anio_real = $this->sumaniorealonz[3];
                    break;
                    case 10082: 
                        $anio_real = $this->sumaniorealton[4];
                    break;
                    case 10083:
                        if(isset($this->sumaniorealonz[4]->anio_real) && isset($this->sumaniorealton[4]->anio_real))
                        {
                            $au = $this->sumaniorealonz[4]->anio_real;
                            $min = $this->sumaniorealton[4]->anio_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10084:
                        $anio_real = $this->sumaniorealonz[4];
                    break;
                    case 10085: 
                        $anio_real = $this->sumaniorealton[5];
                    break;
                    case 10086:
                        if(isset($this->sumaniorealonz[5]->anio_real) && isset($this->sumaniorealton[5]->anio_real))
                        {
                            $au = $this->sumaniorealonz[5]->anio_real;
                            $min = $this->sumaniorealton[5]->anio_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10087:
                        $anio_real = $this->sumaniorealonz[5];
                    break;
                    case 10088: 
                        $anio_real = $this->sumaniorealton[6];
                    break;
                    case 10089:
                        if(isset($this->sumaniorealonz[6]->anio_real) && isset($this->sumaniorealton[6]->anio_real))
                        {
                            $au = $this->sumaniorealonz[6]->anio_real;
                            $min = $this->sumaniorealton[6]->anio_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10090:
                        $anio_real = $this->sumaniorealonz[6];
                    break;
                    case 10091: 
                        $anio_real = $this->sumaniorealton[7];
                    break;
                    case 10092: 
                        $anio_real = $this->sumaniorealton[8];
                    break;
                    case 10093: 
                        $anio_real = $this->sumaniorealton[9];
                    break;
                    case 10094:
                        if(isset($this->sumaniorealonz[7]->anio_real) && isset($this->sumaniorealton[9]->anio_real))
                        {
                            $au = $this->sumaniorealonz[7]->anio_real;
                            $min = $this->sumaniorealton[9]->anio_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10095:
                        $anio_real = $this->sumaniorealonz[7];
                    break;
                    case 10097: 
                        $anio_real = $this->sumaniorealton[10];
                    break;
                    case 10098:
                        if(isset($this->sumaniorealonz[8]->anio_real) && isset($this->sumaniorealton[10]->anio_real))
                        {
                            $au = $this->sumaniorealonz[8]->anio_real;
                            $min = $this->sumaniorealton[10]->anio_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10099:
                        $anio_real = $this->sumaniorealonz[8];
                    break;
                    case 10100: 
                        $anio_real = $this->sumaniorealton[11];
                    break;
                    case 10101:
                        if(isset($this->sumaniorealonz[9]->anio_real) && isset($this->sumaniorealton[11]->anio_real))
                        {
                            $au = $this->sumaniorealonz[9]->anio_real;
                            $min = $this->sumaniorealton[11]->anio_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10102:
                        $anio_real = $this->sumaniorealonz[9];
                    break;
                    case 10103: 
                        $anio_real = $this->sumaniorealton[12];
                    break;
                    case 10104:
                        if(isset($this->sumaniorealonz[10]->anio_real) && isset($this->sumaniorealton[12]->anio_real))
                        {
                            $au = $this->sumaniorealonz[10]->anio_real;
                            $min = $this->sumaniorealton[12]->anio_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10105:
                        $anio_real = $this->sumaniorealonz[10];
                    break;
                    case 10106: 
                        $anio_real = $this->sumaniorealton[13];
                    break;
                    case 10107:
                        if(isset($this->sumaniorealonz[11]->anio_real) && isset($this->sumaniorealton[13]->anio_real))
                        {
                            $au = $this->sumaniorealonz[11]->anio_real;
                            $min = $this->sumaniorealton[13]->anio_real;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10108:
                        $anio_real = $this->sumaniorealonz[11];
                    break;
                    case 10109: 
                        $anio_real = $this->sumaniorealton[14];
                    break;
                    case 10110: 
                        $anio_real = $this->sumaniorealton[15];
                    break;
                    case 10111: 
                        $anio_real = $this->sumaniorealton[16];
                    break;
                    case 10112: 
                        $anio_real = $this->sumaniorealton[17];
                    break;
                    case 10113: 
                        $anio_real = $this->sumaniorealton[18];
                    break;
                    case 10114:
                        $anio_real = $this->avganiorealpor[0];
                    break;
                    case 10115:
                        $anio_real = $this->avganiorealpor[1];
                    break;
                    case 10116:
                        $anio_real = $this->avganiorealpor[2];
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
                if(isset($anio_real->anio_real))
                {
                    $m_real = $anio_real->anio_real;
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
            ->addColumn('anio_budget', function($data)
            {                         
                $anio_budget = [];
                switch($data->variable_id)
                {
                    case 10070: 
                        $anio_budget = $this->sumaniobudgetton[0];
                    break;
                    case 10071:
                        if(isset($this->sumaniobudgetonz[0]->anio_budget) && isset($this->sumaniobudgetton[0]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[0]->anio_budget;
                            $min = $this->sumaniobudgetton[0]->anio_budget;
                        }   
                        else
                        {
                            $min = 0;
                        }                             
                    break;
                    case 10072:
                        $anio_budget = $this->sumaniobudgetonz[0];
                    break;
                    case 10073: 
                        $anio_budget = $this->sumaniobudgetton[1];
                    break;
                    case 10074:
                        if(isset($this->sumaniobudgetonz[1]->anio_budget) && isset($this->sumaniobudgetton[1]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[1]->anio_budget;
                            $min = $this->sumaniobudgetton[1]->anio_budget;
                        }     
                        else
                        {
                            $min = 0;
                        }                              
                    break;
                    case 10075:
                        $anio_budget = $this->sumaniobudgetonz[1];
                    break;                            
                    case 10076: 
                        $anio_budget = $this->sumaniobudgetton[2];
                    break;
                    case 10077:
                        if(isset($this->sumaniobudgetonz[2]->anio_budget) && isset($this->sumaniobudgetton[2]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[2]->anio_budget;
                            $min = $this->sumaniobudgetton[2]->anio_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10078:
                        $anio_budget = $this->sumaniobudgetonz[2];
                    break;
                    case 10079: 
                        $anio_budget = $this->sumaniobudgetton[3];
                    break;
                    case 10080:
                        if(isset($this->sumaniobudgetonz[3]->anio_budget) && isset($this->sumaniobudgetton[3]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[3]->anio_budget;
                            $min = $this->sumaniobudgetton[3]->anio_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10081:
                        $anio_budget = $this->sumaniobudgetonz[3];
                    break;
                    case 10082: 
                        $anio_budget = $this->sumaniobudgetton[4];
                    break;
                    case 10083:
                        if(isset($this->sumaniobudgetonz[4]->anio_budget) && isset($this->sumaniobudgetton[4]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[4]->anio_budget;
                            $min = $this->sumaniobudgetton[4]->anio_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10084:
                        $anio_budget = $this->sumaniobudgetonz[4];
                    break;
                    case 10085: 
                        $anio_budget = $this->sumaniobudgetton[5];
                    break;
                    case 10086:
                        if(isset($this->sumaniobudgetonz[5]->anio_budget) && isset($this->sumaniobudgetton[5]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[5]->anio_budget;
                            $min = $this->sumaniobudgetton[5]->anio_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10087:
                        $anio_budget = $this->sumaniobudgetonz[5];
                    break;
                    case 10088: 
                        $anio_budget = $this->sumaniobudgetton[6];
                    break;
                    case 10089:
                        if(isset($this->sumaniobudgetonz[6]->anio_budget) && isset($this->sumaniobudgetton[6]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[6]->anio_budget;
                            $min = $this->sumaniobudgetton[6]->anio_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10090:
                        $anio_budget = $this->sumaniobudgetonz[6];
                    break;
                    case 10091: 
                        $anio_budget = $this->sumaniobudgetton[7];
                    break;
                    case 10092: 
                        $anio_budget = $this->sumaniobudgetton[8];
                    break;
                    case 10093: 
                        $anio_budget = $this->sumaniobudgetton[9];
                    break;
                    case 10094:
                        if(isset($this->sumaniobudgetonz[7]->anio_budget) && isset($this->sumaniobudgetton[9]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[7]->anio_budget;
                            $min = $this->sumaniobudgetton[9]->anio_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10095:
                        $anio_budget = $this->sumaniobudgetonz[7];
                    break;
                    case 10097: 
                        $anio_budget = $this->sumaniobudgetton[10];
                    break;
                    case 10098:
                        if(isset($this->sumaniobudgetonz[8]->anio_budget) && isset($this->sumaniobudgetton[10]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[8]->anio_budget;
                            $min = $this->sumaniobudgetton[10]->anio_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10099:
                        $anio_budget = $this->sumaniobudgetonz[8];
                    break;
                    case 10100: 
                        $anio_budget = $this->sumaniobudgetton[11];
                    break;
                    case 10101:
                        if(isset($this->sumaniobudgetonz[9]->anio_budget) && isset($this->sumaniobudgetton[11]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[9]->anio_budget;
                            $min = $this->sumaniobudgetton[11]->anio_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10102:
                        $anio_budget = $this->sumaniobudgetonz[9];
                    break;
                    case 10103: 
                        $anio_budget = $this->sumaniobudgetton[12];
                    break;
                    case 10104:
                        if(isset($this->sumaniobudgetonz[10]->anio_budget) && isset($this->sumaniobudgetton[12]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[10]->anio_budget;
                            $min = $this->sumaniobudgetton[12]->anio_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10105:
                        $anio_budget = $this->sumaniobudgetonz[10];
                    break;
                    case 10106: 
                        $anio_budget = $this->sumaniobudgetton[13];
                    break;
                    case 10107:
                        if(isset($this->sumaniobudgetonz[11]->anio_budget) && isset($this->sumaniobudgetton[13]->anio_budget))
                        {
                            $au = $this->sumaniobudgetonz[11]->anio_budget;
                            $min = $this->sumaniobudgetton[13]->anio_budget;
                        }  
                        else
                        {
                            $min = 0;
                        } 
                    break;
                    case 10108:
                        $anio_budget = $this->sumaniobudgetonz[11];
                    break;
                    case 10109: 
                        $anio_budget = $this->sumaniobudgetton[14];
                    break;
                    case 10110: 
                        $anio_budget = $this->sumaniobudgetton[15];
                    break;
                    case 10111: 
                        $anio_budget = $this->sumaniobudgetton[16];
                    break;
                    case 10112: 
                        $anio_budget = $this->sumaniobudgetton[17];
                    break;
                    case 10113: 
                        $anio_budget = $this->sumaniobudgetton[18];
                    break;
                    case 10114:
                        $anio_budget = $this->avganiobudgetpor[0];
                    break;
                    case 10115:
                        $anio_budget = $this->avganiobudgetpor[1];
                    break;
                    case 10116:
                        $anio_budget = $this->avganiobudgetpor[2];
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
                if(isset($anio_budget->anio_budget))
                {
                    if ($anio_budget->anio_budget > 0)
                    {
                        $m_budget = $anio_budget->anio_budget;
                        if($m_budget > 100)
                        {
                            return number_format(round($m_budget), 0, '.', ',');
                        }
                        else
                        {
                            return number_format($m_budget, 2, '.', ',');
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
            })
            ->rawColumns(['categoria','subcategoria','dia_real','dia_budget','mes_real','mes_budget','trimestre_real','anio_real'])
            ->make(true);             
         
        $registrosmina= $tablamina->getData()->data;        
        $registrosprocesos= $tablaprocesos->getData()->data;
        $registros = array_merge($registrosmina, $registrosprocesos);
        if ($registros <> [] && $registros <> NULL)
        {
            $pdf = Pdf::loadView('pdf.combinado', compact('registros')); 
            if ( env('APP_ENV') == 'production')
            {
                $data["email"] = "mmsa.dailyreport_procesos@mansfieldmin.com";
            }
            else
            {
                $data["email"] = "ejensen@mansfieldmin.com";
            }
            Mail::send('mails.dailytablecombinado', $data, function ($message) use ($data, $pdf) {
                $message->to($data['email']);
                $message->subject('DailyReport '.$this->date);
                $message->attachData($pdf->output(), 'DailyReport'.$this->date.'.pdf'); //attached pdf file
            });
        }
        else
        {
            if ( env('APP_ENV') == 'production')
            {
                $data["email"] = ["ejensen@mansfieldmin.com", "dpereira@mansfieldmin.com"];
            }
            else
            {
                $data["email"] = "ejensen@mansfieldmin.com";
            }
            Mail::send('mails.dailytablecombinadofail', $data, function ($message) use ($data) {
                $message->to($data['email']);
                $message->subject('DailyReport '.$this->date);
            });
        }
    }
}
