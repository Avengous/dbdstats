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
	
	protected function summonerByName($summonerName) {
		$query = DB::table('summoners')->select('*')->where([['name', '=', $summonerName]]);
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
}