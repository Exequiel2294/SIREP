<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComentarioArea extends Model
{
    public $timestamps = false;
    protected $table = 'comentario_area';
    protected $guarded = [
        'id'
    ];
}
