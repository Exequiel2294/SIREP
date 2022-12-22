<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    public $timestamps = false;
    protected $table = 'budget';
    protected $guarded = [
        'id'
    ];
}
