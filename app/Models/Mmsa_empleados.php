<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mmsa_empleados extends Model
{
    protected $table = 'mmsa_empleados';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
