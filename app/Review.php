<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];

    protected $appends = ['username'];

    public function getUserNameAttribute()
    {
         return $this->orders->user->first_name.' '.$this->orders->user->last_name;
        
          
    }

    public function orders() {
        return $this->belongsTo('App\Order', 'order_id');
    }
    
}
