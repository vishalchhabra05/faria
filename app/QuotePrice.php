<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotePrice extends Model
{
    protected $guarded = [];

    public function order(){
        return $this->belongsTo('App\Order','order_id','id');
    }
    
}
