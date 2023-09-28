<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Price extends Model
{
    use HasApiTokens, Notifiable;
    use Sluggable;
    use SluggableScopeHelpers;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'varient'
            ]
        ];
    }

    protected $guarded = [];

    public function service(){
        return $this->belongsTo('App\Service','service_id','id');
    }

    public function service_get(){
        return $this->belongsTo('App\Service','id','service_id');
    }

}
