<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $guarded = [];


    public function state(){
        return $this->belongsTo('App\Province','state_id','id');
    }
}
