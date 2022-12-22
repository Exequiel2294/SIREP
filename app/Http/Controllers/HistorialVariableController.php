<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Http\Controllers\Controller;
use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class HistorialVariableController extends Controller
{

    public function index()
    {
        $usuario = auth()->id();
        $variables =  DB::table('variable as v')
            ->join('subcategoria as s', 'v.subcategoria_id', '=', 's.id')
            ->join('permisos_variables as p', 'v.id', '=', 'p.variable_id')
            ->select('v.id as id', 's.nombre as area', 'v.nombre as nombre')
            ->where('v.tipo','<>',4)
            ->where('v.estado', '=', 1)
            ->where('p.user_id','=',$usuario)
            ->orderBy('s.nombre', 'asc')
            ->orderBy('v.orden', 'asc')
            ->get();
        
        return view('variable_historial',['variables'=>$variables]);
    }

    public function getValores(Request $request)
    {       
        $id = $request->id;
        $fd = $request->fd;
        $fh = $request->fh;

        $datos = DB::table('data as d')
                    ->join('variable as v', 'd.variable_id', '=', 'v.id')
                    ->join('subcategoria as s', 'v.subcategoria_id', '=', 's.id')
                    ->select('d.id as id', 'v.unidad as unidad', 'v.nombre as nombre','s.nombre as area','d.valor as valor','d.fecha as fecha')
                    ->where('d.variable_id','=',$id)
                    ->whereBetween('d.fecha', [$fd, $fh])
                    ->orderBy('d.fecha', 'asc')
                    ->get();

        /*foreach ($datos as $key => $d) {
            $d->valor = round($d->valor,2);
        }*/
                
        return datatables()->of($datos)
                ->addColumn('valor', function($data)
                {
                    if(isset($data->valor)) 
                    { 
                        return number_format($data->valor, 12, '.', ',');                        
                    }        
                    else
                    {
                        return '-';
                    }
                })
                ->addColumn('action', function($data)
                {
                    $button = ''; 
                    $button .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" class="btn-action-table edit" title="Editar registro"><i style="color:#0F62AC;" class="fa fa-edit"></i></a>';  
                    return $button;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);          
    } 

    public function edit($id)
    { 
        $data_register = Data::findOrFail($id);

        $where = ['user_id' => Auth::user()->id, 'variable_id' => $data_register->variable_id];    
        $uservbles = DB::table('permisos_variables')
                    ->where($where)
                    ->get();
        if ($uservbles->count() == 0)
        {
            $data['msg'] = 'No cuenta con los permisos necesarios para editar esta variable.';
            $data['val'] = -1;
            return response()->json($data);
        }
        else
        {
            if ((date('m', strtotime($data_register->fecha)) == date('m') && date('Y', strtotime($data_register->fecha)) == date('Y')) || 
            (date('m', strtotime($data_register->fecha)) == date('m') - 1 && date('Y', strtotime($data_register->fecha)) == date('Y') && date('d') <= 10))
            {
                if (date('Y-m-d', strtotime($data_register->fecha)) == date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))))
                {  
                    $vbles_11h = DB::table('variable')->where('tipo',2)->pluck('id')->toArray();
                    if (in_array($data_register->variable_id,$vbles_11h) && (int)date('H') < 14)
                    {
                        $data['msg'] = 'No puede modificar esta variable hasta que la misma sea cargada a las 11hs';
                        $data['val'] = -1;
                        return response()->json($data);
                    }
                    else
                    {     
                        $vbles_10h = DB::table('variable')->where('tipo',9)->pluck('id')->toArray();
                        if (in_array($data_register->variable_id,$vbles_10h) && (int)date('H', time() - 3600 * 3) < 10)
                        {
                            $data['msg'] = 'No puede modificar esta variable hasta que la misma sea cargada a las 10hs';
                            $data['val'] = -1;
                            return response()->json($data);
                        }
                        else
                        {       
                            $vbles_11_21h = DB::table('variable')->where('tipo',5)->pluck('id')->toArray();            
                            if (in_array($data_register->variable_id,$vbles_11_21h))
                            {
                                if ((int)date('H', time() - 3600 * 3) < 11)
                                {
                                    $data['msg'] = 'No puede modificar esta variable hasta que la misma sea cargada a las 11hs';
                                    $data['val'] = -1;
                                    return response()->json($data);
                                }
                                else
                                {
                                    if ((int)date('H', time() - 3600 * 3) < 21)
                                    {
                                        $data['msg'] = 'El valor de esta variable se sobreescribirá a 21hs. del dia corriente.';
                                        $data['val'] = 2;
                                        $data['generic'] =  $data_register;
                                        return response()->json($data);      
                                    }
                                }
                            }    
                        }                     
                    }
                }                     
                

                
                $data['val'] = 1;
                $data['generic'] =  $data_register;
                return response()->json($data);    
                

            }
            else
            {
                $data['msg'] = 'No puede modificar data que ya fue conciliada.';
                $data['val'] = -1;
                return response()->json($data);   
            }
            
        
        }
        
    }

}
