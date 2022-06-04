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


class ComentarioController extends Controller
{
    public function index(Request $request)
    {
        return view('comentario');
    }

    public function load(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'asunto' => 'required|string|min:3|max:100',
                'comentario' => 'required|string|min:5|max:250'
            ]
        );
        if ($validator->fails())
        {
            return response()->json(['error' =>$validator->errors()->all()]);
        }
        else
        {
            Comentario::create(
            [
                'user_id' => auth()->user()->id,
                'asunto' => $request->get('asunto'),
                'comentario' => $request->get('comentario')
            ]);
        }
    }

    public function show($timezone)
    {
        $this->fecha = date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
        $timeline ='';
        $comentarios =  
         DB::table('comentario')
            ->join('users','comentario.user_id', '=', 'users.id')
            ->select('comentario.asunto as asunto', 'comentario.comentario as comentario', 'comentario.created_at as fecha', 'users.name as user','comentario.user_id as user_id','comentario.id as id')
            ->orderby('fecha','desc')
            ->get();

        foreach($comentarios as $comentario)
        { 
            if ($this->fecha <> date('Y-m-d',strtotime($comentario->fecha)))
            {
                $timeline.=
                '<div class="time-label">
                    <span class="bg-red">'.date('Y-m-d', strtotime('-'.$timezone.' minutes',strtotime($comentario->fecha))).'</span>
                </div>';
                $this->fecha = date('Y-m-d',strtotime($comentario->fecha));
            }
            $timeline.=
            '<div>
                <i class="fas fa-comments bg-yellow"></i>
                <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> '.date('H:i:s', strtotime('-'.$timezone.' minutes',strtotime($comentario->fecha))).'</span>
                    <h3 class="timeline-header"><a href="#">'.$comentario->user.'</a> '.$comentario->asunto.'</h3>
                    <div class="timeline-body">'.$comentario->comentario.'</div>';
            
            if ($comentario->user_id == auth()->user()->id)
            {
                $timeline.= 
                '<div class="timeline-footer">
                    <a class="btn btn-danger btn-sm delete" id='.$comentario->id.'>Eliminar</a>
                </div>';
            }
            
            $timeline.=
                '</div>
            </div>';
        }

        $timeline.='<div>
            <i class="fas fa-clock bg-gray"></i>
        </div>';

        return $timeline;
    }

    public function delete($id)
    {
        $where = ['id' => $id, 'user_id'=> auth()->user()->id];
        Comentario::where($where)->delete();

    }
}



