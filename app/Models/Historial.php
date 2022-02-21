<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = 'historial';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
