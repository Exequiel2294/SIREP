<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Data;
use App\Models\Historial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


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
                        if($data->unidad == '%')
                        {
                            /* Promedio para unidades de medida  % */           
                            $mes_real= DB::select(
                                'SELECT MONTH(fecha) as month, AVG(valor) as mes_real
                                FROM [mansfield].[dbo].[data]
                                WHERE variable_id = ?
                                AND  MONTH(fecha) = ?
                                AND  DATEPART(y, fecha) <= ?
                                AND valor <> 0 /* Es correcto? O pueden tener valores 0? */
                                GROUP BY MONTH(fecha)', 
                                [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                            );        
                        }
                        else
                        {
                            /* Acumulación para unidades de medida distinta % */           
                            $mes_real= DB::select(
                                'SELECT MONTH(fecha) as month, SUM(valor) as mes_real
                                FROM [mansfield].[dbo].[data]
                                WHERE variable_id = ?
                                AND  MONTH(fecha) = ?
                                AND  DATEPART(y, fecha) <= ?
                                GROUP BY MONTH(fecha)', 
                                [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                            );
                        }
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
                    })
                    ->addColumn('mes_budget', function($data)
                    {          
                        if($data->unidad == '%')
                        {
                            /* Promedio para unidades de medida  % */           
                            $mes_budget= DB::select(
                                'SELECT MONTH(fecha) as month, AVG(valor) as mes_budget
                                FROM [mansfield].[dbo].[budget]
                                WHERE variable_id = ?
                                AND  MONTH(fecha) = ?
                                AND  DATEPART(y, fecha) <= ?
                                AND valor <> 0 /* Es correcto? O pueden tener valores 0? */
                                GROUP BY MONTH(fecha)', 
                                [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                            );        
                        }
                        else
                        {           
                            /* Acumulacio para unidades de medida distintas de % */    
                            $mes_budget= DB::select(
                                'SELECT MONTH(fecha) as month, SUM(valor) as mes_budget
                                FROM [mansfield].[dbo].[budget]
                                WHERE variable_id = ?
                                AND  MONTH(fecha) = ?
                                AND  DATEPART(y, fecha) <= ?
                                GROUP BY MONTH(fecha)', 
                                [$data->variable_id, date('m', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                            );
                        }
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
                            return '-' ;
                        }
                    })
                    ->addColumn('trimestre_real', function($data)
                    {              
                        if($data->unidad == '%')
                        {         
                            /* Promedio para unidades de medida  % */                    
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
                        }
                        else
                        {        
                            /* Acumulacion para unidades de medida distintas  % */                    
                            $trimestre_real= DB::select(
                                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_real
                                FROM [mansfield].[dbo].[data]
                                WHERE variable_id = ?
                                AND  DATEPART(QUARTER, fecha) = ?
                                AND  DATEPART(y, fecha) <= ?
                                GROUP BY DATEPART(QUARTER, fecha)', 
                                [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                            );
                            
                        }
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
                            return '-' ;                            
                        }
                    })
                    ->addColumn('trimestre_budget', function($data)
                    {           
                        if($data->unidad == '%')
                        {
                            /*Promedio para unidad de medida  % */            
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
                        } 
                        else
                        {
                            /* Acumulacion para unidades de medida distintas  % */            
                            $trimestre_budget= DB::select(
                                'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_budget
                                FROM [mansfield].[dbo].[budget]
                                WHERE variable_id = ?
                                AND  DATEPART(QUARTER, fecha) = ?
                                AND  DATEPART(y, fecha) <= ?
                                GROUP BY DATEPART(QUARTER, fecha)', 
                                [$data->variable_id, ceil(date('m', strtotime($this->date))/3), (int)date('z', strtotime($this->date)) + 1]
                            );
                        }
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
                            return '-' ;
                        }
                    })
                    ->addColumn('anio_real', function($data)
                    {         
                        if($data->unidad == '%')
                        {     
                            /* Promedio para unidad de medida  % */       
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
                        }   
                        else
                        {       
                            /* Acumulacion para unidades de medida distintas  % */      
                            $anio_real= DB::select(
                                'SELECT YEAR(fecha) as year, SUM(valor) as anio_real
                                FROM [mansfield].[dbo].[data]
                                WHERE variable_id = ?
                                AND  YEAR(fecha) = ?
                                AND  DATEPART(y, fecha) <= ?
                                GROUP BY YEAR(fecha)', 
                                [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                            );

                        } 
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
                            return '-' ;
                        }
                    })
                    ->addColumn('anio_budget', function($data)
                    {          
                        if($data->unidad == '%')
                        {      
                            /* Promedio para unidad de medida % */           
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
                        }
                        else
                        {         
                            /* Acumulacion para unidades de medida distintas  % */     
                            $anio_budget= DB::select(
                                'SELECT YEAR(fecha) as year, SUM(valor) as anio_budget
                                FROM [mansfield].[dbo].[budget]
                                WHERE variable_id = ?
                                AND  YEAR(fecha) = ?
                                AND  DATEPART(y, fecha) <= ?
                                GROUP BY YEAR(fecha)', 
                                [$data->variable_id, date('Y', strtotime($this->date)), (int)date('z', strtotime($this->date)) + 1]
                            );                            
                        } 
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
                            return '-' ;
                        }
                    })
                    ->addColumn('action', function($data)
                    {
                        $button = ''; 
                        $button .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" class="btn-action-table edit" title="Editar registro"><i style="color:#0F62AC;" class="fa-lg fa fa-edit"></i></a>';  
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
                Historial::create([
                    'data_id' => $id,
                    'fecha' => date('y-m-d h:i:s'),
                    'transaccion' => 'EDIT',
                    'valorviejo' => $oldvalue,
                    'valornuevo' => $newvalue,
                    'usuario' => auth()->user()->name
                ]);
                return;                
            }
        }        
    } 


}
