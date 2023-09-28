<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','service_id','address','unit','desc','hours',
    'schedule','trust_fees','tax','min_price','quick_service','status','created_at','updated_at','promo','extra_price'];

    public function service(){
        return $this->belongsTo('App\Service','service_id','id');
    }

    public function assine(){
        return $this->belongsTo('App\AssignOrder','id','order_id')->where('status','!=','0');
    }

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function ratingGet(){
        return $this->belongsTo('App\Review','id','order_id');
    }

    public function disputeGet(){
        return $this->belongsTo('App\Dispute','id','order_id');
    }
    public function assign_order(){
        return $this->hasMany('App\AssignOrder','order_id','id');
    }

    public function priceget(){
        return $this->belongsTo('App\Price','service_id','service_id');
    }


    public function quotepriceget(){
        return $this->belongsTo('App\QuotePrice','id','order_id');
    }



}
