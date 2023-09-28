<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;


class StripeCard extends Model
{
    protected $guarded = [];
    public function User(){
        return $this->belongsTo('App\User','id','user_id');
    }

}
