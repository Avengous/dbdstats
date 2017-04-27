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
	
	public function recentMatchDetails($summonerName, $matchCount=100){
		$summonerId = $this->summonerIdByName($summonerName);
		return $this->recentMatches($summonerId, $matchCount)->paginate(10);
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
	
	public function getDuoStats($summonerIds) {
		$duoId = array_sum($summonerIds);
		if($this->recordExists('duo_stats', [['duoId', '=', $duoId]])) {
			$data = DB::table('duo_stats')->select('*')->where('duoId', $duoId)->get();
			return $data[0];
		} else {
			return false;
		}
	}
	
	public function getSummonerIdByName($name) {
		return $this->summonerByName($name, $cols='summonerId')->get()[0]->summonerId;
	}
	
	public function getAllSummonerNames() {
		return $this->summoners(['summonerId', 'name']);
	}
	
	protected function seasonGroups($season) {
		switch($season) {
			case 4:
				return ['SEASON2014'];
			case 5:
				return ['PRESEASON2015', 'SEASON2015'];
			case 6:
				return ['PRESEASON2016', 'SEASON2016'];
			case 7:
				return ['PRESEASON2017'];
			default:
				return ['SEASON2014', 'PRESEASON2015', 'SEASON2015', 'PRESEASON2016', 'SEASON2016', 'PRESEASON2017'];
		}
	}
	
	protected function queueGroups($queue) {
		$solo = ['TEAM_BUILDER_RANKED_SOLO', 'RANKED_SOLO_5x5'];
		$flex = ['RANKED_FLEX_SR'];
		$dynamic = ['TEAM_BUILDER_DRAFT_RANKED_5x5'];
		$team = ['RANKED_TEAM_5x5'];
		switch($queue) {
			case 'SOLO':
				return $solo;
			case 'FLEX':
				return $flex;
			case 'DYNAMIC':
				return $dynamic;
			case 'TEAM':
				return $team;
			case 'ALLTEAM':
				return ['RANKED_FLEX_SR', 'TEAM_BUILDER_DRAFT_RANKED_5x5', 'RANKED_TEAM_5x5'];
			default:
				return ['TEAM_BUILDER_RANKED_SOLO', 'RANKED_SOLO_5x5', 'RANKED_FLEX_SR', 'TEAM_BUILDER_DRAFT_RANKED_5x5', 'RANKED_TEAM_5x5'];
		}
	}
	
	public function getChampionsStats($summonerId=23486636, $queue=null, $season=null, $count=10) {
		if ($season == 6) {
			$queue = 'DYNAMIC';
		}
		$queues = $this->queueGroups($queue);
		$seasons = $this->seasonGroups($season);
		return $this->championPlayedCount($summonerId, $queues, $seasons, $count);
	}
	
	public function getLaneStats($summonerId, $queue=null, $season=null) {
		$queues = $this->queueGroups($queue);
		$seasons = $this->seasonGroups($season);
		$data = $this->rolePlayedCount($summonerId, $queues, $seasons);
		$roles = [];
		foreach ($data as $i) {
			//if ($data->)
		}
		
		return $data;
	}
	
	public function getMultiKills($summonerName, $multiKillCount=5) {
		return $this->tableSelectWhere('match_details', '*', [['summonerId', $this->getSummonerIdByName($summonerName)], ['largestMultiKill', $multiKillCount]]);
	}
}