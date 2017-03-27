<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MatchController extends Controller
{
	public function getMatchList($summonerName){
		$summonerId = $this->summonerIdByName($summonerName);
		$matchlist = $this->riot->matchlist()->matchlist($summonerId);
		return $matchlist;
	}
	
	public function getMatch($matchId) {
		return $this->riot->match()->match($matchId, $raw = true);
	}
	
}
