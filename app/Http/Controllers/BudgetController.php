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


class BudgetController extends Controller
{

    public function index()
    {
        $areas = Area::orderBy('nombre')->pluck('nombre','id')->toArray();        
        return view('budget', compact('areas'));
    }

    public function getvariables(Request $request)
    {      
        $query = DB::table('variable as v')
                ->select('v.id','v.nombre as variable', 'sb.nombre as subcategoria')
                ->join('subcategoria as sb','v.subcategoria_id', '=', 'sb.id')
                ->join('categoria as c','sb.categoria_id', '=', 'c.id')
                ->where(['c.area_id' => $request->area_id, 'v.estado' => 1])
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

    public function getValores(Request $request)
    {   
        $datos = DB::table('budget as b')
                    ->join('variable as v', 'b.variable_id', '=', 'v.id')
                    ->join('subcategoria as s', 'v.subcategoria_id', '=', 's.id')
                    ->select('b.id', 'v.nombre', 'b.fecha', 'b.valor','v.unidad')
                    ->where('v.id',$request->variable_id)
                    ->whereYear('b.fecha','=',$request->anio)
                    ->orderBy('b.fecha', 'asc')
                    ->get();

        /*foreach ($datos as $key => $d) {
            $d->valor = round($d->valor,2);
        }*/
                
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

    public function load(Request $request)
    {      
        
        $validator = Validator::make(
            $request->all(),
            [   
                'budget_load' => 'required|array',
                'budget_load.*.budget_id' => 'required|exists:budget,id',
                'budget_load.*.value' => 'nullable|numeric|between:0,100000000',
            ]            
        );
        if ($validator->fails()) 
        {
            return response()->json(['error'=>$validator->errors()->all()]);
        }
        else
        {
            foreach($request->get('budget_load') as $registro)
            {
                $budget = Budget::findOrFail($registro['budget_id']);
                $oldvalue = $budget->valor;
                $newvalue = $registro['value'];                
                $budget->update([
                    'valor' => $newvalue
                ]);
                if($oldvalue != $newvalue)
                {
                    BudgetHistorial::create([
                        'budget_id' => $budget->id,
                        'fecha' => date('Y-m-d H:i:s'),
                        'transaccion' => '',
                        'valorviejo' => $oldvalue,
                        'valornuevo' => $newvalue,
                        'usuario' => auth()->user()->name
                    ]);
                }
                
            }

        }
        return;
    } 

}
//512383.00000000
