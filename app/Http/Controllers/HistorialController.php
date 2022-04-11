<?php

namespace App\Http\Controllers;

use App\Models\Area;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Models\ActiveDirectory\Group;


class HistorialController extends Controller
{
    public function index(Request $request)
    {
        if(request()->ajax()) {
            $list = DB::table('historial')
                        ->join('data', 'historial.data_id', '=', 'data.id')
                        ->join('variable', 'data.variable_id', '=', 'variable.id')
                        ->select('variable.descripcion as variable','data.fecha as fecha_data','historial.*')
                        ->get();
            return datatables()->of($list)
                    ->addColumn('valorviejo', function($data)
                    {    
                        if ($data->valorviejo != null)  
                        {
                            return number_format($data->valorviejo, 5, '.', ',');
                        }   
                        return '-';
                    })
                    ->addColumn('valornuevo', function($data)
                    {         
                        if ($data->valornuevo != null) 
                        {
                            return number_format($data->valornuevo, 5, '.', ',');
                        }
                        return '-';
                    })
                    ->rawColumns(['valorviejo','valornuevo'])
                    ->addIndexColumn()
                    ->make(true);
        } 
        return view('historial');
    }
}
