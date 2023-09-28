<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCertificate extends Model
{
    protected $guarded = [];


    public function serviceUser(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function certificateServiceInfo(){
        return $this->belongsTo('App\Service','service_id','id');
    }
}
