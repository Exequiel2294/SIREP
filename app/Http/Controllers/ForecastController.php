<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Area;
use App\Models\Forecast;
use App\Models\ForecastHistorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForecastController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:forecast module']);
    }

    // FORECAST INDIVIDUAL 
        public function FI_index()
        {
            $areas = Area::orderBy('nombre')->pluck('nombre','id')->toArray();        
            return view('forecast_individual', compact('areas'));
        }

        public function FI_getVariables(Request $request)
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

        public function FI_getValores(Request $request)
        {       
            $variable_id = $request->variable_id;
            $fd = $request->fd;
            $fh = $request->fh;

            $datos = DB::table('forecast as f')
                        ->join('variable as v', 'f.variable_id', '=', 'v.id')
                        ->join('subcategoria as s', 'v.subcategoria_id', '=', 's.id')
                        ->select('f.id as id', 'v.unidad as unidad', 'v.nombre as nombre','s.nombre as area','f.valor as valor','f.fecha as fecha')
                        ->where('f.variable_id','=',$variable_id)
                        ->whereBetween('f.fecha', [$fd, $fh])
                        ->orderBy('f.fecha', 'asc')
                        ->get();

                    
            return datatables()->of($datos)
                    ->addColumn('valor', function($data)
                    {
                        if(isset($data->valor)) 
                        { 
                            return number_format($data->valor, 4, '.', ',');                        
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

        public function FI_edit($id)
        { 
            $forecast_register = Forecast::findOrFail($id);

            if ( $forecast_register->fecha < date('Y-m-d') ) { 
                $data['val'] = 0;
                $data['msg'] =  "No se puede modificar valores de fechas pasadas";
            }
            else {  
                $data['val'] = 1;
                $data['generic'] =  $forecast_register;
            }         
            return response()->json($data);         
        }

        public function FI_load(Request $request)
        {       
            $id = $request->get('id');
            if($id == '' || $id == null)
            {
    
            }
            else
            {
                $validator = Validator::make(
                    $request->all(),
                    [   
                        'id'    => 'required|numeric|exists:forecast,id',
                        'valor' => [
                            'required',
                            function ($attribute, $value, $fail) use($request) {
                                $variable = DB::table('variable')
                                    ->join('forecast', 'forecast.variable_id', '=','variable.id')
                                    ->where('forecast.id', $request->get('id'))
                                    ->select('variable.valor_max as max', 'variable.valor_min as min')
                                    ->first();
                                if (isset($variable->min) && isset($variable->max)){
                                    if ($value < $variable->min  || $value > $variable->max){
                                        $fail('El valor ingresado debe que encontrarse en el rango ['.$variable->min.', '.$variable->max.']');
                                    }
                                }
                            }
                        ]
                    ]            
                );
                if ($validator->fails()) 
                {
                    return response()->json(['error'=>$validator->errors()->all()]);
                }
                else
                {
                    $forecast = Forecast::findOrFail($id);
                    $oldvalue = $forecast->valor;
                    $newvalue = $request->get('valor');
                    $forecast->update(
                        [
                            'valor' =>$newvalue
                        ]);

                    if($oldvalue != $newvalue)
                    {
                        ForecastHistorial::create([
                            'forecast_id' => $id,
                            'fecha' => date('Y-m-d H:i:s'),
                            'transaccion' => 'EDIT',
                            'valorviejo' => $oldvalue,
                            'valornuevo' => $newvalue,
                            'usuario' => auth()->user()->name
                        ]);
                    }
                    return;                
                }
            }        
        } 
    // FIN

    //FORECAST GROUPAL
        public function FG_index()
        {
            $areas = Area::orderBy('nombre')->pluck('nombre','id')->toArray();        
            return view('forecast_grupal', compact('areas'));
        }

        public function FG_getVariables(Request $request)
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

        public function FG_getValores(Request $request)    {   
            $fd = $request->fd;
            $fh = $request->fh;
            $vs_id = $request->vs_id;
            $vsc_id = $request->vsc_id;
            if($request->area_id == 1){
                $datos = DB::select('SELECT * FROM 
                (SELECT  [variable_id], [fecha], [valor] FROM [forecast] WHERE VARIABLE_ID IN '.$vs_id.' AND fecha between ? AND ?) AS SRC 
                pivot (MAX(valor) for variable_id in '.$vsc_id.') PVT 
                order by fecha',
                [$fd, $fh]);
            }
            else{
                $datos = DB::select('SELECT * FROM 
                (SELECT  [variable_id], [fecha], [valor] FROM [forecast] WHERE VARIABLE_ID IN '.$vs_id.' AND fecha between ? AND ?) AS SRC 
                pivot (MAX(valor) for variable_id in '.$vsc_id.') PVT 
                order by fecha',
                [$fd, $fh]);
            }
                    
            return datatables()->of($datos)
                    ->addIndexColumn()
                    ->make(true);          
        } 

        public function FG_getColumnas(Request $request)
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
    //FIN


}
