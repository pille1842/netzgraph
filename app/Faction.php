<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    public function persons()
    {
    	return $this->hasMany('App\Person');
    }

    public function earnings()
    {
    	return $this->hasManyThrough('App\Earning', 'App\Person');
    }
}
