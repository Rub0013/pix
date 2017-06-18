<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['description'];

    public function prices()
    {
        return $this->hasMany('App\Price');
    }
}
