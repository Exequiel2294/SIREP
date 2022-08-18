<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Data;
use App\Http\Controllers\Controller;
use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class HistorialVariableController extends Controller
{

    public function index()
    {
        //$variables = Variable::where('descripcion','like','%MMSA%')->get();
        $usuario = auth()->id();
        $variables =  DB::table('variable as v')
            ->join('subcategoria as s', 'v.subcategoria_id', '=', 's.id')
            ->join('permisos_variables as p', 'v.id', '=', 'p.variable_id')
            ->select('v.id as id', 's.nombre as area', 'v.nombre as nombre')
            ->where('v.tipo','<>',4)
            ->where('p.user_id','=',$usuario)
            ->orderBy('v.orden')
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
                    ->get();

        foreach ($datos as $key => $d) {
            $d->valor = round($d->valor,2);
        }
                
        return datatables()->of($datos)
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
        $where = array('id' => $id);
        $generic =  DB::table('data')
        ->where($where)
        ->first();
        return response()->json($generic);
    }

    public function delete($id)
    {
        $generic = Area::findOrFail($id);   
        $generic->delete(); 
        return;
    }
}
