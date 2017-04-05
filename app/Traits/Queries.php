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
	
	protected function sharedMatchWinRateBySummonerIds($summonerIds, $queueType=['TEAM_BUILDER_RANKED_SOLO', 'RANKED_SOLO_5x5', 'TEAM_BUILDER_DRAFT_RANKED_5x5']) {
		$matches = [];
		$query = DB::Table('match_details')->select(['winner', count('matchId'), 'summonerId'])->groupBy(['matchId'])->whereIn('summonerId', $summonerIds)->whereIn('queueType', $queueType)->havingRaw('COUNT(matchId) = 2')->get();
		
		//This is slow but unsure how else to do it.
		foreach ($query as $record) {
			
			if ($record) {
				//
			}
		}
		
		//array_push();
		//$query = DB::Table('match_details')->groupBy('matchId')->select(['winner', 'matchId', 'summonerId'])->havingRaw('COUNT(matchId) = 2')->whereIn('summonerId', $summonerIds)->whereIn('queueType', $queueType)->get();
		echo $query.'<br>'.'<br>';
		//->whereIn('summonerId', $summonerIds)
		return $query;
	}
	
	protected function summoners($cols='*') {
		return DB::Table('summoners')->select($cols)->get();
	}
}