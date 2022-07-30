<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class Website extends Controller
{
    public function index()
    {   
        $data = ['name' => "Vishal", 'data' => "Hello Vishal"];
        $user['to']= 'ejensen@mansfieldmin.com';
        Mail::send('mail', $data, function($messages) use ($user){
            $messages->to('ejensen@mansfieldmin.com');
            $messages->subject('Hello Dev');
        });
    }
}
