<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
class CancelPriceMaster extends Model
{
    use Sluggable;
    use SluggableScopeHelpers;
    
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'type'
            ]
        ];
    }


    protected $guarded = [];
}
