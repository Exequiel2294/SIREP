<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    public $timestamps = false;
    protected $table = 'variable';

    protected $guarded = [
        'id'
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'permisos_variables','user_id','variable_id');
    }
}
