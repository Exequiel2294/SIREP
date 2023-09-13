<?php

namespace App\Http\Controllers;

use App\Models\Ssoma_capacitacion;
use App\Http\Controllers\Controller;
use App\Models\Relacion_capacitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SsomaCapacitacionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:ssoma module']);
    }

    /**
     * Function index(): Este devuelve la tabla que se encuentra cuando cargamos el modulo de Capacitacion de Seguridad
     */
    public function index(){
        if(request()->ajax()) {
            $list = DB::table('ssoma_capacitacion')
                        ->select('id','fecha','hora','tema','capacitador','area','lugar','duracion')
                        ->where(['active'=>1])
                        ->get();
            return datatables()->of($list)
                    ->editColumn('hora',function($data){
                        return date('H:i', strtotime($data->hora));
                    })
                    ->addColumn('asist', function($data)
                    {
                        $button = ''; 
                        $button .= '<a href="javascript:void(0)" name="view" data-id="'.$data->id.'" class="btn-action-table view" title="Ver asistentes"><i style="color:#0F62AC;" class="fas fa-users"></i></a>';  
                        return $button;
                    })
                    ->addColumn('evaluated', function($data)
                    {
                        $button1 = ''; 
                        $button1 .= '<a href="javascript:void(0)" name="evaluated" data-id="'.$data->id.'" class="btn-action-table aproved" title="Ver Aprobados"><i style="color:#0F62AC;" class="fas fa-eye"></i></a>';  
                        return $button1;
                    })
                    ->addcolumn('action',function($data){
                        $button2 = ''; 
                        $button2 .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" class="btn-action-table edit" title="Editar registro"><i style="color:#0F62AC;" class="fa fa-edit"></i></a>';  
                        $button2 .= '&nbsp;';
                        $button2 .= '<a href="javascript:void(0)" name="delete" id="'.$data->id.'" class="btn-action-table delete" title="Eliminar registro"><i class="fa fa-times-circle text-danger"></i></a>';
                        return $button2;
                    })
                    ->rawColumns(['asist','evaluated','action'])
                    ->addIndexColumn()
                    ->make(true);
        } 

        return view('ssoma_capacitacion');
    }

    /**
     * Function empleados(): esta function lo que hace es devolver el listado de empleados en un MODAL que estan en la tabla relacion y hace un match con la tabla de de empleados para devolver
     * los empleados que no fueron agregados a a la tabla relacion_capacitacion, Agregar empleados con a una capacitacion
     */
    public function empleados(Request $request){
        $this->id_capacitacion = $request->post('id');// Envio el Id para que en el momento de agregar personas a la capacitacion en la tabla relacion_capacitacion se debe tener el id_capacitacion y el DNI
        $id = $request->post('id');
        $this->user_capacitacion = DB::table('relacion_capacitacion')
                        ->where('id_capacitacion', $id)
                        ->pluck('id_empleados')
                        ->toArray();
        $empleados = DB::table('mmsa_empleados')
                        ->select('DNI','Nombre','Apellido','Puesto','Area')
                        ->get();
        return datatables()->of($empleados)
        ->addColumn('action', function($data)
        {
            if(in_array($data->DNI, $this->user_capacitacion))
            {
                //Se agrega otro data para que al momento de realizar el check la otra funcion pueda recibir los parametros necesarios, em este caso para relacionar se necesita los parametros
                //"id_capacitacion" = es el id de la capacitacion que se creo
                //"DNI"= es el DNI de la persona para asignar una capacitacion
                // IMPORTANTE: la tabla empleados la columna PK es DNI pero para relacionar un empleado con una capacitacion la columna para agregar el emplead es "id_empleados"
                $check = '<input type="checkbox" class="checkpersonas" data-capacitacion="'.$this->id_capacitacion.'" data-empleado="'.$data->DNI.'" name="check_personas" value=1 checked>';
            }
            else
            {
                $check = '<input type="checkbox" class="checkpersonas" data-capacitacion="'.$this->id_capacitacion.'" data-empleado="'.$data->DNI.'" name="check_personas" value=1>';
            }
            return $check;
        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    /**
     * ! ver si cambiar el nombre de esta function !
     * Function capacitacion(): Esta function devuelve un listado de empleados asignado a una capacitacion ya, Al final se agrega la columna action en donde se agregan botones para aprobar o desaprobar un empleado
     */
    public function capacitacion(Request $request){
        $id = $request->post('id');
        $list = DB::table('relacion_capacitacion')
                        ->join('ssoma_capacitacion','ssoma_capacitacion.id','=','relacion_capacitacion.id_capacitacion')
                        ->join('mmsa_empleados','mmsa_empleados.DNI','=','relacion_capacitacion.id_empleados')
                        ->where('relacion_capacitacion.id_capacitacion', $id)
                        ->select('relacion_capacitacion.id','mmsa_empleados.DNI','mmsa_empleados.Nombre','mmsa_empleados.Apellido','mmsa_empleados.Puesto','ssoma_capacitacion.tema','relacion_capacitacion.Estado')
                        ->get();
        return datatables()->of($list)
        ->editColumn('Estado',function($data){
            if($data->Estado==0){
                $estado='Desaprobado';
                return $estado;
            }
            else
            {
                $estado='Aprobado';
                return $estado;
            }
        })
        ->addColumn('action', function($data)
        {
            if($data->Estado == 0)
            {
                $button = ''; 
                $button .= '<a href="javascript:void(0)" name="approved" data-id="'.$data->id.'" class="btn-action-table btn-aprobar" title="Aprobar"><i style="color:#0F62AC;" class="fas fa-user-check"></i></a>';  
                return $button;
            }
            else
            {
                $button = ''; 
                $button .= '<a href="javascript:void(0)" name="disapprove" data-id="'.$data->id.'" class="btn-action-table btn-aprobar" title="Desaprobar"><i style="color:#ff3d3d;" class="fas fa-user-times"></i></a>';  
                return $button;
            }
        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    /**
     * ! Esta mal escrito es "Approved" !
     * Function aproved(): Esta funcion lo que hace es un update del dato de aprobado o desaprobado
     */
    public function aproved(Request $request){
        $id = $request->get('id_relacion'); // este id relacion contiene el id de relacion_capacitacion tabla este nos sirve para modificar la aprobacion de los empleados
        $list = Relacion_capacitacion::findOrFail($id);// ver las diferencias entre un collection generic que es creatada cuando usas un (DB::Table)
        if($list->Estado == 0)//esto si el usuario esta desaprobado pasa a ser aprobado
        {
            Relacion_capacitacion::where('id',$id)
                                ->update(['Estado'=>1]);
            return;
        }
        else //este aprueba directamente al empleado si esta desaprobado
        {
            Relacion_capacitacion::where('id',$id)
                                ->update(['Estado'=>0]);
            return;
        }
    }

    /**
     * Function ABempleados: Esta funcion realiza Altas y Bajas de empleados en la tabla "relacion_capacitacion"
     */
    public function ABEmpleados(Request $request){
        if($request->ajax()){
            //Segun el estado que envio por la solicitud veo si hago un alta o una baja
            //1 = ALTA en la tabla "relacion_capacitacion"
            if ($request->input('state') == 1){
                Relacion_capacitacion::create([
                    'id_capacitacion'=>$request->get('id_capacitacion'),
                    'id_empleados'=>$request->get('id_empleados'),
                    'Estado'=>0,
                    'Fecha'=>date('Y-m-d')
                ]);
                return response()->json(['respuesta'=>'Se asigno una persona a la capacitacion']);
            }
            //0 = BAJA hago e filtro con 2 condicionales el "id_empleados" y "id_capacitacion"
            else{
                $generic = Relacion_capacitacion::where('id_capacitacion',$request->get('id_capacitacion'))
                                                ->where('id_empleados',$request->get('id_empleados'));
                $generic->delete();
                return response()->json(['respuesta'=>'Se quito una persona a la capacitacion']);
            }
        }
        else{
            abort(404);
        }
    }

    /**
     * Function ACapacitacion: ALTA y MODIFICACION de una capacitacion
     * recibir parametro active=1 para que la capacitacion se pueda visualizar 
     */
    public function AMCapacitacion(Request $request){
        if($request->ajax()){
            $id = $request->get('id');
            if($id==''||$id==null){
                $validator = Validator::make(
                    $request->all(),
                    [  
                        'fecha'=>'required',
                        'hora'=>'required',
                        'tema'=>'required | string|min:3|max:100',
                        'capacitador'=>'required | string|min:3|max:100',
                        'area'=>'required | string|min:2|max:100',
                        'lugar'=>'required | string|min:3|max:100',
                        'duracion'=>'required| numeric'
                    ]             
                );
                if ($validator->fails()) 
                {
                    return response()->json(['error'=>$validator->errors()->all()]);
                }
                else
                {
                    Ssoma_capacitacion::create(
                        [
                            'fecha'=>$request->get('fecha'),
                            'hora'=>$request->get('hora'),
                            'tema'=>$request->get('tema'),
                            'capacitador'=>$request->get('capacitador'),
                            'area'=>$request->get('area'),
                            'lugar'=>$request->get('lugar'),
                            'duracion'=>$request->get('duracion'),
                            'active'=>$request->get('active')
                        ]);
                    return;                
                }
            }
            else // 
            {
                $validator = Validator::make(
                    $request->all(),
                    [  
                        'id'=>'required|numeric',
                        'fecha'=>'required',
                        'hora'=>'required',
                        'tema'=>'required | string|min:3|max:100',
                        'capacitador'=>'required | string|min:3|max:100',
                        'area'=>'required | string|min:2|max:100',
                        'lugar'=>'required | string|min:3|max:100',
                        'duracion'=>'required| numeric'
                    ]             
                );
                if ($validator->fails()) 
                {
                    return response()->json(['error'=>$validator->errors()->all()]);
                }
                else
                {
                    Ssoma_capacitacion::where('id',$id)
                    ->update(
                        [
                            'fecha'=>$request->get('fecha'),
                            'hora'=>$request->get('hora'),
                            'tema'=>$request->get('tema'),
                            'capacitador'=>$request->get('capacitador'),
                            'area'=>$request->get('area'),
                            'lugar'=>$request->get('lugar'),
                            'duracion'=>$request->get('duracion')
                        ]);
                    return;                
                }
            }
        }
    }

    /**
     * Function edit(): hace un get de los registros
     */
    public function edit($id){        
        $where = array('id' => $id);
        $generic =  DB::table('ssoma_capacitacion')
        ->where($where)
        ->first();
        return response()->json($generic);
    }

    /**
      * Function delete(): Realiza un aupdate del estado en desactivado del registro para tener un historico
      */
      public function delete($id){
        Ssoma_capacitacion::where('id',$id)
                            ->update(['active'=>0]);
        return;
    }

}
