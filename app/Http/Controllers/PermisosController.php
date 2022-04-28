<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Subcategoria;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PermisosController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Admin']);
    }

    public function index(Request $request)
    {
        $usuarios = DB::table('users')
                    ->get();
        return view('permisos',['usuarios'=>$usuarios]);
    }

    public function load(Request $request)
    {
        $id = $request->post('id');
        $variables = DB::table('permisos_variables')
                    ->join('variable', 'permisos_variables.variable_id', '=', 'variable.id')
                    ->select('permisos_variables.variable_id as id','variable.nombre as nombre','variable.descripcion as descripcion')
                    ->where('permisos_variables.user_id','=',$id)
                    ->get();
        //echo $id;
        return datatables()->of($variables)
            ->addColumn('action', function($data)
            {
                $button = ''; 
                $button .= '<a href="javascript:void(0)" name="delete" id="'.$data->id.'" class="btn-action-table delete" title="Eliminar registro"><i class="fa fa-times-circle text-danger"></i></a>';
                return $button;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function getVariables(Request $request)
    {
        $id = $request->post('id');
        // $variables = DB::table('permisos_variables')
        //             ->join('variable', 'permisos_variables.variable_id', '=', 'variable.id')
        //             ->select('permisos_variables.variable_id as id','variable.nombre as nombre','variable.descripcion as descripcion')
        //             ->where('permisos_variables.user_id','=',$id)
        //             ->get();
        $vbles = DB::table('permisos_variables')
                ->from('permisos_variables')
                ->where('user_id','=',$id)
                ->pluck('variable_id');
        $variables = DB::table('variable')
                        ->select('id','nombre','descripcion')
                        ->whereNotIn('id', $vbles)
                        ->get();
        //echo $variables;
        return datatables()->of($variables)
            ->make(true);
    }

    public function insertar(Request $request){
        $id = $request->post('id');
        $valor = $request->post('valor');
        $tam = $request->post('tam');
        for ($i=0; $i < $tam ; $i++) { 
            Permiso::create(
                [
                    'user_id' => $id,
                    'variable_id' => $valor[$i],
                    'created_at' => null,
                    'updated_at' => null,
                ]);
        }
        return;
        //echo var_dump($valor);
    }
    
}