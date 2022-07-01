<?php

namespace App\Http\Controllers;

use App\Models\Comentario;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Models\ActiveDirectory\Group;
use Illuminate\Support\Facades\Auth;


class ComentarioController extends Controller
{

    public function load(Request $request)
    {
        if (Auth::user()->hasAnyRole(['Reportes_E', 'Admin']))
        {
            $id = $request->get('id');        
            if($id == '' || $id == null)
            {            
                $validator = Validator::make(
                    $request->all(),
                    [   
                        'area_id_comentario' => 'required|exists:comentario_area,id',
                        'comentario' => 'required|string|min:5|max:1000'
                    ]             
                );
                if ($validator->fails()) 
                {
                    return response()->json(['error'=>$validator->errors()->all()]);
                }
                else
                {
                    Comentario::create(
                    [
                        'user_id' => auth()->user()->id,
                        'area_id' => $request->get('area_id_comentario'),
                        'comentario' => $request->get('comentario'),
                        'fecha' => $request->get('selecteddate'),
                        'created_at' => date('Y-m-d'),
                        'updated_at' => date('Y-m-d')
                    ]);
                    return;                
                }
            }
            else
            {
                $validator = Validator::make(
                    $request->all(),
                    [   
                        'id'    => 'required|numeric|exists:comentario,id',  
                        'area_id_comentario' => 'required|exists:comentario_area,id',
                        'comentario' => 'required|string|min:5|max:1000'
                    ]            
                );
                if ($validator->fails()) 
                {
                    return response()->json(['error'=>$validator->errors()->all()]);
                }
                else
                {
                    Comentario::where('id',$id)
                        ->update(
                        [                      
                            'user_id' => auth()->user()->id,
                            'area_id' => $request->get('area_id_comentario'),
                            'comentario' => $request->get('comentario'),
                            'fecha' => $request->get('selecteddate'),
                            'created_at' => date('Y-m-d'),
                            'updated_at' => date('Y-m-d')
                        ]);
                    return;                
                }
            }
        }   
    }

    public function comentariostable (Request $request)
    {
        if ($request->ajax())
        {
            $table =
            DB::select(
                'SELECT c.id AS id, ca.nombre AS area, c.comentario AS comentario, u.name AS usuario FROM comentario c
                INNER JOIN users u
                ON c.user_id = u.id
                INNER JOIN comentario_area ca
                ON c.area_id = ca.id
                WHERE DATEPART(y, c.fecha) = ?',
                [(int)date('z', strtotime(date($request->get('fecha')))) + 1]
            );
                            
            return datatables() ->of($table)
                    ->addColumn('action', function($data)
                    {
                        $button = ''; 
                        $button .= '<a href="javascript:void(0)" name="edit" data-id="'.$data->id.'" class="btn-action-table edit-comentario" title="Editar registro"><i style="color:#0F62AC;" class="fa fa-edit"></i></a>';  
                        $button .= '&nbsp;';
                        $button .= '<a href="javascript:void(0)" name="delete" data-id="'.$data->id.'" class="btn-action-table delete-comentario" title="Eliminar registro"><i class="fa fa-times-circle text-danger"></i></a>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->addIndexColumn()
                    ->make(true);

        }
    }

    public function edit($id)
    {        
        $comentario = Comentario::findOrFail($id);
        if ($comentario->user_id <> Auth()->user()->id)
        {
            $data['msg'] = 'Este comentario no es de su autoria.';
            $data['val'] = -1;
            return response()->json($data);
        }
        $where = array('id' => $id);
        $data['val'] = 1;
        $data['generic'] =  DB::table('comentario')
            ->where($where)
            ->select('id', 'area_id','comentario')
            ->first();
        return response()->json($data);
    }

    public function delete($id)
    {
        $comentario = Comentario::findOrFail($id);
        if ($comentario->user_id <> Auth()->user()->id)
        {
            $data['msg'] = 'Este comentario no es de su autoria.';
            $data['val'] = -1;
            return response()->json($data);
        }
        $data['val'] = 1;
        $comentario->delete();
        return response()->json($data);
        

    }
}



