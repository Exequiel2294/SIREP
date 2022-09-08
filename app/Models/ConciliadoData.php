<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConciliadoData extends Model
{
    public $timestamps = false;
    protected $table = 'conciliado_data';
    protected $guarded = [
        'id'
    ];
}
