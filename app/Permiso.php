<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $fillable=['user_id','variable_id','created_at','updated_at'];
    protected $table = 'permisos_variables';
}
