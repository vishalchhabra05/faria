<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $guarded = [];
    function user(){
        return $this->hasOne('App\User','id','user_id');
    }

    function order(){
        return $this->hasOne('App\AssignOrder','id','order_id')->where('status','!=','0');
    }

    function invoice(){
        return $this->hasOne('App\Invoice','order_id','order_id');
    }

    public function roles() {
        return $this->hasOne('App\RoleUser', 'user_id', 'user_id');
    }

    public function rolename($id) {
       $data = Role::where('id',$id)->first();
       return $data;
    }

}
