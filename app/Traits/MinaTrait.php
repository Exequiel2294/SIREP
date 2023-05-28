<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait MinaTrait {

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function TraitMinaTable(Request $request) {
        $this->date = $request->get('date');
        $this->ley = [10071, 10074, 10077, 10080, 10083, 10086, 10089, 10094, 10098, 10101, 10104, 10107]; 

        //INICIO CALCULOS REUTILIZABLES
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
                    WHERE variable_id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113, 10117)
                    AND MONTH(fecha) = '.$month.'
                    AND DATEPART(y, fecha) <= '.$daypart.'
                    AND YEAR(fecha) = '.$year.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113, 10117)) AS v
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
                    WHERE variable_id IN (10070, 10073, 10076, 10079, 10082, 10085,10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113, 10117)
                    AND DATEPART(QUARTER, fecha) = '.$quarter.'
                    AND DATEPART(y, fecha) <= '.$daypart.'
                    AND YEAR(fecha) = '.$year.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113, 10117)) AS v
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
                        'SELECT v.id AS variable_id, d.tri_real AS tri_real FROM
                        (SELECT variable_id, AVG(valor) AS tri_real
                        FROM [dbo].[data]
                        WHERE variable_id IN (10114,10115,10116)
                        AND  DATEPART(QUARTER, fecha) = '.$quarter.'
                        AND  DATEPART(y, fecha) <= '.$daypart.'
                        AND YEAR(fecha) = '.$year.'
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
                    WHERE variable_id IN (10070, 10073, 10076, 10079, 10082, 10085,10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113, 10117)
                    AND YEAR(fecha) = '.$year.'
                    AND DATEPART(y, fecha) <= '.$daypart.'
                    GROUP BY variable_id) AS d
                    RIGHT JOIN
                    (SELECT id 
                    FROM [dbo].[variable] 
                    WHERE id IN (10070, 10073, 10076, 10079, 10082, 10085, 10088, 10091, 10092, 10093, 10097, 10100, 10103, 10106, 10109, 10110, 10111, 10112, 10113, 10117)) AS v
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
                    'SELECT v.id AS variable_id, d.anio_real AS anio_real FROM
                    (SELECT variable_id, AVG(valor) AS anio_real
                    FROM [dbo].[data]
                    WHERE variable_id IN (10114,10115,10116)
                    AND  YEAR(fecha) = '.$year.'
                    AND  DATEPART(y, fecha) <= '.$daypart.'
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
        //FIN CALCULOS REUTILIZABLES

        $where = ['variable.estado' => 1, 'categoria.area_id' => 2, 'categoria.estado' => 1, 'subcategoria.estado' => 1, 'data.fecha' => $this->date];
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
                    'variable.nombre as nombre', 
                    'variable.orden as var_orden',
                    'variable.unidad as unidad',
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                            ); 
                        break;   
                        default:                        
                            if(isset($data->dia_real)) 
                            { 
                                $d_real = $data->dia_real;
                                if($d_real > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                                AND MONTH(fecha) = ?
                                AND YEAR(fecha) = ?',
                                [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]

                            ); 
                        break;
                        case 'g/t':
                        case '%':
                            $dia_budget= DB::select(
                                'SELECT valor as dia_budget
                                FROM [dbo].[budget]
                                WHERE variable_id = ?
                                AND MONTH(fecha) = ?                                
                                AND YEAR(fecha) = ?',
                                [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]

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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
                                    );
                                break;   
                            } 
                        break;
                    }
                    if(isset($dia_budget[0]->dia_budget)) 
                    { 
                        $d_budget = $dia_budget[0]->dia_budget;
                        if($d_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                ->addColumn('dia_forecast', function($data)
                {
                    switch($data->unidad)
                    {
                        case 't':
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
                            $dia_budget= DB::select(
                                'SELECT valor as dia_budget
                                FROM [dbo].[budget]
                                WHERE variable_id = ?
                                AND MONTH(fecha) = ?                                
                                AND YEAR(fecha) = ?',
                                [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]

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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
                                    );
                                break;   
                            } 
                        break;
                    }
                    if(isset($dia_budget[0]->dia_budget)) 
                    { 
                        $d_budget = $dia_budget[0]->dia_budget;
                        if($d_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                        case 10117: 
                            $mes_real = $this->summesrealton[19];
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
                        if($m_real > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                        if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                ->addColumn('mes_forecast', function($data)
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
                        if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                        case 10117: 
                            $tri_real = $this->sumtrirealton[19];
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
                        if($m_real > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                            $trimestre_budget = $this->avgtribudgetpor[0];
                        break;
                        case 10115:
                            $trimestre_budget = $this->avgtribudgetpor[1];
                        break;
                        case 10116:
                            $trimestre_budget = $this->avgtribudgetpor[2];
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
                            if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                ->addColumn('trimestre_forecast', function($data)
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
                            $trimestre_budget = $this->avgtribudgetpor[0];
                        break;
                        case 10115:
                            $trimestre_budget = $this->avgtribudgetpor[1];
                        break;
                        case 10116:
                            $trimestre_budget = $this->avgtribudgetpor[2];
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
                            if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                        case 10117: 
                            $anio_real = $this->sumaniorealton[19];
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
                        if($m_real > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                            if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                ->addColumn('anio_forecast', function($data)
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
                            if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                ->rawColumns(['categoria','subcategoria','dia_real','dia_budget','dia_forecast','mes_real','mes_budget','mes_forecast','trimestre_real','trimestre_budget','trimestre_forecast','anio_real','anio_budget','anio_forecast'])
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
                    'variable.tipo as tipo',
                    'variable.orden as var_orden',
                    'variable.unidad as unidad',
                    'variable.export as var_export',
                    'data.valor as dia_real',
                    'data.valor as anio_budget'
                    )
                ->get();
            $tabla = datatables()->of($table)  
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
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
                                WHERE  DATEPART(y, A.fecha) = ?
                                AND YEAR(A.fecha) = ?',
                                [(int)date('z', strtotime($this->date)) + 1, date('Y', strtotime($this->date))]
                            ); 
                        break;   
                        default:                        
                            if(isset($data->dia_real)) 
                            { 
                                $d_real = $data->dia_real;
                                if($d_real > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                                AND MONTH(fecha) = ?
                                AND YEAR(fecha) = ?',
                                [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]

                            ); 
                        break;
                        case 'g/t':
                        case '%':
                            $dia_budget= DB::select(
                                'SELECT valor as dia_budget
                                FROM [dbo].[budget]
                                WHERE variable_id = ?
                                AND MONTH(fecha) = ?                                
                                AND YEAR(fecha) = ?',
                                [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]

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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
                                    );
                                break;   
                            } 
                        break;
                    }
                    if(isset($dia_budget[0]->dia_budget)) 
                    { 
                        $d_budget = $dia_budget[0]->dia_budget;
                        if($d_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                ->addColumn('dia_forecast', function($data)
                {
                    switch($data->unidad)
                    {
                        case 't':
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
                            $dia_budget= DB::select(
                                'SELECT valor as dia_budget
                                FROM [dbo].[budget]
                                WHERE variable_id = ?
                                AND MONTH(fecha) = ?                                
                                AND YEAR(fecha) = ?',
                                [$data->variable_id, date('m', strtotime($this->date)), date('Y', strtotime($this->date))]

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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
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
                                        ON A.fecha = B.fecha
                                        WHERE MONTH(A.fecha) = ?
                                        AND YEAR(A.fecha) = ?',
                                        [date('m', strtotime($this->date)), date('Y', strtotime($this->date))]
                                    );
                                break;   
                            } 
                        break;
                    }
                    if(isset($dia_budget[0]->dia_budget)) 
                    { 
                        $d_budget = $dia_budget[0]->dia_budget;
                        if($d_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                        case 10117: 
                            $mes_real = $this->summesrealton[19];
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
                        if($m_real > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                        if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                ->addColumn('mes_forecast', function($data)
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
                        if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                        case 10117: 
                            $tri_real = $this->sumtrirealton[19];
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
                        if($m_real > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                            $trimestre_budget = $this->avgtribudgetpor[0];
                        break;
                        case 10115:
                            $trimestre_budget = $this->avgtribudgetpor[1];
                        break;
                        case 10116:
                            $trimestre_budget = $this->avgtribudgetpor[2];
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
                            if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                ->addColumn('trimestre_forecast', function($data)
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
                            $trimestre_budget = $this->avgtribudgetpor[0];
                        break;
                        case 10115:
                            $trimestre_budget = $this->avgtribudgetpor[1];
                        break;
                        case 10116:
                            $trimestre_budget = $this->avgtribudgetpor[2];
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
                            if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                        case 10117: 
                            $anio_real = $this->sumaniorealton[19];
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
                        if($m_real > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                            if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                ->addColumn('anio_forecast', function($data)
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
                            if($m_budget > 100 || in_array($data->variable_id, [10114, 10115, 10116]))
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
                ->addColumn('action', function($data)
                {
                    $button = '';  
                    if ($data->tipo == 8)
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
                ->rawColumns(['categoria','subcategoria','dia_real','dia_budget','dia_forecast','mes_real','mes_budget', 'mes_forecast', 'trimestre_real', 'trimestre_budget', 'trimestre_forecast' ,'anio_real','anio_budget','anio_forecast','action'])
                ->addIndexColumn()
                ->make(true);
        }
                         
        return $tabla;
    }

}