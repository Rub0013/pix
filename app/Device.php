<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['model'];

    public function prices()
    {
        return $this->hasMany('App\Price');
    }
}
