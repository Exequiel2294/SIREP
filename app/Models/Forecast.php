<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    public $timestamps = false;
    protected $table = 'forecast';
    protected $guarded = [
        'id'
    ];
}
