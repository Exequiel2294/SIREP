<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Models\Events\Updated;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use Symfony\Component\HttpFoundation\RateLimiter\RequestRateLimiterInterface;

class AccesoModulosController extends Controller
{
    
    public function index()
    {
        if (request()->ajax()) {
            $list = DB::table('model_has_permissions as A')
                        ->select('A.permission_id as permission_id','A.model_id as model_id','C.email as correo','B.name as modulo')
                        ->join('permissions as B','A.permission_id','=','B.id')
                        ->join('users as C','A.model_id','=','C.id')
                        ->get();

            return datatables()->of($list)
                    ->addColumn('action', function($data)
                    {
                        $button = ''; 

                        $button .= '<a href="javascript:void(0)" name="delete" data-id="'.$data->permission_id.'" id="'.$data->model_id.'" class="btn-action-table delete" title="Eliminar registro"><i class="fa fa-times-circle text-danger"></i></a>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->addIndexColumn()
                    ->make(true);
        } 
        return view('acceso_mudulo');
    }
    public function getcorreos()
    {
        $correos['data'] = DB::table('users')
                        ->select('id','email')
                        ->orderBy('email')
                        ->get();
        return response()->json($correos);
    }
    public function getmodulos()
    {
        $correos['data'] = DB::table('permissions')
                        ->select('id','name')
                        ->orderBy('name')
                        ->get();
        return response()->json($correos);
    }
    /**
     * Function que carga los datos
     */
    public function load(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [   
                'permission_id' => 'numeric',
                'model_id' => 'numeric'
                
            ]             
        );
        if ($validator->fails()) 
        {
            return response()->json(['error'=>$validator->errors()->all()]);
        }
        else
        {
            DB::table('model_has_permissions')->insert(
                [
                    'permission_id'=>$request->get('permissions_id'),
                    'model_type'=>'App\User',
                    'model_id'=>$request->get('model_id')
                ]);
            return;                
        }
    }
    public function delete(Request $request)
    {
        $model_id = $request->get('model_id');
        $permission_id = $request->get('permissions_id');
        $generic = DB::delete('delete from model_has_permissions where permission_id ="'.$permission_id.'" and model_id="'.$model_id.'"');
        return;
        
    }

}


