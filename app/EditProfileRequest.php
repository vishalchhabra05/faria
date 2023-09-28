<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;


class EditProfileRequest extends Model
{
    protected $guarded = [];
    
    public function UserDetail(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
