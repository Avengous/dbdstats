<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Summoner extends Model
{
    protected $table = 'summoners';

    public function name() {
    	return $this->summoner_name;
    }

    public function matches() {
    	return $this->hasMany('App\Report')
    }
}
