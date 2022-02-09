<?php

namespace App\Http\Controllers;

use App\Models\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AreaController extends Controller
{
    public function index(Request $request)
    {

        if(request()->ajax()) {
            $list = DB::table('area')
                        ->select('id','nombre','descripcion','estado','created_at','updated_at')
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
        return view('area');
    }

    public function load(Request $request)
    {       
        $id = $request->get('id');
        
        if($id == '' || $id == null)
        {
            
            $validator = Validator::make(
                $request->all(),
                [   
                    'nombre' => 'required|string|min:3|max:250|unique:area,nombre',
                    'descripcion' => 'nullable|string|min:3|max:250',
                    'estado' => 'required|numeric|between:0,1'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Area::create(
                    [
                        'nombre' => $request->get('nombre'),
                        'descripcion' => $request->get('descripcion'),
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
                    'id'    => 'required|numeric|exists:area,id',
                    'nombre' => 'required|string|min:3|max:250|unique:area,nombre,'.$request->get('id'),
                    'descripcion' => 'nullable|string|min:3|max:250',
                    'estado' => 'required|numeric|between:0,1'
                ]            
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Area::where('id',$id)
                    ->update(
                    [
                        'nombre' => $request->get('nombre'),
                        'descripcion' => $request->get('descripcion'),
                        'estado' => $request->get('estado')
                    ]);
                return;                
            }
        }

        
    } 

    public function edit($id)
    {        
        $where = array('id' => $id);
        $generic =  DB::table('area')
        ->where($where)
        ->first();
        return response()->json($generic);
    }

    public function delete($id)
    {
        $generic = Area::findOrFail($id);   
        $generic->delete(); 
        return;
    }
}
