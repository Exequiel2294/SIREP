<?php

namespace App\Http\Controllers;

use App\Models\ComentarioArea;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ComentarioAreaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Admin']);
    }

    public function index(Request $request)
    {
        $areas = Area::orderBy('nombre')->pluck('nombre','id')->toArray();
        if(request()->ajax()) {
            $list = DB::table('comentario_area')
                        ->join('area', 'comentario_area.area_id', '=', 'area.id')
                        ->select('comentario_area.id as id','area.nombre as area','comentario_area.nombre as nombre', 'comentario_area.estado as estado')
                        ->get();
            return datatables()->of($list)
                    ->addColumn('action', function($data)
                    {
                        $button = ''; 
                        $button .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" class="btn-action-table edit" title="Editar registro"><i style="color:#0F62AC;" class="fa fa-edit"></i></a>';  
                        $button .= '&nbsp;';
                        $button .= '<a href="javascript:void(0)" name="delete" id="'.$data->id.'" class="btn-action-table delete" title="Eliminar registro"><i class="fa fa-times-circle text-danger"></i></a>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->addIndexColumn()
                    ->make(true);
        } 
        return view('comentario_area', compact('areas'));
    }

    public function load(Request $request)
    {       
        $id = $request->get('id');
        
        if($id == '' || $id == null)
        {            
            $validator = Validator::make(
                $request->all(),
                [   
                    'area_id'   => 'required|exists:area,id',
                    'nombre' => 'required|string|min:3|max:250|unique:comentario_area,nombre',
                    'estado' => 'required|numeric|between:0,1'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                ComentarioArea::create(
                    [
                        'area_id'   => $request->get('area_id'),
                        'nombre' => $request->get('nombre'),
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
                    'id'    => 'required|numeric|exists:comentario_area,id',
                    'area_id'   => 'required|exists:area,id',
                    'nombre' => 'required|string|min:3|max:250|unique:comentario_area,nombre,'.$request->get('id'),
                    'estado' => 'required|numeric|between:0,1'
                ]            
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                ComentarioArea::where('id',$id)
                    ->update(
                    [
                        'area_id'   => $request->get('area_id'),
                        'nombre' => $request->get('nombre'),
                        'estado' => $request->get('estado')
                    ]);
                return;                
            }
        }

        
    } 

    public function edit($id)
    {        
        $where = array('id' => $id);
        $generic =  DB::table('comentario_area')
        ->where($where)
        ->first();
        return response()->json($generic);
    }

    public function delete($id)
    {
        $generic = ComentarioArea::findOrFail($id);   
        $generic->delete(); 
        return;
    }
}