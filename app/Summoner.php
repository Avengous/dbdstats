<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Summoner extends Model
{
    protected $table = 'summoners';
	
	public function scopeSummonerIdByName($query, $summonerName) {
		return $query->where('summoner_name', '=', $summonerName);
	}
}
