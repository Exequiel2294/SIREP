<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Categoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Admin']);
    }

    public function index(Request $request)
    {

        if(request()->ajax()) {
            $list = DB::table('categoria')
                        ->join('area', 'categoria.area_id', '=', 'area.id')
                        ->select('categoria.orden','categoria.id','area.nombre as area','categoria.nombre','categoria.descripcion','categoria.estado','categoria.created_at','categoria.updated_at')
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
        
        $areas = Area::orderBy('nombre')->pluck('nombre','id')->toArray();
        return view('categoria', compact('areas'));
    }

    public function load(Request $request)
    {       
        $id = $request->get('id');
        
        if($id == '' || $id == null)
        {
            
            $validator = Validator::make(
                $request->all(),
                [   
                    'area_id' => 'required|exists:area,id',
                    'nombre' => 'required|string|min:3|max:250',
                    'descripcion' => 'nullable|string|min:3|max:250',
                    'estado' => 'required|numeric|between:0,1',
                    'orden' => 'required|between:0,100'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Categoria::create(
                    [
                        'area_id' => $request->get('area_id'),
                        'nombre' => $request->get('nombre'),
                        'descripcion' => $request->get('descripcion'),
                        'estado' => $request->get('estado'),
                        'orden' => $request->get('orden')
                    ]);
                return;                
            }
        }
        else
        {
            $validator = Validator::make(
                $request->all(),
                [   
                    'id'    => 'required|numeric|exists:categoria,id',                    
                    'area_id' => 'required|exists:area,id',
                    'nombre' => 'required|string|min:3|max:250',
                    'descripcion' => 'nullable|string|min:3|max:250',
                    'estado' => 'required|numeric|between:0,1',
                    'orden' => 'required|between:0,100'
                ]            
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Categoria::where('id',$id)
                    ->update(
                    [                        
                        'area_id' => $request->get('area_id'),
                        'nombre' => $request->get('nombre'),
                        'descripcion' => $request->get('descripcion'),
                        'estado' => $request->get('estado'),
                        'orden' => $request->get('orden')
                    ]);
                return;                
            }
        }

        
    } 

    public function edit($id)
    {        
        $where = array('id' => $id);
        $generic =  DB::table('categoria')
        ->where($where)
        ->first();
        return response()->json($generic);
    }

    public function delete($id)
    {
        $generic = Categoria::findOrFail($id);   
        $generic->delete(); 
        return;
    }
}
