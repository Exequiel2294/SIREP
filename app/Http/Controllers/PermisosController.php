<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Subcategoria;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Permiso;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PermisosController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Admin']);
    }

    public function index()
    {
        $usuarios = DB::table('users')
                    ->get();
        return view('permisos',['usuarios'=>$usuarios]);
    }

    public function getuservbles(Request $request)
    {
        $id = $request->post('id');
        $variables = DB::table('permisos_variables')
                    ->join('variable', 'permisos_variables.variable_id', '=', 'variable.id')
                    ->select('permisos_variables.variable_id as id','variable.nombre as nombre','variable.descripcion as descripcion')
                    ->where('permisos_variables.user_id','=',$id)
                    ->get();

        return datatables()->of($variables)
            ->addIndexColumn()
            ->make(true);
    }

    public function getvariables(Request $request)
    {
        $id = $request->post('id');
        $this->user_vbles = DB::table('permisos_variables')
                        ->where('user_id', $id)
                        ->pluck('variable_id')
                        ->toArray();
        $variables = DB::table('variable')
                        ->select('id','descripcion')
                        ->where('estado',1)
                        ->get();
        return datatables()->of($variables)
        ->addColumn('action', function($data)
        {
            if(in_array($data->id, $this->user_vbles))
            {
                $check = '<input type="checkbox" class="checkvble" data-vbleid="'.$data->id.'" name="check_vble" value=1 checked>';
            }
            else
            {
                $check = '<input type="checkbox" class="checkvble" data-vbleid="'.$data->id.'" name="check_vble" value=1>';
            }
            return $check;
        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function check(Request $request){
        if ($request->ajax()) {
            $user = new User();
            if ($request->input('estado') == 1) {
                $user->find($request->input('user_id'))->variables()->attach($request->input('variable_id'));
                return response()->json(['respuesta' => 'Variable asignada correctamente']);
            } else {
                $user->find($request->input('user_id'))->variables()->detach($request->input('variable_id'));
                return response()->json(['respuesta' => 'Variable desasignada correctamente']);
            }
        } else {
            abort(404);
        }
    }

    public function load(Request $request)
    {       
        $user_id = $request->get('user_id'); 
        $usercopy_id = $request->get('usercopy_id');  
        $variables = DB::table('permisos_variables')
                    ->join('variable', 'permisos_variables.variable_id', '=', 'variable.id')
                    ->where('permisos_variables.user_id','=',$usercopy_id)
                    ->pluck('variable_id')
                    ->toArray();
        $user = User::findOrFail($user_id);
        $user->variables()->sync($variables);
        return;
    } 
    
}