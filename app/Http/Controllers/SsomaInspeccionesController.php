<?php
namespace App\Http\Controllers;

use App\Models\Ssoma_inspecciones;

use App\Http\Controllers\Controller;
use App\Models\Ssoma_capacitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Models\Events\Updated;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use Symfony\Component\HttpFoundation\RateLimiter\RequestRateLimiterInterface;

class SsomaInspeccionesController extends Controller
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
            $list = DB::table('ssoma_inspecciones')
                        ->select('id','fecha','tipologia','hora','nombre_y_apellido','area_reportante','area_contratista_inspeccionada','lugar_frente','hallazgos','nivel_criticidad','planes_accion','solucion_momento','responsable_plan_accion','fecha_vencimiento','estado','comentario','fecha_cierre')
                        ->where(['active'=>1])
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
        return view('ssoma_inspecciones');
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
                    'hora' => 'required',
                    'nombre_y_apellido' => 'required|string|min:3|max:100',
                    'area_reportante' => 'required|string|min:2|max:100',
                    'area_contratista_inspeccionada' => 'required|string|min:2|max:100',
                    'lugar_frente' => 'required|string|min:3|max:100',
                    'hallazgos' => 'required|string|min:2|max:100',
                    'nivel_criticidad' => 'required|string|min:3|max:100',
                    'planes_accion' => 'required|string|min:2|max:100',
                    'solucion_momento' => 'required|string|min:2|max:100',
                    'responsable_plan_accion' => 'required|string|min:3|max:100',
                    'fecha_vencimiento' => 'required',
                    'estado' => 'required|string|min:3|max:100',
                    'comentario' => 'required|string|min:3|max:100',
                    'fecha_cierre' => 'required'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Ssoma_inspecciones::create(
                    [
                        'fecha' => $request->get('fecha'),
                        'tipologia' => $request->get('tipologia'),
                        'hora' => $request->get('hora'),
                        'nombre_y_apellido' => $request->get('nombre_y_apellido'),
                        'area_reportante' => $request->get('area_reportante'),
                        'area_contratista_inspeccionada' => $request->get('area_contratista_inspeccionada'),
                        'lugar_frente' => $request->get('lugar_frente'),
                        'hallazgos' => $request->get('hallazgos'),
                        'nivel_criticidad' => $request->get('nivel_criticidad'),
                        'planes_accion' => $request->get('planes_accion'),
                        'solucion_momento' => $request->get('solucion_momento'),
                        'responsable_plan_accion' => $request->get('responsable_plan_accion'),
                        'fecha_vencimiento' => $request->get('fecha_vencimiento'),
                        'estado' => $request->get('estado'),
                        'comentario' => $request->get('comentario'),
                        'fecha_cierre' => $request->get('fecha_cierre'),
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
                    'tipologia' => 'required|string|min:3|max:100',
                    'hora' => 'required',
                    'nombre_y_apellido' => 'required|string|min:3|max:100',
                    'area_reportante' => 'required|string|min:2|max:100',
                    'area_contratista_inspeccionada' => 'required|string|min:2|max:100',
                    'lugar_frente' => 'required|string|min:3|max:100',
                    'hallazgos' => 'required|string|min:2|max:100',
                    'nivel_criticidad' => 'required|string|min:3|max:100',
                    'planes_accion' => 'required|string|min:2|max:100',
                    'solucion_momento' => 'required|string|min:2|max:100',
                    'responsable_plan_accion' => 'required|string|min:3|max:100',
                    'fecha_vencimiento' => 'required',
                    'estado' => 'required|string|min:3|max:100',
                    'comentario' => 'required|string|min:3|max:100',
                    'fecha_cierre' => 'required'
                ]            
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Ssoma_inspecciones::where('id',$id)
                    ->update(
                    [
                        'fecha' => $request->get('fecha'),
                        'tipologia' => $request->get('tipologia'),
                        'hora' => $request->get('hora'),
                        'nombre_y_apellido' => $request->get('nombre_y_apellido'),
                        'area_reportante' => $request->get('area_reportante'),
                        'area_contratista_inspeccionada' => $request->get('area_contratista_inspeccionada'),
                        'lugar_frente' => $request->get('lugar_frente'),
                        'hallazgos' => $request->get('hallazgos'),
                        'nivel_criticidad' => $request->get('nivel_criticidad'),
                        'planes_accion' => $request->get('planes_accion'),
                        'solucion_momento' => $request->get('solucion_momento'),
                        'responsable_plan_accion' => $request->get('responsable_plan_accion'),
                        'fecha_vencimiento' => $request->get('fecha_vencimiento'),
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
        $generic =  DB::table('ssoma_inspecciones')
        ->where($where)
        ->first();
        return response()->json($generic);
     }

     /**
      * Function delete(): Realiza un aupdate del estado en desactivado del registro para tener un historico
      */
      public function delete($id){
        Ssoma_inspecciones::where('id',$id)
                    ->update(['active'=>0]);
        return;
      }
}
