<?php

namespace App\Http\Controllers;

use App\Models\Variable;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Categoria;
use App\Models\Subarea;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class VariableController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Admin']);
    }

    public function index(Request $request)
    {

        if(request()->ajax()) {
            $list = DB::table('variable')
                        ->join('subcategoria', 'variable.subcategoria_id', '=', 'subcategoria.id')
                        ->join('categoria', 'subcategoria.categoria_id', '=', 'categoria.id')
                        ->join('area', 'categoria.area_id', '=', 'area.id')
                        ->select('area.nombre as area','categoria.nombre as categoria','subcategoria.nombre as subcategoria','variable.id','variable.nombre','variable.descripcion','variable.unidad','variable.estado', 'variable.orden as orden', 'variable.cparametro', 'valor_max as a max', 'valor_min as min')
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
        return view('variable', compact('areas'));
    }

    public function load(Request $request)
    {       
        $id = $request->get('id');
        
        if($id == '' || $id == null)
        {
            
            $validator = Validator::make(
                $request->all(),
                [   
                    'subcategoria_id' => 'required|numeric|exists:subcategoria,id',
                    'nombre' => 'required|string|min:3|max:250',
                    'descripcion' => 'required|string|min:3|max:250|unique:variable,descripcion',
                    'unidad' => 'required|string|min:1|max:50',
                    'estado' => 'required|numeric|between:0,1',
                    'orden' => 'required|numeric|between:0,100',
                    'cparametro' => 'nullable'
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
                        'subcategoria_id' => $request->get('subcategoria_id'),
                        'nombre' => $request->get('nombre'),
                        'descripcion' => $request->get('descripcion'),
                        'unidad' => $request->get('unidad'),
                        'estado' => $request->get('estado'),
                        'orden' => $request->get('orden'),
                        'cparametro' => $request->get('cparametro')
                    ]);
                return;                
            }
        }
        else
        {
            $validator = Validator::make(
                $request->all(),
                [   
                    'subcategoria_id' => 'required|numeric|exists:subcategoria,id',
                    'id'    => 'required|numeric|exists:variable,id',
                    'nombre' => 'required|string|min:3|max:250',
                    'descripcion' => 'required|string|min:3|max:250|unique:variable,descripcion,'.$request->get('id'),
                    'unidad' => 'required|string|min:1|max:50',
                    'estado' => 'required|numeric|between:0,1',
                    'orden' => 'required|numeric|between:0,100',
                    'cparametro' => 'nullable'
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
                        'subcategoria_id' => $request->get('subcategoria_id'),
                        'nombre' => $request->get('nombre'),
                        'descripcion' => $request->get('descripcion'),
                        'unidad' => $request->get('unidad'),
                        'estado' => $request->get('estado'),
                        'orden' => $request->get('orden'),
                        'cparametro' => $request->get('cparametro')
                    ]);
                return;                
            }
        }

        
    } 

    public function edit($id)
    {        
        $where = array('variable.id' => $id);
        $generic =  DB::table('variable')
        ->join('subcategoria','variable.subcategoria_id','=','subcategoria.id')        
        ->join('categoria','subcategoria.categoria_id','=','categoria.id')
        ->where($where)
        ->select('categoria.area_id as area_id','categoria.id as categoria_id','subcategoria.id as subcategoria_id','variable.id','variable.nombre','variable.descripcion','variable.unidad','variable.estado','variable.orden', 'variable.cparametro')
        ->first();
        return response()->json($generic);
    }

    public function delete($id)
    {
        $generic = Variable::findOrFail($id);   
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

    public function getsubcategoria(Request $request)
    {      
        $query = Subcategoria::select('id','nombre')->where('categoria_id',$request->categoria_id)->get();
        $output = '';
        $output .= '<option value="" selected disabled>Seleccionar Subcategoria</option>';
        foreach($query as $row)
        {
            $output .= '<option value="'.$row->id.'">'.$row->nombre.'</option>';       
        }
        $data['result']=$output;
        echo json_encode($data);
    }
}
