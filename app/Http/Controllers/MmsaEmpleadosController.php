<?php

namespace App\Http\Controllers;

//use App\Models\Ssoma_ats;
use App\Models\Mmsa_empleados;
use App\Models\Mmsa_sector;
use App\Models\Mmsa_area;
use App\Models\Mmsa_cargos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JeroenNoten\LaravelAdminLte\View\Components\Form\Select;
use LdapRecord\Models\Events\Updated;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use Symfony\Component\HttpFoundation\RateLimiter\RequestRateLimiterInterface;


class MmsaEmpleadosController extends Controller
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
        if(request()->ajax())
        {
            $list=DB::table('mmsa_empleados as emp')
                    ->select('emp.id as id','emp.DNI as dni','emp.Nombre as nombre','emp.Apellido as apellido','emp.Puesto as puesto','sector.nombre as sector','us.email as correo')
                    ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')
                    ->join('users as us','emp.id_usuario','=','us.id')
                    ->where(['emp.active'=>1])
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
        return view('mmsa_empleados');
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
                    'dni' => 'required',
                    'nombre' => 'required',
                    'apellido' => 'required',
                    'id_cargo'=>'required',
                    'puesto' => 'required',
                    'id_usuario'=>'required',
                    'id_sector' => 'required'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Mmsa_empleados::create(
                    [
                        'DNI' => $request->get('dni'),
                        'Nombre' => $request->get('nombre'),
                        'Apellido' => $request->get('apellido'),
                        'id_cargo'=>$request->get('id_cargo'),
                        'Puesto' => $request->get('puesto'),
                        'id_usuario'=>$request->get('id_usuario'),
                        'id_sector' => $request->get('id_sector'),
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
                    'dni' => 'required',
                    'nombre' => 'required',
                    'apellido' => 'required',
                    'id_cargo'=>'required',
                    'puesto' => 'required',
                    'id_usuario'=>'required',
                    'id_sector' => 'required'
                ]            
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Mmsa_empleados::where('id',$id)
                    ->update(
                    [
                        'DNI' => $request->get('dni'),
                        'Nombre' => $request->get('nombre'),
                        'Apellido' => $request->get('apellido'),
                        'id_cargo'=>$request->get('id_cargo'),
                        'Puesto' => $request->get('puesto'),
                        'id_usuario'=>$request->get('id_usuario'),
                        'id_sector' => $request->get('id_sector'),
                        'active' => $request->get('active')
                    ]);
                return;                
            }
        }
    }


    /**
     * Function getsectores: funcion que devuelve la totalidad de los sectores
     */
    public function getsectores()
    {
        $sectores['data'] = DB::table('mmsa_sector as sector')
                        ->join('mmsa_area as area','sector.id_area','=','area.id')
                        ->select('sector.id as id','sector.nombre as sector','area.nombre as area')
                        ->orderBy('sector.nombre')
                        ->get();
        return response()->json($sectores);
    }

    /**
     * Function getcargos: funcion que devuelve la totalidad de los cargos
     */
    public function getcargos()
    {
        $cargos['result'] = DB::table('mmsa_cargos')
                        ->select('id','cargo','active')
                        ->where(['active'=>1])
                        ->orderBy('id')
                        ->get();
        return response()->json($cargos);
    }

    /**
     * Function getcorreos: funcion que devuelve los correos para asignar al empleado
     */
    public function getcorreos()
    {
        $correos['data'] = DB::table('users')
                        ->select('id','email')
                        ->orderBy('email')
                        ->get();
        return response()->json($correos);
    }
    /**
    * Function edit(): Realiza un get de los datos del registro seleccionado
    */
     public function edit($id){
        $generic =  DB::table('mmsa_empleados as emp')
                    ->select('emp.id as id','emp.DNI as dni','emp.Nombre as nombre','emp.Apellido as apellido','cargo.id as cargo','emp.Puesto as puesto','users.id as correo','sector.id as sector')
                    ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')
                    ->join('users','emp.id_usuario','=','users.id')
                    ->join('mmsa_cargos as cargo','emp.id_cargo','=','cargo.id')
                    ->where(['emp.id'=> $id])//recibe el DNI
                    ->first();
        return response()->json($generic);
     }

    /**
    * Function delete(): Realiza un aupdate del estado en desactivado del registro para tener un historico
    */
    public function delete($id){
        Mmsa_empleados::where('id',$id)
                    ->update(['active'=>0]);
        return;
      }
}
