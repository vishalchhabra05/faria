<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = ['user_id','address','lat','long','state','defult','created_at','updated_at'];
}
