<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastHistorial extends Model
{
    protected $table = 'forecast_historial';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
