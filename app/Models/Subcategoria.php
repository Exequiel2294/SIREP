<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    public $timestamps = false;
    protected $table = 'subcategoria';
    protected $guarded = [
        'id'
    ];
}
