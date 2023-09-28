<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use Sluggable;
    use SluggableScopeHelpers;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'email'
            ]
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $hidden = [
        'password', 'remember_token','email_verified_at'
    ];


    public function roles() {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }

    public function provider_status(){
        return $this->belongsTo('App\ServiceProviderRequest','id','user_id');
    }

    public function bussiness(){
        return $this->belongsTo('App\BusinessInformation','id','user_id');
    }

    public function user_service(){
        return $this->belongsTo('App\ProfessionalService','id','user_id');
    }

    public function insurance(){
        return $this->belongsTo('App\ProfessionalDetail','id','user_id');
    }

    public function review(){
        return $this->belongsTo('App\ReviewLink','id','user_id');
    }

    public function firstRef(){
        return $this->belongsTo('App\Reference','id','user_id');
    }

    public function secondRef(){
        return $this->belongsTo('App\SecondReference','id','user_id');
    }

    public function bank_address(){
        return $this->belongsTo('App\BankAddress','id','user_id');
    }

    public function Account_info(){
        return $this->belongsTo('App\AccountInformation','id','user_id');
    }

    public function Bank_info(){
        return $this->belongsTo('App\BankingInfo','id','user_id');
    }

    public function service(){
        return $this->belongsTo('App\Service','id','service_id');
    }

    public function getaddress(){
      
        return $this->belongsTo('App\UserAddress','id','user_id');
    }

}
