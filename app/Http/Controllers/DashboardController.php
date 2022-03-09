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
                                'categoria.nombre as categoria',
                                'subcategoria.nombre as subcategoria',
                                'variable.nombre as variable', 
                                'variable.unidad as unidad',
                                'data.valor as dia_real',
                                'budget.valor as dia_budget',
                                'data.valor as anio_budget'
                                )
                            ->get();
                            
            return datatables()->of($table)  
                    ->addColumn('dia_real', function($data)
                    {  
                        return number_format($data->dia_real, 2, ',', '.');
                    })
                    ->addColumn('dia_budget', function($data)
                    {          
                        return number_format($data->dia_budget, 2, ',', '.');
                    }) 
                    ->addColumn('mes_real', function($data)
                    {                        
                        $mes_real= DB::select(
                            'SELECT MONTH(fecha) as month, SUM(valor) as mes_real
                            FROM [mansfield].[dbo].[data]
                            WHERE variable_id = ?
                            AND  MONTH(fecha) = ?
                            GROUP BY MONTH(fecha)', 
                            [$data->variable_id, date('m', strtotime($this->date))]
                        );
                        return number_format($mes_real[0]->mes_real, 2, ',', '.');
                    })
                    ->addColumn('mes_budget', function($data)
                    {                        
                        $mes_budget= DB::select(
                            'SELECT MONTH(fecha) as month, SUM(valor) as mes_budget
                            FROM [mansfield].[dbo].[budget]
                            WHERE variable_id = ?
                            AND  MONTH(fecha) = ?
                            GROUP BY MONTH(fecha)', 
                            [$data->variable_id, date('m', strtotime($this->date))]
                        );
                        if(isset($mes_budget[0]->mes_budget))
                        {
                            return number_format($mes_budget[0]->mes_budget, 2, ',', '.');
                        }
                        else
                        {
                            return '-' ;
                        }
                    })
                    ->addColumn('trimestre_real', function($data)
                    {                        
                        $trimestre_real= DB::select(
                            'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_real
                            FROM [mansfield].[dbo].[data]
                            WHERE variable_id = ?
                            AND  DATEPART(QUARTER, fecha) = ?
                            GROUP BY DATEPART(QUARTER, fecha)', 
                            [$data->variable_id, ceil(date('m', strtotime($this->date))/3)]
                        );
                        return number_format($trimestre_real[0]->trimestre_real, 2, ',', '.');
                    })
                    ->addColumn('trimestre_budget', function($data)
                    {                        
                        $trimestre_budget= DB::select(
                            'SELECT DATEPART(QUARTER, fecha) as quarter, SUM(valor) as trimestre_budget
                            FROM [mansfield].[dbo].[budget]
                            WHERE variable_id = ?
                            AND  DATEPART(QUARTER, fecha) = ?
                            GROUP BY DATEPART(QUARTER, fecha)', 
                            [$data->variable_id, ceil(date('m', strtotime($this->date))/3)]
                        );
                        if(isset($trimestre_budget[0]->trimestre_budget))
                        {
                            return number_format($trimestre_budget[0]->trimestre_budget, 2, ',', '.');
                        }
                        else
                        {
                            return '-' ;
                        }
                    })
                    ->addColumn('anio_real', function($data)
                    {                        
                        $anio_real= DB::select(
                            'SELECT YEAR(fecha) as year, SUM(valor) as anio_real
                            FROM [mansfield].[dbo].[data]
                            WHERE variable_id = ?
                            AND  YEAR(fecha) = ?
                            GROUP BY YEAR(fecha)', 
                            [$data->variable_id, date('Y', strtotime($this->date))]
                        );
                        return number_format($anio_real[0]->anio_real, 2, ',', '.');
                    })
                    ->addColumn('anio_budget', function($data)
                    {                        
                        $anio_budget= DB::select(
                            'SELECT YEAR(fecha) as year, SUM(valor) as anio_budget
                            FROM [mansfield].[dbo].[budget]
                            WHERE variable_id = ?
                            AND  YEAR(fecha) = ?
                            GROUP BY YEAR(fecha)', 
                            [$data->variable_id, date('Y', strtotime($this->date))]
                        );
                        if(isset($anio_budget[0]->anio_budget))
                        {
                            return number_format($anio_budget[0]->anio_budget, 2, ',', '.');
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
                    ->rawColumns(['dia_real','dia_budget','dia_porcentaje','mes_real','mes_budget','trimestre_real','anio_real','action'])
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
                    'fecha' => Carbon::now(),
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
