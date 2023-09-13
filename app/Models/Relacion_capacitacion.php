<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relacion_capacitacion extends Model
{
    protected $table = 'relacion_capacitacion';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
