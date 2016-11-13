<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    public function persons()
    {
    	return $this->hasMany('App\Person');
    }
}
