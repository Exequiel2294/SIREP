<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConciliadoHistorial extends Model
{
    protected $table = 'conciliado_historial';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
