<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\BudgetHistorial;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Budget;
use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class HistorialVariablesController extends Controller
{

    public function index()
    {
        $areas = Area::orderBy('nombre')->pluck('nombre','id')->toArray();        
        return view('historialvariables', compact('areas'));
    }

    public function getvariables(Request $request)
    {      
        $query = DB::table('variable as v')
                ->select('v.id','v.nombre as variable', 'sb.nombre as subcategoria')
                ->join('subcategoria as sb','v.subcategoria_id', '=', 'sb.id')
                ->join('categoria as c','sb.categoria_id', '=', 'c.id')
                ->where(['c.area_id' => $request->area_id, 'v.estado' => 1, 'sb.estado' => 1, 'c.estado' => 1])
                ->orderBy('v.orden', 'asc')
                ->get();
        $output = '';
        $output .= '<option value="" selected disabled>Seleccione Variable</option>';
        foreach($query as $row)
        {
            $output .= '<option value="'.$row->id.'">'.$row->subcategoria.' - '.$row->variable.'</option>';       
        }
        $data['result']=$output;
        echo json_encode($data);
    }

    public function getvalores(Request $request)    {   
        $fd = $request->fd;
        $fh = $request->fh;
        $vs_id = $request->vs_id;
        $vsc_id = $request->vsc_id;
        if($request->area_id == 1){
            $datos = DB::select('SELECT * FROM 
            (SELECT  [variable_id], [fecha], [valor] FROM [MMSA_SIREP_DATA] WHERE VARIABLE_ID IN '.$vs_id.' AND fecha between ? AND ?) AS SRC 
            pivot (MAX(valor) for variable_id in '.$vsc_id.') PVT 
            order by fecha',
            [$fd, $fh]);
        }
        else{
            $datos = DB::select('SELECT * FROM 
            (SELECT  [variable_id], [fecha], [valor] FROM [MMSA_SIREP_DATA_MINA] WHERE VARIABLE_ID IN '.$vs_id.' AND fecha between ? AND ?) AS SRC 
            pivot (MAX(valor) for variable_id in '.$vsc_id.') PVT 
            order by fecha',
            [$fd, $fh]);
        }
                
        return datatables()->of($datos)
                ->addIndexColumn()
                ->make(true);          
    } 

    public function getcolumnas(Request $request)
    {        
        if ($request->vs_id == null){$vs_id = [];}
        else{$vs_id = $request->vs_id;}
        $headers = DB::table('variable as v')
                ->join('subcategoria as s', 'v.subcategoria_id', '=', 's.id')
                ->select('v.id as variable_id', 'v.nombre as variable', 's.nombre as subcategoria')
                ->whereIn('v.id', $vs_id)
                ->orderBy('s.orden', 'asc')   
                ->orderBy('v.orden', 'asc')                
                ->get()
                ->toArray();

        $headerp = DB::table('variable as v')
                ->join('subcategoria as s', 'v.subcategoria_id', '=', 's.id')
                ->select('s.id as subcategoria_id','s.nombre as subcategoria')
                ->selectRaw('count(s.id) as cantidad')
                ->whereIn('v.id', $vs_id)
                ->groupBy('s.id','s.nombre', 's.orden')
                ->orderBy('s.orden', 'asc')   
                ->get()
                ->toArray();

        $columnas['columnasp'] = $headerp;
        $columnas['columnass'] = $headers;

        return response()->json($columnas);
    }

}
//512383.00000000
