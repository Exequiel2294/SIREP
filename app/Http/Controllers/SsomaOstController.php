<?php

namespace App\Http\Controllers;

use App\Models\Ssoma_ost;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Models\Events\Updated;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use Symfony\Component\HttpFoundation\RateLimiter\RequestRateLimiterInterface;

class SsomaOstController extends Controller
{
    /**
     * Function index(): Index del modulo ost
     */
    public function index(){
        /**
         * where=>
         * 1(Activo)
         * 0(Desactivado) 
         */
        if(request()->ajax()) {
            $list = DB::table('ssoma_ost')
                        ->select('*')
                        ->where(['active'=>1])
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
        return view('ssoma_ost');
    }

    /**
     * Fucntion load(): Realiza las ALTAS de las OST
     */
     public function load(Request $request){
               
        $id = $request->get('id');
        
        if($id == '' || $id == null)
        {
            
            $validator = Validator::make(
                $request->all(),
                [   
                    'fecha' => 'required',
                    'tipologia' => 'required|string|min:3|max:100',
                    'nombre_y_apellido' => 'required|string|min:3|max:100',
                    'area_reportante' => 'required|string|min:2|max:100',
                    'lugar_frente' => 'required|string|min:3|max:100',
                    'tarea_observada' => 'required|string|min:3|max:100',
                    'acto_inseguro' => 'required|string|min:2|max:100',
                    'condicion_inseguro' => 'required|string|min:2|max:100',
                    'desc_condicion_insegura' => 'required|string|min:3|max:100',
                    'solu_condicion_insegura' => 'required|string|min:2|max:100',
                    'elimi_condicion_insegura' => 'required|string|min:3|max:100',
                    'prevenir_repeticion' => 'required|string|min:3|max:100',
                    'reforzar_acto_seguro' => 'required|string|min:3|max:100',
                    'responsable_ejecucion' => 'required|string|min:3|max:100',
                    'fecha_vecimiento' => 'required',
                    'nivel_criticidad' => 'required|string|min:3|max:100',
                    'estado' => 'required|string|min:3|max:100',
                    'comentario' => 'required|string|min:3|max:100',
                    'fecha_cierre' => 'required|string|min:3|max:100'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Ssoma_ost::create(
                    [
                        'fecha' => $request->get('fecha'),
                        'tipologia' => $request->get('tipologia'),
                        'nombre_y_apellido' => $request->get('nombre_y_apellido'),
                        'area_reportante' => $request->get('area_reportante'),
                        'lugar_frente' => $request->get('lugar_frente'),
                        'tarea_observada' => $request->get('tarea_observada'),
                        'acto_inseguro' => $request->get('acto_inseguro'),
                        'condicion_inseguro' => $request->get('condicion_inseguro'),
                        'desc_condicion_insegura' => $request->get('desc_condicion_insegura'),
                        'solu_condicion_insegura' => $request->get('solu_condicion_insegura'),
                        'elimi_condicion_insegura' => $request->get('elimi_condicion_insegura'),
                        'prevenir_repeticion' => $request->get('prevenir_repeticion'),
                        'reforzar_acto_seguro' => $request->get('reforzar_acto_seguro'),
                        'responsable_ejecucion' => $request->get('responsable_ejecucion'),
                        'fecha_vecimiento' => $request->get('fecha_vecimiento'),
                        'nivel_criticidad' => $request->get('nivel_criticidad'),
                        'estado' => $request->get('estado'),
                        'comentario' => $request->get('comentario'),
                        'active'=>$request->get('active'),
                        'fecha_cierre' => $request->get('fecha_cierre')
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
                    'tipologia' => 'required|string|min:3|max:100',
                    'nombre_y_apellido' => 'required|string|min:3|max:100',
                    'area_reportante' => 'required|string|min:2|max:100',
                    'lugar_frente' => 'required|string|min:3|max:100',
                    'tarea_observada' => 'required|string|min:3|max:100',
                    'acto_inseguro' => 'required|string|min:2|max:100',
                    'condicion_inseguro' => 'required|string|min:2|max:100',
                    'desc_condicion_insegura' => 'required|string|min:3|max:100',
                    'solu_condicion_insegura' => 'required|string|min:2|max:100',
                    'elimi_condicion_insegura' => 'required|string|min:3|max:100',
                    'prevenir_repeticion' => 'required|string|min:3|max:100',
                    'reforzar_acto_seguro' => 'required|string|min:3|max:100',
                    'responsable_ejecucion' => 'required|string|min:3|max:100',
                    'fecha_vecimiento' => 'required',
                    'nivel_criticidad' => 'required|string|min:3|max:100',
                    'estado' => 'required|string|min:3|max:100',
                    'comentario' => 'required|string|min:3|max:100',
                    'fecha_cierre' => 'required|string|min:3|max:100'
                ]            
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Ssoma_ost::where('id',$id)
                    ->update(
                    [
                        'fecha' => $request->get('fecha'),
                        'tipologia' => $request->get('tipologia'),
                        'nombre_y_apellido' => $request->get('nombre_y_apellido'),
                        'area_reportante' => $request->get('area_reportante'),
                        'lugar_frente' => $request->get('lugar_frente'),
                        'tarea_observada' => $request->get('tarea_observada'),
                        'acto_inseguro' => $request->get('acto_inseguro'),
                        'condicion_inseguro' => $request->get('condicion_inseguro'),
                        'desc_condicion_insegura' => $request->get('desc_condicion_insegura'),
                        'solu_condicion_insegura' => $request->get('solu_condicion_insegura'),
                        'elimi_condicion_insegura' => $request->get('elimi_condicion_insegura'),
                        'prevenir_repeticion' => $request->get('prevenir_repeticion'),
                        'reforzar_acto_seguro' => $request->get('reforzar_acto_seguro'),
                        'responsable_ejecucion' => $request->get('responsable_ejecucion'),
                        'fecha_vecimiento' => $request->get('fecha_vecimiento'),
                        'nivel_criticidad' => $request->get('nivel_criticidad'),
                        'estado' => $request->get('estado'),
                        'comentario' => $request->get('comentario'),
                        'fecha_cierre' => $request->get('fecha_cierre')
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
        $generic =  DB::table('ssoma_ost')
        ->where($where)
        ->first();
        return response()->json($generic);
     }

     /**
      * Function delete(): Realiza un aupdate del estado en desactivado del registro para tener un historico
      */
      public function delete($id){
        Ssoma_ost::where('id',$id)
                    ->update(['active'=>0]);
        return;
      }
}
