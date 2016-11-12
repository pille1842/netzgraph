<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    public function person()
    {
    	return $this->belongsTo('App\Person');
    }

    public function earninglevel()
    {
    	return $this->belongsTo('App\Earninglevel');
    }
}
