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
	
	public function formatSeconds($duration) {
		$result = '';
		$seconds = intval($duration) % 60;
		$minutes = (intval($duration) / 60) % 60;
		$hours = (intval($duration) / 3600) % 24;

		if(($hours > 0) || ($result!="")) {
			$result .= str_pad($hours, 2, '0', STR_PAD_LEFT) . ':';
		} 

		if (($minutes > 0) || ($result!="")) {
			$result .= str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':';
		} 

		$result .= str_pad($seconds, 2, '0', STR_PAD_LEFT); 

		return $result;
	}
	
	public function multiSummonerWinRate($summonerIds) {
		$matches = $this->sharedMatchWinRateBySummonerIds($summonerIds);
		$stats = [];
		$stats['wins'] = 0;
		$stats['losses'] = 0;
		foreach ($matches as $match) {
			if ($match->winner == 0) {
				$stats['losses']++;
			} elseif ($match->winner == 1) {
				$stats['wins']++;
			}
		}
		$stats['gamesPlayed'] = $stats['wins'] + $stats['losses'];
		return $stats;
	}
	
	public function getSummonerIdByName($name) {
		return $this->summonerByName($name, $cols='summonerId')->get()[0]->summonerId;
	}
	
	public function getAllSummonerNames() {
		return $this->summoners(['summonerId', 'name']);
	}
}