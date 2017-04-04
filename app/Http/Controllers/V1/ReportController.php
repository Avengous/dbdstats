<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Queries;

class ReportController extends Controller
{	
	use Queries;
	
    public function matchDetails($summonerId, $matchId) {
        return $this->findUniqueMatchByIds($summonerId, $matchId);
    }
	
	public function recentMatchDetails($summonerName, $matchCount=5){
		$summonerId = $this->summonerIdByName($summonerName);
		return $this->recentMatches($summonerId, $matchCount)->get();
	}
	
	public function getSummonerStatsFromMatch($summonerName, $matchId) {
		$summonerId = $this->summonerIdByName($summonerName);
		return $this->matchDetails($summonerId, $matchId);
	}
	
	public function defineMatchRole($role, $lane) {
		if ($lane == 'BOTTOM') {
			if ($role == 'DUO_SUPPORT') {
				return 'Support';
			} elseif ($role == 'DUO_CARRY') {
				return 'ADC';
			}
		} elseif ($lane == 'JUNGLE') {
			return 'Jungle';
		} elseif ($lane == 'MIDDLE') {
			return 'Mid';
		} elseif ($lane == 'TOP') {
			return 'Top';
		} else {
			return 'Unknown';
		}
	}
	
	public function championName($championId) {
		return $this->championNameById($championId);
	}
}