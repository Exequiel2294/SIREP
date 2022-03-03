<?php

namespace App\Http\Controllers;

use App\Models\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class HistorialController extends Controller
{
    public function index(Request $request)
    {

        if(request()->ajax()) {
            $list = DB::table('historial')
                        ->join('data', 'historial.data_id', '=', 'data.id')
                        ->join('variable', 'data.variable_id', '=', 'variable.id')
                        ->select('variable.nombre as variable','historial.*')
                        ->get();
            return datatables()->of($list)
                    ->addIndexColumn()
                    ->make(true);
        } 
        return view('historial');
    }
}
