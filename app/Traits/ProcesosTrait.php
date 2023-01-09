<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait ProcesosTrait {

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function TraitProcesosTable(Request $request) {
        $this->date = $request->get('date');
        $this->ley = [10004, 10010, 10024, 10030, 10035];
        $this->div = [10006, 10013, 10020, 10032, 10041, 10042, 10043, 10044, 10050, 10051, 10054, 10055, 10056, 10057, 10058];

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
            $year = (int)date('Y', strtotime($this->date));
            $quarter = ceil(date('m', strtotime($this->date))/3);
            $month = (int)date('m', strtotime($this->date)); 
            $day = (int)date('d', strtotime($this->date));
            $daypart = (int)date('z', strtotime($this->date)) + 1;
            //MES REAL
                $this->summesreal10005 = 
                DB::select(
                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10005
                    AND  MONTH(fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY MONTH(fecha)', 
                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                );
                $this->summesreal10011 = 
                DB::select(
                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10011
                    AND  MONTH(fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY MONTH(fecha)', 
                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                ); 
                $this->summesreal10019 = 
                DB::select(
                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10019
                    AND  MONTH(fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY MONTH(fecha)', 
                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                ); 
                $this->summesreal10039 = 
                DB::select(
                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10039
                    AND  MONTH(fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY MONTH(fecha)', 
                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                );
                $this->summesreal10045 =
                DB::select(
                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10045
                    AND  MONTH(fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY MONTH(fecha)', 
                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                );
                $this->summesreal10052 =
                DB::select(
                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10052
                    AND  MONTH(fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY MONTH(fecha)', 
                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                );
                $this->summesreal10061 =
                DB::select(
                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10061
                    AND  MONTH(fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY MONTH(fecha)', 
                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                ); 
            //MES BUDGET
                
                $this->summesbudget = 
                DB::select(
                    'SELECT v.id AS variable_id, ((d.valor/DAY(d.fecha))*'.$day.') AS mes_budget FROM
                    (SELECT variable_id, fecha, valor
                    FROM [dbo].[budget]
                    WHERE variable_id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                    AND MONTH(fecha) = '.$month.'                        
                    AND YEAR(fecha) = '.$year.') AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                ); 
                $this->avgmesbudget =
                DB::select(
                    'SELECT v.id AS variable_id, d.valor AS mes_budget FROM
                    (SELECT variable_id, valor
                    FROM [dbo].[budget]
                    WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                    AND  MONTH(fecha) = '.$month.'
                    AND YEAR(fecha) = '.$year.') AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );
                $this->leymesbudget =
                DB::select(
                    'SELECT 10041 as variable_id, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10045) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10041) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10042, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10045) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10042) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10043, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10045) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10043) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10044, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10045) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10044) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10050, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10052) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10050) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10051, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10052) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10051) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10054, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10061) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10054) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10055, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10059) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10055) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10056, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10060) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10056) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10057, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10061) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10057) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10058, (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.') as sumaproducto, (A.valor/DAY(A.fecha)) * '.$day.' as suma FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10061) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10058) as B
                    ON A.fecha = B.fecha
                    WHERE MONTH(A.fecha) = '.$month.'
                    AND YEAR(A.fecha) = '.$year.''
                );
                
            //TRIMESTRE REAL
                $this->sumtrireal10005 = 
                DB::select(
                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10005
                    AND  DATEPART(QUARTER, fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY DATEPART(QUARTER, fecha)', 
                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                ); 
                $this->sumtrireal10011 = 
                DB::select(
                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10011
                    AND  DATEPART(QUARTER, fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY DATEPART(QUARTER, fecha)', 
                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                ); 
                $this->sumtrireal10019 = 
                DB::select(
                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10019
                    AND  DATEPART(QUARTER, fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY DATEPART(QUARTER, fecha)', 
                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                ); 
                $this->sumtrireal10039 = 
                DB::select(
                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10039
                    AND  DATEPART(QUARTER, fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY DATEPART(QUARTER, fecha)', 
                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                );
                $this->sumtrireal10045 =
                DB::select(
                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10045
                    AND  DATEPART(QUARTER, fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY DATEPART(QUARTER, fecha)', 
                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                );
                $this->sumtrireal10052 =
                DB::select(
                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10052
                    AND  DATEPART(QUARTER, fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY DATEPART(QUARTER, fecha)', 
                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                );
                $this->sumtrireal10061 =
                DB::select(
                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                    FROM [dbo].[data]
                    WHERE variable_id = 10061
                    AND  DATEPART(QUARTER, fecha) = ?
                    AND  DATEPART(y, fecha) <= ?
                    AND YEAR(fecha) = ?
                    GROUP BY DATEPART(QUARTER, fecha)', 
                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                );
            //TRIMESTRE BUDGET                
                $this->sumtribudget = 
                DB::select(
                    'SELECT v.id AS variable_id, d.valor as tri_budget
                    FROM
                    (SELECT variable_id, 
                    SUM(CASE	
                        WHEN MONTH(fecha) < '.$month.' THEN valor
                        WHEN MONTH(fecha) = '.$month.' THEN (valor/DAY(fecha)) * '.$day.'
                    END) AS valor
                    FROM [dbo].[budget]
                    WHERE variable_id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                    AND DATEPART(QUARTER, fecha) = '.$quarter.'
                    AND MONTH(fecha) <= '.$month.'
                    AND YEAR(fecha) = '.$year.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );

                $this->avgtribudget =
                DB::select(
                    'SELECT v.id AS variable_id, (d.valor/(CASE WHEN d.dias = 0 THEN NULL ELSE d.dias END)) AS tri_budget FROM
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
                    WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                    AND DATEPART(QUARTER, fecha) = '.$quarter.'
                    AND MONTH(fecha) <= '.$month.'
                    AND YEAR(fecha) = '.$year.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                    ON d.variable_id = v.id
                    ORDER BY id ASC'
                );

                $this->leytribudget =
                DB::select(
                    'SELECT 10041 as variable_id, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10045) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10041) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10042, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10045) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10042) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10043, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10045) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10043) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10044, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10045) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10044) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10050, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10052) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10050) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10051, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10052) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10051) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10054, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10061) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10054) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10055, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10059) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10055) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10056, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10060) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10056) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10057, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10061) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10057) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.'
                    UNION 
                    SELECT 10058, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                        WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                    END) as sumaproducto, 
                    SUM(CASE
                        WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                        WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                    END) as suma 
                    FROM
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10061) as A
                    INNER JOIN   
                    (SELECT fecha, valor
                    FROM [dbo].[budget]
                    where variable_id = 10058) as B
                    ON A.fecha = B.fecha
                    WHERE DATEPART(QUARTER, A.fecha) = '.$quarter.'
                    AND MONTH(A.fecha) <= '.$month.'
                    AND YEAR(A.fecha) = '.$year.''
                );
                
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
            //ANO BUDGET
                //new
                    $this->sumaniobudget = 
                    DB::select(
                        'SELECT v.id AS variable_id, d.valor as anio_budget
                        FROM
                        (SELECT variable_id, 
                        SUM(CASE	
                            WHEN MONTH(fecha) < '.$month.' THEN valor
                            WHEN MONTH(fecha) = '.$month.' THEN (valor/DAY(fecha)) * '.$day.'
                        END) AS valor
                        FROM [dbo].[budget]
                        WHERE variable_id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)
                        AND MONTH(fecha) <= '.$month.'
                        AND YEAR(fecha) = '.$year.'
                        GROUP BY variable_id) AS d
                        RIGHT JOIN
                        (SELECT id 
                        FROM [dbo].[variable] 
                        WHERE id IN (10002, 10005, 10008, 10011, 10019, 10022, 10023, 10025, 10027, 10028, 10031, 10037, 10038, 10039, 10045, 10046, 10047, 10048, 10052, 10053, 10059, 10060, 10061, 10062, 10063, 10064, 10065, 10067, 10068, 10069)) AS v
                        ON d.variable_id = v.id
                        ORDER BY id ASC'
                    );

                    $this->avganiobudget =
                    DB::select(
                        'SELECT v.id AS variable_id, (d.valor/(CASE WHEN d.dias = 0 THEN NULL ELSE d.dias END)) AS anio_budget FROM
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
                        WHERE variable_id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)
                        AND MONTH(fecha) <= '.$month.'
                        AND YEAR(fecha) = '.$year.'
                        GROUP BY variable_id) AS d
                        RIGHT JOIN
                        (SELECT id 
                        FROM [dbo].[variable] 
                        WHERE id IN (10003,10007,10009,10012,10014,10015,10017,10018,10021,10026,10029,10033,10034,10036,10040,10049)) AS v
                        ON d.variable_id = v.id
                        ORDER BY id ASC'
                    );

                    $this->leyaniobudget =
                    DB::select(
                        'SELECT 10041 as variable_id, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10041) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10042, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10042) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10043, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10043) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10044, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10045) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10044) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10050, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10050) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10051, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10052) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10051) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10054, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10054) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10055, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10059) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10055) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10056, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10060) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10056) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10057, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10057) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.'
                        UNION 
                        SELECT 10058, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * DAY(A.fecha))
                            WHEN MONTH(A.fecha) = '.$month.' THEN (((A.valor/DAY(A.fecha)) * B.valor) * '.$day.')
                        END) as sumaproducto, 
                        SUM(CASE
                            WHEN MONTH(A.fecha) < '.$month.' THEN (A.valor/DAY(A.fecha)) * DAY(A.fecha)
                            WHEN MONTH(A.fecha) = '.$month.' THEN (A.valor/DAY(A.fecha)) * '.$day.'
                        END) as suma 
                        FROM
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10061) as A
                        INNER JOIN   
                        (SELECT fecha, valor
                        FROM [dbo].[budget]
                        where variable_id = 10058) as B
                        ON A.fecha = B.fecha
                        AND MONTH(A.fecha) <= '.$month.'
                        AND YEAR(A.fecha) = '.$year.''
                    );
                //end new
                /*OLD
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
                END OLD */
        //FIN CALCULOS REUTILIZABLES

        $where = ['variable.estado' => 1, 'categoria.area_id' => 1, 'categoria.estado' => 1, 'subcategoria.estado' => 1, 'data.fecha' => $this->date];

        if ($request->get('type') == 1)
        {
            $table = DB::table('data')
                ->join('variable','data.variable_id','=','variable.id')
                ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                ->join('categoria','subcategoria.categoria_id','=','categoria.id')
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
                    'data.valor as anio_budget'
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
                        case 'oz':
                        case 'h':
                        case 'm3':                                         
                            switch($data->variable_id)
                            { 
                                case 10048:  
                                    $dia_budget= DB::select(
                                        'SELECT valor/DAY(fecha) as dia_budget
                                        FROM [dbo].[budget]
                                        WHERE variable_id = ?
                                        AND MONTH(fecha) = ?
                                        AND YEAR(fecha) = ?',
                                        [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
                                    );                                    
                                    if(isset($dia_budget[0]->dia_budget)) 
                                    { 
                                        $d_budget = $dia_budget[0]->dia_budget;
                                        return number_format($d_budget, 2, '.', ',');                                        
                                    }        
                                    else
                                    {
                                        return '-';
                                    } 
                                break;
                            } 
                            $dia_budget= DB::select(
                                'SELECT valor/DAY(fecha) as dia_budget
                                FROM [dbo].[budget]
                                WHERE variable_id = ?
                                AND MONTH(fecha) = ?
                                AND YEAR(fecha) = ?',
                                [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
                            ); 
                        break;
                        case 'g/t':
                        case '%':
                        case 't/h':
                        case 'mm':
                        case 'ppm':
                        case 'kg/t':
                            $dia_budget= DB::select(
                                'SELECT valor as dia_budget
                                FROM [dbo].[budget]
                                WHERE variable_id = ?
                                AND MONTH(fecha) = ?
                                AND YEAR(fecha) = ?',
                                [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]

                            );
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(fecha) = ?
                                    GROUP BY MONTH(fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10025
                                    AND  MONTH(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY MONTH(fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A1.fecha) = ?
                                    GROUP BY MONTH(A1.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A1.fecha) = ?
                                    GROUP BY MONTH(A1.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10059
                                    AND  MONTH(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY MONTH(fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10060
                                    AND  MONTH(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY MONTH(fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;
                                case 10008:
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;                                 
                                case 10037:
                                    //10037: MMSA_APILAM_TA_Total Au Apilado (oz)                  
                                    //SUMATORIA MENSUAL(((10039 MMSA_APILAM_TA_Total Mineral Apilado (t))*(10035 MMSA_APILAM_TA_Ley Au (g/t))) / 31.1035)                                    
                                    $mes_real = 
                                    DB::select(
                                        'SELECT MONTH(A.fecha), SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10039) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10035) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;
                                case 10028:
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;                                     
                                case 10038:
                                    //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                    //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)                              
                                    $mes_real = 
                                    DB::select(
                                        'SELECT MONTH(A.fecha), SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as mes_real FROM
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
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;  
                                case 10048:                                    
                                    $mes_real= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as mes_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND YEAR(fecha) = ?
                                        GROUP BY MONTH(fecha)', 
                                        [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );  
                                    if(isset($mes_real[0]->mes_real))
                                    {
                                        $m_real = $mes_real[0]->mes_real;
                                        return number_format($m_real, 2, '.', ',');                                        
                                    }
                                    else
                                    {
                                        return '-';
                                    }
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;
                                default:
                                    $mes_real= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as mes_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND YEAR(fecha) = ?
                                        GROUP BY MONTH(fecha)', 
                                        [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(fecha) = ?
                                    AND valor <> 0 
                                    GROUP BY MONTH(fecha)', 
                                    [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                            );                                     
                                            $suma2= DB::select(
                                                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                FROM [dbo].[data]
                                                WHERE variable_id = 10065
                                                AND  MONTH(fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                AND YEAR(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10005
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(fecha) = ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10025
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A1.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A1.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A1.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A1.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10059
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10060
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;
                                case 10008: 
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;     
                                case 10037:
                                    //10037: MMSA_APILAM_TA_Total Au Apilado (oz)                  
                                    //SUMATORIA TRIMESTRAL(((10039 MMSA_APILAM_TA_Total Mineral Apilado (t))*(10035 MMSA_APILAM_TA_Ley Au (g/t))) / 31.1035)                                    
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM((A.valor * B.valor)/31.1035) as trimestre_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10039) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10035) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;
                                case 10028:
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;   
                                case 10038:
                                    //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                    //SUMATRIMESTRAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)                                 
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as trimestre_real FROM
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
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break; 
                                case 10048:
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND YEAR(fecha) = ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                    if(isset($trimestre_real[0]->trimestre_real))
                                    {
                                        $t_real = $trimestre_real[0]->trimestre_real;
                                        return number_format($t_real, 2, '.', ',');                                        
                                    }
                                    else
                                    {
                                        return '-';
                                    }
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;
                                default:
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND YEAR(fecha) = ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(fecha) = ?
                                    AND valor <> 0
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                            );                                     
                                            $suma2= DB::select(
                                                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                FROM [dbo].[data]
                                                WHERE variable_id = 10065
                                                AND  DATEPART(QUARTER, fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                AND YEAR(fecha) = ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    else
                                    {
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
                                    }
                                break;
                                case 10038: 
                                    if ($this->date == '2022-12-31')
                                    {
                                        return number_format(round(107362), 0, '.', ',');
                                    }
                                    else{
                                        //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                        //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)       
                                        $anio_real= DB::select(
                                            'SELECT YEAR(A.fecha) as year, SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as anio_real FROM
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
                                            WHERE YEAR(A.fecha) = ?
                                            AND  DATEPART(y, A.fecha) <=  ?
                                            GROUP BY YEAR(A.fecha)',
                                            [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                        ); 
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
                            $anio_budget = $this->sumaniobudget[9];
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
                            $anio_budget = $this->sumaniobudget[12];
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
                })
                ->rawColumns(['categoria','subcategoria','dia_real','dia_budget','mes_real','mes_budget','trimestre_real','anio_real'])
                ->make(true);
        }
        else
        {
            $table = DB::table('data')
                ->join('variable','data.variable_id','=','variable.id')
                ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                ->join('categoria','subcategoria.categoria_id','=','categoria.id')
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
                        case 'oz':
                        case 'h':
                        case 'm3':                                         
                            switch($data->variable_id)
                            { 
                                case 10048:  
                                    $dia_budget= DB::select(
                                        'SELECT valor/DAY(fecha) as dia_budget
                                        FROM [dbo].[budget]
                                        WHERE variable_id = ?
                                        AND MONTH(fecha) = ?
                                        AND YEAR(fecha) = ?',
                                        [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
                                    );                                    
                                    if(isset($dia_budget[0]->dia_budget)) 
                                    { 
                                        $d_budget = $dia_budget[0]->dia_budget;
                                        return number_format($d_budget, 2, '.', ',');                                        
                                    }        
                                    else
                                    {
                                        return '-';
                                    } 
                                break;
                            } 
                            $dia_budget= DB::select(
                                'SELECT valor/DAY(fecha) as dia_budget
                                FROM [dbo].[budget]
                                WHERE variable_id = ?
                                AND MONTH(fecha) = ?
                                AND YEAR(fecha) = ?',
                                [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
                            ); 
                        break;
                        case 'g/t':
                        case '%':
                        case 't/h':
                        case 'mm':
                        case 'ppm':
                        case 'kg/t':
                            $dia_budget= DB::select(
                                'SELECT valor as dia_budget
                                FROM [dbo].[budget]
                                WHERE variable_id = ?
                                AND MONTH(fecha) = ?
                                AND YEAR(fecha) = ?',
                                [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]

                            );
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(fecha) = ?
                                    GROUP BY MONTH(fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10025
                                    AND  MONTH(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY MONTH(fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A1.fecha) = ?
                                    GROUP BY MONTH(A1.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A1.fecha) = ?
                                    GROUP BY MONTH(A1.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10059
                                    AND  MONTH(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY MONTH(fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10060
                                    AND  MONTH(fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY MONTH(fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY MONTH(A.fecha)', 
                                    [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;
                                case 10008:
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;                                 
                                case 10037:
                                    //10037: MMSA_APILAM_TA_Total Au Apilado (oz)                  
                                    //SUMATORIA MENSUAL(((10039 MMSA_APILAM_TA_Total Mineral Apilado (t))*(10035 MMSA_APILAM_TA_Ley Au (g/t))) / 31.1035)                                    
                                    $mes_real = 
                                    DB::select(
                                        'SELECT MONTH(A.fecha), SUM((A.valor * B.valor)/31.1035) as mes_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10039) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10035) as B
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;
                                case 10028:
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;                                     
                                case 10038:
                                    //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                    //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)                              
                                    $mes_real = 
                                    DB::select(
                                        'SELECT MONTH(A.fecha), SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as mes_real FROM
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
                                        WHERE MONTH(A.fecha) =  ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;  
                                case 10048:                                    
                                    $mes_real= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as mes_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND YEAR(fecha) = ?
                                        GROUP BY MONTH(fecha)', 
                                        [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );  
                                    if(isset($mes_real[0]->mes_real))
                                    {
                                        $m_real = $mes_real[0]->mes_real;
                                        return number_format($m_real, 2, '.', ',');                                        
                                    }
                                    else
                                    {
                                        return '-';
                                    }
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY MONTH(A.fecha)', 
                                        [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    ); 
                                break;
                                default:
                                    $mes_real= DB::select(
                                        'SELECT MONTH(fecha) as month, SUM(valor) as mes_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  MONTH(fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND YEAR(fecha) = ?
                                        GROUP BY MONTH(fecha)', 
                                        [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(fecha) = ?
                                    AND valor <> 0 
                                    GROUP BY MONTH(fecha)', 
                                    [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                            );                                     
                                            $suma2= DB::select(
                                                'SELECT MONTH(fecha) as month, SUM(valor) as suma
                                                FROM [dbo].[data]
                                                WHERE variable_id = 10065
                                                AND  MONTH(fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                AND YEAR(fecha) = ?
                                                GROUP BY MONTH(fecha)', 
                                                [date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10005
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(fecha) = ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10025
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A1.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A1.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A1.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A1.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10059
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                );                                     
                                $suma= DB::select(
                                    'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                    FROM [dbo].[data]
                                    WHERE variable_id = 10060
                                    AND  DATEPART(QUARTER, fecha) = ?
                                    AND  DATEPART(y, fecha) <= ?
                                    AND YEAR(fecha) = ?
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(A.fecha) = ?
                                    GROUP BY DATEPART(QUARTER, A.fecha)', 
                                    [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;
                                case 10008: 
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;     
                                case 10037:
                                    //10037: MMSA_APILAM_TA_Total Au Apilado (oz)                  
                                    //SUMATORIA TRIMESTRAL(((10039 MMSA_APILAM_TA_Total Mineral Apilado (t))*(10035 MMSA_APILAM_TA_Ley Au (g/t))) / 31.1035)                                    
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM((A.valor * B.valor)/31.1035) as trimestre_real FROM
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10039) as A
                                        INNER JOIN   
                                        (SELECT fecha, variable_id, [valor]
                                        FROM [dbo].[data]
                                        where variable_id = 10035) as B
                                        ON A.fecha = B.fecha
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;
                                case 10028:
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;   
                                case 10038:
                                    //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                    //SUMATRIMESTRAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)                                 
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, A.fecha) as quarter, SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as trimestre_real FROM
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
                                        WHERE DATEPART(QUARTER, A.fecha) = ?
                                        AND  DATEPART(y, A.fecha) <=  ?
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break; 
                                case 10048:
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND YEAR(fecha) = ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                    if(isset($trimestre_real[0]->trimestre_real))
                                    {
                                        $t_real = $trimestre_real[0]->trimestre_real;
                                        return number_format($t_real, 2, '.', ',');                                        
                                    }
                                    else
                                    {
                                        return '-';
                                    }
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
                                        AND YEAR(A.fecha) = ?
                                        GROUP BY DATEPART(QUARTER, A.fecha)', 
                                        [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                    );
                                break;
                                default:
                                    $trimestre_real= DB::select(
                                        'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_real
                                        FROM [dbo].[data]
                                        WHERE variable_id = ?
                                        AND  DATEPART(QUARTER, fecha) = ?
                                        AND  DATEPART(y, fecha) <= ?
                                        AND YEAR(fecha) = ?
                                        GROUP BY DATEPART(QUARTER, fecha)', 
                                        [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    AND YEAR(fecha) = ?
                                    AND valor <> 0
                                    GROUP BY DATEPART(QUARTER, fecha)', 
                                    [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                                AND YEAR(fecha) = ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                                            );                                     
                                            $suma2= DB::select(
                                                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as suma
                                                FROM [dbo].[data]
                                                WHERE variable_id = 10065
                                                AND  DATEPART(QUARTER, fecha) = ?
                                                AND  DATEPART(y, fecha) <= ?
                                                AND YEAR(fecha) = ?
                                                GROUP BY DATEPART(QUARTER, fecha)', 
                                                [ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                    else
                                    {
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
                                    }
                                break; 
                                case 10038: 
                                    if ($this->date == '2022-12-31')
                                    {
                                        return number_format(round(107362), 0, '.', ',');
                                    }
                                    else{
                                        //10038 MMSA_APILAM_TA_Total Au Extraible Apilado (oz)                  
                                        //SUMAMENSUAL((((10036 MMSA_APILAM_TA_Recuperación %)* 100) * (10039 MMSA_APILAM_TA_Total Mineral Apilado t) * (10035 MMSA_APILAM_TA_Ley Au g/t)) / 31.1035)       
                                        $anio_real= DB::select(
                                            'SELECT YEAR(A.fecha) as year, SUM(((A.valor/100) * B.valor * C.valor)/31.1035) as anio_real FROM
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
                                            WHERE YEAR(A.fecha) = ?
                                            AND  DATEPART(y, A.fecha) <=  ?
                                            GROUP BY YEAR(A.fecha)',
                                            [date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                                        ); 
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
                            $anio_budget = $this->sumaniobudget[9];
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
                            $anio_budget = $this->sumaniobudget[12];
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
                ->rawColumns(['categoria','subcategoria','dia_real','dia_budget','dia_porcentaje','mes_real','mes_budget','trimestre_real','anio_real','action'])
                ->addIndexColumn()
                ->make(true);
        }
                         
        return $tabla;
    }

}