<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetHistorial extends Model
{
    protected $table = 'budget_historial';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];
}
