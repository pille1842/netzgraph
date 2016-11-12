<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public function persons()
    {
    	return $this->hasMany('App\Person');
    }
}
