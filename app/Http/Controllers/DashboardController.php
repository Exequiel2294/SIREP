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

    public function apilamientotable(Request $request)
    {
        if(request()->ajax()) {
            $where = ['variables.estado' => 1];
            $apilamiento = DB::table('data')
                            ->join('variables','data.variable_id','=','variables.id')
                            
                            ->select(
                                'variables.nivel1 as nivel1',
                                'variables.nivel2 as nivel2',
                                'variables.nombre as variable', 
                                'variables.unidad as unidad',
                                'data.valor as dia_real',
                                'data.valor as dia_budget',
                                'data.valor as dia_porcentaje',
                                'data.valor as mes_real',
                                'data.valor as mes_budget',
                                'data.valor as mes_porcentaje',
                                'data.valor as trimestre_real',
                                'data.valor as trimestre_budget',
                                'data.valor as trimestre_porcentaje',
                                'data.valor as anio_real',
                                'data.valor as anio_budget',
                                'data.valor as anio_porcentaje'
                                )
                            ->get();
                            
            return datatables()->of($apilamiento)
                    ->addIndexColumn()
                    ->make(true);
        } 
    }
}
