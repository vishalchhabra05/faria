<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Service extends Model
{
    use HasApiTokens, Notifiable;
    use Sluggable;
    use SluggableScopeHelpers;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'service_name'
            ]
        ];
    }


    protected $guarded = [];


    public function category_get(){
        return $this->belongsTo('App\Category','category_id','id');
    }

    public function category(){
        return $this->belongsTo('App\Category','category_id','id')->where('status',1);
    }

    public function priceGet(){
     
        return $this->belongsTo('App\Price','id','service_id');
    }

}
