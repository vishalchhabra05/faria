<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class AccountInformation extends Model
{
    use HasApiTokens, Notifiable;
    use Sluggable;
    use SluggableScopeHelpers;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'first_name'
            ]
        ];
    }
   
    protected $fillable = ['user_id','slug','first_name','last_name','dob','insurance_number','hst_number','ssn_number'];

    public function addressget(){
        return $this->belongsTo('App\BusinessInformation','user_id','user_id');
    }
}
