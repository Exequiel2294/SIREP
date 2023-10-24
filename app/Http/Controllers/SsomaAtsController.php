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
use App\Http\Controllers\Response;
use App\Models\Mmsa_empleados;
use Dotenv\Result\Success;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

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
            $list = DB::table('ssoma_ats as ats')
                        ->join('mmsa_empleados as emp','ats.dni','=','emp.dni')
                        ->join('mmsa_sector as sec','emp.id_sector','=','sec.id')
                        ->join('mmsa_area as area','sec.id_area','=','area.id')
                        ->select('ats.id as id',
                                 'ats.fecha as fecha',
                                 'ats.hora as hora',
                                 'emp.DNI as dni',
                                 'emp.Nombre as nombre',
                                 'emp.Apellido as apellido',
                                 'area.nombre as area',
                                 'ats.tarea as tarea',
                                 'ats.lugar_frente as lugar_frente',
                                 'ats.cantidad_personas',
                                 'ats.comentarios')

                        ->where(['ats.active'=>1])//1 activo
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
     * Function GetEmpleado: devuelve los datos de un empleado segun el dni
     */
    public function GetEmpleado($dni){
        $empleado = Mmsa_empleados::where(['DNI'=>$dni])
                        ->first();
        if ($empleado) {
            $data['val']=1;
            $data['generic']=DB::table('mmsa_empleados as emp')
                    ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')
                    ->where(['emp.DNI'=>$dni])
                    ->first();
            return response()->json($data);
        }
        else{
            $data['msg']='No se encontro el DNI ingresado';
            $data['val']=0;
            return response()->json($data);
        }
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
                    'dni' => 'required|numeric',
                    'tarea' => 'required|string|min:2|max:100',
                    'lugar_frente' => 'required|string|min:3|max:100',
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
                        'dni' => $request->get('dni'),
                        'tarea' => $request->get('tarea'),
                        'lugar_frente' => $request->get('lugar_frente'),
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
                    'dni' => 'required|numeric',
                    'tarea' => 'required|string|min:2|max:100',
                    'lugar_frente' => 'required|string|min:3|max:100',
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
                        'dni' => $request->get('dni'),
                        'tarea' => $request->get('tarea'),
                        'lugar_frente' => $request->get('lugar_frente'),
                        'cantidad_personas' => $request->get('cantidad_personas'),
                        'comentarios' => $request->get('comentarios'),
                        //'active' => $request->get('active')
                    ]);
                return;                
            }
        }
    }

    /**
     * Function edit(): Realiza un get de los datos del registro seleccionado
    */
    public function edit($id){

        $generic = DB::table('ssoma_ats as ats')
        ->join('mmsa_empleados as emp','ats.dni','=','emp.dni')
        ->join('mmsa_sector as sec','emp.id_sector','=','sec.id')
        ->join('mmsa_area as area','sec.id_area','=','area.id')
        ->select('ats.id as id',
                 'ats.fecha as fecha',
                 'ats.hora as hora',
                 'emp.DNI as dni',
                 'emp.Nombre as nombre',
                 'emp.Apellido as apellido',
                 'area.nombre as area',
                 'ats.tarea as tarea',
                 'ats.lugar_frente as lugar_frente',
                 'ats.cantidad_personas',
                 'ats.comentarios')
        ->where(['ats.id'=>$id])
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
