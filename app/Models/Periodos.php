<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodos extends Model
{
    protected $table = 'periodos';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
