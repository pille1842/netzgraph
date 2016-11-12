<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Earninglevel extends Model
{
    public function earnings()
    {
    	return $this->hasMany('App\Earning');
    }
}
