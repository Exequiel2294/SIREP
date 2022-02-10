<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
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
                        return $dia_porcentaje.'%';
                    })
                    ->addColumn('mes_porcentaje', function($data)
                    {
                        $mes_porcentaje = ($data->mes_real/$data->mes_budget)*100;
                        return $mes_porcentaje.'%';
                    })
                    ->addColumn('trimestre_porcentaje', function($data)
                    {   
                        $trimestre_porcentaje = ($data->trimestre_real/$data->trimestre_budget)*100;
                        return $trimestre_porcentaje.'%';
                    })
                    ->addColumn('anio_porcentaje', function($data)
                    {
                        $anio_porcentaje = ($data->anio_real/$data->anio_budget)*100;
                        return $anio_porcentaje.'%';
                    })
                    ->rawColumns(['dia_porcentaje','mes_porcentaje','trimestre_porcentaje','anio_porcentaje'])
                    ->addIndexColumn()
                    ->make(true);
        } 
    }


}
