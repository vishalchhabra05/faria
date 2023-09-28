<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignOrder extends Model
{
    protected $fillable = ['user_id','order_id','comment','status','created_at','updated_at'];

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function order(){
        return $this->belongsTo('App\Order','order_id','id');
    }

    public function quoteprice(){
        return $this->belongsTo('App\QuotePrice','order_id','order_id');
    }

   
}
