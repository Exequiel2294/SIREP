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
            $apilamiento = DB::table('data')
                            ->join('variable','data.variable_id','=','variable.id')
                            ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                            ->join('categoria','subcategoria.categoria_id','=','categoria.id')
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
                                'data.valor as dia_budget',
                                'data.valor as mes_budget',
                                'data.valor as trimestre_budget',
                                'data.valor as anio_budget'
                                )
                            ->get();
                            
            return datatables()->of($apilamiento)   
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
                        return $mes_real[0]->mes_real;
                    })
                    ->addColumn('mes_porcentaje', function($data)
                    {
                        $mes_porcentaje = 'asd';
                        
                        return $mes_porcentaje;
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
                        return $trimestre_real[0]->trimestre_real;
                    })
                    ->addColumn('trimestre_porcentaje', function($data)
                    {   
                        $trimestre_porcentaje = "100%";
                        return $trimestre_porcentaje;
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
                        return $anio_real[0]->anio_real;
                    })
                    ->addColumn('anio_porcentaje', function($data)
                    {
                        return '100';
                    })
                    ->addColumn('action', function($data)
                    {
                        $button = ''; 
                        $button .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" class="btn-action-table edit" title="Editar registro"><i style="color:#0F62AC;" class="fa-lg fa fa-edit"></i></a>';  
                        return $button;
                    })
                    ->rawColumns(['dia_porcentaje','mes_real','mes_porcentaje','trimestre_real','trimestre_porcentaje','anio_real','anio_porcentaje','action'])
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
