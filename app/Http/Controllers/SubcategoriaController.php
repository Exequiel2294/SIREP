<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Subcategoria;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class SubcategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Admin']);
    }

    public function index(Request $request)
    {

        if(request()->ajax()) {
            $list = DB::table('subcategoria')
                        ->join('categoria', 'subcategoria.categoria_id', '=', 'categoria.id')
                        ->join('area', 'categoria.area_id', '=', 'area.id')                        
                        ->select('area.nombre as area','subcategoria.id','categoria.nombre as categoria','subcategoria.nombre','subcategoria.descripcion','subcategoria.estado','subcategoria.created_at','subcategoria.updated_at','subcategoria.orden as orden')
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
        return view('subcategoria', compact('areas'));
    }

    public function load(Request $request)
    {       
        $id = $request->get('id');
        
        if($id == '' || $id == null)
        {
            
            $validator = Validator::make(
                $request->all(),
                [   
                    'categoria_id' => 'required|exists:categoria,id',
                    'nombre' => 'required|string|min:3|max:250',
                    'descripcion' => 'nullable|string|min:3|max:250',
                    'estado' => 'required|numeric|between:0,1',
                    'orden' => 'required|numeric|between:0,100'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Subcategoria::create(
                    [
                        'categoria_id' => $request->get('categoria_id'),
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
                    'id'    => 'required|numeric|exists:subcategoria,id',                    
                    'categoria_id' => 'required|exists:categoria,id',
                    'nombre' => 'required|string|min:3|max:250',
                    'descripcion' => 'nullable|string|min:3|max:250',
                    'estado' => 'required|numeric|between:0,1',
                    'orden' => 'required|numeric|between:0,100'
                ]            
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Subcategoria::where('id',$id)
                    ->update(
                    [                        
                        'categoria_id' => $request->get('categoria_id'),
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
        $where = array('subcategoria.id' => $id);
        $generic =  DB::table('subcategoria')
        ->join('categoria','subcategoria.categoria_id','=','categoria.id')
        ->where($where)
        ->select('categoria.area_id as area_id','categoria.id as categoria_id','subcategoria.id','subcategoria.nombre','subcategoria.descripcion','subcategoria.estado','subcategoria.orden')
        ->first();
        return response()->json($generic);
    }

    public function delete($id)
    {
        $generic = Subcategoria::findOrFail($id);   
        $generic->delete(); 
        return;
    }

    public function getcategoria(Request $request)
    {      
        $query = Categoria::select('id','nombre')->where('area_id',$request->area_id)->get();
        $output = '';
        $output .= '<option value="" selected disabled>Seleccionar Categoria</option>';
        foreach($query as $row)
        {
            $output .= '<option value="'.$row->id.'">'.$row->nombre.'</option>';       
        }
        $data['result']=$output;
        echo json_encode($data);
    }
}
