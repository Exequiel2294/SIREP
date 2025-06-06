<?php
namespace App\Http\Controllers;

use App\Models\Ssoma_inspecciones;
use App\Models\Mmsa_empleados;
use App\Models\Mmsa_sector;

use App\Http\Controllers\Controller;
use App\Models\Ssoma_capacitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Models\Events\Updated;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use Symfony\Component\HttpFoundation\RateLimiter\RequestRateLimiterInterface;

class SsomaInspeccionesController extends Controller
{   
    public function __construct()
    {
        $this->middleware(['permission:ssoma module']);
    }

    /**
     * Function index(): Index del modulo inspeccion
     */
    public function index(){
        $user= Auth::id(); //obtengo el id del usuario
        $data_user=Mmsa_empleados::where(['id_usuario'=>$user])->first();//tomo las datos del usuario
        /**
         * where=>
         * 1(Activo)
         * 0(Desactivado) 
         */
        if (Auth::user()->hasAnyRole(['Reportes_E', 'Admin'])) {
            if(request()->ajax()) {
                $list = DB::table('ssoma_inspecciones as insp')
                            ->join('mmsa_empleados as emp','insp.dni','=','emp.DNI')
                            ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')
                            ->select('insp.id as id',
                                    'emp.Nombre as nombre',
                                    'emp.Apellido as apellido',
                                    'emp.Puesto as puesto',
                                    'sector.nombre as area',
                                    'insp.fecha as fecha',
                                    'insp.id_intelex as intelex')
                            
                            ->where(['insp.active'=>1])
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
            return view('ssoma_inspecciones');
        } 
        else
        {
            if(request()->ajax()) {
                switch ($data_user->id_cargo){
                    case 1:
                        $data_direccion = DB::table('mmsa_direccion as dir')//Obtengo el ID direccion en el caso que el usuario sea un director
                                        ->select('dir.id as id_dir')
                                        ->join('mmsa_area as area','area.id_direccion','=','dir.id')
                                        ->join('mmsa_sector as sector','sector.id_area','=','area.id')
                                        ->where(['sector.id'=>$data_user->id_sector])
                                        ->first();               
        
                        $list = DB::table('ssoma_inspecciones as insp')
                                        ->join('mmsa_empleados as emp','insp.dni','=','emp.dni')
                                        ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')
                                        ->join('mmsa_area as area','sector.id_area','=','area.id')
                                        ->join('mmsa_direccion as dir','area.id_direccion','=','dir.id')
                                        ->select('insp.id as id',
                                                'emp.Nombre as nombre',
                                                'emp.Apellido as apellido',
                                                'emp.Puesto as puesto',
                                                'sector.nombre as area',
                                                'insp.fecha as fecha',
                                                'insp.id_intelex as intelex')
                                        ->where(['insp.active'=>1])
                                        ->where(['dir.id'=>$data_direccion->id_dir])
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
                                return view('ssoma_inspecciones');
                        break;
                    case 2:
                        $data_area = DB::table('mmsa_area as area')//Obtengo el ID direccion en el caso que el usuario sea un gerente
                                        ->select('area.id as id_area')
                                        ->join('mmsa_sector as sector','sector.id_area','=','area.id')
                                        ->where(['sector.id'=>$data_user->id_sector])
                                        ->first();               
        
                        $list = DB::table('ssoma_inspecciones as insp')
                                        ->join('mmsa_empleados as emp','insp.dni','=','emp.dni')
                                        ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')
                                        ->join('mmsa_area as area','sector.id_area','=','area.id')
                                        ->join('mmsa_direccion as dir','area.id_direccion','=','dir.id')
                                        ->select('insp.id as id',
                                                'emp.Nombre as nombre',
                                                'emp.Apellido as apellido',
                                                'emp.Puesto as puesto',
                                                'sector.nombre as area',
                                                'insp.fecha as fecha',
                                                'insp.id_intelex as intelex')
                                        ->where(['insp.active'=>1])//1 activo
                                        ->where(['area.id'=>$data_area->id_area])
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
                                return view('ssoma_inspecciones');
                    
                        break;
                    case 3:
                        $list = DB::table('ssoma_inspecciones as insp')
                                    ->join('mmsa_empleados as emp','insp.dni','=','emp.dni')
                                    ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')
                                    ->join('mmsa_area as area','sector.id_area','=','area.id')
                                    ->select('insp.id as id',
                                                'emp.Nombre as nombre',
                                                'emp.Apellido as apellido',
                                                'emp.Puesto as puesto',
                                                'sector.nombre as area',
                                                'insp.fecha as fecha',
                                                'insp.id_intelex as intelex')
                                    ->where(['insp.active'=>1])//1 activo
                                    ->where(['emp.id_sector'=>$data_user->id_sector])
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
                            return view('ssoma_inspecciones');
                        
                        break;
                    case 4:
                        $list = DB::table('ssoma_inspecciones as insp')
                                    ->join('mmsa_empleados as emp','insp.dni','=','emp.dni')
                                    ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')
                                    ->join('mmsa_area as area','sector.id_area','=','area.id')
                                    ->select('insp.id as id',
                                                'emp.Nombre as nombre',
                                                'emp.Apellido as apellido',
                                                'emp.Puesto as puesto',
                                                'sector.nombre as area',
                                                'insp.fecha as fecha',
                                                'insp.id_intelex as intelex')
                                    ->where(['insp.active'=>1])//1 activo
                                    ->where(['emp.DNI'=>$data_user->DNI])
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
                            return view('ssoma_inspecciones');
        
                        break;
                }
            } 
            return view('ssoma_inspecciones');
        }
       
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
                Ssoma_inspecciones::create(
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
                Ssoma_inspecciones::where('id',$id)
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
        //$where = array('id' => $id);
        $generic =  DB::table('ssoma_inspecciones as insp')
                    ->select('insp.id as id','insp.dni as dni','emp.Nombre as nombre','emp.Apellido as apellido','emp.Puesto as puesto','sector.nombre as area','insp.fecha','insp.id_intelex')
                    ->join('mmsa_empleados as emp','insp.dni','=','emp.DNI')
                    ->join('mmsa_sector as sector','emp.id_sector','=','sector.id')            
                    ->where(['insp.id'=> $id])
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
