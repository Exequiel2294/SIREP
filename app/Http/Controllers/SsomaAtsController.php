<?php

namespace App\Http\Controllers;

use App\Models\Ssoma_ats;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Models\Events\Updated;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use Symfony\Component\HttpFoundation\RateLimiter\RequestRateLimiterInterface;


class SsomaAtsController extends Controller
{
    /**
     * Function index(): Index del modulo ats
     */
    public function index(){
        /**
         * where=>
         * 1(Activo)
         * 0(Desactivado) 
         */
        if(request()->ajax()) {
            $list = DB::table('ssoma_ats')
                        ->select('id','fecha','hora','tarea','lugar_frente','nombre_y_apellido','area','cantidad_personas','comentarios')
                        ->where(['active'=>1])//1 activo
                        ->get();
            return datatables()->of($list)
                    ->editColumn('hora',function($data){
                        return date('H:i', strtotime($data->hora));
                    })
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
        return view('ssoma_ats');
    }

    /**
     * Function index(): funcion en donde crea un registro para 
     */
    public function load(Request $request){
        $id = $request->get('id');
        
        if($id == '' || $id == null)
        {
            
            $validator = Validator::make(
                $request->all(),
                [   
                    'fecha' => 'required',
                    'hora' => 'required',
                    'tarea' => 'required|string|min:2|max:100',
                    'lugar_frente' => 'required|string|min:3|max:100',
                    'nombre_y_apellido' => 'required|string|min:3|max:100',
                    'area' => 'required|string|min:2|max:100',
                    'cantidad_personas'=>'required|numeric',
                    'comentarios' => 'required|string|min:3|max:100'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Ssoma_ats::create(
                    [
                        'fecha' => $request->get('fecha'),
                        'hora' => $request->get('hora'),
                        'tarea' => $request->get('tarea'),
                        'lugar_frente' => $request->get('lugar_frente'),
                        'nombre_y_apellido' => $request->get('nombre_y_apellido'),
                        'area' => $request->get('area'),
                        'cantidad_personas' => $request->get('cantidad_personas'),
                        'comentarios' => $request->get('comentarios'),
                        'active' => $request->get('active')

                    ]);
                return;                
            }
        }
        else
        {
            $validator = Validator::make(
                $request->all(),
                [   
                    'fecha' => 'required',
                    'hora' => 'required',
                    'tarea' => 'required|string|min:2|max:100',
                    'lugar_frente' => 'required|string|min:3|max:100',
                    'nombre_y_apellido' => 'required|string|min:3|max:100',
                    'area' => 'required|string|min:2|max:100',
                    'cantidad_personas'=>'required|numeric',
                    'comentarios' => 'required|string|min:3|max:100'
                ]            
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Ssoma_ats::where('id',$id)
                    ->update(
                    [
                        'fecha' => $request->get('fecha'),
                        'hora' => $request->get('hora'),
                        'tarea' => $request->get('tarea'),
                        'lugar_frente' => $request->get('lugar_frente'),
                        'nombre_y_apellido' => $request->get('nombre_y_apellido'),
                        'area' => $request->get('area'),
                        'cantidad_personas' => $request->get('cantidad_personas'),
                        'comentarios' => $request->get('comentarios')
                    ]);
                return;                
            }
        }
    }

    /**
     * Function edit(): Realiza un get de los datos del registro seleccionado
    */
    public function edit($id){
        $where = array('id' => $id);
        $generic =  DB::table('ssoma_ats')
        ->where($where)
        ->first();
        return response()->json($generic);
    }

    /**
     * Function delete(): Ocultar
     */
    public function delete($id){
        Ssoma_ats::where('id',$id)
                    ->update(['active'=>0]);
        return;
    }
}
