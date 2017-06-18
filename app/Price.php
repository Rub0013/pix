<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['device_id','service_id','price'];

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function service()
    {
        return $this->belongsTo('App\Service');
    }
}
