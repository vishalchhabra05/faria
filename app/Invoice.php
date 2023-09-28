<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
class Invoice extends Model
{
    use Sluggable;
    use SluggableScopeHelpers;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'comment'
            ]
        ];
    }

    protected $guarded = [];

    public function order(){
        return $this->belongsTo('App\Order','order_id','id');
    }

    public function assine(){
        return $this->belongsTo('App\AssignOrder','id','order_id')->where('status','!=','0');
    }

    public function assineOne(){
        return $this->belongsTo('App\AssignOrder','order_id','order_id')->where('status','!=','0');
    }


    public function Bank_info(){
        return $this->belongsTo('App\BankingInfo','user_id','user_id');
    }
}
