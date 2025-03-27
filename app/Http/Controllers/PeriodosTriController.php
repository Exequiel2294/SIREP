<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Models\Events\Updated;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use Symfony\Component\HttpFoundation\RateLimiter\RequestRateLimiterInterface;
use App\Http\Controllers\Response;
use App\Models\Periodos;
use App\Models\Periodos_tri;
use Dotenv\Result\Success;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

class PeriodosTriController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:periods module']);
    }
    public function index(Request $request)
    {
        if(request()->ajax()) {
            $list = DB::table('periodos_tri')
                        ->select('id','anio','periodo','fecha_ini','fecha_fin','descripcion')
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
        return view('periodostri');
    }
    
    /**
     * Function index(): funcion en donde crea un registro para 
     */
    public function load(Request $request){
        
        $id = $request->get('id');
        //return dd($id);
        if($id == '' || $id == null)
        {
            
            $validator = Validator::make(
                $request->all(),
                [   
                    'anio' => 'required|integer|min:1900|max:2099',
                    'periodo' => 'required|integer|between:1,4',
                    'descripcion' => 'required|string|min:2|max:250',
                    'fecha_ini' => 'required|date|before_or_equal:fecha_fin',
                    'fecha_fin' => 'required|date|after_or_equal:fecha_ini'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Periodos_tri::create(
                    [
                        'anio' => $request->get('anio'),
                        'periodo' => $request->get('periodo'),
                        'fecha_ini' => $request->get('fecha_ini'),
                        'fecha_fin' => $request->get('fecha_fin'),
                        'descripcion' => $request->get('descripcion')
                    ]);
                return;                
            }
        }
        else
        {
             
            $validator = Validator::make(
                $request->all(),
                [   
                    'anio' => 'required|integer|min:1900|max:2099',
                    'periodo' => 'required|integer|between:1,4',
                    'descripcion' => 'required|string|min:2|max:250',
                    'fecha_ini' => 'required|date|before_or_equal:fecha_fin',
                    'fecha_fin' => 'required|date|after_or_equal:fecha_ini'
                ]             
            );
            if ($validator->fails()) 
            {
                return response()->json(['error'=>$validator->errors()->all()]);
            }
            else
            {
                Periodos_tri::where('id',$id)
                    ->update(
                    [
                        'anio' => $request->get('anio'),
                        'periodo' => $request->get('periodo'),
                        'fecha_ini' => $request->get('fecha_ini'),
                        'fecha_fin' => $request->get('fecha_fin'),
                        'descripcion' => $request->get('descripcion')
                    ]);
                return;                  
            }
        }

    }

    /**
     * Function edit(): Realiza un get de los datos del registro seleccionado
    */
    public function edit($id){

        $generic = DB::table('periodos_tri')
        ->select('id','anio','periodo','fecha_ini','fecha_fin','descripcion')
        ->where(['id'=>$id])
        ->first();
        return response()->json($generic);
    }

    /**
     * Function delete(): Ocultar
     */
    public function delete($id){
        Periodos_tri::where('id',$id)
        ->delete();
        return;
    }
}
