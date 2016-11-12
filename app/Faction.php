<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    public function persons()
    {
    	return $this->hasMany('App\Person');
    }
}
