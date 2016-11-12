<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
	protected $table = 'persons';

    public function faction()
    {
    	return $this->belongsTo('App\Faction');
    }

    public function committees()
    {
    	return $this->belongsToMany('App\Committee')->withPivot('role');
    }

    public function district()
    {
    	return $this->belongsTo('App\District');
    }

    public function earnings()
    {
    	return $this->hasMany('App\Earning');
    }

    public function state()
    {
    	return $this->belongsTo('App\State');
    }
}
