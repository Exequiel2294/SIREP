<?php

namespace App\Http\Controllers;

use App\Models\Ssoma_ost;
use App\Models\Mmsa_empleados;
use App\Models\Mmsa_sector;

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
            $list = DB::table('ssoma_ost as ost')
                        ->select('ost.id as id','emp.Nombre as nombre','emp.Apellido as apellido','emp.Puesto as puesto','sector.nombre as area','ost.fecha as fecha','ost.id_intelex as intelex')
                        ->join('mmsa_empleados as emp','ost.dni','=','emp.DNI')
                        ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')
                        ->where(['ost.active'=>1])
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
     * Fucntion load(): Realiza las ALTAS de las OST
     */
     public function load(Request $request){
               
               
        $id = $request->get('id');
        
        if($id == '' || $id == null)
        {
            
            $validator = Validator::make(
                $request->all(),
                [   
                    'dni' => 'required|numeric',
                    'fecha' => 'required',
                    'id_intelex' => 'required|numeric'
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
                        'dni'=> $request->get('dni'),
                        'fecha' => $request->get('fecha'),
                        'id_intelex'=>$request->get('id_intelex'),
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
                    'dni' => 'required|numeric',
                    'fecha' => 'required',
                    'id_intelex' => 'required|numeric'
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
                        'dni'=> $request->get('dni'),
                        'fecha' => $request->get('fecha'),
                        'id_intelex'=>$request->get('id_intelex')
                    ]);
                return;                
            }
        }
     }

     /**
      * Function edit(): Realiza un get de los datos del registro seleccionado
      */
     public function edit($id){
        $generic =  DB::table('ssoma_ost as ost')
                    ->select('ost.id as id','ost.dni as dni','emp.Nombre as nombre','emp.Apellido as apellido','emp.Puesto as puesto','sector.nombre as area','ost.fecha','ost.id_intelex')
                    ->join('mmsa_empleados as emp','ost.dni','=','emp.DNI')
                    ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')            
                    ->where(['ost.id'=> $id])
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
