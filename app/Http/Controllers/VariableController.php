<?php

namespace App\Http\Controllers;

use App\Models\Variable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class VariableController extends Controller
{
    public function index(Request $request)
    {

        if(request()->ajax()) {
            $list = DB::table('variables')
                        ->select('id','nivel1','nivel2','nombre','descripcion','unidad','estado', 'created_at', 'updated_at')
                        ->get();
            return datatables()->of($list)
                    ->addColumn('action', function($data)
                    {
                        $button = ''; 
                        $button .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" class="btn-action-table edit" title="Editar registro"><i class="fa fa-edit"></i></a>';  
                        $button .= '&nbsp;';
                        $button .= '<a href="javascript:void(0)" name="delete" id="'.$data->id.'" class="btn-action-table delete" title="Eliminar registro"><i class="fa fa-times-circle text-danger"></i></a>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->addIndexColumn()
                    ->make(true);
        } 
        return view('variable');
    }

    public function load(Request $request)
    {       
        $id = $request->get('id');
        
        if($id == '' || $id == null)
        {
            
            $validator = Validator::make(
                $request->all(),
                [   
                    'nivel1' => 'required|string|min:3|max:250',
                    'nivel2' => 'required|string|min:3|max:250',
                    'nombre' => 'required|string|min:3|max:250',
                    'descripcion' => 'required|string|min:3|max:250',
                    'unidad' => 'required|string|min:1|max:50',
                    'estado' => 'required|numeric|between:0,1'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Variable::create(
                    [
                        'nivel1' => $request->get('nivel1'),
                        'nivel2' => $request->get('nivel2'),
                        'nombre' => $request->get('nombre'),
                        'descripcion' => $request->get('descripcion'),
                        'unidad' => $request->get('unidad'),
                        'estado' => $request->get('estado')
                    ]);
                return;                
            }
        }
        else
        {
            $validator = Validator::make(
                $request->all(),
                [   
                    'nivel1' => 'required|string|min:3|max:250',
                    'nivel2' => 'required|string|min:3|max:250',
                    'id'    => 'required|numeric|exists:variables,id',
                    'nombre' => 'required|string|min:3|max:250',
                    'descripcion' => 'required|string|min:3|max:250',
                    'unidad' => 'required|string|min:1|max:50',
                    'estado' => 'required|numeric|between:0,1'
                ]            
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Variable::where('id',$id)
                    ->update(
                    [
                        'nivel1' => $request->get('nivel1'),
                        'nivel2' => $request->get('nivel2'),
                        'nombre' => $request->get('nombre'),
                        'descripcion' => $request->get('descripcion'),
                        'unidad' => $request->get('unidad'),
                        'estado' => $request->get('estado')
                    ]);
                return;                
            }
        }

        
    } 

    public function edit($id)
    {        
        $where = array('id' => $id);
        $generic =  DB::table('variables')
        ->where($where)
        ->first();
        return response()->json($generic);
    }

    public function delete($id)
    {
        $generic = Variable::findOrFail($id);   
        $generic->delete(); 
        return;
    }
}
