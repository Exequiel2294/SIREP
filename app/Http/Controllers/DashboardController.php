<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Data;
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
            $date = $request->get('fecha');
            $where = ['variable.estado' => 1, 'categoria.area_id' => 1, 'categoria.estado' => 1, 'subcategoria.estado' => 1, 'data.fecha' => $date];
            $apilamiento = DB::table('data')
                            ->join('variable','data.variable_id','=','variable.id')
                            ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')
                            ->join('categoria','subcategoria.categoria_id','=','categoria.id')
                            ->where($where)
                            ->select(
                                'data.id as id',
                                'data.fecha as fecha',
                                'categoria.nombre as categoria',
                                'subcategoria.nombre as subcategoria',
                                'variable.nombre as variable', 
                                'variable.unidad as unidad',
                                'data.valor as dia_real',
                                'data.valor as dia_budget',
                                'data.valor as mes_real',
                                'data.valor as mes_budget',
                                'data.valor as trimestre_real',
                                'data.valor as trimestre_budget',
                                'data.valor as anio_real',
                                'data.valor as anio_budget',
                                )
                            ->get();
                            
            return datatables()->of($apilamiento)
                    ->addColumn('dia_porcentaje', function($data)
                    {
                        $dia_porcentaje = ($data->dia_real/$data->dia_budget)*100;
                        if ($dia_porcentaje <= 90)
                        {
                            return '<div class="percentage_red">'.$dia_porcentaje.'%</div>';
                        }
                        if ($dia_porcentaje > 90 && $dia_porcentaje<=95)
                        {
                            return '<div class="percentage_yellow">'.$dia_porcentaje.'%</div>';
                        }
                        if ($dia_porcentaje > 95)
                        {
                            return '<div class="percentage_green">'.$dia_porcentaje.'%</div>';
                        }
                        return $dia_porcentaje;
                    })
                    ->addColumn('mes_porcentaje', function($data)
                    {
                        $mes_porcentaje = ($data->mes_real/$data->mes_budget)*100;
                        if ($mes_porcentaje <= 90)
                        {
                            return '<div class="percentage_red">'.$mes_porcentaje.'%</div>';
                        }
                        if ($mes_porcentaje > 90 && $mes_porcentaje<=95)
                        {
                            return '<div class="percentage_yellow">'.$mes_porcentaje.'%</div>';
                        }
                        if ($mes_porcentaje > 95)
                        {
                            return '<div class="percentage_green">'.$mes_porcentaje.'%</div>';
                        }
                        return $mes_porcentaje;
                    })
                    ->addColumn('trimestre_porcentaje', function($data)
                    {   
                        $trimestre_porcentaje = ($data->mes_real/$data->mes_budget)*100;
                        if ($trimestre_porcentaje <= 90)
                        {
                            return '<div class="percentage_red">'.$trimestre_porcentaje.'%</div>';
                        }
                        if ($trimestre_porcentaje > 90 && $trimestre_porcentaje<=95)
                        {
                            return '<div class="percentage_yellow">'.$trimestre_porcentaje.'%</div>';
                        }
                        if ($trimestre_porcentaje > 95)
                        {
                            return '<div class="percentage_green">'.$trimestre_porcentaje.'%</div>';
                        }
                        return $trimestre_porcentaje;
                    })
                    ->addColumn('anio_porcentaje', function($data)
                    {
                        $anio_porcentaje = ($data->anio_real/$data->anio_budget)*100;
                        if ($anio_porcentaje <= 90)
                        {
                            return '<div class="percentage_red">'.$anio_porcentaje.'%</div>';
                        }
                        if ($anio_porcentaje > 90 && $anio_porcentaje<=95)
                        {
                            return '<div class="percentage_yellow">'.$anio_porcentaje.'%</div>';
                        }
                        if ($anio_porcentaje > 95)
                        {
                            return '<div class="percentage_green">'.$anio_porcentaje.'%</div>';
                        }
                        return $anio_porcentaje;
                    })
                    ->addColumn('action', function($data)
                    {
                        $button = ''; 
                        $button .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" class="btn-action-table edit" title="Editar registro"><i class="fa fa-edit"></i></a>';  
                        return $button;
                    })
                    ->rawColumns(['dia_porcentaje','mes_porcentaje','trimestre_porcentaje','anio_porcentaje','action'])
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
                Data::where('id',$id)
                    ->update(
                    [
                        'valor' => $request->get('valor')
                    ]);
                return;                
            }
        }        
    } 


}
