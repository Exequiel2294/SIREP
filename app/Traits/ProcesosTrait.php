<?php

namespace App\Traits;
use App\Models\Periodos;
use App\Models\Periodos_tri;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait ProcesosTrait {

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function TraitProcesosTable(Request $request) {
        //PARA PDF
        if ($request->get('type') == 1)
        {
            $this->date = $request->get('date');
            
            $requestDay=date('Y-m-d',strtotime($this->date));
            //Mes convertido en INT
            $monthInt=(int)date('m',strtotime($this->date));
            //Anio convertido en INT
            $yearInt=(int)date('Y',strtotime($this->date));
            //Defino el trimestre en el que trae la request
            $quarter = (int)ceil($monthInt/3);
            //que no excede el numero del quarter
            $quarter = min($quarter, 4);
            //TRAER DATOS PARA EL MENSUAL
                $NumPeriodo = Periodos::where('fecha_ini','<=',$requestDay)
                                    ->where('fecha_fin','>=',$requestDay)
                                    ->value('periodo');

                $fechaIni = Periodos::where('anio', $yearInt)
                                    ->where('periodo', $NumPeriodo)
                                    ->value('fecha_ini');

                $fechaFin = Periodos::where('anio', $yearInt)
                                    ->where('periodo', $NumPeriodo)
                                    ->value('fecha_fin');
            //HASTA AQUI

            //TRAE DATOS PARA EL TRIMESTRE
                $fechaIni_Tri = Periodos_tri::where('anio', $yearInt)
                ->where('periodo', $quarter)
                ->value('fecha_ini');
                $fechaFin_Tri = Periodos_tri::where('anio', $yearInt)
                ->where('periodo', $quarter)
                ->value('fecha_fin');
                $finTri = date('Y-m-d',strtotime($fechaFin_Tri));
                $iniTri = date('Y-m-d',strtotime($fechaIni_Tri));
                if( $finTri < $requestDay || $iniTri > $requestDay)
                {
                    $quarter +=1;
                    $fechaIniTri = Periodos_tri::where('anio', $yearInt)
                                        ->where('periodo', $quarter)
                                        ->value('fecha_ini');

                    $fechaFinTri = Periodos_tri::where('anio', $yearInt)
                                            ->where('periodo', $quarter)
                                            ->value('fecha_fin');
                }
                else
                {
                    $fechaIniTri = Periodos_tri::where('anio', $yearInt)
                                        ->where('periodo', $quarter)
                                        ->value('fecha_ini');

                    $fechaFinTri = Periodos_tri::where('anio', $yearInt)
                                            ->where('periodo', $quarter)
                                            ->value('fecha_fin');
                }
            //HASTA AQUI
            $this->fecha_ini = date('Y-m-d',strtotime($fechaIni));
            $this->fecha_fin = date('Y-m-d',strtotime($fechaFin));
            $this->fecha_iniTri = date('Y-m-d',strtotime($fechaIniTri));
            $this->fecha_finTri = date('Y-m-d',strtotime($fechaFinTri));
        }
        else
        {
            $this->date = $request->get('date');
            $this->fecha_ini = $request->get('fecha_ini');
            $this->fecha_fin = $request->get('fecha_fin');
            $this->fecha_iniTri = $request->get('fecha_iniTri');
            $this->fecha_finTri = $request->get('fecha_finTri');
            
        }
        //Estos array son validadores para cuando se este por entregar la data
        $this->ley = [10004, 10010, 10024, 10030, 10035];
        $this->div = [10006, 10013, 10020, 10032, 10041, 10042, 10043, 10044, 10050, 10051, 10054, 10055, 10056, 10057, 10058];

        $this->pparray = [10004, 10010, 10012, 10015, 10018, 10024, 10030, 10033, 10035, 10036, 10040, 10041, 10042, 10043, 10044, 10049, 10050, 10051, 10054, 10055, 10056, 10057, 10058];//se coloca 10015 en pparray por el budget
        $this->sumarray = [10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067];
        $this->promarray = [10003, 10007, 10009, 10014, 10016, 10017, 10021, 10026, 10029, 10034];
        $this->percentage = [10003, 10007, 10009, 10014, 10016, 10017, 10018, 10021, 10026, 10029, 10033, 10034, 10036, 10040, 10049];
        $this->divarray = [10006, 10013, 10020, 10032];
        
        
        //INICIO CALCULOS REUTILIZABLES
            //TOMAMOS LA FECHA DEL REQUEST
                $requestDay = date('Y-m-d',strtotime($this->date));
                //realiza un convcert en int el año
                $year = (int)date('Y', strtotime($this->date));
                //Esto convierte el numero del dia en INT
                $daypart = (int)date('z', strtotime($this->date)) + 1;
                //Esto convierte en INT el mes
                $month = (int)date('m', strtotime($this->date));//veremos
            //TAMBIEN HACER MODIFICACION AQUI PARA QUE
                //MES REAL
                    $this->summesreal =
                    DB::select(
                            'SELECT v.id AS variable_id, f.valor as mes_real FROM
                            (SELECT variable_id, SUM(valor) AS valor
                            FROM [dbo].[MMSA_SIREP_DATA]
                            WHERE variable_id IN (10002,10005,10008,10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                            AND  fecha between ? and ?
                            GROUP BY variable_id) AS f
                            RIGHT JOIN
                            (SELECT id
                            FROM [dbo].[variable] 
                            WHERE id IN (10002,10005,10008,10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                            ON f.variable_id = v.id
                            ORDER BY id ASC',
                            [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                    );
                    $this->avgmesreal =
                    DB::select(
                            'SELECT v.id AS variable_id, f.valor AS mes_real FROM
                            (SELECT variable_id, AVG(valor) AS valor
                            FROM [dbo].[MMSA_SIREP_DATA]
                            WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                            AND valor <> 0
                            AND  fecha between ? and ?
                            GROUP BY variable_id) AS f
                            RIGHT JOIN
                            (SELECT id 
                            FROM [dbo].[variable] 
                            WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                            ON f.variable_id = v.id
                            ORDER BY id ASC',
                            [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                    );
                    $this->leymesreal=
                    DB::select(
                        'SELECT variable_id,SUM(sumaproducto) AS sumaproducto,SUM(suma) AS suma
                            FROM (SELECT 10041 AS variable_id,SUM(A.valor * B.valor) AS sumaproducto,SUM(A.valor) AS suma
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10045
                            AND B.variable_id = 10041
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10042 AS variable_id, SUM(A.valor * B.valor) AS sumaproducto, SUM(A.valor) AS suma
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10045
                            AND B.variable_id = 10042
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10043 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10045
                            AND B.variable_id = 10043
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10044 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10045
                            AND B.variable_id = 10044
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10050 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10052
                            AND B.variable_id = 10050
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10051 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10052
                            AND B.variable_id = 10051
                            AND A.fecha BETWEEN ? AND ?
                            ---Esta parte no va ya que el 10054 CN Solución PLS esta desactivado
                            UNION ALL
                            SELECT 10054 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10061
                            AND B.variable_id = 10054
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10055 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10059
                            AND B.variable_id = 10055
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10056 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10060
                            AND B.variable_id = 10056
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10057 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10061
                            AND B.variable_id = 10057
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            ---Esta parte no va ya que 10058 pH Solución PLS esta desactivado
                            SELECT 10058 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[MMSA_SIREP_DATA] A
                            INNER JOIN [dbo].[MMSA_SIREP_DATA] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10061
                            AND B.variable_id = 10058
                            AND A.fecha BETWEEN ? AND ?
                        )AS combined
                        GROUP BY variable_id',
                        [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),]
                    );
                    //dd($this->summesreal,$this->avgmesreal,$this->leymesreal);
                //FIN
                //MES FORECAST.
                    $this->summesforecast10039 = 
                        DB::select(
                        'SELECT variable_id as var, SUM(valor) as suma
                        FROM [dbo].[forecast]
                        WHERE variable_id = 10039
                        AND  fecha between ? and ?
                        GROUP BY variable_id', 
                        [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                        );

                    $this->summesforecast10031 = 
                        DB::select(
                        'SELECT variable_id as var, SUM(valor) as suma
                        FROM [dbo].[forecast]
                        WHERE variable_id = 10031
                        AND  fecha between ? and ?
                        GROUP BY variable_id', 
                        [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                        );
                    
                    $this->summesforecast = 
                        DB::select(
                            'SELECT v.id AS variable_id, f.valor as mes_forecast FROM
                            (SELECT variable_id, SUM(valor) AS valor
                            FROM [dbo].[forecast]
                            WHERE variable_id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                            AND  fecha between ? and ?
                            GROUP BY variable_id) AS f
                            RIGHT JOIN
                            (SELECT id
                            FROM [dbo].[variable] 
                            WHERE id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                            ON f.variable_id = v.id
                            ORDER BY id ASC',
                            [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                        );
                    $this->avgmesforecast =
                        DB::select(
                            'SELECT v.id AS variable_id, f.valor AS mes_forecast FROM
                            (SELECT variable_id, AVG(valor) AS valor
                            FROM [dbo].[forecast]
                            WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                            AND valor <> 0
                            AND  fecha between ? and ?
                            GROUP BY variable_id) AS f
                            RIGHT JOIN
                            (SELECT id 
                            FROM [dbo].[variable] 
                            WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                            ON f.variable_id = v.id
                            ORDER BY id ASC',
                            [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                        );

                    $this->leymesforecast =
                        DB::select(
                            'SELECT variable_id,SUM(sumaproducto) AS sumaproducto,SUM(suma) AS suma
                                FROM (SELECT 10041 AS variable_id,SUM(A.valor * B.valor) AS sumaproducto,SUM(A.valor) AS suma
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10045
                                AND B.variable_id = 10041
                                AND A.fecha BETWEEN ? AND ?
                                UNION ALL
                                SELECT 10042 AS variable_id,SUM(A.valor * B.valor) AS sumaproducto,SUM(A.valor) AS suma
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10045
                                AND B.variable_id = 10042
                                AND A.fecha BETWEEN ? AND ?
                                UNION ALL
                                SELECT 10043 AS variable_id, 
                                SUM(A.valor * B.valor) AS sumaproducto, 
                                SUM(A.valor) AS suma 
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10045
                                AND B.variable_id = 10043
                                AND A.fecha BETWEEN ? AND ?
                                UNION ALL
                                SELECT 10044 AS variable_id, 
                                SUM(A.valor * B.valor) AS sumaproducto, 
                                SUM(A.valor) AS suma 
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10045
                                AND B.variable_id = 10044
                                AND A.fecha BETWEEN ? AND ?
                                UNION ALL
                                SELECT 10050 AS variable_id, 
                                SUM(A.valor * B.valor) AS sumaproducto, 
                                SUM(A.valor) AS suma 
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10052
                                AND B.variable_id = 10050
                                AND A.fecha BETWEEN ? AND ?
                                UNION ALL
                                SELECT 10051 AS variable_id, 
                                SUM(A.valor * B.valor) AS sumaproducto, 
                                SUM(A.valor) AS suma 
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10052
                                AND B.variable_id = 10051
                                AND A.fecha BETWEEN ? AND ?
                                UNION ALL
                                SELECT 10054 AS variable_id, 
                                SUM(A.valor * B.valor) AS sumaproducto, 
                                SUM(A.valor) AS suma 
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10061
                                AND B.variable_id = 10054
                                AND A.fecha BETWEEN ? AND ?
                                UNION ALL
                                SELECT 10055 AS variable_id, 
                                SUM(A.valor * B.valor) AS sumaproducto, 
                                SUM(A.valor) AS suma 
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10059
                                AND B.variable_id = 10055
                                AND A.fecha BETWEEN ? AND ?
                                UNION ALL
                                SELECT 10056 AS variable_id, 
                                SUM(A.valor * B.valor) AS sumaproducto, 
                                SUM(A.valor) AS suma 
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10060
                                AND B.variable_id = 10056
                                AND A.fecha BETWEEN ? AND ?
                                UNION ALL
                                SELECT 10057 AS variable_id, 
                                SUM(A.valor * B.valor) AS sumaproducto, 
                                SUM(A.valor) AS suma 
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10061
                                AND B.variable_id = 10057
                                AND A.fecha BETWEEN ? AND ?
                                UNION ALL
                                SELECT 10058 AS variable_id, 
                                SUM(A.valor * B.valor) AS sumaproducto, 
                                SUM(A.valor) AS suma 
                                FROM [dbo].[forecast] A
                                INNER JOIN [dbo].[forecast] B
                                ON A.fecha = B.fecha
                                AND A.variable_id = 10061
                                AND B.variable_id = 10058
                                AND A.fecha BETWEEN ? AND ?
                            )AS combined
                            GROUP BY variable_id',
                            [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                            date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                            date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                            date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                            date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                            date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                            date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                            date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                            date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                            date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                            date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),]
                        );
                //FIN
                //MES BUDGET
                    $this->summesbudget = 
                        DB::select(
                            'SELECT v.id AS variable_id, f.valor as mes_budget FROM
                            (SELECT variable_id, SUM(valor) AS valor
                            FROM [dbo].[budget]
                            WHERE variable_id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                            AND  fecha between ? and ?
                            GROUP BY variable_id) AS f
                            RIGHT JOIN
                            (SELECT id
                            FROM [dbo].[variable] 
                            WHERE id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                            ON f.variable_id = v.id
                            ORDER BY id ASC',
                            [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                        );
                    $this->avgmesbudget =
                        DB::select(
                            'SELECT v.id AS variable_id, f.valor AS mes_budget FROM
                            (SELECT variable_id, AVG(valor) AS valor
                            FROM [dbo].[budget]
                            WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                            AND valor <> 0
                            AND  fecha between ? and ?
                            GROUP BY variable_id) AS f
                            RIGHT JOIN
                            (SELECT id 
                            FROM [dbo].[variable] 
                            WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                            ON f.variable_id = v.id
                            ORDER BY id ASC',
                            [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                        );
                    
                    $this->leymesbudget =
                    DB::select(
                        'SELECT variable_id,SUM(sumaproducto) AS sumaproducto,SUM(suma) AS suma
                            FROM (SELECT 10041 AS variable_id,SUM(A.valor * B.valor) AS sumaproducto,SUM(A.valor) AS suma
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10045
                            AND B.variable_id = 10041
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10042 AS variable_id, SUM(A.valor * B.valor) AS sumaproducto, SUM(A.valor) AS suma
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10045
                            AND B.variable_id = 10042
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10043 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10045
                            AND B.variable_id = 10043
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10044 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10045
                            AND B.variable_id = 10044
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10050 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10052
                            AND B.variable_id = 10050
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10051 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10052
                            AND B.variable_id = 10051
                            AND A.fecha BETWEEN ? AND ?
                            ---Esta parte no va ya que el 10054 CN Solución PLS esta desactivado
                            UNION ALL
                            SELECT 10054 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10061
                            AND B.variable_id = 10054
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10055 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10059
                            AND B.variable_id = 10055
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10056 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10060
                            AND B.variable_id = 10056
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            SELECT 10057 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10061
                            AND B.variable_id = 10057
                            AND A.fecha BETWEEN ? AND ?
                            UNION ALL
                            ---Esta parte no va ya que 10058 pH Solución PLS esta desactivado
                            SELECT 10058 AS variable_id, 
                            SUM(A.valor * B.valor) AS sumaproducto, 
                            SUM(A.valor) AS suma 
                            FROM [dbo].[budget] A
                            INNER JOIN [dbo].[budget] B
                            ON A.fecha = B.fecha
                            AND A.variable_id = 10061
                            AND B.variable_id = 10058
                            AND A.fecha BETWEEN ? AND ?
                        )AS combined
                        GROUP BY variable_id',
                        [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),]
                    );
                
                //FIN
                //TRIMESTRE REAL
                    $this->sumtrireal = 
                    DB::select(
                        'SELECT v.id AS variable_id, f.valor as tri_real FROM
                        (SELECT variable_id, SUM(valor) AS valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        WHERE variable_id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 
                        10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                        AND  fecha between ? and ?
                        GROUP BY variable_id) AS f
                        RIGHT JOIN
                        (SELECT id
                        FROM [dbo].[variable] 
                        WHERE id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 
                        10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                        ON f.variable_id = v.id
                        ORDER BY id ASC',
                        [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                    );

                    $this->avgtrireal =
                    DB::select(
                        'SELECT v.id AS variable_id, f.valor AS tri_real FROM
                        (SELECT variable_id, 
                        AVG(valor) AS valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                        AND valor <>0
                        AND  fecha between ? and ?
                        GROUP BY variable_id) AS f
                        RIGHT JOIN
                        (SELECT id 
                        FROM [dbo].[variable] 
                        WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                        ON f.variable_id = v.id
                        ORDER BY id ASC',
                        [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                    );

                    $this->leytrireal =
                    DB::select(
                        'SELECT 10041 as variable_id, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10041) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10042, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10042) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10043, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10043) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10044, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10044) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10050, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10050) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10051, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10051) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10054, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10054) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10055, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10059) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10055) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10056, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10060) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10056) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10057, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10057) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10058, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[MMSA_SIREP_DATA]
                        where variable_id = 10058) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?',
                        [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),]
                    );
                //FIN

                //TRIMESTRE FORECAST 
                    $this->sumtriforecast = 
                    DB::select(
                        'SELECT v.id AS variable_id, f.valor as tri_forecast FROM
                        (SELECT variable_id, SUM(valor) AS valor
                        FROM [dbo].[forecast]
                        WHERE variable_id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 
                        10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                        AND  fecha between ? and ?
                        GROUP BY variable_id) AS f
                        RIGHT JOIN
                        (SELECT id
                        FROM [dbo].[variable] 
                        WHERE id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 
                        10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                        ON f.variable_id = v.id
                        ORDER BY id ASC',
                        [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                    );

                    $this->avgtriforecast =
                    DB::select(
                        'SELECT v.id AS variable_id, f.valor AS tri_forecast FROM
                        (SELECT variable_id, 
                        AVG(valor) AS valor
                        FROM [dbo].[forecast]
                        WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                        AND valor <>0
                        AND  fecha between ? and ?
                        GROUP BY variable_id) AS f
                        RIGHT JOIN
                        (SELECT id 
                        FROM [dbo].[variable] 
                        WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                        ON f.variable_id = v.id
                        ORDER BY id ASC',
                        [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                    );

                    $this->leytriforecast =
                    DB::select(
                        'SELECT 10041 as variable_id, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10041) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10042, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10042) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10043, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10043) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10044, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10044) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10050, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10050) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10051, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10051) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10054, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10054) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10055, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10059) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10055) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10056, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10060) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10056) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10057, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10057) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10058, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10058) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?',
                        [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),]
                    );

                    $this->sumtriforecast10031 = 
                    DB::select(
                        'SELECT variable_id as var, SUM(valor) as suma
                        FROM [dbo].[forecast]
                        WHERE variable_id = 10031
                        AND  fecha between ? and ?
                        GROUP BY variable_id', 
                        [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                    );

                    $this->sumtriforecast10039 = 
                    DB::select(
                        'SELECT variable_id as var, SUM(valor) as suma
                        FROM [dbo].[forecast]
                        WHERE variable_id = 10039
                        AND  fecha between ? and ?
                        GROUP BY variable_id', 
                        [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                    );
                //FIN
                //TRIMESTRE BUDGET                
                    $this->sumtribudget = 
                    DB::select(
                        'SELECT v.id AS variable_id, f.valor as tri_budget FROM
                        (SELECT variable_id, SUM(valor) AS valor
                        FROM [dbo].[budget]
                        WHERE variable_id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 
                        10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                        AND  fecha between ? and ?
                        GROUP BY variable_id) AS f
                        RIGHT JOIN
                        (SELECT id
                        FROM [dbo].[variable] 
                        WHERE id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 
                        10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                        ON f.variable_id = v.id
                        ORDER BY id ASC',
                        [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                    );

                    $this->avgtribudget =
                    DB::select(
                        'SELECT v.id AS variable_id, f.valor AS tri_budget FROM
                        (SELECT variable_id, 
                        AVG(valor) AS valor
                        FROM [dbo].[budget]
                        WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                        AND valor <>0
                        AND  fecha between ? and ?
                        GROUP BY variable_id) AS f
                        RIGHT JOIN
                        (SELECT id 
                        FROM [dbo].[variable] 
                        WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                        ON f.variable_id = v.id
                        ORDER BY id ASC',
                        [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                    );

                    $this->leytribudget =
                    DB::select(
                        'SELECT 10041 as variable_id, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10041) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10042, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10042) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10043, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10043) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10044, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10044) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10050, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10050) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10051, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10051) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10054, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10054) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10055, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10059) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10055) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10056, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10060) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10056) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10057, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10057) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?
                        UNION 
                        SELECT 10058, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10058) as B
                        ON A.fecha = B.fecha
                        AND A.fecha between ? and ?',
                        [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),
                        date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date)),]
                    );

                    // $this->sumtribudget10031 = 
                    // DB::select(
                    //     'SELECT variable_id as var, SUM(valor) as suma
                    //     FROM [dbo].[budget]
                    //     WHERE variable_id = 10031
                    //     AND  fecha between ? and ?
                    //     GROUP BY variable_id', 
                    //     [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                    // );

                    // $this->sumtribudget10039 = 
                    // DB::select(
                    //     'SELECT variable_id as var, SUM(valor) as suma
                    //     FROM [dbo].[budget]
                    //     WHERE variable_id = 10039
                    //     AND  fecha between ? and ?
                    //     GROUP BY variable_id', 
                    //     [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                    // );
                //FIN
                //AÑO REAL
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
                    $this->sumanioreal10031 = 
                    DB::select(
                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                        FROM [dbo].[data]
                        WHERE variable_id = 10031
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
                //FIN
                //AÑO BUDGET
                    $this->sumaniobudget10031 = 
                    DB::select(
                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                        FROM [dbo].[budget]
                        WHERE variable_id = 10031
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
                    
                    $this->sumaniobudget = 
                    DB::select(
                        'SELECT v.id AS variable_id, f.valor as anio_budget FROM
                        (SELECT variable_id, SUM(valor) AS valor
                        FROM [dbo].[budget]
                        WHERE variable_id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 
                        10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                        AND  DATEPART(y, fecha) <= '.$daypart.'
                        AND YEAR(fecha) = '.$year.'
                        GROUP BY variable_id) AS f
                        RIGHT JOIN
                        (SELECT id
                        FROM [dbo].[variable] 
                        WHERE id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 
                        10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                        ON f.variable_id = v.id
                        ORDER BY id ASC'
                    );
                    
                    $this->avganiobudget =
                    DB::select(
                        'SELECT v.id AS variable_id, f.valor AS anio_budget FROM
                        (SELECT variable_id, 
                        AVG(valor) AS valor
                        FROM [dbo].[budget]
                        WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                        AND valor <>0
                        AND  DATEPART(y, fecha) <= '.$daypart.'
                        AND YEAR(fecha) = '.$year.'
                        GROUP BY variable_id) AS f
                        RIGHT JOIN
                        (SELECT id 
                        FROM [dbo].[variable] 
                        WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                        ON f.variable_id = v.id
                        ORDER BY id ASC'
                    );
                    
                    $this->leyaniobudget =
                    DB::select(
                        'SELECT 10041 as variable_id, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10041) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10042, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10042) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10043, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10043) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10044, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10044) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10050, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10050) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10051, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10051) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10054, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10054) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10055, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10059) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10055) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10056, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10060) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10056) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10057, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10057) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10058, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10058) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.''
                    );
                //FIN
                //AÑO FORECAST 
                    $this->sumanioforecast10031 = 
                    DB::select(
                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                        FROM [dbo].[forecast]
                        WHERE variable_id = 10031
                        AND  YEAR(fecha) = ?
                        AND  DATEPART(y, fecha) <= ?
                        GROUP BY YEAR(fecha)',
                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                    );

                    $this->sumanioforecast10039 = 
                    DB::select(
                        'SELECT YEAR(fecha) as year, SUM(valor) as suma
                        FROM [dbo].[forecast]
                        WHERE variable_id = 10039
                        AND  YEAR(fecha) = ?
                        AND  DATEPART(y, fecha) <= ?
                        GROUP BY YEAR(fecha)',
                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                    );

                    $this->sumanioforecast = 
                    DB::select(
                        'SELECT v.id AS variable_id, f.valor as anio_forecast FROM
                        (SELECT variable_id, SUM(valor) AS valor
                        FROM [dbo].[forecast]
                        WHERE variable_id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 
                        10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                        AND  DATEPART(y, fecha) <= '.$daypart.'
                        AND YEAR(fecha) = '.$year.'
                        GROUP BY variable_id) AS f
                        RIGHT JOIN
                        (SELECT id
                        FROM [dbo].[variable] 
                        WHERE id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 
                        10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                        ON f.variable_id = v.id
                        ORDER BY id ASC'
                    );

                    $this->avganioforecast =
                    DB::select(
                        'SELECT v.id AS variable_id, f.valor AS anio_forecast FROM
                        (SELECT variable_id, 
                        AVG(valor) AS valor
                        FROM [dbo].[forecast]
                        WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                        AND valor <>0
                        AND  DATEPART(y, fecha) <= '.$daypart.'
                        AND YEAR(fecha) = '.$year.'
                        GROUP BY variable_id) AS f
                        RIGHT JOIN
                        (SELECT id 
                        FROM [dbo].[variable] 
                        WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                        ON f.variable_id = v.id
                        ORDER BY id ASC'
                    );

                    $this->leyanioforecast =
                    DB::select(
                        'SELECT 10041 as variable_id, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10041) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10042, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10042) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10043, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10043) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10044, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10044) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10050, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10050) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10051, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10051) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10054, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10054) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10055, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10059) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10055) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10056, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10060) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10056) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10057, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10057) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10058, 
                        SUM(A.valor * B.valor) as sumaproducto, 
                        SUM(A.valor) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[forecast]
                        where variable_id = 10058) as B
                        ON A.fecha = B.fecha
                        WHERE DATEPART(y, A.fecha) <= '.$daypart.'
                        AND YEAR(A.fecha) = '.$year.''
                    );
                //FIN
                //ARMADO DE LA DASHBOARD
                    $where = ['variable.estado' => 1, 'categoria.area_id' => 1, 'categoria.estado' => 1, 'subcategoria.estado' => 1, 'data.fecha' => $this->date];
                    //DE ESTE TOMA EL PDF
                    if ($request->get('type') == 1)
                    {
                        $table = DB::table('data')
                            ->join('variable','data.variable_id','=','variable.id')
                            ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                            ->join('categoria','subcategoria.categoria_id','=','categoria.id')
                            ->leftJoin('forecast', function($q) {
                                $q->on('data.variable_id', '=', 'forecast.variable_id')
                                ->on('data.fecha', '=', 'forecast.fecha');
                            })
                            ->leftJoin('budget', function($q) {
                                $q->on('data.variable_id', '=', 'budget.variable_id')
                                ->on('data.fecha', '=', 'budget.fecha');
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
                                'forecast.valor as dia_forecast',
                                'budget.valor as dia_budget',
                                'variable.subcategoria_id as subcategoria_id'
                                )
                            ->orderBy('variable.orden', 'asc')
                            ->get();
                        
                        $tabla = datatables()->of($table)  
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            where variable_id = 10010
                                            AND valor <> 0) as B
                                            ON A.fecha = B.fecha
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            where variable_id = 10035
                                            AND valor <> 0) as B
                                            ON A.fecha = B.fecha
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                        ); 
                                    break;  
                                    case 10048:
                                        if(isset($data->dia_real)) 
                                        { 
                                            $d_real = $data->dia_real;
                                            return number_format($d_real, 2, '.', ',');                                
                                        }        
                                        else
                                        {
                                            return '-';
                                        } 
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                            WHERE  DATEPART(y, A.fecha) = ?
                                            AND YEAR(A.fecha) = ?',
                                            [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                        ); 
                                    break;
                                    default:                        
                                        if(isset($data->dia_real)) 
                                        {                                 
                                            $d_real = $data->dia_real;
                                            if($d_real > 100 || in_array($data->variable_id, $this->percentage))
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
                                    if($d_real > 100  || in_array($data->variable_id, $this->percentage))
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
                            ///MODIFICADO 14/10/2024
                            ->addColumn('dia_budget', function($data)
                            {
                                if(isset($data->dia_budget)) 
                                {                                 
                                    $d_forecast = $data->dia_budget;
                                    if($d_forecast > 100 || in_array($data->variable_id, $this->percentage))
                                    {
                                        return number_format(round($d_forecast), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($d_forecast, 2, '.', ',');
                                    }                                
                                }        
                                else
                                {
                                    return '-';
                                }          
                            }) 
                            ->addColumn('dia_forecast', function($data)
                            {
                                if(isset($data->dia_forecast)) 
                                {                                 
                                    $d_forecast = $data->dia_forecast;
                                    if($d_forecast > 100 || in_array($data->variable_id, $this->percentage))
                                    {
                                        return number_format(round($d_forecast), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($d_forecast, 2, '.', ',');
                                    }                                
                                }        
                                else
                                {
                                    return '-';
                                }          
                            })
                            //MODIFICADO 08/09/2024 
                            ->addColumn('mes_real', function($data)
                            {
                            switch($data->variable_id)
                                {
                                    case 10002:
                                        $mes_real = $this->summesreal[0];
                                    break;
                                    case 10003:
                                        $mes_real = $this->avgmesreal[0];
                                    break;
                                    case 10004:
                                        if(isset($this->summesreal[0]->mes_real) && isset($this->summesreal[1]->mes_real))
                                        {
                                            $au = $this->summesreal[0]->mes_real;
                                            $min = $this->summesreal[1]->mes_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10005:
                                        $mes_real = $this->summesreal[1];
                                    break;
                                    case 10006:
                                        if(isset($this->summesreal[1]->mes_real) && (isset($this->summesreal[23]->mes_real) && $this->summesreal[23]->mes_real != 0))
                                        {
                                            $min = $this->summesreal[1]->mes_real;
                                            $hs = $this->summesreal[23]->mes_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10007:
                                        $mes_real = $this->avgmesreal[1];
                                    break;
                                    case 10008:
                                        $mes_real = $this->summesreal[2];
                                    break;
                                    case 10009:
                                        $mes_real = $this->avgmesreal[2];
                                    break;
                                    case 10010:
                                        if(isset($this->summesreal[3]->mes_real) && isset($this->summesreal[2]->mes_real))
                                        {
                                            $au = $this->summesreal[2]->mes_real;
                                            $min = $this->summesreal[3]->mes_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10011:
                                        $mes_real = $this->summesreal[3];
                                    break;
                                    case 10012:
                                        $mes_real = $this->avgmesreal[3];
                                    break;
                                    case 10013:
                                        if(isset($this->summesreal[3]->mes_real) && (isset($this->summesreal[24]->mes_real) && $this->summesreal[24]->mes_real != 0))
                                        {
                                            $min = $this->summesreal[3]->mes_real;
                                            $hs = $this->summesreal[24]->mes_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10014:
                                        $mes_real = $this->avgmesreal[4];
                                    break;
                                    case 10015:
                                        $mes_real = $this->avgmesreal[5];
                                    break;
                                    case 10016:
                                        $mes_real = 0;
                                    break;
                                    case 10017:
                                        $mes_real = $this->avgmesreal[6];
                                    break;
                                    case 10018:
                                        $mes_real = $this->avgmesreal[7];
                                    break;
                                    case 10019:
                                        $mes_real = $this->summesreal[4];
                                    break;
                                    case 10020:
                                        if(isset($this->summesreal[4]->mes_real) && (isset($this->summesreal[25]->mes_real) && $this->summesreal[25]->mes_real != 0))
                                        {
                                            $min = $this->summesreal[4]->mes_real;
                                            $hs = $this->summesreal[25]->mes_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10021:
                                        $mes_real = $this->avgmesreal[8];
                                    break;
                                    case 10022:
                                        $mes_real = $this->summesreal[5];
                                    break;
                                    case 10023:
                                        $mes_real = $this->summesreal[6];
                                    break;
                                    case 10024:
                                        if(isset($this->summesreal[7]->mes_real) && isset($this->summesreal[6]->mes_real))
                                        {
                                            $au = $this->summesreal[6]->mes_real;
                                            $min = $this->summesreal[7]->mes_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10025:
                                        $mes_real = $this->summesreal[7];
                                    break;
                                    case 10026:
                                        $mes_real = $this->avgmesreal[9];
                                    break;
                                    case 10027:
                                        $mes_real = $this->summesreal[8];
                                    break;
                                    case 10028:
                                        $mes_real = $this->summesreal[9];
                                    break;
                                    case 10029:
                                        $mes_real = $this->avgmesreal[10];
                                    break;
                                    case 10030:
                                        if(isset($this->summesreal[10]->mes_real) && isset($this->summesreal[8]->mes_real))
                                        {
                                            $au = $this->summesreal[8]->mes_real;
                                            $min = $this->summesreal[10]->mes_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10031:
                                        $mes_real = $this->summesreal[10];
                                    break;
                                    case 10032:
                                        if(isset($this->summesreal[10]->mes_real) && (isset($this->summesreal[26]->mes_real) && $this->summesreal[26]->mes_real != 0))
                                        {
                                            $min = $this->summesreal[10]->mes_real;
                                            $hs = $this->summesreal[26]->mes_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10033:
                                        $mes_real = $this->avgmesreal[11];
                                    break;
                                    case 10034:
                                        $mes_real = $this->avgmesreal[12];
                                    break;
                                    case 10035:
                                        if(isset($this->summesreal[13]->mes_real) && isset($this->summesreal[11]->mes_real))
                                        {
                                            $au = $this->summesreal[11]->mes_real;
                                            $min = $this->summesreal[13]->mes_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10036:
                                        $mes_real = $this->avgmesreal[13];
                                    break;
                                    case 10037:
                                        $mes_real = $this->summesreal[11];
                                    break;
                                    case 10038:
                                        $mes_real = $this->summesreal[12];
                                    break;
                                    case 10039:
                                        $mes_real = $this->summesreal[13];
                                    break;
                                    case 10040:
                                        $mes_real = $this->avgmesreal[14];
                                    break;
                                    case 10041:
                                        if(isset($this->leymesreal[0]->sumaproducto) && (isset($this->leymesreal[0]->suma) && $this->leymesreal[0]->suma != 0))
                                        {
                                            $min = $this->leymesreal[0]->sumaproducto;
                                            $hs = $this->leymesreal[0]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10042:
                                        if(isset($this->leymesreal[1]->sumaproducto) && (isset($this->leymesreal[1]->suma) && $this->leymesreal[1]->suma != 0))
                                        {
                                            $min = $this->leymesreal[1]->sumaproducto;
                                            $hs = $this->leymesreal[1]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10043:
                                        if(isset($this->leymesreal[2]->sumaproducto) && (isset($this->leymesreal[2]->suma) && $this->leymesreal[2]->suma != 0))
                                        {
                                            $min = $this->leymesreal[2]->sumaproducto;
                                            $hs = $this->leymesreal[2]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10044:
                                        if(isset($this->leymesreal[3]->sumaproducto) && (isset($this->leymesreal[3]->suma) && $this->leymesreal[3]->suma != 0))
                                        {
                                            $min = $this->leymesreal[3]->sumaproducto;
                                            $hs = $this->leymesreal[3]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10045:
                                        $mes_real = $this->summesreal[14];
                                    break;
                                    case 10046:
                                        $mes_real = $this->summesreal[15];
                                    break;
                                    case 10047:
                                        $mes_real = $this->summesreal[16];
                                    break;
                                    case 10048:
                                        $mes_real = $this->summesreal[17];                            
                                        if(isset($mes_real->mes_real))
                                        {
                                            $m_budget = $mes_real->mes_real;
                                            return number_format($m_budget, 2, '.', ',');                                
                                        }
                                        else
                                        {
                                            return '-';
                                        }
                                    break;
                                    case 10049:
                                        $mes_real = $this->avgmesreal[15];
                                    break;
                                    case 10050:
                                        if(isset($this->leymesreal[4]->sumaproducto) && (isset($this->leymesreal[4]->suma) && $this->leymesreal[4]->suma != 0))
                                        {
                                            $min = $this->leymesreal[4]->sumaproducto;
                                            $hs = $this->leymesreal[4]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10051:
                                        if(isset($this->leymesreal[5]->sumaproducto) && (isset($this->leymesreal[5]->suma) && $this->leymesreal[5]->suma != 0))
                                        {
                                            $min = $this->leymesreal[5]->sumaproducto;
                                            $hs = $this->leymesreal[5]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10052:
                                        $mes_real = $this->summesreal[18];
                                    break;
                                    case 10053:
                                        $mes_real = $this->summesreal[19];
                                    break;
                                    case 10054:
                                        if(isset($this->leymesreal[6]->sumaproducto) && (isset($this->leymesreal[6]->suma) && $this->leymesreal[6]->suma != 0))
                                        {
                                            $min = $this->leymesreal[6]->sumaproducto;
                                            $hs = $this->leymesreal[6]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10055:
                                        if(isset($this->leymesreal[7]->sumaproducto) && (isset($this->leymesreal[7]->suma) && $this->leymesreal[7]->suma != 0))
                                        {
                                            $min = $this->leymesreal[7]->sumaproducto;
                                            $hs = $this->leymesreal[7]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10056:
                                        if(isset($this->leymesreal[8]->sumaproducto) && (isset($this->leymesreal[8]->suma) && $this->leymesreal[8]->suma != 0))
                                        {
                                            $min = $this->leymesreal[8]->sumaproducto;
                                            $hs = $this->leymesreal[8]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10057:
                                        if(isset($this->leymesreal[9]->sumaproducto) && (isset($this->leymesreal[9]->suma) && $this->leymesreal[9]->suma != 0))
                                        {
                                            $min = $this->leymesreal[9]->sumaproducto;
                                            $hs = $this->leymesreal[9]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10058:
                                        if(isset($this->leymesreal[10]->sumaproducto) && (isset($this->leymesreal[10]->suma) && $this->leymesreal[10]->suma != 0))
                                        {
                                            $min = $this->leymesreal[10]->sumaproducto;
                                            $hs = $this->leymesreal[10]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10059:
                                        $mes_real = $this->summesreal[20];
                                    break;
                                    case 10060:
                                        $mes_real = $this->summesreal[21];
                                    break;
                                    case 10061:
                                        $mes_real = $this->summesreal[22];
                                    break;
                                    case 10062:
                                        $mes_real = $this->summesreal[23];
                                    break;
                                    case 10063:
                                        $mes_real = $this->summesreal[24];
                                    break;
                                    case 10064:
                                        $mes_real = $this->summesreal[25];
                                    break;
                                    case 10065:
                                        $mes_real = $this->summesreal[26];
                                    break;
                                    case 10066:
                                        $mes_real = 0;
                                    break;
                                    case 10067:
                                        $mes_real = $this->summesreal[27];
                                    break;
                                    case 10068:
                                        $mes_real = $this->summesreal[28];
                                    break;
                                    case 10069:
                                        $mes_real = $this->summesreal[29];
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
                                if (in_array($data->variable_id, $this->div))
                                {
                                    if ($min > 0)
                                    {
                                        $m_real = $min/$hs;
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
                                if(isset($mes_real->mes_real))
                                {
                                    $m_real = $mes_real->mes_real;
                                    if($m_real > 100 || in_array($data->variable_id, $this->percentage))
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
                            //VARIFICADO NO UTILIZA NINGUNA SUBCONSULTA
                            ->addColumn('mes_budget', function($data)
                            {
                                switch($data->variable_id)
                                {
                                    case 10002:
                                        $mes_budget = $this->summesbudget[0];
                                    break;
                                    case 10003:
                                        $mes_budget = $this->avgmesbudget[0];
                                    break;
                                    case 10004:
                                        if(isset($this->summesbudget[0]->mes_budget) && isset($this->summesbudget[1]->mes_budget))
                                        {
                                            $au = $this->summesbudget[0]->mes_budget;
                                            $min = $this->summesbudget[1]->mes_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10005:
                                        $mes_budget = $this->summesbudget[1];
                                    break;
                                    case 10006:
                                        if(isset($this->summesbudget[1]->mes_budget) && (isset($this->summesbudget[23]->mes_budget) && $this->summesbudget[23]->mes_budget != 0))
                                        {
                                            $min = $this->summesbudget[1]->mes_budget;
                                            $hs = $this->summesbudget[23]->mes_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10007:
                                        $mes_budget = $this->avgmesbudget[1];
                                    break;
                                    case 10008:
                                        $mes_budget = $this->summesbudget[2];
                                    break;
                                    case 10009:
                                        $mes_budget = $this->avgmesbudget[2];
                                    break;
                                    case 10010:
                                        if(isset($this->summesbudget[3]->mes_budget) && isset($this->summesbudget[2]->mes_budget))
                                        {
                                            $au = $this->summesbudget[2]->mes_budget;
                                            $min = $this->summesbudget[3]->mes_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10011:
                                        $mes_budget = $this->summesbudget[3];
                                    break;
                                    case 10012:
                                        $mes_budget = $this->avgmesbudget[3];
                                    break;
                                    case 10013:
                                        if(isset($this->summesbudget[3]->mes_budget) && (isset($this->summesbudget[24]->mes_budget) && $this->summesbudget[24]->mes_budget != 0))
                                        {
                                            $min = $this->summesbudget[3]->mes_budget;
                                            $hs = $this->summesbudget[24]->mes_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10014:
                                        $mes_budget = $this->avgmesbudget[4];
                                    break;
                                    case 10015:
                                        $mes_budget = $this->avgmesbudget[5];
                                    break;
                                    case 10016:
                                        $mes_budget = 0;
                                    break;
                                    case 10017:
                                        $mes_budget = $this->avgmesbudget[6];
                                    break;
                                    case 10018:
                                        $mes_budget = $this->avgmesbudget[7];
                                    break;
                                    case 10019:
                                        $mes_budget = $this->summesbudget[4];
                                    break;
                                    case 10020:
                                        if(isset($this->summesbudget[4]->mes_budget) && (isset($this->summesbudget[25]->mes_budget) && $this->summesbudget[25]->mes_budget != 0))
                                        {
                                            $min = $this->summesbudget[4]->mes_budget;
                                            $hs = $this->summesbudget[25]->mes_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10021:
                                        $mes_budget = $this->avgmesbudget[8];
                                    break;
                                    case 10022:
                                        $mes_budget = $this->summesbudget[5];
                                    break;
                                    case 10023:
                                        $mes_budget = $this->summesbudget[6];
                                    break;
                                    case 10024:
                                        if(isset($this->summesbudget[7]->mes_budget) && isset($this->summesbudget[6]->mes_budget))
                                        {
                                            $au = $this->summesbudget[6]->mes_budget;
                                            $min = $this->summesbudget[7]->mes_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10025:
                                        $mes_budget = $this->summesbudget[7];
                                    break;
                                    case 10026:
                                        $mes_budget = $this->avgmesbudget[9];
                                    break;
                                    case 10027:
                                        $mes_budget = $this->summesbudget[8];
                                    break;
                                    case 10028:
                                        $mes_budget = $this->summesbudget[9];
                                    break;
                                    case 10029:
                                        $mes_budget = $this->avgmesbudget[10];
                                    break;
                                    case 10030:
                                        if(isset($this->summesbudget[10]->mes_budget) && isset($this->summesbudget[8]->mes_budget))
                                        {
                                            $au = $this->summesbudget[8]->mes_budget;
                                            $min = $this->summesbudget[10]->mes_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10031:
                                        $mes_budget = $this->summesbudget[10];
                                    break;
                                    case 10032:
                                        if(isset($this->summesbudget[10]->mes_budget) && (isset($this->summesbudget[26]->mes_budget) && $this->summesbudget[26]->mes_budget != 0))
                                        {
                                            $min = $this->summesbudget[10]->mes_budget;
                                            $hs = $this->summesbudget[26]->mes_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10033:
                                        $mes_budget = $this->avgmesbudget[11];
                                    break;
                                    case 10034:
                                        $mes_budget = $this->avgmesbudget[12];
                                    break;
                                    case 10035:
                                        if(isset($this->summesbudget[13]->mes_budget) && isset($this->summesbudget[11]->mes_budget))
                                        {
                                            $au = $this->summesbudget[11]->mes_budget;
                                            $min = $this->summesbudget[13]->mes_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10036:
                                        $mes_budget = $this->avgmesbudget[13];
                                    break;
                                    case 10037:
                                        $mes_budget = $this->summesbudget[11];
                                    break;
                                    case 10038:
                                        $mes_budget = $this->summesbudget[12];
                                    break;
                                    case 10039:
                                        $mes_budget = $this->summesbudget[13];
                                    break;
                                    case 10040:
                                        $mes_budget = $this->avgmesbudget[14];
                                    break;
                                    case 10041:
                                        if(isset($this->leymesbudget[0]->sumaproducto) && (isset($this->leymesbudget[0]->suma) && $this->leymesbudget[0]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[0]->sumaproducto;
                                            $hs = $this->leymesbudget[0]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10042:
                                        if(isset($this->leymesbudget[1]->sumaproducto) && (isset($this->leymesbudget[1]->suma) && $this->leymesbudget[1]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[1]->sumaproducto;
                                            $hs = $this->leymesbudget[1]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10043:
                                        if(isset($this->leymesbudget[2]->sumaproducto) && (isset($this->leymesbudget[2]->suma) && $this->leymesbudget[2]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[2]->sumaproducto;
                                            $hs = $this->leymesbudget[2]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10044:
                                        if(isset($this->leymesbudget[3]->sumaproducto) && (isset($this->leymesbudget[3]->suma) && $this->leymesbudget[3]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[3]->sumaproducto;
                                            $hs = $this->leymesbudget[3]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10045:
                                        $mes_budget = $this->summesbudget[14];
                                    break;
                                    case 10046:
                                        $mes_budget = $this->summesbudget[15];
                                    break;
                                    case 10047:
                                        $mes_budget = $this->summesbudget[16];
                                    break;
                                    case 10048:
                                        $mes_budget = $this->summesbudget[17];                            
                                        if(isset($mes_budget->mes_budget))
                                        {
                                            $m_budget = $mes_budget->mes_budget;
                                            return number_format($m_budget, 2, '.', ',');                                
                                        }
                                        else
                                        {
                                            return '-';
                                        }
                                    break;
                                    case 10049:
                                        $mes_budget = $this->avgmesbudget[15];
                                    break;
                                    case 10050:
                                        if(isset($this->leymesbudget[4]->sumaproducto) && (isset($this->leymesbudget[4]->suma) && $this->leymesbudget[4]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[4]->sumaproducto;
                                            $hs = $this->leymesbudget[4]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10051:
                                        if(isset($this->leymesbudget[5]->sumaproducto) && (isset($this->leymesbudget[5]->suma) && $this->leymesbudget[5]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[5]->sumaproducto;
                                            $hs = $this->leymesbudget[5]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10052:
                                        $mes_budget = $this->summesbudget[18];
                                    break;
                                    case 10053:
                                        $mes_budget = $this->summesbudget[19];
                                    break;
                                    case 10054:
                                        if(isset($this->leymesbudget[6]->sumaproducto) && (isset($this->leymesbudget[6]->suma) && $this->leymesbudget[6]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[6]->sumaproducto;
                                            $hs = $this->leymesbudget[6]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10055:
                                        if(isset($this->leymesbudget[7]->sumaproducto) && (isset($this->leymesbudget[7]->suma) && $this->leymesbudget[7]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[7]->sumaproducto;
                                            $hs = $this->leymesbudget[7]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10056:
                                        if(isset($this->leymesbudget[8]->sumaproducto) && (isset($this->leymesbudget[8]->suma) && $this->leymesbudget[8]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[8]->sumaproducto;
                                            $hs = $this->leymesbudget[8]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10057:
                                        if(isset($this->leymesbudget[9]->sumaproducto) && (isset($this->leymesbudget[9]->suma) && $this->leymesbudget[9]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[9]->sumaproducto;
                                            $hs = $this->leymesbudget[9]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10058:
                                        if(isset($this->leymesbudget[10]->sumaproducto) && (isset($this->leymesbudget[10]->suma) && $this->leymesbudget[10]->suma != 0))
                                        {
                                            $min = $this->leymesbudget[10]->sumaproducto;
                                            $hs = $this->leymesbudget[10]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10059:
                                        $mes_budget = $this->summesbudget[20];
                                    break;
                                    case 10060:
                                        $mes_budget = $this->summesbudget[21];
                                    break;
                                    case 10061:
                                        $mes_budget = $this->summesbudget[22];
                                    break;
                                    case 10062:
                                        $mes_budget = $this->summesbudget[23];
                                    break;
                                    case 10063:
                                        $mes_budget = $this->summesbudget[24];
                                    break;
                                    case 10064:
                                        $mes_budget = $this->summesbudget[25];
                                    break;
                                    case 10065:
                                        $mes_budget = $this->summesbudget[26];
                                    break;
                                    case 10066:
                                        $mes_budget = 0;
                                    break;
                                    case 10067:
                                        $mes_budget = $this->summesbudget[27];
                                    break;
                                    case 10068:
                                        $mes_budget = $this->summesbudget[28];
                                    break;
                                    case 10069:
                                        $mes_budget = $this->summesbudget[29];
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
                                if (in_array($data->variable_id, $this->div))
                                {
                                    if ($min > 0)
                                    {
                                        $m_budget = $min/$hs;
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
                                if(isset($mes_budget->mes_budget))
                                {
                                    $m_budget = $mes_budget->mes_budget;
                                    if($m_budget > 100 || in_array($data->variable_id, $this->percentage))
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
                            //MODIFICADO 05/09/2024
                            ->addColumn('mes_forecast', function($data)
                            {     
                                switch($data->variable_id)
                                {
                                    case 10002:
                                        $mes_forecast = $this->summesforecast[0];
                                    break;
                                    case 10003:
                                        $mes_forecast = $this->avgmesforecast[0];
                                    break;
                                    case 10004:
                                        if(isset($this->summesforecast[0]->mes_forecast) && isset($this->summesforecast[1]->mes_forecast))
                                        {
                                            $au = $this->summesforecast[0]->mes_forecast;
                                            $min = $this->summesforecast[1]->mes_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10005:
                                        $mes_forecast = $this->summesforecast[1];
                                    break;
                                    case 10006:
                                        if(isset($this->summesforecast[1]->mes_forecast) && (isset($this->summesforecast[23]->mes_forecast) && $this->summesforecast[23]->mes_forecast != 0))
                                        {
                                            $min = $this->summesforecast[1]->mes_forecast;
                                            $hs = $this->summesforecast[23]->mes_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10007:
                                        $mes_forecast = $this->avgmesforecast[1];
                                    break;
                                    case 10008:
                                        $mes_forecast = $this->summesforecast[2];
                                    break;
                                    case 10009:
                                        $mes_forecast = $this->avgmesforecast[2];
                                    break;
                                    case 10010:
                                        if(isset($this->summesforecast[3]->mes_forecast) && isset($this->summesforecast[2]->mes_forecast))
                                        {
                                            $au = $this->summesforecast[2]->mes_forecast;
                                            $min = $this->summesforecast[3]->mes_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10011:
                                        $mes_forecast = $this->summesforecast[3];
                                    break;
                                    case 10012:
                                        $mes_forecast = $this->avgmesforecast[3];
                                    break;
                                    case 10013:
                                        if(isset($this->summesforecast[3]->mes_forecast) && (isset($this->summesforecast[24]->mes_forecast) && $this->summesforecast[24]->mes_forecast != 0))
                                        {
                                            $min = $this->summesforecast[3]->mes_forecast;
                                            $hs = $this->summesforecast[24]->mes_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10014:
                                        $mes_forecast = $this->avgmesforecast[4];
                                    break;
                                    case 10015:
                                        $mes_forecast = $this->avgmesforecast[5];
                                    break;
                                    case 10016:
                                        $mes_forecast = 0;
                                    break;
                                    case 10017:
                                        $mes_forecast = $this->avgmesforecast[6];
                                    break;
                                    case 10018:
                                        $mes_forecast = $this->avgmesforecast[7];
                                    break;
                                    case 10019:
                                        $mes_forecast = $this->summesforecast[4];
                                    break;
                                    case 10020:
                                        if(isset($this->summesforecast[4]->mes_forecast) && (isset($this->summesforecast[25]->mes_forecast) && $this->summesforecast[25]->mes_forecast != 0))
                                        {
                                            $min = $this->summesforecast[4]->mes_forecast;
                                            $hs = $this->summesforecast[25]->mes_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10021:
                                        $mes_forecast = $this->avgmesforecast[8];
                                    break;
                                    case 10022:
                                        $mes_forecast = $this->summesforecast[5];
                                    break;
                                    case 10023:
                                        $mes_forecast = $this->summesforecast[6];
                                    break;
                                    case 10024:
                                        if(isset($this->summesforecast[7]->mes_forecast) && isset($this->summesforecast[6]->mes_forecast))
                                        {
                                            $au = $this->summesforecast[6]->mes_forecast;
                                            $min = $this->summesforecast[7]->mes_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10025:
                                        $mes_forecast = $this->summesforecast[7];
                                    break;
                                    case 10026:
                                        $mes_forecast = $this->avgmesforecast[9];
                                    break;
                                    case 10027:
                                        $mes_forecast = $this->summesforecast[8];
                                    break;
                                    case 10028:
                                        //$mes_forecast = $this->summesforecast[9];
                                        //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                                //SUMAMENSUAL((((10033 MMSA_APILAM_STACKER_Recuperación %)* 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                               
                                                
                                                                                    
                                                //10030 MMSA_APILAM_STACKER_Ley Au (g/t) 
                                                //Promedio Ponderado Mensual(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t, 10030 MMSA_APILAM_STACKER_Ley Au (g/t))                          
                                                $sumaproducto10030 = DB::select(
                                                    'SELECT A.variable_id as var,SUM(A.valor * B.valor) as sumaproducto FROM
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10030) as A
                                                    INNER JOIN   
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10031) as B
                                                    ON A.fecha = B.fecha
                                                    WHERE A.fecha between ? and ?
                                                    GROUP BY A.variable_id',  
                                                    [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                                                );        

                                                //10033 MMSA_APILAM_STACKER_Recuperación %
                                                //Promedio Ponderado Mensual(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t, 10033 MMSA_APILAM_STACKER_Recuperación %)                    
                                                $sumaproducto10033 = DB::select(
                                                    'SELECT A.variable_id as var,SUM(A.valor * B.valor) as sumaproducto FROM
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10033) as A
                                                    INNER JOIN   
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10031) as B
                                                    ON A.fecha = B.fecha
                                                    WHERE A.fecha between ? and ?
                                                    GROUP BY A.variable_id',  
                                                    [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]);                                     
                                                $suma10031 = $this->summesforecast10031; 
                                        

                                                if(isset($sumaproducto10030[0]->sumaproducto) && isset($sumaproducto10033[0]->sumaproducto) && isset($suma10031[0]->suma))
                                                {
                                                    if ($suma10031[0]->suma > 0) {
                                                        //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                        $recup =  $sumaproducto10033[0]->sumaproducto/$suma10031[0]->suma;
                                                        $leyAu = $sumaproducto10030[0]->sumaproducto/$suma10031[0]->suma;
                                                        $sumMin = $suma10031[0]->suma;
                                                        $mes_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                        if($mes_forecast > 100)
                                                        {
                                                            return number_format(round($mes_forecast), 0, '.', ',');
                                                        }
                                                        else
                                                        {
                                                            return number_format($mes_forecast, 2, '.', ',');
                                                        }
                                                    }
                                                    else {
                                                        return '-';
                                                    }
                                                }
                                                else
                                                {
                                                    return '-';
                                                } 
                                    break;
                                    case 10029:
                                        $mes_forecast = $this->avgmesforecast[10];
                                    break;
                                    case 10030:
                                        if(isset($this->summesforecast[10]->mes_forecast) && isset($this->summesforecast[8]->mes_forecast))
                                        {
                                            $au = $this->summesforecast[8]->mes_forecast;
                                            $min = $this->summesforecast[10]->mes_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10031:
                                        $mes_forecast = $this->summesforecast[10];
                                    break;
                                    case 10032:
                                        if(isset($this->summesforecast[10]->mes_forecast) && (isset($this->summesforecast[26]->mes_forecast) && $this->summesforecast[26]->mes_forecast != 0))
                                        {
                                            $min = $this->summesforecast[10]->mes_forecast;
                                            $hs = $this->summesforecast[26]->mes_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10033:
                                        $mes_forecast = $this->avgmesforecast[11];
                                    break;
                                    case 10034:
                                        $mes_forecast = $this->avgmesforecast[12];
                                    break;
                                    case 10035:
                                        if(isset($this->summesforecast[13]->mes_forecast) && isset($this->summesforecast[11]->mes_forecast))
                                        {
                                            $au = $this->summesforecast[11]->mes_forecast;
                                            $min = $this->summesforecast[13]->mes_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10036:
                                        $mes_forecast = $this->avgmesforecast[13];
                                    break;
                                    case 10037:
                                        $mes_forecast = $this->summesforecast[11];
                                    break;
                                    case 10038:
                                        //$mes_forecast = $this->summesforecast[12];
                                        //$mes_forecast = $this->summesforecast[12];
                                        //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                                //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)                                     
                                                
                                                                                    
                                                //10035 MMSA_APILAM_TA_Ley Au g/t
                                                //Promedio Ponderado Mensual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                                $sumaproducto10035 = DB::select(
                                                    'SELECT A.variable_id as var,SUM(A.valor * B.valor) as sumaproducto FROM
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10035) as A
                                                    INNER JOIN   
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10039) as B
                                                    ON A.fecha = B.fecha
                                                    WHERE A.fecha between ? and ?
                                                    GROUP BY A.variable_id',  
                                                    [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                                                );  
                                                
                                                //10036 MMSA_APILAM_TA_Recuperación %
                                                //Promedio Ponderado Mensual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                                $sumaproducto10036 = DB::select(
                                                'SELECT A.variable_id as var,SUM(A.valor * B.valor) as sumaproducto FROM
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10036) as A
                                                    INNER JOIN   
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10039) as B
                                                    ON A.fecha = B.fecha
                                                    WHERE A.fecha between ? and ?
                                                    GROUP BY A.variable_id',  
                                                    [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                                                );     

                                                $suma10039= $this->summesforecast10039; 
                                                
                                                if(isset($sumaproducto10035[0]->sumaproducto) && isset($sumaproducto10036[0]->sumaproducto) && isset($suma10039[0]->suma))
                                                {
                                                    if ($suma10039[0]->suma > 0) {
                                                        //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                        $recup =  $sumaproducto10036[0]->sumaproducto/$suma10039[0]->suma;
                                                        $leyAu = $sumaproducto10035[0]->sumaproducto/$suma10039[0]->suma;
                                                        $sumMin = $suma10039[0]->suma;
                                                        $mes_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                        if($mes_forecast > 100)
                                                        {
                                                            return number_format(round($mes_forecast), 0, '.', ',');
                                                        }
                                                        else
                                                        {
                                                            return number_format($mes_forecast, 2, '.', ',');
                                                        }
                                                    }
                                                    else {
                                                        return '-';
                                                    }
                                                }
                                                else
                                                {
                                                    return '-';
                                                }
                                    break;
                                    case 10039:
                                        $mes_forecast = $this->summesforecast[13];
                                    break;
                                    case 10040:
                                        $mes_forecast = $this->avgmesforecast[14];
                                    break;
                                    case 10041:
                                        if(isset($this->leymesforecast[0]->sumaproducto) && (isset($this->leymesforecast[0]->suma) && $this->leymesforecast[0]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[0]->sumaproducto;
                                            $hs = $this->leymesforecast[0]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10042:
                                        if(isset($this->leymesforecast[1]->sumaproducto) && (isset($this->leymesforecast[1]->suma) && $this->leymesforecast[1]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[1]->sumaproducto;
                                            $hs = $this->leymesforecast[1]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10043:
                                        if(isset($this->leymesforecast[2]->sumaproducto) && (isset($this->leymesforecast[2]->suma) && $this->leymesforecast[2]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[2]->sumaproducto;
                                            $hs = $this->leymesforecast[2]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10044:
                                        if(isset($this->leymesforecast[3]->sumaproducto) && (isset($this->leymesforecast[3]->suma) && $this->leymesforecast[3]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[3]->sumaproducto;
                                            $hs = $this->leymesforecast[3]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10045:
                                        $mes_forecast = $this->summesforecast[14];
                                    break;
                                    case 10046:
                                        $mes_forecast = $this->summesforecast[15];
                                    break;
                                    case 10047:
                                        $mes_forecast = $this->summesforecast[16];
                                    break;
                                    case 10048:
                                        $mes_forecast = $this->summesforecast[17];                            
                                        if(isset($mes_forecast->mes_forecast))
                                        {
                                            $m_forecast = $mes_forecast->mes_forecast;
                                            return number_format($m_forecast, 2, '.', ',');                                
                                        }
                                        else
                                        {
                                            return '-';
                                        }
                                    break;
                                    case 10049:
                                        $mes_forecast = $this->avgmesforecast[15];
                                    break;
                                    case 10050:
                                        if(isset($this->leymesforecast[4]->sumaproducto) && (isset($this->leymesforecast[4]->suma) && $this->leymesforecast[4]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[4]->sumaproducto;
                                            $hs = $this->leymesforecast[4]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10051:
                                        if(isset($this->leymesforecast[5]->sumaproducto) && (isset($this->leymesforecast[5]->suma) && $this->leymesforecast[5]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[5]->sumaproducto;
                                            $hs = $this->leymesforecast[5]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10052:
                                        $mes_forecast = $this->summesforecast[18];
                                    break;
                                    case 10053:
                                        $mes_forecast = $this->summesforecast[19];
                                    break;
                                    case 10054:
                                        if(isset($this->leymesforecast[6]->sumaproducto) && (isset($this->leymesforecast[6]->suma) && $this->leymesforecast[6]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[6]->sumaproducto;
                                            $hs = $this->leymesforecast[6]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10055:
                                        if(isset($this->leymesforecast[7]->sumaproducto) && (isset($this->leymesforecast[7]->suma) && $this->leymesforecast[7]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[7]->sumaproducto;
                                            $hs = $this->leymesforecast[7]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10056:
                                        if(isset($this->leymesforecast[8]->sumaproducto) && (isset($this->leymesforecast[8]->suma) && $this->leymesforecast[8]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[8]->sumaproducto;
                                            $hs = $this->leymesforecast[8]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10057:
                                        if(isset($this->leymesforecast[9]->sumaproducto) && (isset($this->leymesforecast[9]->suma) && $this->leymesforecast[9]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[9]->sumaproducto;
                                            $hs = $this->leymesforecast[9]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10058:
                                        if(isset($this->leymesforecast[10]->sumaproducto) && (isset($this->leymesforecast[10]->suma) && $this->leymesforecast[10]->suma != 0))
                                        {
                                            $min = $this->leymesforecast[10]->sumaproducto;
                                            $hs = $this->leymesforecast[10]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10059:
                                        $mes_forecast = $this->summesforecast[20];
                                    break;
                                    case 10060:
                                        $mes_forecast = $this->summesforecast[21];
                                    break;
                                    case 10061:
                                        $mes_forecast = $this->summesforecast[22];
                                    break;
                                    case 10062:
                                        $mes_forecast = $this->summesforecast[23];
                                    break;
                                    case 10063:
                                        $mes_forecast = $this->summesforecast[24];
                                    break;
                                    case 10064:
                                        $mes_forecast = $this->summesforecast[25];
                                    break;
                                    case 10065:
                                        $mes_forecast = $this->summesforecast[26];
                                    break;
                                    case 10066:
                                        $mes_forecast = 0;
                                    break;
                                    case 10067:
                                        $mes_forecast = $this->summesforecast[27];
                                    break;
                                    case 10068:
                                        $mes_forecast = $this->summesforecast[28];
                                    break;
                                    case 10069:
                                        $mes_forecast = $this->summesforecast[29];
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
                                if (in_array($data->variable_id, $this->div))
                                {
                                    if ($min > 0)
                                    {
                                        $m_forecast = $min/$hs;
                                        if($m_forecast > 100)
                                        {
                                            return number_format(round($m_forecast), 0, '.', ',');
                                        }
                                        else
                                        {
                                            return number_format($m_forecast, 2, '.', ',');
                                        }
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                }

                                if(isset($mes_forecast->mes_forecast))
                                {
                                    $m_forecast = $mes_forecast->mes_forecast;
                                    if($m_forecast > 100 || in_array($data->variable_id, $this->percentage))
                                    {
                                        return number_format(round($m_forecast), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($m_forecast, 2, '.', ',');
                                    }
                                }
                                else
                                {
                                    return '-';
                                }
                            })//MODIFICADO 06/09/2024
                            ->addColumn('trimestre_real', function($data)
                            {
                                switch($data->variable_id)
                                {
                                    case 10002:
                                        $tri_real = $this->sumtrireal[0];
                                    break;
                                    case 10003:
                                        $tri_real = $this->avgtrireal[0];
                                    break;
                                    case 10004:
                                        if(isset($this->sumtrireal[0]->tri_real) && isset($this->sumtrireal[1]->tri_real))
                                        {
                                            $au = $this->sumtrireal[0]->tri_real;
                                            $min = $this->sumtrireal[1]->tri_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10005:
                                        $tri_real = $this->sumtrireal[1];
                                    break;
                                    case 10006:
                                        if(isset($this->sumtrireal[1]->tri_real) && (isset($this->sumtrireal[23]->tri_real) && $this->sumtrireal[23]->tri_real != 0))
                                        {
                                            $min = $this->sumtrireal[1]->tri_real;
                                            $hs = $this->sumtrireal[23]->tri_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10007:
                                        $tri_real = $this->avgtrireal[1];
                                    break;
                                    case 10008:
                                        $tri_real = $this->sumtrireal[2];
                                    break;
                                    case 10009:
                                        $tri_real = $this->avgtrireal[2];
                                    break;
                                    case 10010:
                                        if(isset($this->sumtrireal[3]->tri_real) && isset($this->sumtrireal[2]->tri_real))
                                        {
                                            $au = $this->sumtrireal[2]->tri_real;
                                            $min = $this->sumtrireal[3]->tri_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10011:
                                        $tri_real = $this->sumtrireal[3];
                                    break;
                                    case 10012:
                                        $tri_real = $this->avgtrireal[3];
                                    break;
                                    case 10013:
                                        if(isset($this->sumtrireal[3]->tri_real) && (isset($this->sumtrireal[24]->tri_real) && $this->sumtrireal[24]->tri_real != 0))
                                        {
                                            $min = $this->sumtrireal[3]->tri_real;
                                            $hs = $this->sumtrireal[24]->tri_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10014:
                                        $tri_real = $this->avgtrireal[4];
                                    break;
                                    case 10015:
                                        $tri_real = $this->avgtrireal[5];
                                    break;
                                    case 10016:
                                        $tri_real = 0;
                                    break;
                                    case 10017:
                                        $tri_real = $this->avgtrireal[6];
                                    break;
                                    case 10018:
                                        $tri_real = $this->avgtrireal[7];
                                    break;
                                    case 10019:
                                        $tri_real = $this->sumtrireal[4];
                                    break;
                                    case 10020:
                                        if(isset($this->sumtrireal[4]->tri_real) && (isset($this->sumtrireal[25]->tri_real) && $this->sumtrireal[25]->tri_real != 0))
                                        {
                                            $min = $this->sumtrireal[4]->tri_real;
                                            $hs = $this->sumtrireal[25]->tri_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10021:
                                        $tri_real = $this->avgtrireal[8];
                                    break;
                                    case 10022:
                                        $tri_real = $this->sumtrireal[5];
                                    break;
                                    case 10023:
                                        $tri_real = $this->sumtrireal[6];
                                    break;
                                    case 10024:
                                        if(isset($this->sumtrireal[7]->tri_real) && isset($this->sumtrireal[6]->tri_real))
                                        {
                                            $au = $this->sumtrireal[6]->tri_real;
                                            $min = $this->sumtrireal[7]->tri_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10025:
                                        $tri_real = $this->sumtrireal[7];
                                    break;
                                    case 10026:
                                        $tri_real = $this->avgtrireal[9];
                                    break;
                                    case 10027:
                                        $tri_real = $this->sumtrireal[8];
                                    break;
                                    case 10028:
                                        $tri_real = $this->sumtrireal[9];
                                    break;
                                    case 10029:
                                        $tri_real = $this->avgtrireal[10];
                                    break;
                                    case 10030:
                                        if(isset($this->sumtrireal[10]->tri_real) && isset($this->sumtrireal[8]->tri_real))
                                        {
                                            $au = $this->sumtrireal[8]->tri_real;
                                            $min = $this->sumtrireal[10]->tri_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10031:
                                        $tri_real = $this->sumtrireal[10];
                                    break;
                                    case 10032:
                                        if(isset($this->sumtrireal[10]->tri_real) && (isset($this->sumtrireal[26]->tri_real) && $this->sumtrireal[26]->tri_real != 0))
                                        {
                                            $min = $this->sumtrireal[10]->tri_real;
                                            $hs = $this->sumtrireal[26]->tri_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10033:
                                        $tri_real = $this->avgtrireal[11];
                                    break;
                                    case 10034:
                                        $tri_real = $this->avgtrireal[12];
                                    break;
                                    case 10035:
                                        if(isset($this->sumtrireal[13]->tri_real) && isset($this->sumtrireal[11]->tri_real))
                                        {
                                            $au = $this->sumtrireal[11]->tri_real;
                                            $min = $this->sumtrireal[13]->tri_real;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10036:
                                        $tri_real = $this->avgtrireal[13];
                                    break;
                                    case 10037:
                                        $tri_real = $this->sumtrireal[11];
                                    break;
                                    case 10038:
                                        $tri_real = $this->sumtrireal[12];
                                    break;
                                    case 10039:
                                        $tri_real = $this->sumtrireal[13];
                                    break;
                                    case 10040:
                                        $tri_real = $this->avgtrireal[14];
                                    break;
                                    case 10041:
                                        if(isset($this->leytrireal[0]->sumaproducto) && (isset($this->leytrireal[0]->suma) && $this->leytrireal[0]->suma != 0))
                                        {
                                            $min = $this->leytrireal[0]->sumaproducto;
                                            $hs = $this->leytrireal[0]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10042:
                                        if(isset($this->leytrireal[1]->sumaproducto) && (isset($this->leytrireal[1]->suma) && $this->leytrireal[1]->suma != 0))
                                        {
                                            $min = $this->leytrireal[1]->sumaproducto;
                                            $hs = $this->leytrireal[1]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10043:
                                        if(isset($this->leytrireal[2]->sumaproducto) && (isset($this->leytrireal[2]->suma) && $this->leytrireal[2]->suma != 0))
                                        {
                                            $min = $this->leytrireal[2]->sumaproducto;
                                            $hs = $this->leytrireal[2]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10044:
                                        if(isset($this->leytrireal[3]->sumaproducto) && (isset($this->leytrireal[3]->suma) && $this->leytrireal[3]->suma != 0))
                                        {
                                            $min = $this->leytrireal[3]->sumaproducto;
                                            $hs = $this->leytrireal[3]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10045:
                                        $tri_real = $this->sumtrireal[14];
                                    break;
                                    case 10046:
                                        $tri_real = $this->sumtrireal[15];
                                    break;
                                    case 10047:
                                        $tri_real = $this->sumtrireal[16];
                                    break;
                                    case 10048:
                                        $tri_real = $this->sumtrireal[17];                            
                                        if(isset($tri_real->tri_real))
                                        {
                                            $t_real = $tri_real->tri_real;
                                            return number_format($t_real, 2, '.', ',');                                
                                        }
                                        else
                                        {
                                            return '-';
                                        }
                                    break;
                                    case 10049:
                                        $tri_real = $this->avgtrireal[15];
                                    break;
                                    case 10050:
                                        if(isset($this->leytrireal[4]->sumaproducto) && (isset($this->leytrireal[4]->suma) && $this->leytrireal[4]->suma != 0))
                                        {
                                            $min = $this->leytrireal[4]->sumaproducto;
                                            $hs = $this->leytrireal[4]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10051:
                                        if(isset($this->leytrireal[5]->sumaproducto) && (isset($this->leytrireal[5]->suma) && $this->leytrireal[5]->suma != 0))
                                        {
                                            $min = $this->leytrireal[5]->sumaproducto;
                                            $hs = $this->leytrireal[5]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10052:
                                        $tri_real = $this->sumtrireal[18];
                                    break;
                                    case 10053:
                                        $tri_real = $this->sumtrireal[19];
                                    break;
                                    case 10054:
                                        if(isset($this->leytrireal[6]->sumaproducto) && (isset($this->leytrireal[6]->suma) && $this->leytrireal[6]->suma != 0))
                                        {
                                            $min = $this->leytrireal[6]->sumaproducto;
                                            $hs = $this->leytrireal[6]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10055:
                                        if(isset($this->leytrireal[7]->sumaproducto) && (isset($this->leytrireal[7]->suma) && $this->leytrireal[7]->suma != 0))
                                        {
                                            $min = $this->leytrireal[7]->sumaproducto;
                                            $hs = $this->leytrireal[7]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10056:
                                        if(isset($this->leytrireal[8]->sumaproducto) && (isset($this->leytrireal[8]->suma) && $this->leytrireal[8]->suma != 0))
                                        {
                                            $min = $this->leytrireal[8]->sumaproducto;
                                            $hs = $this->leytrireal[8]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10057:
                                        if(isset($this->leytrireal[9]->sumaproducto) && (isset($this->leytrireal[9]->suma) && $this->leytrireal[9]->suma != 0))
                                        {
                                            $min = $this->leytrireal[9]->sumaproducto;
                                            $hs = $this->leytrireal[9]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    //No esta aparece como desactivado
                                    case 10058:
                                        if(isset($this->leytrireal[10]->sumaproducto) && (isset($this->leytrireal[10]->suma) && $this->leytrireal[10]->suma != 0))
                                        {
                                            $min = $this->leytrireal[10]->sumaproducto;
                                            $hs = $this->leytrireal[10]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10059:
                                        $tri_real = $this->sumtrireal[20];
                                    break;
                                    case 10060:
                                        $tri_real = $this->sumtrireal[21];
                                    break;
                                    case 10061:
                                        $tri_real = $this->sumtrireal[22];
                                    break;
                                    case 10062:
                                        $tri_real = $this->sumtrireal[23];
                                    break;
                                    case 10063:
                                        $tri_real = $this->sumtrireal[24];
                                    break;
                                    case 10064:
                                        $tri_real = $this->sumtrireal[25];
                                    break;
                                    case 10065:
                                        $tri_real = $this->sumtrireal[26];
                                    break;
                                    case 10066:
                                        $tri_real = 0;
                                    break;
                                    case 10067:
                                        $tri_real = $this->sumtrireal[27];
                                    break;
                                    case 10068:
                                        $tri_real = $this->sumtrireal[28];
                                    break;
                                    case 10069:
                                        $tri_real = $this->sumtrireal[29];
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
                                if (in_array($data->variable_id, $this->div))
                                {
                                    if ($min > 0)
                                    {
                                        $t_real = $min/$hs;
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
                                if(isset($tri_real->tri_real))
                                {
                                    $t_real = $tri_real->tri_real;
                                    if($t_real > 100 || in_array($data->variable_id, $this->percentage))
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
                            })
                            //NO UTILIZA NINGUN TIPO DE SUBCONSULTA
                            ->addColumn('trimestre_budget', function($data)
                            {
                                switch($data->variable_id)
                                {
                                    case 10002:
                                        $tri_budget = $this->sumtribudget[0];
                                    break;
                                    case 10003:
                                        $tri_budget = $this->avgtribudget[0];
                                    break;
                                    case 10004:
                                        if(isset($this->sumtribudget[0]->tri_budget) && isset($this->sumtribudget[1]->tri_budget))
                                        {
                                            $au = $this->sumtribudget[0]->tri_budget;
                                            $min = $this->sumtribudget[1]->tri_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10005:
                                        $tri_budget = $this->sumtribudget[1];
                                    break;
                                    case 10006:
                                        if(isset($this->sumtribudget[1]->tri_budget) && (isset($this->sumtribudget[23]->tri_budget) && $this->sumtribudget[23]->tri_budget != 0))
                                        {
                                            $min = $this->sumtribudget[1]->tri_budget;
                                            $hs = $this->sumtribudget[23]->tri_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10007:
                                        $tri_budget = $this->avgtribudget[1];
                                    break;
                                    case 10008:
                                        $tri_budget = $this->sumtribudget[2];
                                    break;
                                    case 10009:
                                        $tri_budget = $this->avgtribudget[2];
                                    break;
                                    case 10010:
                                        if(isset($this->sumtribudget[3]->tri_budget) && isset($this->sumtribudget[2]->tri_budget))
                                        {
                                            $au = $this->sumtribudget[2]->tri_budget;
                                            $min = $this->sumtribudget[3]->tri_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10011:
                                        $tri_budget = $this->sumtribudget[3];
                                    break;
                                    case 10012:
                                        $tri_budget = $this->avgtribudget[3];
                                    break;
                                    case 10013:
                                        if(isset($this->sumtribudget[3]->tri_budget) && (isset($this->sumtribudget[24]->tri_budget) && $this->sumtribudget[24]->tri_budget != 0))
                                        {
                                            $min = $this->sumtribudget[3]->tri_budget;
                                            $hs = $this->sumtribudget[24]->tri_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10014:
                                        $tri_budget = $this->avgtribudget[4];
                                    break;
                                    case 10015:
                                        $tri_budget = $this->avgtribudget[5];
                                    break;
                                    case 10016:
                                        $tri_budget = 0;
                                    break;
                                    case 10017:
                                        $tri_budget = $this->avgtribudget[6];
                                    break;
                                    case 10018:
                                        $tri_budget = $this->avgtribudget[7];
                                    break;
                                    case 10019:
                                        $tri_budget = $this->sumtribudget[4];
                                    break;
                                    case 10020:
                                        if(isset($this->sumtribudget[4]->tri_budget) && (isset($this->sumtribudget[25]->tri_budget) && $this->sumtribudget[25]->tri_budget != 0))
                                        {
                                            $min = $this->sumtribudget[4]->tri_budget;
                                            $hs = $this->sumtribudget[25]->tri_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10021:
                                        $tri_budget = $this->avgtribudget[8];
                                    break;
                                    case 10022:
                                        $tri_budget = $this->sumtribudget[5];
                                    break;
                                    case 10023:
                                        $tri_budget = $this->sumtribudget[6];
                                    break;
                                    case 10024:
                                        if(isset($this->sumtribudget[7]->tri_budget) && isset($this->sumtribudget[6]->tri_budget))
                                        {
                                            $au = $this->sumtribudget[6]->tri_budget;
                                            $min = $this->sumtribudget[7]->tri_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10025:
                                        $tri_budget = $this->sumtribudget[7];
                                    break;
                                    case 10026:
                                        $tri_budget = $this->avgtribudget[9];
                                    break;
                                    case 10027:
                                        $tri_budget = $this->sumtribudget[8];
                                    break;
                                    case 10028:
                                        $tri_budget = $this->sumtribudget[9];
                                    break;
                                    case 10029:
                                        $tri_budget = $this->avgtribudget[10];
                                    break;
                                    case 10030:
                                        if(isset($this->sumtribudget[10]->tri_budget) && isset($this->sumtribudget[8]->tri_budget))
                                        {
                                            $au = $this->sumtribudget[8]->tri_budget;
                                            $min = $this->sumtribudget[10]->tri_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10031:
                                        $tri_budget = $this->sumtribudget[10];
                                    break;
                                    case 10032:
                                        if(isset($this->sumtribudget[10]->tri_budget) && (isset($this->sumtribudget[26]->tri_budget) && $this->sumtribudget[26]->tri_budget != 0))
                                        {
                                            $min = $this->sumtribudget[10]->tri_budget;
                                            $hs = $this->sumtribudget[26]->tri_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10033:
                                        $tri_budget = $this->avgtribudget[11];
                                    break;
                                    case 10034:
                                        $tri_budget = $this->avgtribudget[12];
                                    break;
                                    case 10035:
                                        if(isset($this->sumtribudget[13]->tri_budget) && isset($this->sumtribudget[11]->tri_budget))
                                        {
                                            $au = $this->sumtribudget[11]->tri_budget;
                                            $min = $this->sumtribudget[13]->tri_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10036:
                                        $tri_budget = $this->avgtribudget[13];
                                    break;
                                    case 10037:
                                        $tri_budget = $this->sumtribudget[11];
                                    break;
                                    case 10038:
                                        $tri_budget = $this->sumtribudget[12];
                                    break;
                                    case 10039:
                                        $tri_budget = $this->sumtribudget[13];
                                    break;
                                    case 10040:
                                        $tri_budget = $this->avgtribudget[14];
                                    break;
                                    case 10041:
                                        if(isset($this->leytribudget[0]->sumaproducto) && (isset($this->leytribudget[0]->suma) && $this->leytribudget[0]->suma != 0))
                                        {
                                            $min = $this->leytribudget[0]->sumaproducto;
                                            $hs = $this->leytribudget[0]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10042:
                                        if(isset($this->leytribudget[1]->sumaproducto) && (isset($this->leytribudget[1]->suma) && $this->leytribudget[1]->suma != 0))
                                        {
                                            $min = $this->leytribudget[1]->sumaproducto;
                                            $hs = $this->leytribudget[1]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10043:
                                        if(isset($this->leytribudget[2]->sumaproducto) && (isset($this->leytribudget[2]->suma) && $this->leytribudget[2]->suma != 0))
                                        {
                                            $min = $this->leytribudget[2]->sumaproducto;
                                            $hs = $this->leytribudget[2]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10044:
                                        if(isset($this->leytribudget[3]->sumaproducto) && (isset($this->leytribudget[3]->suma) && $this->leytribudget[3]->suma != 0))
                                        {
                                            $min = $this->leytribudget[3]->sumaproducto;
                                            $hs = $this->leytribudget[3]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10045:
                                        $tri_budget = $this->sumtribudget[14];
                                    break;
                                    case 10046:
                                        $tri_budget = $this->sumtribudget[15];
                                    break;
                                    case 10047:
                                        $tri_budget = $this->sumtribudget[16];
                                    break;
                                    case 10048:
                                        $tri_budget = $this->sumtribudget[17];                            
                                        if(isset($tri_budget->tri_budget))
                                        {
                                            $t_budget = $tri_budget->tri_budget;
                                            return number_format($t_budget, 2, '.', ',');                                
                                        }
                                        else
                                        {
                                            return '-';
                                        }
                                    break;
                                    case 10049:
                                        $tri_budget = $this->avgtribudget[15];
                                    break;
                                    case 10050:
                                        if(isset($this->leytribudget[4]->sumaproducto) && (isset($this->leytribudget[4]->suma) && $this->leytribudget[4]->suma != 0))
                                        {
                                            $min = $this->leytribudget[4]->sumaproducto;
                                            $hs = $this->leytribudget[4]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10051:
                                        if(isset($this->leytribudget[5]->sumaproducto) && (isset($this->leytribudget[5]->suma) && $this->leytribudget[5]->suma != 0))
                                        {
                                            $min = $this->leytribudget[5]->sumaproducto;
                                            $hs = $this->leytribudget[5]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10052:
                                        $tri_budget = $this->sumtribudget[18];
                                    break;
                                    case 10053:
                                        $tri_budget = $this->sumtribudget[19];
                                    break;
                                    case 10054:
                                        if(isset($this->leytribudget[6]->sumaproducto) && (isset($this->leytribudget[6]->suma) && $this->leytribudget[6]->suma != 0))
                                        {
                                            $min = $this->leytribudget[6]->sumaproducto;
                                            $hs = $this->leytribudget[6]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10055:
                                        if(isset($this->leytribudget[7]->sumaproducto) && (isset($this->leytribudget[7]->suma) && $this->leytribudget[7]->suma != 0))
                                        {
                                            $min = $this->leytribudget[7]->sumaproducto;
                                            $hs = $this->leytribudget[7]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10056:
                                        if(isset($this->leytribudget[8]->sumaproducto) && (isset($this->leytribudget[8]->suma) && $this->leytribudget[8]->suma != 0))
                                        {
                                            $min = $this->leytribudget[8]->sumaproducto;
                                            $hs = $this->leytribudget[8]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10057:
                                        if(isset($this->leytribudget[9]->sumaproducto) && (isset($this->leytribudget[9]->suma) && $this->leytribudget[9]->suma != 0))
                                        {
                                            $min = $this->leytribudget[9]->sumaproducto;
                                            $hs = $this->leytribudget[9]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10058:
                                        if(isset($this->leytribudget[10]->sumaproducto) && (isset($this->leytribudget[10]->suma) && $this->leytribudget[10]->suma != 0))
                                        {
                                            $min = $this->leytribudget[10]->sumaproducto;
                                            $hs = $this->leytribudget[10]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10059:
                                        $tri_budget = $this->sumtribudget[20];
                                    break;
                                    case 10060:
                                        $tri_budget = $this->sumtribudget[21];
                                    break;
                                    case 10061:
                                        $tri_budget = $this->sumtribudget[22];
                                    break;
                                    case 10062:
                                        $tri_budget = $this->sumtribudget[23];
                                    break;
                                    case 10063:
                                        $tri_budget = $this->sumtribudget[24];
                                    break;
                                    case 10064:
                                        $tri_budget = $this->sumtribudget[25];
                                    break;
                                    case 10065:
                                        $tri_budget = $this->sumtribudget[26];
                                    break;
                                    case 10066:
                                        $tri_budget = 0;
                                    break;
                                    case 10067:
                                        $tri_budget = $this->sumtribudget[27];
                                    break;
                                    case 10068:
                                        $tri_budget = $this->sumtribudget[28];
                                    break;
                                    case 10069:
                                        $tri_budget = $this->sumtribudget[29];
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
                                if (in_array($data->variable_id, $this->div))
                                {
                                    if ($min > 0)
                                    {
                                        $t_budget = $min/$hs;
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
                                if(isset($tri_budget->tri_budget))
                                {
                                    $t_budget = $tri_budget->tri_budget;
                                    if($t_budget > 100 || in_array($data->variable_id, $this->percentage))
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
                            })
                            //TERMINADO 06/09/2024
                            ->addColumn('trimestre_forecast', function($data)
                            {                            
                                switch($data->variable_id)
                                {
                                    case 10002:
                                        $tri_forecast = $this->sumtriforecast[0];
                                    break;
                                    case 10003:
                                        $tri_forecast = $this->avgtriforecast[0];
                                    break;
                                    case 10004:
                                        if(isset($this->sumtriforecast[0]->tri_forecast) && isset($this->sumtriforecast[1]->tri_forecast))
                                        {
                                            $au = $this->sumtriforecast[0]->tri_forecast;
                                            $min = $this->sumtriforecast[1]->tri_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10005:
                                        $tri_forecast = $this->sumtriforecast[1];
                                    break;
                                    case 10006:
                                        if(isset($this->sumtriforecast[1]->tri_forecast) && (isset($this->sumtriforecast[23]->tri_forecast) && $this->sumtriforecast[23]->tri_forecast != 0))
                                        {
                                            $min = $this->sumtriforecast[1]->tri_forecast;
                                            $hs = $this->sumtriforecast[23]->tri_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10007:
                                        $tri_forecast = $this->avgtriforecast[1];
                                    break;
                                    case 10008:
                                        $tri_forecast = $this->sumtriforecast[2];
                                    break;
                                    case 10009:
                                        $tri_forecast = $this->avgtriforecast[2];
                                    break;
                                    case 10010:
                                        if(isset($this->sumtriforecast[3]->tri_forecast) && isset($this->sumtriforecast[2]->tri_forecast))
                                        {
                                            $au = $this->sumtriforecast[2]->tri_forecast;
                                            $min = $this->sumtriforecast[3]->tri_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10011:
                                        $tri_forecast = $this->sumtriforecast[3];
                                    break;
                                    case 10012:
                                        $tri_forecast = $this->avgtriforecast[3];
                                    break;
                                    case 10013:
                                        if(isset($this->sumtriforecast[3]->tri_forecast) && (isset($this->sumtriforecast[24]->tri_forecast) && $this->sumtriforecast[24]->tri_forecast != 0))
                                        {
                                            $min = $this->sumtriforecast[3]->tri_forecast;
                                            $hs = $this->sumtriforecast[24]->tri_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10014:
                                        $tri_forecast = $this->avgtriforecast[4];
                                    break;
                                    case 10015:
                                        $tri_forecast = $this->avgtriforecast[5];
                                    break;
                                    case 10016:
                                        $tri_forecast = 0;
                                    break;
                                    case 10017:
                                        $tri_forecast = $this->avgtriforecast[6];
                                    break;
                                    case 10018:
                                        $tri_forecast = $this->avgtriforecast[7];
                                    break;
                                    case 10019:
                                        $tri_forecast = $this->sumtriforecast[4];
                                    break;
                                    case 10020:
                                        if(isset($this->sumtriforecast[4]->tri_forecast) && (isset($this->sumtriforecast[25]->tri_forecast) && $this->sumtriforecast[25]->tri_forecast != 0))
                                        {
                                            $min = $this->sumtriforecast[4]->tri_forecast;
                                            $hs = $this->sumtriforecast[25]->tri_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10021:
                                        $tri_forecast = $this->avgtriforecast[8];
                                    break;
                                    case 10022:
                                        $tri_forecast = $this->sumtriforecast[5];
                                    break;
                                    case 10023:
                                        $tri_forecast = $this->sumtriforecast[6];
                                    break;
                                    case 10024:
                                        if(isset($this->sumtriforecast[7]->tri_forecast) && isset($this->sumtriforecast[6]->tri_forecast))
                                        {
                                            $au = $this->sumtriforecast[6]->tri_forecast;
                                            $min = $this->sumtriforecast[7]->tri_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10025:
                                        $tri_forecast = $this->sumtriforecast[7];
                                    break;
                                    case 10026:
                                        $tri_forecast = $this->avgtriforecast[9];
                                    break;
                                    case 10027:
                                        $tri_forecast = $this->sumtriforecast[8];
                                    break;
                                    case 10028:
                                        //$tri_forecast = $this->sumtriforecast[9];
                                        //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                                //SUMATRIMESTRAL((((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                                   
                                                
                                                                                    
                                                //10010 Ley Au MMSA_HPGR_Ley Au 
                                                //Promedio Ponderado Trimestral(10011 MMSA_HPGR_Mineral Triturado t, 10010 MMSA_HPGR_Ley Au g/t)                         
                                                $sumaproducto10030 = DB::select(
                                                    'SELECT A.variable_id as var, SUM(A.valor * B.valor) as sumaproducto FROM
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10030) as A
                                                    INNER JOIN   
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10031) as B
                                                    ON A.fecha = B.fecha
                                                    WHERE A.fecha between ? and ?
                                                    GROUP BY A.variable_id', 
                                                    [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                                                );   

                                                //10033 MMSA_APILAM_STACKER_Recuperación %
                                                //Promedio Ponderado Trimestral(10011 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                                $sumaproducto10033= DB::select(
                                                    'SELECT A.variable_id as var, SUM(A.valor * B.valor) as sumaproducto FROM
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10033) as A
                                                    INNER JOIN   
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10031) as B
                                                    ON A.fecha = B.fecha
                                                    WHERE A.fecha between ? and ?
                                                    GROUP BY A.variable_id', 
                                                    [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                                                ); 
                                                                                
                                                $suma10031 = $this->sumtriforecast10031; 
                                            

                                                if(isset($sumaproducto10030[0]->sumaproducto) && isset($sumaproducto10033[0]->sumaproducto) && isset($suma10031[0]->suma))
                                                {
                                                    if ($suma10031[0]->suma > 0) {
                                                        //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                        $recup =  $sumaproducto10033[0]->sumaproducto/$suma10031[0]->suma;
                                                        $leyAu = $sumaproducto10030[0]->sumaproducto/$suma10031[0]->suma;
                                                        $sumMin = $suma10031[0]->suma;
                                                        $tri_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                        if($tri_forecast > 100)
                                                        {
                                                            return number_format(round($tri_forecast), 0, '.', ',');
                                                        }
                                                        else
                                                        {
                                                            return number_format($tri_forecast, 2, '.', ',');
                                                        }
                                                    }
                                                    else {
                                                        return '-';
                                                    }
                                                }
                                                else
                                                {
                                                    return '-';
                                                } 
                                    break;
                                    case 10029:
                                        $tri_forecast = $this->avgtriforecast[10];
                                    break;
                                    case 10030:
                                        if(isset($this->sumtriforecast[10]->tri_forecast) && isset($this->sumtriforecast[8]->tri_forecast))
                                        {
                                            $au = $this->sumtriforecast[8]->tri_forecast;
                                            $min = $this->sumtriforecast[10]->tri_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10031:
                                        $tri_forecast = $this->sumtriforecast[10];
                                    break;
                                    case 10032:
                                        if(isset($this->sumtriforecast[10]->tri_forecast) && (isset($this->sumtriforecast[26]->tri_forecast) && $this->sumtriforecast[26]->tri_forecast != 0))
                                        {
                                            $min = $this->sumtriforecast[10]->tri_forecast;
                                            $hs = $this->sumtriforecast[26]->tri_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10033:
                                        $tri_forecast = $this->avgtriforecast[11];
                                    break;
                                    case 10034:
                                        $tri_forecast = $this->avgtriforecast[12];
                                    break;
                                    case 10035:
                                        if(isset($this->sumtriforecast[13]->tri_forecast) && isset($this->sumtriforecast[11]->tri_forecast))
                                        {
                                            $au = $this->sumtriforecast[11]->tri_forecast;
                                            $min = $this->sumtriforecast[13]->tri_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10036:
                                        $tri_forecast = $this->avgtriforecast[13];
                                    break;
                                    case 10037:
                                        $tri_forecast = $this->sumtriforecast[11];
                                    break;
                                    case 10038:
                                        //$tri_forecast = $this->sumtriforecast[12];
                                            //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                                    //SUMATRIMESTRAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035) 
                                                                                        
                                                    //10035 MMSA_APILAM_TA_Ley Au g/t
                                                    //Promedio Ponderado Trimestral(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                                    $sumaproducto10035 = DB::select(
                                                        'SELECT A.variable_id as var, SUM(A.valor * B.valor) as sumaproducto FROM
                                                        (SELECT fecha, variable_id, [valor]
                                                        FROM [dbo].[forecast]
                                                        where variable_id = 10035) as A
                                                        INNER JOIN   
                                                        (SELECT fecha, variable_id, [valor]
                                                        FROM [dbo].[forecast]
                                                        where variable_id = 10039) as B
                                                        ON A.fecha = B.fecha
                                                        WHERE A.fecha between ? and ?
                                                        GROUP BY A.variable_id', 
                                                        [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                                                    ); 
                
                                                    //10036 MMSA_APILAM_TA_Recuperación %
                                                    //Promedio Ponderado Trimestral(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                                    $sumaproducto10036 = DB::select(
                                                        'SELECT A.variable_id as var, SUM(A.valor * B.valor) as sumaproducto FROM
                                                        (SELECT fecha, variable_id, [valor]
                                                        FROM [dbo].[forecast]
                                                        where variable_id = 10036) as A
                                                        INNER JOIN   
                                                        (SELECT fecha, variable_id, [valor]
                                                        FROM [dbo].[forecast]
                                                        where variable_id = 10039) as B
                                                        ON A.fecha = B.fecha
                                                        WHERE A.fecha between ? and ?
                                                        GROUP BY A.variable_id', 
                                                        [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                                                    );                                     
                                                    $suma10039 = $this->sumtriforecast10039;                                     
                
                                                    if(isset($sumaproducto10035[0]->sumaproducto) && isset($sumaproducto10036[0]->sumaproducto) && isset($suma10039[0]->suma))
                                                    {
                                                        if ($suma10039[0]->suma > 0) {
                                                            //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                            $recup =  $sumaproducto10036[0]->sumaproducto/$suma10039[0]->suma;
                                                            $leyAu = $sumaproducto10035[0]->sumaproducto/$suma10039[0]->suma;
                                                            $sumMin = $suma10039[0]->suma;
                                                            $tri_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                            if($tri_forecast > 100)
                                                            {
                                                                return number_format(round($tri_forecast), 0, '.', ',');
                                                            }
                                                            else
                                                            {
                                                                return number_format($tri_forecast, 2, '.', ',');
                                                            }
                                                        }
                                                        else {
                                                            return '-';
                                                        }
                                                    }
                                                    else
                                                    {
                                                        return '-';
                                                    }
                                    break;
                                    case 10039:
                                        $tri_forecast = $this->sumtriforecast[13];
                                    break;
                                    case 10040:
                                        $tri_forecast = $this->avgtriforecast[14];
                                    break;
                                    case 10041:
                                        if(isset($this->leytriforecast[0]->sumaproducto) && (isset($this->leytriforecast[0]->suma) && $this->leytriforecast[0]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[0]->sumaproducto;
                                            $hs = $this->leytriforecast[0]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10042:
                                        if(isset($this->leytriforecast[1]->sumaproducto) && (isset($this->leytriforecast[1]->suma) && $this->leytriforecast[1]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[1]->sumaproducto;
                                            $hs = $this->leytriforecast[1]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10043:
                                        if(isset($this->leytriforecast[2]->sumaproducto) && (isset($this->leytriforecast[2]->suma) && $this->leytriforecast[2]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[2]->sumaproducto;
                                            $hs = $this->leytriforecast[2]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10044:
                                        if(isset($this->leytriforecast[3]->sumaproducto) && (isset($this->leytriforecast[3]->suma) && $this->leytriforecast[3]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[3]->sumaproducto;
                                            $hs = $this->leytriforecast[3]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10045:
                                        $tri_forecast = $this->sumtriforecast[14];
                                    break;
                                    case 10046:
                                        $tri_forecast = $this->sumtriforecast[15];
                                    break;
                                    case 10047:
                                        $tri_forecast = $this->sumtriforecast[16];
                                    break;
                                    case 10048:
                                        $tri_forecast = $this->sumtriforecast[17];                            
                                        if(isset($tri_forecast->tri_forecast))
                                        {
                                            $t_forecast = $tri_forecast->tri_forecast;
                                            return number_format($t_forecast, 2, '.', ',');                                
                                        }
                                        else
                                        {
                                            return '-';
                                        }
                                    break;
                                    case 10049:
                                        $tri_forecast = $this->avgtriforecast[15];
                                    break;
                                    case 10050:
                                        if(isset($this->leytriforecast[4]->sumaproducto) && (isset($this->leytriforecast[4]->suma) && $this->leytriforecast[4]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[4]->sumaproducto;
                                            $hs = $this->leytriforecast[4]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10051:
                                        if(isset($this->leytriforecast[5]->sumaproducto) && (isset($this->leytriforecast[5]->suma) && $this->leytriforecast[5]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[5]->sumaproducto;
                                            $hs = $this->leytriforecast[5]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10052:
                                        $tri_forecast = $this->sumtriforecast[18];
                                    break;
                                    case 10053:
                                        $tri_forecast = $this->sumtriforecast[19];
                                    break;
                                    case 10054:
                                        if(isset($this->leytriforecast[6]->sumaproducto) && (isset($this->leytriforecast[6]->suma) && $this->leytriforecast[6]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[6]->sumaproducto;
                                            $hs = $this->leytriforecast[6]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10055:
                                        if(isset($this->leytriforecast[7]->sumaproducto) && (isset($this->leytriforecast[7]->suma) && $this->leytriforecast[7]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[7]->sumaproducto;
                                            $hs = $this->leytriforecast[7]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10056:
                                        if(isset($this->leytriforecast[8]->sumaproducto) && (isset($this->leytriforecast[8]->suma) && $this->leytriforecast[8]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[8]->sumaproducto;
                                            $hs = $this->leytriforecast[8]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10057:
                                        if(isset($this->leytriforecast[9]->sumaproducto) && (isset($this->leytriforecast[9]->suma) && $this->leytriforecast[9]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[9]->sumaproducto;
                                            $hs = $this->leytriforecast[9]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10058:
                                        if(isset($this->leytriforecast[10]->sumaproducto) && (isset($this->leytriforecast[10]->suma) && $this->leytriforecast[10]->suma != 0))
                                        {
                                            $min = $this->leytriforecast[10]->sumaproducto;
                                            $hs = $this->leytriforecast[10]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10059:
                                        $tri_forecast = $this->sumtriforecast[20];
                                    break;
                                    case 10060:
                                        $tri_forecast = $this->sumtriforecast[21];
                                    break;
                                    case 10061:
                                        $tri_forecast = $this->sumtriforecast[22];
                                    break;
                                    case 10062:
                                        $tri_forecast = $this->sumtriforecast[23];
                                    break;
                                    case 10063:
                                        $tri_forecast = $this->sumtriforecast[24];
                                    break;
                                    case 10064:
                                        $tri_forecast = $this->sumtriforecast[25];
                                    break;
                                    case 10065:
                                        $tri_forecast = $this->sumtriforecast[26];
                                    break;
                                    case 10066:
                                        $tri_forecast = 0;
                                    break;
                                    case 10067:
                                        $tri_forecast = $this->sumtriforecast[27];
                                    break;
                                    case 10068:
                                        $tri_forecast = $this->sumtriforecast[28];
                                    break;
                                    case 10069:
                                        $tri_forecast = $this->sumtriforecast[29];
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
                                if (in_array($data->variable_id, $this->div))
                                {
                                    if ($min > 0)
                                    {
                                        $t_forecast = $min/$hs;
                                        if($t_forecast > 100)
                                        {
                                            return number_format(round($t_forecast), 0, '.', ',');
                                        }
                                        else
                                        {
                                            return number_format($t_forecast, 2, '.', ',');
                                        }
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                }
                                if(isset($tri_forecast->tri_forecast))
                                {
                                    $t_forecast = $tri_forecast->tri_forecast;
                                    if($t_forecast > 100 || in_array($data->variable_id, $this->percentage))
                                    {
                                        return number_format(round($t_forecast), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($t_forecast, 2, '.', ',');
                                    }
                                }
                                else
                                {
                                    return '-';
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
                                            if($a_real > 100 || in_array($data->variable_id, $this->percentage))
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
                                            case 10037: 
                                                //10037: MMSA_APILAM_TA_Total Au Apilado (oz)                  
                                                //SUMATORIA ANUAL(((10039 MMSA_APILAM_TA_Total Mineral Apilado (t))*(10035 MMSA_APILAM_TA_Ley Au (g/t))) / 31.1035)   
                                                $anio_real= DB::select(
                                                    'SELECT YEAR(A.fecha) as year, SUM((A.valor * B.valor)/31.1035) as anio_real FROM
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[data]
                                                    where variable_id = 10039) as A
                                                    INNER JOIN   
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[data]
                                                    where variable_id = 10035) as B
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
                                                if ($this->date == '2022-12-31')
                                                {
                                                    return number_format(round(107280), 0, '.', ',');
                                                }
                                                else{
                                                    if ($this->date == '2023-04-30')
                                                    {
                                                        return number_format(round(33491), 0, '.', ',');
                                                    }
                                                    else {
                                                        if (strtotime($this->date) >= strtotime('2023-05-29')) {
                                                            $resultado = DB::select('SELECT 
                                                                SUM(CASE 
                                                                    WHEN V10031 > 0 THEN ((V10033/V10031) * (V10030/V10031) * V10031 * 0.01)/31.1035
                                                                    ELSE 0
                                                                END) AS AU FROM
                                                                (SELECT MONTH(fecha) AS MES, SUM(valor) AS V10031
                                                                FROM [dbo].[data]
                                                                WHERE variable_id = 10031
                                                                AND  DATEPART(y, fecha) <= DATEPART(y, ?)
                                                                AND YEAR(fecha) = YEAR(?)
                                                                GROUP BY MONTH(fecha)) AS GC
                                                                LEFT JOIN
                                                                (SELECT MONTH(A.fecha) AS MES, SUM(A.valor * B.valor) AS V10030 FROM
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10030) as A
                                                                INNER JOIN   
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10031) as B
                                                                ON A.fecha = B.fecha
                                                                AND  DATEPART(y, A.fecha) <=  DATEPART(y, ?)
                                                                AND YEAR(A.fecha) = YEAR(?)
                                                                GROUP BY MONTH(A.fecha)) AS GA
                                                                ON GC.MES = GA.MES                                                 
                                                                LEFT JOIN
                                                                (SELECT MONTH(A.fecha) AS MES, SUM(A.valor * B.valor) AS V10033 FROM
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10033) as A
                                                                INNER JOIN   
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10031) as B
                                                                ON A.fecha = B.fecha
                                                                AND  DATEPART(y, A.fecha) <=  DATEPART(y, ?)
                                                                AND YEAR(A.fecha) = YEAR(?)
                                                                GROUP BY MONTH(A.fecha)) AS GB
                                                                ON GC.MES = GB.MES',
                                                                [$this->date, $this->date, $this->date, $this->date, $this->date, $this->date]);
                                                            if(isset($resultado[0]->AU))
                                                            {
                                                                if ($resultado[0]->AU > 0) {
                                                                    return number_format(round($resultado[0]->AU), 0, '.', ',');                                                
                                                                }
                                                                else {
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
                                                            //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                                            //SUMAANUAL((((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)    
                                                            
                                                            
                                                                                                
                                                            //10030 Ley Au MMSA_HPGR_Ley Au 
                                                            //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10030 MMSA_HPGR_Ley Au g/t)                         
                                                            $sumaproducto10030 = DB::select(
                                                                'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10030) as A
                                                                INNER JOIN   
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10031) as B
                                                                ON A.fecha = B.fecha
                                                                WHERE YEAR(A.fecha) = ?
                                                                AND  DATEPART(y, A.fecha) <=  ?
                                                                GROUP BY YEAR(A.fecha)',
                                                                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                            );  
                    
                                                            //10033 MMSA_APILAM_STACKER_Recuperación %
                                                            //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                                            $sumaproducto10033 = DB::select(
                                                                'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10033) as A
                                                                INNER JOIN   
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10031) as B
                                                                ON A.fecha = B.fecha
                                                                WHERE YEAR(A.fecha) = ?
                                                                AND  DATEPART(y, A.fecha) <=  ?
                                                                GROUP BY YEAR(A.fecha)',
                                                                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                            );                                     
                                                            $suma10031 = $this->sumanioreal10031; 
                                                        
                                                            
                                                            
                                                            if(isset($sumaproducto10030[0]->sumaproducto) && isset($sumaproducto10033[0]->sumaproducto) && isset($suma10031[0]->suma))
                                                            {
                                                                if ($suma10031[0]->suma > 0) {
                                                                    //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                                    $recup =  $sumaproducto10033[0]->sumaproducto/$suma10031[0]->suma;
                                                                    $leyAu = $sumaproducto10030[0]->sumaproducto/$suma10031[0]->suma;
                                                                    $sumMin = $suma10031[0]->suma;
                                                                    $a_real =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                                    if($a_real > 100)
                                                                    {
                                                                        return number_format(round($a_real), 0, '.', ',');
                                                                    }
                                                                    else
                                                                    {
                                                                        return number_format($a_real, 2, '.', ',');
                                                                    }
                                                                }
                                                                else {
                                                                    return '-';
                                                                }
                                                            }
                                                            else
                                                            {
                                                                return '-';
                                                            }
                                                        }
                                                    }                                        
                                                }
                                            break;
                                            case 10038: 
                                                if ($this->date == '2022-12-31')
                                                {
                                                    return number_format(round(107362), 0, '.', ',');
                                                }
                                                else{
                                                    if ($this->date == '2023-04-30')
                                                    {
                                                        return number_format(round(33491), 0, '.', ',');
                                                    }
                                                    else {
                                                        if (strtotime($this->date) >= strtotime('2023-05-29')) {
                                                            $resultado = DB::select('SELECT 
                                                                SUM(CASE 
                                                                    WHEN V10039 > 0 THEN ((V10036/V10039) * (V10035/V10039) * V10039 * 0.01)/31.1035
                                                                    ELSE 0
                                                                END) AS AU FROM
                                                                (SELECT MONTH(fecha) AS MES, SUM(valor) AS V10039
                                                                FROM [dbo].[data]
                                                                WHERE variable_id = 10039
                                                                AND  DATEPART(y, fecha) <= DATEPART(y, ?)
                                                                AND YEAR(fecha) = YEAR(?)
                                                                GROUP BY MONTH(fecha)) AS GC
                                                                LEFT JOIN
                                                                (SELECT MONTH(A.fecha) AS MES, SUM(A.valor * B.valor) AS V10035 FROM
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10035) as A
                                                                INNER JOIN   
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10039) as B
                                                                ON A.fecha = B.fecha
                                                                AND  DATEPART(y, A.fecha) <=  DATEPART(y, ?)
                                                                AND YEAR(A.fecha) = YEAR(?)
                                                                GROUP BY MONTH(A.fecha)) AS GA
                                                                ON GC.MES = GA.MES                                                 
                                                                LEFT JOIN
                                                                (SELECT MONTH(A.fecha) AS MES, SUM(A.valor * B.valor) AS V10036 FROM
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10036) as A
                                                                INNER JOIN   
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[data]
                                                                where variable_id = 10039) as B
                                                                ON A.fecha = B.fecha
                                                                AND  DATEPART(y, A.fecha) <=  DATEPART(y, ?)
                                                                AND YEAR(A.fecha) = YEAR(?)
                                                                GROUP BY MONTH(A.fecha)) AS GB
                                                                ON GC.MES = GB.MES',
                                                                [$this->date, $this->date, $this->date, $this->date, $this->date, $this->date]);
                                                            if(isset($resultado[0]->AU))
                                                            {
                                                                if ($resultado[0]->AU > 0) {
                                                                    return number_format(round($resultado[0]->AU), 0, '.', ',');                                                
                                                                }
                                                                else {
                                                                    return '-';
                                                                }
                                                            }
                                                            else
                                                            {
                                                                return '-';
                                                            }
                                                        }
                                                        else{
                                                            //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                                            //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)       
                                                            
                                                                                                
                                                            //10035 MMSA_APILAM_TA_Ley Au g/t
                                                            //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                                            $sumaproducto10035 = DB::select(
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
                                                                                                
                                                            //10036 MMSA_APILAM_TA_Recuperación %
                                                            //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                                            $sumaproducto10036 = DB::select(
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
                                                            $suma10039 = $this->sumanioreal10039; 
                                                            
                    
                                                            if(isset($sumaproducto10035[0]->sumaproducto) && isset($sumaproducto10036[0]->sumaproducto) && isset($suma10039[0]->suma))
                                                            {
                                                                if ($suma10039[0]->suma > 0) {
                                                                    //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                                    $recup =  $sumaproducto10036[0]->sumaproducto/$suma10039[0]->suma;
                                                                    $leyAu = $sumaproducto10035[0]->sumaproducto/$suma10039[0]->suma;
                                                                    $sumMin = $suma10039[0]->suma;
                                                                    $a_real =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                                    if($a_real > 100)
                                                                    {
                                                                        return number_format(round($a_real), 0, '.', ',');
                                                                    }
                                                                    else
                                                                    {
                                                                        return number_format($a_real, 2, '.', ',');
                                                                    }
                                                                }
                                                                else {
                                                                    return '-';
                                                                }
                                                            }
                                                            else
                                                            {
                                                                return '-';
                                                            }
                                                        }
                                                    }                                        
                                                }
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
                                            case 10048:
                                                $anio_real= DB::select(
                                                    'SELECT YEAR(fecha) as year, SUM(valor) as anio_real
                                                    FROM [dbo].[data]
                                                    WHERE variable_id = ?
                                                    AND  YEAR(fecha) = ?
                                                    AND  DATEPART(y, fecha) <= ?
                                                    GROUP BY YEAR(fecha)', 
                                                    [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                );
                                                if(isset($anio_real[0]->anio_real))
                                                {
                                                    $a_real = $anio_real[0]->anio_real;
                                                    return number_format($a_real, 2, '.', ',');                                        
                                                }
                                                else
                                                {
                                                    return '-';
                                                }
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
                                                GROUP BY YEAR(fecha)', 
                                                [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                            );
                                            if(isset($anio_real[0]->anio_real))
                                            {
                                                $a_real = $anio_real[0]->anio_real;
                                                return number_format(round($a_real), 0, '.', ',');
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
                            //MODIFICADO 14/10/2024
                            ->addColumn('anio_budget', function($data)
                            {
                                switch($data->variable_id)
                                {
                                    case 10002:
                                        $anio_budget = $this->sumaniobudget[0];
                                    break;
                                    case 10003:
                                        $anio_budget = $this->avganiobudget[0];
                                    break;
                                    case 10004:
                                        if(isset($this->sumaniobudget[0]->anio_budget) && isset($this->sumaniobudget[1]->anio_budget))
                                        {
                                            $au = $this->sumaniobudget[0]->anio_budget;
                                            $min = $this->sumaniobudget[1]->anio_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10005:
                                        $anio_budget = $this->sumaniobudget[1];
                                    break;
                                    case 10006:
                                        if(isset($this->sumaniobudget[1]->anio_budget) && (isset($this->sumaniobudget[23]->anio_budget) && $this->sumaniobudget[23]->anio_budget != 0))
                                        {
                                            $min = $this->sumaniobudget[1]->anio_budget;
                                            $hs = $this->sumaniobudget[23]->anio_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10007:
                                        $anio_budget = $this->avganiobudget[1];
                                    break;
                                    case 10008:
                                        $anio_budget = $this->sumaniobudget[2];
                                    break;
                                    case 10009:
                                        $anio_budget = $this->avganiobudget[2];
                                    break;
                                    case 10010:
                                        if(isset($this->sumaniobudget[3]->anio_budget) && isset($this->sumaniobudget[2]->anio_budget))
                                        {
                                            $au = $this->sumaniobudget[2]->anio_budget;
                                            $min = $this->sumaniobudget[3]->anio_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10011:
                                        $anio_budget = $this->sumaniobudget[3];
                                    break;
                                    case 10012:
                                        $anio_budget = $this->avganiobudget[3];
                                    break;
                                    case 10013:
                                        if(isset($this->sumaniobudget[3]->anio_budget) && (isset($this->sumaniobudget[24]->anio_budget) && $this->sumaniobudget[24]->anio_budget != 0))
                                        {
                                            $min = $this->sumaniobudget[3]->anio_budget;
                                            $hs = $this->sumaniobudget[24]->anio_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10014:
                                        $anio_budget = $this->avganiobudget[4];
                                    break;
                                    case 10015:
                                        $anio_budget = $this->avganiobudget[5];
                                    break;
                                    case 10016:
                                        $anio_budget = 0;
                                    break;
                                    case 10017:
                                        $anio_budget = $this->avganiobudget[6];
                                    break;
                                    case 10018:
                                        $anio_budget = $this->avganiobudget[7];
                                    break;
                                    case 10019:
                                        $anio_budget = $this->sumaniobudget[4];
                                    break;
                                    case 10020:
                                        if(isset($this->sumaniobudget[4]->anio_budget) && (isset($this->sumaniobudget[25]->anio_budget) && $this->sumaniobudget[25]->anio_budget != 0))
                                        {
                                            $min = $this->sumaniobudget[4]->anio_budget;
                                            $hs = $this->sumaniobudget[25]->anio_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10021:
                                        $anio_budget = $this->avganiobudget[8];
                                    break;
                                    case 10022:
                                        $anio_budget = $this->sumaniobudget[5];
                                    break;
                                    case 10023:
                                        $anio_budget = $this->sumaniobudget[6];
                                    break;
                                    case 10024:
                                        if(isset($this->sumaniobudget[7]->anio_budget) && isset($this->sumaniobudget[6]->anio_budget))
                                        {
                                            $au = $this->sumaniobudget[6]->anio_budget;
                                            $min = $this->sumaniobudget[7]->anio_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10025:
                                        $anio_budget = $this->sumaniobudget[7];
                                    break;
                                    case 10026:
                                        $anio_budget = $this->avganiobudget[9];
                                    break;
                                    case 10027:
                                        $anio_budget = $this->sumaniobudget[8];
                                    break;
                                    case 10028:
                                        //$anio_budget = $this->sumaniobudget[9];
                                        //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                        //SUMAANUAL((((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                                             
                                        //10030 Ley Au MMSA_HPGR_Ley Au 
                                        //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10030 MMSA_HPGR_Ley Au g/t)                         
                                        $sumaproducto10030 = DB::select(
                                            'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [dbo].[budget]
                                            where variable_id = 10030) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [dbo].[budget]
                                            where variable_id = 10031) as B
                                            ON A.fecha = B.fecha
                                            WHERE YEAR(A.fecha) = ?
                                            AND  DATEPART(y, A.fecha) <=  ?
                                            GROUP BY YEAR(A.fecha)',
                                            [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                        );  

                                        //10033 MMSA_APILAM_STACKER_Recuperación %
                                        //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                        $sumaproducto10033 = DB::select(
                                            'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [dbo].[budget]
                                            where variable_id = 10033) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [dbo].[budget]
                                            where variable_id = 10031) as B
                                            ON A.fecha = B.fecha
                                            WHERE YEAR(A.fecha) = ?
                                            AND  DATEPART(y, A.fecha) <=  ?
                                            GROUP BY YEAR(A.fecha)',
                                            [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                        );                                     
                                        $suma10031 = $this->sumaniobudget10031; 
                                        
                                        
                                        
                                        if(isset($sumaproducto10030[0]->sumaproducto) && isset($sumaproducto10033[0]->sumaproducto) && isset($suma10031[0]->suma))
                                        {
                                            if ($suma10031[0]->suma > 0) {
                                                //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                $recup =  $sumaproducto10033[0]->sumaproducto/$suma10031[0]->suma;
                                                $leyAu = $sumaproducto10030[0]->sumaproducto/$suma10031[0]->suma;
                                                $sumMin = $suma10031[0]->suma;
                                                $anio_budget =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                if($anio_budget > 100)
                                                {
                                                    return number_format(round($anio_budget), 0, '.', ',');
                                                }
                                                else
                                                {
                                                    return number_format($anio_budget, 2, '.', ',');
                                                }
                                            }
                                            else {
                                                return '-';
                                            }
                                        }
                                        else
                                        {
                                            return '-';
                                        }
                                    break;
                                    case 10029:
                                        $anio_budget = $this->avganiobudget[10];
                                    break;
                                    case 10030:
                                        if(isset($this->sumaniobudget[10]->anio_budget) && isset($this->sumaniobudget[8]->anio_budget))
                                        {
                                            $au = $this->sumaniobudget[8]->anio_budget;
                                            $min = $this->sumaniobudget[10]->anio_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10031:
                                        $anio_budget = $this->sumaniobudget[10];
                                    break;
                                    case 10032:
                                        if(isset($this->sumaniobudget[10]->anio_budget) && (isset($this->sumaniobudget[26]->anio_budget) && $this->sumaniobudget[26]->anio_budget != 0))
                                        {
                                            $min = $this->sumaniobudget[10]->anio_budget;
                                            $hs = $this->sumaniobudget[26]->anio_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10033:
                                        $anio_budget = $this->avganiobudget[11];
                                    break;
                                    case 10034:
                                        $anio_budget = $this->avganiobudget[12];
                                    break;
                                    case 10035:
                                        if(isset($this->sumaniobudget[13]->anio_budget) && isset($this->sumaniobudget[11]->anio_budget))
                                        {
                                            $au = $this->sumaniobudget[11]->anio_budget;
                                            $min = $this->sumaniobudget[13]->anio_budget;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10036:
                                        $anio_budget = $this->avganiobudget[13];
                                    break;
                                    case 10037:
                                        $anio_budget = $this->sumaniobudget[11];
                                    break;
                                    case 10038:
                                        //$anio_budget = $this->sumaniobudget[12];
                                        //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                                            //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)       
                                                            
                                                                                                
                                                            //10035 MMSA_APILAM_TA_Ley Au g/t
                                                            //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                                            $sumaproducto10035 = DB::select(
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
                                                                                                
                                                            //10036 MMSA_APILAM_TA_Recuperación %
                                                            //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                                            $sumaproducto10036 = DB::select(
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
                                                            $suma10039 = $this->sumaniobudget10039; 
                                                            

                                                            if(isset($sumaproducto10035[0]->sumaproducto) && isset($sumaproducto10036[0]->sumaproducto) && isset($suma10039[0]->suma))
                                                            {
                                                                if ($suma10039[0]->suma > 0) {
                                                                    //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                                    $recup =  $sumaproducto10036[0]->sumaproducto/$suma10039[0]->suma;
                                                                    $leyAu = $sumaproducto10035[0]->sumaproducto/$suma10039[0]->suma;
                                                                    $sumMin = $suma10039[0]->suma;
                                                                    $anio_budget =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                                    if($anio_budget > 100)
                                                                    {
                                                                        return number_format(round($anio_budget), 0, '.', ',');
                                                                    }
                                                                    else
                                                                    {
                                                                        return number_format($anio_budget, 2, '.', ',');
                                                                    }
                                                                }
                                                                else {
                                                                    return '-';
                                                                }
                                                            }
                                                            else
                                                            {
                                                                return '-';
                                                            }
                                    break;
                                    case 10039:
                                        $anio_budget = $this->sumaniobudget[13];
                                    break;
                                    case 10040:
                                        $anio_budget = $this->avganiobudget[14];
                                    break;
                                    case 10041:
                                        if(isset($this->leyaniobudget[0]->sumaproducto) && (isset($this->leyaniobudget[0]->suma) && $this->leyaniobudget[0]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[0]->sumaproducto;
                                            $hs = $this->leyaniobudget[0]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10042:
                                        if(isset($this->leyaniobudget[1]->sumaproducto) && (isset($this->leyaniobudget[1]->suma) && $this->leyaniobudget[1]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[1]->sumaproducto;
                                            $hs = $this->leyaniobudget[1]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10043:
                                        if(isset($this->leyaniobudget[2]->sumaproducto) && (isset($this->leyaniobudget[2]->suma) && $this->leyaniobudget[2]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[2]->sumaproducto;
                                            $hs = $this->leyaniobudget[2]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10044:
                                        if(isset($this->leyaniobudget[3]->sumaproducto) && (isset($this->leyaniobudget[3]->suma) && $this->leyaniobudget[3]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[3]->sumaproducto;
                                            $hs = $this->leyaniobudget[3]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10045:
                                        $anio_budget = $this->sumaniobudget[14];
                                    break;
                                    case 10046:
                                        $anio_budget = $this->sumaniobudget[15];
                                    break;
                                    case 10047:
                                        $anio_budget = $this->sumaniobudget[16];
                                    break;
                                    case 10048:
                                        $anio_budget = $this->sumaniobudget[17];                            
                                        if(isset($anio_budget->anio_budget))
                                        {
                                            $a_budget = $anio_budget->anio_budget;
                                            return number_format($a_budget, 2, '.', ',');                                
                                        }
                                        else
                                        {
                                            return '-';
                                        }
                                    break;
                                    case 10049:
                                        $anio_budget = $this->avganiobudget[15];
                                    break;
                                    case 10050:
                                        if(isset($this->leyaniobudget[4]->sumaproducto) && (isset($this->leyaniobudget[4]->suma) && $this->leyaniobudget[4]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[4]->sumaproducto;
                                            $hs = $this->leyaniobudget[4]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10051:
                                        if(isset($this->leyaniobudget[5]->sumaproducto) && (isset($this->leyaniobudget[5]->suma) && $this->leyaniobudget[5]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[5]->sumaproducto;
                                            $hs = $this->leyaniobudget[5]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10052:
                                        $anio_budget = $this->sumaniobudget[18];
                                    break;
                                    case 10053:
                                        $anio_budget = $this->sumaniobudget[19];
                                    break;
                                    case 10054:
                                        if(isset($this->leyaniobudget[6]->sumaproducto) && (isset($this->leyaniobudget[6]->suma) && $this->leyaniobudget[6]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[6]->sumaproducto;
                                            $hs = $this->leyaniobudget[6]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10055:
                                        if(isset($this->leyaniobudget[7]->sumaproducto) && (isset($this->leyaniobudget[7]->suma) && $this->leyaniobudget[7]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[7]->sumaproducto;
                                            $hs = $this->leyaniobudget[7]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10056:
                                        if(isset($this->leyaniobudget[8]->sumaproducto) && (isset($this->leyaniobudget[8]->suma) && $this->leyaniobudget[8]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[8]->sumaproducto;
                                            $hs = $this->leyaniobudget[8]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10057:
                                        if(isset($this->leyaniobudget[9]->sumaproducto) && (isset($this->leyaniobudget[9]->suma) && $this->leyaniobudget[9]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[9]->sumaproducto;
                                            $hs = $this->leyaniobudget[9]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10058:
                                        if(isset($this->leyaniobudget[10]->sumaproducto) && (isset($this->leyaniobudget[10]->suma) && $this->leyaniobudget[10]->suma != 0))
                                        {
                                            $min = $this->leyaniobudget[10]->sumaproducto;
                                            $hs = $this->leyaniobudget[10]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10059:
                                        $anio_budget = $this->sumaniobudget[20];
                                    break;
                                    case 10060:
                                        $anio_budget = $this->sumaniobudget[21];
                                    break;
                                    case 10061:
                                        $anio_budget = $this->sumaniobudget[22];
                                    break;
                                    case 10062:
                                        $anio_budget = $this->sumaniobudget[23];
                                    break;
                                    case 10063:
                                        $anio_budget = $this->sumaniobudget[24];
                                    break;
                                    case 10064:
                                        $anio_budget = $this->sumaniobudget[25];
                                    break;
                                    case 10065:
                                        $anio_budget = $this->sumaniobudget[26];
                                    break;
                                    case 10066:
                                        $anio_budget = 0;
                                    break;
                                    case 10067:
                                        $anio_budget = $this->sumaniobudget[27];
                                    break;
                                    case 10068:
                                        $anio_budget = $this->sumaniobudget[28];
                                    break;
                                    case 10069:
                                        $anio_budget = $this->sumaniobudget[29];
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
                                if (in_array($data->variable_id, $this->div))
                                {
                                    if ($min > 0)
                                    {
                                        $a_budget = $min/$hs;
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
                                if(isset($anio_budget->anio_budget))
                                {
                                    $a_budget = $anio_budget->anio_budget;
                                    if($a_budget > 100 || in_array($data->variable_id, $this->percentage))
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
                            })
                            ->addColumn('anio_forecast', function($data)
                            {
                                switch($data->variable_id)
                                {
                                    case 10002:
                                        $anio_forecast = $this->sumanioforecast[0];
                                    break;
                                    case 10003:
                                        $anio_forecast = $this->avganioforecast[0];
                                    break;
                                    case 10004:
                                        if(isset($this->sumanioforecast[0]->anio_forecast) && isset($this->sumanioforecast[1]->anio_forecast))
                                        {
                                            $au = $this->sumanioforecast[0]->anio_forecast;
                                            $min = $this->sumanioforecast[1]->anio_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10005:
                                        $anio_forecast = $this->sumanioforecast[1];
                                    break;
                                    case 10006:
                                        if(isset($this->sumanioforecast[1]->anio_forecast) && (isset($this->sumanioforecast[23]->anio_forecast) && $this->sumanioforecast[23]->anio_forecast != 0))
                                        {
                                            $min = $this->sumanioforecast[1]->anio_forecast;
                                            $hs = $this->sumanioforecast[23]->anio_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10007:
                                        $anio_forecast = $this->avganioforecast[1];
                                    break;
                                    case 10008:
                                        $anio_forecast = $this->sumanioforecast[2];
                                    break;
                                    case 10009:
                                        $anio_forecast = $this->avganioforecast[2];
                                    break;
                                    case 10010:
                                        if(isset($this->sumanioforecast[3]->anio_forecast) && isset($this->sumanioforecast[2]->anio_forecast))
                                        {
                                            $au = $this->sumanioforecast[2]->anio_forecast;
                                            $min = $this->sumanioforecast[3]->anio_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10011:
                                        $anio_forecast = $this->sumanioforecast[3];
                                    break;
                                    case 10012:
                                        $anio_forecast = $this->avganioforecast[3];
                                    break;
                                    case 10013:
                                        if(isset($this->sumanioforecast[3]->anio_forecast) && (isset($this->sumanioforecast[24]->anio_forecast) && $this->sumanioforecast[24]->anio_forecast != 0))
                                        {
                                            $min = $this->sumanioforecast[3]->anio_forecast;
                                            $hs = $this->sumanioforecast[24]->anio_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10014:
                                        $anio_forecast = $this->avganioforecast[4];
                                    break;
                                    case 10015:
                                        $anio_forecast = $this->avganioforecast[5];
                                    break;
                                    case 10016:
                                        $anio_forecast = 0;
                                    break;
                                    case 10017:
                                        $anio_forecast = $this->avganioforecast[6];
                                    break;
                                    case 10018:
                                        $anio_forecast = $this->avganioforecast[7];
                                    break;
                                    case 10019:
                                        $anio_forecast = $this->sumanioforecast[4];
                                    break;
                                    case 10020:
                                        if(isset($this->sumanioforecast[4]->anio_forecast) && (isset($this->sumanioforecast[25]->anio_forecast) && $this->sumanioforecast[25]->anio_forecast != 0))
                                        {
                                            $min = $this->sumanioforecast[4]->anio_forecast;
                                            $hs = $this->sumanioforecast[25]->anio_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10021:
                                        $anio_forecast = $this->avganioforecast[8];
                                    break;
                                    case 10022:
                                        $anio_forecast = $this->sumanioforecast[5];
                                    break;
                                    case 10023:
                                        $anio_forecast = $this->sumanioforecast[6];
                                    break;
                                    case 10024:
                                        if(isset($this->sumanioforecast[7]->anio_forecast) && isset($this->sumanioforecast[6]->anio_forecast))
                                        {
                                            $au = $this->sumanioforecast[6]->anio_forecast;
                                            $min = $this->sumanioforecast[7]->anio_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10025:
                                        $anio_forecast = $this->sumanioforecast[7];
                                    break;
                                    case 10026:
                                        $anio_forecast = $this->avganioforecast[9];
                                    break;
                                    case 10027:
                                        $anio_forecast = $this->sumanioforecast[8];
                                    break;
                                    case 10028:
                                        //$anio_forecast = $this->sumanioforecast[9];
                                        //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                        //SUMAANUAL((((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                                             
                                        //10030 Ley Au MMSA_HPGR_Ley Au 
                                        //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10030 MMSA_HPGR_Ley Au g/t)                         
                                        $sumaproducto10030 = DB::select(
                                            'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [dbo].[forecast]
                                            where variable_id = 10030) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [dbo].[forecast]
                                            where variable_id = 10031) as B
                                            ON A.fecha = B.fecha
                                            WHERE YEAR(A.fecha) = ?
                                            AND  DATEPART(y, A.fecha) <=  ?
                                            GROUP BY YEAR(A.fecha)',
                                            [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                        );  

                                        //10033 MMSA_APILAM_STACKER_Recuperación %
                                        //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                        $sumaproducto10033 = DB::select(
                                            'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [dbo].[forecast]
                                            where variable_id = 10033) as A
                                            INNER JOIN   
                                            (SELECT fecha, variable_id, [valor]
                                            FROM [dbo].[forecast]
                                            where variable_id = 10031) as B
                                            ON A.fecha = B.fecha
                                            WHERE YEAR(A.fecha) = ?
                                            AND  DATEPART(y, A.fecha) <=  ?
                                            GROUP BY YEAR(A.fecha)',
                                            [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                        );                                     
                                        $suma10031 = $this->sumanioforecast10031; 
                                        
                                        
                                        
                                        if(isset($sumaproducto10030[0]->sumaproducto) && isset($sumaproducto10033[0]->sumaproducto) && isset($suma10031[0]->suma))
                                        {
                                            if ($suma10031[0]->suma > 0) {
                                                //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                $recup =  $sumaproducto10033[0]->sumaproducto/$suma10031[0]->suma;
                                                $leyAu = $sumaproducto10030[0]->sumaproducto/$suma10031[0]->suma;
                                                $sumMin = $suma10031[0]->suma;
                                                $anio_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                if($anio_forecast > 100)
                                                {
                                                    return number_format(round($anio_forecast), 0, '.', ',');
                                                }
                                                else
                                                {
                                                    return number_format($anio_forecast, 2, '.', ',');
                                                }
                                            }
                                            else {
                                                return '-';
                                            }
                                        }
                                        else
                                        {
                                            return '-';
                                        }
                                    break;
                                    case 10029:
                                        $anio_forecast = $this->avganioforecast[10];
                                    break;
                                    case 10030:
                                        if(isset($this->sumanioforecast[10]->anio_forecast) && isset($this->sumanioforecast[8]->anio_forecast))
                                        {
                                            $au = $this->sumanioforecast[8]->anio_forecast;
                                            $min = $this->sumanioforecast[10]->anio_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10031:
                                        $anio_forecast = $this->sumanioforecast[10];
                                    break;
                                    case 10032:
                                        if(isset($this->sumanioforecast[10]->anio_forecast) && (isset($this->sumanioforecast[26]->anio_forecast) && $this->sumanioforecast[26]->anio_forecast != 0))
                                        {
                                            $min = $this->sumanioforecast[10]->anio_forecast;
                                            $hs = $this->sumanioforecast[26]->anio_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10033:
                                        $anio_forecast = $this->avganioforecast[11];
                                    break;
                                    case 10034:
                                        $anio_forecast = $this->avganioforecast[12];
                                    break;
                                    case 10035:
                                        if(isset($this->sumanioforecast[13]->anio_forecast) && isset($this->sumanioforecast[11]->anio_forecast))
                                        {
                                            $au = $this->sumanioforecast[11]->anio_forecast;
                                            $min = $this->sumanioforecast[13]->anio_forecast;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10036:
                                        $anio_forecast = $this->avganioforecast[13];
                                    break;
                                    case 10037:
                                        $anio_forecast = $this->sumanioforecast[11];
                                    break;
                                    case 10038:
                                        //$anio_forecast = $this->sumanioforecast[12];
                                        //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                                            //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)       
                                                            
                                                                                                
                                                            //10035 MMSA_APILAM_TA_Ley Au g/t
                                                            //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                                            $sumaproducto10035 = DB::select(
                                                                'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[forecast]
                                                                where variable_id = 10035) as A
                                                                INNER JOIN   
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[forecast]
                                                                where variable_id = 10039) as B
                                                                ON A.fecha = B.fecha
                                                                WHERE YEAR(A.fecha) = ?
                                                                AND  DATEPART(y, A.fecha) <=  ?
                                                                GROUP BY YEAR(A.fecha)',
                                                                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                            );                                                                      
                                                                                                
                                                            //10036 MMSA_APILAM_TA_Recuperación %
                                                            //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                                            $sumaproducto10036 = DB::select(
                                                                'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[forecast]
                                                                where variable_id = 10036) as A
                                                                INNER JOIN   
                                                                (SELECT fecha, variable_id, [valor]
                                                                FROM [dbo].[forecast]
                                                                where variable_id = 10039) as B
                                                                ON A.fecha = B.fecha
                                                                WHERE YEAR(A.fecha) = ?
                                                                AND  DATEPART(y, A.fecha) <=  ?
                                                                GROUP BY YEAR(A.fecha)',
                                                                [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                            );                                     
                                                            $suma10039 = $this->sumanioforecast10039; 
                                                            
                    
                                                            if(isset($sumaproducto10035[0]->sumaproducto) && isset($sumaproducto10036[0]->sumaproducto) && isset($suma10039[0]->suma))
                                                            {
                                                                if ($suma10039[0]->suma > 0) {
                                                                    //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                                    $recup =  $sumaproducto10036[0]->sumaproducto/$suma10039[0]->suma;
                                                                    $leyAu = $sumaproducto10035[0]->sumaproducto/$suma10039[0]->suma;
                                                                    $sumMin = $suma10039[0]->suma;
                                                                    $anio_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                                    if($anio_forecast > 100)
                                                                    {
                                                                        return number_format(round($anio_forecast), 0, '.', ',');
                                                                    }
                                                                    else
                                                                    {
                                                                        return number_format($anio_forecast, 2, '.', ',');
                                                                    }
                                                                }
                                                                else {
                                                                    return '-';
                                                                }
                                                            }
                                                            else
                                                            {
                                                                return '-';
                                                            }
                                    break;
                                    case 10039:
                                        $anio_forecast = $this->sumanioforecast[13];
                                    break;
                                    case 10040:
                                        $anio_forecast = $this->avganioforecast[14];
                                    break;
                                    case 10041:
                                        if(isset($this->leyanioforecast[0]->sumaproducto) && (isset($this->leyanioforecast[0]->suma) && $this->leyanioforecast[0]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[0]->sumaproducto;
                                            $hs = $this->leyanioforecast[0]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10042:
                                        if(isset($this->leyanioforecast[1]->sumaproducto) && (isset($this->leyanioforecast[1]->suma) && $this->leyanioforecast[1]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[1]->sumaproducto;
                                            $hs = $this->leyanioforecast[1]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10043:
                                        if(isset($this->leyanioforecast[2]->sumaproducto) && (isset($this->leyanioforecast[2]->suma) && $this->leyanioforecast[2]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[2]->sumaproducto;
                                            $hs = $this->leyanioforecast[2]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10044:
                                        if(isset($this->leyanioforecast[3]->sumaproducto) && (isset($this->leyanioforecast[3]->suma) && $this->leyanioforecast[3]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[3]->sumaproducto;
                                            $hs = $this->leyanioforecast[3]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10045:
                                        $anio_forecast = $this->sumanioforecast[14];
                                    break;
                                    case 10046:
                                        $anio_forecast = $this->sumanioforecast[15];
                                    break;
                                    case 10047:
                                        $anio_forecast = $this->sumanioforecast[16];
                                    break;
                                    case 10048:
                                        $anio_forecast = $this->sumanioforecast[17];                            
                                        if(isset($anio_forecast->anio_forecast))
                                        {
                                            $a_forecast = $anio_forecast->anio_forecast;
                                            return number_format($a_forecast, 2, '.', ',');                                
                                        }
                                        else
                                        {
                                            return '-';
                                        }
                                    break;
                                    case 10049:
                                        $anio_forecast = $this->avganioforecast[15];
                                    break;
                                    case 10050:
                                        if(isset($this->leyanioforecast[4]->sumaproducto) && (isset($this->leyanioforecast[4]->suma) && $this->leyanioforecast[4]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[4]->sumaproducto;
                                            $hs = $this->leyanioforecast[4]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10051:
                                        if(isset($this->leyanioforecast[5]->sumaproducto) && (isset($this->leyanioforecast[5]->suma) && $this->leyanioforecast[5]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[5]->sumaproducto;
                                            $hs = $this->leyanioforecast[5]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10052:
                                        $anio_forecast = $this->sumanioforecast[18];
                                    break;
                                    case 10053:
                                        $anio_forecast = $this->sumanioforecast[19];
                                    break;
                                    case 10054:
                                        if(isset($this->leyanioforecast[6]->sumaproducto) && (isset($this->leyanioforecast[6]->suma) && $this->leyanioforecast[6]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[6]->sumaproducto;
                                            $hs = $this->leyanioforecast[6]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10055:
                                        if(isset($this->leyanioforecast[7]->sumaproducto) && (isset($this->leyanioforecast[7]->suma) && $this->leyanioforecast[7]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[7]->sumaproducto;
                                            $hs = $this->leyanioforecast[7]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10056:
                                        if(isset($this->leyanioforecast[8]->sumaproducto) && (isset($this->leyanioforecast[8]->suma) && $this->leyanioforecast[8]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[8]->sumaproducto;
                                            $hs = $this->leyanioforecast[8]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10057:
                                        if(isset($this->leyanioforecast[9]->sumaproducto) && (isset($this->leyanioforecast[9]->suma) && $this->leyanioforecast[9]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[9]->sumaproducto;
                                            $hs = $this->leyanioforecast[9]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10058:
                                        if(isset($this->leyanioforecast[10]->sumaproducto) && (isset($this->leyanioforecast[10]->suma) && $this->leyanioforecast[10]->suma != 0))
                                        {
                                            $min = $this->leyanioforecast[10]->sumaproducto;
                                            $hs = $this->leyanioforecast[10]->suma;
                                        }   
                                        else
                                        {
                                            $min = 0;
                                        } 
                                    break;
                                    case 10059:
                                        $anio_forecast = $this->sumanioforecast[20];
                                    break;
                                    case 10060:
                                        $anio_forecast = $this->sumanioforecast[21];
                                    break;
                                    case 10061:
                                        $anio_forecast = $this->sumanioforecast[22];
                                    break;
                                    case 10062:
                                        $anio_forecast = $this->sumanioforecast[23];
                                    break;
                                    case 10063:
                                        $anio_forecast = $this->sumanioforecast[24];
                                    break;
                                    case 10064:
                                        $anio_forecast = $this->sumanioforecast[25];
                                    break;
                                    case 10065:
                                        $anio_forecast = $this->sumanioforecast[26];
                                    break;
                                    case 10066:
                                        $anio_forecast = 0;
                                    break;
                                    case 10067:
                                        $anio_forecast = $this->sumanioforecast[27];
                                    break;
                                    case 10068:
                                        $anio_forecast = $this->sumanioforecast[28];
                                    break;
                                    case 10069:
                                        $anio_forecast = $this->sumanioforecast[29];
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
                                if (in_array($data->variable_id, $this->div))
                                {
                                    if ($min > 0)
                                    {
                                        $a_forecast = $min/$hs;
                                        if($a_forecast > 100)
                                        {
                                            return number_format(round($a_forecast), 0, '.', ',');
                                        }
                                        else
                                        {
                                            return number_format($a_forecast, 2, '.', ',');
                                        }
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                }
                                if(isset($anio_forecast->anio_forecast))
                                {
                                    $a_forecast = $anio_forecast->anio_forecast;
                                    if($a_forecast > 100 || in_array($data->variable_id, $this->percentage))
                                    {
                                        return number_format(round($a_forecast), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($a_forecast, 2, '.', ',');
                                    }
                                }
                                else
                                {
                                    return '-';
                                }
                            })
                            ->rawColumns(['categoria','subcategoria','dia_real','dia_budget','dia_forecast','mes_real','mes_budget','mes_forecast','trimestre_real','trimestre_budget','trimestre_forecast','anio_real','anio_budget','anio_forecast'])
                            ->make(true);
                    }
                    else
                    {
                        $table = DB::table('data')
                            ->join('variable','data.variable_id','=','variable.id')
                            ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                            ->join('categoria','subcategoria.categoria_id','=','categoria.id')
                            ->leftJoin('forecast', function($q) {
                                $q->on('data.variable_id', '=', 'forecast.variable_id')
                                ->on('data.fecha', '=', 'forecast.fecha');
                            })
                            ->leftJoin('budget', function($q) {
                                $q->on('data.variable_id', '=', 'budget.variable_id')
                                ->on('data.fecha', '=', 'budget.fecha');
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
                                'variable.export as var_export',
                                'variable.tipo as tipo',
                                'data.valor as dia_real',
                                'forecast.valor as dia_forecast',
                                'budget.valor as dia_budget'
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        where variable_id = 10010
                                        AND valor <> 0) as B
                                        ON A.fecha = B.fecha
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        where variable_id = 10035
                                        AND valor <> 0) as B
                                        ON A.fecha = B.fecha
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;  
                                case 10048:
                                    if(isset($data->dia_real)) 
                                    { 
                                        $d_real = $data->dia_real;
                                        return number_format($d_real, 2, '.', ',');                                
                                    }        
                                    else
                                    {
                                        return '-';
                                    } 
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        WHERE  DATEPART(y, A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;
                                default:                        
                                    if(isset($data->dia_real)) 
                                    {                                 
                                        $d_real = $data->dia_real;
                                        if($d_real > 100 || in_array($data->variable_id, $this->percentage))
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
                                if($d_real > 100  || in_array($data->variable_id, $this->percentage))
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
                        ///MODIFICADO 14/10/2024
                        ->addColumn('dia_budget', function($data)
                        {
                            if(isset($data->dia_budget)) 
                            {                                 
                                $d_forecast = $data->dia_budget;
                                if($d_forecast > 100 || in_array($data->variable_id, $this->percentage))
                                {
                                    return number_format(round($d_forecast), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($d_forecast, 2, '.', ',');
                                }                                
                            }        
                            else
                            {
                                return '-';
                            }          
                        }) 
                        ->addColumn('dia_forecast', function($data)
                        {
                            if(isset($data->dia_forecast)) 
                            {                                 
                                $d_forecast = $data->dia_forecast;
                                if($d_forecast > 100 || in_array($data->variable_id, $this->percentage))
                                {
                                    return number_format(round($d_forecast), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($d_forecast, 2, '.', ',');
                                }                                
                            }        
                            else
                            {
                                return '-';
                            }          
                        })
                        //MODIFICADO 04/09/2024 
                        ->addColumn('mes_real', function($data)
                        {
                           switch($data->variable_id)
                            {
                                case 10002:
                                    $mes_real = $this->summesreal[0];
                                break;
                                case 10003:
                                    $mes_real = $this->avgmesreal[0];
                                break;
                                case 10004:
                                    if(isset($this->summesreal[0]->mes_real) && isset($this->summesreal[1]->mes_real))
                                    {
                                        $au = $this->summesreal[0]->mes_real;
                                        $min = $this->summesreal[1]->mes_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10005:
                                    $mes_real = $this->summesreal[1];
                                break;
                                case 10006:
                                    if(isset($this->summesreal[1]->mes_real) && (isset($this->summesreal[23]->mes_real) && $this->summesreal[23]->mes_real != 0))
                                    {
                                        $min = $this->summesreal[1]->mes_real;
                                        $hs = $this->summesreal[23]->mes_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10007:
                                    $mes_real = $this->avgmesreal[1];
                                break;
                                case 10008:
                                    $mes_real = $this->summesreal[2];
                                break;
                                case 10009:
                                    $mes_real = $this->avgmesreal[2];
                                break;
                                case 10010:
                                    if(isset($this->summesreal[3]->mes_real) && isset($this->summesreal[2]->mes_real))
                                    {
                                        $au = $this->summesreal[2]->mes_real;
                                        $min = $this->summesreal[3]->mes_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10011:
                                    $mes_real = $this->summesreal[3];
                                break;
                                case 10012:
                                    $mes_real = $this->avgmesreal[3];
                                break;
                                case 10013:
                                    if(isset($this->summesreal[3]->mes_real) && (isset($this->summesreal[24]->mes_real) && $this->summesreal[24]->mes_real != 0))
                                    {
                                        $min = $this->summesreal[3]->mes_real;
                                        $hs = $this->summesreal[24]->mes_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10014:
                                    $mes_real = $this->avgmesreal[4];
                                break;
                                case 10015:
                                    $mes_real = $this->avgmesreal[5];
                                break;
                                case 10016:
                                    $mes_real = 0;
                                break;
                                case 10017:
                                    $mes_real = $this->avgmesreal[6];
                                break;
                                case 10018:
                                    $mes_real = $this->avgmesreal[7];
                                break;
                                case 10019:
                                    $mes_real = $this->summesreal[4];
                                break;
                                case 10020:
                                    if(isset($this->summesreal[4]->mes_real) && (isset($this->summesreal[25]->mes_real) && $this->summesreal[25]->mes_real != 0))
                                    {
                                        $min = $this->summesreal[4]->mes_real;
                                        $hs = $this->summesreal[25]->mes_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10021:
                                    $mes_real = $this->avgmesreal[8];
                                break;
                                case 10022:
                                    $mes_real = $this->summesreal[5];
                                break;
                                case 10023:
                                    $mes_real = $this->summesreal[6];
                                break;
                                case 10024:
                                    if(isset($this->summesreal[7]->mes_real) && isset($this->summesreal[6]->mes_real))
                                    {
                                        $au = $this->summesreal[6]->mes_real;
                                        $min = $this->summesreal[7]->mes_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10025:
                                    $mes_real = $this->summesreal[7];
                                break;
                                case 10026:
                                    $mes_real = $this->avgmesreal[9];
                                break;
                                case 10027:
                                    $mes_real = $this->summesreal[8];
                                break;
                                case 10028:
                                    $mes_real = $this->summesreal[9];
                                break;
                                case 10029:
                                    $mes_real = $this->avgmesreal[10];
                                break;
                                case 10030:
                                    if(isset($this->summesreal[10]->mes_real) && isset($this->summesreal[8]->mes_real))
                                    {
                                        $au = $this->summesreal[8]->mes_real;
                                        $min = $this->summesreal[10]->mes_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10031:
                                    $mes_real = $this->summesreal[10];
                                break;
                                case 10032:
                                    if(isset($this->summesreal[10]->mes_real) && (isset($this->summesreal[26]->mes_real) && $this->summesreal[26]->mes_real != 0))
                                    {
                                        $min = $this->summesreal[10]->mes_real;
                                        $hs = $this->summesreal[26]->mes_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10033:
                                    $mes_real = $this->avgmesreal[11];
                                break;
                                case 10034:
                                    $mes_real = $this->avgmesreal[12];
                                break;
                                case 10035:
                                    if(isset($this->summesreal[13]->mes_real) && isset($this->summesreal[11]->mes_real))
                                    {
                                        $au = $this->summesreal[11]->mes_real;
                                        $min = $this->summesreal[13]->mes_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10036:
                                    $mes_real = $this->avgmesreal[13];
                                break;
                                case 10037:
                                    $mes_real = $this->summesreal[11];
                                break;
                                case 10038:
                                    $mes_real = $this->summesreal[12];
                                break;
                                case 10039:
                                    $mes_real = $this->summesreal[13];
                                break;
                                case 10040:
                                    $mes_real = $this->avgmesreal[14];
                                break;
                                case 10041:
                                    if(isset($this->leymesreal[0]->sumaproducto) && (isset($this->leymesreal[0]->suma) && $this->leymesreal[0]->suma != 0))
                                    {
                                        $min = $this->leymesreal[0]->sumaproducto;
                                        $hs = $this->leymesreal[0]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10042:
                                    if(isset($this->leymesreal[1]->sumaproducto) && (isset($this->leymesreal[1]->suma) && $this->leymesreal[1]->suma != 0))
                                    {
                                        $min = $this->leymesreal[1]->sumaproducto;
                                        $hs = $this->leymesreal[1]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10043:
                                    if(isset($this->leymesreal[2]->sumaproducto) && (isset($this->leymesreal[2]->suma) && $this->leymesreal[2]->suma != 0))
                                    {
                                        $min = $this->leymesreal[2]->sumaproducto;
                                        $hs = $this->leymesreal[2]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10044:
                                    if(isset($this->leymesreal[3]->sumaproducto) && (isset($this->leymesreal[3]->suma) && $this->leymesreal[3]->suma != 0))
                                    {
                                        $min = $this->leymesreal[3]->sumaproducto;
                                        $hs = $this->leymesreal[3]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10045:
                                    $mes_real = $this->summesreal[14];
                                break;
                                case 10046:
                                    $mes_real = $this->summesreal[15];
                                break;
                                case 10047:
                                    $mes_real = $this->summesreal[16];
                                break;
                                case 10048:
                                    $mes_real = $this->summesreal[17];                            
                                    if(isset($mes_real->mes_real))
                                    {
                                        $m_budget = $mes_real->mes_real;
                                        return number_format($m_budget, 2, '.', ',');                                
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10049:
                                    $mes_real = $this->avgmesreal[15];
                                break;
                                case 10050:
                                    if(isset($this->leymesreal[4]->sumaproducto) && (isset($this->leymesreal[4]->suma) && $this->leymesreal[4]->suma != 0))
                                    {
                                        $min = $this->leymesreal[4]->sumaproducto;
                                        $hs = $this->leymesreal[4]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10051:
                                    if(isset($this->leymesreal[5]->sumaproducto) && (isset($this->leymesreal[5]->suma) && $this->leymesreal[5]->suma != 0))
                                    {
                                        $min = $this->leymesreal[5]->sumaproducto;
                                        $hs = $this->leymesreal[5]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10052:
                                    $mes_real = $this->summesreal[18];
                                break;
                                case 10053:
                                    $mes_real = $this->summesreal[19];
                                break;
                                case 10054:
                                    if(isset($this->leymesreal[6]->sumaproducto) && (isset($this->leymesreal[6]->suma) && $this->leymesreal[6]->suma != 0))
                                    {
                                        $min = $this->leymesreal[6]->sumaproducto;
                                        $hs = $this->leymesreal[6]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10055:
                                    if(isset($this->leymesreal[7]->sumaproducto) && (isset($this->leymesreal[7]->suma) && $this->leymesreal[7]->suma != 0))
                                    {
                                        $min = $this->leymesreal[7]->sumaproducto;
                                        $hs = $this->leymesreal[7]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10056:
                                    if(isset($this->leymesreal[8]->sumaproducto) && (isset($this->leymesreal[8]->suma) && $this->leymesreal[8]->suma != 0))
                                    {
                                        $min = $this->leymesreal[8]->sumaproducto;
                                        $hs = $this->leymesreal[8]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10057:
                                    if(isset($this->leymesreal[9]->sumaproducto) && (isset($this->leymesreal[9]->suma) && $this->leymesreal[9]->suma != 0))
                                    {
                                        $min = $this->leymesreal[9]->sumaproducto;
                                        $hs = $this->leymesreal[9]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10058:
                                    if(isset($this->leymesreal[10]->sumaproducto) && (isset($this->leymesreal[10]->suma) && $this->leymesreal[10]->suma != 0))
                                    {
                                        $min = $this->leymesreal[10]->sumaproducto;
                                        $hs = $this->leymesreal[10]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10059:
                                    $mes_real = $this->summesreal[20];
                                break;
                                case 10060:
                                    $mes_real = $this->summesreal[21];
                                break;
                                case 10061:
                                    $mes_real = $this->summesreal[22];
                                break;
                                case 10062:
                                    $mes_real = $this->summesreal[23];
                                break;
                                case 10063:
                                    $mes_real = $this->summesreal[24];
                                break;
                                case 10064:
                                    $mes_real = $this->summesreal[25];
                                break;
                                case 10065:
                                    $mes_real = $this->summesreal[26];
                                break;
                                case 10066:
                                    $mes_real = 0;
                                break;
                                case 10067:
                                    $mes_real = $this->summesreal[27];
                                break;
                                case 10068:
                                    $mes_real = $this->summesreal[28];
                                break;
                                case 10069:
                                    $mes_real = $this->summesreal[29];
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
                            if (in_array($data->variable_id, $this->div))
                            {
                                if ($min > 0)
                                {
                                    $m_real = $min/$hs;
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
                            if(isset($mes_real->mes_real))
                            {
                                $m_real = $mes_real->mes_real;
                                if($m_real > 100 || in_array($data->variable_id, $this->percentage))
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
                        //VARIFICADO NO UTILIZA NINGUNA SUBCONSULTA
                        ->addColumn('mes_budget', function($data)
                        {
                            switch($data->variable_id)
                            {
                                case 10002:
                                    $mes_budget = $this->summesbudget[0];
                                break;
                                case 10003:
                                    $mes_budget = $this->avgmesbudget[0];
                                break;
                                case 10004:
                                    if(isset($this->summesbudget[0]->mes_budget) && isset($this->summesbudget[1]->mes_budget))
                                    {
                                        $au = $this->summesbudget[0]->mes_budget;
                                        $min = $this->summesbudget[1]->mes_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10005:
                                    $mes_budget = $this->summesbudget[1];
                                break;
                                case 10006:
                                    if(isset($this->summesbudget[1]->mes_budget) && (isset($this->summesbudget[23]->mes_budget) && $this->summesbudget[23]->mes_budget != 0))
                                    {
                                        $min = $this->summesbudget[1]->mes_budget;
                                        $hs = $this->summesbudget[23]->mes_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10007:
                                    $mes_budget = $this->avgmesbudget[1];
                                break;
                                case 10008:
                                    $mes_budget = $this->summesbudget[2];
                                break;
                                case 10009:
                                    $mes_budget = $this->avgmesbudget[2];
                                break;
                                case 10010:
                                    if(isset($this->summesbudget[3]->mes_budget) && isset($this->summesbudget[2]->mes_budget))
                                    {
                                        $au = $this->summesbudget[2]->mes_budget;
                                        $min = $this->summesbudget[3]->mes_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10011:
                                    $mes_budget = $this->summesbudget[3];
                                break;
                                case 10012:
                                    $mes_budget = $this->avgmesbudget[3];
                                break;
                                case 10013:
                                    if(isset($this->summesbudget[3]->mes_budget) && (isset($this->summesbudget[24]->mes_budget) && $this->summesbudget[24]->mes_budget != 0))
                                    {
                                        $min = $this->summesbudget[3]->mes_budget;
                                        $hs = $this->summesbudget[24]->mes_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10014:
                                    $mes_budget = $this->avgmesbudget[4];
                                break;
                                case 10015:
                                    $mes_budget = $this->avgmesbudget[5];
                                break;
                                case 10016:
                                    $mes_budget = 0;
                                break;
                                case 10017:
                                    $mes_budget = $this->avgmesbudget[6];
                                break;
                                case 10018:
                                    $mes_budget = $this->avgmesbudget[7];
                                break;
                                case 10019:
                                    $mes_budget = $this->summesbudget[4];
                                break;
                                case 10020:
                                    if(isset($this->summesbudget[4]->mes_budget) && (isset($this->summesbudget[25]->mes_budget) && $this->summesbudget[25]->mes_budget != 0))
                                    {
                                        $min = $this->summesbudget[4]->mes_budget;
                                        $hs = $this->summesbudget[25]->mes_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10021:
                                    $mes_budget = $this->avgmesbudget[8];
                                break;
                                case 10022:
                                    $mes_budget = $this->summesbudget[5];
                                break;
                                case 10023:
                                    $mes_budget = $this->summesbudget[6];
                                break;
                                case 10024:
                                    if(isset($this->summesbudget[7]->mes_budget) && isset($this->summesbudget[6]->mes_budget))
                                    {
                                        $au = $this->summesbudget[6]->mes_budget;
                                        $min = $this->summesbudget[7]->mes_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10025:
                                    $mes_budget = $this->summesbudget[7];
                                break;
                                case 10026:
                                    $mes_budget = $this->avgmesbudget[9];
                                break;
                                case 10027:
                                    $mes_budget = $this->summesbudget[8];
                                break;
                                case 10028:
                                    $mes_budget = $this->summesbudget[9];
                                break;
                                case 10029:
                                    $mes_budget = $this->avgmesbudget[10];
                                break;
                                case 10030:
                                    if(isset($this->summesbudget[10]->mes_budget) && isset($this->summesbudget[8]->mes_budget))
                                    {
                                        $au = $this->summesbudget[8]->mes_budget;
                                        $min = $this->summesbudget[10]->mes_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10031:
                                    $mes_budget = $this->summesbudget[10];
                                break;
                                case 10032:
                                    if(isset($this->summesbudget[10]->mes_budget) && (isset($this->summesbudget[26]->mes_budget) && $this->summesbudget[26]->mes_budget != 0))
                                    {
                                        $min = $this->summesbudget[10]->mes_budget;
                                        $hs = $this->summesbudget[26]->mes_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10033:
                                    $mes_budget = $this->avgmesbudget[11];
                                break;
                                case 10034:
                                    $mes_budget = $this->avgmesbudget[12];
                                break;
                                case 10035:
                                    if(isset($this->summesbudget[13]->mes_budget) && isset($this->summesbudget[11]->mes_budget))
                                    {
                                        $au = $this->summesbudget[11]->mes_budget;
                                        $min = $this->summesbudget[13]->mes_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10036:
                                    $mes_budget = $this->avgmesbudget[13];
                                break;
                                case 10037:
                                    $mes_budget = $this->summesbudget[11];
                                break;
                                case 10038:
                                    $mes_budget = $this->summesbudget[12];
                                break;
                                case 10039:
                                    $mes_budget = $this->summesbudget[13];
                                break;
                                case 10040:
                                    $mes_budget = $this->avgmesbudget[14];
                                break;
                                case 10041:
                                    if(isset($this->leymesbudget[0]->sumaproducto) && (isset($this->leymesbudget[0]->suma) && $this->leymesbudget[0]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[0]->sumaproducto;
                                        $hs = $this->leymesbudget[0]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10042:
                                    if(isset($this->leymesbudget[1]->sumaproducto) && (isset($this->leymesbudget[1]->suma) && $this->leymesbudget[1]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[1]->sumaproducto;
                                        $hs = $this->leymesbudget[1]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10043:
                                    if(isset($this->leymesbudget[2]->sumaproducto) && (isset($this->leymesbudget[2]->suma) && $this->leymesbudget[2]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[2]->sumaproducto;
                                        $hs = $this->leymesbudget[2]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10044:
                                    if(isset($this->leymesbudget[3]->sumaproducto) && (isset($this->leymesbudget[3]->suma) && $this->leymesbudget[3]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[3]->sumaproducto;
                                        $hs = $this->leymesbudget[3]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10045:
                                    $mes_budget = $this->summesbudget[14];
                                break;
                                case 10046:
                                    $mes_budget = $this->summesbudget[15];
                                break;
                                case 10047:
                                    $mes_budget = $this->summesbudget[16];
                                break;
                                case 10048:
                                    $mes_budget = $this->summesbudget[17];                            
                                    if(isset($mes_budget->mes_budget))
                                    {
                                        $m_budget = $mes_budget->mes_budget;
                                        return number_format($m_budget, 2, '.', ',');                                
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10049:
                                    $mes_budget = $this->avgmesbudget[15];
                                break;
                                case 10050:
                                    if(isset($this->leymesbudget[4]->sumaproducto) && (isset($this->leymesbudget[4]->suma) && $this->leymesbudget[4]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[4]->sumaproducto;
                                        $hs = $this->leymesbudget[4]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10051:
                                    if(isset($this->leymesbudget[5]->sumaproducto) && (isset($this->leymesbudget[5]->suma) && $this->leymesbudget[5]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[5]->sumaproducto;
                                        $hs = $this->leymesbudget[5]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10052:
                                    $mes_budget = $this->summesbudget[18];
                                break;
                                case 10053:
                                    $mes_budget = $this->summesbudget[19];
                                break;
                                case 10054:
                                    if(isset($this->leymesbudget[6]->sumaproducto) && (isset($this->leymesbudget[6]->suma) && $this->leymesbudget[6]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[6]->sumaproducto;
                                        $hs = $this->leymesbudget[6]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10055:
                                    if(isset($this->leymesbudget[7]->sumaproducto) && (isset($this->leymesbudget[7]->suma) && $this->leymesbudget[7]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[7]->sumaproducto;
                                        $hs = $this->leymesbudget[7]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10056:
                                    if(isset($this->leymesbudget[8]->sumaproducto) && (isset($this->leymesbudget[8]->suma) && $this->leymesbudget[8]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[8]->sumaproducto;
                                        $hs = $this->leymesbudget[8]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10057:
                                    if(isset($this->leymesbudget[9]->sumaproducto) && (isset($this->leymesbudget[9]->suma) && $this->leymesbudget[9]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[9]->sumaproducto;
                                        $hs = $this->leymesbudget[9]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10058:
                                    if(isset($this->leymesbudget[10]->sumaproducto) && (isset($this->leymesbudget[10]->suma) && $this->leymesbudget[10]->suma != 0))
                                    {
                                        $min = $this->leymesbudget[10]->sumaproducto;
                                        $hs = $this->leymesbudget[10]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10059:
                                    $mes_budget = $this->summesbudget[20];
                                break;
                                case 10060:
                                    $mes_budget = $this->summesbudget[21];
                                break;
                                case 10061:
                                    $mes_budget = $this->summesbudget[22];
                                break;
                                case 10062:
                                    $mes_budget = $this->summesbudget[23];
                                break;
                                case 10063:
                                    $mes_budget = $this->summesbudget[24];
                                break;
                                case 10064:
                                    $mes_budget = $this->summesbudget[25];
                                break;
                                case 10065:
                                    $mes_budget = $this->summesbudget[26];
                                break;
                                case 10066:
                                    $mes_budget = 0;
                                break;
                                case 10067:
                                    $mes_budget = $this->summesbudget[27];
                                break;
                                case 10068:
                                    $mes_budget = $this->summesbudget[28];
                                break;
                                case 10069:
                                    $mes_budget = $this->summesbudget[29];
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
                            if (in_array($data->variable_id, $this->div))
                            {
                                if ($min > 0)
                                {
                                    $m_budget = $min/$hs;
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
                            if(isset($mes_budget->mes_budget))
                            {
                                $m_budget = $mes_budget->mes_budget;
                                if($m_budget > 100 || in_array($data->variable_id, $this->percentage))
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
                        //MODIFICADO 05/09/2024
                        ->addColumn('mes_forecast', function($data)
                        {     
                            switch($data->variable_id)
                            {
                                case 10002:
                                    $mes_forecast = $this->summesforecast[0];
                                break;
                                case 10003:
                                    $mes_forecast = $this->avgmesforecast[0];
                                break;
                                case 10004:
                                    if(isset($this->summesforecast[0]->mes_forecast) && isset($this->summesforecast[1]->mes_forecast))
                                    {
                                        $au = $this->summesforecast[0]->mes_forecast;
                                        $min = $this->summesforecast[1]->mes_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10005:
                                    $mes_forecast = $this->summesforecast[1];
                                break;
                                case 10006:
                                    if(isset($this->summesforecast[1]->mes_forecast) && (isset($this->summesforecast[23]->mes_forecast) && $this->summesforecast[23]->mes_forecast != 0))
                                    {
                                        $min = $this->summesforecast[1]->mes_forecast;
                                        $hs = $this->summesforecast[23]->mes_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10007:
                                    $mes_forecast = $this->avgmesforecast[1];
                                break;
                                case 10008:
                                    $mes_forecast = $this->summesforecast[2];
                                break;
                                case 10009:
                                    $mes_forecast = $this->avgmesforecast[2];
                                break;
                                case 10010:
                                    if(isset($this->summesforecast[3]->mes_forecast) && isset($this->summesforecast[2]->mes_forecast))
                                    {
                                        $au = $this->summesforecast[2]->mes_forecast;
                                        $min = $this->summesforecast[3]->mes_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10011:
                                    $mes_forecast = $this->summesforecast[3];
                                break;
                                case 10012:
                                    $mes_forecast = $this->avgmesforecast[3];
                                break;
                                case 10013:
                                    if(isset($this->summesforecast[3]->mes_forecast) && (isset($this->summesforecast[24]->mes_forecast) && $this->summesforecast[24]->mes_forecast != 0))
                                    {
                                        $min = $this->summesforecast[3]->mes_forecast;
                                        $hs = $this->summesforecast[24]->mes_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10014:
                                    $mes_forecast = $this->avgmesforecast[4];
                                break;
                                case 10015:
                                    $mes_forecast = $this->avgmesforecast[5];
                                break;
                                case 10016:
                                    $mes_forecast = 0;
                                break;
                                case 10017:
                                    $mes_forecast = $this->avgmesforecast[6];
                                break;
                                case 10018:
                                    $mes_forecast = $this->avgmesforecast[7];
                                break;
                                case 10019:
                                    $mes_forecast = $this->summesforecast[4];
                                break;
                                case 10020:
                                    if(isset($this->summesforecast[4]->mes_forecast) && (isset($this->summesforecast[25]->mes_forecast) && $this->summesforecast[25]->mes_forecast != 0))
                                    {
                                        $min = $this->summesforecast[4]->mes_forecast;
                                        $hs = $this->summesforecast[25]->mes_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10021:
                                    $mes_forecast = $this->avgmesforecast[8];
                                break;
                                case 10022:
                                    $mes_forecast = $this->summesforecast[5];
                                break;
                                case 10023:
                                    $mes_forecast = $this->summesforecast[6];
                                break;
                                case 10024:
                                    if(isset($this->summesforecast[7]->mes_forecast) && isset($this->summesforecast[6]->mes_forecast))
                                    {
                                        $au = $this->summesforecast[6]->mes_forecast;
                                        $min = $this->summesforecast[7]->mes_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10025:
                                    $mes_forecast = $this->summesforecast[7];
                                break;
                                case 10026:
                                    $mes_forecast = $this->avgmesforecast[9];
                                break;
                                case 10027:
                                    $mes_forecast = $this->summesforecast[8];
                                break;
                                case 10028:
                                    //$mes_forecast = $this->summesforecast[9];
                                    //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                            //SUMAMENSUAL((((10033 MMSA_APILAM_STACKER_Recuperación %)* 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                               
                                            
                                                                                
                                            //10030 MMSA_APILAM_STACKER_Ley Au (g/t) 
                                            //Promedio Ponderado Mensual(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t, 10030 MMSA_APILAM_STACKER_Ley Au (g/t))                          
                                            $sumaproducto10030 = DB::select(
                                                'SELECT A.variable_id as var,SUM(A.valor * B.valor) as sumaproducto FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10030) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10031) as B
                                                ON A.fecha = B.fecha
                                                WHERE A.fecha between ? and ?
                                                GROUP BY A.variable_id',  
                                                [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                                            );        

                                            //10033 MMSA_APILAM_STACKER_Recuperación %
                                            //Promedio Ponderado Mensual(10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t, 10033 MMSA_APILAM_STACKER_Recuperación %)                    
                                            $sumaproducto10033 = DB::select(
                                                'SELECT A.variable_id as var,SUM(A.valor * B.valor) as sumaproducto FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10033) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10031) as B
                                                ON A.fecha = B.fecha
                                                WHERE A.fecha between ? and ?
                                                GROUP BY A.variable_id',  
                                                [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]);                                     
                                            $suma10031 = $this->summesforecast10031; 
                                    

                                            if(isset($sumaproducto10030[0]->sumaproducto) && isset($sumaproducto10033[0]->sumaproducto) && isset($suma10031[0]->suma))
                                            {
                                                if ($suma10031[0]->suma > 0) {
                                                    //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                    $recup =  $sumaproducto10033[0]->sumaproducto/$suma10031[0]->suma;
                                                    $leyAu = $sumaproducto10030[0]->sumaproducto/$suma10031[0]->suma;
                                                    $sumMin = $suma10031[0]->suma;
                                                    $mes_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                    if($mes_forecast > 100)
                                                    {
                                                        return number_format(round($mes_forecast), 0, '.', ',');
                                                    }
                                                    else
                                                    {
                                                        return number_format($mes_forecast, 2, '.', ',');
                                                    }
                                                }
                                                else {
                                                    return '-';
                                                }
                                            }
                                            else
                                            {
                                                return '-';
                                            } 
                                break;
                                case 10029:
                                    $mes_forecast = $this->avgmesforecast[10];
                                break;
                                case 10030:
                                    if(isset($this->summesforecast[10]->mes_forecast) && isset($this->summesforecast[8]->mes_forecast))
                                    {
                                        $au = $this->summesforecast[8]->mes_forecast;
                                        $min = $this->summesforecast[10]->mes_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10031:
                                    $mes_forecast = $this->summesforecast[10];
                                break;
                                case 10032:
                                    if(isset($this->summesforecast[10]->mes_forecast) && (isset($this->summesforecast[26]->mes_forecast) && $this->summesforecast[26]->mes_forecast != 0))
                                    {
                                        $min = $this->summesforecast[10]->mes_forecast;
                                        $hs = $this->summesforecast[26]->mes_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10033:
                                    $mes_forecast = $this->avgmesforecast[11];
                                break;
                                case 10034:
                                    $mes_forecast = $this->avgmesforecast[12];
                                break;
                                case 10035:
                                    if(isset($this->summesforecast[13]->mes_forecast) && isset($this->summesforecast[11]->mes_forecast))
                                    {
                                        $au = $this->summesforecast[11]->mes_forecast;
                                        $min = $this->summesforecast[13]->mes_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10036:
                                    $mes_forecast = $this->avgmesforecast[13];
                                break;
                                case 10037:
                                    $mes_forecast = $this->summesforecast[11];
                                break;
                                case 10038:
                                    //$mes_forecast = $this->summesforecast[12];
                                    //$mes_forecast = $this->summesforecast[12];
                                    //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                            //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)                                     
                                            
                                                                                
                                            //10035 MMSA_APILAM_TA_Ley Au g/t
                                            //Promedio Ponderado Mensual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                            $sumaproducto10035 = DB::select(
                                                'SELECT A.variable_id as var,SUM(A.valor * B.valor) as sumaproducto FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10035) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10039) as B
                                                ON A.fecha = B.fecha
                                                WHERE A.fecha between ? and ?
                                                GROUP BY A.variable_id',  
                                                [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                                            );  
                                            
                                            //10036 MMSA_APILAM_TA_Recuperación %
                                            //Promedio Ponderado Mensual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                            $sumaproducto10036 = DB::select(
                                            'SELECT A.variable_id as var,SUM(A.valor * B.valor) as sumaproducto FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10036) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10039) as B
                                                ON A.fecha = B.fecha
                                                WHERE A.fecha between ? and ?
                                                GROUP BY A.variable_id',  
                                                [date('Y-m-d',strtotime($this->fecha_ini)),date('Y-m-d',strtotime($this->date))]
                                            );     

                                            $suma10039= $this->summesforecast10039; 
                                            
                                            if(isset($sumaproducto10035[0]->sumaproducto) && isset($sumaproducto10036[0]->sumaproducto) && isset($suma10039[0]->suma))
                                            {
                                                if ($suma10039[0]->suma > 0) {
                                                    //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                    $recup =  $sumaproducto10036[0]->sumaproducto/$suma10039[0]->suma;
                                                    $leyAu = $sumaproducto10035[0]->sumaproducto/$suma10039[0]->suma;
                                                    $sumMin = $suma10039[0]->suma;
                                                    $mes_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                    if($mes_forecast > 100)
                                                    {
                                                        return number_format(round($mes_forecast), 0, '.', ',');
                                                    }
                                                    else
                                                    {
                                                        return number_format($mes_forecast, 2, '.', ',');
                                                    }
                                                }
                                                else {
                                                    return '-';
                                                }
                                            }
                                            else
                                            {
                                                return '-';
                                            }
                                break;
                                case 10039:
                                    $mes_forecast = $this->summesforecast[13];
                                break;
                                case 10040:
                                    $mes_forecast = $this->avgmesforecast[14];
                                break;
                                case 10041:
                                    if(isset($this->leymesforecast[0]->sumaproducto) && (isset($this->leymesforecast[0]->suma) && $this->leymesforecast[0]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[0]->sumaproducto;
                                        $hs = $this->leymesforecast[0]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10042:
                                    if(isset($this->leymesforecast[1]->sumaproducto) && (isset($this->leymesforecast[1]->suma) && $this->leymesforecast[1]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[1]->sumaproducto;
                                        $hs = $this->leymesforecast[1]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10043:
                                    if(isset($this->leymesforecast[2]->sumaproducto) && (isset($this->leymesforecast[2]->suma) && $this->leymesforecast[2]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[2]->sumaproducto;
                                        $hs = $this->leymesforecast[2]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10044:
                                    if(isset($this->leymesforecast[3]->sumaproducto) && (isset($this->leymesforecast[3]->suma) && $this->leymesforecast[3]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[3]->sumaproducto;
                                        $hs = $this->leymesforecast[3]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10045:
                                    $mes_forecast = $this->summesforecast[14];
                                break;
                                case 10046:
                                    $mes_forecast = $this->summesforecast[15];
                                break;
                                case 10047:
                                    $mes_forecast = $this->summesforecast[16];
                                break;
                                case 10048:
                                    $mes_forecast = $this->summesforecast[17];                            
                                    if(isset($mes_forecast->mes_forecast))
                                    {
                                        $m_forecast = $mes_forecast->mes_forecast;
                                        return number_format($m_forecast, 2, '.', ',');                                
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10049:
                                    $mes_forecast = $this->avgmesforecast[15];
                                break;
                                case 10050:
                                    if(isset($this->leymesforecast[4]->sumaproducto) && (isset($this->leymesforecast[4]->suma) && $this->leymesforecast[4]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[4]->sumaproducto;
                                        $hs = $this->leymesforecast[4]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10051:
                                    if(isset($this->leymesforecast[5]->sumaproducto) && (isset($this->leymesforecast[5]->suma) && $this->leymesforecast[5]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[5]->sumaproducto;
                                        $hs = $this->leymesforecast[5]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10052:
                                    $mes_forecast = $this->summesforecast[18];
                                break;
                                case 10053:
                                    $mes_forecast = $this->summesforecast[19];
                                break;
                                case 10054:
                                    if(isset($this->leymesforecast[6]->sumaproducto) && (isset($this->leymesforecast[6]->suma) && $this->leymesforecast[6]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[6]->sumaproducto;
                                        $hs = $this->leymesforecast[6]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10055:
                                    if(isset($this->leymesforecast[7]->sumaproducto) && (isset($this->leymesforecast[7]->suma) && $this->leymesforecast[7]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[7]->sumaproducto;
                                        $hs = $this->leymesforecast[7]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10056:
                                    if(isset($this->leymesforecast[8]->sumaproducto) && (isset($this->leymesforecast[8]->suma) && $this->leymesforecast[8]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[8]->sumaproducto;
                                        $hs = $this->leymesforecast[8]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10057:
                                    if(isset($this->leymesforecast[9]->sumaproducto) && (isset($this->leymesforecast[9]->suma) && $this->leymesforecast[9]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[9]->sumaproducto;
                                        $hs = $this->leymesforecast[9]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10058:
                                    if(isset($this->leymesforecast[10]->sumaproducto) && (isset($this->leymesforecast[10]->suma) && $this->leymesforecast[10]->suma != 0))
                                    {
                                        $min = $this->leymesforecast[10]->sumaproducto;
                                        $hs = $this->leymesforecast[10]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10059:
                                    $mes_forecast = $this->summesforecast[20];
                                break;
                                case 10060:
                                    $mes_forecast = $this->summesforecast[21];
                                break;
                                case 10061:
                                    $mes_forecast = $this->summesforecast[22];
                                break;
                                case 10062:
                                    $mes_forecast = $this->summesforecast[23];
                                break;
                                case 10063:
                                    $mes_forecast = $this->summesforecast[24];
                                break;
                                case 10064:
                                    $mes_forecast = $this->summesforecast[25];
                                break;
                                case 10065:
                                    $mes_forecast = $this->summesforecast[26];
                                break;
                                case 10066:
                                    $mes_forecast = 0;
                                break;
                                case 10067:
                                    $mes_forecast = $this->summesforecast[27];
                                break;
                                case 10068:
                                    $mes_forecast = $this->summesforecast[28];
                                break;
                                case 10069:
                                    $mes_forecast = $this->summesforecast[29];
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
                            if (in_array($data->variable_id, $this->div))
                            {
                                if ($min > 0)
                                {
                                    $m_forecast = $min/$hs;
                                    if($m_forecast > 100)
                                    {
                                        return number_format(round($m_forecast), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($m_forecast, 2, '.', ',');
                                    }
                                }
                                else
                                {
                                    return '-';
                                }
                            }

                            if(isset($mes_forecast->mes_forecast))
                            {
                                $m_forecast = $mes_forecast->mes_forecast;
                                if($m_forecast > 100 || in_array($data->variable_id, $this->percentage))
                                {
                                    return number_format(round($m_forecast), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($m_forecast, 2, '.', ',');
                                }
                            }
                            else
                            {
                                return '-';
                            }
                        })//MODIFICADO 06/09/2024
                        ->addColumn('trimestre_real', function($data)
                        {
                            switch($data->variable_id)
                            {
                                case 10002:
                                    $tri_real = $this->sumtrireal[0];
                                break;
                                case 10003:
                                    $tri_real = $this->avgtrireal[0];
                                break;
                                case 10004:
                                    if(isset($this->sumtrireal[0]->tri_real) && isset($this->sumtrireal[1]->tri_real))
                                    {
                                        $au = $this->sumtrireal[0]->tri_real;
                                        $min = $this->sumtrireal[1]->tri_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10005:
                                    $tri_real = $this->sumtrireal[1];
                                break;
                                case 10006:
                                    if(isset($this->sumtrireal[1]->tri_real) && (isset($this->sumtrireal[23]->tri_real) && $this->sumtrireal[23]->tri_real != 0))
                                    {
                                        $min = $this->sumtrireal[1]->tri_real;
                                        $hs = $this->sumtrireal[23]->tri_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10007:
                                    $tri_real = $this->avgtrireal[1];
                                break;
                                case 10008:
                                    $tri_real = $this->sumtrireal[2];
                                break;
                                case 10009:
                                    $tri_real = $this->avgtrireal[2];
                                break;
                                case 10010:
                                    if(isset($this->sumtrireal[3]->tri_real) && isset($this->sumtrireal[2]->tri_real))
                                    {
                                        $au = $this->sumtrireal[2]->tri_real;
                                        $min = $this->sumtrireal[3]->tri_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10011:
                                    $tri_real = $this->sumtrireal[3];
                                break;
                                case 10012:
                                    $tri_real = $this->avgtrireal[3];
                                break;
                                case 10013:
                                    if(isset($this->sumtrireal[3]->tri_real) && (isset($this->sumtrireal[24]->tri_real) && $this->sumtrireal[24]->tri_real != 0))
                                    {
                                        $min = $this->sumtrireal[3]->tri_real;
                                        $hs = $this->sumtrireal[24]->tri_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10014:
                                    $tri_real = $this->avgtrireal[4];
                                break;
                                case 10015:
                                    $tri_real = $this->avgtrireal[5];
                                break;
                                case 10016:
                                    $tri_real = 0;
                                break;
                                case 10017:
                                    $tri_real = $this->avgtrireal[6];
                                break;
                                case 10018:
                                    $tri_real = $this->avgtrireal[7];
                                break;
                                case 10019:
                                    $tri_real = $this->sumtrireal[4];
                                break;
                                case 10020:
                                    if(isset($this->sumtrireal[4]->tri_real) && (isset($this->sumtrireal[25]->tri_real) && $this->sumtrireal[25]->tri_real != 0))
                                    {
                                        $min = $this->sumtrireal[4]->tri_real;
                                        $hs = $this->sumtrireal[25]->tri_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10021:
                                    $tri_real = $this->avgtrireal[8];
                                break;
                                case 10022:
                                    $tri_real = $this->sumtrireal[5];
                                break;
                                case 10023:
                                    $tri_real = $this->sumtrireal[6];
                                break;
                                case 10024:
                                    if(isset($this->sumtrireal[7]->tri_real) && isset($this->sumtrireal[6]->tri_real))
                                    {
                                        $au = $this->sumtrireal[6]->tri_real;
                                        $min = $this->sumtrireal[7]->tri_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10025:
                                    $tri_real = $this->sumtrireal[7];
                                break;
                                case 10026:
                                    $tri_real = $this->avgtrireal[9];
                                break;
                                case 10027:
                                    $tri_real = $this->sumtrireal[8];
                                break;
                                case 10028:
                                    $tri_real = $this->sumtrireal[9];
                                break;
                                case 10029:
                                    $tri_real = $this->avgtrireal[10];
                                break;
                                case 10030:
                                    if(isset($this->sumtrireal[10]->tri_real) && isset($this->sumtrireal[8]->tri_real))
                                    {
                                        $au = $this->sumtrireal[8]->tri_real;
                                        $min = $this->sumtrireal[10]->tri_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10031:
                                    $tri_real = $this->sumtrireal[10];
                                break;
                                case 10032:
                                    if(isset($this->sumtrireal[10]->tri_real) && (isset($this->sumtrireal[26]->tri_real) && $this->sumtrireal[26]->tri_real != 0))
                                    {
                                        $min = $this->sumtrireal[10]->tri_real;
                                        $hs = $this->sumtrireal[26]->tri_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10033:
                                    $tri_real = $this->avgtrireal[11];
                                break;
                                case 10034:
                                    $tri_real = $this->avgtrireal[12];
                                break;
                                case 10035:
                                    if(isset($this->sumtrireal[13]->tri_real) && isset($this->sumtrireal[11]->tri_real))
                                    {
                                        $au = $this->sumtrireal[11]->tri_real;
                                        $min = $this->sumtrireal[13]->tri_real;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10036:
                                    $tri_real = $this->avgtrireal[13];
                                break;
                                case 10037:
                                    $tri_real = $this->sumtrireal[11];
                                break;
                                case 10038:
                                    $tri_real = $this->sumtrireal[12];
                                break;
                                case 10039:
                                    $tri_real = $this->sumtrireal[13];
                                break;
                                case 10040:
                                    $tri_real = $this->avgtrireal[14];
                                break;
                                case 10041:
                                    if(isset($this->leytrireal[0]->sumaproducto) && (isset($this->leytrireal[0]->suma) && $this->leytrireal[0]->suma != 0))
                                    {
                                        $min = $this->leytrireal[0]->sumaproducto;
                                        $hs = $this->leytrireal[0]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10042:
                                    if(isset($this->leytrireal[1]->sumaproducto) && (isset($this->leytrireal[1]->suma) && $this->leytrireal[1]->suma != 0))
                                    {
                                        $min = $this->leytrireal[1]->sumaproducto;
                                        $hs = $this->leytrireal[1]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10043:
                                    if(isset($this->leytrireal[2]->sumaproducto) && (isset($this->leytrireal[2]->suma) && $this->leytrireal[2]->suma != 0))
                                    {
                                        $min = $this->leytrireal[2]->sumaproducto;
                                        $hs = $this->leytrireal[2]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10044:
                                    if(isset($this->leytrireal[3]->sumaproducto) && (isset($this->leytrireal[3]->suma) && $this->leytrireal[3]->suma != 0))
                                    {
                                        $min = $this->leytrireal[3]->sumaproducto;
                                        $hs = $this->leytrireal[3]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10045:
                                    $tri_real = $this->sumtrireal[14];
                                break;
                                case 10046:
                                    $tri_real = $this->sumtrireal[15];
                                break;
                                case 10047:
                                    $tri_real = $this->sumtrireal[16];
                                break;
                                case 10048:
                                    $tri_real = $this->sumtrireal[17];                            
                                    if(isset($tri_real->tri_real))
                                    {
                                        $t_real = $tri_real->tri_real;
                                        return number_format($t_real, 2, '.', ',');                                
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10049:
                                    $tri_real = $this->avgtrireal[15];
                                break;
                                case 10050:
                                    if(isset($this->leytrireal[4]->sumaproducto) && (isset($this->leytrireal[4]->suma) && $this->leytrireal[4]->suma != 0))
                                    {
                                        $min = $this->leytrireal[4]->sumaproducto;
                                        $hs = $this->leytrireal[4]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10051:
                                    if(isset($this->leytrireal[5]->sumaproducto) && (isset($this->leytrireal[5]->suma) && $this->leytrireal[5]->suma != 0))
                                    {
                                        $min = $this->leytrireal[5]->sumaproducto;
                                        $hs = $this->leytrireal[5]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10052:
                                    $tri_real = $this->sumtrireal[18];
                                break;
                                case 10053:
                                    $tri_real = $this->sumtrireal[19];
                                break;
                                case 10054:
                                    if(isset($this->leytrireal[6]->sumaproducto) && (isset($this->leytrireal[6]->suma) && $this->leytrireal[6]->suma != 0))
                                    {
                                        $min = $this->leytrireal[6]->sumaproducto;
                                        $hs = $this->leytrireal[6]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10055:
                                    if(isset($this->leytrireal[7]->sumaproducto) && (isset($this->leytrireal[7]->suma) && $this->leytrireal[7]->suma != 0))
                                    {
                                        $min = $this->leytrireal[7]->sumaproducto;
                                        $hs = $this->leytrireal[7]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10056:
                                    if(isset($this->leytrireal[8]->sumaproducto) && (isset($this->leytrireal[8]->suma) && $this->leytrireal[8]->suma != 0))
                                    {
                                        $min = $this->leytrireal[8]->sumaproducto;
                                        $hs = $this->leytrireal[8]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10057:
                                    if(isset($this->leytrireal[9]->sumaproducto) && (isset($this->leytrireal[9]->suma) && $this->leytrireal[9]->suma != 0))
                                    {
                                        $min = $this->leytrireal[9]->sumaproducto;
                                        $hs = $this->leytrireal[9]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                //No esta aparece como desactivado
                                case 10058:
                                    if(isset($this->leytrireal[10]->sumaproducto) && (isset($this->leytrireal[10]->suma) && $this->leytrireal[10]->suma != 0))
                                    {
                                        $min = $this->leytrireal[10]->sumaproducto;
                                        $hs = $this->leytrireal[10]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10059:
                                    $tri_real = $this->sumtrireal[20];
                                break;
                                case 10060:
                                    $tri_real = $this->sumtrireal[21];
                                break;
                                case 10061:
                                    $tri_real = $this->sumtrireal[22];
                                break;
                                case 10062:
                                    $tri_real = $this->sumtrireal[23];
                                break;
                                case 10063:
                                    $tri_real = $this->sumtrireal[24];
                                break;
                                case 10064:
                                    $tri_real = $this->sumtrireal[25];
                                break;
                                case 10065:
                                    $tri_real = $this->sumtrireal[26];
                                break;
                                case 10066:
                                    $tri_real = 0;
                                break;
                                case 10067:
                                    $tri_real = $this->sumtrireal[27];
                                break;
                                case 10068:
                                    $tri_real = $this->sumtrireal[28];
                                break;
                                case 10069:
                                    $tri_real = $this->sumtrireal[29];
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
                            if (in_array($data->variable_id, $this->div))
                            {
                                if ($min > 0)
                                {
                                    $t_real = $min/$hs;
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
                            if(isset($tri_real->tri_real))
                            {
                                $t_real = $tri_real->tri_real;
                                if($t_real > 100 || in_array($data->variable_id, $this->percentage))
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
                        })
                        //NO UTILIZA NINGUN TIPO DE SUBCONSULTA
                        ->addColumn('trimestre_budget', function($data)
                        {
                            switch($data->variable_id)
                            {
                                case 10002:
                                    $tri_budget = $this->sumtribudget[0];
                                break;
                                case 10003:
                                    $tri_budget = $this->avgtribudget[0];
                                break;
                                case 10004:
                                    if(isset($this->sumtribudget[0]->tri_budget) && isset($this->sumtribudget[1]->tri_budget))
                                    {
                                        $au = $this->sumtribudget[0]->tri_budget;
                                        $min = $this->sumtribudget[1]->tri_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10005:
                                    $tri_budget = $this->sumtribudget[1];
                                break;
                                case 10006:
                                    if(isset($this->sumtribudget[1]->tri_budget) && (isset($this->sumtribudget[23]->tri_budget) && $this->sumtribudget[23]->tri_budget != 0))
                                    {
                                        $min = $this->sumtribudget[1]->tri_budget;
                                        $hs = $this->sumtribudget[23]->tri_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10007:
                                    $tri_budget = $this->avgtribudget[1];
                                break;
                                case 10008:
                                    $tri_budget = $this->sumtribudget[2];
                                break;
                                case 10009:
                                    $tri_budget = $this->avgtribudget[2];
                                break;
                                case 10010:
                                    if(isset($this->sumtribudget[3]->tri_budget) && isset($this->sumtribudget[2]->tri_budget))
                                    {
                                        $au = $this->sumtribudget[2]->tri_budget;
                                        $min = $this->sumtribudget[3]->tri_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10011:
                                    $tri_budget = $this->sumtribudget[3];
                                break;
                                case 10012:
                                    $tri_budget = $this->avgtribudget[3];
                                break;
                                case 10013:
                                    if(isset($this->sumtribudget[3]->tri_budget) && (isset($this->sumtribudget[24]->tri_budget) && $this->sumtribudget[24]->tri_budget != 0))
                                    {
                                        $min = $this->sumtribudget[3]->tri_budget;
                                        $hs = $this->sumtribudget[24]->tri_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10014:
                                    $tri_budget = $this->avgtribudget[4];
                                break;
                                case 10015:
                                    $tri_budget = $this->avgtribudget[5];
                                break;
                                case 10016:
                                    $tri_budget = 0;
                                break;
                                case 10017:
                                    $tri_budget = $this->avgtribudget[6];
                                break;
                                case 10018:
                                    $tri_budget = $this->avgtribudget[7];
                                break;
                                case 10019:
                                    $tri_budget = $this->sumtribudget[4];
                                break;
                                case 10020:
                                    if(isset($this->sumtribudget[4]->tri_budget) && (isset($this->sumtribudget[25]->tri_budget) && $this->sumtribudget[25]->tri_budget != 0))
                                    {
                                        $min = $this->sumtribudget[4]->tri_budget;
                                        $hs = $this->sumtribudget[25]->tri_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10021:
                                    $tri_budget = $this->avgtribudget[8];
                                break;
                                case 10022:
                                    $tri_budget = $this->sumtribudget[5];
                                break;
                                case 10023:
                                    $tri_budget = $this->sumtribudget[6];
                                break;
                                case 10024:
                                    if(isset($this->sumtribudget[7]->tri_budget) && isset($this->sumtribudget[6]->tri_budget))
                                    {
                                        $au = $this->sumtribudget[6]->tri_budget;
                                        $min = $this->sumtribudget[7]->tri_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10025:
                                    $tri_budget = $this->sumtribudget[7];
                                break;
                                case 10026:
                                    $tri_budget = $this->avgtribudget[9];
                                break;
                                case 10027:
                                    $tri_budget = $this->sumtribudget[8];
                                break;
                                case 10028:
                                    $tri_budget = $this->sumtribudget[9];
                                break;
                                case 10029:
                                    $tri_budget = $this->avgtribudget[10];
                                break;
                                case 10030:
                                    if(isset($this->sumtribudget[10]->tri_budget) && isset($this->sumtribudget[8]->tri_budget))
                                    {
                                        $au = $this->sumtribudget[8]->tri_budget;
                                        $min = $this->sumtribudget[10]->tri_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10031:
                                    $tri_budget = $this->sumtribudget[10];
                                break;
                                case 10032:
                                    if(isset($this->sumtribudget[10]->tri_budget) && (isset($this->sumtribudget[26]->tri_budget) && $this->sumtribudget[26]->tri_budget != 0))
                                    {
                                        $min = $this->sumtribudget[10]->tri_budget;
                                        $hs = $this->sumtribudget[26]->tri_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10033:
                                    $tri_budget = $this->avgtribudget[11];
                                break;
                                case 10034:
                                    $tri_budget = $this->avgtribudget[12];
                                break;
                                case 10035:
                                    if(isset($this->sumtribudget[13]->tri_budget) && isset($this->sumtribudget[11]->tri_budget))
                                    {
                                        $au = $this->sumtribudget[11]->tri_budget;
                                        $min = $this->sumtribudget[13]->tri_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10036:
                                    $tri_budget = $this->avgtribudget[13];
                                break;
                                case 10037:
                                    $tri_budget = $this->sumtribudget[11];
                                break;
                                case 10038:
                                    $tri_budget = $this->sumtribudget[12];
                                break;
                                case 10039:
                                    $tri_budget = $this->sumtribudget[13];
                                break;
                                case 10040:
                                    $tri_budget = $this->avgtribudget[14];
                                break;
                                case 10041:
                                    if(isset($this->leytribudget[0]->sumaproducto) && (isset($this->leytribudget[0]->suma) && $this->leytribudget[0]->suma != 0))
                                    {
                                        $min = $this->leytribudget[0]->sumaproducto;
                                        $hs = $this->leytribudget[0]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10042:
                                    if(isset($this->leytribudget[1]->sumaproducto) && (isset($this->leytribudget[1]->suma) && $this->leytribudget[1]->suma != 0))
                                    {
                                        $min = $this->leytribudget[1]->sumaproducto;
                                        $hs = $this->leytribudget[1]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10043:
                                    if(isset($this->leytribudget[2]->sumaproducto) && (isset($this->leytribudget[2]->suma) && $this->leytribudget[2]->suma != 0))
                                    {
                                        $min = $this->leytribudget[2]->sumaproducto;
                                        $hs = $this->leytribudget[2]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10044:
                                    if(isset($this->leytribudget[3]->sumaproducto) && (isset($this->leytribudget[3]->suma) && $this->leytribudget[3]->suma != 0))
                                    {
                                        $min = $this->leytribudget[3]->sumaproducto;
                                        $hs = $this->leytribudget[3]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10045:
                                    $tri_budget = $this->sumtribudget[14];
                                break;
                                case 10046:
                                    $tri_budget = $this->sumtribudget[15];
                                break;
                                case 10047:
                                    $tri_budget = $this->sumtribudget[16];
                                break;
                                case 10048:
                                    $tri_budget = $this->sumtribudget[17];                            
                                    if(isset($tri_budget->tri_budget))
                                    {
                                        $t_budget = $tri_budget->tri_budget;
                                        return number_format($t_budget, 2, '.', ',');                                
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10049:
                                    $tri_budget = $this->avgtribudget[15];
                                break;
                                case 10050:
                                    if(isset($this->leytribudget[4]->sumaproducto) && (isset($this->leytribudget[4]->suma) && $this->leytribudget[4]->suma != 0))
                                    {
                                        $min = $this->leytribudget[4]->sumaproducto;
                                        $hs = $this->leytribudget[4]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10051:
                                    if(isset($this->leytribudget[5]->sumaproducto) && (isset($this->leytribudget[5]->suma) && $this->leytribudget[5]->suma != 0))
                                    {
                                        $min = $this->leytribudget[5]->sumaproducto;
                                        $hs = $this->leytribudget[5]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10052:
                                    $tri_budget = $this->sumtribudget[18];
                                break;
                                case 10053:
                                    $tri_budget = $this->sumtribudget[19];
                                break;
                                case 10054:
                                    if(isset($this->leytribudget[6]->sumaproducto) && (isset($this->leytribudget[6]->suma) && $this->leytribudget[6]->suma != 0))
                                    {
                                        $min = $this->leytribudget[6]->sumaproducto;
                                        $hs = $this->leytribudget[6]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10055:
                                    if(isset($this->leytribudget[7]->sumaproducto) && (isset($this->leytribudget[7]->suma) && $this->leytribudget[7]->suma != 0))
                                    {
                                        $min = $this->leytribudget[7]->sumaproducto;
                                        $hs = $this->leytribudget[7]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10056:
                                    if(isset($this->leytribudget[8]->sumaproducto) && (isset($this->leytribudget[8]->suma) && $this->leytribudget[8]->suma != 0))
                                    {
                                        $min = $this->leytribudget[8]->sumaproducto;
                                        $hs = $this->leytribudget[8]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10057:
                                    if(isset($this->leytribudget[9]->sumaproducto) && (isset($this->leytribudget[9]->suma) && $this->leytribudget[9]->suma != 0))
                                    {
                                        $min = $this->leytribudget[9]->sumaproducto;
                                        $hs = $this->leytribudget[9]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                //No esta aparece como desactivado
                                case 10058:
                                    if(isset($this->leytribudget[10]->sumaproducto) && (isset($this->leytribudget[10]->suma) && $this->leytribudget[10]->suma != 0))
                                    {
                                        $min = $this->leytribudget[10]->sumaproducto;
                                        $hs = $this->leytribudget[10]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10059:
                                    $tri_budget = $this->sumtribudget[20];
                                break;
                                case 10060:
                                    $tri_budget = $this->sumtribudget[21];
                                break;
                                case 10061:
                                    $tri_budget = $this->sumtribudget[22];
                                break;
                                case 10062:
                                    $tri_budget = $this->sumtribudget[23];
                                break;
                                case 10063:
                                    $tri_budget = $this->sumtribudget[24];
                                break;
                                case 10064:
                                    $tri_budget = $this->sumtribudget[25];
                                break;
                                case 10065:
                                    $tri_budget = $this->sumtribudget[26];
                                break;
                                case 10066:
                                    $tri_budget = 0;
                                break;
                                case 10067:
                                    $tri_budget = $this->sumtribudget[27];
                                break;
                                case 10068:
                                    $tri_budget = $this->sumtribudget[28];
                                break;
                                case 10069:
                                    $tri_budget = $this->sumtribudget[29];
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
                            if (in_array($data->variable_id, $this->div))
                            {
                                if ($min > 0)
                                {
                                    $t_budget = $min/$hs;
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
                            if(isset($tri_budget->tri_budget))
                            {
                                $t_budget = $tri_budget->tri_budget;
                                if($t_budget > 100 || in_array($data->variable_id, $this->percentage))
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
                        })
                        //TERMINADO 06/09/2024
                        ->addColumn('trimestre_forecast', function($data)
                        {                            
                            switch($data->variable_id)
                            {
                                case 10002:
                                    $tri_forecast = $this->sumtriforecast[0];
                                break;
                                case 10003:
                                    $tri_forecast = $this->avgtriforecast[0];
                                break;
                                case 10004:
                                    if(isset($this->sumtriforecast[0]->tri_forecast) && isset($this->sumtriforecast[1]->tri_forecast))
                                    {
                                        $au = $this->sumtriforecast[0]->tri_forecast;
                                        $min = $this->sumtriforecast[1]->tri_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10005:
                                    $tri_forecast = $this->sumtriforecast[1];
                                break;
                                case 10006:
                                    if(isset($this->sumtriforecast[1]->tri_forecast) && (isset($this->sumtriforecast[23]->tri_forecast) && $this->sumtriforecast[23]->tri_forecast != 0))
                                    {
                                        $min = $this->sumtriforecast[1]->tri_forecast;
                                        $hs = $this->sumtriforecast[23]->tri_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10007:
                                    $tri_forecast = $this->avgtriforecast[1];
                                break;
                                case 10008:
                                    $tri_forecast = $this->sumtriforecast[2];
                                break;
                                case 10009:
                                    $tri_forecast = $this->avgtriforecast[2];
                                break;
                                case 10010:
                                    if(isset($this->sumtriforecast[3]->tri_forecast) && isset($this->sumtriforecast[2]->tri_forecast))
                                    {
                                        $au = $this->sumtriforecast[2]->tri_forecast;
                                        $min = $this->sumtriforecast[3]->tri_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10011:
                                    $tri_forecast = $this->sumtriforecast[3];
                                break;
                                case 10012:
                                    $tri_forecast = $this->avgtriforecast[3];
                                break;
                                case 10013:
                                    if(isset($this->sumtriforecast[3]->tri_forecast) && (isset($this->sumtriforecast[24]->tri_forecast) && $this->sumtriforecast[24]->tri_forecast != 0))
                                    {
                                        $min = $this->sumtriforecast[3]->tri_forecast;
                                        $hs = $this->sumtriforecast[24]->tri_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10014:
                                    $tri_forecast = $this->avgtriforecast[4];
                                break;
                                case 10015:
                                    $tri_forecast = $this->avgtriforecast[5];
                                break;
                                case 10016:
                                    $tri_forecast = 0;
                                break;
                                case 10017:
                                    $tri_forecast = $this->avgtriforecast[6];
                                break;
                                case 10018:
                                    $tri_forecast = $this->avgtriforecast[7];
                                break;
                                case 10019:
                                    $tri_forecast = $this->sumtriforecast[4];
                                break;
                                case 10020:
                                    if(isset($this->sumtriforecast[4]->tri_forecast) && (isset($this->sumtriforecast[25]->tri_forecast) && $this->sumtriforecast[25]->tri_forecast != 0))
                                    {
                                        $min = $this->sumtriforecast[4]->tri_forecast;
                                        $hs = $this->sumtriforecast[25]->tri_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10021:
                                    $tri_forecast = $this->avgtriforecast[8];
                                break;
                                case 10022:
                                    $tri_forecast = $this->sumtriforecast[5];
                                break;
                                case 10023:
                                    $tri_forecast = $this->sumtriforecast[6];
                                break;
                                case 10024:
                                    if(isset($this->sumtriforecast[7]->tri_forecast) && isset($this->sumtriforecast[6]->tri_forecast))
                                    {
                                        $au = $this->sumtriforecast[6]->tri_forecast;
                                        $min = $this->sumtriforecast[7]->tri_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10025:
                                    $tri_forecast = $this->sumtriforecast[7];
                                break;
                                case 10026:
                                    $tri_forecast = $this->avgtriforecast[9];
                                break;
                                case 10027:
                                    $tri_forecast = $this->sumtriforecast[8];
                                break;
                                case 10028:
                                    //$tri_forecast = $this->sumtriforecast[9];
                                    //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                            //SUMATRIMESTRAL((((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                                   
                                            
                                                                                
                                            //10010 Ley Au MMSA_HPGR_Ley Au 
                                            //Promedio Ponderado Trimestral(10011 MMSA_HPGR_Mineral Triturado t, 10010 MMSA_HPGR_Ley Au g/t)                         
                                            $sumaproducto10030 = DB::select(
                                                'SELECT A.variable_id as var, SUM(A.valor * B.valor) as sumaproducto FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10030) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10031) as B
                                                ON A.fecha = B.fecha
                                                WHERE A.fecha between ? and ?
                                                GROUP BY A.variable_id', 
                                                [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                                            );   

                                            //10033 MMSA_APILAM_STACKER_Recuperación %
                                            //Promedio Ponderado Trimestral(10011 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                            $sumaproducto10033= DB::select(
                                                'SELECT A.variable_id as var, SUM(A.valor * B.valor) as sumaproducto FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10033) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[forecast]
                                                where variable_id = 10031) as B
                                                ON A.fecha = B.fecha
                                                WHERE A.fecha between ? and ?
                                                GROUP BY A.variable_id', 
                                                [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                                            ); 
                                                                            
                                            $suma10031 = $this->sumtriforecast10031; 
                                        

                                            if(isset($sumaproducto10030[0]->sumaproducto) && isset($sumaproducto10033[0]->sumaproducto) && isset($suma10031[0]->suma))
                                            {
                                                if ($suma10031[0]->suma > 0) {
                                                    //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                    $recup =  $sumaproducto10033[0]->sumaproducto/$suma10031[0]->suma;
                                                    $leyAu = $sumaproducto10030[0]->sumaproducto/$suma10031[0]->suma;
                                                    $sumMin = $suma10031[0]->suma;
                                                    $tri_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                    if($tri_forecast > 100)
                                                    {
                                                        return number_format(round($tri_forecast), 0, '.', ',');
                                                    }
                                                    else
                                                    {
                                                        return number_format($tri_forecast, 2, '.', ',');
                                                    }
                                                }
                                                else {
                                                    return '-';
                                                }
                                            }
                                            else
                                            {
                                                return '-';
                                            } 
                                break;
                                case 10029:
                                    $tri_forecast = $this->avgtriforecast[10];
                                break;
                                case 10030:
                                    if(isset($this->sumtriforecast[10]->tri_forecast) && isset($this->sumtriforecast[8]->tri_forecast))
                                    {
                                        $au = $this->sumtriforecast[8]->tri_forecast;
                                        $min = $this->sumtriforecast[10]->tri_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10031:
                                    $tri_forecast = $this->sumtriforecast[10];
                                break;
                                case 10032:
                                    if(isset($this->sumtriforecast[10]->tri_forecast) && (isset($this->sumtriforecast[26]->tri_forecast) && $this->sumtriforecast[26]->tri_forecast != 0))
                                    {
                                        $min = $this->sumtriforecast[10]->tri_forecast;
                                        $hs = $this->sumtriforecast[26]->tri_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10033:
                                    $tri_forecast = $this->avgtriforecast[11];
                                break;
                                case 10034:
                                    $tri_forecast = $this->avgtriforecast[12];
                                break;
                                case 10035:
                                    if(isset($this->sumtriforecast[13]->tri_forecast) && isset($this->sumtriforecast[11]->tri_forecast))
                                    {
                                        $au = $this->sumtriforecast[11]->tri_forecast;
                                        $min = $this->sumtriforecast[13]->tri_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10036:
                                    $tri_forecast = $this->avgtriforecast[13];
                                break;
                                case 10037:
                                    $tri_forecast = $this->sumtriforecast[11];
                                break;
                                case 10038:
                                    //$tri_forecast = $this->sumtriforecast[12];
                                        //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                                //SUMATRIMESTRAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035) 
                                                                                    
                                                //10035 MMSA_APILAM_TA_Ley Au g/t
                                                //Promedio Ponderado Trimestral(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                                $sumaproducto10035 = DB::select(
                                                    'SELECT A.variable_id as var, SUM(A.valor * B.valor) as sumaproducto FROM
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10035) as A
                                                    INNER JOIN   
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10039) as B
                                                    ON A.fecha = B.fecha
                                                    WHERE A.fecha between ? and ?
                                                    GROUP BY A.variable_id', 
                                                    [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                                                ); 
            
                                                //10036 MMSA_APILAM_TA_Recuperación %
                                                //Promedio Ponderado Trimestral(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                                $sumaproducto10036 = DB::select(
                                                    'SELECT A.variable_id as var, SUM(A.valor * B.valor) as sumaproducto FROM
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10036) as A
                                                    INNER JOIN   
                                                    (SELECT fecha, variable_id, [valor]
                                                    FROM [dbo].[forecast]
                                                    where variable_id = 10039) as B
                                                    ON A.fecha = B.fecha
                                                    WHERE A.fecha between ? and ?
                                                    GROUP BY A.variable_id', 
                                                    [date('Y-m-d',strtotime($this->fecha_iniTri)),date('Y-m-d',strtotime($this->date))]
                                                );                                     
                                                $suma10039 = $this->sumtriforecast10039;                                     
            
                                                if(isset($sumaproducto10035[0]->sumaproducto) && isset($sumaproducto10036[0]->sumaproducto) && isset($suma10039[0]->suma))
                                                {
                                                    if ($suma10039[0]->suma > 0) {
                                                        //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                        $recup =  $sumaproducto10036[0]->sumaproducto/$suma10039[0]->suma;
                                                        $leyAu = $sumaproducto10035[0]->sumaproducto/$suma10039[0]->suma;
                                                        $sumMin = $suma10039[0]->suma;
                                                        $tri_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                        if($tri_forecast > 100)
                                                        {
                                                            return number_format(round($tri_forecast), 0, '.', ',');
                                                        }
                                                        else
                                                        {
                                                            return number_format($tri_forecast, 2, '.', ',');
                                                        }
                                                    }
                                                    else {
                                                        return '-';
                                                    }
                                                }
                                                else
                                                {
                                                    return '-';
                                                }
                                break;
                                case 10039:
                                    $tri_forecast = $this->sumtriforecast[13];
                                break;
                                case 10040:
                                    $tri_forecast = $this->avgtriforecast[14];
                                break;
                                case 10041:
                                    if(isset($this->leytriforecast[0]->sumaproducto) && (isset($this->leytriforecast[0]->suma) && $this->leytriforecast[0]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[0]->sumaproducto;
                                        $hs = $this->leytriforecast[0]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10042:
                                    if(isset($this->leytriforecast[1]->sumaproducto) && (isset($this->leytriforecast[1]->suma) && $this->leytriforecast[1]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[1]->sumaproducto;
                                        $hs = $this->leytriforecast[1]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10043:
                                    if(isset($this->leytriforecast[2]->sumaproducto) && (isset($this->leytriforecast[2]->suma) && $this->leytriforecast[2]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[2]->sumaproducto;
                                        $hs = $this->leytriforecast[2]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10044:
                                    if(isset($this->leytriforecast[3]->sumaproducto) && (isset($this->leytriforecast[3]->suma) && $this->leytriforecast[3]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[3]->sumaproducto;
                                        $hs = $this->leytriforecast[3]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10045:
                                    $tri_forecast = $this->sumtriforecast[14];
                                break;
                                case 10046:
                                    $tri_forecast = $this->sumtriforecast[15];
                                break;
                                case 10047:
                                    $tri_forecast = $this->sumtriforecast[16];
                                break;
                                case 10048:
                                    $tri_forecast = $this->sumtriforecast[17];                            
                                    if(isset($tri_forecast->tri_forecast))
                                    {
                                        $t_forecast = $tri_forecast->tri_forecast;
                                        return number_format($t_forecast, 2, '.', ',');                                
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10049:
                                    $tri_forecast = $this->avgtriforecast[15];
                                break;
                                case 10050:
                                    if(isset($this->leytriforecast[4]->sumaproducto) && (isset($this->leytriforecast[4]->suma) && $this->leytriforecast[4]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[4]->sumaproducto;
                                        $hs = $this->leytriforecast[4]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10051:
                                    if(isset($this->leytriforecast[5]->sumaproducto) && (isset($this->leytriforecast[5]->suma) && $this->leytriforecast[5]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[5]->sumaproducto;
                                        $hs = $this->leytriforecast[5]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10052:
                                    $tri_forecast = $this->sumtriforecast[18];
                                break;
                                case 10053:
                                    $tri_forecast = $this->sumtriforecast[19];
                                break;
                                case 10054:
                                    if(isset($this->leytriforecast[6]->sumaproducto) && (isset($this->leytriforecast[6]->suma) && $this->leytriforecast[6]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[6]->sumaproducto;
                                        $hs = $this->leytriforecast[6]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10055:
                                    if(isset($this->leytriforecast[7]->sumaproducto) && (isset($this->leytriforecast[7]->suma) && $this->leytriforecast[7]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[7]->sumaproducto;
                                        $hs = $this->leytriforecast[7]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10056:
                                    if(isset($this->leytriforecast[8]->sumaproducto) && (isset($this->leytriforecast[8]->suma) && $this->leytriforecast[8]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[8]->sumaproducto;
                                        $hs = $this->leytriforecast[8]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10057:
                                    if(isset($this->leytriforecast[9]->sumaproducto) && (isset($this->leytriforecast[9]->suma) && $this->leytriforecast[9]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[9]->sumaproducto;
                                        $hs = $this->leytriforecast[9]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10058:
                                    if(isset($this->leytriforecast[10]->sumaproducto) && (isset($this->leytriforecast[10]->suma) && $this->leytriforecast[10]->suma != 0))
                                    {
                                        $min = $this->leytriforecast[10]->sumaproducto;
                                        $hs = $this->leytriforecast[10]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10059:
                                    $tri_forecast = $this->sumtriforecast[20];
                                break;
                                case 10060:
                                    $tri_forecast = $this->sumtriforecast[21];
                                break;
                                case 10061:
                                    $tri_forecast = $this->sumtriforecast[22];
                                break;
                                case 10062:
                                    $tri_forecast = $this->sumtriforecast[23];
                                break;
                                case 10063:
                                    $tri_forecast = $this->sumtriforecast[24];
                                break;
                                case 10064:
                                    $tri_forecast = $this->sumtriforecast[25];
                                break;
                                case 10065:
                                    $tri_forecast = $this->sumtriforecast[26];
                                break;
                                case 10066:
                                    $tri_forecast = 0;
                                break;
                                case 10067:
                                    $tri_forecast = $this->sumtriforecast[27];
                                break;
                                case 10068:
                                    $tri_forecast = $this->sumtriforecast[28];
                                break;
                                case 10069:
                                    $tri_forecast = $this->sumtriforecast[29];
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
                            if (in_array($data->variable_id, $this->div))
                            {
                                if ($min > 0)
                                {
                                    $t_forecast = $min/$hs;
                                    if($t_forecast > 100)
                                    {
                                        return number_format(round($t_forecast), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($t_forecast, 2, '.', ',');
                                    }
                                }
                                else
                                {
                                    return '-';
                                }
                            }
                            if(isset($tri_forecast->tri_forecast))
                            {
                                $t_forecast = $tri_forecast->tri_forecast;
                                if($t_forecast > 100 || in_array($data->variable_id, $this->percentage))
                                {
                                    return number_format(round($t_forecast), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($t_forecast, 2, '.', ',');
                                }
                            }
                            else
                            {
                                return '-';
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
                                        if($a_real > 100 || in_array($data->variable_id, $this->percentage))
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
                                        case 10037: 
                                            //10037: MMSA_APILAM_TA_Total Au Apilado (oz)                  
                                            //SUMATORIA ANUAL(((10039 MMSA_APILAM_TA_Total Mineral Apilado (t))*(10035 MMSA_APILAM_TA_Ley Au (g/t))) / 31.1035)   
                                            $anio_real= DB::select(
                                                'SELECT YEAR(A.fecha) as year, SUM((A.valor * B.valor)/31.1035) as anio_real FROM
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[data]
                                                where variable_id = 10039) as A
                                                INNER JOIN   
                                                (SELECT fecha, variable_id, [valor]
                                                FROM [dbo].[data]
                                                where variable_id = 10035) as B
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
                                            if ($this->date == '2022-12-31')
                                            {
                                                return number_format(round(107280), 0, '.', ',');
                                            }
                                            else{
                                                if ($this->date == '2023-04-30')
                                                {
                                                    return number_format(round(33491), 0, '.', ',');
                                                }
                                                else {
                                                    if (strtotime($this->date) >= strtotime('2023-05-29')) {
                                                        $resultado = DB::select('SELECT 
                                                            SUM(CASE 
                                                                WHEN V10031 > 0 THEN ((V10033/V10031) * (V10030/V10031) * V10031 * 0.01)/31.1035
                                                                ELSE 0
                                                            END) AS AU FROM
                                                            (SELECT MONTH(fecha) AS MES, SUM(valor) AS V10031
                                                            FROM [dbo].[data]
                                                            WHERE variable_id = 10031
                                                            AND  DATEPART(y, fecha) <= DATEPART(y, ?)
                                                            AND YEAR(fecha) = YEAR(?)
                                                            GROUP BY MONTH(fecha)) AS GC
                                                            LEFT JOIN
                                                            (SELECT MONTH(A.fecha) AS MES, SUM(A.valor * B.valor) AS V10030 FROM
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10030) as A
                                                            INNER JOIN   
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10031) as B
                                                            ON A.fecha = B.fecha
                                                            AND  DATEPART(y, A.fecha) <=  DATEPART(y, ?)
                                                            AND YEAR(A.fecha) = YEAR(?)
                                                            GROUP BY MONTH(A.fecha)) AS GA
                                                            ON GC.MES = GA.MES                                                 
                                                            LEFT JOIN
                                                            (SELECT MONTH(A.fecha) AS MES, SUM(A.valor * B.valor) AS V10033 FROM
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10033) as A
                                                            INNER JOIN   
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10031) as B
                                                            ON A.fecha = B.fecha
                                                            AND  DATEPART(y, A.fecha) <=  DATEPART(y, ?)
                                                            AND YEAR(A.fecha) = YEAR(?)
                                                            GROUP BY MONTH(A.fecha)) AS GB
                                                            ON GC.MES = GB.MES',
                                                            [$this->date, $this->date, $this->date, $this->date, $this->date, $this->date]);
                                                        if(isset($resultado[0]->AU))
                                                        {
                                                            if ($resultado[0]->AU > 0) {
                                                                return number_format(round($resultado[0]->AU), 0, '.', ',');                                                
                                                            }
                                                            else {
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
                                                        //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                                        //SUMAANUAL((((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)    
                                                        
                                                        
                                                                                            
                                                        //10030 Ley Au MMSA_HPGR_Ley Au 
                                                        //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10030 MMSA_HPGR_Ley Au g/t)                         
                                                        $sumaproducto10030 = DB::select(
                                                            'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10030) as A
                                                            INNER JOIN   
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10031) as B
                                                            ON A.fecha = B.fecha
                                                            WHERE YEAR(A.fecha) = ?
                                                            AND  DATEPART(y, A.fecha) <=  ?
                                                            GROUP BY YEAR(A.fecha)',
                                                            [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                        );  
                
                                                        //10033 MMSA_APILAM_STACKER_Recuperación %
                                                        //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                                        $sumaproducto10033 = DB::select(
                                                            'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10033) as A
                                                            INNER JOIN   
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10031) as B
                                                            ON A.fecha = B.fecha
                                                            WHERE YEAR(A.fecha) = ?
                                                            AND  DATEPART(y, A.fecha) <=  ?
                                                            GROUP BY YEAR(A.fecha)',
                                                            [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                        );                                     
                                                        $suma10031 = $this->sumanioreal10031; 
                                                    
                                                        
                                                        
                                                        if(isset($sumaproducto10030[0]->sumaproducto) && isset($sumaproducto10033[0]->sumaproducto) && isset($suma10031[0]->suma))
                                                        {
                                                            if ($suma10031[0]->suma > 0) {
                                                                //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                                $recup =  $sumaproducto10033[0]->sumaproducto/$suma10031[0]->suma;
                                                                $leyAu = $sumaproducto10030[0]->sumaproducto/$suma10031[0]->suma;
                                                                $sumMin = $suma10031[0]->suma;
                                                                $a_real =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                                if($a_real > 100)
                                                                {
                                                                    return number_format(round($a_real), 0, '.', ',');
                                                                }
                                                                else
                                                                {
                                                                    return number_format($a_real, 2, '.', ',');
                                                                }
                                                            }
                                                            else {
                                                                return '-';
                                                            }
                                                        }
                                                        else
                                                        {
                                                            return '-';
                                                        }
                                                    }
                                                }                                        
                                            }
                                        break;
                                        case 10038: 
                                            if ($this->date == '2022-12-31')
                                            {
                                                return number_format(round(107362), 0, '.', ',');
                                            }
                                            else{
                                                if ($this->date == '2023-04-30')
                                                {
                                                    return number_format(round(33491), 0, '.', ',');
                                                }
                                                else {
                                                    if (strtotime($this->date) >= strtotime('2023-05-29')) {
                                                        $resultado = DB::select('SELECT 
                                                            SUM(CASE 
                                                                WHEN V10039 > 0 THEN ((V10036/V10039) * (V10035/V10039) * V10039 * 0.01)/31.1035
                                                                ELSE 0
                                                            END) AS AU FROM
                                                            (SELECT MONTH(fecha) AS MES, SUM(valor) AS V10039
                                                            FROM [dbo].[data]
                                                            WHERE variable_id = 10039
                                                            AND  DATEPART(y, fecha) <= DATEPART(y, ?)
                                                            AND YEAR(fecha) = YEAR(?)
                                                            GROUP BY MONTH(fecha)) AS GC
                                                            LEFT JOIN
                                                            (SELECT MONTH(A.fecha) AS MES, SUM(A.valor * B.valor) AS V10035 FROM
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10035) as A
                                                            INNER JOIN   
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10039) as B
                                                            ON A.fecha = B.fecha
                                                            AND  DATEPART(y, A.fecha) <=  DATEPART(y, ?)
                                                            AND YEAR(A.fecha) = YEAR(?)
                                                            GROUP BY MONTH(A.fecha)) AS GA
                                                            ON GC.MES = GA.MES                                                 
                                                            LEFT JOIN
                                                            (SELECT MONTH(A.fecha) AS MES, SUM(A.valor * B.valor) AS V10036 FROM
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10036) as A
                                                            INNER JOIN   
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[data]
                                                            where variable_id = 10039) as B
                                                            ON A.fecha = B.fecha
                                                            AND  DATEPART(y, A.fecha) <=  DATEPART(y, ?)
                                                            AND YEAR(A.fecha) = YEAR(?)
                                                            GROUP BY MONTH(A.fecha)) AS GB
                                                            ON GC.MES = GB.MES',
                                                            [$this->date, $this->date, $this->date, $this->date, $this->date, $this->date]);
                                                        if(isset($resultado[0]->AU))
                                                        {
                                                            if ($resultado[0]->AU > 0) {
                                                                return number_format(round($resultado[0]->AU), 0, '.', ',');                                                
                                                            }
                                                            else {
                                                                return '-';
                                                            }
                                                        }
                                                        else
                                                        {
                                                            return '-';
                                                        }
                                                    }
                                                    else{
                                                        //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                                        //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)       
                                                        
                                                                                            
                                                        //10035 MMSA_APILAM_TA_Ley Au g/t
                                                        //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                                        $sumaproducto10035 = DB::select(
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
                                                                                            
                                                        //10036 MMSA_APILAM_TA_Recuperación %
                                                        //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                                        $sumaproducto10036 = DB::select(
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
                                                        $suma10039 = $this->sumanioreal10039; 
                                                        
                
                                                        if(isset($sumaproducto10035[0]->sumaproducto) && isset($sumaproducto10036[0]->sumaproducto) && isset($suma10039[0]->suma))
                                                        {
                                                            if ($suma10039[0]->suma > 0) {
                                                                //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                                $recup =  $sumaproducto10036[0]->sumaproducto/$suma10039[0]->suma;
                                                                $leyAu = $sumaproducto10035[0]->sumaproducto/$suma10039[0]->suma;
                                                                $sumMin = $suma10039[0]->suma;
                                                                $a_real =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                                if($a_real > 100)
                                                                {
                                                                    return number_format(round($a_real), 0, '.', ',');
                                                                }
                                                                else
                                                                {
                                                                    return number_format($a_real, 2, '.', ',');
                                                                }
                                                            }
                                                            else {
                                                                return '-';
                                                            }
                                                        }
                                                        else
                                                        {
                                                            return '-';
                                                        }
                                                    }
                                                }                                        
                                            }
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
                                        case 10048:
                                            $anio_real= DB::select(
                                                'SELECT YEAR(fecha) as year, SUM(valor) as anio_real
                                                FROM [dbo].[data]
                                                WHERE variable_id = ?
                                                AND  YEAR(fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                GROUP BY YEAR(fecha)', 
                                                [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                            );
                                            if(isset($anio_real[0]->anio_real))
                                            {
                                                $a_real = $anio_real[0]->anio_real;
                                                return number_format($a_real, 2, '.', ',');                                        
                                            }
                                            else
                                            {
                                                return '-';
                                            }
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
                                            GROUP BY YEAR(fecha)', 
                                            [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                        );
                                        if(isset($anio_real[0]->anio_real))
                                        {
                                            $a_real = $anio_real[0]->anio_real;
                                            return number_format(round($a_real), 0, '.', ',');
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
                        //MODIFICADO 14/10/2024
                        ->addColumn('anio_budget', function($data)
                        {
                            switch($data->variable_id)
                            {
                                case 10002:
                                    $anio_budget = $this->sumaniobudget[0];
                                break;
                                case 10003:
                                    $anio_budget = $this->avganiobudget[0];
                                break;
                                case 10004:
                                    if(isset($this->sumaniobudget[0]->anio_budget) && isset($this->sumaniobudget[1]->anio_budget))
                                    {
                                        $au = $this->sumaniobudget[0]->anio_budget;
                                        $min = $this->sumaniobudget[1]->anio_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10005:
                                    $anio_budget = $this->sumaniobudget[1];
                                break;
                                case 10006:
                                    if(isset($this->sumaniobudget[1]->anio_budget) && (isset($this->sumaniobudget[23]->anio_budget) && $this->sumaniobudget[23]->anio_budget != 0))
                                    {
                                        $min = $this->sumaniobudget[1]->anio_budget;
                                        $hs = $this->sumaniobudget[23]->anio_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10007:
                                    $anio_budget = $this->avganiobudget[1];
                                break;
                                case 10008:
                                    $anio_budget = $this->sumaniobudget[2];
                                break;
                                case 10009:
                                    $anio_budget = $this->avganiobudget[2];
                                break;
                                case 10010:
                                    if(isset($this->sumaniobudget[3]->anio_budget) && isset($this->sumaniobudget[2]->anio_budget))
                                    {
                                        $au = $this->sumaniobudget[2]->anio_budget;
                                        $min = $this->sumaniobudget[3]->anio_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10011:
                                    $anio_budget = $this->sumaniobudget[3];
                                break;
                                case 10012:
                                    $anio_budget = $this->avganiobudget[3];
                                break;
                                case 10013:
                                    if(isset($this->sumaniobudget[3]->anio_budget) && (isset($this->sumaniobudget[24]->anio_budget) && $this->sumaniobudget[24]->anio_budget != 0))
                                    {
                                        $min = $this->sumaniobudget[3]->anio_budget;
                                        $hs = $this->sumaniobudget[24]->anio_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10014:
                                    $anio_budget = $this->avganiobudget[4];
                                break;
                                case 10015:
                                    $anio_budget = $this->avganiobudget[5];
                                break;
                                case 10016:
                                    $anio_budget = 0;
                                break;
                                case 10017:
                                    $anio_budget = $this->avganiobudget[6];
                                break;
                                case 10018:
                                    $anio_budget = $this->avganiobudget[7];
                                break;
                                case 10019:
                                    $anio_budget = $this->sumaniobudget[4];
                                break;
                                case 10020:
                                    if(isset($this->sumaniobudget[4]->anio_budget) && (isset($this->sumaniobudget[25]->anio_budget) && $this->sumaniobudget[25]->anio_budget != 0))
                                    {
                                        $min = $this->sumaniobudget[4]->anio_budget;
                                        $hs = $this->sumaniobudget[25]->anio_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10021:
                                    $anio_budget = $this->avganiobudget[8];
                                break;
                                case 10022:
                                    $anio_budget = $this->sumaniobudget[5];
                                break;
                                case 10023:
                                    $anio_budget = $this->sumaniobudget[6];
                                break;
                                case 10024:
                                    if(isset($this->sumaniobudget[7]->anio_budget) && isset($this->sumaniobudget[6]->anio_budget))
                                    {
                                        $au = $this->sumaniobudget[6]->anio_budget;
                                        $min = $this->sumaniobudget[7]->anio_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10025:
                                    $anio_budget = $this->sumaniobudget[7];
                                break;
                                case 10026:
                                    $anio_budget = $this->avganiobudget[9];
                                break;
                                case 10027:
                                    $anio_budget = $this->sumaniobudget[8];
                                break;
                                case 10028:
                                    //$anio_budget = $this->sumaniobudget[9];
                                    //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                    //SUMAANUAL((((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                                             
                                    //10030 Ley Au MMSA_HPGR_Ley Au 
                                    //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10030 MMSA_HPGR_Ley Au g/t)                         
                                    $sumaproducto10030 = DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[budget]
                                        where variable_id = 10030) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[budget]
                                        where variable_id = 10031) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );  

                                    //10033 MMSA_APILAM_STACKER_Recuperación %
                                    //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                    $sumaproducto10033 = DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[budget]
                                        where variable_id = 10033) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[budget]
                                        where variable_id = 10031) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma10031 = $this->sumaniobudget10031; 
                                    
                                    
                                    
                                    if(isset($sumaproducto10030[0]->sumaproducto) && isset($sumaproducto10033[0]->sumaproducto) && isset($suma10031[0]->suma))
                                    {
                                        if ($suma10031[0]->suma > 0) {
                                            //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                            $recup =  $sumaproducto10033[0]->sumaproducto/$suma10031[0]->suma;
                                            $leyAu = $sumaproducto10030[0]->sumaproducto/$suma10031[0]->suma;
                                            $sumMin = $suma10031[0]->suma;
                                            $anio_budget =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                            if($anio_budget > 100)
                                            {
                                                return number_format(round($anio_budget), 0, '.', ',');
                                            }
                                            else
                                            {
                                                return number_format($anio_budget, 2, '.', ',');
                                            }
                                        }
                                        else {
                                            return '-';
                                        }
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10029:
                                    $anio_budget = $this->avganiobudget[10];
                                break;
                                case 10030:
                                    if(isset($this->sumaniobudget[10]->anio_budget) && isset($this->sumaniobudget[8]->anio_budget))
                                    {
                                        $au = $this->sumaniobudget[8]->anio_budget;
                                        $min = $this->sumaniobudget[10]->anio_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10031:
                                    $anio_budget = $this->sumaniobudget[10];
                                break;
                                case 10032:
                                    if(isset($this->sumaniobudget[10]->anio_budget) && (isset($this->sumaniobudget[26]->anio_budget) && $this->sumaniobudget[26]->anio_budget != 0))
                                    {
                                        $min = $this->sumaniobudget[10]->anio_budget;
                                        $hs = $this->sumaniobudget[26]->anio_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10033:
                                    $anio_budget = $this->avganiobudget[11];
                                break;
                                case 10034:
                                    $anio_budget = $this->avganiobudget[12];
                                break;
                                case 10035:
                                    if(isset($this->sumaniobudget[13]->anio_budget) && isset($this->sumaniobudget[11]->anio_budget))
                                    {
                                        $au = $this->sumaniobudget[11]->anio_budget;
                                        $min = $this->sumaniobudget[13]->anio_budget;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10036:
                                    $anio_budget = $this->avganiobudget[13];
                                break;
                                case 10037:
                                    $anio_budget = $this->sumaniobudget[11];
                                break;
                                case 10038:
                                    //$anio_budget = $this->sumaniobudget[12];
                                    //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                                        //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)       
                                                        
                                                                                            
                                                        //10035 MMSA_APILAM_TA_Ley Au g/t
                                                        //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                                        $sumaproducto10035 = DB::select(
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
                                                                                            
                                                        //10036 MMSA_APILAM_TA_Recuperación %
                                                        //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                                        $sumaproducto10036 = DB::select(
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
                                                        $suma10039 = $this->sumaniobudget10039; 
                                                        

                                                        if(isset($sumaproducto10035[0]->sumaproducto) && isset($sumaproducto10036[0]->sumaproducto) && isset($suma10039[0]->suma))
                                                        {
                                                            if ($suma10039[0]->suma > 0) {
                                                                //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                                $recup =  $sumaproducto10036[0]->sumaproducto/$suma10039[0]->suma;
                                                                $leyAu = $sumaproducto10035[0]->sumaproducto/$suma10039[0]->suma;
                                                                $sumMin = $suma10039[0]->suma;
                                                                $anio_budget =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                                if($anio_budget > 100)
                                                                {
                                                                    return number_format(round($anio_budget), 0, '.', ',');
                                                                }
                                                                else
                                                                {
                                                                    return number_format($anio_budget, 2, '.', ',');
                                                                }
                                                            }
                                                            else {
                                                                return '-';
                                                            }
                                                        }
                                                        else
                                                        {
                                                            return '-';
                                                        }
                                break;
                                case 10039:
                                    $anio_budget = $this->sumaniobudget[13];
                                break;
                                case 10040:
                                    $anio_budget = $this->avganiobudget[14];
                                break;
                                case 10041:
                                    if(isset($this->leyaniobudget[0]->sumaproducto) && (isset($this->leyaniobudget[0]->suma) && $this->leyaniobudget[0]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[0]->sumaproducto;
                                        $hs = $this->leyaniobudget[0]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10042:
                                    if(isset($this->leyaniobudget[1]->sumaproducto) && (isset($this->leyaniobudget[1]->suma) && $this->leyaniobudget[1]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[1]->sumaproducto;
                                        $hs = $this->leyaniobudget[1]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10043:
                                    if(isset($this->leyaniobudget[2]->sumaproducto) && (isset($this->leyaniobudget[2]->suma) && $this->leyaniobudget[2]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[2]->sumaproducto;
                                        $hs = $this->leyaniobudget[2]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10044:
                                    if(isset($this->leyaniobudget[3]->sumaproducto) && (isset($this->leyaniobudget[3]->suma) && $this->leyaniobudget[3]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[3]->sumaproducto;
                                        $hs = $this->leyaniobudget[3]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10045:
                                    $anio_budget = $this->sumaniobudget[14];
                                break;
                                case 10046:
                                    $anio_budget = $this->sumaniobudget[15];
                                break;
                                case 10047:
                                    $anio_budget = $this->sumaniobudget[16];
                                break;
                                case 10048:
                                    $anio_budget = $this->sumaniobudget[17];                            
                                    if(isset($anio_budget->anio_budget))
                                    {
                                        $a_budget = $anio_budget->anio_budget;
                                        return number_format($a_budget, 2, '.', ',');                                
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10049:
                                    $anio_budget = $this->avganiobudget[15];
                                break;
                                case 10050:
                                    if(isset($this->leyaniobudget[4]->sumaproducto) && (isset($this->leyaniobudget[4]->suma) && $this->leyaniobudget[4]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[4]->sumaproducto;
                                        $hs = $this->leyaniobudget[4]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10051:
                                    if(isset($this->leyaniobudget[5]->sumaproducto) && (isset($this->leyaniobudget[5]->suma) && $this->leyaniobudget[5]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[5]->sumaproducto;
                                        $hs = $this->leyaniobudget[5]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10052:
                                    $anio_budget = $this->sumaniobudget[18];
                                break;
                                case 10053:
                                    $anio_budget = $this->sumaniobudget[19];
                                break;
                                case 10054:
                                    if(isset($this->leyaniobudget[6]->sumaproducto) && (isset($this->leyaniobudget[6]->suma) && $this->leyaniobudget[6]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[6]->sumaproducto;
                                        $hs = $this->leyaniobudget[6]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10055:
                                    if(isset($this->leyaniobudget[7]->sumaproducto) && (isset($this->leyaniobudget[7]->suma) && $this->leyaniobudget[7]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[7]->sumaproducto;
                                        $hs = $this->leyaniobudget[7]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10056:
                                    if(isset($this->leyaniobudget[8]->sumaproducto) && (isset($this->leyaniobudget[8]->suma) && $this->leyaniobudget[8]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[8]->sumaproducto;
                                        $hs = $this->leyaniobudget[8]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10057:
                                    if(isset($this->leyaniobudget[9]->sumaproducto) && (isset($this->leyaniobudget[9]->suma) && $this->leyaniobudget[9]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[9]->sumaproducto;
                                        $hs = $this->leyaniobudget[9]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10058:
                                    if(isset($this->leyaniobudget[10]->sumaproducto) && (isset($this->leyaniobudget[10]->suma) && $this->leyaniobudget[10]->suma != 0))
                                    {
                                        $min = $this->leyaniobudget[10]->sumaproducto;
                                        $hs = $this->leyaniobudget[10]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10059:
                                    $anio_budget = $this->sumaniobudget[20];
                                break;
                                case 10060:
                                    $anio_budget = $this->sumaniobudget[21];
                                break;
                                case 10061:
                                    $anio_budget = $this->sumaniobudget[22];
                                break;
                                case 10062:
                                    $anio_budget = $this->sumaniobudget[23];
                                break;
                                case 10063:
                                    $anio_budget = $this->sumaniobudget[24];
                                break;
                                case 10064:
                                    $anio_budget = $this->sumaniobudget[25];
                                break;
                                case 10065:
                                    $anio_budget = $this->sumaniobudget[26];
                                break;
                                case 10066:
                                    $anio_budget = 0;
                                break;
                                case 10067:
                                    $anio_budget = $this->sumaniobudget[27];
                                break;
                                case 10068:
                                    $anio_budget = $this->sumaniobudget[28];
                                break;
                                case 10069:
                                    $anio_budget = $this->sumaniobudget[29];
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
                            if (in_array($data->variable_id, $this->div))
                            {
                                if ($min > 0)
                                {
                                    $a_budget = $min/$hs;
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
                            if(isset($anio_budget->anio_budget))
                            {
                                $a_budget = $anio_budget->anio_budget;
                                if($a_budget > 100 || in_array($data->variable_id, $this->percentage))
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
                        })
                        ->addColumn('anio_forecast', function($data)
                        {
                            switch($data->variable_id)
                            {
                                case 10002:
                                    $anio_forecast = $this->sumanioforecast[0];
                                break;
                                case 10003:
                                    $anio_forecast = $this->avganioforecast[0];
                                break;
                                case 10004:
                                    if(isset($this->sumanioforecast[0]->anio_forecast) && isset($this->sumanioforecast[1]->anio_forecast))
                                    {
                                        $au = $this->sumanioforecast[0]->anio_forecast;
                                        $min = $this->sumanioforecast[1]->anio_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10005:
                                    $anio_forecast = $this->sumanioforecast[1];
                                break;
                                case 10006:
                                    if(isset($this->sumanioforecast[1]->anio_forecast) && (isset($this->sumanioforecast[23]->anio_forecast) && $this->sumanioforecast[23]->anio_forecast != 0))
                                    {
                                        $min = $this->sumanioforecast[1]->anio_forecast;
                                        $hs = $this->sumanioforecast[23]->anio_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10007:
                                    $anio_forecast = $this->avganioforecast[1];
                                break;
                                case 10008:
                                    $anio_forecast = $this->sumanioforecast[2];
                                break;
                                case 10009:
                                    $anio_forecast = $this->avganioforecast[2];
                                break;
                                case 10010:
                                    if(isset($this->sumanioforecast[3]->anio_forecast) && isset($this->sumanioforecast[2]->anio_forecast))
                                    {
                                        $au = $this->sumanioforecast[2]->anio_forecast;
                                        $min = $this->sumanioforecast[3]->anio_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10011:
                                    $anio_forecast = $this->sumanioforecast[3];
                                break;
                                case 10012:
                                    $anio_forecast = $this->avganioforecast[3];
                                break;
                                case 10013:
                                    if(isset($this->sumanioforecast[3]->anio_forecast) && (isset($this->sumanioforecast[24]->anio_forecast) && $this->sumanioforecast[24]->anio_forecast != 0))
                                    {
                                        $min = $this->sumanioforecast[3]->anio_forecast;
                                        $hs = $this->sumanioforecast[24]->anio_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10014:
                                    $anio_forecast = $this->avganioforecast[4];
                                break;
                                case 10015:
                                    $anio_forecast = $this->avganioforecast[5];
                                break;
                                case 10016:
                                    $anio_forecast = 0;
                                break;
                                case 10017:
                                    $anio_forecast = $this->avganioforecast[6];
                                break;
                                case 10018:
                                    $anio_forecast = $this->avganioforecast[7];
                                break;
                                case 10019:
                                    $anio_forecast = $this->sumanioforecast[4];
                                break;
                                case 10020:
                                    if(isset($this->sumanioforecast[4]->anio_forecast) && (isset($this->sumanioforecast[25]->anio_forecast) && $this->sumanioforecast[25]->anio_forecast != 0))
                                    {
                                        $min = $this->sumanioforecast[4]->anio_forecast;
                                        $hs = $this->sumanioforecast[25]->anio_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10021:
                                    $anio_forecast = $this->avganioforecast[8];
                                break;
                                case 10022:
                                    $anio_forecast = $this->sumanioforecast[5];
                                break;
                                case 10023:
                                    $anio_forecast = $this->sumanioforecast[6];
                                break;
                                case 10024:
                                    if(isset($this->sumanioforecast[7]->anio_forecast) && isset($this->sumanioforecast[6]->anio_forecast))
                                    {
                                        $au = $this->sumanioforecast[6]->anio_forecast;
                                        $min = $this->sumanioforecast[7]->anio_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10025:
                                    $anio_forecast = $this->sumanioforecast[7];
                                break;
                                case 10026:
                                    $anio_forecast = $this->avganioforecast[9];
                                break;
                                case 10027:
                                    $anio_forecast = $this->sumanioforecast[8];
                                break;
                                case 10028:
                                    //$anio_forecast = $this->sumanioforecast[9];
                                    //MMSA_APILAM_STACKER_Au Extraible Apilado                  
                                    //SUMAANUAL((((10033 MMSA_APILAM_STACKER_Recuperación %)/ 100) * (10031 MMSA_APILAM_STACKER_Mineral Apilado Stacker t) * (10030 MMSA_APILAM_STACKER_Ley Au g/t)) / 31.1035)                                             
                                    //10030 Ley Au MMSA_HPGR_Ley Au 
                                    //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10030 MMSA_HPGR_Ley Au g/t)                         
                                    $sumaproducto10030 = DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[forecast]
                                        where variable_id = 10030) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[forecast]
                                        where variable_id = 10031) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );  

                                    //10033 MMSA_APILAM_STACKER_Recuperación %
                                    //Promedio Ponderado Anual(10031 MMSA_HPGR_Mineral Triturado t, 10033 MMSA_APILAM_STACKER_Recuperación %)                      
                                    $sumaproducto10033 = DB::select(
                                        'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[forecast]
                                        where variable_id = 10033) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[forecast]
                                        where variable_id = 10031) as B
                                        ON A.fecha = B.fecha
                                        WHERE YEAR(A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        GROUP BY YEAR(A.fecha)',
                                        [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                    );                                     
                                    $suma10031 = $this->sumanioforecast10031; 
                                    
                                    
                                    
                                    if(isset($sumaproducto10030[0]->sumaproducto) && isset($sumaproducto10033[0]->sumaproducto) && isset($suma10031[0]->suma))
                                    {
                                        if ($suma10031[0]->suma > 0) {
                                            //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                            $recup =  $sumaproducto10033[0]->sumaproducto/$suma10031[0]->suma;
                                            $leyAu = $sumaproducto10030[0]->sumaproducto/$suma10031[0]->suma;
                                            $sumMin = $suma10031[0]->suma;
                                            $anio_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                            if($anio_forecast > 100)
                                            {
                                                return number_format(round($anio_forecast), 0, '.', ',');
                                            }
                                            else
                                            {
                                                return number_format($anio_forecast, 2, '.', ',');
                                            }
                                        }
                                        else {
                                            return '-';
                                        }
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10029:
                                    $anio_forecast = $this->avganioforecast[10];
                                break;
                                case 10030:
                                    if(isset($this->sumanioforecast[10]->anio_forecast) && isset($this->sumanioforecast[8]->anio_forecast))
                                    {
                                        $au = $this->sumanioforecast[8]->anio_forecast;
                                        $min = $this->sumanioforecast[10]->anio_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10031:
                                    $anio_forecast = $this->sumanioforecast[10];
                                break;
                                case 10032:
                                    if(isset($this->sumanioforecast[10]->anio_forecast) && (isset($this->sumanioforecast[26]->anio_forecast) && $this->sumanioforecast[26]->anio_forecast != 0))
                                    {
                                        $min = $this->sumanioforecast[10]->anio_forecast;
                                        $hs = $this->sumanioforecast[26]->anio_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10033:
                                    $anio_forecast = $this->avganioforecast[11];
                                break;
                                case 10034:
                                    $anio_forecast = $this->avganioforecast[12];
                                break;
                                case 10035:
                                    if(isset($this->sumanioforecast[13]->anio_forecast) && isset($this->sumanioforecast[11]->anio_forecast))
                                    {
                                        $au = $this->sumanioforecast[11]->anio_forecast;
                                        $min = $this->sumanioforecast[13]->anio_forecast;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10036:
                                    $anio_forecast = $this->avganioforecast[13];
                                break;
                                case 10037:
                                    $anio_forecast = $this->sumanioforecast[11];
                                break;
                                case 10038:
                                    //$anio_forecast = $this->sumanioforecast[12];
                                    //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                                        //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)       
                                                        
                                                                                            
                                                        //10035 MMSA_APILAM_TA_Ley Au g/t
                                                        //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10035 MMSA_APILAM_TA_Ley Au g/t)                       
                                                        $sumaproducto10035 = DB::select(
                                                            'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[forecast]
                                                            where variable_id = 10035) as A
                                                            INNER JOIN   
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[forecast]
                                                            where variable_id = 10039) as B
                                                            ON A.fecha = B.fecha
                                                            WHERE YEAR(A.fecha) = ?
                                                            AND  DATEPART(y, A.fecha) <=  ?
                                                            GROUP BY YEAR(A.fecha)',
                                                            [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                        );                                                                      
                                                                                            
                                                        //10036 MMSA_APILAM_TA_Recuperación %
                                                        //Promedio Ponderado Anual(10039 MMSA_APILAM_TA_Total Mineral Apilado t, 10036 MMSA_APILAM_TA_Recuperación)                      
                                                        $sumaproducto10036 = DB::select(
                                                            'SELECT YEAR(A.fecha) as year, SUM(A.valor * B.valor) as sumaproducto FROM
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[forecast]
                                                            where variable_id = 10036) as A
                                                            INNER JOIN   
                                                            (SELECT fecha, variable_id, [valor]
                                                            FROM [dbo].[forecast]
                                                            where variable_id = 10039) as B
                                                            ON A.fecha = B.fecha
                                                            WHERE YEAR(A.fecha) = ?
                                                            AND  DATEPART(y, A.fecha) <=  ?
                                                            GROUP BY YEAR(A.fecha)',
                                                            [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                                        );                                     
                                                        $suma10039 = $this->sumanioforecast10039; 
                                                        
                
                                                        if(isset($sumaproducto10035[0]->sumaproducto) && isset($sumaproducto10036[0]->sumaproducto) && isset($suma10039[0]->suma))
                                                        {
                                                            if ($suma10039[0]->suma > 0) {
                                                                //76.1538043622208379843997 0.704806345958821606296926 537286.19157985
                                                                $recup =  $sumaproducto10036[0]->sumaproducto/$suma10039[0]->suma;
                                                                $leyAu = $sumaproducto10035[0]->sumaproducto/$suma10039[0]->suma;
                                                                $sumMin = $suma10039[0]->suma;
                                                                $anio_forecast =  ($recup *  $leyAu  * $sumMin * 0.0100000) / 31.1035;
                                                                if($anio_forecast > 100)
                                                                {
                                                                    return number_format(round($anio_forecast), 0, '.', ',');
                                                                }
                                                                else
                                                                {
                                                                    return number_format($anio_forecast, 2, '.', ',');
                                                                }
                                                            }
                                                            else {
                                                                return '-';
                                                            }
                                                        }
                                                        else
                                                        {
                                                            return '-';
                                                        }
                                break;
                                case 10039:
                                    $anio_forecast = $this->sumanioforecast[13];
                                break;
                                case 10040:
                                    $anio_forecast = $this->avganioforecast[14];
                                break;
                                case 10041:
                                    if(isset($this->leyanioforecast[0]->sumaproducto) && (isset($this->leyanioforecast[0]->suma) && $this->leyanioforecast[0]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[0]->sumaproducto;
                                        $hs = $this->leyanioforecast[0]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10042:
                                    if(isset($this->leyanioforecast[1]->sumaproducto) && (isset($this->leyanioforecast[1]->suma) && $this->leyanioforecast[1]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[1]->sumaproducto;
                                        $hs = $this->leyanioforecast[1]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10043:
                                    if(isset($this->leyanioforecast[2]->sumaproducto) && (isset($this->leyanioforecast[2]->suma) && $this->leyanioforecast[2]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[2]->sumaproducto;
                                        $hs = $this->leyanioforecast[2]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10044:
                                    if(isset($this->leyanioforecast[3]->sumaproducto) && (isset($this->leyanioforecast[3]->suma) && $this->leyanioforecast[3]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[3]->sumaproducto;
                                        $hs = $this->leyanioforecast[3]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10045:
                                    $anio_forecast = $this->sumanioforecast[14];
                                break;
                                case 10046:
                                    $anio_forecast = $this->sumanioforecast[15];
                                break;
                                case 10047:
                                    $anio_forecast = $this->sumanioforecast[16];
                                break;
                                case 10048:
                                    $anio_forecast = $this->sumanioforecast[17];                            
                                    if(isset($anio_forecast->anio_forecast))
                                    {
                                        $a_forecast = $anio_forecast->anio_forecast;
                                        return number_format($a_forecast, 2, '.', ',');                                
                                    }
                                    else
                                    {
                                        return '-';
                                    }
                                break;
                                case 10049:
                                    $anio_forecast = $this->avganioforecast[15];
                                break;
                                case 10050:
                                    if(isset($this->leyanioforecast[4]->sumaproducto) && (isset($this->leyanioforecast[4]->suma) && $this->leyanioforecast[4]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[4]->sumaproducto;
                                        $hs = $this->leyanioforecast[4]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10051:
                                    if(isset($this->leyanioforecast[5]->sumaproducto) && (isset($this->leyanioforecast[5]->suma) && $this->leyanioforecast[5]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[5]->sumaproducto;
                                        $hs = $this->leyanioforecast[5]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10052:
                                    $anio_forecast = $this->sumanioforecast[18];
                                break;
                                case 10053:
                                    $anio_forecast = $this->sumanioforecast[19];
                                break;
                                case 10054:
                                    if(isset($this->leyanioforecast[6]->sumaproducto) && (isset($this->leyanioforecast[6]->suma) && $this->leyanioforecast[6]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[6]->sumaproducto;
                                        $hs = $this->leyanioforecast[6]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10055:
                                    if(isset($this->leyanioforecast[7]->sumaproducto) && (isset($this->leyanioforecast[7]->suma) && $this->leyanioforecast[7]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[7]->sumaproducto;
                                        $hs = $this->leyanioforecast[7]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10056:
                                    if(isset($this->leyanioforecast[8]->sumaproducto) && (isset($this->leyanioforecast[8]->suma) && $this->leyanioforecast[8]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[8]->sumaproducto;
                                        $hs = $this->leyanioforecast[8]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10057:
                                    if(isset($this->leyanioforecast[9]->sumaproducto) && (isset($this->leyanioforecast[9]->suma) && $this->leyanioforecast[9]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[9]->sumaproducto;
                                        $hs = $this->leyanioforecast[9]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10058:
                                    if(isset($this->leyanioforecast[10]->sumaproducto) && (isset($this->leyanioforecast[10]->suma) && $this->leyanioforecast[10]->suma != 0))
                                    {
                                        $min = $this->leyanioforecast[10]->sumaproducto;
                                        $hs = $this->leyanioforecast[10]->suma;
                                    }   
                                    else
                                    {
                                        $min = 0;
                                    } 
                                break;
                                case 10059:
                                    $anio_forecast = $this->sumanioforecast[20];
                                break;
                                case 10060:
                                    $anio_forecast = $this->sumanioforecast[21];
                                break;
                                case 10061:
                                    $anio_forecast = $this->sumanioforecast[22];
                                break;
                                case 10062:
                                    $anio_forecast = $this->sumanioforecast[23];
                                break;
                                case 10063:
                                    $anio_forecast = $this->sumanioforecast[24];
                                break;
                                case 10064:
                                    $anio_forecast = $this->sumanioforecast[25];
                                break;
                                case 10065:
                                    $anio_forecast = $this->sumanioforecast[26];
                                break;
                                case 10066:
                                    $anio_forecast = 0;
                                break;
                                case 10067:
                                    $anio_forecast = $this->sumanioforecast[27];
                                break;
                                case 10068:
                                    $anio_forecast = $this->sumanioforecast[28];
                                break;
                                case 10069:
                                    $anio_forecast = $this->sumanioforecast[29];
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
                            if (in_array($data->variable_id, $this->div))
                            {
                                if ($min > 0)
                                {
                                    $a_forecast = $min/$hs;
                                    if($a_forecast > 100)
                                    {
                                        return number_format(round($a_forecast), 0, '.', ',');
                                    }
                                    else
                                    {
                                        return number_format($a_forecast, 2, '.', ',');
                                    }
                                }
                                else
                                {
                                    return '-';
                                }
                            }
                            if(isset($anio_forecast->anio_forecast))
                            {
                                $a_forecast = $anio_forecast->anio_forecast;
                                if($a_forecast > 100 || in_array($data->variable_id, $this->percentage))
                                {
                                    return number_format(round($a_forecast), 0, '.', ',');
                                }
                                else
                                {
                                    return number_format($a_forecast, 2, '.', ',');
                                }
                            }
                            else
                            {
                                return '-';
                            }
                        })
                        ->addColumn('action', function($data)
                        {
                            $button = '';  
                            if ($data->tipo == 4)
                            {
                                $button .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" data-vbleid="'.$data->variable_id.'" class="btn-action-table edit" title="Información Variable"><i style="color:#0F62AC;" class="fa-lg fas fa-info-circle"></i></a>';
                            }
                            else
                            {
                                if (Auth::user()->hasAnyRole(['Reportes_E', 'Admin']))
                                {
                                    $button .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" data-vbleid="'.$data->variable_id.'" class="btn-action-table edit" title="Editar registro"><i style="color:#0F62AC;" class="fa-lg fa fa-edit"></i></a>';
                                }     
                                else
                                {
                                    $button .= '<a href="javascript:void(0)" name="edit" class="btn-action-table edit2" title="No tiene los permisos necesarios"><i style="color:#0F62AC;" class="fa-lg fa fa-edit"></i></a>';
                                }  
                            }                 
                            return $button;
                        })
                        ->rawColumns(['categoria','subcategoria','dia_real','dia_budget', 'dia_forecast', 'mes_real', 'mes_budget', 'mes_forecast','trimestre_real', 'trimestre_budget', 'trimestre_forecast', 'anio_real', 'anio_budget', 'anio_forecast', 'action'])
                        ->addIndexColumn()
                        ->make(true);
                    }           
                    return $tabla;
                //FIN
            }  
    }