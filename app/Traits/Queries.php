<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait Queries {
	
    protected function summonerIdByName($summonerName) {
		$query = DB::table('summoners')->select('summonerId')->where([['name', '=', $summonerName]]);
		$summonerId = $query->pluck('summonerId')[0];
		return $summonerId;
    }
	
    protected function summonerNameById($summonerId) {
		$query = DB::table('summoners')->select('name')->where([['summonerId', '=', $summonerId]]);
		$name = $query->pluck('name')[0];
		return $name;
    }
	
	protected function summonerByName($summonerName, $cols='*') {
		$query = DB::table('summoners')->select($cols)->where([['name', '=', $summonerName]]);
		return $query;
	}
	
	protected function recentMatches($summonerId, $matchCount=1) {
		$query = DB::table('match_details')->select('*')->where([['summonerId', '=', $summonerId]])->orderBy('matchCreation', 'desc')->take($matchCount);
		return $query;
	}
	
	protected function findUniqueMatchByIds($summonerId, $matchId) {
		$query = DB::table('match_details')->select('*')->where([['summonerId', '=', $summonerId], ['matchId', '=', $matchId]]);
		return $query;
	}
	
	protected function findRecordCount($table, $query) {
		$query = DB::table($table)->select('*')->where($query)->count();
	}
	
	protected function championNameById($championId) {
		$query = DB::Table('champions')->select('championName')->where([['championId', '=', $championId]])->pluck('championName')[0];
		return $query;
	}
	
	protected function totalDeathCount($summonerId) {
		return DB::Table('match_details')->select('deaths')->where([['summonerId', $summonerId]])->sum('deaths');
	}
	
	protected function sharedMatchWinRateBySummonerIds($summonerIds, $queueType) {
		$idString = "'".implode("','",$summonerIds);
		$queueString = "'".implode("','",$queueType);
		$query = DB::select(sprintf("SELECT matchId, winner FROM match_details a INNER JOIN ( SELECT matchId AS mid FROM match_details WHERE summonerId = %s ) b ON a.matchId = b.mid INNER JOIN ( SELECT matchId as countmid, count(id) AS count FROM match_details GROUP BY matchId HAVING count = 2 ) c ON a.matchId = c.countmid WHERE a.summonerId = %s AND a.queuetype IN (%s');", $summonerIds[0], $summonerIds[1], $queueString));
		return $query;
	}
	
	protected function summoners($cols='*') {
		return DB::Table('summoners')->select($cols)->get();
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
		}
	}
	
	protected function queueGroups($queue) {
		switch($queue) {
			case 'SOLO':
				return ['TEAM_BUILDER_RANKED_SOLO', 'RANKED_SOLO_5x5'];
			case 'FLEX':
				return ['RANKED_FLEX_SR'];
			case 'DYNAMIC':
				return ['TEAM_BUILDER_DRAFT_RANKED_5x5'];
			case 'TEAM':
				return ['RANKED_TEAM_5x5'];
			default:
				return [''];
		}
	}
	
	protected function arrayToCsvString($col, $val) {
		//
	}
	
	protected function championPlayedCount($summonerId, $queue=NULL, $season='') {
		$queueString = "AND %s = %s";
		$seasonString = '';
		$query = sprintf("SELECT championId, count(championId) FROM match_details WHERE summonerId = %s %s %s GROUP BY championId ORDER BY count(championId) DESC;", $summonerId, $queueString, $seasonString);
		
	}
	
	
}